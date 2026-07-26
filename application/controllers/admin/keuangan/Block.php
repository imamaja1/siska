<?php

class Block extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
        $this->load->service('KeuanganService');
    }

    public function index(){
        $data['content'] = 'admin/keuangan/block/V_lists';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Block Mahasiswa";
        $data['judul_sub_judul'] = '';
        $data['mahasiswa'] = $this->keuanganservice->getMahasiswaAktif();
		$data['ta'] = $this->keuanganservice->getTahunAkademikList();

        $this->load->view('admin/template/V_main', $data);
    }

    public function get_data(){
        $data['data'] = $this->keuanganservice->getBlockData();
        $this->load->view('admin/keuangan/block/V_data', $data);
    }

    public function get_mahasiswa(){
        $keyword = $this->input->post('keyword');
        $data = $this->keuanganservice->getMahasiswaByKeyword($keyword);
        echo json_encode($data);
    }

    public function store(){
        $this->keuanganservice->insertBlock($this->input->post());
    }

    public function delete($id){
        $this->keuanganservice->deleteBlock($id);
    }
}
