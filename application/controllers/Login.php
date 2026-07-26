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

    function index()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[10]', array('required' => 'Field Username harus diisi', 'min_length' => 'Field Username minimal 10 karakter'));
            $this->form_validation->set_rules('password', 'Password', 'required', array('required' => 'Field password harus diisi'));
            $this->form_validation->set_rules('status', 'Status', 'required', array('required' => 'Field Status harus dipilih'));

            if ($this->form_validation->run() == false) {
                $this->load->view('auth/v_login');
            } else {
                $nim = $this->input->post('username');
                $pass = md5($this->input->post('password'));
              	$sandi_pengguna = $this->input->post('password');
                if ($this->input->post('status') == 'mahasiswa') {
                  	$angkatan = substr($nim, 0, 2);

                    $mah = $this->authservice->getMahasiswaByNim($nim);
                    if (!$mah) {
                        $this->session->set_flashdata('pesan', 'NIM yang anda masukkan belum terdaftar pada aplikasi SISKA. Silahkan cek NIM anda dengan benar.');
                        redirect(site_url('login'));
                    }
                    $prodi = $this->authservice->getProgramStudiByKode($mah->program_studi_kode);

                        $cek = $this->login_model->login_mahasiswa($nim, $pass);
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
                                $email = $cek->email;
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

                            $this->session->set_userdata($data_user);
                            redirect(site_url('home'));
                        } else {
                            $this->session->set_flashdata('pesan', 'Maaf, NIM dan atau Sandi anda salah');
                            redirect(site_url('login'));
                        }

                } else {
                    $alamat_email = $this->input->post('username');
                    $sandi_pengguna = $this->input->post('password');

                    $cek_login = $this->login_model->login_dosen($alamat_email, md5($sandi_pengguna));
                    if ($cek_login->num_rows() == 1) {
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

                        $this->session->set_userdata($data);
                        redirect('home/dosen');
                    } else {
                        $this->session->set_flashdata('pesan', 'Maaf, Alamat Email dan atau Sandi anda salah');
                        redirect('login');
                    }
                }
            }
        } else {
            $this->load->view('auth/v_login');
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
