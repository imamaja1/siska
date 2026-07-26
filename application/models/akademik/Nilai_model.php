<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nilai_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_matakuliah_by_tahun_akademik_and_jurusan($kode_tahun_akademik, $kode_program_studi) {

        $query = $this->db->select('kd.id_matakuliah, mak.kode_matakuliah, nama_matakuliah, substr(mak.kode_matakuliah,6,1) as semester')
            ->from('krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
            ->where('mah.program_studi_kode',$kode_program_studi)
            ->where('kode_tahun_akademik',$kode_tahun_akademik)
            ->where_not_in('kd.status','K')
            ->where_not_in('krs.semester','K')
            ->group_by('mak.id_matakuliah')
            ->get()->result();

        return $query;
    }

    function get_all_nilai_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $limit, $offset) {

        $query = $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->where('mah.program_studi_kode',$kode_program_studi)
            ->where('krs.kode_tahun_akademik',$kode_tahun_akademik)
            ->where('kd.id_matakuliah', $id_matakuliah)
            ->where_not_in('kd.status', 'K')
            ->limit($limit)
            ->offset($offset)
            ->get()->result();

        return $query;
    }

    function count_all_results_nilai_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah) {

        $query = $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->where('mah.program_studi_kode',$kode_program_studi)
            ->where('krs.kode_tahun_akademik',$kode_tahun_akademik)
            ->where('kd.id_matakuliah', $id_matakuliah)
            ->where_not_in('kd.status', 'K')
            ->get()->result();

        return $query;

    }

//    function get_nilai_per_matakuliah_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $limit, $offset) {
//
//        $query1 = $this->db->select('krs.nim, krs.kode_krs, kode_khs_detail, nama_mahasiswa, kd.status, khd.nilai_harian, khd.nilai_uts, khd.nilai_uas, khd.nilai_akhir, khd.tidak_berhak')
//            ->from('krs')
//            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
//            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
//            ->join('mahasiswa as mah','krs.nim=mah.nim')
//            ->where('mah.program_studi_kode', $kode_program_studi)
//            ->where('kode_tahun_akademik', $kode_tahun_akademik)
//            ->where('id_matakuliah',$id_matakuliah)
//            ->where_not_in('kd.status','K')
//            ->where_not_in('krs.semester','K')
//            ->get_compiled_select();
//
//        return $this->db->query($query1." ORDER BY substr(mah.nim,1,2) ASC, substr(mah.nim,-4,4) ASC LIMIT ".$limit." OFFSET ".$offset)->result();
//    }
  
  // baru 18/4/2022
  function get_nilai_per_matakuliah_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $kelas_id = 0, $limit, $offset)
    {
        if ($kelas_id == 0) {
//            digunakan jika kelas tidak dibagi atau menampilkan semua data
            $query1 = $this->db->select('krs.nim, krs.kode_krs, kode_khs_detail, nama_mahasiswa, kd.status, khd.nilai_harian, khd.nilai_uts, khd.nilai_uas, khd.nilai_akhir, khd.tidak_berhak')
                ->from('krs')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('kd.id_matakuliah', $id_matakuliah)
                ->where_not_in('kd.status', 'K')
                ->where_not_in('krs.semester', 'K')
                ->get_compiled_select();
        } else {
//            filter dari id kelas yang dipilih saat filter awal / filter pada halaman
            $query1 = $this->db->select('krs.nim, krs.kode_krs, kode_khs_detail, nama_mahasiswa, kd.status, khd.nilai_harian, khd.nilai_uts, khd.nilai_uas, khd.nilai_akhir, khd.tidak_berhak')
                ->from('krs')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                ->join('kelas_mahasiswa as km', 'km.kode_krs_detail=kd.kode_krs_detail')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('kd.id_matakuliah', $id_matakuliah)
                ->where('km.kelas_id', $kelas_id)
                ->where_not_in('kd.status', 'K')
                ->where_not_in('krs.semester', 'K')
                ->get_compiled_select();
        }

        return $this->db->query($query1 . " ORDER BY substr(mah.nim,1,2) ASC, substr(mah.nim,-4,4) ASC LIMIT ? OFFSET ?", array((int)$limit, (int)$offset))->result();
    }

// get list kelas dari matakuliah di semester berjalan
    function get_kelas_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        $query = $this->db->select('* ,count(kode_krs_detail) as jml,kelas.kelas_id')
            ->from('kelas')
            ->join('nama_kelas as nk', 'kelas.nama_kelas_id=nk.nama_kelas_id')
            ->join('kelas_mahasiswa as km', 'kelas.kelas_id=km.kelas_id', 'left')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kode_program_studi', $kode_program_studi)
            ->where('id_matakuliah', $id_matakuliah)
            ->group_by('kelas.nama_kelas_id')
            ->order_by('kelas.nama_kelas_id ASC')
            ->get()->result();
        return $query;
    }
  

    function get_nilai_per_mahasiswa_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nim) {

        $query1 = $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->where('mah.program_studi_kode',$kode_program_studi)
            ->where('krs.kode_tahun_akademik',$kode_tahun_akademik)
            ->where('kd.id_matakuliah', $id_matakuliah)
            ->where('krs.nim', $nim)
            ->where_not_in('kd.status', 'K')
            ->get()->result();

        return $query1;

    }

    function count_nilai_per_matakuliah_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah) {

        $query = $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('id_matakuliah',$id_matakuliah)
            ->where_not_in('kd.status','K')
            ->where_not_in('krs.semester','K')
            ->get()->result();

        return $query;
    }

    function update($arr_nilai_matakuliah) {
        $this->db->trans_start();
        foreach ($arr_nilai_matakuliah as $sql) {
            $this->db->query($sql);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
  
    public function validasi_dekan($kode_khs_detail, $data_nilai)
    {
        $this->db->where('kode_khs_detail', $kode_khs_detail);
        return $this->db->update('khs_detail', $data_nilai);
    }

}
