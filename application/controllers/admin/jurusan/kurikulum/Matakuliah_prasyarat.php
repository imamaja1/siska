<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matakuliah_prasyarat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('MatakuliahService');
        
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
            'content' => 'admin/jurusan/kurikulum/V_Matakuliah_prasyarat',
            'judul' => 'Jurusan',
            'sub_judul' => 'Matakuliah Prasyarat',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Matakuliah Prasyarat</li>',
            'prodi' => $this->matakuliahservice->getJurusan(),
            'hasil' => $this->matakuliahservice->getPrasyarat(),
            'nama_kurikulum' => $this->matakuliahservice->getNamaKurikulum(),
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah_prasyarat() {
        $status = $this->matakuliahservice->simpanPrasyarat($this->input->post());

        if ($status) {
            $this->session->set_flashdata('info', '<script>swal("Success!","Data berhasil disimpan","success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","Data gagal disimpan","error")</script>');
        }
        redirect(site_url('admin/jurusan/kurikulum/matakuliah_prasyarat'));
    }

    public function ubah() {
        $status = $this->matakuliahservice->ubahPrasyarat($this->input->post());

        if ($status) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "Data berhasil diubah", "success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "Data gagal diubah", "error")</script>');
        }
        redirect(site_url('admin/jurusan/kurikulum/matakuliah_prasyarat'));
    }

    public function edit_prasyarat($id, $kode_nama_kuikulum) {
        $data = $this->matakuliahservice->getEditPrasyaratData($id, $kode_nama_kuikulum);
        $this->load->view('admin/jurusan/kurikulum/V_edit_prasyarat', $data);
    }

    public function hapus($id) {
        if ($this->matakuliahservice->hapusPrasyarat($id)) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "Data berhasil dihapus!", "success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "Data gagal dihapus!", "error")</script>');
        }
        redirect(site_url('admin/jurusan/kurikulum/matakuliah_prasyarat'));
    }

    public function get_matakuliah() {
        $nama_kurikulum = $this->input->post('nama_kurikulum');
        $matakuliah = $this->matakuliahservice->getMatakuliahByKurikulum($nama_kurikulum);
        
        $html = '<option value="">Pilih</option>';
        foreach ($matakuliah as $mak) {
            $html .= '<option value="'.$mak->id_matakuliah.'">'.$mak->nama_matakuliah.'</option>';
        }
        echo $html;
    }
}
