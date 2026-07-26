<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    protected $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->service('UserService');
        $this->load->library(['pagination', 'form_validation']);

        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        $cek = rbac_cek($class, $id_user);
        if (!$cek) {
            redirect(site_url('denied'));
        }
    }

    public function index($offset = 0)
    {
        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ?: 0;

        $total = $this->userservice->countAllUsers();

        if ($total > 0) {
            $this->pagination->initialize([
                'base_url'       => site_url('user/index'),
                'total_rows'     => $total,
                'per_page'       => $this->limit,
                'uri_segment'    => $uri_segment,
                'full_tag_open'  => '<div class="btn-group">',
                'full_tag_close' => '</div>',
                'cur_tag_open'   => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
                'cur_tag_close'  => '</a>',
                'attributes'     => ['class' => 'btn btn-flat btn-xs btn-default'],
            ]);
        }

        $this->load->view('admin/template/V_main', [
            'content'       => 'admin/pengguna/V_pengguna',
            'judul'         => 'User',
            'sub_judul'     => 'User',
            'title_h1'      => 'User',
            'data_pengguna' => $this->userservice->getPaginatedUsers($this->limit, $offset),
            'halaman'       => $this->pagination->create_links(),
            'jumlah_data'   => $total,
        ]);
    }

    public function tambah()
    {
        $this->load->view('admin/template/V_main', [
            'content'   => 'admin/pengguna/V_tambah_pengguna',
            'judul'     => 'User',
            'sub_judul' => 'Tambah Data',
            'title_h1'  => '<li>User</li>',
            'title_h2'  => '<li>Tambah Data</li>',
            'roles'     => $this->userservice->getUserRoles(),
        ]);
    }

    public function simpan()
    {
        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required');
        $this->form_validation->set_rules('nama_login', 'Nama Login', 'required');
        $this->form_validation->set_rules('sandi_pengguna', 'Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|matches[ulangi_sandi_pengguna]');
        $this->form_validation->set_rules('ulangi_sandi_pengguna', 'Ulangi Sandi Pengguna', 'trim|required');
        $this->form_validation->set_rules('id_role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            return $this->tambah();
        }

        $sandi_error = $this->userservice->validatePasswordComplexity($this->input->post('sandi_pengguna'));
        if ($sandi_error) {
            $this->form_validation->set_message('valid_password_complexity', $sandi_error);
            $this->form_validation->set_rules('valid_password_complexity', 'valid_password_complexity', 'callback_valid_password_complexity');
            return $this->tambah();
        }

        $login_error = $this->userservice->checkDuplicateLogin($this->input->post('nama_login'));
        if ($login_error) {
            $this->session->set_flashdata('pesan', '<script>swal("Gagal!","' . $login_error . '","error")</script>');
            return $this->tambah();
        }

        $res = $this->userservice->createUser($this->input->post());
        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        redirect('user');
    }

    public function edit($kode)
    {
        $this->load->view('admin/template/V_main', [
            'content'       => 'admin/pengguna/V_ubah_pengguna',
            'judul'         => 'User',
            'sub_judul'     => 'Ubah Data',
            'active_1_1'    => 'active',
            'active_1_2'    => 'active',
            'roles'         => $this->userservice->getUserRoles(),
            'data_pengguna' => $this->userservice->getUserById($kode),
            'title_h1'      => '<li>User</li>',
            'title_h2'      => '<li>Ubah Data</li>',
        ]);
    }

    public function ubah_data()
    {
        $kode = $this->input->post('kode_pengguna');

        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required');
        $this->form_validation->set_rules('nama_login', 'Nama Login', 'required');
        $this->form_validation->set_rules('id_role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            return $this->edit($kode);
        }

        $login_error = $this->userservice->checkDuplicateLogin($this->input->post('nama_login'), $kode);
        if ($login_error) {
            $this->session->set_flashdata('pesan', '<script>swal("Gagal!","' . $login_error . '","error")</script>');
            return $this->edit($kode);
        }

        $res = $this->userservice->updateUser($kode, $this->input->post());
        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        redirect('user');
    }

    public function ubah_password()
    {
        $kode = $this->input->post('kode_pengguna');

        $this->form_validation->set_rules('sandi_pengguna', 'Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|matches[ulangi_sandi_pengguna]');
        $this->form_validation->set_rules('ulangi_sandi_pengguna', 'Ulangi Sandi Pengguna', 'trim|required');

        if ($this->form_validation->run() == false) {
            return $this->edit($kode);
        }

        $sandi_error = $this->userservice->validatePasswordComplexity($this->input->post('sandi_pengguna'));
        if ($sandi_error) {
            $this->session->set_flashdata('pesan', '<script>swal("Gagal!","' . $sandi_error . '","error")</script>');
            return $this->edit($kode);
        }

        $res = $this->userservice->updatePassword($kode, $this->input->post('sandi_pengguna'));
        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        redirect('user');
    }

    public function hapus($kode)
    {
        $res = $this->userservice->deleteUser($kode);
        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
        redirect('user');
    }

    public function valid_password_complexity($str)
    {
        $error = $this->userservice->validatePasswordComplexity($str);
        if ($error) {
            $this->form_validation->set_message('valid_password_complexity', $error);
            return false;
        }
        return true;
    }
}
