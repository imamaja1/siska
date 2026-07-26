<?php

defined('BASEPATH') or exit('No direct script access allowed');

class login_admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        $this->load->service('NilaiService');

    }

    function login()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hari = date('w');
        $tgl = date('d');
        $bln = date('m');
        $thn = date('Y');
        switch ($hari) {
            case 0 :
                {
                    $hari = 'Ahad';
                }
                break;
            case 1 :
                {
                    $hari = 'Senin';
                }
                break;
            case 2 :
                {
                    $hari = 'Selasa';
                }
                break;
            case 3 :
                {
                    $hari = 'Rabu';
                }
                break;
            case 4 :
                {
                    $hari = 'Kamis';
                }
                break;
            case 5 :
                {
                    $hari = "Jum'at";
                }
                break;
            case 6 :
                {
                    $hari = 'Sabtu';
                }
                break;
            default:
                {
                    $hari = 'UnKnown';
                }
                break;
        }

        switch ($bln) {
            case 1 :
                {
                    $bln = 'Januari';
                }
                break;
            case 2 :
                {
                    $bln = 'Februari';
                }
                break;
            case 3 :
                {
                    $bln = 'Maret';
                }
                break;
            case 4 :
                {
                    $bln = 'April';
                }
                break;
            case 5 :
                {
                    $bln = 'Mei';
                }
                break;
            case 6 :
                {
                    $bln = "Juni";
                }
                break;
            case 7 :
                {
                    $bln = 'Juli';
                }
                break;
            case 8 :
                {
                    $bln = 'Agustus';
                }
                break;
            case 9 :
                {
                    $bln = 'September';
                }
                break;
            case 10 :
                {
                    $bln = 'Oktober';
                }
                break;
            case 11 :
                {
                    $bln = 'November';
                }
                break;
            case 12 :
                {
                    $bln = 'Desember';
                }
                break;
            default:
                {
                    $bln = 'UnKnown';
                }
                break;
        }

        $this->form_validation->set_rules('nama_login', 'nama_login', 'required', array('required' => 'Field Nama Login harus diisi'));
        $this->form_validation->set_rules('sandi', 'sandi', 'required', array('required' => 'Field Sandi harus diisi'));
       
        if ($this->form_validation->run() == false) {
            $this->load->view('admin/V_login_admin');
            
        } else {
            $nama_login = $this->input->post('nama_login');
            $sandi_plain = $this->input->post('sandi');
            $sandi_md5 = md5($sandi_plain);

            $auth_success = false;
            $pengguna = $this->login_model->get_admin_by_username($nama_login);

            if (!$pengguna) {
                $this->session->set_flashdata('pesan', 'Maaf, Nama login dan atau sandi anda salah');
                redirect('login/admin');
            }

            $ion_user = $this->nilaiservice->get_user_by_username($nama_login);
            if ($ion_user && password_verify($sandi_plain, $ion_user->password)) {
                $auth_success = true;
            } elseif (md5($sandi_plain) === $pengguna->sandi_pengguna) {
                $auth_success = true;
                if (!$ion_user) {
                    $this->ion_auth->register($nama_login, $sandi_plain, 'pustik@universitasbumigora.ac.id', ['first_name' => $nama_login], ['1']);
                } else {
                    $this->ion_auth->update($ion_user->id, ['password' => $sandi_plain]);
                }
            }

            if ($auth_success) {
                $data = array(
                    'nama_pengguna' => $pengguna->nama_pengguna,
                    'nama_login' => $pengguna->nama_login,
                    'kode_pengguna' => $pengguna->kode_pengguna,
                    'id_role' => $pengguna->id_role,
                    'id' => $pengguna->kode_pengguna,
                    'sekarang' => $hari . ", " . $tgl . " " . $bln . " " . $thn,
                );
                $this->session->set_userdata($data);
                redirect('home/admin');
            } else {
                $this->session->set_flashdata('pesan', 'Maaf, Nama login dan atau sandi anda salah');
                redirect('login/admin');
            }
        }
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('login/admin');
    }

}
