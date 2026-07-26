<?php

class validasikhusus extends CI_Controller {

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
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

//        if (!isDekan($this->session->userdata('kode_dosen'))) {
//            redirect('denied');
//        }
        $this->load->library('pagination');
        $this->load->service('DosenService');
    }

    public function index() {
//        $id_dekan = $this->session->userdata('kode_dosen');

        $data['kode_fakultas'] = $this->dosenservice->getAllFakultas();
        $kode_fkk = $this->dosenservice->getFakultasById(1);

        $prodi = $this->Fakultas_model->getProdiFromDekan($kode_fkk['dekan']);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dosenservice->getKelasForValidasi($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/V_validasi_khusus';
        $data['judul'] = 'Informasi Pembagian Kelas dan Validasi yang Sudah Masuk';
        $data['kelas'] = $kelas;

        $this->load->view('dosen/template/V_main', $data);
    }

    public function cari() {
//        $id_dekan = $this->session->userdata('kode_dosen');

        $data['kode_fakultas'] = $this->dosenservice->getAllFakultas();

        $kode_fakultas = $this->input->post("kd_fk");
        $data['match_kode_fakultas'] =  $kode_fakultas;

        $prodi = $this->Fakultas_model->getProdiFromDekan($kode_fakultas);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dosenservice->getKelasForValidasi($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/V_validasi_khusus';
        $data['judul'] = 'Informasi Pembagian Kelas dan Validasi yang Sudah Masuk';
        $data['kelas'] = $kelas;

        $this->load->view('dosen/template/V_main', $data);
    }

}
