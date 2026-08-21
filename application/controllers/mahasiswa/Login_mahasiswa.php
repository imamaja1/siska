<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_mahasiswa extends CI_Controller
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
            'Login_model',
        ));
        $this->load->library('form_validation');
        $this->load->service('MahasiswaService');
    }

    public function index()
    {
              redirect(site_url('login'));

//        $this->load->view("mahasiswa/V_login_mahasiswa");
    }

    public function cek_login()
    {

        $this->form_validation->set_rules('nim', 'nim', 'required|numeric|min_length[10]', array('required' => 'Field NIM harus diisi', 'numeric' => 'Field NIM harus berisi bilangan positif', 'min_length' => 'Field NIM minimal sebanyak 10 karakter'));
        $this->form_validation->set_rules('sandi', 'sandi', 'required', array('required' => 'Field Sandi harus diisi'));

        if ($this->form_validation->run() == false) {
            $this->load->view('mahasiswa/V_login_mahasiswa');
        } else {

            $nim = $this->input->post('nim');
            $input = $this->input->post('sandi');
//            $ilokm = substr($nim, 2,4);
////                        sementara
//            if ($ilokm == '0102'){
//                $this->session->set_flashdata('pesan', 'Maaf, Untuk sementara mahasiswa dengan Prodi S1 Ilkom belum bisa mengakses SISKA, kareana masih ada penyesuaian kurikulum');
//                redirect(site_url('login'));
//            }else{
                $jurusan = substr($nim, 2, 2);
                $jenjang = substr($nim, 4, 1);
                $cek = $this->Login_model->login_mahasiswa($nim, $input);

                if (count($cek) > 0) {
//                    $kode_program_studi = $this->Nama_jurusan_model->get_id($jurusan, $jenjang);
                    $kode_program_studi = get_kode_prodi($nim)->kode_program_studi;
//                    $nama_kurikulum = $this->m_data_kurikulum->get_nama_kurikulum($nim);
                    $data_user = array(
                            'nim' => $cek->nim,
                            'nama_mahasiswa' => $cek->nama_mahasiswa,
                            'kode_program_studi' => $kode_program_studi,
//                        'kode_nama_kurikulum' => $nama_kurikulum->kode_nama_kurikulum,
                            'kode_nama_kurikulum' => kode_nama_kurikulum($nim),
                            'status' => 'login_mahasiswa',
                            'foto' => $cek->foto,
                            'jenis_kelamin' => $cek->jenis_kelamin,
                    );

                    $this->session->set_userdata($data_user);
                    redirect(site_url('home'));
                } else {
                    $this->session->set_flashdata('pesan', 'Maaf, NIM dan atau Sandi anda salah');
                    redirect(site_url('login'));
                }
//            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url('Login'));
    }

}
