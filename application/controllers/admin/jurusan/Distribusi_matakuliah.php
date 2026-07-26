<?php

class Distribusi_matakuliah extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
        ));

        $this->load->service('DistribusiService');

        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index()
    {
        $data['content'] = 'admin/jurusan/distribusi_matakuliah/V_index';
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Distribusi Matakuliah';
        $data['title_h1'] = '<i class="fa fa-map"></i> <li>Jurusan</li>';
        $data['title_h2'] = '<li>Distribusi Matakuliah</li>';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('admin/template/V_main',$data);
    }

    public function filter()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');

        $data_sess = array(
            'kode_tahun_akademik_sess' => $kode_tahun_akademik,
        );

        $this->session->set_userdata($data_sess);
        redirect(site_url('admin/jurusan/distribusi_matakuliah/data_distribusi'));
    }

    public function data_distribusi()
    {
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik_sess');
        $hasil = $this->distribusiservice->buildDistribusiData($kode_tahun_akademik);

//        echo "<pre>";
//        print_r($hasil);
//        die();

        $data['content'] = 'admin/jurusan/distribusi_matakuliah/V_distribusi_matakuliah';
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Distribusi Matakuliah';
        $data['title_h1'] = '<i class="fa fa-map"></i> <li>Jurusan</li>';
        $data['title_h2'] = '<li>Distribusi Matakuliah</li>';
        $data['data'] = $hasil;

        $this->load->view('admin/template/V_main',$data);

    }

    public function export()
    {
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik_sess');
        $hasil = $this->distribusiservice->buildDistribusiData($kode_tahun_akademik);

        $data['file_name'] = 'Distribusi_Matakuliah';
        $data['data'] = $hasil;

        $this->load->view('admin/jurusan/distribusi_matakuliah/V_exel',$data);
    }

}