<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AuditFeederService extends MY_Service {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Audit_feeder_model');
        $this->load->model('jurusan/m_tahun_akademik');
        $this->load->service('FeederService');
    }

    public function hasilMahasiswa($nim, $kode_tahun_akademik)
    {
        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['error' => 'NIM wajib diisi.'];
        }

        if ($kode_tahun_akademik === 'all') {
            return $this->hasilMahasiswaSemuaTa($nim);
        }

        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        if (!$ta) {
            return ['error' => 'Tahun akademik tidak valid.'];
        }

        $id_semester = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
        $siska_rows = $this->audit_feeder_model->getNilaiSiskaByNim($nim, $kode_tahun_akademik);
        $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['nim' => $nim]);

        return [
            'hasil'       => $this->susunHasil($siska_rows, $feeder, TRUE),
            'judul_hasil' => 'Audit Nilai SISKA vs Feeder',
            'sub_hasil'   => 'Mahasiswa: ' . $nim . ' | ' . $ta->tahun_akademik . ' - ' . ($ta->semester == '1' ? 'Ganjil' : 'Genap'),
            'id_semester' => $id_semester,
            'tampil_ta'   => FALSE,
        ];
    }

    private function hasilMahasiswaSemuaTa($nim)
    {
        $angkatan = substr($nim, 0, 2);
        $tahun = (int) $angkatan;
        if ($tahun <= 0) {
            return ['error' => 'NIM tidak valid untuk menentukan angkatan.'];
        }

        $start_tahun = 2000 + $tahun;
        $start_ta = $start_tahun . '/' . ($start_tahun + 1);
        $daftar_ta = $this->audit_feeder_model->getTahunAkademikFrom($start_ta);

        $all_rows = [];
        $summary = [
            'sesuai'               => 0,
            'berbeda'              => 0,
            'sesuai_kode_berubah'  => 0,
            'berbeda_kode_berubah' => 0,
            'tidak_ada'            => 0,
            'kosong'               => 0,
            'tidak_ada_siska'      => 0,
        ];
        $feeder_error = '';
        $feeder_total = 0;

        foreach ($daftar_ta as $ta) {
            $siska_rows = $this->audit_feeder_model->getNilaiSiskaByNim($nim, $ta->kode_tahun_akademik);
            if (empty($siska_rows)) {
                continue;
            }

            $id_semester = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
            $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['nim' => $nim]);
            if (!empty($feeder['error'])) {
                $feeder_error = $feeder['error'];
            }
            $feeder_total += (int) ($feeder['total'] ?? 0);

            $hasil = $this->susunHasil($siska_rows, $feeder, TRUE);
            foreach ($hasil['rows'] as $row) {
                $row->tahun_akademik = $ta->tahun_akademik;
                $row->semester_label = ($ta->semester == '1' ? 'Ganjil' : 'Genap');
                $all_rows[] = $row;
            }
            foreach ($summary as $k => $v) {
                $summary[$k] += $hasil['summary'][$k];
            }
        }

        return [
            'hasil' => [
                'rows'         => $all_rows,
                'summary'      => $summary,
                'feeder_keys'  => [],
                'feeder_total' => $feeder_total,
                'feeder_error' => $feeder_error,
            ],
            'judul_hasil' => 'Audit Nilai SISKA vs Feeder',
            'sub_hasil'   => 'Mahasiswa: ' . $nim . ' | Semua Tahun Akademik (mulai ' . $start_ta . ')',
            'id_semester' => '',
            'tampil_ta'   => TRUE,
        ];
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

    /**
     * Cari satu kandidat nilai Feeder untuk tiap NIM (baris null) pada TA terpilih.
     * Kunci pencarian hanya: NIM + Nama Matakuliah + Tahun Akademik.
     * Prioritas: nama matakuliah sama persis (ternormalisasi), lalu paling mirip.
     */
    public function kandidatNullFeeder($kode_tahun_akademik, $nama_matakuliah, $nim_list)
    {
        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        if (!$ta) {
            return ['error' => 'Tahun akademik tidak valid.'];
        }

        $id_semester  = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
        $clean_target = $this->normalizeName($nama_matakuliah);
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

            // Saring hanya TA terpilih.
            $rows_ta = [];
            foreach ($cache[$nim]['rows'] as $row) {
                if (trim((string) $row['id_semester']) === $id_semester) {
                    $rows_ta[] = $row;
                }
            }

            $kandidat  = NULL;
            $metode    = '';
            $best_skor = 0;

            foreach ($rows_ta as $row) {
                $clean = $this->normalizeName($row['nama_mata_kuliah'] ?? '');
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
                'nim'         => $nim,
                'kode'        => $kandidat ? (string) ($kandidat['kode_mata_kuliah'] ?? '') : '',
                'nama_mk'     => $kandidat ? (string) ($kandidat['nama_mata_kuliah'] ?? '') : '',
                'kelas'       => $kandidat ? (string) ($kandidat['nama_kelas'] ?? '') : '',
                'sks'         => $kandidat ? (is_numeric($kandidat['sks']) ? (float) $kandidat['sks'] : $kandidat['sks']) : '',
                'angka'       => $kandidat ? $this->formatNilaiTampil($kandidat['nilai_angka'] ?? NULL) : '',
                'huruf'       => $kandidat ? ((isset($kandidat['nilai_huruf']) && $kandidat['nilai_huruf'] !== NULL && $kandidat['nilai_huruf'] !== '') ? $kandidat['nilai_huruf'] : 'null') : '',
                'metode'      => $metode,
                'skor'        => ($metode === 'persis') ? 100 : (($metode === 'mirip') ? (int) round($best_skor) : 0),
                'total_semua' => count($cache[$nim]['rows']),
                'total_ta'    => count($rows_ta),
                'semester'    => $id_semester,
                'contoh'      => $contoh,
                'error'       => $cache[$nim]['error'],
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

    private function normalizeName($value)
    {
        $value = strtoupper((string) $value);
        $value = str_replace('*', ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
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
    private function saringFeederKelas($feeder, $kelas_norm, $prodi_norm = '')
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
            if ($prodi_norm !== '' && $feeder_punya_prodi && $this->normalizeName($f['prodi'] ?? '') !== $prodi_norm) {
                continue;
            }
            if ($kelas_norm !== '' && $feeder_punya_kelas && $this->normalizeKelas($f['nama_kelas'] ?? '') !== $kelas_norm) {
                continue;
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

        $clean_highlight = $this->normalizeName($highlight_name);

        $header = [
            'nim'                => $rows[0]['nim'] !== '' ? $rows[0]['nim'] : $nim,
            'nama_mahasiswa'     => $rows[0]['nama_mahasiswa'],
            'nama_program_studi' => $rows[0]['nama_program_studi'],
            'angkatan'           => $rows[0]['angkatan'],
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
                'kode_mata_kuliah'    => $row['kode_mata_kuliah'],
                'nama_mata_kuliah'    => $row['nama_mata_kuliah'],
                'sks'                 => $sks,
                'sks_tampil'          => number_format($sks, 2, '.', ''),
                'nilai_angka'         => $row['nilai_angka'],
                'nilai_angka_tampil'  => $this->formatNilaiTampil($row['nilai_angka']),
                'nilai_huruf'         => $row['nilai_huruf'],
                'nilai_huruf_tampil'  => ($row['nilai_huruf'] === NULL || $row['nilai_huruf'] === '') ? 'null' : $row['nilai_huruf'],
                'nilai_indeks'        => $row['nilai_indeks'],
                'nilai_indeks_tampil' => $this->formatNilaiTampil($row['nilai_indeks']),
                'highlight'           => ($clean_highlight !== '' && $this->normalizeName($row['nama_mata_kuliah']) === $clean_highlight),
            ];

            if ($indeks !== NULL) {
                $grouped[$key]['total_sks']  += $sks;
                $grouped[$key]['total_sksn'] += $indeks * $sks;
            }
        }

        ksort($grouped);

        foreach ($grouped as $key => $g) {
            $grouped[$key]['ips'] = $g['total_sks'] > 0 ? $g['total_sksn'] / $g['total_sks'] : 0;
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
                    'kode_mata_kuliah' => $row['kode_mata_kuliah'],
                    'nama_mata_kuliah' => $row['nama_mata_kuliah'],
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
                'kode_mata_kuliah'    => $b['kode_mata_kuliah'],
                'nama_mata_kuliah'    => $b['nama_mata_kuliah'],
                'nama_semester'       => $b['nama_semester'],
                'sks'                 => $sks,
                'sks_tampil'          => number_format($sks, 2, '.', ''),
                'nilai_angka_tampil'  => $this->formatNilaiTampil($b['nilai_angka']),
                'nilai_huruf_tampil'  => ($b['nilai_huruf'] === NULL || $b['nilai_huruf'] === '') ? 'null' : $b['nilai_huruf'],
                'nilai_indeks_tampil' => $this->formatNilaiTampil($b['nilai_indeks']),
                'highlight'           => ($clean_highlight !== '' && $this->normalizeName($b['nama_mata_kuliah']) === $clean_highlight),
            ];

            if ($indeks !== NULL) {
                $grand_sks  += $sks;
                $grand_sksn += $indeks * $sks;
            }
        }

        return [
            'header'      => $header,
            'semester'    => array_values($grouped),
            'konsolidasi' => $konsolidasi,
            'total_sks'   => $grand_sks,
            'total_sksn'  => $grand_sksn,
            'ipk'         => $grand_sks > 0 ? $grand_sksn / $grand_sks : 0,
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
            } elseif (abs(round((float) $nilai_siska, 2) - round((float) $angka, 2)) > 0.001) {
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
                'sumber'          => ($f !== NULL) ? 'both' : 'siska',
                'beda'            => ($status === 'Sesuai') ? 0 : 1,
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
        return number_format(round((float) $v, 2), 2, '.', '');
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
}
