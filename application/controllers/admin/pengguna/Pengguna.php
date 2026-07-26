<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pengguna extends CI_Controller
{
    protected $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pengguna_model');
        $this->load->library(['pagination', 'form_validation']);

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

    public function index($offset = 0)
    {
        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ?: 0;

        $total = $this->pengguna_model->countAll();

        if ($total > 0) {
            $this->pagination->initialize([
                'base_url'       => site_url('admin/pengguna/pengguna/index'),
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
            'judul'         => 'Pengguna',
            'sub_judul'     => 'Pengguna',
            'title_h1'      => 'Pengguna',
            'data_pengguna' => $this->pengguna_model->getPagination($this->limit, $offset),
            'halaman'       => $this->pagination->create_links(),
            'jumlah_data'   => $total,
        ]);
    }

    function tambah()
    {
        $this->load->view('admin/template/V_main', [
            'content'   => 'admin/pengguna/V_tambah_pengguna',
            'judul'     => 'Pengguna',
            'roles'     => $this->pengguna_model->getAllRoles(),
            'sub_judul' => 'Tambah Data',
            'title_h1'  => '<li>Pengguna</li>',
            'title_h2'  => '<li>Tambah Data</li>',
        ]);
    }

    function simpan()
    {
        $roles = $this->pengguna_model->getAllRoles();

        $this->form_validation->set_rules('nama_pengguna', 'nama_pengguna', 'required');
        $this->form_validation->set_rules('nama_login', 'nama_login', 'required');
        $this->form_validation->set_rules('sandi_pengguna', 'Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|callback_valid_password_complexity|matches[ulangi_sandi_pengguna]');
        $this->form_validation->set_rules('ulangi_sandi_pengguna', 'Ulangi Sandi Pengguna', 'trim|required');
        $this->form_validation->set_rules('id_role', 'id_role', 'required');

        if ($this->form_validation->run() == false) {
            return $this->load->view('admin/template/V_main', [
                'content'   => 'admin/pengguna/V_tambah_pengguna',
                'judul'     => 'Pengguna',
                'roles'     => $roles,
                'sub_judul' => 'Tambah Data',
                'title_h1'  => '<li>Pengguna</li>',
                'title_h2'  => '<li>Tambah Data</li>',
            ]);
        }

        $data = [
            'nama_pengguna'  => $this->input->post('nama_pengguna'),
            'nama_login'     => $this->input->post('nama_login'),
            'sandi_pengguna' => md5($this->input->post('sandi_pengguna')),
            'id_role'        => $this->input->post('id_role'),
        ];

        if ($this->pengguna_model->add($data)) {
            $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Tambah data berhasil","success")</script>');
            redirect('admin/pengguna/pengguna');
        }
    }

    function valid_password_complexity($str)
    {
        if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $str)) {
            $this->form_validation->set_message('valid_password_complexity', 'Sandi harus memiliki panjang min. 8 karakter, max. 20 karakter, mengandung min. 1 huruf besar, 1 huruf kecil, dan 1 angka!');
            return false;
        }
        return true;
    }

    function edit($kode)
    {
        $this->load->view('admin/template/V_main', [
            'content'       => 'admin/pengguna/V_ubah_pengguna',
            'judul'         => 'Pengguna',
            'sub_judul'     => 'Ubah Data',
            'active_1_1'    => 'active',
            'active_1_2'    => 'active',
            'roles'         => $this->pengguna_model->getAllRoles(),
            'data_pengguna' => $this->pengguna_model->edit($kode),
            'title_h1'      => '<li>Pengguna</li>',
            'title_h2'      => '<li>Ubah Data</li>',
        ]);
    }

    function hapus($kode)
    {
        if ($this->pengguna_model->del($kode)) {
            $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Hapus data berhasil","success")</script>');
            redirect('admin/pengguna/pengguna');
        }
    }

    function ubah_data()
    {
        $kode_pengguna = $this->input->post('kode_pengguna');
        $roles = $this->pengguna_model->getAllRoles();

        $this->form_validation->set_rules('nama_pengguna', 'nama_pengguna', 'required');
        $this->form_validation->set_rules('nama_login', 'nama_login', 'required');
        $this->form_validation->set_rules('id_role', 'id_role', 'required');

        if ($this->form_validation->run() == false) {
            return $this->load->view('admin/template/V_main', [
                'content'       => 'admin/pengguna/V_ubah_pengguna',
                'judul'         => 'Pengguna',
                'sub_judul'     => 'Ubah Data',
                'active_1_1'    => 'active',
                'active_1_2'    => 'active',
                'roles'         => $roles,
                'data_pengguna' => $this->pengguna_model->edit($kode_pengguna),
                'title_h1'      => '<li>Pengguna</li>',
                'title_h2'      => '<li>Tambah Data</li>',
            ]);
        }

        $this->pengguna_model->update($kode_pengguna, [
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'nama_login'    => $this->input->post('nama_login'),
            'id_role'       => $this->input->post('id_role'),
        ]);

        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Ubah Data berhasil","success")</script>');
        redirect('admin/pengguna/pengguna');
    }

    function ubah_password()
    {
        $kode_pengguna = $this->input->post('kode_pengguna');

        $this->form_validation->set_rules('sandi_pengguna', 'Sandi Pengguna', 'trim|required|min_length[8]|max_length[20]|callback_valid_password_complexity|matches[ulangi_sandi_pengguna]');
        $this->form_validation->set_rules('ulangi_sandi_pengguna', 'Ulangi Sandi Pengguna', 'trim|required');

        if ($this->form_validation->run() == false) {
            return $this->load->view('admin/template/V_main', [
                'content'       => 'admin/pengguna/V_ubah_pengguna',
                'judul'         => 'Pengguna',
                'sub_judul'     => 'Tambah Data',
                'active_2_1'    => 'active',
                'active_2_2'    => 'active',
                'data_pengguna' => $this->pengguna_model->edit($kode_pengguna),
                'title_h1'      => '<li>Pengguna</li>',
                'title_h2'      => '<li>Tambah Data</li>',
            ]);
        }

        $this->pengguna_model->update($kode_pengguna, [
            'sandi_pengguna' => md5($this->input->post('sandi_pengguna')),
        ]);

        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Ubah sandi berhasil","success")</script>');
        redirect('admin/pengguna/pengguna');
    }
}
