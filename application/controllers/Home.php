<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('akademik/nilai_model');
        $this->load->model('jurusan/m_tahun_akademik');
        $this->load->service('DosenService');
        $this->load->service('MahasiswaService');
    }

    public function index() {

        if (!$this->session->userdata('nim')) {
            redirect('login');
        }

        $data = array(
            'conten' => 'mahasiswa/template/V_conten',
            'judul' => 'Dashbord'
        );
        $this->load->view('mahasiswa/template/V_main', $data);
    }

    function admin() {

        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }

        $data = array(
            'content' => 'admin/template/V_dashboard',
            'judul' => 'Dashbaord',
            'sub_judul' => 'Dashboard',
            'judul_sub_judul' => '',
            'title_h1' => '<i class="fa fa-home"></i> <li>Dashboard</li>',
            'title_h2' => '',
            'title_h3' => '',
        );

        $this->load->view('admin/template/V_main', $data);
    }

    function dosen() {
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $kode_dosen  = $this->session->userdata('kode_dosen');
        $data = array(
            'content' => 'dosen/template/V_dashboard',
            'judul' => 'Dashbaord',
            'sub_judul' => 'Dashboard',
            'title_h1' => '',
            'title_h2' => '',
            'title_h3' => '',
            'a_dashboard' => 'active',
            'kode_dosen' => $kode_dosen,
            'nama_dosen' => $this->session->userdata('nama_dosen'),
            'chat_id' => $this->dosenservice->getChatIdDosenByKode($kode_dosen)
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function Access_denied() {
        $data = array(
            'conten' => 'mahasiswa/template/V_access_denied',
            'judul' => 'Akses Ditolak',
            'sub_judul' => 'Akses Ditolak',
        );

        $this->load->view('mahasiswa/template/V_main', $data);
    }

    function Access_krs_denied() {
        $data = array(
            'conten' => 'mahasiswa/template/V_access_krs_denied',
            'judul' => 'Akses Ditolak',
            'sub_judul' => 'Akses Ditolak',
        );
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data['krs_mhs'] = $this->mahasiswaservice->getKrsMhsHistorySimple($this->session->userdata('nim'), $tahun_akademik);
        $this->load->view('mahasiswa/template/V_main', $data);
    }
}