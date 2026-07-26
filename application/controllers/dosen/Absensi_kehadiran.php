<?php

class Absensi_kehadiran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    public function index() {

        $data['content'] = 'dosen/V_absensi_kehadiran';
        $data['judul'] = 'Absensi Kehadiran Dosen';
        $data['a_absensi_kehadiran'] = 'active';

        $this->load->view('dosen/template/V_main', $data);
    }

 

}
