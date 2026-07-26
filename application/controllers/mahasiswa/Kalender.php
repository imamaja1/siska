<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kalender extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'jurusan/m_tahun_akademik',
        ));
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }

    }

    public function index()
    {
        $ta = $this->m_tahun_akademik->get_semester();
        $tahun_akadmeik = $ta->ta;
        $data = array(
            'conten' => 'mahasiswa/V_kalender',
            'judul' => 'Kalender Akademik ' . $tahun_akadmeik,
        );

        $this->load->view('mahasiswa/template/V_main', $data);
    }
}