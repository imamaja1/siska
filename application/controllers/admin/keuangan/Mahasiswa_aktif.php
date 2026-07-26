<?php

class Mahasiswa_aktif extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
        $this->load->model(array(
                'jurusan/m_tahun_akademik',
        ));
        $this->load->service('KeuanganService');
    }

    public function index(){
        $data['content'] = 'admin/keuangan/mahasiswa_aktif/V_index';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Mahasiswa Aktif";
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $this->load->view('admin/template/V_main', $data);
    }

    public function get_data($kode_tahun_akademik){
        $data['tahun_akademik'] = $this->keuanganservice->getTahunAkademikRow($kode_tahun_akademik);
        $data['mahasiswa_aktif'] = $this->keuanganservice->getMahasiswaAktifByTA($kode_tahun_akademik);
        $this->load->view('admin/keuangan/mahasiswa_aktif/V_lists', $data);
    }

    public function excel($kode_tahun_akademik){
        $data['tahun_akademik'] = $this->keuanganservice->getTahunAkademikRow($kode_tahun_akademik);
        $semester = $data['tahun_akademik']->semester == '1' ? 'GANJIL' : 'GENAP';
        $data['filename'] = $data['tahun_akademik']->tahun_akademik."-".$semester;
        $data['mahasiswa_aktif'] = $this->keuanganservice->getMahasiswaAktifByTA($kode_tahun_akademik);
        $this->load->view('admin/keuangan/mahasiswa_aktif/Excel', $data);
    }
}
