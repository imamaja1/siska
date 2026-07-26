<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kkp_model extends CI_Model {

    function get_all_nilai_kkp_by_kode_angkatan_and_jurusan_matakuliah_semester($kode_angkatan_and_jurusan, $kode_matakuliah, $semester, $limit, $offset) {
        $sql = "SELECT krs.kode_krs, krs.nim, mahasiswa.nama_mahasiswa FROM krs";
        $sql .= " INNER JOIN mahasiswa ON krs.nim=mahasiswa.nim INNER JOIN krs_detail ON krs.kode_krs=krs_detail.kode_krs";
        $sql .= " WHERE substr(krs.nim,1,5)=? AND krs_detail.kode_matakuliah=? AND krs.semester=?";
        $sql .= " ORDER BY RIGHT(krs.nim,4) ";
        $sql .= " LIMIT ?, ?";
        return $this->db->query($sql, array($kode_angkatan_and_jurusan, $kode_matakuliah, $semester, (int)$limit, (int)$offset))->result();
    }

    function count_all_results_nilai_kkp_by_kode_angkatan_and_jurusan_matakuliah_semester($kode_angkatan_and_jurusan, $kode_matakuliah, $semester) {
        $sql = "SELECT krs.kode_krs, krs.nim, mahasiswa.nama_mahasiswa FROM krs";
        $sql .= " INNER JOIN mahasiswa ON krs.nim=mahasiswa.nim INNER JOIN krs_detail ON krs.kode_krs=krs_detail.kode_krs";
        $sql .= " WHERE substr(krs.nim,1,5)=? AND krs_detail.kode_matakuliah=? AND krs.semester=?";
        $sql .= " ORDER BY RIGHT(krs.nim,4)";
        return $this->db->query($sql, array($kode_angkatan_and_jurusan, $kode_matakuliah, $semester))->result();
    }

    function get_all_nilai_kkp_by_fileter($kode_program_studi, $angkatan, $kode_matakuliah, $semester, $limit, $offset)
    {

        $query = $this->db->select('*')
            ->from('krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
            ->where('mah.program_studi_kode',$kode_program_studi)
            ->where('substr(krs.nim,1,2)',$angkatan)
            ->where_in('mak.kode_matakuliah',$kode_matakuliah)
            ->where('krs.semester',$semester)
            ->offset($offset)
            ->limit($limit)
            ->get()->result();

        return $query;
    }

    function count_all_nilai_kkp_by_fileter($kode_program_studi, $angkatan, $kode_matakuliah, $semester)
    {

        $query = $this->db->select('*')
            ->from('krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
            ->where('mah.program_studi_kode)',$kode_program_studi)
            ->where('substr(krs.nim,1,2)',$angkatan)
            ->where_in('mak.kode_matakuliah',$kode_matakuliah)
            ->where('krs.semester',$semester)
            ->get()->result();

        return $query;
    }

    function get_nilai_kkp_by_nim($nim)
    {
        $kkp = get_kode_matakuliah_kkp();
        $query = $this->db->select('*')
            ->from('krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->where_in('mak.kode_matakuliah',$kkp)
            ->like('krs.nim', $nim)
            ->get()->result();
        return $query;
    }

    function update_nilai_akhir($kode_krs_detail, $nilai_akhir) {
        $this->db->where('kode_krs_detail', $kode_krs_detail)->update('krs_detail', array('nilai_akhir' => $nilai_akhir));
    }

    function get_nilai_kkp_by_nama_mahasiswa($nama_mahasiswa)
    {
        $kkp = get_kode_matakuliah_kkp();
        $query = $this->db->select('*')
            ->from('krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
            ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
            ->where_in('mak.kode_matakuliah',$kkp)
            ->like('mah.nama_mahasiswa', $nama_mahasiswa)
            ->get()->result();
        return $query;
    }

}
