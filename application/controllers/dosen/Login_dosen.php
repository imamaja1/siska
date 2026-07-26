<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_dosen extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library('form_validation');
        $this->load->service('DosenService');
    }

    public function Login() {
        $this->form_validation->set_rules('email', 'email', 'required|valid_email', array('required' => 'Field Email harus diisi', 'valid_email' => 'Field Alamat Email harus berisi alamat email yang valid'));
        $this->form_validation->set_rules('sandi', 'sandi', 'required', array('required' => 'Field Sandi harus diisi'));

        if ($this->form_validation->run() == false) {
            $this->load->view('dosen/V_login_dosen');
        } else {
            $alamat_email = $this->input->post('email');
            $sandi_pengguna = md5($this->input->post('sandi'));

            $cek_login = $this->login_model->login_dosen($alamat_email, $sandi_pengguna);

            if ($cek_login->num_rows() == 1) {
//                foreach ($cek_login->result() as $row) {
                $row = $cek_login->row_object();
                    $data = array(
                        'kode_dosen' => $row->kode_dosen,
                        'nama_dosen' => $row->nama_dosen,
                        'alamat_email' => $row->alamat_email,
                    );

                    $this->session->set_userdata($data);
//                }
                redirect('home/dosen');
            } else {
                $this->session->set_flashdata('pesan', 'Maaf, Alamat Email dan atau Sandi anda salah');
                redirect('login/dosen');
            }
        }
    }

    function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

}
