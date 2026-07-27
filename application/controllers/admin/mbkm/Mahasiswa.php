<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect(site_url('login_admin/login'));
        }
        $this->load->model('jurusan/m_tahun_akademik');
        $this->load->service('MbkmService');
    }

    public function index() {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        if (!$kode_tahun_akademik) {
            $kode_tahun_akademik = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $data['ta'] = $kode_tahun_akademik;
        $data['mahasiswa'] = $this->mbkmservice->getMahasiswaMbkm($kode_tahun_akademik);
        $this->load->view('admin/mbkm/V_data_mhs_mbkm', $data);
    }

    public function filter() {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_program_studi = $this->input->post('kode_program_studi');
        $data['ta'] = $kode_tahun_akademik;
        $data['mahasiswa'] = $this->mbkmservice->getMahasiswaMbkm($kode_tahun_akademik, $kode_program_studi);
        $this->load->view('admin/mbkm/V_data_mhs_mbkm', $data);
    }

}
