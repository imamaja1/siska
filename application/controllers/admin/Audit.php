<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Audit extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'Audit_feeder_model',
        ));
        $this->load->service('CekService');
        $this->load->service('FeederService');
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index()
    {
        $data['judul'] = 'Audit Nilai';
        $data['sub_judul'] = 'Audit Nilai |';
        $data['content'] = 'admin/audit/V_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_program_studi = $this->input->post('kode_program_studi');

        $filter_angkatan = ($kode_program_studi != '23');
        $data['data'] = $this->cekservice->getAuditNilai($kode_tahun_akademik, $kode_program_studi, $filter_angkatan);
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_semester();
        $prodi_obj = $this->Nama_jurusan_model->get_kode_by_program_studi($kode_program_studi);
        if (!$prodi_obj) {
            $prodi_obj = $this->db->select('nama_program_studi')->from('program_studi')->where('kode_program_studi', $kode_program_studi)->get()->row_object();
        }
        $data['prodi'] = $prodi_obj;

        $this->load->view('admin/audit/V_hasil', $data);
    }

    public function feeder_mahasiswa()
    {
        $data['judul'] = 'Audit Nilai';
        $data['sub_judul'] = 'Nilai SISKA vs Feeder - Mahasiswa';
        $data['content'] = 'admin/audit/V_feeder_mahasiswa';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function feeder_mahasiswa_hasil()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $nim = trim((string) $this->input->post('nim'));

        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        if (!$ta) {
            echo '<div class="callout callout-danger flat"><p>Tahun akademik tidak valid.</p></div>';
            return;
        }
        if ($nim === '') {
            echo '<div class="callout callout-danger flat"><p>NIM wajib diisi.</p></div>';
            return;
        }

        $id_semester = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
        $siska_rows = $this->Audit_feeder_model->getNilaiSiskaByNim($nim, $kode_tahun_akademik);
        $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['nim' => $nim]);

        $data['hasil'] = $this->susunHasil($siska_rows, $feeder);
        $data['judul_hasil'] = 'Audit Nilai SISKA vs Feeder';
        $data['sub_hasil'] = 'Mahasiswa: ' . $nim . ' | ' . $ta->tahun_akademik . ' - ' . ($ta->semester == '1' ? 'Ganjil' : 'Genap');
        $data['id_semester'] = $id_semester;

        $this->load->view('admin/audit/V_feeder_hasil', $data);
    }

    public function feeder_kelas()
    {
        $data['judul'] = 'Audit Nilai';
        $data['sub_judul'] = 'Nilai SISKA vs Feeder - Kelas';
        $data['content'] = 'admin/audit/V_feeder_kelas';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function feeder_kelas_hasil()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_program_studi = $this->input->post('kode_program_studi');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $kode_matakuliah = trim((string) $this->input->post('kode_matakuliah'));
        $nama_kelas_id = $this->input->post('nama_kelas_id');

        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        if (!$ta) {
            echo '<div class="callout callout-danger flat"><p>Tahun akademik tidak valid.</p></div>';
            return;
        }
        if (empty($id_matakuliah)) {
            echo '<div class="callout callout-danger flat"><p>Matakuliah wajib dipilih.</p></div>';
            return;
        }
        if ($kode_matakuliah === '') {
            echo '<div class="callout callout-danger flat"><p>Kode matakuliah tidak terbaca. Pilih ulang matakuliah.</p></div>';
            return;
        }

        $id_semester = $this->feederservice->idSemester($ta->tahun_akademik, $ta->semester);
        $siska_rows = $this->Audit_feeder_model->getNilaiSiskaByKelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nama_kelas_id);
        $feeder = $this->feederservice->getNilaiFeederMap($id_semester, ['kode_mata_kuliah' => $kode_matakuliah]);

        $data['hasil'] = $this->susunHasil($siska_rows, $feeder);
        $data['judul_hasil'] = 'Audit Nilai SISKA vs Feeder';
        $data['sub_hasil'] = 'Matakuliah: ' . $kode_matakuliah . ' | ' . $ta->tahun_akademik . ' - ' . ($ta->semester == '1' ? 'Ganjil' : 'Genap');
        $data['id_semester'] = $id_semester;

        $this->load->view('admin/audit/V_feeder_hasil', $data);
    }

    public function get_matakuliah($kode_tahun_akademik, $kode_program_studi)
    {
        $rows = $this->Audit_feeder_model->getMatakuliahByProdiTa($kode_tahun_akademik, $kode_program_studi);
        echo json_encode($rows);
    }

    public function get_kelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        $rows = $this->Audit_feeder_model->getKelasByMatakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        echo json_encode($rows);
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
                'nama_mahasiswa'  => $r->nama_mahasiswa,
                'kode_matakuliah' => $kode,
                'nama_matakuliah' => $r->nama_matakuliah,
                'nama_kelas'      => $r->nama_kelas,
                'nilai_siska'     => ($nilai_siska === NULL || $nilai_siska === '') ? NULL : number_format(round((float) $nilai_siska, 2), 2, '.', ''),
                'nilai_angka'     => ($angka === NULL || $angka === '') ? NULL : number_format(round((float) $angka, 2), 2, '.', ''),
                'nilai_huruf'     => $huruf,
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
}