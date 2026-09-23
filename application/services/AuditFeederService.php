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
            'hasil'       => $this->susunHasil($siska_rows, $feeder),
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
        $summary = ['sesuai' => 0, 'berbeda' => 0, 'tidak_ada' => 0, 'kosong' => 0];
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

            $hasil = $this->susunHasil($siska_rows, $feeder);
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
        $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['kode_mata_kuliah' => $kode_matakuliah]);

        return [
            'hasil'       => $this->susunHasil($siska_rows, $feeder),
            'judul_hasil' => 'Audit Nilai SISKA vs Feeder',
            'sub_hasil'   => 'Matakuliah: ' . $kode_matakuliah . ' | ' . $ta->tahun_akademik . ' - ' . ($ta->semester == '1' ? 'Ganjil' : 'Genap'),
            'id_semester' => $id_semester,
            'tampil_ta'   => FALSE,
        ];
    }

    public function petikanFeeder($nim)
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

    private function susunHasil($siska_rows, $feeder)
    {
        $map = $feeder['map'] ?? [];
        $rows = [];
        $summary = ['sesuai' => 0, 'berbeda' => 0, 'tidak_ada' => 0, 'kosong' => 0];

        foreach ($siska_rows as $r) {
            $nim = trim((string) $r->nim);
            $kode = trim((string) $r->kode_matakuliah);
            $key = strtoupper($nim) . '|' . strtoupper($kode);
            $f = $map[$key] ?? NULL;

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
                'nilai_siska'     => $this->formatNilaiTampil($nilai_siska),
                'nilai_huruf_siska' => ($huruf_siska === NULL || $huruf_siska === '') ? 'null' : $huruf_siska,
                'nilai_angka'     => $this->formatNilaiTampil($angka),
                'nilai_huruf'     => ($huruf === NULL || $huruf === '') ? 'null' : $huruf,
                'status'          => $status,
            ];
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
