<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ketua_jurusan extends CI_Controller {

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
            'content' => 'admin/jurusan/program_studi/V_Ketua_jurusan',
            'judul' => 'Jurusan',
            'sub_judul' => 'Ketua Jurusan',
            'judul_sub_judul' => 'Program Studi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Program Studi</li>',
            'title_h3' => '<li>Ketua Jurusan</li>',
            'data' => $this->programstudiservice->getKetuaJurusanLengkap(),
            'nama_jurusan' => $this->programstudiservice->getNamaJurusanLengkap(),
            'dosen' => $this->programstudiservice->getDosen()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan() {
        $res = $this->programstudiservice->simpanKetuaJurusan($this->input->post());

        if ($res['status']) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/Ketua_jurusan');
    }

    public function edit($kode_kaprodi) {
        $data['data'] = $this->programstudiservice->getKetuaJurusanById($kode_kaprodi);
        $data['nama_jurusan'] = $this->programstudiservice->getNamaJurusanLengkap();
        $data['dosen'] = $this->programstudiservice->getDosen();

        $this->load->view('admin/jurusan/program_studi/Modal_edit_kaprodi', $data);
    }

    public function ubah() {
        if ($this->programstudiservice->ubahKetuaJurusan($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Kaprodi berhasil diubah", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Kaprodi gagal diubah", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/Ketua_jurusan');
    }

    public function hapus($id) {
        if ($this->programstudiservice->hapusKetuaJurusan($id)) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data Kaprodi berhasil dihapus", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data Kaprodi gagal dihapus", "error");</script>'
            );
        }
        redirect('admin/jurusan/program_studi/Ketua_jurusan');
    }

    public function upload_image($kode_kaprodi) {
        $res = $this->programstudiservice->uploadImageKetuaJurusan($kode_kaprodi);
        echo json_encode($res);
    }

}
