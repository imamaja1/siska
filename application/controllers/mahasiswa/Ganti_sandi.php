<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ganti_sandi extends CI_Controller {

    function __construct() {
        parent::__construct();
//        $this->load->model('jurusan/m_dosen');
        $this->load->library('form_validation');
        $this->load->service('MahasiswaService');

        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    function index() {

        $data = array(
            'conten' => 'mahasiswa/V_ganti_sandi',
            'judul' => 'Ganti Sandi',
        );

        $this->load->view('mahasiswa/template/V_main', $data);
    }

    function ganti_sandi_proses() {
        $data = array(
            'conten' => 'mahasiswa/V_ganti_sandi',
            'judul' => 'Ganti Sandi',
        );

        $this->form_validation->set_rules('sandi_lama', 'Sandi Lama', 'trim|required', array('required' => 'Field Sandi Lama harus diisi'));
        $this->form_validation->set_rules('sandi_pengguna', 'Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|callback_valid_password_complexity|matches[ulangi_sandi_pengguna]', array('required' => 'Field Sandi Pengguna harus diisi','min_length'=>'Field Sandi Pengguna minimal harus sebanyak 8 karakter','max_length'=>'Field Sandi Pengguna maksimal harus sebanyak 20 karakter','matches'=>'Field Sandi Pengguna tidak cocok dengan field Ulangi Sandi Pengguna'));
        $this->form_validation->set_rules('ulangi_sandi_pengguna', 'Ulangi Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|callback_valid_password_complexity', array('required' => 'Field Ulangi Sandi harus diisi','min_length'=>'Field Ulangi Sandi minimal harus sebanyak 8 karakter','max_length'=>'Field Ulangi Sandi maksimal harus sebanyak 20 karakter'));

        if ($this->form_validation->run() == TRUE) {
            $nim = $this->session->userdata('nim');
            $mahasiswa = $this->mahasiswaservice->getMahasiswaByNim($nim);
            $sandi_lama = md5($this->input->post('sandi_lama'));

            if (!$mahasiswa || !isset($mahasiswa->sandi) || $mahasiswa->sandi !== $sandi_lama) {
                $this->session->set_flashdata(
                    'pesan', '<script>swal("Gagal!","Sandi Lama tidak sesuai","error")</script>'
                );
                redirect('mahasiswa/ganti_sandi');
            }

            // Melakukan one way hash menggunakan MD5 pada sandi pengguna
            $sandi = md5($this->input->post('sandi_pengguna'));
            $string = $this->input->post('sandi_pengguna');
            $password_api = password_hash($string,PASSWORD_BCRYPT);

            // Persiapan data
            $data_ubah = array('sandi' => $sandi);

            $ubah = $this->mahasiswaservice->updateMahasiswaPassword($nim, $data_ubah);
            if ($ubah)
            {
                $this->mahasiswaservice->updateUsersPassword($nim, array('password'=>$password_api));
                $this->session->set_flashdata(
                    'pesan', '<script>swal("Sukses!","Ganti Sandi Berhasil","success")</script>'
                );
            }else{
                $this->session->set_flashdata(
                    'pesan', '<script>swal("Gagal!","Ganti Sandi Gagal","error")</script>'
                );
            }

            redirect('mahasiswa/ganti_sandi');
        }
        $this->load->view('mahasiswa/template/V_main', $data);
    }

    function valid_password_complexity($str) {
        if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[0-9]).*$#", $str)) {
            $this->form_validation->set_message('valid_password_complexity', 'Sandi harus memiliki panjang min. 8 karakter, max. 20 karakter, mengandung gabungan huruf dan angka!');
            return false;
        } else {
            return true;
        }
    }

}