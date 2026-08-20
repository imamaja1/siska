<?php

class validasikhusus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/universitas/Fakultas_model',
            'laporan/laporan_model',
            'akademik/Nilai_model',
        ));
        $this->load->service('ValidasiService');
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
        $this->load->library('pagination');
    }

    public function index() {
//        $id_dekan = $this->session->userdata('kode_dosen');

        $data['kode_fakultas'] = $this->validasiservice->get_all_fakultas();
        $kode_fkk = $this->validasiservice->get_fakultas_by_kode(1);
//        $ta = $this->m_tahun_akademik->get();
        $ta = $this->m_tahun_akademik->get();
        $prodi = $this->Fakultas_model->getProdiFromDekan($kode_fkk['dekan']);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
//        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->validasiservice->get_kelas_by_prodi($kode_prodi);

        $data['content'] = 'admin/akademik/nilai/V_validasi_khusus';
        $data['judul'] = 'Akademik';
        $data['tahun_akademik'] = $ta;
        $data['sub_judul'] = 'Nilai';
        $data['kelas'] = $kelas;

        $this->load->view('admin/template/V_main', $data);
    }

    public function cari() {
//        $id_dekan = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $data['kode_fakultas'] = $this->validasiservice->get_all_fakultas();

        $kode_fakultas = $this->input->post("kd_fk");
        $kode_tahun_akademik = $this->input->post("kode_tahun_akademik");
        $data['match_kode_fakultas'] = $kode_fakultas;

        $prodi = $this->Fakultas_model->getProdiFromDekan($kode_fakultas);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
//        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->validasiservice->get_kelas_by_prodi_and_ta($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'admin/akademik/nilai/V_validasi_khusus';
        $data['judul'] = 'Akademik';
        $data['tahun_akademik'] = $ta;
        $data['sub_judul'] = 'Nilai';
        $data['kelas'] = $kelas;

        $this->load->view('admin/template/V_main', $data);
    }

}
