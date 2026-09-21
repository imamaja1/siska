<?php

class Kop_prodi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }

        $id_user = $this->session->userdata('id');
        if (!rbac_cek('Nama_jurusan', $id_user)) {
            redirect(site_url('denied'));
        }

        $this->load->service('KopProdiService');
    }

    public function index()
    {
        $prodi = $this->kopprodiservice->getProdiList();

        $kop = array();
        foreach ($prodi as $row) {
            $kop[$row->kode_program_studi] = $this->kopprodiservice->getKopFile($row->kode_program_studi);
        }

        $data['content'] = 'admin/akademik/kop_prodi/V_index';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Kop Prodi';
        $data['judul_sub_judul'] = '';
        $data['prodi'] = $prodi;
        $data['kop'] = $kop;

        $this->load->view('admin/template/V_main', $data);
    }

    public function upload($kode_program_studi)
    {
        $res = $this->kopprodiservice->uploadKop($kode_program_studi);
        echo json_encode($res);
    }

    public function reset($kode_program_studi)
    {
        $res = $this->kopprodiservice->resetKop($kode_program_studi);
        echo json_encode($res);
    }
}
