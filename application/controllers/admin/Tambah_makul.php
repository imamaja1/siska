<?php

class Tambah_makul extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect(site_url('login_admin/login'));
        }
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/program_studi/Kompetensi_model',
            'jurusan/kurikulum/m_data_kurikulum',
            'jurusan/kurikulum/m_matakuliah',
            'jurusan/Perwalian_model',
            'keuangan/Status_perkuliahan_model',
            'akademik/Krs_model',
            'akademik/Krs_detail_model',
            'akademik/Khs_model',
            'akademik/Mahasiswa_model',
            'kuisioner/kuisioner_model',

        ));
    }

    public function index()
    {
//        $data_krs = array(
//            'kode_tahun_akademik' => '14',
//            'nim' => '1110520197',
//            'semester' => '14',
//        );
//
//        $kode_mk = array('TSKB281580','TSKB181581');
//        $kode_krs = $this->Krs_model->simpan_krs($data_krs);
//        Simpan data Khs
//        if ($kode_krs !== null) {
            $data_khs = array(
                'kode_krs' => '6793',
            );
            $this->Khs_model->simpan_khs($data_khs);
//        Simpan Data Krs_Detail

                $data_krs_detail = array(
                    'kode_krs' => '6793',
                    'kode_matakuliah' => 'TSKB181581',
                );

                $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                $data_khs_detail = array(
                    'kode_krs_detail' => $kode_krs_detail,
                );

                $this->Krs_detail_model->simpan_khs($data_khs_detail);

        }
//    }
}