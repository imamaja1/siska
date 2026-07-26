<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PerwalianService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getDosenPerwalian() {
        return $this->db->select('*')
            ->from('dosen')
            ->join('program_studi as ps','dosen.homebase=ps.kode_program_studi')
            ->where('status_dosen','T')
            ->where('status_login','A')
            ->get()->result();
    }

    public function getPerwalianDetail($param) {
        return $this->db->select('*')
            ->from('perwalian as per')
            ->join('mahasiswa as mah','mah.nim=per.nim')
            ->join('dosen as dos','dos.kode_dosen=per.kode_dosen')
            ->where('kode_perwalian', $param)
            ->get()->row_object();
    }

    public function searchDosen($keyword) {
        return $this->db->select('kode_dosen, nama_dosen')
            ->from('dosen')
            ->where("nama_dosen LIKE '%$keyword%'")
            ->get()->result();
    }

    public function getPerwalianByDosen($kode_dosen) {
        return $this->db->select('m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, p.kode_dosen_perwakilan')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->where(array('p.kode_dosen' => $kode_dosen,'m.status' => 'A'))
            ->order_by('m.nim','DESC')
            ->get()->result();
    }

    public function hapusPerwakilan($kode_perwalian) {
        return $this->db->where('kode_perwalian', $kode_perwalian)->update('perwalian', array('kode_dosen_perwakilan' => null));
    }

    public function pindahPerwalianDosen($kode_perwalian, $kode_dosen) {
        $this->db->where('kode_perwalian', $kode_perwalian)->update('perwalian', array('kode_dosen' => $kode_dosen));
    }

    public function pindahPerwalianPerwakilan($kode_perwalian, $kode_dosen) {
        $this->db->where('kode_perwalian', $kode_perwalian)->update('perwalian', array('kode_dosen_perwakilan' => $kode_dosen));
    }

    public function getPerwalianById($kode_perwalian) {
        return $this->db->get_where('perwalian', array('kode_perwalian' => $kode_perwalian))->row_object();
    }

    public function getPerwalianDetailByNim($nim) {
        return $this->db->select('nama_dosen,mah.nim, nama_mahasiswa, mah.email, mah.telepon')
            ->from('perwalian as per')
            ->join('dosen','per.kode_dosen=dosen.kode_dosen')
            ->join('mahasiswa as mah','mah.nim=per.nim')
            ->where('per.nim', $nim)
            ->get()->row_object();
    }

    public function getKrsByNim($nim) {
        return $this->db->select('*')
            ->from('krs')
            ->where('nim', $nim)
            ->where_not_in('semester','K')
            ->group_by('kode_tahun_akademik')
            ->get()->result();
    }
}
