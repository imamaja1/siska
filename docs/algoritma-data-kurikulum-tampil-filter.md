# Algoritma & Flowchart — Data Kurikulum `tampil_filter`

Alur: `admin/jurusan/kurikulum/data_kurikulum/tampil_filter/{kode}`

## Konteks alur

User memilih kurikulum di form filter `V_index` → POST `nama_kurikulum` → `filter()`
(`Data_kurikulum.php:36`) melakukan redirect ke `tampil_filter/{kode_nama_kurikulum}`
→ `tampil_filter()` (`Data_kurikulum.php:41`) memuat halaman tabel kurikulum.

## Algoritma `tampil_filter($kode_nama_kurikulum)`

```
1. MULAI
2. [Konstruktor - otentikasi]
   a. Jika session 'nama_login' kosong → redirect 'login/admin' → SELESAI
   b. rbac_cek('data_kurikulum', id_user); jika tidak punya akses
      → redirect 'denied' → SELESAI
3. kode_program_studi = getKodeProdiFromKurikulum(kode_nama_kurikulum)
      (query tabel nama_kurikulum → ambil kolom kode_program_studi)
4. Susun array $data:
   - content = 'admin/jurusan/kurikulum/Data_kurikulum/V_Data_kurikulum'
   - judul/sub_judul/title_h1/h2/h3 (label menu)
   - kode_nama_kurikulum = $kode_nama_kurikulum
   - nama_kurikulum = getNamaKurikulumById(kode)   (query get_byid)
   - data_matakuliah = getMatakuliahByProdi(kode_program_studi)
                         (m_matakuliah → get_matakuliah_byid_prodi → data mata kuliah)
   - data = getDataKurikulum(kode_nama_kurikulum)
              (m_data_kurikulum → get_data_kurikulum)
5. pilihan = getKompetensiPilihan(kode_program_studi)
   a. Query: kompetensi JOIN matakuliah_kompetensi
      WHERE kompetensi.kode_program_studi = kode_program_studi
   b. Jika jumlah hasil > 0:
      - mk_pilihan   = array_column(hasil, 'id_matakuliah')
      - nama_pilihan = array_column(hasil, 'nama', key=id_matakuliah)
      else: mk_pilihan=[] , nama_pilihan=[]
6. Set $data['mk_pilihan']   = pilihan['mk_pilihan']   (default [])
   Set $data['nama_pilihan'] = pilihan['nama_pilihan'] (default [])
7. load->view('admin/template/V_main', $data)
   → V_Data_kurikulum → include V_render_data (render tabel mata kuliah + data kurikulum)
8. SELESAI
```

## Flowchart (Mermaid)

```mermaid
flowchart TD
    A([MULAI]) --> B{Pilih kurikulum di form filter}
    B -->|POST nama_kurikulum| C[filter\\(\\) redirect ke tampil_filter/kode]
    C --> D{Session nama_login ada?}
    D -- Tidak --> E[redirect login/admin] --> Z([SELESAI])
    D -- Ya --> F{rbac_cek akses?}
    F -- Tidak --> G[redirect denied] --> Z
    F -- Ya --> H[kode_program_studi = getKodeProdiFromKurikulum kode]
    H --> I[Ambil nama_kurikulum by id]
    I --> J[Ambil data_matakuliah by prodi (mata kuliah)]
    J --> K[Ambil data kurikulum]
    K --> L[getKompetensiPilihan kode_program_studi]
    L --> M{Hasil kompetensi > 0?}
    M -- Ya --> N[mk_pilihan & nama_pilihan diisi dari array_column]
    M -- Tidak --> O[mk_pilihan & nama_pilihan = array kosong]
    N --> P
    O --> P[Gabung semua ke array $data]
    P --> Q[load view V_main -> V_Data_kurikulum -> V_render_data]
    Q --> Z
```

## Catatan

- `getKompetensiPilihan` (`KurikulumService.php:106`) memanggil
  `array_column($kompetensi,'nama','id_matakuliah')` untuk membangun map nama kompetensi
  per mata kuliah, dipakai view untuk menandai MK pilihan.
- Alur ini dipakai ulang oleh `render_data()` (`Data_kurikulum.php:142`) dan
  `excel()` (`Data_kurikulum.php:64`).
