<?php

class KRSAN extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/M_tahun_akademik',
            'laporan/laporan_model',
        ));
        $this->load->service('KaprodiService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['content'] = 'dosen/kaprodi/krsan/v_index';
        $data['judul'] = 'Mahsiswa KRS';
        $data['sub_judul'] = 'Data Mahasiswa';
        $data['sub_judul'] = 'Halaman Mahasiswa';
        $data['semester'] = $this->m_tahun_akademik->get_semester();        
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['angkatan'] = $this->m_tahun_akademik->tahun_angkatan();   
        $data['kode_tahun_akademik'] = $data['semester']->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
        
    }
    public function get_mahasiswa($ta = null, $angkatan = false, $status_krs = false) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_program_studi = $this->kaprodiservice->get_kaprodi_prodi_row_kode($kode_dosen)->kode_program_studi;
        $data['data'] = $this->kaprodiservice->get_mahasiswa_krsan($ta, $kode_program_studi, $angkatan, $status_krs);
        
        $this->load->view('dosen/kaprodi/krsan/v_data_mhs',$data);
    }
}
