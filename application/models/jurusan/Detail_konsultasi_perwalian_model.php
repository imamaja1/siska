<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Detail_konsultasi_perwalian_model extends CI_Model {

    private $table = 'konsultasi_perwalian_detail';

    public function simpan($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function get_konsultasi_krs($nim_mhs) {

        return $this->db->select('*')
                        ->from('konsultasi_perwalian as kp')
                        ->join('konsultasi_perwalian_detail as kpd', 'kp.kode_konsultasi_perwalian=kpd.kode_konsultasi_perwalian')
                        ->where(array('kpd.jenis_konsultasi' => 'K', 'kp.nim' => $nim_mhs))
                        ->get()->result();
    }

    public function get_konsultasi_umum($nim_mhs) {

        return $this->db->select('*')
                        ->from('konsultasi_perwalian as kp')
                        ->join('konsultasi_perwalian_detail as kpd', 'kp.kode_konsultasi_perwalian=kpd.kode_konsultasi_perwalian')
                        ->where(array('kpd.jenis_konsultasi' => 'U', 'kp.nim' => $nim_mhs))
                        ->get()->result();
    }

    public function get_konsultasi_perwalian($nim_mhs) {

        return $this->db->select('*')
                        ->from('konsultasi_perwalian as kp')
                        ->join('konsultasi_perwalian_detail as kpd', 'kp.kode_konsultasi_perwalian=kpd.kode_konsultasi_perwalian')
                        ->where(array('kpd.jenis_konsultasi' => 'P', 'kp.nim' => $nim_mhs))
                        ->get()->result();
    }

   
    function ubah_konsultasi_krs($kode, $data = []) {
        return $this->db->update($this->table, $data, array('kode_konsultasi_perwalian_detail' => $kode));
    }

    function hapus_perwalian($kode) {
        return $this->db->delete($this->table, array('kode_konsultasi_perwalian_detail' => $kode));
    }

}
