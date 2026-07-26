# Rencana Pengembangan API SISKA

**Sistem Informasi Akademik** — Universitas Bumigora

---

## Daftar Isi

- [1. Arsitektur](#1-arsitektur)
- [2. Struktur Module](#2-struktur-module)
- [3. Role & Hak Akses](#3-role--hak-akses)
- [4. Fitur Per Role](#4-fitur-per-role)
  - [4.1 Auth / Public](#41-auth--public)
  - [4.2 Admin](#42-admin)
  - [4.3 Dosen](#43-dosen)
  - [4.4 Mahasiswa](#44-mahasiswa)
- [5. Database Mapping](#5-database-mapping)
- [6. Standar Response API](#6-standar-response-api)
- [7. Prioritas Pengembangan](#7-prioritas-pengembangan)

---

## 1. Arsitektur

```
siska-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── Admin/
│   │   │   │   ├── Dosen/
│   │   │   │   ├── Mahasiswa/
│   │   │   │   └── PublicController.php
│   │   ├── Middleware/
│   │   │   ├── JwtMiddleware.php
│   │   │   ├── RoleMiddleware.php
│   │   │   └── ApiLogMiddleware.php
│   │   └── Resources/
│   ├── Models/
│   │   ├── Mahasiswa.php
│   │   ├── Dosen.php
│   │   ├── ProgramStudi.php
│   │   ├── Krs.php
│   │   ├── Khs.php
│   │   └── ...
│   └── Services/
│       ├── AuthService.php
│       ├── KrsService.php
│       ├── NilaiService.php
│       └── ...
├── routes/
│   └── api.php
├── database/
│   └── migrations/
└── docs/
    └── rencana-api.md
```

### Stack Rekomendasi

| Komponen | Pilihan |
|----------|---------|
| Framework | Laravel 10/11 |
| Auth API | Laravel Sanctum (token-based) atau JWT (tymon/jwt-auth) |
| Dokumentasi | Swagger OpenAPI (darkaonline/l5-swagger) |
| Response | JSON standar |
| Format Request | `application/json` |
| Pagination | `?page=1&per_page=20` |
| Filter | `?search=...&prodi=...&ta=...` |

---

## 2. Struktur Module

```
routes/api.php
├── public  → tidak perlu token
├── auth    → token role: admin | dosen | mahasiswa
├── admin   → token + middleware role:admin
├── dosen   → token + middleware role:dosen
└── mahasiswa → token + middleware role:mahasiswa
```

### Response Standar

```json
{
  "status": true,
  "code": 200,
  "message": "Berhasil",
  "data": {},
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100
  }
}
```

### Error Standar

```json
{
  "status": false,
  "code": 422,
  "message": "Validasi gagal",
  "errors": {
    "nim": ["NIM tidak ditemukan"]
  }
}
```

---

## 3. Role & Hak Akses

| Role | Scope | Auth |
|------|-------|------|
| `public` | Endpoint publik | Tanpa token |
| `admin` | Semua fitur admin | Token Sanctum |
| `dosen` | Penilaian, perwalian, presensi | Token Sanctum |
| `kaprodi` | Validasi nilai, rekap prodi | Token Sanctum |
| `dekan` | Validasi final dekan | Token Sanctum |
| `mahasiswa` | KRS, KHS, profil | Token Sanctum |

---

## 4. Fitur Per Role

### 4.1 Auth / Public

| Module | Method | Endpoint | Roles |
|--------|--------|----------|-------|
| **Auth** | POST | `/api/v1/auth/login` | public |
| | POST | `/api/v1/auth/logout` | all |
| | GET | `/api/v1/auth/me` | all |
| | POST | `/api/v1/auth/refresh` | all |
| | POST | `/api/v1/auth/lupa-password` | public |
| | POST | `/api/v1/auth/reset-password` | public |
| | PUT | `/api/v1/auth/ganti-sandi` | all |
| **Public** | GET | `/api/v1/cek-pembayaran/{nim}` | public |
| | GET | `/api/v1/provinsi` | public |

---

### 4.2 Admin

#### 4.2.1 Master Data

| Module | Method | Endpoint |
|--------|--------|----------|
| **Fakultas** | GET | `/api/v1/admin/fakultas` |
| | GET | `/api/v1/admin/fakultas/{kode_fakultas}` |
| | POST | `/api/v1/admin/fakultas` |
| | PUT | `/api/v1/admin/fakultas/{kode_fakultas}` |
| | DELETE | `/api/v1/admin/fakultas/{kode_fakultas}` |
| **Jurusan** | GET | `/api/v1/admin/jurusan` |
| | POST | `/api/v1/admin/jurusan` |
| | PUT | `/api/v1/admin/jurusan/{id}` |
| | DELETE | `/api/v1/admin/jurusan/{id}` |
| **Jenjang** | GET | `/api/v1/admin/jenjang` |
| | POST | `/api/v1/admin/jenjang` |
| | PUT | `/api/v1/admin/jenjang/{id}` |
| | DELETE | `/api/v1/admin/jenjang/{id}` |
| **Program Studi** | GET | `/api/v1/admin/program-studi` |
| | GET | `/api/v1/admin/program-studi/{kode}` |
| | POST | `/api/v1/admin/program-studi` |
| | PUT | `/api/v1/admin/program-studi/{kode}` |
| | DELETE | `/api/v1/admin/program-studi/{kode}` |
| **Tahun Akademik** | GET | `/api/v1/admin/tahun-akademik` |
| | POST | `/api/v1/admin/tahun-akademik` |
| | PUT | `/api/v1/admin/tahun-akademik/{kode}` |
| | DELETE | `/api/v1/admin/tahun-akademik/{kode}` |
| **Dosen** | GET | `/api/v1/admin/dosen` |
| | GET | `/api/v1/admin/dosen/{kode}` |
| | POST | `/api/v1/admin/dosen` |
| | PUT | `/api/v1/admin/dosen/{kode}` |
| | DELETE | `/api/v1/admin/dosen/{kode}` |
| **Mahasiswa** | GET | `/api/v1/admin/mahasiswa` |
| | GET | `/api/v1/admin/mahasiswa/{nim}` |
| | POST | `/api/v1/admin/mahasiswa` |
| | PUT | `/api/v1/admin/mahasiswa/{nim}` |
| | DELETE | `/api/v1/admin/mahasiswa/{nim}` |
| **Matakuliah** | GET | `/api/v1/admin/matakuliah` |
| | GET | `/api/v1/admin/matakuliah/{id}` |
| | POST | `/api/v1/admin/matakuliah` |
| | PUT | `/api/v1/admin/matakuliah/{id}` |
| | DELETE | `/api/v1/admin/matakuliah/{id}` |
| **Kurikulum** | GET | `/api/v1/admin/kurikulum` |
| | POST | `/api/v1/admin/kurikulum` |
| | PUT | `/api/v1/admin/kurikulum/{id}` |
| | DELETE | `/api/v1/admin/kurikulum/{id}` |
| **Nama Kurikulum** | GET | `/api/v1/admin/nama-kurikulum` |
| | POST | `/api/v1/admin/nama-kurikulum` |
| | PUT | `/api/v1/admin/nama-kurikulum/{id}` |
| **Kurikulum Angkatan** | GET | `/api/v1/admin/kurikulum-angkatan` |
| | POST | `/api/v1/admin/kurikulum-angkatan` |
| **Prasyarat MK** | GET | `/api/v1/admin/prasyarat` |
| | POST | `/api/v1/admin/prasyarat` |
| | DELETE | `/api/v1/admin/prasyarat/{id}` |
| **Ruang** | GET | `/api/v1/admin/ruang` |
| | POST | `/api/v1/admin/ruang` |
| | PUT | `/api/v1/admin/ruang/{kode}` |
| **Sesi/Shift** | GET | `/api/v1/admin/sesi` |
| | POST | `/api/v1/admin/sesi` |
| **Kompetensi** | GET | `/api/v1/admin/kompetensi` |
| | POST | `/api/v1/admin/kompetensi` |
| | PUT | `/api/v1/admin/kompetensi/{id}` |
| **Kaprodi** | GET | `/api/v1/admin/kaprodi` |
| | POST | `/api/v1/admin/kaprodi` |
| | PUT | `/api/v1/admin/kaprodi/{id}` |
| **Institusi** | GET | `/api/v1/admin/institusi` |
| | POST | `/api/v1/admin/institusi` |
| | PUT | `/api/v1/admin/institusi/{kode}` |

---

#### 4.2.2 Akademik

| Module | Method | Endpoint |
|--------|--------|----------|
| **KRS** | GET | `/api/v1/admin/krs` |
| | GET | `/api/v1/admin/krs/{kode}` |
| | PUT | `/api/v1/admin/krs/{kode}/validasi` |
| | DELETE | `/api/v1/admin/krs/{kode}` |
| | POST | `/api/v1/admin/krs/tambah-makul` |
| **KHS** | GET | `/api/v1/admin/khs` |
| | GET | `/api/v1/admin/khs/{nim}` |
| **Nilai** | GET | `/api/v1/admin/nilai` |
| | GET | `/api/v1/admin/nilai/{kelas_id}` |
| | POST | `/api/v1/admin/nilai/kenaikan` |
| **Cetak Nilai** | POST | `/api/v1/admin/cetak-nilai/uts/{kelas_id}` |
| | POST | `/api/v1/admin/cetak-nilai/uas/{kelas_id}` |
| | POST | `/api/v1/admin/cetak-nilai/akhir/{kelas_id}` |
| **Validasi Khusus** | GET | `/api/v1/admin/validasi-khusus` |
| | PUT | `/api/v1/admin/validasi-khusus/{id}` |
| **KKP** | GET | `/api/v1/admin/kkp` |
| | POST | `/api/v1/admin/kkp` |
| | PUT | `/api/v1/admin/kkp/nilai/{id}` |
| **Pembimbing KKP** | GET | `/api/v1/admin/pembimbing-kkp` |
| | POST | `/api/v1/admin/pembimbing-kkp` |
| **Kompetensi Mahasiswa** | GET | `/api/v1/admin/kompetensi-mahasiswa` |
| | POST | `/api/v1/admin/kompetensi-mahasiswa` |
| | PUT | `/api/v1/admin/kompetensi-mahasiswa/{id}` |
| **Konversi** | GET | `/api/v1/admin/konversi` |
| | POST | `/api/v1/admin/konversi` |
| | GET | `/api/v1/admin/konversi/{nim}` |
| **Hapus Makul** | DELETE | `/api/v1/admin/hapus-makul/{id_krs_detail}` |
| **Petikan Nilai** | GET | `/api/v1/admin/petikan-nilai` |
| | POST | `/api/v1/admin/petikan-nilai/cetak/{nim}` |
| **Status Perkuliahan** | GET | `/api/v1/admin/status-perkuliahan` |
| **Pembayaran Mahasiswa** | GET | `/api/v1/admin/pembayaran-mahasiswa` |
| **KPAT** | GET | `/api/v1/admin/kpat/kelas` |
| | GET | `/api/v1/admin/kpat/krs` |
| | GET | `/api/v1/admin/kpat/khs` |
| | GET | `/api/v1/admin/kpat/nilai` |
| | POST | `/api/v1/admin/kpat/kelas` |
| **Perubahan KRS** | GET | `/api/v1/admin/perubahan/semester-ini` |
| | GET | `/api/v1/admin/perubahan/semester-lalu` |
| | POST | `/api/v1/admin/perubahan/semester-ini` |

---

#### 4.2.3 Keuangan

| Module | Method | Endpoint |
|--------|--------|----------|
| **Pembayaran** | GET | `/api/v1/admin/pembayaran` |
| | GET | `/api/v1/admin/pembayaran/{nim}` |
| | POST | `/api/v1/admin/pembayaran` |
| **Block** | GET | `/api/v1/admin/block` |
| | POST | `/api/v1/admin/block` |
| | DELETE | `/api/v1/admin/block/{id}` |
| **Status Perkuliahan** | GET | `/api/v1/admin/status-perkuliahan` |
| | PUT | `/api/v1/admin/status-perkuliahan/{id}` |
| | POST | `/api/v1/admin/status-perkuliahan/import` |
| **Mahasiswa Aktif** | GET | `/api/v1/admin/mahasiswa-aktif` |

---

#### 4.2.4 Kuisioner

| Module | Method | Endpoint |
|--------|--------|----------|
| **Kuisioner** | GET | `/api/v1/admin/kuisioner` |
| | GET | `/api/v1/admin/kuisioner/hasil` |
| | GET | `/api/v1/admin/kuisioner/hasil/{kelas_id}` |
| | PUT | `/api/v1/admin/kuisioner/aktivasi` |
| | POST | `/api/v1/admin/kuisioner/export` |
| **Kelas Kuisioner** | GET | `/api/v1/admin/kuisioner/kelas` |
| | POST | `/api/v1/admin/kuisioner/kelas` |
| **Mengajar** | GET | `/api/v1/admin/kuisioner/mengajar` |
| | POST | `/api/v1/admin/kuisioner/mengajar` |

---

#### 4.2.5 Laporan

| Module | Method | Endpoint |
|--------|--------|----------|
| **Rekap IPK** | GET | `/api/v1/admin/laporan/rekap-ipk` |
| | POST | `/api/v1/admin/laporan/rekap-ipk/export` |
| **Aktif Perkuliahan** | GET | `/api/v1/admin/laporan/aktif-perkuliahan` |

---

#### 4.2.6 Pengguna & RBAC

| Module | Method | Endpoint |
|--------|--------|----------|
| **Pengguna** | GET | `/api/v1/admin/pengguna` |
| | POST | `/api/v1/admin/pengguna` |
| | PUT | `/api/v1/admin/pengguna/{kode}` |
| | DELETE | `/api/v1/admin/pengguna/{kode}` |
| **Ganti Sandi** | PUT | `/api/v1/admin/pengguna/ganti-sandi` |
| **Role** | GET | `/api/v1/admin/rbac/role` |
| | POST | `/api/v1/admin/rbac/role` |
| **Access** | GET | `/api/v1/admin/rbac/access` |
| | POST | `/api/v1/admin/rbac/access` |
| | DELETE | `/api/v1/admin/rbac/access/{id}` |

---

#### 4.2.7 Lainnya

| Module | Method | Endpoint |
|--------|--------|----------|
| **Double** | GET | `/api/v1/admin/double/krs` |
| | GET | `/api/v1/admin/double/khs` |
| | POST | `/api/v1/admin/double/resolve/{id}` |
| **MBKM** | GET | `/api/v1/admin/mbkm` |
| | POST | `/api/v1/admin/mbkm` |
| **Distribusi MK** | GET | `/api/v1/admin/distribusi-matakuliah` |
| **Konsentrasi** | GET | `/api/v1/admin/konsentrasi` |
| | POST | `/api/v1/admin/konsentrasi` |
| **Student Body** | GET | `/api/v1/admin/student-body` |
| **Penjadwalan** | GET | `/api/v1/admin/jadwal` |
| | POST | `/api/v1/admin/jadwal` |
| | PUT | `/api/v1/admin/jadwal/{kode}` |
| | DELETE | `/api/v1/admin/jadwal/{kode}` |

---

### 4.3 Dosen

| Module | Method | Endpoint |
|--------|--------|----------|
| **Dashboard** | GET | `/api/v1/dosen/dashboard` |
| **Kelas** | GET | `/api/v1/dosen/kelas` |
| | GET | `/api/v1/dosen/kelas/{id}/mahasiswa` |
| **Penilaian** | GET | `/api/v1/dosen/penilaian/{kelas_id}` |
| | PUT | `/api/v1/dosen/penilaian/{id_khs_detail}` |
| | POST | `/api/v1/dosen/penilaian/batch` |
| | POST | `/api/v1/dosen/penilaian/hitung/{kelas_id}` |
| **Update Nilai** | POST | `/api/v1/dosen/update-nilai` |
| **Cetak Nilai** | GET | `/api/v1/dosen/cetak-nilai/{kelas_id}` |
| **Penilaian KPAT** | GET | `/api/v1/dosen/penilaian-kpat/{kelas_id}` |
| | PUT | `/api/v1/dosen/penilaian-kpat/{id}` |
| **Presensi** | GET | `/api/v1/dosen/presensi/{jadwal_id}` |
| | POST | `/api/v1/dosen/presensi` |
| **Perwalian** | GET | `/api/v1/dosen/perwalian` |
| | GET | `/api/v1/dosen/perwalian/mahasiswa-bimbingan` |
| **Konsultasi** | GET | `/api/v1/dosen/konsultasi` |
| | GET | `/api/v1/dosen/konsultasi/{id}` |
| | PUT | `/api/v1/dosen/konsultasi/{id}/tanggapan` |
| **Bimbingan KKP** | GET | `/api/v1/dosen/bimbingan-kkp` |
| | PUT | `/api/v1/dosen/bimbingan-kkp/{id}/nilai` |
| **Kurikulum** | GET | `/api/v1/dosen/kurikulum` |
| **Prasyarat** | GET | `/api/v1/dosen/prasyarat` |
| **Bidang Ilmu** | GET | `/api/v1/dosen/bidang-ilmu` |
| **MBKM** | GET | `/api/v1/dosen/mbkm` |
| **Validasi Khusus** | GET | `/api/v1/dosen/validasi-khusus` |
| | PUT | `/api/v1/dosen/validasi-khusus/{id}` |
| **Obrolan** | GET | `/api/v1/dosen/obrolan` |
| | POST | `/api/v1/dosen/obrolan` |
| **Ganti Sandi** | PUT | `/api/v1/dosen/ganti-sandi` |

#### 4.3.1 Kaprodi

| Module | Method | Endpoint |
|--------|--------|----------|
| **Dashboard** | GET | `/api/v1/kaprodi/dashboard` |
| **Validasi Nilai** | GET | `/api/v1/kaprodi/validasi-nilai` |
| | GET | `/api/v1/kaprodi/validasi-nilai/{kelas_id}` |
| | PUT | `/api/v1/kaprodi/validasi-nilai/{kelas_id}/prodi` |
| **Validasi KPAT** | GET | `/api/v1/kaprodi/validasi-nilai-kpat` |
| | PUT | `/api/v1/kaprodi/validasi-nilai-kpat/{kelas_id}/prodi` |
| **Kelas** | GET | `/api/v1/kaprodi/kelas` |
| | GET | `/api/v1/kaprodi/kelas/{id}` |
| **Mahasiswa** | GET | `/api/v1/kaprodi/mahasiswa` |
| **IPK** | GET | `/api/v1/kaprodi/ipk` |
| **Aktif Perkuliahan** | GET | `/api/v1/kaprodi/aktif-perkuliahan` |
| **MBKM** | GET | `/api/v1/kaprodi/mbkm` |
| **KRSAN** | GET | `/api/v1/kaprodi/krsan` |
| **KPAT** | GET | `/api/v1/kaprodi/kpat` |
| **UMB** | GET | `/api/v1/kaprodi/umb` |
| **Update Penilaian** | PUT | `/api/v1/kaprodi/update-penilaian` |
| **Konsultasi** | GET | `/api/v1/kaprodi/konsultasi` |

#### 4.3.2 Dekan

| Module | Method | Endpoint |
|--------|--------|----------|
| **Dashboard** | GET | `/api/v1/dekan/dashboard` |
| **Validasi Nilai** | GET | `/api/v1/dekan/validasi-nilai` |
| | GET | `/api/v1/dekan/validasi-nilai/{kelas_id}` |
| | PUT | `/api/v1/dekan/validasi-nilai/{kelas_id}/dekan` |
| **Validasi KPAT** | GET | `/api/v1/dekan/validasi-nilai-kpat` |
| | PUT | `/api/v1/dekan/validasi-nilai-kpat/{kelas_id}/dekan` |
| **Update Penilaian** | PUT | `/api/v1/dekan/update-penilaian` |

---

### 4.4 Mahasiswa

| Module | Method | Endpoint |
|--------|--------|----------|
| **Dashboard** | GET | `/api/v1/mahasiswa/dashboard` |
| **KRS** | GET | `/api/v1/mahasiswa/krs` |
| | GET | `/api/v1/mahasiswa/krs/{kode}` |
| | POST | `/api/v1/mahasiswa/krs` |
| | POST | `/api/v1/mahasiswa/krs/tambah/{id_matakuliah}` |
| | DELETE | `/api/v1/mahasiswa/krs/hapus/{id_krs_detail}` |
| | GET | `/api/v1/mahasiswa/krs/cek-block` |
| | GET | `/api/v1/mahasiswa/krs/matakuliah-tersedia` |
| | GET | `/api/v1/mahasiswa/krs/sks-tersedia` |
| **KHS** | GET | `/api/v1/mahasiswa/khs` |
| | GET | `/api/v1/mahasiswa/khs/{ta}` |
| **Profil** | GET | `/api/v1/mahasiswa/profil` |
| | PUT | `/api/v1/mahasiswa/profil` |
| **Kurikulum** | GET | `/api/v1/mahasiswa/kurikulum` |
| **Petikan Nilai** | GET | `/api/v1/mahasiswa/petikan-nilai` |
| | POST | `/api/v1/mahasiswa/petikan-nilai/cetak` |
| **Prasyarat** | GET | `/api/v1/mahasiswa/prasyarat` |
| **Kompetensi** | GET | `/api/v1/mahasiswa/kompetensi` |
| | POST | `/api/v1/mahasiswa/kompetensi` |
| **Kuisioner** | GET | `/api/v1/mahasiswa/kuisioner` |
| | GET | `/api/v1/mahasiswa/kuisioner/soal` |
| | POST | `/api/v1/mahasiswa/kuisioner/jawab` |
| **Kalender** | GET | `/api/v1/mahasiswa/kalender` |
| **Obrolan** | GET | `/api/v1/mahasiswa/obrolan` |
| | POST | `/api/v1/mahasiswa/obrolan` |
| **Ganti Sandi** | PUT | `/api/v1/mahasiswa/ganti-sandi` |

---

## 5. Database Mapping

### 5.1 Entity → Model Laravel

| Tabel | Model | Key |
|-------|-------|-----|
| `mahasiswa` | `Mahasiswa` | `nim` (PK) |
| `dosen` | `Dosen` | `kode_dosen` (PK) |
| `program_studi` | `ProgramStudi` | `kode_program_studi` |
| `jurusan` | `Jurusan` | `id_jurusan` |
| `jenjang` | `Jenjang` | `id_jenjang` |
| `fakultas` | `Fakultas` | `kode_fakultas` |
| `institusi` | `Institusi` | `kode_institusi` |
| `tahun_akademik` | `TahunAkademik` | `kode_tahun_akademik` |
| `matakuliah` | `Matakuliah` | `id_matakuliah` |
| `kurikulum` | `Kurikulum` | `kode_kurikulum` |
| `nama_kurikulum` | `NamaKurikulum` | `kode_nama_kurikulum` |
| `krs` | `Krs` | `kode_krs` |
| `krs_detail` | `KrsDetail` | `kode_krs_detail` |
| `khs` | `Khs` | `kode_khs` |
| `khs_detail` | `KhsDetail` | `kode_khs_detail` |
| `kelas` | `Kelas` | `kelas_id` |
| `kelas_mahasiswa` | `KelasMahasiswa` | `kelas_mahasiswa_id` |
| `mengajar` | `Mengajar` | `mengajar_id` |
| `penjadwalan` | `Penjadwalan` | `kode_penjadwalan` |
| `ruang` | `Ruang` | `kode_ruang` |
| `sesi` | `Sesi` | `kode_sesi` |
| `presensi` | `Presensi` | `kode_presensi` |
| `pembayaran` | `Pembayaran` | `id` |
| `status_perkuliahan` | `StatusPerkuliahan` | `kode_status_perkuliahan` |
| `block` | `Block` | `id` |
| `pembimbing_kkp` | `PembimbingKkp` | `id_pembimbing_kkp` |
| `nilai_kkp` | `NilaiKkp` | `id_nilai` |
| `kompetensi` | `Kompetensi` | `kode_kompetensi` |
| `kuisioner` | `Kuisioner` | `kuisioner_id` |
| `pengguna` | `Pengguna` | `kode_pengguna` |
| `role` | `Role` | `id_role` |
| `access` | `Access` | `id_access` |
| `kaprodi` | `Kaprodi` | `kode_kaprodi` |
| `perwalian` | `Perwalian` | `kode_perwalian` |
| `konsultasi_perwalian` | `KonsultasiPerwalian` | `kode_konsultasi_perwalian` |
| `mbkm` | `Mbkm` | `id` |
| `sistem_penilaian` | `SistemPenilaian` | `kode_sistem_penilaian` |
| `sistem_penilaian_detail` | `SistemPenilaianDetail` | `kode_sistem_penilaian_detail` |
| `persentasi_nilai_dosen` | `PersentasiNilaiDosen` | `id` |
| `bidang_ilmu` | `BidangIlmu` | `id_bidang_ilmu` |

### 5.2 Relasi Utama

```
institusi -> jurusan -> program_studi -> mahasiswa
fakultas -> program_studi
program_studi -> matakuliah -> kurikulum
program_studi -> dosen (homebase)

mahasiswa -> krs -> krs_detail -> khs_detail
krs_detail -> kelas_mahasiswa -> kelas -> matakuliah
kelas -> mengajar -> dosen

mahasiswa -> perwalian -> dosen
mahasiswa -> konsultasi_perwalian -> dosen
mahasiswa -> pembimbing_kkp -> dosen -> nilai_kkp
mahasiswa -> status_perkuliahan
mahasiswa -> pembayaran
mahasiswa -> kompetensi_mahasiswa -> kompetensi

kelas -> kelas_validasi -> catatan_revisi
kelas -> persentasi_nilai_dosen
kurikulum -> sistem_penilaian -> sistem_penilaian_detail
```

---

## 6. Standar Response API

### 6.1 Sukses

```json
{
  "status": true,
  "code": 200,
  "message": "Data berhasil dimuat",
  "data": {}
}
```

### 6.2 List dengan Pagination

```json
{
  "status": true,
  "code": 200,
  "message": "Data berhasil dimuat",
  "data": [],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100,
    "from": 1,
    "to": 20
  }
}
```

### 6.3 Error

```json
{
  "status": false,
  "code": 422,
  "message": "Validasi gagal",
  "errors": {
    "nim": ["NIM tidak ditemukan"],
    "nama": ["Nama harus diisi"]
  }
}
```

### 6.4 Kode HTTP

| Kode | Keterangan |
|------|------------|
| 200 | OK |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized (token tidak valid) |
| 403 | Forbidden (role tidak sesuai) |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Internal Server Error |

---

## 7. Prioritas Pengembangan

### Fase 1 — Core MVP (Minggu 1-2)
Target: Auth + master data + KRS + KHS

```
[Auth]      Login, logout, me, refresh token
[Master]    Program studi, tahun akademik, mahasiswa, dosen, matakuliah
[Admin]     KRS, KHS, nilai
[Mahasiswa] KRS (ambil/batal), KHS (lihat)
[Dosen]     Kelas, input nilai
```

### Fase 2 — Akademik Lengkap (Minggu 3-4)
Target: Validasi, cetak, kompetensi, KKP, perwalian

```
[Admin]     Validasi, cetak nilai, kompetensi, KKP, konversi, petikan
[Kaprodi]   Validasi nilai prodi
[Dekan]     Validasi nilai dekan
[Dosen]     Perwalian, konsultasi, bimbingan KKP
[Mahasiswa] Kuisioner, petikan nilai
```

### Fase 3 — Keuangan & Laporan (Minggu 5)
```
[Admin]     Pembayaran, block, status perkuliahan
[Admin]     Laporan rekap IPK, aktif perkuliahan
[Admin]     RBAC, pengguna
```

### Fase 4 — Lengkap (Minggu 6)
```
[Admin]     KPAT, MBKM, perubahan KRS, penjadwalan, presensi
[Dosen]     Presensi, KPAT, MBKM
[Mahasiswa] Kalender, obrolan
```

---

## Lampiran

### Referensi Controller CI ke Module Laravel

| CI Controller | Module Laravel | Keterangan |
|--------------|----------------|------------|
| `admin/akademik/Mahasiswa` | `Admin/Akademik/MahasiswaController` | |
| `admin/akademik/Krs` | `Admin/Akademik/KrsController` | |
| `admin/akademik/Khs` | `Admin/Akademik/KhsController` | |
| `admin/akademik/Nilai` | `Admin/Akademik/NilaiController` | |
| `admin/jurusan/Dosen` | `Admin/Jurusan/DosenController` | |
| `admin/jurusan/kurikulum/Matakuliah` | `Admin/Jurusan/Kurikulum/MatakuliahController` | |
| `dosen/Penilaian` | `Dosen/PenilaianController` | |
| `dosen/Perwalian` | `Dosen/PerwalianController` | |
| `dosen/kaprodi/Validasinilai` | `Dosen/Kaprodi/ValidasiNilaiController` | |
| `dosen/dekan/ValidasiNilai` | `Dosen/Dekan/ValidasiNilaiController` | |
| `mahasiswa/Krs` | `Mahasiswa/KrsController` | |
| `mahasiswa/Khs` | `Mahasiswa/KhsController` | |
| `Login` | `Api/AuthController` | Refactor ke token-based |
| `Api` | `Api/PublicController` | |
