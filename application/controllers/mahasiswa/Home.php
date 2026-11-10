<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
        $nim = $this->session->userdata('nim');
        $data['conten'] = "mahasiswa/V_dashbord";
        $data['judul'] = "Dashboard";
        $data['hide_page_header'] = true;
        $data['prodi'] = get_kode_prodi($nim);
        $data['tahun_akademik'] = tahun_akademik();
        $data['angkatan'] = '20' . substr($nim, 0, 2);
        $data['pembayaran'] = pembayaran_mahasiswa($nim);

        // Data Akademik untuk Ringkasan Dashboard
        $this->load->model('jurusan/Perwalian_model');
        $dosen_wali = $this->Perwalian_model->get_perwalian_by_nim($nim);
        $data['dosen_wali'] = !empty($dosen_wali->nama_dosen) ? $dosen_wali->nama_dosen : '-';

        $this->load->model('akademik/Petikan_nilai_model');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum') ?: kode_nama_kurikulum($nim);
        $petikan = $this->Petikan_nilai_model->petikan_nilai($nim, $kode_nama_kurikulum);
        $sks_lulus = 0;
        $sksn_lulus = 0;
        if (!empty($petikan) && is_array($petikan)) {
            foreach ($petikan as $row) {
                if (!empty($row['nilai_akhir']) && $row['nilai_akhir'] != 'E' && $row['nilai_akhir'] != 'T') {
                    $sks_lulus += (float) ($row['sks'] ?? 0);
                    $sksn_lulus += (float) ($row['sksn'] ?? 0);
                }
            }
        }
        $data['total_sks_lulus'] = $sks_lulus;
        $data['ipk_kumulatif'] = $sks_lulus > 0 ? number_format($sksn_lulus / $sks_lulus, 2) : '0.00';

        // SKS KRS semester berjalan
        $sks_krs = 0;
        $ta = $data['tahun_akademik'];
        if ($ta) {
            $krs_cek = $this->db->where('nim', $nim)->where('kode_tahun_akademik', $ta->kode_tahun_akademik)->get('krs')->row();
            if ($krs_cek) {
                $sks_sum = $this->db->select('SUM(sks_teori + sks_praktek + sks_praktikum) AS total_sks')
                    ->from('krs_detail')
                    ->join('matakuliah', 'matakuliah.id_matakuliah = krs_detail.id_matakuliah')
                    ->where('krs_detail.kode_krs', $krs_cek->kode_krs)
                    ->get()->row();
                if ($sks_sum && $sks_sum->total_sks > 0) {
                    $sks_krs = (int) $sks_sum->total_sks;
                }
            }
        }
        $data['sks_krs'] = $sks_krs;

        $this->load->view('mahasiswa/template/V_main', $data);
    }

}
