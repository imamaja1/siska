<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenjang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('ProgramStudiService');
        
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
            'content' => 'admin/jurusan/program_studi/V_Jenjang',
            'judul' => 'Jurusan',
            'sub_judul' => 'Jenjang',
            'judul_sub_judul' => 'Program Studi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Program Studi</li>',
            'title_h3' => '<li>Jenjang</li>',
            'data_jenjang' => $this->programstudiservice->getJenjangLengkap(),
            'data_institusi' => $this->programstudiservice->getInstitusi()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah() {
        $res = $this->programstudiservice->simpanJenjang($this->input->post());

        if ($res['status']) {
            $this->session->set_flashdata(
                'info', '<script>swal("Sukses!", "'.$res['msg'].'", "success")</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "'.$res['msg'].'", "error")</script>'
            );
        }
        redirect('admin/jurusan/program_studi/jenjang');
    }

    public function ubah() {
        if ($this->programstudiservice->ubahJenjang($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Sukses!","Ubah Data Jenjang Berhasil","success")</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!","Ubah Data Jenjang Gagal","error")</script>'
            );
        }
        redirect('admin/jurusan/program_studi/jenjang');
    }

    public function hapus($id_jenjang) {
        if ($this->programstudiservice->hapusJenjang($id_jenjang)) {
            $this->session->set_flashdata(
                'info', '<script>swal("Sukses!","Hapus Data Jenjang Berhasil","success")</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!","Hapus Data Jenjang Gagal","error")</script>'
            );
        }
        redirect('admin/jurusan/program_studi/jenjang');
    }

}
