<?php

class Update_penilaian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/universitas/Fakultas_model',
            'laporan/laporan_model',
            'akademik/Nilai_model'
        ));
        $this->load->service('DekanService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isDekan($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function index() {
        $id_dekan = $this->session->userdata('kode_dosen');

        $prodi = $this->Fakultas_model->getProdiFromDekan($id_dekan);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dekanservice->get_kelas_validasi_simple($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/dekan/V_penilaian';
        $data['judul'] = 'Validas Nilai';
        $data['kelas'] = $kelas;

        $this->load->view('dosen/template/V_main', $data);
    }

    public function kelas_uts() {
        $id_dekan = $this->session->userdata('kode_dosen');
        $prodi = $this->Fakultas_model->getProdiFromDekan($id_dekan);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dekanservice->get_kelas_update_uts_dekan($kode_prodi, $kode_tahun_akademik);
        $data['kelas'] = $kelas;

        $pesan_dosen = array();
        foreach ($kelas as $key => $value) {
            $tmp_dosen = $this->dekanservice->get_catatan_revisi_dekan_count($value->kelas_id);
            $pesan_dosen[$value->kelas_id] = count($tmp_dosen);
        }
        $data['pesan_dosen'] = $pesan_dosen;

        $this->load->view('dosen/dekan/update-nilai/V_kelas_uts', $data);
    }

    public function kelas_uas() {
        $id_dekan = $this->session->userdata('kode_dosen');
        $prodi = $this->Fakultas_model->getProdiFromDekan($id_dekan);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dekanservice->get_kelas_update_uas_dekan($kode_prodi, $kode_tahun_akademik);

        $data['kelas'] = $kelas;
    }

    public function data_mahasiswa_uts($kelas_id) {
        $kelas_mahasiswa = $this->dekanservice->get_mahasiswa_update_uts_dekan($kelas_id);

        $data_kelas = $this->dekanservice->get_kelas_update_detail_dekan($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/dekan/update-nilai/V_nilai_uts', $data);
    }

    public function data_mahasiswa_uas($kelas_id) {
        $kelas_mahasiswa = $this->dekanservice->get_mahasiswa_uas_dekan($kelas_id);
        $data_kelas = $this->dekanservice->get_data_kelas_dekan($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/dekan/V_nilai_uas', $data);
    }

    public function revisi_uas() {
        $note = htmlspecialchars($this->input->post('catatan_prodi'));
        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));
        
        $this->dekanservice->update('kelas', array('catatan_dekan' => $note, 'status_nilai' => 'R', 'validasi_dekan' => 'R', 'validasi_nilai' => 'R'), array('kelas_id' => $kelas_id));
        $this->dekanservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'R', 'validasi_dekan' => 'R', 'validasi_prodi' => 'R']);

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);
        
        $query_prodi = $this->dekanservice->get_query_prodi_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Catatan " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . " untuk nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", yaitu: " . $note . ", Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);
        kirim_ke_telegram($query_prodi['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $note,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));

        $this->dekanservice->insert('catatan_revisi_uas',$massage1);
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function revisi_uts() {
        $note = htmlspecialchars($this->input->post('catatan_prodi'));
        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));
        
        $this->dekanservice->update('dummy_update_kelas', array('status_uts_dosen' => 'R', 'status_uts_prodi' => 'R', 'status_uts_dekan' => 'R'), array('id_kelas' => $kelas_id));
        
        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);
        
        $query_prodi = $this->dekanservice->get_query_prodi_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function validasi_uts($kelas_id) {
        $nilai = $this->dekanservice->get_dummy_nilai_update_uts($kelas_id);

        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_uts' => $row->uts,
                'nilai_akhir' => $row->na,
            );
            $ubah = $this->Nilai_model->validasi_dekan($row->kode_khs_detail, $data_nilai);
            if ($ubah) {
                $res['st'][] = true;
            } else {
                $res['st'][] = false;
            }
        }

        $this->dekanservice->update('dummy_update_kelas', array('status_uts_dekan' => 'T'), array('id_kelas' => $kelas_id));

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah disetujui oleh " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", nilai per matakuliah dapat dicetak pada menu penilaian untuk keperluan BKD/Lainnya, Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $message_text,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));
       
        $resss = $this->dekanservice->insert('catatan_revisi',$massage1);
        echo $resss;
    }

    public function validasi_uas($kelas_id) {
        $nilai = $this->dekanservice->get_dummy_nilai_uas($kelas_id);
        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_harian' => $row->dummy_harian,
                'nilai_uts' => $row->dummy_uts,
                'nilai_uas' => $row->dummy_uas,
                'nilai_akhir' => $row->dummy_na,
            );
            $ubah = $this->Nilai_model->validasi_dekan($row->dummy_id, $data_nilai);
            if ($ubah) {
                $res['st'][] = true;
            } else {
                $res['st'][] = false;
            }
        }
        $this->dekanservice->update('kelas', array('validasi_dekan' => 'T'), array('kelas_id' => $kelas_id));
        $this->dekanservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'T', 'validasi_prodi' => 'T', 'validasi_dekan' => 'T']);

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah disetujui oleh " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", nilai per matakuliah dapat dicetak pada menu penilaian untuk keperluan BKD/Lainnya, Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $message_text,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));
       
        $resss = $this->dekanservice->insert('catatan_revisi_uas',$massage1);
        echo $resss;
    }

   public function validasi_nilai_uas() {
        $dekan = $this->dekanservice->get_dekan_prodi($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $kelas = $this->dekanservice->get_kelas_validasi_simple($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/dekan/V_penilaian_uas';
        $data['judul'] = 'Validas Nilai UAS';
        $data['kelas'] = $kelas;
        
        $data['a_validasi_nilai_dekan'] = 'active';
        $data['a_validasi_nilai_uas_dekan'] = 'active';

       
        $this->load->view('dosen/template/V_main', $data);
    }
    public function validasi_nilai_uts() {
        $dekan = $this->dekanservice->get_dekan_prodi($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $data['content'] = 'dosen/dekan/update-nilai/V_index_uts';
        $data['judul'] = 'Validas Nilai UTS';
        $data['kelas'] = $kelas;
        
        $data['a_update_nilai_dekan'] = 'active';
        $data['a_update_nilai_uts_dekan'] = 'active';

        $this->load->view('dosen/template/V_main', $data);
    }
}
