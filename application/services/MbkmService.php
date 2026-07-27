<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MbkmService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getMahasiswaMbkm($ta, $kode_program_studi = null) {
        $this->db->select('mahasiswa.nim,mahasiswa.nama_mahasiswa,nama_program_studi,tahun_akademik.semester,mbkm.id as id_fix')
            ->from('mbkm')
            ->join('mahasiswa','mahasiswa.nim = mbkm.nim')
            ->join('tahun_akademik','tahun_akademik.kode_tahun_akademik = mbkm.kode_ta')
            ->join('program_studi','program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('mbkm.kode_ta', $ta);
        
        if ($kode_program_studi) {
            $this->db->where('program_studi.kode_program_studi', $kode_program_studi);
        }
        
        return $this->db->order_by('mbkm.id','DESC')
            ->get()->result_object();
    }

    public function searchMahasiswaMbkm($nim, $ta) {
        return $this->db->select('mahasiswa.*,program_studi.*,mbkm.id as id_mbkm,mbkm.kode_ta as ta_now')
            ->from('mahasiswa')
            ->join('mbkm','mahasiswa.nim = mbkm.nim')
            ->join('program_studi','program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('mahasiswa.nim', $nim)->where('mbkm.kode_ta', $ta)
            ->get()->result_object();
    }

    public function searchMahasiswaOnly($nim) {
        return $this->db->select('mahasiswa.*,program_studi.*')
            ->from('mahasiswa')
            ->join('program_studi','program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('mahasiswa.nim', $nim)
            ->get()->result_object();
    }

    public function cekMbkm($nim, $ta) {
        return $this->db->select('*')->from('mbkm')->where('mbkm.nim', $nim)->where('mbkm.kode_ta', $ta)->get()->row_object();
    }

    public function tambahMbkm($data) {
        return $this->db->insert('mbkm', $data);
    }

    public function hapusMbkm($id) {
        return $this->db->where('id', $id)->delete('mbkm');
    }

    public function getNilaiMahasiswa($id, $ta) {
        return $this->db->select('*')
            ->from('mbkm')
            ->join('mahasiswa as mhs','mhs.nim = mbkm.nim')
            ->join('program_studi as ps','ps.kode_program_studi = mhs.program_studi_kode')
            ->join('fakultas','fakultas.kode_fakultas = ps.kode_fakultas')
            ->join('krs','mhs.nim = krs.nim')
            ->where('mbkm.id', $id)
            ->where('mbkm.kode_ta', $ta)
            ->where('krs.kode_tahun_akademik', $ta)
            ->get()->row_object();
    }

    public function getKurikulum($kode_kurikulum) {
        return $this->db->select('*')
            ->from('nama_kurikulum')
            ->where('kode_nama_kurikulum', $kode_kurikulum)
            ->get()->row_object();
    }

    public function getDataNilai($id, $ta) {
        return $this->db->select('*,mm.status as status_mbkm,mm.id as id_mk_mbkm,khd.kode_krs_detail')
            ->from('mbkm')
            ->join('mahasiswa as mhs','mhs.nim = mbkm.nim')
            ->join('krs','mhs.nim = krs.nim')
            ->join('krs_detail as krd','krd.kode_krs = krs.kode_krs')
            ->join('matakuliah','matakuliah.id_matakuliah = krd.id_matakuliah')
            ->join('khs_detail as khd','khd.kode_krs_detail = krd.kode_krs_detail')
            ->join('mk_mbkm as mm','krd.kode_krs_detail = mm.kode_krs_detail','left')
            ->where('mbkm.id', $id)
            ->where('mbkm.kode_ta', $ta)
            ->where('krd.status !=','K')
            ->where('krs.kode_tahun_akademik', $ta)
            ->order_by('matakuliah.id_matakuliah')
            ->get()->result_object();
    }

    public function updateNilaiMhs($kode_khs_detail, $nilai) {
        $data = array(
            'nilai_harian' => $nilai,
            'nilai_uas' => $nilai,
            'nilai_uts' => $nilai,
            'nilai_akhir' => $nilai,
        );
        return $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', $data);
    }

    public function getStatusMbkm($kode_krs_detail) {
        return $this->db->select('mm.status as status_mbkm,mm.id as id_mk_mbkm,krd.kode_krs_detail')
            ->from('mbkm')
            ->join('mahasiswa as mhs','mhs.nim = mbkm.nim')
            ->join('krs','mhs.nim = krs.nim')
            ->join('krs_detail as krd','krd.kode_krs = krs.kode_krs')
            ->join('matakuliah','matakuliah.id_matakuliah = krd.id_matakuliah')
            ->join('khs_detail as khd','khd.kode_krs_detail = krd.kode_krs_detail')
            ->join('mk_mbkm as mm','krd.kode_krs_detail = mm.kode_krs_detail','left')
            ->where('krd.kode_krs_detail', $kode_krs_detail)
            ->get()->row_object();
    }

    public function updateStatusMbkm($kode_krs_detail, $status) {
        $this->db->where('kode_krs_detail', $kode_krs_detail)->update('mk_mbkm', array('status' => $status));
    }

    public function insertStatusMbkm($data) {
        $this->db->insert('mk_mbkm', $data);
    }

    public function getTahunAkademik($kode_ta) {
        return $this->db->get_where('tahun_akademik', array('kode_tahun_akademik' => $kode_ta))->row_object();
    }

    public function getKrs($nim, $kode_ta) {
        return $this->db->get_where('krs', array('nim' => $nim, 'kode_tahun_akademik' => $kode_ta))->row_object();
    }

    public function getNamaMahasiswa($nim) {
        return $this->db->get_where('mahasiswa', array('nim' => $nim))->row_object()->nama_mahasiswa;
    }
}
