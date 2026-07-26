akademik
├── Akademik/
│   ├── MahasiswaController      → CRUD, detail, cari, export
│   ├── KrsController            → Kelola KRS, validasi, hapus MK
│   ├── KhsController            → Lihat KHS, filter, export Excel
│   ├── NilaiController           → Kelola nilai, validasi, kenaikan
│   ├── CetakNilaiController      → Cetak daftar nilai (UTS/UAS/Final) + QR
│   ├── ValidasiKhususController  → Validasi final (dekan)
│   ├── KkpController            → Kelola KKP, input nilai KKP
│   ├── PembimbingKkpController  → Assign pembimbing KKP
│   ├── KompetensiController     → Kompetensi mahasiswa
│   ├── KonversiController       → Konversi nilai pindahan
│   ├── HapusMakulController     → Hapus MK dari transkrip
│   ├── PetikanNilaiController   → Cetak petikan nilai (PDF)
│   ├── StatusPerkuliahanController → Rekap status
│   ├── PembayaranMahasiswaController → Rekap pembayaran SKS
│   ├── Kpat/
│   │   ├── KelasController      → Kelola kelas KPAT
│   │   ├── KrsController        → KRS jalur KPAT
│   │   ├── KhsController        → KHS jalur KPAT
│   │   └── NilaiController      → Nilai jalur KPAT
│   └── Perubahan/
│       ├── SemesterIniController    → Perubahan KRS semester berjalan
│       └── SemesterLaluController   → Perubahan KRS semester lalu
│
├── Jurusan/
│   ├── DosenController          → CRUD dosen
│   ├── TahunAkademikController   → CRUD tahun akademik
│   ├── PerwalianController      → Assign dosen wali
│   ├── KonsultasiPerwalianController → Monitoring konsultasi
│   ├── DistribusiMatakuliahController → Distribusi MK per dosen
│   ├── KonsentrasiController    → Kelola konsentrasi
│   ├── StudentBodyController    → Data mahasiswa per prodi
│   ├── Kurikulum/
│   │   ├── MatakuliahController     → CRUD matakuliah
│   │   ├── DataKurikulumController  → Susunan kurikulum
│   │   ├── NamaKurikulumController  → Nama kurikulum
│   │   ├── KurikulumAngkatanController → Kurikulum per angkatan
│   │   └── MatakuliahPrasyaratController → Prasyarat MK
│   ├── ProgramStudi/
│   │   ├── NamaJurusanController    → CRUD program studi
│   │   ├── KodeJurusanController    → CRUD jurusan
│   │   ├── JenjangController        → CRUD jenjang (S1/D3)
│   │   ├── KetuaJurusanController   → Assign kaprodi
│   │   └── KompetensiController     → CRUD kompetensi prodi
│   └── Universitas/
│       └── FakultasController       → CRUD fakultas
│
├── Keuangan/
│   ├── PembayaranController     → Rekap pembayaran SPP/SKS/Lab
│   ├── BlockController          → Blokir/buka blokir mahasiswa
│   ├── StatusPerkuliahanController → Kelola status (aktif/cuti/DO)
│   └── MahasiswaAktifController  → Daftar mahasiswa aktif
│
├── Kuisioner/
│   ├── KuisionerController      → Aktivasi, hasil, export
│   ├── KelasController          → CRUD kelas kuisioner
│   └── MengajarController       → Assign mengajar dosen
│
├── Laporan/
│   ├── RekapIpkController       → Rekap IPK per prodi (Excel)
│   └── AktifPerkuliahanController → Laporan mahasiswa aktif
│
├── Pengguna/
│   ├── PenggunaController       → CRUD users admin
│   └── GantiSandiController     → Ganti password admin
│
├── MbkmController               → Daftar peserta MBKM
├── DoubleController              → Deteksi duplikasi KRS/KHS
├── TambahMakulController         → Manual entry KRS/KHS
├── RbacController                → Atur akses role ke controller
└── HomeController                → Dashboard admin

Dosen/
├── PenilaianController           → Input nilai harian/uts/uas
├── UpdateNilaiController         → Update nilai massal
├── CetakNilaiController          → Cetak nilai kelas
├── PerwalianController           → Kelola mahasiswa bimbingan
├── KonsultasiPerwalianController → Tanggapi konsultasi mahasiswa
├── AbsensiKehadiranController    → Input presensi mahasiswa
├── BimbinganKkpController        → Bimbingan KKP + input nilai
├── KurikulumController           → Lihat kurikulum
├── MatakuliahPrasyaratController → Lihat prasyarat MK
├── BidangIlmuController          → Kelola bidang ilmu
├── PenilaianKpatController       → Nilai jalur KPAT
├── ValidasikhususController      → Validasi khusus
├── MbkmController                → MBKM
├── ObrolanController             → Chat dengan mahasiswa
├── BotdosenController            → Bot/chatbot
└── GantiSandiController          → Ganti password
Dosen/
└──Kaprodi/                          → (sub-role, akses terbatas)
  ├── MahasiswaController           → Data mahasiswa prodi
  ├── KelasController               → Kelas prodi
  ├── IpkController                 → Rekap IPK mahasiswa
  ├── AktifPerkuliahanController    → Mahasiswa aktif
  ├── ValidasinilaiController       → Validasi nilai (UTS/UAS)
  ├── ValidasinilaiKpatController   → Validasi nilai KPAT
  ├── UpdatePenilaianController     → Review perubahan nilai
  ├── KpatController                → Manajemen KPAT
  ├── KrsanController               → KRS an/perubahan
  ├── KonsultasiPerwalianController → Monitoring konsultasi
  ├── MbkmController                → Peserta MBKM
└── UmbController                 → UMB (?)
Dosen/
└── Dekan/                          → (sub-role)
    ├── ValidasiNilaiController     → Validasi final nilai
    ├── ValidasiNilaiSimpanController → Proses validasi
    ├── ValidasinilaiKpatController → Validasi KPAT
    └── UpdatePenilaianController   → Review nilai
    
Mahasiswa/
├── KrsController                 → Buat KRS, cek block, ambil MK
├── KhsController                 → Lihat KHS
├── ProfilController              → Lihat/edit profil
├── KurikulumController           → Lihat kurikulum prodi
├── PetikanNilaiController        → Cetak petikan nilai
├── MatakuliahPrasyaratController  → Cek prasyarat
├── KompetensiController          → Pilih kompetensi
├── KuisionerController           → Isi kuisioner evaluasi
├── KalenderController            → Kalender akademik
├── ObrolanController             → Chat dosen wali
└── GantiSandiController          → Ganti password

app/Http/Controllers/Auth/
├── LoginController               → Login (multi-role: admin/dosen/mahasiswa)
├── LupaPasswordController        → Reset password
├── VerifikasiController          → Verifikasi data
├── CekPembayaranController       → Cek status bayar (publik)
└── HomeController                → Dashboard landing

app/Http/Controllers/Api/
└── (sama seperti struktur role di atas, tapi return JSON)
