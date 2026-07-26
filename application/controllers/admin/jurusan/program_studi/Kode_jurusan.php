<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kode_jurusan extends CI_Controller {

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
            'content' => 'admin/jurusan/program_studi/V_Kode_jurusan',
            'judul' => 'Jurusan',
            'sub_judul' => 'Kode Jurusan',
            'judul_sub_judul' => 'Program Studi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Program Studi</li>',
            'title_h3' => '<li>Kode Jurusan</li>',
            'data' => $this->programstudiservice->getKodeJurusanLengkap(),
            'institusi' => $this->programstudiservice->getInstitusi()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan() {
        $res = $this->programstudiservice->simpanKodeJurusan($this->input->post());

        if ($res['status']) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/Kode_jurusan');
    }

    public function ubah() {
        if ($this->programstudiservice->ubahKodeJurusan($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Jurusan berhasil diubah", "success");</script>');
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Jurusan gagal diubah", "error");</script>');
        }
        redirect('admin/jurusan/program_studi/Kode_jurusan');
    }

    public function hapus($id) {
        if ($this->programstudiservice->hapusKodeJurusan($id)) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Jurusan berhasil dihapus", "success");</script>');
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Jurusan gagal dihapus", "error");</script>');
        }
        redirect('admin/jurusan/program_studi/Kode_jurusan');
    }

}
