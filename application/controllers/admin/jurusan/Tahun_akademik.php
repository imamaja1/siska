<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_akademik extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('AkademikSetupService');
        
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
        $data = array(
            'content' => 'admin/jurusan/V_Tahun_akademik',
            'judul' => 'Jurusan',
            'sub_judul' => 'Tahun Akademik',
            'judul_sub_judul' => '',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Tahun Akademik</li>',
            'data' => $this->akademiksetupservice->getTahunAkademikLengkap(),
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan() {
        $res = $this->akademiksetupservice->simpanTahunAkademik($this->input->post());
        if ($this->input->is_ajax_request()) {
            echo json_encode($res);
            return;
        }
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>');
        }
        redirect('admin/jurusan/tahun_akademik');
    }

    public function edit($id) {
        $data['data'] = $this->akademiksetupservice->getTahunAkademikById($id);
        $data['id'] = $id;
        $this->load->view('admin/jurusan/Modal_edit_tahun_akademik', $data);
    }

    public function ubah($id) {
        $res = $this->akademiksetupservice->ubahTahunAkademik($id, $this->input->post());
        if ($this->input->is_ajax_request()) {
            echo json_encode($res);
            return;
        }
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>');
        }
        redirect('admin/jurusan/tahun_akademik');
    }

    public function hapus($id) {
        $res = $this->akademiksetupservice->hapusTahunAkademik($id);
        if ($this->input->is_ajax_request()) {
            echo json_encode($res);
            return;
        }
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>');
        }
        redirect('admin/jurusan/tahun_akademik');
    }

    public function ubah_status($id, $status) {
        $res = $this->akademiksetupservice->ubahStatusTahunAkademik($id, $status);
        if ($this->input->is_ajax_request()) {
            echo json_encode($res);
            return;
        }
        if ($res['status']) {
            $this->session->set_flashdata('info', '<script>swal("Success!", "'.$res['msg'].'", "success");</script>');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal!", "'.$res['msg'].'", "error");</script>');
        }
        redirect('admin/jurusan/tahun_akademik');
    }

    public function render_tabel() {
        $data['data'] = $this->akademiksetupservice->getTahunAkademikLengkap();
        $this->load->view('admin/jurusan/V_tabel_tahun_akademik', $data);
    }

}
