<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fakultas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->service('UniversitasService');

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

    public function index() {
        $fakultas = $this->universitasservice->getFakultasLengkap();
        $data = array(
            'content' => 'admin/jurusan/universitas/fakultas/V_index',
            'judul' => 'Jurusan',
            'sub_judul' => 'Fakultas',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Fakultas</li>',
            'fakultas' => $fakultas,
            'dosen' => $this->universitasservice->getDosen(),
        );

        return $this->load->view('admin/template/V_main', $data);
    }
    
    public function tambah() {
        $data['dosen'] = $this->universitasservice->getDosen();
        return $this->load->view('admin/jurusan/universitas/fakultas/V_add', $data);
    }

    public function add() {
        $res = $this->universitasservice->simpanFakultas($this->input->post());
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!","' . $res['msg'] . '", "success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '", "error")</script>');
        }
        return redirect(site_url('admin/jurusan/universitas/fakultas'));
    }

    public function edit($id) {
        $data['data'] = $this->universitasservice->getFakultasById($id);
        $data['dosen'] = $this->universitasservice->getDosen();
        $data['id'] = $id;
        return $this->load->view('admin/jurusan/universitas/fakultas/V_edit', $data);
    }

    public function update($id) {
        $res = $this->universitasservice->ubahFakultas($id, $this->input->post());
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!","' . $res['msg'] . '", "success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '", "error")</script>');
        }
        return redirect(site_url('admin/jurusan/universitas/fakultas'));
    }
    
    function delete($id) {
        $res = $this->universitasservice->hapusFakultas($id);
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!","' . $res['msg'] . '", "success")</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '", "error")</script>');
        }
        return redirect(site_url('admin/jurusan/universitas/fakultas'));
    }
}