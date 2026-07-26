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
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
        $this->load->service('RbacService');
        $this->load->helper('directory');
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
        foreach ($controller as $key => $value) {
            $data_simpan = array(
                'id_role' => $id_roel,
                'nama_controller' => $value,
            );
            $this->rbacservice->insertAccess($data_simpan);
        }

        redirect(site_url('admin/rbac'));
    }
}