<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rbac extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('rbac_model');
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
        $this->load->service('RbacService');
        $this->load->helper('directory');
        $this->load->library('form_validation');
    }

    function index()
    {
        $role = $this->rbacservice->getAllRole();

        $data = rbac_list($this->session->userdata('id_role'));
        $controller = array_map(function ($value) {
            return $value['nama'];
        }, $data);
        $data['content'] = "admin/rbac/V_access";
        $data['judul'] = 'RBAC';
        $data['sub_judul'] = 'RBAC Role';
        $data['controller'] = $controller;
        $data['role'] = $role;

        $this->load->view('admin/template/V_main', $data);
    }

    function get_list_file($id)
    {
        $role = $this->rbacservice->getAllRole();
        $data = rbac_list($id);
        $controller = array_map(function ($value) {
            return $value['nama'];
        }, $data);
        $data['controller'] = $controller;
        $data['role'] = $role;

        $this->load->view('admin/rbac/V_form', $data);
    }

    function simpan_access()
    {
        $id_roel = $this->input->post('id_role');
        $this->rbacservice->deleteAccessByRole($id_roel);
        $controller = $this->input->post('controller');
        if (is_array($controller)) {
            foreach ($controller as $key => $value) {
                $data_simpan = array(
                    'id_role' => $id_roel,
                    'nama_controller' => $value,
                );
                $this->rbacservice->insertAccess($data_simpan);
            }
        }

        $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Hak akses berhasil diperbarui","success")</script>');
        redirect(site_url('admin/rbac'));
    }

    function tambah_role()
    {
        $this->form_validation->set_rules('nama_role', 'Nama Role', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            $error = validation_errors(' ', ' ');
            $this->session->set_flashdata('pesan', '<script>swal("Perhatian!","' . addslashes(trim($error)) . '","warning")</script>');
            redirect(site_url('admin/rbac'));
            return;
        }

        $nama_role = trim($this->input->post('nama_role', TRUE));

        if ($this->rbacservice->checkRoleExists($nama_role)) {
            $this->session->set_flashdata('pesan', '<script>swal("Gagal!","Role dengan nama tersebut sudah ada!","error")</script>');
            redirect(site_url('admin/rbac'));
            return;
        }

        $insert_id = $this->rbacservice->insertRole(['nama_role' => $nama_role]);
        if ($insert_id) {
            $this->session->set_flashdata('pesan', '<script>swal("Sukses!","Role ' . addslashes($nama_role) . ' berhasil ditambahkan","success")</script>');
        } else {
            $this->session->set_flashdata('pesan', '<script>swal("Gagal!","Terjadi kesalahan saat menambahkan role","error")</script>');
        }

        redirect(site_url('admin/rbac'));
    }
}
