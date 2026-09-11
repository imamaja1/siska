# Hapus Mahasiswa per Angkatan — HANYA baris mahasiswa (tanpa hapus KRS & lainnya)

## Keputusan pengguna
- Lokasi: box baru di `admin/pengaturan/distribusi_perwalian` (di bawah box "Data & Hapus Perwalian").
- Cakupan: **Program Studi + Tahun Angkatan**.
- Konfirmasi: Preview/Count lalu wajib **ketik `HAPUS`**.
- **Scope penghapusan: HANYA tabel `mahasiswa`. KRS, konsultasi, status_perkuliahan, kuisioner, dsb. TIDAK dihapus.**

## Kendala teknis (hasil riset)
Tabel terkait `mahasiswa.nim`:
- **Tanpa FK** → otomatis TIDAK ikut terhapus (tetap orphan): `krs`, `konsultasi_perwalian`, `krs_perubahan`, `krs_temp`, `status_perkuliahan`, `pembayaran`, `block`, `mbkm`, `log_aktivitas_nilai`.
- **FK CASCADE** → AKAN ikut terhapus jika FK aktif: `perwalian`, `presensi`, `kompetensi_mahasiswa`, `pembimbing_kkp` (beserta cascade-nya: presensi_detail, kompetensi_mahasiswa_detail, nilai_kkp).
- **FK NO ACTION** → memblokir hapus: `kuisioner_layanan` (29.848 baris utk contoh prodi 2/angkatan 25).

Karena itu, agar **hanya `mahasiswa` yang terhapus**, hapus dilakukan dengan `SET FOREIGN_KEY_CHECKS=0` dalam transaksi, lalu dikembalikan ke `1`. Efek: seluruh baris terkait di 14 tabel menjadi **orphan** (referensi NIM yang sudah tidak ada) dan TIDAK dihapus sama sekali — sesuai permintaan.

## Perubahan

### 1. `application/models/akademik/Mahasiswa_model.php`
Tambah:
- `get_nim_by_angkatan_prodi($kode_program_studi, $angkatan)` → array nim.
- `count_related_per_angkatan($kode_program_studi, $angkatan)` → array jumlah baris terkait per tabel (INFORMATIF, hanya untuk preview — tidak dihapus).
- `hapus_mahasiswa_per_angkatan($kode_program_studi, $angkatan)` → transaksi:
  1. Ambil nims.
  2. `SET FOREIGN_KEY_CHECKS=0`.
  3. `DELETE FROM mahasiswa WHERE nim IN (...)` (hanya ini).
  4. `SET FOREIGN_KEY_CHECKS=1`.
  5. Kembalikan `['status'=>trans_status, 'hapus'=>n]`.

### 2. `application/controllers/admin/pengaturan/Distribusi_perwalian.php`
- Load `'akademik/Mahasiswa_model'` di constructor.
- `preview_hapus_mahasiswa()` (AJAX POST): JSON `{status, mahasiswa, related_counts}` — menampilkan jumlah mahasiswa + peringatan jumlah baris terkait yang AKAN menjadi orphan (tidak dihapus).
- `hapus_mahasiswa()` (POST): validasi prodi+angkatan; wajib `konfirmasi === 'HAPUS'`; panggil `hapus_mahasiswa_per_angkatan`; flashdata jumlah terhapus → redirect.

### 3. `application/config/routes.php`
```
admin/pengaturan/distribusi_perwalian/preview_hapus_mahasiswa
admin/pengaturan/distribusi_perwalian/hapus_mahasiswa
```

### 4. `application/views/admin/pengaturan/distribusi_perwalian/V_index.php`
- Box baru **"Hapus Mahasiswa per Angkatan"** (box-danger):
  - Select Program Studi + Tahun Angkatan.
  - Tombol "Preview Hapus Mahasiswa" (AJAX + token CSRF).
  - Hasil preview: jumlah mahasiswa yang akan dihapus + peringatan orphan (baris terkait tetap tersisa, tidak dihapus).
  - Input teks "Ketik `HAPUS`" → tombol "Hapus Mahasiswa" aktif; form POST ke `hapus_mahasiswa` + hidden prodi/angkatan + token CSRF.

### 5. Keamanan
- RBAC class sama; semua POST menyertakan token CSRF.

## Verifikasi
- `php -l` file yang diubah.
- Uji query preview counts.
- Tidak uji hapus aktual; review transaksi + FK checks.