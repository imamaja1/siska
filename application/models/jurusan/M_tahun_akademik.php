<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_tahun_akademik extends CI_Model {

    public $table = "tahun_akademik";

    function __construct() {
        parent::__construct();
    }

    function get_nilai_26() {
        return $this->db->where('kode_tahun_akademik >=',26)->order_by('tahun_akademik', 'DESC')->order_by('semester', 'DESC')->get($this->table)->result();
    }
    function get_nilai_not_26() {
        return $this->db->where('kode_tahun_akademik <',26)->order_by('tahun_akademik', 'DESC')->order_by('semester', 'DESC')->get($this->table)->result();
    }

    function get() {
        return $this->db->order_by('tahun_akademik', 'DESC')->order_by('semester', 'DESC')->get($this->table)->result();
    }

    function get_all_tahun_akademik() {
        $this->db->order_by('kode_tahun_akademik');
        return $this->db->get($this->table);
    }

    function get_aktif() {
        $tahun_akademik = $this->db->where('status', 'A')->get($this->table)->row_object();
        return $tahun_akademik ? $tahun_akademik->kode_tahun_akademik : null;
    }

    function get_byid($id) {
        $tahun_akademik = $this->db->where('kode_tahun_akademik', $id)->get($this->table)->row_object();
        return $tahun_akademik ? $tahun_akademik->tahun_akademik : null;
    }

    function get_semester() {
        return $this->db->select(array('semester', 'substring(tahun_akademik,3,2) as tahun_akademik', 'tahun_akademik as ta', 'kode_tahun_akademik'))->where('status', 'A')->get($this->table)->row_object();
    }

    function get_tahun() {
        return $this->db->query("select distinct(tahun_akademik) from {$this->table}")->result();
    }

    function get_tahun_ganjil_genap() {
        return $this->db->get($this->table)->result();
    }

    function tahun_angkatan() {
        return $this->db->distinct()->select('tahun_akademik')->order_by('tahun_akademik', 'DESC')->get($this->table)->result();
    }

    function simpan($data) {
        return $this->db->insert($this->table, $data);
    }

    function ubah($data, $id) {
        return $this->db->where('kode_tahun_akademik', $id)->update($this->table, $data);
    }

    function hapus($id) {

        return $this->db->where('kode_tahun_akademik', $id)->delete($this->table);
    }

    function get_tahun_akademik_aktif() {
        return $this->db->get_where($this->table, array('status' => 'A'), 1)->row();
    }

    function get_all_byid($id) {
        $tahun_akademik = $this->db->select('*,substr(tahun_akademik,3,2) as tahun')->where('kode_tahun_akademik', $id)->get($this->table)->row_object();
        return $tahun_akademik;
    }

    function get_tahun_akademik_by_kode($kode_tahun_akademik) {
        return $this->db->get_where($this->table, array('kode_tahun_akademik' => $kode_tahun_akademik), 1)->row();
    }

    function get_tahun_akademik_by_kode_one($kode_tahun_akademik) {
        return $this->db->get_where($this->table, array('kode_tahun_akademik' => $kode_tahun_akademik), 1)->row_object();
    }

    function get_kode_tahun_akademik_by_semester($semester, $nim)
    {
        $query = $this->db->select('*')
            ->from('tahun_akademik as ta')
            ->join('krs', 'ta.kode_tahun_akademik=krs.kode_tahun_akademik')
            ->where('krs.semester', $semester)
            ->where('krs.nim', $nim)
            ->get()->row_object();
        if ($query) {
            return $query->kode_tahun_akademik;
        }
        return false;
    }

}
