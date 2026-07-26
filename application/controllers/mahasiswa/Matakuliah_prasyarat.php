<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Matakuliah_prasyarat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/kurikulum/m_matakuliah_prasyarat',
        ));
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa')
        {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
        $data['conten'] = "mahasiswa/V_Matakuliah_prasyarat";
        $data['judul'] = "Matakuliah Prasyarat";
        $data['data_prasyarat'] = $this->m_matakuliah_prasyarat->get_byid_kurikulum($this->session->userdata('kode_nama_kurikulum')) ?? [];
        


        $this->load->view('mahasiswa/template/V_main', $data);
    }

}
