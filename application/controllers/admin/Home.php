<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect(site_url('login_admin/login'));
        }
    }

    public function index() {
        $data['content'] = "admin/template/V_dashboard";
        $data['judul'] = "Home";
        $data['sub_judul'] = "";

        $this->load->view('admin/template/V_Main', $data);
    }
}
