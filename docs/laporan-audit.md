# LAPORAN AUDIT KODE SISKA UBG
**Tanggal:** 23 Juli 2026

---

## RINGKASAN EKSEKUTIF

| Kategori | Kritis | Tinggi | Menengah/Sedang | Rendah | **Total** |
|----------|:--------:|:--------:|:-----------------:|:--------:|:----------:|
| **Keamanan** | 6 | 0 | 9 | 9 | **24** |
| **Bug Penilaian** | 6 | 5 | 4 | 2 | **17** |
| **Kualitas Kode** | 3 | 8 | 6 | 8 | **25** |
| **TOTAL** | **15** | **13** | **19** | **19** | **66** |

---

## 🔴 KRITIS — HARUS SEGERA DIPERBAIKI (15 temuan)

### Keamanan (6)

| # | Temuan | Lokasi |
|---|--------|--------|
| K1 | **Password DB plaintext** `eeebb69480ec78` | `config/database.php:12` |
| K2 | **encryption_key kosong** | `config/config.php:328` |
| K3 | **CSRF protection mati** | `config/config.php:474` |
| K4 | **SQL Injection via string concatenation** (banyak file) | `admin/akademik/Nilai.php`, `dosen/Botdosen.php`, dll |
| K5 | **Token Telegram bot hardcoded** `AAH2CxbnErR25yJebFdGaxFzrpIaiKh2OgA` | `dosen/Botdosen.php:34` |
| K6 | **File penanda deface** `Miyomar1337.txt` | Root |

### Bug Penilaian (6)

| # | Temuan | Lokasi |
|---|--------|--------|
| B1 | **`nilai_validasi_telat()` fungsi tidak ada** — dipanggil 12x, fatal error | `coba_helper.php` (missing) |
| B2 | **`$data1['time']` overwrite object jadi string** — view crash | `Penilaian.php:1031` |
| B3 | **`$validasi_prodi_uts` undefined** — validasi UTS tidak jalan | `Penilaian.php:1546,1555` |
| B4 | **`$this->kelas()` tidak ada** — kaprodi crash | `Validasinilai.php:169` |
| B5 | **`dum.id_kelas` subquery tanpa join** — dekan crash | `ValidasiNilai.php:42,468` |
| B6 | **`$prodi` undefined** (seharusnya `$dekan`) — dekan crash | `ValidasiNilai.php:492` |

### Kualitas Kode (3)

| # | Temuan | Lokasi |
|---|--------|--------|
| Q1 | **Typo `sFtus_dosen`** — runtime bug | `V_nilai_revisi.php:43` |
| Q2 | **SQL `and AND` double keyword** — query error | `admin/akademik/Krs.php:51` |
| Q3 | **View dengan SQL injection raw query** | `V_bidang_ilmu.php:50` |

---

## 🟠 TINGGI (13 temuan)

### Bug Penilaian (5)

| # | Temuan | Lokasi |
|---|--------|--------|
| B7 | **`nilai_validasi_dosen_uas()` return numeric timestamp** bukan label | `coba_helper.php:787` |
| B8 | **Trailing space WHERE column name** `'validasi_prodi '` / `'validasi_dekan '` | `coba_helper.php:822-823` |
| B9 | **Insert `isian_uts` untuk flow UAS** (seharusnya `isian`) | `Penilaian.php:1655` |
| B10 | **`cari_kelas()` missing columns** `singkatan_program_studi`, `validasi_dekan` | `Validasinilai.php:171` |
| B11 | **`$message_text` used before assignment** | `Validasinilai.php:337` |

### Kualitas Kode (8)

| # | Temuan | Lokasi |
|---|--------|--------|
| Q4 | **N+1 query problem** — 8+ lokasi, 400+ query per halaman | `Penilaian.php`, `Krs_model.php` |
| Q5 | **View melakukan query DB** — MVC violation | `V_menu_1.php`, `V_cetak_all.php`, `V_bidang_ilmu.php` |
| Q6 | **Database dump `sql_siska_ubg_ac_id.sql` di root web** | Root |
| Q7 | **Try-catch `PDOException` kosong tanpa logging** — silent failure | `Penilaian.php:1432-1676` |
| Q8 | **File backup/zip di source** (8+ file) | `controllers/`, `views/`, `libraries/` |
| Q9 | **`$row` di luar foreach loop** — undefined behavior | `V_nilai_revisi.php:187` |
| Q10 | **`defined('BASEPATH')` missing** | `Penilaian.php:1` |
| Q11 | **`nilai_validasi_prodi_uas/dekan_uas` logika tanggal TERBALIK** | `coba_helper.php:806,826` |

