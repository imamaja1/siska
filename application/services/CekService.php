<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CekService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getDummyUpdateNilaiByTa($kode_tahun_akademik) {
        return $this->db->select('dummy_update_nilai.*')->from('kelas')
            ->join('dummy_update_nilai','dummy_update_nilai.kelas_id = kelas.kelas_id')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kode_khs_detail !=', '0')
            ->order_by('dummy_update_nilai.kode_khs_detail')
            ->get()->result_object();
    }

    public function getKhsDetailByTa($kode_tahun_akademik) {
        return $this->db->select('khs_detail.*')->from('kelas')
            ->join('dummy_update_nilai','dummy_update_nilai.kelas_id = kelas.kelas_id')
            ->join('khs_detail','khs_detail.kode_khs_detail = dummy_update_nilai.kode_khs_detail','left')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('dummy_update_nilai.kode_khs_detail !=', '0')
            ->order_by('dummy_update_nilai.kode_khs_detail')
            ->get()->result_object();
    }
}
