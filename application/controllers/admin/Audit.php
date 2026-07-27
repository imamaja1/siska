<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Audit extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
        ));
        $this->load->service('CekService');
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
    }

    public function index()
    {
        $data['judul'] = 'Audit Nilai';
        $data['sub_judul'] = 'Audit Nilai |';
        $data['content'] = 'admin/audit/V_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_program_studi = $this->input->post('kode_program_studi');

        $filter_angkatan = ($kode_program_studi != '23');
        $data['data'] = $this->cekservice->getAuditNilai($kode_tahun_akademik, $kode_program_studi, $filter_angkatan);
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_semester();
        $prodi_obj = $this->Nama_jurusan_model->get_kode_by_program_studi($kode_program_studi);
        if (!$prodi_obj) {
            $prodi_obj = $this->db->select('nama_program_studi')->from('program_studi')->where('kode_program_studi', $kode_program_studi)->get()->row_object();
        }
        $data['prodi'] = $prodi_obj;

        $this->load->view('admin/audit/V_hasil', $data);
    }
}