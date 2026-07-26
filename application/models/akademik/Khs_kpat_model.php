<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Khs_kpat_model extends CI_Model
{

    public $variable;

    public function __construct()
    {
        parent::__construct();

    }

    public function filter($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
            $query = $this->db->select('krs.nim, mah.nama_mahasiswa, krs.kode_krs')
                ->from('krs')
                ->join('krs_detail as krd','krs.kode_krs=krd.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->where('mah.program_studi_kode',$kode_program_studi)
                ->where('substr(krs.nim,1,2)',$angkatan)
                ->where('krd.status','K')
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->group_by('krs.nim')
                ->get()->result();

        return $query;
    }

    public function khs($kode_krs)
    {
        $krs_detail = $this->db->select('*,krs.semester')
            ->from('krs')
            ->join('krs_detail as krd','krs.kode_krs=krd.kode_krs')
            ->join('khs_detail as khd','krd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak','krd.id_matakuliah=mak.id_matakuliah')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->where('krs.kode_krs',$kode_krs)
            ->where('krd.status','K')
            ->get()->result();
        return $krs_detail;
    }

    public function kurikulum_penilaian($angkatan, $kode_program_studi)
    {
        $penilaian = $this->db->query("SELECT * FROM (SELECT distinct kode_sistem_penilaian_detail, mid(angkatan1,-2) as angkatan, nama_kurikulum.kode_nama_kurikulum, nilai_minimum, nilai_maksimum, grade, bobot_nilai, kategori, keterangan, nama_kurikulum, kode_program_studi FROM nama_kurikulum, kurikulum, sistem_penilaian, sistem_penilaian_detail WHERE nama_kurikulum.kode_nama_kurikulum=kurikulum.kode_nama_kurikulum and nama_kurikulum.kode_nama_kurikulum=sistem_penilaian.kode_nama_kurikulum and sistem_penilaian.kode_sistem_penilaian=sistem_penilaian_detail.kode_sistem_penilaian) as mhs WHERE angkatan=? and kode_program_studi=?", array($angkatan, $kode_program_studi))->result_array();

        return $penilaian;
    }
    
   
}
