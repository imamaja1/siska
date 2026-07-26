<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kurikulum extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/kurikulum/m_data_kurikulum',
            'jurusan/kurikulum/m_nama_kurikulum',
        ));
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
//        $nim = $this->session->userdata('nim');
//        echo $nim;
//        die();
        $kurikulum = $this->m_nama_kurikulum->get_byid($this->session->userdata('kode_nama_kurikulum'));
        $data['conten'] = "mahasiswa/V_kurikulum";
        $data['judul'] = "Kurikulum " . $kurikulum->nama_kurikulum . " | " . $kurikulum->singkatan_program_studi . " | " . $kurikulum->angkatan;
        $data['data'] = $this->m_data_kurikulum->get_data_kurikulum($this->session->userdata('kode_nama_kurikulum'));
      
       $kode_prodi = $this->session->userdata('kode_program_studi');

        // cek program studi punya kompetensi atau tidak
        $kompetensi = $this->mahasiswaservice->getKompetensiByProdi($kode_prodi);

        $data['mk_pilihan'] = array();
        $data['nama_pilihan'] = array();
        if (count($kompetensi) > 0) {
            $data['mk_pilihan'] = array_column($kompetensi, 'id_matakuliah');
            $data['nama_pilihan'] = array_column($kompetensi, 'nama', 'id_matakuliah');
        }

        $this->load->view('mahasiswa/template/V_main', $data);
    }

}