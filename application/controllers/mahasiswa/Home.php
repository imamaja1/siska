<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
        $data['conten'] = "mahasiswa/V_dashbord";
        $data['judul'] = "Dashbord";

        $this->load->view('mahasiswa/template/V_main', $data);
    }

}
