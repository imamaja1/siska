<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kompetensi extends CI_Controller {

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
            'content' => 'admin/jurusan/program_studi/V_Kompetensi',
            'judul' => 'Jurusan',
            'sub_judul' => 'Kompetensi',
            'judul_sub_judul' => 'Program Studi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Program Studi</li>',
            'title_h3' => '<li>Kompetensi</li>',
            'data' => $this->programstudiservice->getKompetensiLengkap(),
            'kode_nama_jurusan' => $this->programstudiservice->getNamaJurusanLengkap() // Changed from Nama_jurusan_model->get() since they map to the same table, though getNamaJurusanLengkap joins with fakultas which is better
        );
        // Wait, Nama_jurusan_model->get() just returns program_studi without joins. 
        // I will use getNamaJurusanLengkap or wait, let's just make a simple getProgramStudi in the service.
        // Actually, $this->programstudiservice->getNamaJurusanLengkap() works fine because it has kode_program_studi and nama_program_studi.

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan() {
        if ($this->programstudiservice->simpanKompetensi($this->input->post())) {
            $this->session->set_flashdata(
                    'info', '<script>swal("Success!", "Data berhasil disimpan", "success");</script>');
        } else {
            $this->session->set_flashdata(
                    'info', '<script>swal("Gagal!", "Data gagal disimpan", "error");</script>');
        }
        redirect('admin/jurusan/program_studi/Kompetensi');
    }

    public function ubah() {
        if ($this->programstudiservice->ubahKompetensi($this->input->post())) {
            $this->session->set_flashdata(
                    'info', '<script>swal("Success!", "Data berhasil diubah", "success");</script>');
        } else {
            $this->session->set_flashdata(
                    'info', '<script>swal("Gagal!", "Data gagal diubah", "error");</script>');
        }
        redirect('admin/jurusan/program_studi/Kompetensi');
    }

    public function hapus($id) {
        if ($this->programstudiservice->hapusKompetensi($id)) {
            $this->session->set_flashdata(
                    'info', '<script>swal("Success!", "Data berhasil dihapus", "success");</script>');
        } else {
            $this->session->set_flashdata(
                    'info', '<script>swal("Gagal!", "Data gagal dihapus", "error");</script>');
        }
        redirect('admin/jurusan/program_studi/Kompetensi');
    }

}
