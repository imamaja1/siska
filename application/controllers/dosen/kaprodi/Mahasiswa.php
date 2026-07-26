<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class mahasiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/mahasiswa_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'akademik/Krs_model',
        ));
        $this->load->service('KaprodiService');

        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect(site_url('denied'));
        }
    }

    public function index() {

        $kode_dosen = $this->session->userdata('kode_dosen');

        $kode_program_studi = $this->kaprodiservice->get_kaprodi_prodi_array($kode_dosen);

        $nama_program_studi = $this->kaprodiservice->get_prodi_nama($kode_program_studi['kode_program_studi']);

        $jumlah_semua = $this->kaprodiservice->get_jumlah_semua_mahasiswa($kode_program_studi['kode_program_studi']);
       
        $jumlah_aktif = $this->kaprodiservice->get_jumlah_aktif_mahasiswa($kode_program_studi['kode_program_studi']);

        $jumlah_tidak_aktif = $this->kaprodiservice->get_jumlah_tidak_aktif_mahasiswa($kode_program_studi['kode_program_studi']);

        $data = array(
            'content' => 'dosen/kaprodi/mahasiswa/V_mahasiswa_semua',
            'judul' => 'Mahasiswa ' . $nama_program_studi['nama_program_studi'],
            'sub_judul' => 'Mahasiswa',
            'title_h1' => '<li>Prodi</li>',
            'a_data_semua_mahasiswa' => 'active',
            'a_mahasiswa_prodi' => 'active',
            'title_h2' => '<li>Mahasiswa</li>',
            'data_mahasiswa' => "",
            'status' => "false",
            'jumlah_semua' => $jumlah_semua,
            'jumlah_aktif' => $jumlah_aktif,
            'jumlah_tidak_aktif' => $jumlah_tidak_aktif,
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function semua($id) {
        $kode_dosen = $this->session->userdata('kode_dosen');

        $kode_program_studi = $this->kaprodiservice->get_kaprodi_prodi_array($kode_dosen);

        $nama_program_studi = $this->kaprodiservice->get_prodi_nama($kode_program_studi['kode_program_studi']);

        $query = $this->kaprodiservice->get_mahasiswa_by_angkatan($kode_program_studi['kode_program_studi'], $id);

        $query_count = $this->kaprodiservice->get_jumlah_mahasiswa_angkatan($kode_program_studi['kode_program_studi']);

        $data = array(
            'content' => 'dosen/kaprodi/mahasiswa/V_mahasiswa_semua',
            'judul' => 'Mahasiswa ' . $nama_program_studi['nama_program_studi'],
            'sub_judul' => 'Mahasiswa',
            'title_h1' => '<li>Prodi</li>',
            'a_data_semua_mahasiswa' => 'active',
            'a_mahasiswa_prodi' => 'active',
            'title_h2' => '<li>Mahasiswa</li>',
            'data_mahasiswa' => $query,
            'status' => "true",
            'hitung_jumlah' => $query_count,
        );

        $this->load->view('dosen/template/V_main', $data);
    }

}
