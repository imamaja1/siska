<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Matakuliah_prasyarat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/kurikulum/m_matakuliah_prasyarat',
            'jurusan/kurikulum/m_nama_kurikulum',
            'jurusan/program_studi/Nama_jurusan_model',
        ));
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa')
        {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
        $kode_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $kode_prodi = $this->session->userdata('kode_program_studi');

        $data['conten'] = "mahasiswa/V_Matakuliah_prasyarat";
        $data['judul'] = "Matakuliah Prasyarat";
        $data['data_prasyarat'] = $this->m_matakuliah_prasyarat->get_byid_kurikulum($kode_kurikulum) ?? [];
        $data['kurikulum'] = $this->m_nama_kurikulum->get_byid($kode_kurikulum);
        $data['prodi'] = $this->Nama_jurusan_model->get_all_byid($kode_prodi);
        $data['hide_page_header'] = true;

        $this->load->view('mahasiswa/template/V_main', $data);
    }

}