---

## 🟡 MENENGAH/SEDANG (19 temuan)

### Keamanan

| # | Temuan |
|---|--------|
| M1 | `track_login_attempts` disabled (brute-force protection mati) |
| M2 | bcrypt `default_rounds = 8` (rendah, minimal 10-12) |
| M3 | `phpinfo.php` terekspos publik |
| M4 | 12 file debugging (`test_*.php`, `cek_*.php`) ekspos data sensitif |
| M5 | `.htaccess` tidak melindungi `system/` dan `application/` |
| M6 | Global XSS filtering = FALSE |
| M7 | Penggunaan `$_POST` langsung tanpa `$this->input->post()` |
| M8 | File index cadangan + CentOS-WebPanel terindikasi |
| M9 | `application/Home.php` di luar folder controllers |

### Bug

| # | Temuan |
|---|--------|
| B12 | `nama_kurikulum()` return object, bukan string |
| B13 | WHERE `kode_dosen = 1` tidak tepat (seharusnya string kode) |
| B14 | INNER JOIN `persentasi_nilai_dosen` bisa gagal jika belum diisi |
| B15 | Pesan Telegram: "Nilai UTS" padahal flow UAS |

### Kualitas Kode

| # | Temuan |
|---|--------|
| Q12 | `Penilaian.php` 2321 baris & `Krs.php` 1887 baris — terlalu besar |
| Q13 | 7 lokasi `if(false)` dead code |
| Q14 | Method testing `syarat()` dengan hardcoded data & `print_r()` |
| Q15 | `nilai_mahasiswa_uas_exp()` tidak me-render view (dead code) |
| Q16 | 6+ pola duplikasi kode besar (IPK, pesan, cetak) |

---

## 🟢 RENDAH (19 temuan)

### Keamanan

| # | Temuan |
|---|--------|
| L1 | `db_debug` bergantung ENVIRONMENT |
| L2 | Session `sess_match_ip = FALSE` |
| L3 | `log_threshold = 0` (no error logging) |
| L4 | `forgot_password_expiration = 0` (tidak kadaluarsa) |
| L5 | Google Site Verification, `index3.html`, file tidak perlu lainnya |

### Bug

| # | Temuan |
|---|--------|
| B16 | Typo `kode_nama_kuirkulum` di function_exists guard |
| B17 | Typo `ut-8` seharusnya `utf-8` di mPDF |

### Kualitas Kode

| # | Temuan |
|---|--------|
| Q17 | `udpate_*` typo di nama method |
| Q18 | `conten` vs `content` inkonsistensi (mahasiswa vs admin) |
| Q19 | `pecentase` vs `persentasi` vs `persentase` inkonsistensi |
| Q20 | `DapaT` / `Dapar` typo |
| Q21 | `title_h` typo berulang di ~100+ file |
| Q22 | `$this->$tahun_akademik` double dollar (bug potensial) |
| Q23 | `# code...` placeholder comment |
| Q24 | ~10 ZIP/RAR file di source tree |

---

## PRIORITAS PERBAIKAN (Urutan)

1. **Hapus `Miyomar1337.txt`** + investigasi backdoor
2. **Revoke Telegram token**, generate baru
3. **Set `encryption_key`** + generate random 32-byte
4. **Aktifkan CSRF** 
5. **Fix semua SQL injection** — gunakan Query Builder/parameter binding
6. **Fix 6 bug kritis penilaian** (fungsi hilang, undefined vars, subquery tanpa join)
7. **Pindahkan password DB ke .env**
8. **Proteksi `application/` dan `system/`** dengan `.htaccess`
9. **Hapus 12 file debugging** di root
10. **Hapus `phpinfo.php`**
11. **Hapus `sql_siska_ubg_ac_id.sql` dari root web**
12. **Fix N+1 queries** + duplikasi kode
13. **Fix `track_login_attempts`** + bcrypt rounds
14. **Hapus file backup/zip** dari source
15. **Aktifkan `log_threshold = 1`**

---

## CATATAN

Audit dilakukan pada CodeIgniter 3 dengan database MySQL. Semua temuan berdasarkan analisis statis kode sumber tanpa melakukan penetration testing aktif.
