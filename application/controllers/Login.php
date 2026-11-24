<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
                'akademik/Mahasiswa_model',
                'jurusan/program_studi/Kode_jurusan_model',
                'jurusan/program_studi/Nama_jurusan_model',
                'jurusan/program_studi/Ketua_jurusan_model',
                'jurusan/program_studi/Jenjang_model',
                'jurusan/kurikulum/m_data_kurikulum',
                'login_model',
        ));
        $this->load->library('ion_auth');
        $this->load->service('AuthService');
    }

    private function render_login_view()
    {
        $data = array(
            'remembered_username' => $this->input->cookie('siska_remember_username', TRUE) ?: '',
            'remembered_role'     => $this->input->cookie('siska_remember_role', TRUE) ?: 'mahasiswa',
        );

        $this->load->view('auth/v_login', $data);
    }

    private function handle_remember_me($username, $role)
    {
        $is_secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
        if ($this->input->post('remember')) {
            $expire = 2592000;
            $this->input->set_cookie(array(
                'name'     => 'siska_remember_username',
                'value'    => $username,
                'expire'   => $expire,
                'path'     => '/',
                'secure'   => $is_secure,
                'httponly' => TRUE,
                'samesite' => 'Lax',
            ));
            $this->input->set_cookie(array(
                'name'     => 'siska_remember_role',
                'value'    => ($role === 'dosen') ? 'dosen' : 'mahasiswa',
                'expire'   => $expire,
                'path'     => '/',
                'secure'   => $is_secure,
                'httponly' => TRUE,
                'samesite' => 'Lax',
            ));
        } else {
            $this->input->set_cookie('siska_remember_username', '', -3600, '', '/');
            $this->input->set_cookie('siska_remember_role', '', -3600, '', '/');
        }
    }

    function index()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[10]', array('required' => 'Field Username harus diisi', 'min_length' => 'Field Username minimal 10 karakter'));
            $this->form_validation->set_rules('password', 'Password', 'required', array('required' => 'Field password harus diisi'));
            $this->form_validation->set_rules('status', 'Status', 'required', array('required' => 'Field Status harus dipilih'));

            if ($this->form_validation->run() == false) {
                $this->render_login_view();
            } else {
                $nim = $this->input->post('username');
                $sandi_pengguna = $this->input->post('password');
                if ($this->input->post('status') == 'mahasiswa') {

                    $mah = $this->authservice->getMahasiswaByNim($nim);
                    if (!$mah) {
                        $this->session->set_flashdata('pesan', 'NIM yang anda masukkan belum terdaftar pada aplikasi SIAKAD. Silahkan cek NIM anda dengan benar.');
                        redirect(site_url('login'));
                    }

                        $cek = $this->login_model->login_mahasiswa($nim, $sandi_pengguna);
                        if ($cek) {
                            $data_user = array(
                                    'nim' => $cek->nim,
                                    'nama_mahasiswa' => $cek->nama_mahasiswa,
                                    'kode_program_studi' => $cek->program_studi_kode,
                                    'kode_nama_kurikulum' => kode_nama_kurikulum($nim),
                                    'status' => 'login_mahasiswa',
                                    'foto' => $cek->foto,
                                    'jenis_kelamin' => $cek->jenis_kelamin,
                            );

                            $cek_exist = $this->authservice->getUserByUsername($nim);
                            if (!$cek_exist) {
                                $username = $nim;
                                $password = $this->input->post('password');
                                $email = !empty($cek->email) ? $cek->email : $nim . '@student.local';
                                $additional_data = array(
                                    'first_name' => $nim,
                                  	'key_ref' => $nim,
                                	'role' => 2
                                );
                                $group = array('3');
                                $this->ion_auth->register($username, $password, $email, $additional_data, $group);

                             	$result = $this->authservice->getUserIdByUsername($username);
                             	$this->authservice->updateUserId($username, $result->id_user);
                            }

                          else {
                            if ($cek_exist->key_ref == '') {
                                $this->authservice->updateUserKeyRef($nim);
                            }
                            if (!password_verify($sandi_pengguna, $cek_exist->password)) {
                                $password_new = password_hash($sandi_pengguna, PASSWORD_BCRYPT);
                                $this->authservice->updateUserPassword($nim, $password_new);
                            }
                        }

                            $this->handle_remember_me($nim, 'mahasiswa');
                            $this->session->set_userdata($data_user);
                            $this->session->sess_regenerate(TRUE);
                            redirect(site_url('home'));
                        } else {
                            $this->session->set_flashdata('pesan', 'Maaf, NIM dan atau Sandi anda salah');
                            redirect(site_url('login'));
                        }

                } else {
                    $alamat_email = $this->input->post('username');
                    $sandi_pengguna = $this->input->post('password');

                    $cek_login = $this->login_model->login_dosen($alamat_email, $sandi_pengguna);
                    if ($cek_login && $cek_login->num_rows() == 1) {
                        $row = $cek_login->row_object();
                        $data = array(
                                'kode_dosen' => $row->kode_dosen,
                                'nama_dosen' => $row->nama_dosen,
                                'alamat_email' => $row->alamat_email,
                        );

                        $cek_exist = $this->authservice->getUserByUsername($alamat_email);
                        if (!$cek_exist) {
                            $username = $alamat_email;
                            $password = $this->input->post('password');
                            $email = $alamat_email;
                            $additional_data = array(
                              	'first_name' => $row->nama_dosen,
                                'name' => $row->nama_dosen,
                                'key_ref' => $row->kode_dosen,
                                'role' => 3
                            );
                            $group = array('2');
                            $this->ion_auth->register($username, $password, $email, $additional_data, $group);
                          	$result = $this->authservice->getUserIdByEmail($alamat_email);
                            $this->authservice->updateUserIdByEmail($alamat_email, $result->id_user);
                        }
                      else {
                            if (!password_verify($sandi_pengguna, $cek_exist->password)) {
                                $password_new = password_hash($sandi_pengguna, PASSWORD_BCRYPT);
                                $this->authservice->updateUserPasswordByKeyRef($row->kode_dosen, $password_new);
                            }
                        }

                        $this->handle_remember_me($alamat_email, 'dosen');
                        $this->session->set_userdata($data);
                        $this->session->sess_regenerate(TRUE);
                        redirect('home/dosen');
                    } else {
                        $this->session->set_flashdata('pesan', 'Maaf, Alamat Email dan atau Sandi anda salah');
                        redirect('login');
                    }
                }
            }
        } else {
            $this->render_login_view();
        }
    }

    function dosen()
    {
        redirect('login');
    }

    function admin()
    {
        $this->load->view('admin/V_login_admin');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url('Login'));
    }
}
