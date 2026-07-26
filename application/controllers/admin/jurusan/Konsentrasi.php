<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konsentrasi extends CI_Controller {

    var $limit = 20;

    public function __construct() {
        parent::__construct();
        $this->load->service('ProgramStudiService');
        $this->load->library(array('pagination', 'form_validation'));
        
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
    }

    public function index() {
        $lists = $this->programstudiservice->getKonsentrasiLengkap();
        $data = array(
            'content' => 'admin/jurusan/konsentrasi/V_lists',
            'judul' => 'Jurusan',
            'sub_judul' => 'Konsentrasi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Konsentrasi</li>',
            'lists' => $lists
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function matakuliah_konsentrasi($kode_konsentrasi) {
        $res = $this->programstudiservice->getMatakuliahKonsentrasi($kode_konsentrasi);
        
        $data = array(
            'content' => 'admin/jurusan/konsentrasi/V_matakuliah_konsentrasi',
            'judul' => 'Jurusan',
            'sub_judul' => 'Matakuliah Konsentrasi',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Matakuliah Konsentrasi</li>',
            'lists' => $res['lists'],
            'konsentrasi' => $res['konsentrasi'],
            'matakuliah' => $res['matakuliah'],
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_matakuliah() {
        $this->programstudiservice->simpanMatakuliahKonsentrasi($this->input->post());
        $this->session->set_flashdata('info', 'Data berhasil disimpan');
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete_matakuliah($id) {
        $this->programstudiservice->hapusMatakuliahKonsentrasi($id);
        $this->session->set_flashdata('info', 'Data berhasil dihapus');
        return redirect($_SERVER['HTTP_REFERER']);
    }

}
