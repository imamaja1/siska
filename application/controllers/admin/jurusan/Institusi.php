<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institusi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('UniversitasService');
        
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index() {
        $data = array(
            'content' => 'admin/jurusan/V_Institusi',
            'judul' => 'Jurusan',
            'sub_judul' => 'Institusi',
            'judul_sub_judul' => '',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Institusi</li>',
            'title_h3' => '',
            'data_institusi' => $this->universitasservice->getInstitusiLengkap()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah() {
        $res = $this->universitasservice->simpanInstitusi($this->input->post());

        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '","error")</script>');
        }
        redirect('admin/jurusan/institusi');
    }

    public function ubah() {
        $res = $this->universitasservice->ubahInstitusi($this->input->post());

        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '","error")</script>');
        }
        redirect('admin/jurusan/institusi');
    }

    public function hapus($kode) {
        $res = $this->universitasservice->hapusInstitusi($kode);

        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '","error")</script>');
        }
        redirect('admin/jurusan/institusi');
    }
}
