<?php

class Update_penilaian extends CI_Controller {

    var $limit = 25;
    var $kode_program_studi;

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'laporan/laporan_model',
        ));
        $this->load->service('KaprodiService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function validasi_nilai_uts() {
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_array($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $kelas = $this->kaprodiservice->get_kelas_update_uts_index($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/kaprodi/update-nilai/V_index_uts';
        $data['judul'] = 'Validas Nilai UTS';
        $data['kelas'] = $kelas;
       
        $data['a_update_nilai_kaprodi'] = 'active';
        $data['a_update_nilai_uts_prodi'] = 'active';

        $this->load->view('dosen/template/V_main', $data);
    }

    public function validasi_nilai_uas() {
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_array($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $data['content'] = 'dosen/kaprodi/validasi-nilai/V_index_uas';
        $data['judul'] = 'Validas Nilai UAS';
        $data['kelas'] = $kelas;
       
        $data['a_validasi_nilai_kaprodi'] = 'active';
        $data['a_validasi_nilai_uas_prodi'] = 'active';

        $this->load->view('dosen/template/V_main', $data);
    }

    public function kelas_uts() {
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_array($this->session->userdata('kode_dosen'));
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $kelas = $this->kaprodiservice->get_kelas_update_uts($kode_prodi, $kode_tahun_akademik);

        $data['kelas'] = $kelas;

        $pesan_dosen = array();
        foreach ($kelas as $key => $value) {
            $tmp_dosen = $this->kaprodiservice->get_catatan_revisi_count($value->kelas_id);
            $pesan_dosen[$value->kelas_id] = count($tmp_dosen);
        }
        $data['pesan_dosen'] = $pesan_dosen;

        $this->load->view('dosen/kaprodi/update-nilai/V_kelas_uts', $data);
    }

    public function kelas_uas() {
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_array($this->session->userdata('kode_dosen'));
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $kelas = $this->kaprodiservice->get_kelas_validasi_uas($kode_prodi, $kode_tahun_akademik);
      
        $data['kelas'] = $kelas;

        $pesan_dosen = array();
        foreach ($kelas as $key => $value) {
            $tmp_dosen = $this->kaprodiservice->get_catatan_revisi_uas_count($value->kelas_id);
            $pesan_dosen[$value->kelas_id] = count($tmp_dosen);
        }
        $data['pesan_dosen'] = $pesan_dosen;

        $this->load->view('dosen/kaprodi/validasi-nilai/V_kelas_uas', $data);
    }

    public function cari_kelas() {
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_row($this->session->userdata('kode_dosen'));
        $this->kode_program_studi = $prodi->kode_program_studi;
        $keyword = $this->input->post('keyword');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        if ($keyword == '') {
            $this->kelas_uas();
        } else {
            $kelas = $this->kaprodiservice->get_kelas_search($this->kode_program_studi, $kode_tahun_akademik, $keyword);
            $data['kelas'] = $kelas;

            $this->load->view('dosen/kaprodi/validasi-nilai/V_kelas', $data);
        }
    }

    public function data_mahasiswa_uts($kelas_id) {
      
        $kelas_mahasiswa = $this->kaprodiservice->get_mahasiswa_update_uts($kelas_id);

        $data_kelas = $this->kaprodiservice->get_data_kelas_update($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/kaprodi/update-nilai/V_nilai_uts', $data);
    }
    
    public function lihat_data_mahasiswa_uts($kelas_id) {
      
        $kelas_mahasiswa = $this->kaprodiservice->get_mahasiswa_uts_asc($kelas_id);

        $data_kelas = $this->kaprodiservice->get_data_kelas($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/kaprodi/validasi-nilai/V_lihat_nilai_uts', $data);
    }

    public function lihat_data_mahasiswa_uas($kelas_id) {
      
        $kelas_mahasiswa = $this->kaprodiservice->get_mahasiswa_uts_asc($kelas_id);

        $data_kelas = $this->kaprodiservice->get_data_kelas($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/kaprodi/validasi-nilai/V_lihat_nilai_uas', $data);
    }

    public function data_mahasiswa($kelas_id) {
        $kelas_mahasiswa = $this->kaprodiservice->get_mahasiswa_uas($kelas_id);
        $data_kelas = $this->kaprodiservice->get_data_kelas($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/kaprodi/validasi-nilai/V_nilai_uas', $data);
    }

    public function revisi_nilai($kelas_id) {
        return $this->kaprodiservice->update('kelas', array('status_nilai' => 'F'), array('kelas_id' => $kelas_id));
    }

    public function revisi_uts($kelas_id) {
        $note = htmlspecialchars($this->input->post('catatan_prodi'));
        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));
        $this->kaprodiservice->update('dummy_update_kelas', array('status_uts_prodi' => 'R','status_uts_dosen' => 'R'), array('id_kelas' => $kelas_id));
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function rev_uas() {
        $note = htmlspecialchars($this->input->post('catatan_prodi'));
        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));
        $this->kaprodiservice->update('kelas', array('catatan_prodi' => $note, 'status_nilai' => 'R','status_revisi_uas' => '1'), array('kelas_id' => $kelas_id));
        $this->kaprodiservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'R', 'catatan_prodi' => $note, 'validasi_prodi' => 'R']);

        $query_dosen_kelas = $this->kaprodiservice->get_query_dosen_kelas($kelas_id);

        $query_prodi = $this->kaprodiservice->get_query_prodi($kelas_id);

        $message_text = "*SISKA UBG* - Catatan " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . " untuk nilai UAS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", yaitu: " . $note . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id,
            'pesan_prodi' => $note,
            'param_prodi' => 1,
            'kode_dosen' => 1,
            'kode_prodi' => 1,
            'tgl_prodi' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));

        $this->kaprodiservice->insert('catatan_revisi_uas', $massage1);
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function rev_uts() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $note = htmlspecialchars($this->input->post('catatan_prodi'));

        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));

        $this->kaprodiservice->update('kelas', array('status_nilai_uts' => 'R', 'validasi_nilai_uts' => 'R','status_revisi_uts' => '1'), array('kelas_id' => $kelas_id));
        $this->kaprodiservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian_uts' => 'R', 'validasi_prodi_uts' => 'R']);

        $this->kaprodiservice->insert('catatan_revisi', ['kelas_id' => $kelas_id, 'kode_prodi' => $kode_dosen, 'pesan_prodi' => $note, 'param_prodi' => 0, 'tgl_prodi' => date("Y-m-d H:i:s")]);

        $query_dosen_kelas = $this->kaprodiservice->get_query_dosen_kelas_no_pnd($kelas_id);

        $query_prodi = $this->kaprodiservice->get_query_prodi($kelas_id);

        $message_text = "*SISKA UBG* - Catatan " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . " untuk nilai UTS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", yaitu: " . $note . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";

        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id,
            'pesan_prodi' => $note,
            'param_prodi' => 1,
            'kode_dosen' => 1,
            'kode_prodi' => 1,
            'tgl_prodi' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));

        $this->kaprodiservice->insert('catatan_revisi', $massage1);

        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function validasi($kelas_id) {
        $nilai = $this->kaprodiservice->validasi_kelas_dummy($kelas_id);
        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_harian' => $row->dummy_harian,
                'nilai_uts' => $row->dummy_uts,
                'nilai_uas' => $row->dummy_uas,
                'nilai_akhir' => $row->dummy_na,
            );
            $ubah = $this->kaprodiservice->update_khs_detail($row->dummy_id, $data_nilai);

            if ($ubah) {
                $res['st'][] = true;
            } else {
                $res['st'][] = false;
            }
        }

        if (count(array_unique($res['st'])) === 1) {
            if (current($res['st']) == true) {
                $re['status'] = true;
                $this->kaprodiservice->update('kelas', array('validasi_nilai' => 'T'), array('kelas_id' => $kelas_id));
            }
        }
    }

    public function validasi_prodi($kelas_id) {
        $this->kaprodiservice->update('kelas', array('validasi_nilai' => 'T'), array('kelas_id' => $kelas_id));
        $this->kaprodiservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'T', 'validasi_prodi' => 'T']);

        $query_dosen_kelas = $this->kaprodiservice->get_query_dosen_kelas($kelas_id);

        $query_prodi = $this->kaprodiservice->get_query_prodi($kelas_id);

        $query_fakultas = $this->kaprodiservice->get_query_fakultas($kelas_id);

        $message_text1 = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah disetujui oleh " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . " selanjutnya menunggu validasi dari Dekan " . $query_fakultas['nama_fakultas'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text1);

        $message_text2 = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", menunggu validasi dari" . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
        kirim_ke_telegram($query_fakultas['chatid'], $message_text2);

        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function validasi_prodi_uts($kelas_id) {
        $this->kaprodiservice->update('dummy_update_kelas', array('status_uts_prodi' => 'T'), array('id_kelas' => $kelas_id));

        $query_dosen_kelas = $this->kaprodiservice->get_query_dosen_kelas_no_pnd($kelas_id);

        $query_prodi = $this->kaprodiservice->get_query_prodi($kelas_id);

        $query_fakultas = $this->kaprodiservice->get_query_fakultas($kelas_id);
    }

}
