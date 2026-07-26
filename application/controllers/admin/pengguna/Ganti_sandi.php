<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ganti_sandi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('jurusan/m_dosen');
        $this->load->model('pengguna_model');
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index() {
        $data['content'] = "admin/V_ganti_sandi";
        $data['judul'] = "Ganti Sandi";
        $data['sub_judul'] = "";

        $this->load->view('admin/template/V_main', $data);
    }

    function ganti_sandi_proses() {

        $data = array(
            'content' => 'admin/V_ganti_sandi',
            'judul' => 'Ganti Sandi',
            'sub_judul' => ''
        );

        $this->form_validation->set_rules('sandi_pengguna', 'Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|callback_valid_password_complexity|matches[ulangi_sandi_pengguna]', array('required' => 'Field Sandi Pengguna harus diisi', 'min_length' => 'Field Sandi Pengguna minimal harus sebanyak 8 karakter', 'max_length' => 'Field Sandi Pengguna maksimal harus sebanyak 20 karakter', 'matches' => 'Field Sandi Pengguna tidak cocok dengan field Ulangi Sandi Pengguna'));
        $this->form_validation->set_rules('ulangi_sandi_pengguna', 'Ulangi Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|callback_valid_password_complexity', array('required' => 'Field Ulangi Sandi harus diisi', 'min_length' => 'Field Ulangi Sandi minimal harus sebanyak 8 karakter', 'max_length' => 'Field Ulangi Sandi maksimal harus sebanyak 20 karakter'));

        if ($this->form_validation->run() == TRUE) {
            // Melakukan one way hash menggunakan MD5 pada sandi pengguna
            $sandi_pengguna = md5($this->input->post('sandi_pengguna'));

            // Persiapan data
            $pengguna = array('sandi_pengguna' => $sandi_pengguna);

            $this->pengguna_model->update_password($this->session->userdata('kode_pengguna'), $pengguna);

            $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Ganti Sandi Berhasil","success")</script>');

            redirect('admin/pengguna/ganti_sandi');
        }
        $this->load->view('admin/template/V_main', $data);
    }

    function valid_password_complexity($str) {
        if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $str)) {
            $this->form_validation->set_message('valid_password_complexity', 'Sandi harus memiliki panjang min. 8 karakter, max. 20 karakter, mengandung min. 1 huruf besar, 1 huruf kecil, dan 1 angka!');
            return false;
        } else {
            return true;
        }
    }

}
