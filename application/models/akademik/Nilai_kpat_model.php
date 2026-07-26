<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_kpat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function filter($kode_tahun_akademik,$kode_program_studi, $id_matakuliah) {

        $query = $this->db->select('nama_matakuliah, kd.kode_matakuliah, kode_tahun_akademik, kode_khs_detail, krs.nim, nama_mahasiswa, npm , nilai_harian, nilai_uts, nilai_uas, nilai_akhir')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
            ->where('kd.status','K')
            ->where('kd.id_matakuliah',$id_matakuliah)
            ->where('krs.kode_tahun_akademik',$kode_tahun_akademik)
            ->where('mah.program_studi_kode',$kode_program_studi)
            ->get()->result();

        return $query;
    }

}

?>