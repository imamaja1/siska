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
        $this->load->service('DashboardService');
        $ta_aktif = $this->dashboardservice->getTahunAkademikAktif();

        $data['content'] = "admin/template/V_dashboard";
        $data['judul'] = "Home";
        $data['sub_judul'] = "";
        $data['stat'] = $this->dashboardservice->getStatistikDashboard($ta_aktif ? $ta_aktif->kode_tahun_akademik : null);
        $data['stat_prodi'] = $this->dashboardservice->getMahasiswaTanpaKelasPerProdi($ta_aktif ? $ta_aktif->kode_tahun_akademik : null);

        $this->load->view('admin/template/V_main', $data);
    }
}
