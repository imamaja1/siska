<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nama_jurusan extends CI_Controller
{
    public function __construct()
    {
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

    public function index()
    {
        $data = array(
            'content' => 'admin/jurusan/program_studi/V_Nama_jurusan',
            'judul' => 'Jurusan',
            'sub_judul' => 'Nama Jurusan',
            'judul_sub_judul' => 'Program Studi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Program Studi</li>',
            'title_h3' => '<li>Nama Jurusan</li>',
            'data' => $this->programstudiservice->getNamaJurusanLengkap(),
            'kode_jenjang' => $this->programstudiservice->getJenjang(),
            'kode_jurusan' => $this->programstudiservice->getKodeJurusanLengkap(),
            'fakultas' => $this->programstudiservice->getFakultas(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan()
    {
        if ($this->programstudiservice->simpanNamaJurusan($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Nama Jurusan berhasil disimpan", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Nama Jurusan gagal disimpan", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/nama_jurusan');
    }

    public function edit($kode_program_studi)
    {
        $data['fakultas'] = $this->programstudiservice->getFakultas();
        $data['data'] = $this->programstudiservice->getNamaJurusanByKode($kode_program_studi);
        $this->load->view('admin/jurusan/program_studi/Modal_edit_prodi', $data);
    }

    public function ubah()
    {
        if ($this->programstudiservice->ubahNamaJurusan($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Nama Jurusan berhasil diubah", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Nama Jurusan gagal diubah", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/nama_jurusan');
    }

    public function hapus($id)
    {
        if ($this->programstudiservice->hapusNamaJurusan($id)) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Nama Jurusan berhasil dihapus", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Nama Jurusan gagal dihapus", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/nama_jurusan');
    }
}
