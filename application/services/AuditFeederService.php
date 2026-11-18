<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AuditFeederService extends MY_Service {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Audit_feeder_model');
        $this->load->model('jurusan/m_tahun_akademik');
        $this->load->service('FeederService');
    }

    public function hasilKelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $kode_matakuliah, $nama_kelas_id)
    {
        $kode_matakuliah = trim((string) $kode_matakuliah);

        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        if (!$ta) {
            return ['error' => 'Tahun akademik tidak valid.'];
        }
        if (empty($id_matakuliah)) {
            return ['error' => 'Matakuliah wajib dipilih.'];
        }
        if ($kode_matakuliah === '') {
            return ['error' => 'Kode matakuliah tidak terbaca. Pilih ulang matakuliah.'];
        }

        $id_semester = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
        $siska_rows = $this->audit_feeder_model->getNilaiSiskaByKelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nama_kelas_id);

        // Ambil data Feeder berdasarkan kode matakuliah (bukan nama matakuliah).
        $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['kode_mata_kuliah' => $kode_matakuliah]);

        // Nama kelas yang dipilih (untuk membatasi data Feeder ke kelas yang sama).
        $kelas_norm = '';
        if ($nama_kelas_id !== NULL && $nama_kelas_id !== '') {
            if (!empty($siska_rows) && !empty($siska_rows[0]->nama_kelas)) {
                $kelas_norm = $this->normalizeKelas($siska_rows[0]->nama_kelas);
            } else {
                $kelas_norm = $this->normalizeKelas($this->audit_feeder_model->getNamaKelasById($nama_kelas_id));
            }
        }

        $prodi_norm = $this->normalizeName($this->audit_feeder_model->getProdiNameByKode($kode_program_studi));

        $feeder = $this->saringFeederKelas($feeder, $kelas_norm, $prodi_norm);

        $hasil = $this->susunHasil($siska_rows, $feeder, FALSE);
        $semester_label = ($ta->semester == '1' ? 'Ganjil' : 'Genap');
        foreach ($hasil['rows'] as $row) {
            $row->tahun_akademik = $ta->tahun_akademik;
            $row->semester_label = $semester_label;
            $row->kode_tahun_akademik = $kode_tahun_akademik;
            $row->id_matakuliah = $id_matakuliah;
        }

        return [
            'hasil'       => $hasil,
            'judul_hasil' => 'Audit Nilai SISKA vs Feeder',
            'sub_hasil'   => 'Matakuliah: ' . $kode_matakuliah . ' | ' . $ta->tahun_akademik . ' - ' . $semester_label,
            'id_semester'         => $id_semester,
            'tampil_ta'           => FALSE,
            'tampil_null'         => TRUE,
            'id_matakuliah'       => $id_matakuliah,
            'kode_tahun_akademik' => $kode_tahun_akademik,
        ];
    }

    public function hasilKpat($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $kode_matakuliah, $nama_kelas_id)
    {
        $kode_matakuliah = trim((string) $kode_matakuliah);

        $tahun_str = '';
        if (strpos((string) $kode_tahun_akademik, '/') !== FALSE) {
            $tahun_str = trim((string) $kode_tahun_akademik);
        } else {
            $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
            if ($ta) {
                $tahun_str = trim((string) $ta->tahun_akademik);
            }
        }
        if ($tahun_str === '') {
            return ['error' => 'Tahun akademik tidak valid.'];
        }
        if (empty($id_matakuliah)) {
            return ['error' => 'Matakuliah wajib dipilih.'];
        }
        if ($kode_matakuliah === '') {
            return ['error' => 'Kode matakuliah tidak terbaca. Pilih ulang matakuliah.'];
        }

        $year = (int) substr($tahun_str, 0, 4);
        $id_semester_antara = ($year > 0) ? ($year . '3') : '';

        // Ambil data nilai KPAT dari SISKA
        $siska_rows = $this->audit_feeder_model->getNilaiSiskaByKpat($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nama_kelas_id);

        // Ambil data nilai Feeder (coba semester antara YYYY3 dahulu)
        $id_semester = $id_semester_antara;
        $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['kode_mata_kuliah' => $kode_matakuliah]);

        // Jika di semester antara kosong, coba cek semester genap YYYY2 siapa tahu diinput di semester genap
        if (empty($feeder['map'])) {
            $id_semester_genap = ($year > 0) ? ($year . '2') : '';
            if ($id_semester_genap !== '') {
                $feeder_genap = $this->feederservice->getNilaiFeederMap($id_semester_genap, ['kode_mata_kuliah' => $kode_matakuliah]);
                if (!empty($feeder_genap['map'])) {
                    $feeder = $feeder_genap;
                    $id_semester = $id_semester_genap;
                }
            }
        }

        // Normalisasi kelas jika memilih kelas KPAT tertentu
        $kelas_norm = '';
        if ($nama_kelas_id !== NULL && $nama_kelas_id !== '') {
            if (!empty($siska_rows) && !empty($siska_rows[0]->nama_kelas)) {
                $kelas_norm = $this->normalizeKelas($siska_rows[0]->nama_kelas);
            } else {
                $kelas_norm = $this->normalizeKelas($this->audit_feeder_model->getNamaKelasById($nama_kelas_id));
            }
        }

        $prodi_norm = $this->normalizeName($this->audit_feeder_model->getProdiNameByKode($kode_program_studi));
        $feeder = $this->saringFeederKelas($feeder, $kelas_norm, $prodi_norm, TRUE);

        $hasil = $this->susunHasil($siska_rows, $feeder, FALSE);

        foreach ($hasil['rows'] as $row) {
            $row->tahun_akademik = !empty($row->tahun_akademik) ? $row->tahun_akademik : $tahun_str;
            $row->semester_label = (isset($row->semester_ta) && (int) $row->semester_ta === 1) ? 'Ganjil' : 'Genap';
            $row->kode_tahun_akademik = $kode_tahun_akademik;
            $row->id_matakuliah = $id_matakuliah;
        }

        $nama_mk_hdr = (!empty($siska_rows) && !empty($siska_rows[0]->nama_matakuliah)) ? (' - ' . $siska_rows[0]->nama_matakuliah) : '';

        return [
            'hasil'       => $hasil,
            'judul_hasil' => 'Audit Nilai SISKA vs Feeder - KPAT',
            'sub_hasil'   => 'KPAT: ' . $kode_matakuliah . $nama_mk_hdr . ' | Tahun Akademik: ' . $tahun_str,
            'id_semester'         => $id_semester,
            'tampil_ta'           => TRUE,
            'tampil_null'         => TRUE,
            'is_kpat'             => TRUE,
            'id_matakuliah'       => $id_matakuliah,
            'kode_tahun_akademik' => $kode_tahun_akademik,
        ];
    }

    /**
     * Cari satu kandidat nilai Feeder untuk tiap NIM (baris null) pada TA terpilih.
     * Kunci pencarian hanya: NIM + Nama Matakuliah + Tahun Akademik.
     * Prioritas: nama matakuliah sama persis (ternormalisasi), lalu paling mirip.
     */
    public function kandidatNullFeeder($kode_tahun_akademik, $nama_matakuliah, $nim_list, $is_kpat = FALSE)
    {
        $id_semester = '';
        if (strpos((string) $kode_tahun_akademik, '/') !== FALSE) {
            $tahun_str = trim((string) $kode_tahun_akademik);
        } else {
            $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
            if ($ta) {
                $id_semester = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
            }
        }
        if (!$is_kpat && $id_semester === '') {
            return ['error' => 'Tahun akademik tidak valid.'];
        }

        $clean_target = $this->normalizeMatakuliah($nama_matakuliah);
        if ($clean_target === '') {
            return ['error' => 'Nama matakuliah kosong.'];
        }

        if (!is_array($nim_list)) {
            $nim_list = explode(',', (string) $nim_list);
        }

        $nims = [];
        foreach ($nim_list as $n) {
            $n = trim((string) $n);
            if ($n !== '') {
                $nims[$n] = TRUE;
            }
        }

        $cache = [];
        $rows  = [];

        foreach (array_keys($nims) as $nim) {
            if (!isset($cache[$nim])) {
                $res = $this->feederservice->getNilaiMahasiswa($nim);
                if (!empty($res['error'])) {
                    $cache[$nim] = ['error' => $res['error'], 'rows' => []];
                } else {
                    $cache[$nim] = ['error' => '', 'rows' => ($res['rows'] ?? [])];
                }
            }

            // Saring riwayat nilai Feeder
            $rows_ta = [];
            foreach ($cache[$nim]['rows'] as $row) {
                $row_sem = trim((string) ($row['id_semester'] ?? ''));
                if ($is_kpat) {
                    // Gunakan SEMUA tahun akademik, tapi TETAP semester pendek
                    $is_pendek = (substr($row_sem, -1) === '3')
                        || (stripos((string)($row['nama_semester'] ?? ''), 'pendek') !== FALSE)
                        || (stripos((string)($row['nama_semester'] ?? ''), 'antara') !== FALSE);
                    if ($is_pendek) {
                        $rows_ta[] = $row;
                    }
                } else {
                    if ($row_sem === $id_semester) {
                        $rows_ta[] = $row;
                    }
                }
            }

            $kandidat  = NULL;
            $metode    = '';
            $best_skor = 0;

            foreach ($rows_ta as $row) {
                $clean = $this->normalizeMatakuliah($row['nama_mata_kuliah'] ?? '');
                if ($clean === '') {
                    continue;
                }

                if ($clean === $clean_target) {
                    $kandidat = $row;
                    $metode   = 'persis';
                    break;
                }

                $skor = $this->miripPersen($clean_target, $clean);
                if ($skor > $best_skor) {
                    $best_skor = $skor;
                    $kandidat  = $row;
                    $metode    = 'mirip';
                }
            }

            if ($metode === 'mirip' && $best_skor < 60) {
                $kandidat = NULL;
                $metode   = '';
            }

            $contoh = [];
            foreach ($rows_ta as $row) {
                if (count($contoh) >= 5) {
                    break;
                }
                $contoh[] = [
                    'kode'    => (string) ($row['kode_mata_kuliah'] ?? ''),
                    'nama_mk' => (string) ($row['nama_mata_kuliah'] ?? ''),
                ];
            }

            $rows[] = [
                'nim'           => $nim,
                'kode'          => $kandidat ? (string) ($kandidat['kode_mata_kuliah'] ?? '') : '',
                'nama_mk'       => $kandidat ? (string) ($kandidat['nama_mata_kuliah'] ?? '') : '',
                'kelas'         => $kandidat ? (string) ($kandidat['nama_kelas'] ?? '') : '',
                'sks'           => $kandidat ? (is_numeric($kandidat['sks']) ? (float) $kandidat['sks'] : $kandidat['sks']) : '',
                'angka'         => $kandidat ? $this->formatNilaiTampil($kandidat['nilai_angka'] ?? NULL) : '',
                'huruf'         => $kandidat ? ((isset($kandidat['nilai_huruf']) && $kandidat['nilai_huruf'] !== NULL && $kandidat['nilai_huruf'] !== '') ? $kandidat['nilai_huruf'] : 'null') : '',
                'id_semester'   => $kandidat ? (string) ($kandidat['id_semester'] ?? '') : '',
                'nama_semester' => $kandidat ? (string) ($kandidat['nama_semester'] ?? '') : '',
                'metode'        => $metode,
                'skor'          => ($metode === 'persis') ? 100 : (($metode === 'mirip') ? (int) round($best_skor) : 0),
                'total_semua'   => count($cache[$nim]['rows']),
                'total_ta'      => count($rows_ta),
                'semester'      => $kandidat ? ($kandidat['nama_semester'] ?? $kandidat['id_semester'] ?? $id_semester) : $id_semester,
                'contoh'        => $contoh,
                'error'         => $cache[$nim]['error'],
            ];
        }

        return [
            'nama_matakuliah' => $nama_matakuliah,
            'semester'        => $id_semester,
            'rows'            => $rows,
        ];
    }

    /**
     * Persentase kemiripan dua string (0 - 100).
     */
    private function miripPersen($a, $b)
    {
        $a = (string) $a;
        $b = (string) $b;
        if ($a === '' || $b === '') {
            return 0;
        }

        similar_text($a, $b, $persen);
        return (float) $persen;
    }

    /**
     * Ekstrak jenjang pendidikan (D1, D2, D3, D4, S1, S2, S3, PROFESI) dari awal nama prodi.
     */
    private function extractJenjang($prodi_norm)
    {
        if (preg_match('/^(D1|D2|D3|D4|S1|S2|S3|PROFESI)\b/i', $prodi_norm, $m)) {
            return strtoupper($m[1]);
        }
        return '';
    }

    /**
     * Cek apakah nama prodi SISKA dan Feeder cocok.
     * Mengakomodasi variasi nama resmi di Feeder seperti "D3 Rekayasa Perangkat Lunak Aplikasi"
     * vs "D3 Rekayasa Perangkat Lunak" di SISKA, dengan tetap memvalidasi konsistensi jenjang.
     */
    private function isProdiMatch($prodi_siska_norm, $prodi_feeder_norm)
    {
        if ($prodi_siska_norm === '' || $prodi_feeder_norm === '') {
            return TRUE;
        }

        if ($prodi_siska_norm === $prodi_feeder_norm) {
            return TRUE;
        }

        if (strpos($prodi_feeder_norm, $prodi_siska_norm) !== FALSE || strpos($prodi_siska_norm, $prodi_feeder_norm) !== FALSE) {
            $js = $this->extractJenjang($prodi_siska_norm);
            $jf = $this->extractJenjang($prodi_feeder_norm);
            if ($js !== '' && $jf !== '' && $js !== $jf) {
                return FALSE;
            }
            return TRUE;
        }

        return FALSE;
    }

    private function normalizeName($value)
    {
        $value = strtoupper((string) $value);
        $value = str_replace('*', ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        return trim($value);
    }

    /**
     * Normalisasi nama matakuliah: buang spasi dan seluruh tanda baca,
     * sehingga "COPYWRITING & ADVERTISING**" setara dengan
     * "COPY WRITING & ADVERTISING".
     */
    private function normalizeMatakuliah($value)
    {
        $value = strtoupper((string) $value);
        $value = preg_replace('/[^A-Z0-9]+/', '', $value);
        return trim($value);
    }

    /**
     * Normalisasi nama kelas: buang awalan angka Romawi (mis. semester)
     * sehingga "IIIA" di Feeder setara dengan "A" di SISKA.
     */
    private function normalizeKelas($value)
    {
        $value = $this->normalizeName($value);
        $stripped = preg_replace('/^[IVXLCDM]+(?=.)/', '', $value);
        return ($stripped === '' || $stripped === NULL) ? $value : $stripped;
    }

    /**
     * Saring map Feeder (tetap key NIM|kode) agar hanya menyisakan baris
     * dengan kelas dan program studi yang sesuai.
     */
    private function saringFeederKelas($feeder, $kelas_norm, $prodi_norm = '', $is_kpat = FALSE)
    {
        $map = $feeder['map'] ?? [];

        $feeder_punya_kelas = FALSE;
        $feeder_punya_prodi = FALSE;
        foreach ($map as $f) {
            if (!empty($f['nama_kelas'])) {
                $feeder_punya_kelas = TRUE;
            }
            if (!empty($f['prodi'])) {
                $feeder_punya_prodi = TRUE;
            }
            if ($feeder_punya_kelas && $feeder_punya_prodi) {
                break;
            }
        }

        $baru = [];
        foreach ($map as $key => $f) {
            $f_prodi = $this->normalizeName($f['prodi'] ?? '');
            if ($prodi_norm !== '' && $feeder_punya_prodi && !$this->isProdiMatch($prodi_norm, $f_prodi)) {
                continue;
            }
            if ($kelas_norm !== '' && $feeder_punya_kelas && $this->normalizeKelas($f['nama_kelas'] ?? '') !== $kelas_norm) {
                continue;
            }
            // Jika audit kelas reguler memilih Semua Kelas, jangan sertakan kelas Feeder yang berlabel SP/Pendek
            if (!$is_kpat && $kelas_norm === '' && !empty($f['nama_kelas'])) {
                $f_kelas = trim((string) $f['nama_kelas']);
                if (preg_match('/\b(sp|pendek|kpat)\b/i', $f_kelas)) {
                    continue;
                }
            }

            $baru[$key] = $f;
        }

        return [
            'map'   => $baru,
            'keys'  => $feeder['keys'] ?? [],
            'total' => count($baru),
            'error' => $feeder['error'] ?? '',
        ];
    }

    /**
     * Tahun akademik (format "YYYY/YYYY") dari id_semester Feeder ("20231")
     * atau dari nama_semester ("2022/2023 Ganjil").
     */
    private function tahunAkademikDariSemester($id_semester, $nama_semester = '')
    {
        $id = trim((string) $id_semester);
        if (preg_match('/^(\d{4})([12])$/', $id, $m)) {
            $tahun = (int) $m[1];
            return $tahun . '/' . ($tahun + 1);
        }

        $nama = trim((string) $nama_semester);
        if ($nama !== '' && preg_match('/(\d{4})\s*\/\s*(\d{4})/', $nama, $m2)) {
            return $m2[1] . '/' . $m2[2];
        }
        if ($nama !== '' && preg_match('/^(\d{4})/', $nama, $m3)) {
            $tahun = (int) $m3[1];
            return $tahun . '/' . ($tahun + 1);
        }

        return '';
    }

    /**
     * Label tahun akademik + semester, mis. "2023/2024 Ganjil".
     * Semester diambil dari digit terakhir id_semester atau kata Ganjil/Genap
     * pada nama_semester.
     */
    private function labelTahunAkademikSemester($id_semester, $nama_semester = '')
    {
        $ta = $this->tahunAkademikDariSemester($id_semester, $nama_semester);

        $smt = '';
        $id  = trim((string) $id_semester);
        if (preg_match('/^(\d{4})([12])$/', $id, $m)) {
            $smt = ($m[2] === '1') ? 'Ganjil' : 'Genap';
        } else {
            $nama = strtoupper(trim((string) $nama_semester));
            if (strpos($nama, 'GANJIL') !== FALSE) {
                $smt = 'Ganjil';
            } elseif (strpos($nama, 'GENAP') !== FALSE) {
                $smt = 'Genap';
            }
        }

        if ($ta !== '' && $smt !== '') {
            return $ta . ' ' . $smt;
        }
        if ($ta !== '') {
            return $ta;
        }

        return trim((string) $nama_semester);
    }

    /**
     * Kelompokkan baris perbandingan berdasarkan tahun akademik + semester Feeder.
     * Baris tanpa sisi Feeder (Tidak ada di Feeder) masuk grup "Tanpa Tahun Akademik".
     */
    private function kelompokkanPerbandingan($perbandingan)
    {
        $grup = [];
        foreach ($perbandingan as $row) {
            $label = !empty($row['feeder_ta_smt']) ? $row['feeder_ta_smt'] : 'Tanpa Tahun Akademik';
            if (!isset($grup[$label])) {
                $grup[$label] = [];
            }
            $grup[$label][] = $row;
        }

        $rank = function ($v) {
            if ($v === 'Tanpa Tahun Akademik') {
                return 2;
            }
            if ($v === 'Konversi') {
                return 1;
            }
            return 0;
        };

        uksort($grup, function ($a, $b) use ($rank) {
            $ra = $rank($a);
            $rb = $rank($b);
            if ($ra !== $rb) {
                return $ra - $rb;
            }
            return strcmp((string) $a, (string) $b);
        });

        $out = [];
        foreach ($grup as $ta => $rows) {
            $out[] = ['tahun_akademik' => $ta, 'rows' => $rows];
        }

        return $out;
    }

    /**
     * Data mentah Feeder untuk satu NIM (tanpa perhitungan petikan/SISKA).
     * Menggabungkan respons GetDetailNilaiPerkuliahanKelas + GetTranskripMahasiswa.
     */
    public function rawFeederData($nim)
    {
        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['error' => 'NIM wajib diisi.'];
        }

        $result = $this->feederservice->getNilaiMahasiswa($nim);
        if (!empty($result['error'])) {
            return ['error' => $result['error']];
        }

        $rows = $result['rows'] ?? [];
        if (empty($rows)) {
            return ['error' => 'Data nilai Feeder tidak ditemukan untuk NIM tersebut.'];
        }

        $header = [
            'nim'                     => $rows[0]['nim'] !== '' ? $rows[0]['nim'] : $nim,
            'nama_mahasiswa'          => $rows[0]['nama_mahasiswa'],
            'nama_program_studi'      => $rows[0]['nama_program_studi'],
            'id_prodi'                => $rows[0]['id_prodi'],
            'jurusan'                 => $rows[0]['jurusan'],
            'angkatan'                => $rows[0]['angkatan'],
            'id_mahasiswa'            => $rows[0]['id_mahasiswa'],
            'id_registrasi_mahasiswa' => $rows[0]['id_registrasi_mahasiswa'],
        ];

        $raw_rows = [];
        $id_regs  = [];
        foreach ($rows as $row) {
            $raw_rows[] = isset($row['raw']) ? $row['raw'] : $row;
            $idr = trim((string) $row['id_registrasi_mahasiswa']);
            if ($idr !== '') {
                $id_regs[$idr] = TRUE;
            }
        }

        $transkrip_raw   = [];
        $transkrip_error = '';
        foreach (array_keys($id_regs) as $idr) {
            $tr = $this->feederservice->getTranskripMahasiswa($idr);
            if (!empty($tr['error'])) {
                $transkrip_error = $tr['error'];
                continue;
            }
            foreach ($tr['rows'] as $t) {
                $transkrip_raw[] = isset($t['raw']) ? $t['raw'] : $t;
            }
        }

        return [
            'header'          => $header,
            'raw_rows'        => $raw_rows,
            'transkrip_raw'   => $transkrip_raw,
            'transkrip_error' => $transkrip_error,
        ];
    }

    public function petikanFeeder($nim, $id_semester = NULL, $highlight_name = '')
    {
        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['error' => 'NIM wajib diisi.'];
        }

        $result = $this->feederservice->getNilaiMahasiswa($nim);
        if (!empty($result['error'])) {
            return ['error' => $result['error']];
        }

        $rows = $result['rows'] ?? [];

        if ($id_semester !== NULL && trim((string) $id_semester) !== '') {
            $id_semester = trim((string) $id_semester);
            $rows = array_values(array_filter($rows, function ($row) use ($id_semester) {
                return trim((string) $row['id_semester']) === $id_semester;
            }));
        }

        if (empty($rows)) {
            return ['error' => 'Data nilai Feeder tidak ditemukan untuk NIM tersebut.'];
        }

        $clean_highlight = $this->normalizeMatakuliah($highlight_name);

        $header = [
            'nim'                     => $rows[0]['nim'] !== '' ? $rows[0]['nim'] : $nim,
            'nama_mahasiswa'          => $rows[0]['nama_mahasiswa'],
            'nama_program_studi'      => $rows[0]['nama_program_studi'],
            'id_prodi'                => $rows[0]['id_prodi'],
            'jurusan'                 => $rows[0]['jurusan'],
            'angkatan'                => $rows[0]['angkatan'],
            'id_mahasiswa'            => $rows[0]['id_mahasiswa'],
            'id_registrasi_mahasiswa' => $rows[0]['id_registrasi_mahasiswa'],
        ];

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['id_semester'] !== '' ? $row['id_semester'] : $row['nama_semester'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id_semester'   => $row['id_semester'],
                    'nama_semester' => $row['nama_semester'],
                    'items'         => [],
                    'total_sks'     => 0,
                    'total_sksn'    => 0,
                    'ips'           => 0,
                ];
            }

            $sks = is_numeric($row['sks']) ? (float) $row['sks'] : 0;
            $indeks = is_numeric($row['nilai_indeks']) ? (float) $row['nilai_indeks'] : NULL;

            $grouped[$key]['items'][] = [
                'id_prodi'                => $row['id_prodi'],
                'nama_program_studi'      => $row['nama_program_studi'],
                'id_semester'             => $row['id_semester'],
                'nama_semester'           => $row['nama_semester'],
                'id_matkul'               => $row['id_matkul'],
                'kode_mata_kuliah'        => $row['kode_mata_kuliah'],
                'nama_mata_kuliah'        => $row['nama_mata_kuliah'],
                'sks'                     => $sks,
                'sks_tampil'              => number_format($sks, 2, '.', ''),
                'id_kelas_kuliah'         => $row['id_kelas_kuliah'],
                'nama_kelas'              => $row['nama_kelas'],
                'id_registrasi_mahasiswa' => $row['id_registrasi_mahasiswa'],
                'id_mahasiswa'            => $row['id_mahasiswa'],
                'nim'                     => $row['nim'],
                'nama_mahasiswa'          => $row['nama_mahasiswa'],
                'jurusan'                 => $row['jurusan'],
                'angkatan'                => $row['angkatan'],
                'nilai_angka'             => $row['nilai_angka'],
                'nilai_angka_tampil'      => $this->formatNilaiTampil($row['nilai_angka']),
                'nilai_huruf'             => $row['nilai_huruf'],
                'nilai_huruf_tampil'      => ($row['nilai_huruf'] === NULL || $row['nilai_huruf'] === '') ? 'null' : $row['nilai_huruf'],
                'nilai_indeks'            => $row['nilai_indeks'],
                'nilai_indeks_tampil'     => $this->formatNilaiTampil($row['nilai_indeks']),
                'raw'                     => $row['raw'],
                'highlight'               => ($clean_highlight !== '' && $this->normalizeMatakuliah($row['nama_mata_kuliah']) === $clean_highlight),
            ];

            if ($indeks !== NULL) {
                $grouped[$key]['total_sks']  += $sks;
                $grouped[$key]['total_sksn'] += $indeks * $sks;
            }
        }

        ksort($grouped);

        foreach ($grouped as $key => $g) {
            $grouped[$key]['ips'] = $g['total_sks'] > 0 ? round($g['total_sksn'] / $g['total_sks'], 2, PHP_ROUND_HALF_EVEN) : 0;
        }

        // Konsolidasi: satu baris per kode matakuliah, ambil nilai terbaik (indeks tertinggi, lalu angka tertinggi)
        $best = [];
        foreach ($rows as $row) {
            $kode = $row['kode_mata_kuliah'];
            if ($kode === '') {
                continue;
            }

            $indeks = is_numeric($row['nilai_indeks']) ? (float) $row['nilai_indeks'] : -1;
            $angka  = is_numeric($row['nilai_angka']) ? (float) $row['nilai_angka'] : -1;

            if (!isset($best[$kode])
                || $indeks > $best[$kode]['_indeks']
                || ($indeks == $best[$kode]['_indeks'] && $angka > $best[$kode]['_angka'])) {
                $best[$kode] = [
                    '_indeks'          => $indeks,
                    '_angka'           => $angka,
                    'id_prodi'         => $row['id_prodi'],
                    'id_semester'      => $row['id_semester'],
                    'id_matkul'        => $row['id_matkul'],
                    'kode_mata_kuliah' => $row['kode_mata_kuliah'],
                    'nama_mata_kuliah' => $row['nama_mata_kuliah'],
                    'nama_kelas'       => $row['nama_kelas'],
                    'id_kelas_kuliah'  => $row['id_kelas_kuliah'],
                    'jurusan'          => $row['jurusan'],
                    'nama_semester'    => $row['nama_semester'] !== '' ? $row['nama_semester'] : $row['id_semester'],
                    'sks'              => is_numeric($row['sks']) ? (float) $row['sks'] : 0,
                    'nilai_angka'      => $row['nilai_angka'],
                    'nilai_huruf'      => $row['nilai_huruf'],
                    'nilai_indeks'     => $row['nilai_indeks'],
                ];
            }
        }

        ksort($best);

        $konsolidasi = [];
        $grand_sks = 0;
        $grand_sksn = 0;
        foreach ($best as $b) {
            $sks = $b['sks'];
            $indeks = $b['_indeks'] >= 0 ? $b['_indeks'] : NULL;

            $konsolidasi[] = [
                'id_prodi'            => $b['id_prodi'],
                'id_semester'         => $b['id_semester'],
                'id_matkul'           => $b['id_matkul'],
                'kode_mata_kuliah'    => $b['kode_mata_kuliah'],
                'nama_mata_kuliah'    => $b['nama_mata_kuliah'],
                'nama_kelas'          => $b['nama_kelas'],
                'id_kelas_kuliah'     => $b['id_kelas_kuliah'],
                'jurusan'             => $b['jurusan'],
                'nama_semester'       => $b['nama_semester'],
                'sks'                 => $sks,
                'sks_tampil'          => number_format($sks, 2, '.', ''),
                'nilai_angka'         => $b['nilai_angka'],
                'nilai_angka_tampil'  => $this->formatNilaiTampil($b['nilai_angka']),
                'nilai_huruf'         => $b['nilai_huruf'],
                'nilai_huruf_tampil'  => ($b['nilai_huruf'] === NULL || $b['nilai_huruf'] === '') ? 'null' : $b['nilai_huruf'],
                'nilai_indeks'        => $b['nilai_indeks'],
                'nilai_indeks_tampil' => $this->formatNilaiTampil($b['nilai_indeks']),
                'highlight'           => ($clean_highlight !== '' && $this->normalizeMatakuliah($b['nama_mata_kuliah']) === $clean_highlight),
            ];

            if ($indeks !== NULL) {
                $grand_sks  += $sks;
                $grand_sksn += $indeks * $sks;
            }
        }

        // Data mentah Feeder apa adanya (untuk tab Data Mentah).
        $raw_rows = [];
        foreach ($rows as $row) {
            $raw_rows[] = isset($row['raw']) ? $row['raw'] : $row;
        }

        // Peta bantu dari detail: id_matkul => semester, kelas, tahun akademik.
        $smt_by_matkul   = [];
        $kelas_by_matkul = [];
        $ta_by_matkul    = [];
        $ta_smt_by_matkul = [];
        $id_regs         = [];
        foreach ($rows as $row) {
            $mid = strtolower(trim((string) $row['id_matkul']));
            if ($mid !== '') {
                $smt_by_matkul[$mid]   = $row['nama_semester'] !== '' ? $row['nama_semester'] : $row['id_semester'];
                $kelas_by_matkul[$mid] = $row['nama_kelas'];
                $ta_by_matkul[$mid]    = $this->tahunAkademikDariSemester($row['id_semester'], $row['nama_semester']);
                $ta_smt_by_matkul[$mid] = $this->labelTahunAkademikSemester($row['id_semester'], $row['nama_semester']);
            }
            $idr = trim((string) $row['id_registrasi_mahasiswa']);
            if ($idr !== '') {
                $id_regs[$idr] = TRUE;
            }
        }

        // ===== Petikan Nilai dari GetTranskripMahasiswa (memuat nilai konversi/transfer) =====
        $transkrip_rows = [];
        $transkrip_error = '';
        foreach (array_keys($id_regs) as $idr) {
            $tr = $this->feederservice->getTranskripMahasiswa($idr);
            if (!empty($tr['error'])) {
                $transkrip_error = $tr['error'];
                continue;
            }
            foreach ($tr['rows'] as $t) {
                $transkrip_rows[] = $t;
            }
        }

        $transkrip_raw = [];
        foreach ($transkrip_rows as $t) {
            $transkrip_raw[] = isset($t['raw']) ? $t['raw'] : $t;
        }

        $petikan = [];
        $p_sks   = 0;
        $p_sksn  = 0;
        foreach ($transkrip_rows as $t) {
            $sks = is_numeric($t['sks']) ? (float) $t['sks'] : 0;
            $indeks = is_numeric($t['nilai_indeks']) ? (float) $t['nilai_indeks'] : NULL;
            $is_transfer = ($t['id_nilai_transfer'] !== '' || $t['id_konversi_aktivitas'] !== '');
            $mid = strtolower(trim((string) $t['id_matkul']));
            $semester = isset($smt_by_matkul[$mid]) ? $smt_by_matkul[$mid] : ($is_transfer ? 'Konversi' : '');

            $petikan[] = [
                'kode_mata_kuliah'       => $t['kode_mata_kuliah'],
                'nama_mata_kuliah'       => $t['nama_mata_kuliah'],
                'sks'                    => $sks,
                'sks_tampil'             => number_format($sks, 2, '.', ''),
                'nama_semester'          => $semester,
                'smt_diambil'            => $t['smt_diambil'],
                'nama_kelas'             => isset($kelas_by_matkul[$mid]) ? $kelas_by_matkul[$mid] : '',
                'nilai_angka'            => $t['nilai_angka'],
                'nilai_angka_tampil'     => $this->formatNilaiTampil($t['nilai_angka']),
                'nilai_huruf'            => $t['nilai_huruf'],
                'nilai_huruf_tampil'     => ($t['nilai_huruf'] === NULL || $t['nilai_huruf'] === '') ? 'null' : $t['nilai_huruf'],
                'nilai_indeks'           => $t['nilai_indeks'],
                'nilai_indeks_tampil'    => $this->formatNilaiTampil($t['nilai_indeks']),
                'is_transfer'            => $is_transfer,
                'id_matkul'              => $t['id_matkul'],
                'id_kelas_kuliah'        => $t['id_kelas_kuliah'],
                'id_nilai_transfer'      => $t['id_nilai_transfer'],
                'id_konversi_aktivitas'  => $t['id_konversi_aktivitas'],
                'id_registrasi_mahasiswa' => $t['id_registrasi_mahasiswa'],
                'raw'                    => $t['raw'],
            ];

            if ($indeks !== NULL) {
                $p_sks  += $sks;
                $p_sksn += $indeks * $sks;
            }
        }

        usort($petikan, function ($a, $b) {
            $c = strcmp((string) $a['kode_mata_kuliah'], (string) $b['kode_mata_kuliah']);
            if ($c !== 0) {
                return $c;
            }
            return strcmp((string) $a['nama_mata_kuliah'], (string) $b['nama_mata_kuliah']);
        });

        // ===== Grup Konversi untuk tab Detail Nilai =====
        // Baris transkrip tanpa kelas (id_kelas_kuliah kosong) dianggap konversi.
        $konversi_items = [];
        $k_sks  = 0;
        $k_sksn = 0;
        foreach ($petikan as $p) {
            if ($p['id_kelas_kuliah'] !== '') {
                continue;
            }

            $indeks = is_numeric($p['nilai_indeks']) ? (float) $p['nilai_indeks'] : NULL;

            $konversi_items[] = [
                'id_prodi'                => $header['id_prodi'],
                'nama_program_studi'      => $header['nama_program_studi'],
                'id_semester'             => '',
                'nama_semester'           => 'Konversi',
                'id_matkul'               => $p['id_matkul'],
                'kode_mata_kuliah'        => $p['kode_mata_kuliah'],
                'nama_mata_kuliah'        => $p['nama_mata_kuliah'],
                'sks'                     => $p['sks'],
                'sks_tampil'              => $p['sks_tampil'],
                'id_kelas_kuliah'         => $p['id_kelas_kuliah'],
                'nama_kelas'              => $p['nama_kelas'],
                'id_registrasi_mahasiswa' => $p['id_registrasi_mahasiswa'],
                'id_mahasiswa'            => $header['id_mahasiswa'],
                'nim'                     => $header['nim'],
                'nama_mahasiswa'          => $header['nama_mahasiswa'],
                'jurusan'                 => $header['jurusan'],
                'angkatan'                => $header['angkatan'],
                'nilai_angka'             => $p['nilai_angka'],
                'nilai_angka_tampil'      => $p['nilai_angka_tampil'],
                'nilai_huruf'             => $p['nilai_huruf'],
                'nilai_huruf_tampil'      => $p['nilai_huruf_tampil'],
                'nilai_indeks'            => $p['nilai_indeks'],
                'nilai_indeks_tampil'     => $p['nilai_indeks_tampil'],
                'id_nilai_transfer'       => $p['id_nilai_transfer'],
                'id_konversi_aktivitas'   => $p['id_konversi_aktivitas'],
                'smt_diambil'             => $p['smt_diambil'],
                'status'                  => 'Konversi',
                'highlight'               => FALSE,
                'raw'                     => $p['raw'],
            ];

            if ($indeks !== NULL) {
                $k_sks  += $p['sks'];
                $k_sksn += $indeks * $p['sks'];
            }
        }

        $semester_out = array_values($grouped);
        if (!empty($konversi_items)) {
            array_unshift($semester_out, [
                'id_semester'   => 'KONVERSI',
                'nama_semester' => 'Konversi',
                'items'         => $konversi_items,
                'total_sks'     => $k_sks,
                'total_sksn'    => $k_sksn,
                'ips'           => $k_sks > 0 ? round($k_sksn / $k_sks, 2, PHP_ROUND_HALF_EVEN) : 0,
            ]);
        }

        // ===== Perbandingan SISKA vs Feeder (transkrip, termasuk konversi semester 'K') =====
        $siska_rows = $this->audit_feeder_model->getNilaiSiskaAllByNim($nim);

        $siska_map = [];
        foreach ($siska_rows as $s) {
            $kode_s = strtoupper(trim((string) $s['kode_matakuliah']));
            if ($kode_s === '') {
                continue;
            }
            $siska_map[$kode_s][] = $s;
        }

        // Ambil nilai terbaik per kode dari transkrip.
        $best_tr = [];
        foreach ($petikan as $p) {
            $kode_p = strtoupper(trim((string) $p['kode_mata_kuliah']));
            if ($kode_p === '') {
                continue;
            }
            $indeks_p = is_numeric($p['nilai_indeks']) ? (float) $p['nilai_indeks'] : -1;
            $angka_p  = is_numeric($p['nilai_angka']) ? (float) $p['nilai_angka'] : -1;
            if (!isset($best_tr[$kode_p])
                || $indeks_p > $best_tr[$kode_p]['_indeks']
                || ($indeks_p == $best_tr[$kode_p]['_indeks'] && $angka_p > $best_tr[$kode_p]['_angka'])) {
                $best_tr[$kode_p] = ['_indeks' => $indeks_p, '_angka' => $angka_p, 'row' => $p];
            }
        }

        $perbandingan = [];
        $summary_banding = [
            'sesuai'           => 0,
            'berbeda'          => 0,
            'siska_kosong'     => 0,
            'feeder_kosong'    => 0,
            'tidak_ada_siska'  => 0,
            'tidak_ada_feeder' => 0,
        ];
        // ===== Susun pasangan Feeder vs SISKA =====
        // Tahap 1: cocok berdasarkan Kode MK.
        $pairs          = [];
        $terpakai_siska = [];
        foreach (array_keys($best_tr) as $kode_f) {
            if (isset($siska_map[$kode_f])) {
                $pairs[] = ['feeder' => $kode_f, 'siska' => $kode_f, 'matched_by' => 'kode'];
                $terpakai_siska[$kode_f] = TRUE;
            } else {
                $pairs[] = ['feeder' => $kode_f, 'siska' => NULL, 'matched_by' => NULL];
            }
        }

        // Tahap 2: cocok berdasarkan Nama Matakuliah (normalisasi: buang '*' dll).
        $index_nama_siska = [];
        foreach (array_keys($siska_map) as $kode_s) {
            if (isset($terpakai_siska[$kode_s])) {
                continue;
            }
            $nama_s = $this->normalizeMatakuliah($siska_map[$kode_s][0]['nama_matakuliah']);
            if ($nama_s !== '' && !isset($index_nama_siska[$nama_s])) {
                $index_nama_siska[$nama_s] = $kode_s;
            }
        }
        foreach ($pairs as $i => $pair) {
            if ($pair['feeder'] === NULL || $pair['siska'] !== NULL) {
                continue;
            }
            $nama_f = $this->normalizeMatakuliah($best_tr[$pair['feeder']]['row']['nama_mata_kuliah']);
            if ($nama_f === '' || !isset($index_nama_siska[$nama_f])) {
                continue;
            }
            $kode_s = $index_nama_siska[$nama_f];
            if (isset($terpakai_siska[$kode_s])) {
                continue;
            }
            $pairs[$i]['siska']      = $kode_s;
            $pairs[$i]['matched_by'] = 'nama';
            $terpakai_siska[$kode_s] = TRUE;
        }

        // Sisa SISKA yang belum punya pasangan.
        foreach (array_keys($siska_map) as $kode_s) {
            if (!isset($terpakai_siska[$kode_s])) {
                $pairs[] = ['feeder' => NULL, 'siska' => $kode_s, 'matched_by' => NULL];
                $terpakai_siska[$kode_s] = TRUE;
            }
        }

        foreach ($pairs as $pair) {
            $f      = ($pair['feeder'] !== NULL) ? $best_tr[$pair['feeder']]['row'] : NULL;
            $s_list = ($pair['siska'] !== NULL) ? $siska_map[$pair['siska']] : [];

            $s_best     = NULL;
            $s_semester = [];
            $s_ta       = [];
            $s_konversi = FALSE;
            foreach ($s_list as $s) {
                if ($s_best === NULL
                    || (is_numeric($s['nilai_akhir']) && (float) $s['nilai_akhir'] > (float) ($s_best['nilai_akhir'] ?? -1))) {
                    $s_best = $s;
                }
                if ((string) $s['semester'] === 'K') {
                    $s_konversi   = TRUE;
                    $s_semester[] = 'Konversi';
                    $s_ta[]       = 'Konversi';
                } else {
                    $label = !empty($s['tahun_akademik']) ? $s['tahun_akademik'] : $s['kode_tahun_akademik'];
                    $smt   = (int) $s['semester'];
                    $s_semester[] = $label . ' ' . (($smt % 2 === 0) ? 'Genap' : 'Ganjil');
                    $s_ta[]       = $label;
                }
            }
            $s_semester = array_values(array_unique($s_semester));
            $s_ta       = array_values(array_unique(array_filter($s_ta, function ($v) {
                return trim((string) $v) !== '';
            })));

            $f_ta = '';
            if ($f !== NULL && !empty($f['id_matkul'])) {
                $mid_f = strtolower(trim((string) $f['id_matkul']));
                $f_ta  = isset($ta_by_matkul[$mid_f]) ? $ta_by_matkul[$mid_f] : '';
            }
            if ($f_ta === '' && $f !== NULL && !empty($f['nama_semester'])) {
                $f_ta = $this->tahunAkademikDariSemester('', $f['nama_semester']);
            }

            $f_ta_smt = '';
            if ($f !== NULL) {
                if (!empty($f['id_matkul'])) {
                    $mid_f_smt = strtolower(trim((string) $f['id_matkul']));
                    $f_ta_smt  = isset($ta_smt_by_matkul[$mid_f_smt]) ? $ta_smt_by_matkul[$mid_f_smt] : '';
                }
                if ($f_ta_smt === '' && !empty($f['nama_semester'])) {
                    $f_ta_smt = $this->labelTahunAkademikSemester('', $f['nama_semester']);
                }
            }

            $f_angka = ($f && is_numeric($f['nilai_angka'])) ? (float) $f['nilai_angka'] : NULL;
            $f_huruf = ($f && $f['nilai_huruf'] !== NULL && $f['nilai_huruf'] !== '') ? strtoupper(trim((string) $f['nilai_huruf'])) : '';
            $s_angka = ($s_best && is_numeric($s_best['nilai_akhir'])) ? (float) $s_best['nilai_akhir'] : NULL;
            $s_huruf = ($s_best && $s_best['nilai_huruf'] !== NULL && $s_best['nilai_huruf'] !== '') ? strtoupper(trim((string) $s_best['nilai_huruf'])) : '';

            if ($f !== NULL && $s_best !== NULL) {
                if ($s_angka === NULL && $s_huruf === '') {
                    $status = 'SISKA belum ada nilai';
                    $summary_banding['siska_kosong']++;
                } elseif ($f_angka === NULL && $f_huruf === '') {
                    $status = 'Feeder belum ada nilai';
                    $summary_banding['feeder_kosong']++;
                } elseif ($f_angka !== NULL && $s_angka !== NULL) {
                    if (abs(round($f_angka, 2, PHP_ROUND_HALF_EVEN) - round($s_angka, 2, PHP_ROUND_HALF_EVEN)) <= 0.001) {
                        $status = 'Sesuai';
                        $summary_banding['sesuai']++;
                    } else {
                        $status = 'Berbeda';
                        $summary_banding['berbeda']++;
                    }
                } elseif ($f_huruf !== '' && $s_huruf !== '') {
                    if ($f_huruf === $s_huruf) {
                        $status = 'Sesuai';
                        $summary_banding['sesuai']++;
                    } else {
                        $status = 'Berbeda';
                        $summary_banding['berbeda']++;
                    }
                } else {
                    $status = 'Berbeda';
                    $summary_banding['berbeda']++;
                }
            } elseif ($f !== NULL) {
                $status = 'Tidak ada di SISKA';
                $summary_banding['tidak_ada_siska']++;
            } else {
                $status = 'Tidak ada di Feeder';
                $summary_banding['tidak_ada_feeder']++;
            }

            $perbandingan[] = [
                'kode_mata_kuliah' => $f ? $f['kode_mata_kuliah'] : ($s_best['kode_matakuliah'] ?? ''),
                'feeder_kode'      => $f ? $f['kode_mata_kuliah'] : '',
                'siska_kode'       => ($s_best && !empty($s_best['kode_matakuliah'])) ? $s_best['kode_matakuliah'] : '',
                'siska_kode_tahun_akademik' => ($s_best && !empty($s_best['kode_tahun_akademik'])) ? $s_best['kode_tahun_akademik'] : '',
                'nama_mata_kuliah' => $f ? $f['nama_mata_kuliah'] : ($s_best['nama_matakuliah'] ?? ''),
                'feeder_nama'      => $f ? $f['nama_mata_kuliah'] : '',
                'siska_nama'       => ($s_best && !empty($s_best['nama_matakuliah'])) ? $s_best['nama_matakuliah'] : '',
                'sks'              => $f ? $f['sks'] : (isset($s_best['sks']) && is_numeric($s_best['sks']) ? (float) $s_best['sks'] : 0),
                'feeder_sks'       => $f ? $f['sks'] : NULL,
                'siska_sks'        => ($s_best && isset($s_best['sks']) && is_numeric($s_best['sks'])) ? (float) $s_best['sks'] : NULL,
                'feeder_semester'  => $f ? $f['nama_semester'] : '',
                'feeder_kelas'     => $f ? $f['nama_kelas'] : '',
                'feeder_angka'     => $f ? $this->formatNilaiTampil($f['nilai_angka']) : 'null',
                'feeder_huruf'     => ($f && $f['nilai_huruf'] !== NULL && $f['nilai_huruf'] !== '') ? $f['nilai_huruf'] : 'null',
                'feeder_indeks'    => $f ? $this->formatNilaiTampil($f['nilai_indeks']) : 'null',
                'feeder_transfer'  => ($f && !empty($f['is_transfer'])),
                'siska_semester'   => implode(', ', $s_semester),
                'siska_kelas'      => $s_best['nama_kelas'] ?? '',
                'siska_harian'     => $s_best ? $this->formatNilaiTampil($s_best['nilai_harian']) : 'null',
                'siska_uts'        => $s_best ? $this->formatNilaiTampil($s_best['nilai_uts']) : 'null',
                'siska_uas'        => $s_best ? $this->formatNilaiTampil($s_best['nilai_uas']) : 'null',
                'siska_angka'      => $s_best ? $this->formatNilaiTampil($s_best['nilai_akhir']) : 'null',
                'siska_huruf'      => ($s_best && $s_best['nilai_huruf'] !== NULL && $s_best['nilai_huruf'] !== '') ? $s_best['nilai_huruf'] : 'null',
                'siska_konversi'   => $s_konversi,
                'feeder_ta'        => $f_ta,
                'feeder_ta_smt'    => $f_ta_smt,
                'siska_ta_list'    => $s_ta,
                'matched_by'       => $pair['matched_by'],
                'status'           => $status,
            ];
        }

        // Urutkan: Berbeda → SISKA belum ada nilai → Tidak ada di Feeder →
        // Tidak ada di SISKA → Feeder belum ada nilai → Sesuai (paling bawah).
        $urutan_status = [
            'Berbeda'                => 1,
            'SISKA belum ada nilai'  => 2,
            'Tidak ada di Feeder'    => 3,
            'Tidak ada di SISKA'     => 4,
            'Feeder belum ada nilai' => 5,
            'Sesuai'                 => 6,
        ];
        usort($perbandingan, function ($a, $b) use ($urutan_status) {
            $pa = isset($urutan_status[$a['status']]) ? $urutan_status[$a['status']] : 99;
            $pb = isset($urutan_status[$b['status']]) ? $urutan_status[$b['status']] : 99;
            if ($pa !== $pb) {
                return $pa - $pb;
            }
            return strcmp((string) $a['kode_mata_kuliah'], (string) $b['kode_mata_kuliah']);
        });

        $perbandingan_grup = $this->kelompokkanPerbandingan($perbandingan);

        return [
            'nim'              => $header['nim'],
            'header'           => $header,
            'semester'         => $semester_out,
            'konsolidasi'      => $konsolidasi,
            'total_sks'        => $grand_sks,
            'total_sksn'       => $grand_sksn,
            'ipk'              => $grand_sks > 0 ? round($grand_sksn / $grand_sks, 2, PHP_ROUND_HALF_EVEN) : 0,
            'petikan'          => $petikan,
            'petikan_total_sks'  => $p_sks,
            'petikan_total_sksn' => $p_sksn,
            'petikan_ipk'        => $p_sks > 0 ? round($p_sksn / $p_sks, 2, PHP_ROUND_HALF_EVEN) : 0,
            'transkrip_error'  => $transkrip_error,
            'raw_rows'         => $raw_rows,
            'transkrip_raw'    => $transkrip_raw,
            'siska'            => $siska_rows,
            'perbandingan'     => $perbandingan,
            'perbandingan_grup' => $perbandingan_grup,
            'summary_banding'  => $summary_banding,
        ];
    }

    /**
     * Bandingkan baris SISKA dengan Feeder berdasarkan NIM + kode matakuliah.
     */
    private function susunHasil($siska_rows, $feeder, $include_feeder_only = FALSE)
    {
        $map = $feeder['map'] ?? [];
        $rows = [];
        $summary = [
            'sesuai'               => 0,
            'berbeda'              => 0,
            'sesuai_kode_berubah'  => 0,
            'berbeda_kode_berubah' => 0,
            'tidak_ada'            => 0,
            'kosong'               => 0,
            'tidak_ada_siska'      => 0,
        ];
        $terpakai = [];
        $nama_matakuliah_fallback = '';

        foreach ($siska_rows as $r) {
            $nim = trim((string) $r->nim);
            $kode = trim((string) $r->kode_matakuliah);
            if ($nama_matakuliah_fallback === '' && !empty($r->nama_matakuliah)) {
                $nama_matakuliah_fallback = $r->nama_matakuliah;
            }

            // Cocokkan NIM + kode matakuliah (per-NIM, tanpa nama matakuliah).
            $map_key = strtoupper($nim) . '|' . strtoupper($kode);
            $f = (isset($map[$map_key]) && !isset($terpakai[$map_key])) ? $map[$map_key] : NULL;
            if ($f !== NULL) {
                $terpakai[$map_key] = TRUE;
            }

            $nilai_siska = $r->nilai_akhir;
            $angka = $f['angka'] ?? NULL;
            $huruf = $f['huruf'] ?? NULL;
            $huruf_siska = $this->nilaiHurufSiska($nim, $r->semester ?? NULL, $nilai_siska);

            if ($nilai_siska === NULL || $nilai_siska === '') {
                $status = 'Nilai SISKA kosong';
                $summary['kosong']++;
            } elseif ($f === NULL) {
                $status = 'Tidak ada di Feeder';
                $summary['tidak_ada']++;
            } elseif (abs(round((float) $nilai_siska, 2, PHP_ROUND_HALF_EVEN) - round((float) $angka, 2, PHP_ROUND_HALF_EVEN)) > 0.001) {
                $status = 'Berbeda';
                $summary['berbeda']++;
            } else {
                $status = 'Sesuai';
                $summary['sesuai']++;
            }

            $rows[] = (object) [
                'nim'             => $nim,
                'nama_mahasiswa'  => !empty($r->nama_mahasiswa) ? $r->nama_mahasiswa : '-',
                'kode_matakuliah' => $kode,
                'nama_matakuliah' => $r->nama_matakuliah,
                'nama_kelas'      => $r->nama_kelas,
                'sks'             => (isset($r->sks) && is_numeric($r->sks)) ? (float) $r->sks : (isset($r->sks) ? $r->sks : ''),
                'nilai_siska'     => $this->formatNilaiTampil($nilai_siska),
                'nilai_huruf_siska' => ($huruf_siska === NULL || $huruf_siska === '') ? 'null' : $huruf_siska,
                'nilai_angka'     => $this->formatNilaiTampil($angka),
                'nilai_huruf'     => ($huruf === NULL || $huruf === '') ? 'null' : $huruf,
                'status'          => $status,
                'kode_feeder'     => $f ? ($f['kode'] ?? '') : '',
                'nama_mk_feeder'  => $f ? ($f['nama_mk'] ?? '') : '',
                'kelas_feeder'    => $f ? ($f['nama_kelas'] ?? '') : '',
                'semester_feeder' => $f ? (!empty($f['nama_semester']) ? $f['nama_semester'] : ($f['id_semester'] ?? '')) : '',
                'sumber'          => ($f !== NULL) ? 'both' : 'siska',
                'beda'            => ($status === 'Sesuai') ? 0 : 1,
                'semester_kpat'   => $r->semester_kpat ?? NULL,
                'semester'        => $r->semester ?? NULL,
                'semester_ta'     => $r->semester_ta ?? NULL,
                'tahun_akademik'  => $r->tahun_akademik ?? NULL,
            ];
        }

        if ($include_feeder_only) {
            foreach ($map as $map_key => $f) {
                if (isset($terpakai[$map_key])) {
                    continue;
                }

                $summary['tidak_ada_siska']++;
                $rows[] = (object) [
                    'nim'             => $f['nim'] ?? '',
                    'nama_mahasiswa'  => !empty($f['nama']) ? $f['nama'] : '-',
                    'kode_matakuliah' => $f['kode'] ?? '',
                    'nama_matakuliah' => !empty($f['nama_mk']) ? $f['nama_mk'] : $nama_matakuliah_fallback,
                    'nama_kelas'      => !empty($f['nama_kelas']) ? $f['nama_kelas'] : '-',
                    'nilai_siska'     => 'null',
                    'nilai_huruf_siska' => 'null',
                    'nilai_angka'     => $this->formatNilaiTampil($f['angka'] ?? NULL),
                    'nilai_huruf'     => (!isset($f['huruf']) || $f['huruf'] === NULL || $f['huruf'] === '') ? 'null' : $f['huruf'],
                    'status'          => 'Tidak ada di SISKA',
                    'kode_feeder'     => $f['kode'] ?? '',
                    'nama_mk_feeder'  => $f['nama_mk'] ?? '',
                    'kelas_feeder'    => $f['nama_kelas'] ?? '',
                    'semester_feeder' => !empty($f['nama_semester']) ? $f['nama_semester'] : ($f['id_semester'] ?? ''),
                    'sumber'          => 'feeder',
                    'beda'            => 1,
                ];
            }

            usort($rows, function ($a, $b) {
                return strcmp((string) $a->nim, (string) $b->nim);
            });
        }

        return [
            'rows'         => $rows,
            'summary'      => $summary,
            'feeder_keys'  => $feeder['keys'] ?? [],
            'feeder_total' => $feeder['total'] ?? 0,
            'feeder_error' => $feeder['error'] ?? '',
        ];
    }

    private function formatNilaiTampil($v)
    {
        if ($v === NULL || $v === '') {
            return 'null';
        }
        if (!is_numeric($v)) {
            return 'NaN';
        }
        return number_format(round((float) $v, 2, PHP_ROUND_HALF_EVEN), 2, '.', '');
    }

    private function nilaiHurufSiska($nim, $semester, $nilai_akhir)
    {
        if ($nilai_akhir === NULL || $nilai_akhir === '') {
            return NULL;
        }

        $na = (float) $nilai_akhir;
        foreach (data_penilaian($nim, $semester) as $row) {
            if ($row['nilai_minimum'] <= $na && $na <= $row['nilai_maksimum']) {
                return $row['grade'];
            }
        }

        return NULL;
    }

    /**
     * Komparasi Mahasiswa SISKA vs Feeder berdasarkan Angkatan dan Program Studi.
     */
    public function bandingkanMahasiswa($angkatan, $kode_program_studi = NULL)
    {
        $angkatan = trim((string) $angkatan);
        if ($angkatan === '') {
            return ['error' => 'Angkatan wajib dipilih/diisi.'];
        }

        $angkatan_2digit = (strlen($angkatan) === 4) ? substr($angkatan, 2, 2) : $angkatan;
        $angkatan_4digit = (strlen($angkatan) === 2) ? ((int)$angkatan > 50 ? '19' . $angkatan : '20' . $angkatan) : $angkatan;

        // Ambil data SISKA
        $siska_rows = $this->Audit_feeder_model->getMahasiswaSiskaByAngkatanProdi($angkatan_2digit, $kode_program_studi);
        $siska_map = [];
        foreach ($siska_rows as $row) {
            $nim = trim((string) $row->nim);
            if ($nim !== '') {
                $siska_map[$nim] = $row;
            }
        }

        // Cari ID Prodi Feeder jika prodi dipilih menggunakan GetAllProdi
        $feeder_id_prodi = '';
        $prodi_name = '';
        if (!empty($kode_program_studi)) {
            $prodi_name = $this->Audit_feeder_model->getProdiNameByKode($kode_program_studi);
            $prodi_res = $this->feederservice->getAllProdiFeeder();
            if (empty($prodi_res['error']) && !empty($prodi_res['data']) && is_array($prodi_res['data'])) {
                $prodi_norm = $this->normalizeName($prodi_name);
                foreach ($prodi_res['data'] as $p) {
                    $p_name = $this->normalizeName($p['nama_program_studi'] ?? '');
                    $p_kode = trim((string) ($p['kode_program_studi'] ?? ''));
                    if ($this->isProdiMatch($prodi_norm, $p_name) || ($p_kode !== '' && $p_kode === (string) $kode_program_studi)) {
                        $feeder_id_prodi = (string) ($p['id_prodi'] ?? '');
                        break;
                    }
                }
            }
        }

        // Susun filter Feeder berdasarkan 2 digit NIM dan id_prodi
        $feeder_filter_parts = ["(nim like '" . $angkatan_2digit . "%' or nipd like '" . $angkatan_2digit . "%')"];
        if ($feeder_id_prodi !== '') {
            $feeder_filter_parts[] = "id_prodi='" . str_replace("'", "\\'", $feeder_id_prodi) . "'";
        }
        $feeder_filter = implode(' and ', $feeder_filter_parts);

        // Ambil data Feeder
        $feeder_error = '';
        $feeder_res = $this->feederservice->getAllMahasiswaByFilter($feeder_filter, 'nim asc');
        if (!empty($feeder_res['error'])) {
            $feeder_error = $feeder_res['error'];
            $feeder_rows = [];
        } else {
            $feeder_rows = $feeder_res['rows'] ?? [];
        }

        // Jika Feeder tidak terfilter id_prodi dan user memilih prodi, filter manual berdasarkan nama prodi
        if ($feeder_id_prodi === '' && $prodi_name !== '') {
            $prodi_norm = $this->normalizeName($prodi_name);
            $filtered_feeder = [];
            foreach ($feeder_rows as $fr) {
                if ($this->isProdiMatch($prodi_norm, $this->normalizeName($fr['nama_program_studi'] ?? ''))) {
                    $filtered_feeder[] = $fr;
                }
            }
            $feeder_rows = $filtered_feeder;
        }

        $feeder_map = [];
        foreach ($feeder_rows as $frow) {
            $nim = trim((string) $frow['nim']);
            if ($nim !== '') {
                $feeder_map[$nim] = $frow;
            }
        }

        // Ambil ID mahasiswa Feeder untuk batch fetch biodata lengkap
        $feeder_ids = [];
        foreach ($feeder_rows as $frow) {
            $id_m = trim((string) ($frow['id_mahasiswa'] ?? ''));
            if ($id_m !== '') {
                $feeder_ids[] = $id_m;
            }
        }

        $feeder_bio_map = [];
        if (!empty($feeder_ids)) {
            $bio_batch = $this->feederservice->getBiodataMahasiswaBatch($feeder_ids);
            $feeder_bio_map = $bio_batch['data'] ?? [];
        }

        // Komparasi gabungan
        $all_nims = array_unique(array_merge(array_keys($siska_map), array_keys($feeder_map)));
        sort($all_nims, SORT_NATURAL);

        $rows = [];
        $summary = [
            'total_siska'        => count($siska_map),
            'total_feeder'       => count($feeder_map),
            'total_cocok'        => 0, // >= 90% (Hijau)
            'total_beda_nama'    => 0, // 50% - 89.9% (Oren)
            'total_hanya_siska'  => 0,
            'total_hanya_feeder' => 0,
            'total_hijau'        => 0,
            'total_oren'         => 0,
            'total_merah'        => 0,
            'feeder_error'       => $feeder_error,
        ];

        foreach ($all_nims as $nim) {
            $in_siska = isset($siska_map[$nim]);
            $in_feeder = isset($feeder_map[$nim]);

            $siska_item = $in_siska ? $siska_map[$nim] : NULL;
            $feeder_item = $in_feeder ? $feeder_map[$nim] : NULL;

            $nama_siska = $siska_item ? trim((string) $siska_item->nama_mahasiswa) : '';
            $nama_feeder = $feeder_item ? trim((string) $feeder_item['nama_mahasiswa']) : '';
            $id_mahasiswa = $feeder_item ? ($feeder_item['id_mahasiswa'] ?: '') : '';
            $feeder_bio = ($id_mahasiswa !== '' && isset($feeder_bio_map[$id_mahasiswa])) ? $feeder_bio_map[$id_mahasiswa] : NULL;

            $persen = 0;
            $status_class = 'danger';
            $status_keterangan = '';

            if ($in_siska && $in_feeder) {
                $fields = $this->buildMahasiswaFields($siska_item, $feeder_item, $feeder_bio);
                $eval = $this->evaluasiKesesuaianAtribut($fields);
                $persen = $eval['persen'];
                $status_class = $eval['status_class'];
                $status_keterangan = "{$eval['sesuai']} Sesuai, {$eval['selisih']} Selisih";

                if ($status_class === 'success') {
                    $summary['total_hijau']++;
                    $summary['total_cocok']++;
                } elseif ($status_class === 'warning') {
                    $summary['total_oren']++;
                    $summary['total_beda_nama']++;
                } else {
                    $summary['total_merah']++;
                }
            } elseif ($in_siska && !$in_feeder) {
                $persen = 0;
                $status_class = 'danger';
                $status_keterangan = 'Hanya di SISKA';
                $summary['total_hanya_siska']++;
                $summary['total_merah']++;
            } elseif (!$in_siska && $in_feeder) {
                $persen = 0;
                $status_class = 'danger';
                $status_keterangan = 'Hanya di Feeder';
                $summary['total_hanya_feeder']++;
                $summary['total_merah']++;
            }

            $rows[] = (object) [
                'nim'                  => $nim,
                'nama_siska'           => $nama_siska,
                'nama_feeder'          => $nama_feeder,
                'status_siska'         => $siska_item ? ($siska_item->status === 'A' ? 'Aktif' : 'Non-Aktif') : '-',
                'status_feeder'        => $feeder_item ? ($feeder_item['nama_status_mahasiswa'] ?: '-') : '-',
                'prodi_siska'          => $siska_item ? ($siska_item->nama_program_studi ?: '-') : '-',
                'prodi_feeder'         => $feeder_item ? ($feeder_item['nama_program_studi'] ?: '-') : '-',
                'id_mahasiswa'         => $id_mahasiswa,
                'persentase_audit'     => $persen,
                'status'               => $persen . '%',
                'status_class'         => $status_class,
                'status_keterangan'    => $status_keterangan,
                'in_siska'             => $in_siska,
                'in_feeder'            => $in_feeder,
            ];
        }

        return [
            'angkatan'        => $angkatan,
            'angkatan_4digit' => $angkatan_4digit,
            'prodi_name'      => $prodi_name,
            'rows'            => $rows,
            'summary'         => $summary,
        ];
    }

    /**
     * Format tanggal biodata ke d-m-Y jika valid.
     */
    public function formatTanggalBiodata($raw_date)
    {
        $raw = trim((string) $raw_date);
        if ($raw === '' || $raw === '0000-00-00' || $raw === '-') {
            return '-';
        }
        $ts = strtotime($raw);
        return $ts ? date('d-m-Y', $ts) : $raw;
    }

    /**
     * Format NISN standar 10 digit pad 0 di depan jika numerik.
     */
    public function formatNisnBiodata($raw_nisn)
    {
        $raw = trim((string) $raw_nisn);
        if ($raw === '' || $raw === '-') {
            return '-';
        }
        return (ctype_digit($raw) && strlen($raw) < 10)
            ? str_pad($raw, 10, '0', STR_PAD_LEFT)
            : $raw;
    }

    /**
     * Susun 14 atribut pembanding antara SISKA dan Feeder.
     */
    public function buildMahasiswaFields($siska_item, $feeder_item, $feeder_bio = NULL)
    {
        $tgl_siska = $siska_item ? $this->formatTanggalBiodata($siska_item->tanggal_lahir ?? '') : '-';
        $raw_tgl_feeder = $feeder_bio ? ($feeder_bio['tanggal_lahir'] ?? '') : ($feeder_item ? ($feeder_item['tanggal_lahir'] ?? '') : '');
        $tgl_feeder = $this->formatTanggalBiodata($raw_tgl_feeder);

        $nisn_siska = $siska_item ? $this->formatNisnBiodata($siska_item->nisn ?? '') : '-';
        $raw_nisn_feeder = $feeder_bio ? ($feeder_bio['nisn'] ?? '') : ($feeder_item ? ($feeder_item['nisn'] ?? '') : '');
        $nisn_feeder = $this->formatNisnBiodata($raw_nisn_feeder);

        $jk_siska = '-';
        if ($siska_item && !empty($siska_item->jenis_kelamin)) {
            $jk_siska = ($siska_item->jenis_kelamin === 'L') ? 'Laki-Laki' : (($siska_item->jenis_kelamin === 'P') ? 'Perempuan' : $siska_item->jenis_kelamin);
        }

        $jk_feeder = '-';
        $raw_jk_feeder = $feeder_bio ? ($feeder_bio['jenis_kelamin'] ?? '') : ($feeder_item ? ($feeder_item['jenis_kelamin'] ?? '') : '');
        if (!empty($raw_jk_feeder)) {
            $jk_feeder = ($raw_jk_feeder === 'L') ? 'Laki-Laki' : (($raw_jk_feeder === 'P') ? 'Perempuan' : $raw_jk_feeder);
        }

        return [
            ['key' => 'nim', 'label' => 'NIM / NIPD', 'siska' => $siska_item ? ($siska_item->nim ?: '-') : '-', 'feeder' => $feeder_item ? ($feeder_item['nim'] ?: ($feeder_item['nipd'] ?: '-')) : '-'],
            ['key' => 'nama_mahasiswa', 'label' => 'Nama Mahasiswa', 'siska' => $siska_item ? ($siska_item->nama_mahasiswa ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['nama_mahasiswa'] ?: '-') : ($feeder_item ? ($feeder_item['nama_mahasiswa'] ?: '-') : '-')],
            ['key' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'siska' => $jk_siska, 'feeder' => $jk_feeder],
            ['key' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'siska' => $siska_item ? ($siska_item->tempat_lahir ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['tempat_lahir'] ?: '-') : ($feeder_item ? ($feeder_item['tempat_lahir'] ?: '-') : '-')],
            ['key' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'siska' => $tgl_siska, 'feeder' => $tgl_feeder],
            ['key' => 'nik', 'label' => 'NIK', 'siska' => $siska_item ? ($siska_item->nik ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['nik'] ?: '-') : ($feeder_item ? ($feeder_item['nik'] ?: '-') : '-')],
            ['key' => 'nisn', 'label' => 'NISN', 'siska' => $nisn_siska, 'feeder' => $nisn_feeder],
            ['key' => 'agama', 'label' => 'Agama', 'siska' => $siska_item ? ($siska_item->agama ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['nama_agama'] ?: '-') : ($feeder_item ? ($feeder_item['nama_agama'] ?: '-') : '-')],
            ['key' => 'nama_ibu', 'label' => 'Nama Ibu Kandung', 'siska' => $siska_item ? ($siska_item->nama_ibu ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['nama_ibu_kandung'] ?? '-') : '-'],
            ['key' => 'telepon', 'label' => 'No. Telepon / HP', 'siska' => $siska_item ? ($siska_item->telepon ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['handphone'] ?: ($feeder_bio['telepon'] ?: '-')) : '-'],
            ['key' => 'email', 'label' => 'Email', 'siska' => $siska_item ? ($siska_item->email ?: '-') : '-', 'feeder' => $feeder_bio ? ($feeder_bio['email'] ?: '-') : '-'],
            ['key' => 'program_studi', 'label' => 'Program Studi', 'siska' => $siska_item ? ($siska_item->nama_program_studi ?: '-') : '-', 'feeder' => $feeder_item ? ($feeder_item['nama_program_studi'] ?: '-') : '-'],
            ['key' => 'status', 'label' => 'Status Mahasiswa', 'siska' => $siska_item ? ($siska_item->status === 'A' ? 'Aktif' : 'Non-Aktif') : '-', 'feeder' => $feeder_item ? ($feeder_item['nama_status_mahasiswa'] ?: '-') : '-'],
        ];
    }

    /**
     * Hitung persentase kecocokan 13 atribut dan kelas warna badge:
     * - Hijau (success) jika >= 90%
     * - Oren (warning) jika >= 50% dan < 90%
     * - Merah (danger) jika < 50%
     */
    public function evaluasiKesesuaianAtribut(array $fields)
    {
        $total = count($fields);
        $sesuai = 0;
        $selisih = 0;
        $total_syncable = 0;

        foreach ($fields as &$f) {
            $val_siska = trim((string) ($f['siska'] ?? ''));
            $val_feeder = trim((string) ($f['feeder'] ?? ''));

            $sama = FALSE;
            if ($val_siska === '-' && $val_feeder === '-') {
                $sama = TRUE;
            } elseif ($val_siska !== '-' && $val_feeder !== '-') {
                $clean_s = strtoupper(preg_replace('/\s+/', ' ', $val_siska));
                $clean_f = strtoupper(preg_replace('/\s+/', ' ', $val_feeder));
                $sama = ($clean_s === $clean_f);

                // Toleransi NISN leading zeros
                if (!$sama && ($f['label'] ?? '') === 'NISN') {
                    if (ltrim($clean_s, '0') !== '' && ltrim($clean_s, '0') === ltrim($clean_f, '0')) {
                        $sama = TRUE;
                    }
                }

                // Toleransi Tanggal Lahir
                if (!$sama && ($f['label'] ?? '') === 'Tanggal Lahir') {
                    $ts_s = strtotime($clean_s);
                    $ts_f = strtotime($clean_f);
                    if ($ts_s && $ts_f && date('Y-m-d', $ts_s) === date('Y-m-d', $ts_f)) {
                        $sama = TRUE;
                    }
                }
            }

            $f['sama'] = $sama;
            $f['feeder_has_val'] = ($val_feeder !== '' && $val_feeder !== '-');
            if ($sama) {
                $sesuai++;
            } else {
                $selisih++;
                $f_key = $f['key'] ?? '';
                if ($f['feeder_has_val'] && $f_key !== 'nim' && $f_key !== 'program_studi') {
                    $total_syncable++;
                }
            }
        }
        unset($f);

        $persen_raw = $total > 0 ? ($sesuai / $total) * 100 : 0;
        $persen = round($persen_raw, 1);
        if ($persen == round($persen)) {
            $persen = (int) $persen;
        }

        if ($persen >= 90) {
            $status_class = 'success';
        } elseif ($persen >= 50) {
            $status_class = 'warning';
        } else {
            $status_class = 'danger';
        }

        return [
            'total'          => $total,
            'sesuai'         => $sesuai,
            'selisih'        => $selisih,
            'total_syncable' => $total_syncable,
            'persen'         => $persen,
            'status_class'   => $status_class,
            'fields'         => $fields,
        ];
    }

    /**
     * Detail Komparasi Biodata Mahasiswa SISKA vs Feeder berdasarkan NIM.
     * $use_sync_cache: Jika TRUE, menggunakan cache sesi Feeder (hanya aktif saat refresh pasca-sinkronisasi).
     */
    public function auditBiodataNim($nim, $use_sync_cache = FALSE)
    {
        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['error' => 'NIM wajib diisi.'];
        }

        $siska = $this->Audit_feeder_model->getBiodataMahasiswaSiska($nim);

        // Cek cache sesi Feeder (khusus refresh pasca-sinkronisasi agar instan & tidak berat)
        $feeder_list = NULL;
        $feeder_bio = NULL;
        $loaded_from_cache = FALSE;

        if ($use_sync_cache && isset($this->session)) {
            $cache = $this->session->userdata('feeder_sync_cache_' . $nim);
            if (is_array($cache) && !empty($cache['time']) && (time() - $cache['time'] < 600)) {
                $feeder_list = $cache['feeder_list'] ?? NULL;
                $feeder_bio = $cache['feeder_bio'] ?? NULL;
                $loaded_from_cache = TRUE;
            }
        }

        if (!$loaded_from_cache) {
            // Cari di Feeder via web service jika bukan dari sinkronisasi
            $id_mahasiswa = '';

            $list_res = $this->feederservice->getListMahasiswa("nim='" . str_replace("'", "\\'", $nim) . "' or nipd='" . str_replace("'", "\\'", $nim) . "'", '', 1, 0);
            if (!empty($list_res['data']) && is_array($list_res['data']) && isset($list_res['data'][0])) {
                $feeder_list = $list_res['data'][0];
                $id_mahasiswa = trim((string) ($feeder_list['id_mahasiswa'] ?? ''));
            }

            if ($id_mahasiswa !== '') {
                $bio_res = $this->feederservice->getBiodataMahasiswa($id_mahasiswa);
                if (!empty($bio_res['data'])) {
                    $feeder_bio = $bio_res['data'];
                }
            }
        }

        if (!$siska && !$feeder_list && !$feeder_bio) {
            return ['error' => "Data mahasiswa dengan NIM {$nim} tidak ditemukan di SISKA maupun di Feeder."];
        }

        // Susun 13 atribut komparasi
        $fields = $this->buildMahasiswaFields($siska, $feeder_list, $feeder_bio);
        $eval = $this->evaluasiKesesuaianAtribut($fields);

        return [
            'nim'           => $nim,
            'siska'         => $siska,
            'feeder_list'   => $feeder_list,
            'feeder_bio'    => $feeder_bio,
            'fields'        => $eval['fields'],
            'summary_audit' => $eval,
            'from_cache'    => $loaded_from_cache,
        ];
    }

    /**
     * Sinkronkan nilai biodata dari Feeder ke database SISKA.
     * Dapat dijalankan per field_key spesifik atau 'all' (seluruh atribut yang ada di Feeder dan selisih).
     */
    public function sinkronBiodataFeederKeSiska($nim, $field_key = 'all')
    {
        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['status' => false, 'msg' => 'NIM wajib diisi.'];
        }

        $audit = $this->auditBiodataNim($nim);
        if (isset($audit['error'])) {
            return ['status' => false, 'msg' => $audit['error']];
        }

        $feeder_bio = $audit['feeder_bio'];
        $feeder_list = $audit['feeder_list'];
        $fields = $audit['fields'];

        if (!$feeder_bio && !$feeder_list) {
            return ['status' => false, 'msg' => 'Data mahasiswa tidak ditemukan di Feeder.'];
        }

        // Simpan data Feeder ke cache sesi khusus sinkronisasi
        if (isset($this->session)) {
            $this->session->set_userdata('feeder_sync_cache_' . $nim, [
                'feeder_list' => $feeder_list,
                'feeder_bio'  => $feeder_bio,
                'time'        => time(),
            ]);
        }

        $update_data = [];

        foreach ($fields as $f) {
            $key = $f['key'] ?? '';
            if (empty($key) || $key === 'nim' || $key === 'program_studi' || $key === 'nama_ayah') {
                continue;
            }

            if ($field_key !== 'all' && $field_key !== $key) {
                continue;
            }

            // Hanya sinkron jika data selisih dan di Feeder ada datanya
            if (!empty($f['sama']) || empty($f['feeder_has_val'])) {
                continue;
            }

            $raw_val = trim((string) ($f['feeder'] ?? ''));
            if ($raw_val === '' || $raw_val === '-') {
                continue;
            }

            switch ($key) {
                case 'nama_mahasiswa':
                    $update_data['nama_mahasiswa'] = $raw_val;
                    break;
                case 'jenis_kelamin':
                    $update_data['jenis_kelamin'] = (stripos($raw_val, 'perempuan') !== FALSE || $raw_val === 'P') ? 'P' : 'L';
                    break;
                case 'tempat_lahir':
                    $update_data['tempat_lahir'] = $raw_val;
                    break;
                case 'tanggal_lahir':
                    $raw_feeder_date = $feeder_bio ? ($feeder_bio['tanggal_lahir'] ?? '') : ($feeder_list ? ($feeder_list['tanggal_lahir'] ?? '') : $raw_val);
                    $ts = strtotime($raw_feeder_date);
                    if ($ts) {
                        $update_data['tanggal_lahir'] = date('Y-m-d', $ts);
                    }
                    break;
                case 'nik':
                    $update_data['nik'] = $raw_val;
                    break;
                case 'nisn':
                    $clean_nisn = preg_replace('/\D/', '', $raw_val);
                    $update_data['nisn'] = (strlen($clean_nisn) > 0 && strlen($clean_nisn) < 10)
                        ? str_pad($clean_nisn, 10, '0', STR_PAD_LEFT)
                        : ($clean_nisn ?: $raw_val);
                    break;
                case 'agama':
                    $norm_agama = 'Islam';
                    if (stripos($raw_val, 'hindu') !== FALSE) {
                        $norm_agama = 'Hindu';
                    } elseif (stripos($raw_val, 'katolik') !== FALSE) {
                        $norm_agama = 'Katolik';
                    } elseif (stripos($raw_val, 'kristen') !== FALSE) {
                        $norm_agama = 'Kristen';
                    } elseif (stripos($raw_val, 'bud') !== FALSE) {
                        $norm_agama = 'Budha';
                    } elseif (stripos($raw_val, 'kong') !== FALSE) {
                        $norm_agama = 'Konghucu';
                    }
                    $update_data['agama'] = $norm_agama;
                    break;
                case 'nama_ibu':
                    $update_data['nama_ibu'] = $raw_val;
                    break;
                case 'nama_ayah':
                    $update_data['nama_ayah'] = $raw_val;
                    break;
                case 'telepon':
                    $update_data['telepon'] = $raw_val;
                    break;
                case 'email':
                    $update_data['email'] = $raw_val;
                    break;
                case 'status':
                    $update_data['status'] = (stripos($raw_val, 'aktif') !== FALSE) ? 'A' : 'N';
                    break;
            }
        }

        if (empty($update_data)) {
            return ['status' => false, 'msg' => 'Tidak ada atribut selisih dengan data valid di Feeder yang dapat disinkronkan.'];
        }

        $this->Audit_feeder_model->updateBiodataMahasiswaFromFeeder($nim, $update_data);
        $count = count($update_data);

        // Evaluasi ulang audit instan pasca-update menggunakan cache sesi Feeder
        $fresh = $this->auditBiodataNim($nim, TRUE);
        $summary = $fresh['summary_audit'] ?? NULL;
        $fresh_siska = $fresh['siska'] ?? NULL;

        return [
            'status'         => true,
            'msg'            => "Berhasil menyinkronkan {$count} atribut biodata dari Feeder ke SISKA.",
            'fields_updated' => array_keys($update_data),
            'summary'        => [
                'nim'               => $nim,
                'persen'            => $summary ? $summary['persen'] : 100,
                'status_class'      => $summary ? $summary['status_class'] : 'success',
                'sesuai'            => $summary ? $summary['sesuai'] : 13,
                'selisih'           => $summary ? $summary['selisih'] : 0,
                'status_keterangan' => $summary ? "{$summary['sesuai']} Sesuai, {$summary['selisih']} Selisih" : '13 Sesuai, 0 Selisih',
                'nama_siska'        => $fresh_siska ? (string) $fresh_siska->nama_mahasiswa : '',
                'status_siska'      => $fresh_siska ? ($fresh_siska->status === 'A' ? 'Aktif' : 'Non-Aktif') : '-',
            ]
        ];
    }
}
