<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Obrolan extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    public function index() {
        $data['content'] = "dosen/V_obrolan";
        $data['judul'] = 'Obrolan';

        $this->load->view('dosen/template/V_main', $data);
    }

}
