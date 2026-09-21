<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_body extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect(site_url('login_admin/login'));
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
        $this->load->service('AkademikSetupService');
    }

    public function index() {
        $data['content'] = 'admin/jurusan/student_body/V_index';
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Student Body';
        $data['title_h1'] = '<i class="fa fa-map"></i> <li>Jurusan</li>';
        $data['title_h2'] = '<li>Student Body</li>';
        $data['angkatan'] = $this->akademiksetupservice->getTahunAngkatan();
        $data['prodi'] = $this->akademiksetupservice->getProgramStudi();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $kode_program_studi = $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('angkatan');

        $data_sess = array(
            'kode_program_studi_sess' => $kode_program_studi,
            'angkatan_sess' => $angkatan,
        );

        $this->session->set_userdata($data_sess);
        redirect(site_url('admin/jurusan/student_body/data_body'));
    }

    public function data_body() {
        $kode_program_studi = $this->session->userdata('kode_program_studi_sess');
        $angkatan = $this->session->userdata('angkatan_sess');

        $data['content'] = 'admin/jurusan/student_body/V_index';
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Student Body';
        $data['angkatan'] = $this->akademiksetupservice->getTahunAngkatan();
        $data['prodi'] = $this->akademiksetupservice->getProgramStudi();
        $data['data_mahasiswa'] = $this->akademiksetupservice->getDataMahasiswaBody($kode_program_studi, $angkatan);

        $this->load->view('admin/template/V_main', $data);
    }

    public function ip($nim) {
        $keterangan = $this->akademiksetupservice->getIpMahasiswa($nim);

        echo json_encode($keterangan);
    }
}