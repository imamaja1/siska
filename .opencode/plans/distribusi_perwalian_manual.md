# Distribusi Perwalian Manual (checkbox) + Fix CSRF

## Latar belakang
Halaman `admin/pengaturan/distribusi_perwalian` saat ini hanya memiliki:
- Distribusi Random (prodi -> preview -> proses)
- Data & Hapus Perwalian

Permintaan: tambah **Distribusi Manual** dengan pembagian 2 kategori/tab:
1. **Tidak Ada Wali** — mahasiswa yang belum punya dosen wali; dicentang untuk diberikan wali.
2. **Ada Wali** — mahasiswa yang sudah punya wali; dicentang untuk dipindahkan ke dosen lain.

Target dosen: **satu dosen per proses** (dropdown dosen, centang banyak mahasiswa, simpan).
Kriteria dosen: `status_dosen='T'` DAN `status_login='A'` (sudah disesuaikan sebelumnya, konsisten dengan modul perwalian lain).

## Temuan penting
- Tabel `perwalian`: `nim` TIDAK unik (9114 baris / 9112 NIM unik, 2 duplikat). Harus ada guard anti-duplikat saat insert.
- **CSRF aktif** (`csrf_protection=TRUE`, token `csrf_siska`, `csrf_regenerate=FALSE`). Form & AJAX lama di `V_index.php` TIDAK mengirim token -> berisiko gagal POST. Perlu diperbaiki sekaligus.
- Model siap pakai: `get_dosen_by_homebase` (status_login='A'), `get_mahasiswa_belum_ada_dosen_wali`, `simpan`.
- Pola UI checkbox + check-all: `application/views/admin/akademik/kpat/kelas/partial/V_mahasiswa_no_kelas.php`.

## Perubahan

### 1. `application/models/jurusan/Perwalian_model.php`
Tambah method baru:
```php
public function get_mahasiswa_sudah_ada_dosen_wali($tahun_angkatan, $homebase)
{
    return $this->db->select('p.nim, m.nama_mahasiswa, p.kode_dosen, d.nama_dosen')
        ->from('perwalian as p')
        ->join('mahasiswa as m', 'm.nim=p.nim')
        ->join('dosen as d', 'd.kode_dosen=p.kode_dosen', 'left')
        ->where('m.program_studi_kode', $homebase)
        ->where('mid(p.nim,1,2)', $tahun_angkatan)
        ->order_by('p.nim', 'ASC')
        ->get()->result_object();
}

public function cek_perwalian_exists($nim)
{
    return (bool) $this->db->where('nim', $nim)->count_all_results($this->tabel);
}

public function ubah_dosen_wali($nim, $kode_dosen)
{
    return $this->db->where('nim', $nim)->update($this->tabel, array('kode_dosen' => $kode_dosen));
}
```

### 2. `application/controllers/admin/pengaturan/Distribusi_perwalian.php`
Tambah dua method:

**`manual_data()`** (AJAX POST):
- Input: `kode_program_studi` (int), `angkatan` (regex `^\d{2}$`).
- Ambil `$dosen = get_dosen_by_homebase($kode_program_studi)` (status_login='A').
- Ambil `$belum = get_mahasiswa_belum_ada_dosen_wali($angkatan, $kode_program_studi)`.
- Ambil `$sudah = get_mahasiswa_sudah_ada_dosen_wali($angkatan, $kode_program_studi)`.
- Build HTML:
  - `dosen_options`: `<option value="kode_dosen">nama_dosen</option>`
  - `table_belum`: tabel No / NIM / Nama / checkbox `name="nim_belum[]"` + header check-all.
  - `table_sudah`: tabel No / NIM / Nama / Dosen Wali Sekarang / checkbox `name="nim_sudah[]"` + header check-all.
- Balik JSON: `{ status, jumlah_dosen, jumlah_belum, jumlah_sudah, dosen_options, table_belum, table_sudah }`.

**`manual_proses()`** (POST form):
- Input: `kode_program_studi`, `angkatan`, `tipe` (`belum`|`sudah`), `kode_dosen`, `nim_belum[]`/`nim_sudah[]`.
- Ambil tahun akademik aktif -> `kode_tahun_akademik`.
- `tipe=belum`: loop NIM; `cek_perwalian_exists` -> skip jika ada; else `simpan(['nim','kode_dosen','kode_tahun_akademik'])`; hitung sukses.
- `tipe=sudah`: loop NIM; `ubah_dosen_wali($nim, $kode_dosen)`; hitung sukses.
- Flashdata sukses/gagal -> redirect `admin/pengaturan/distribusi_perwalian`.

### 3. `application/config/routes.php`
Tambah:
```php
$route['admin/pengaturan/distribusi_perwalian/manual_data'] = 'admin/pengaturan/Distribusi_perwalian/manual_data';
$route['admin/pengaturan/distribusi_perwalian/manual_proses'] = 'admin/pengaturan/Distribusi_perwalian/manual_proses';
```

### 4. `application/views/admin/pengaturan/distribusi_perwalian/V_index.php`
- Tambah box baru **"Distribusi Manual"** (di antara box Random dan box Hapus):
  - Select Program Studi + Select Tahun Angkatan (`angkatan_list`) + tombol "Tampilkan Mahasiswa".
  - `#hasil-manual` (hidden): nav-tabs 2 tab:
    - Tab "Tidak Ada Wali": form `#form-manual-belum` (CSRF hidden, hidden `tipe=belum`, dropdown dosen `#dosen_belum`, div `#table-belum`, tombol simpan).
    - Tab "Ada Wali": form `#form-manual-sudah` (CSRF hidden, hidden `tipe=sudah`, dropdown dosen `#dosen_sudah`, div `#table-sudah`, tombol simpan).
- **Fix CSRF lama**:
  - Tambah hidden `csrf_siska` di `#form-distribusi` dan `#form-hapus`.
  - Sertakan token di data AJAX `preview`, `view_data`, `preview_hapus` (baca dari hidden form).
- JS baru:
  - `#btn-tampilkan`: AJAX POST `manual_data` (termasuk token CSRF) -> isi `#dosen_belum`, `#dosen_sudah`, `#table-belum`, `#table-sudah`, badge count, tampilkan `#hasil-manual`.
  - Handler check-all untuk kedua tabel (`check-all-belum` -> `.check-belum`, `check-all-sudah` -> `.check-sudah`).
  - Submit kedua form dengan `confirm` (validasi dropdown dosen & minimal 1 centang).

## Verifikasi
- `php -l` semua file yang diubah.
- Test manual: pilih prodi+angkatan, centang mahasiswa, simpan; cek tabel `perwalian` via SQL.
- Sanity: pastikan Distribusi Random & Hapus tetap berfungsi setelah fix CSRF.