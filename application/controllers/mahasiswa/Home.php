<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
        $nim = $this->session->userdata('nim');
        $data['conten'] = "mahasiswa/V_dashbord";
        $data['judul'] = "Dashboard";
        $data['hide_page_header'] = true;
        $data['prodi'] = get_kode_prodi($nim);
        $data['tahun_akademik'] = tahun_akademik();
        $data['angkatan'] = '20' . substr($nim, 0, 2);
        $data['pembayaran'] = pembayaran_mahasiswa($nim);

        $this->load->view('mahasiswa/template/V_main', $data);
    }

}
