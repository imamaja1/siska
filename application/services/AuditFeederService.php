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
