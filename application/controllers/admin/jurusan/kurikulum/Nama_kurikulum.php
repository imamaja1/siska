<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nama_kurikulum extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('KurikulumService');
        
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
            'content' => 'admin/jurusan/kurikulum/V_Nama_kurikulum',
            'judul' => 'Jurusan',
            'sub_judul' => 'Nama Kurikulum',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Nama Kurikulum</li>',
            'data' => $this->kurikulumservice->getNamaKurikulumLengkap(),
            'prodi' => $this->kurikulumservice->getProgramStudi()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan() {
        if ($this->kurikulumservice->simpanNamaKurikulum($this->input->post())) {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Success!", "Data berhasil disimpan", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Gagal!", "Data gagal disimpan", "error");</script>'
            );
        }
        redirect(site_url('admin/jurusan/kurikulum/nama_kurikulum'));
    }

    public function hapus($kode_nama_kurikulum) {
        if ($this->kurikulumservice->hapusNamaKurikulum($kode_nama_kurikulum)) {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Success!", "Data berhasil dihapus", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Gagal!", "Data gagal dihapus", "error");</script>'
            );
        }
        redirect(site_url('admin/jurusan/kurikulum/nama_kurikulum'));
    }

    public function ubah() {
        if ($this->kurikulumservice->ubahNamaKurikulum($this->input->post())) {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Success!", "Data berhasil diubah", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Gagal!", "Data gagal diubah", "error");</script>'
            );
        }
        redirect(site_url('admin/jurusan/kurikulum/nama_kurikulum'));
    }

}
