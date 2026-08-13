<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DoubleService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getDoubleKrs($kta) {
        $q = "SELECT kode_krs, nim, kode_tahun_akademik, semester, COUNT(nim) AS cnim 
                FROM krs WHERE kode_tahun_akademik=?
                GROUP BY nim, semester, kode_tahun_akademik HAVING cnim > 1";
        return $this->db->query($q, array($kta))->result();
    }

    public function getKrsDetailByNimSemester($nim, $semester) {
        $q = "SELECT * FROM krs
	            JOIN krs_detail ON krs.kode_krs=krs_detail.kode_krs
	            JOIN khs_detail ON krs_detail.kode_krs_detail=khs_detail.kode_krs_detail
                WHERE 
	                krs.nim=? and krs.semester=?
                ORDER BY krs_detail.id_matakuliah ASC";
        return $this->db->query($q, array($nim, $semester))->result();
    }

    public function getKrsByNim($nim) {
        $q2 = "SELECT * FROM krs WHERE krs.nim=?";
        return $this->db->query($q2, array($nim))->result();
    }

    public function updateKhsDetail($kode_khs_detail, $field, $nilai) {
        $data = array($field => $nilai);
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', $data);
        $lama = $row && isset($row->$field) ? $row->$field : null;
        if ($lama != $nilai) {
            log_aktivitas_nilai('update', $field, $lama, $nilai, 'double', $kode_khs_detail);
        }
        return true;
    }

    public function deleteKrs($kode_krs) {
        log_aktivitas_nilai('delete', 'kode_krs', $kode_krs, null, 'double', null, null, $kode_krs);
        return $this->db->where('kode_krs', $kode_krs)->delete('krs');
    }
}
