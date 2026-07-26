<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends CI_Controller {

    var $limit = 500;

    public function __construct() {
        parent::__construct();
        // Memuat Service
        $this->load->service('DosenService');
        
        // Pengecekan sesi & RBAC
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            if (!rbac_cek($class, $id_user)) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index($offset = 0) {
        $uri_segment = 5;
        $offset = ($this->uri->segment($uri_segment) == FALSE) ? 0 : $this->uri->segment($uri_segment);
        $data_count = $this->dosenservice->getDosenCount();

        if ($data_count > 0) {
            $this->load->library('pagination');
            $config = array(
                'base_url' => site_url('admin/jurusan/dosen/index'),
                'total_rows' => $data_count,
                'per_page' => $this->limit,
                'uri_segment' => $uri_segment,
                'full_tag_open' => '<div class="btn-group">',
                'full_tag_close' => '</div>',
                'cur_tag_open' => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
                'cur_tag_close' => '</a>',
                'attributes' => array('class' => 'btn btn-flat btn-xs btn-default'),
            );
            $this->pagination->initialize($config);
        }

        $data = array(
            'content' => 'admin/jurusan/dosen/V_Dosen',
            'judul' => 'Jurusan',
            'sub_judul' => 'Data Dosen',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Data Dosen</li>',
            'data_dosen' => $this->dosenservice->getDosenPagination($this->limit, $offset),
            'halaman' => $data_count > 0 ? $this->pagination->create_links() : '',
            'jumlah_data' => $data_count,
            'homebase' => $this->dosenservice->getHomebase(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah() {
        $data = array(
            'content' => 'admin/jurusan/dosen/V_tambah_dosen',
            'judul' => 'Jurusan',
            'sub_judul' => 'Tambah Data Dosen',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Tambah Data Dosen</li>',
            'homebase' => $this->dosenservice->getHomebase(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan() {
        $res = $this->dosenservice->simpanDosen($this->input->post());
        
        if ($res['status'] == false) {
            $this->tambah(); // Kembali ke form jika validasi gagal
        } else {
            $this->session->set_flashdata('info', '<script>swal("Success!", "Data berhasil disimpan", "success");</script>');
            redirect('admin/jurusan/dosen');
        }
    }

    public function edit($kode_dosen) {
        $data = array(
            'content' => 'admin/jurusan/dosen/V_ubah_dosen',
            'judul' => 'Jurusan',
            'sub_judul' => 'Ubah Data Dosen',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Ubah Data Dosen</li>',
            'data_dosen' => $this->dosenservice->getDosenById($kode_dosen),
            'homebase' => $this->dosenservice->getHomebase(),
            'active_1_1' => 'active',
            'active_1_2' => 'active',
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function ubah() {
        $res = $this->dosenservice->ubahDosen($this->input->post());
        
        if ($res['status'] == false) {
            $kode_dosen = $this->input->post('kode_dosen_biodata');
            $this->edit($kode_dosen);
        } else {
            $this->session->set_flashdata('info', '<script>swal("Success!", "Data berhasil disimpan", "success");</script>');
            redirect('admin/jurusan/dosen');
        }
    }

    public function ubah_password() {
        $res = $this->dosenservice->ubahPassword($this->input->post());
        
        if ($res['status'] == false) {
            $kode_dosen = $this->input->post('kode_dosen_password');
            $data = array(
                'content' => 'admin/jurusan/dosen/V_ubah_dosen',
                'judul' => 'Jurusan',
                'sub_judul' => 'Ubah Data Dosen',
                'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
                'title_h2' => '<li>Ubah Data Dosen</li>',
                'data_dosen' => $this->dosenservice->getDosenById($kode_dosen),
                'active_2_1' => 'active',
                'active_2_2' => 'active',
            );
            $this->load->view('admin/template/V_main', $data);
        } else {
            $this->session->set_flashdata('info', '<script>swal("Success!", "Data berhasil disimpan", "success");</script>');
            redirect('admin/jurusan/dosen');
        }
    }

    public function generate_sandi($kode_dosen) {
        $res = $this->dosenservice->generateSandi($kode_dosen);
        
        if ($res['status']) {
            $data = array(
                'content' => "admin/jurusan/dosen/V_sandi",
                'judul' => "Jurusan",
                'sub_judul' => "Sandi Dosen",
                'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
                'title_h2' => '<li>Sandi Dosen</li>',
                'kirim_string' => $res['password_string'],
                'data_dosen' => $this->dosenservice->searchByKodeDosen($kode_dosen)
            );
            $this->session->set_flashdata('info_reset_berhasil', '<script>swal("Sukses!","Password Berhasil di ganti","success")</script>');
            $this->load->view('admin/template/V_main', $data);
        }
    }

    public function hapus($id) {
        $res = $this->dosenservice->hapusDosen($id);
        
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>');
        }
        redirect('admin/jurusan/dosen');
    }

    public function upload_image($kode_dosen) {
        $res = $this->dosenservice->uploadImage($kode_dosen);
        echo json_encode($res);
    }
}
