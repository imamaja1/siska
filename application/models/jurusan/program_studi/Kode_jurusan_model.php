<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kode_jurusan_model extends CI_Model {

    private $table = "jurusan";

    public function get() {
        return $this->db->get($this->table)->result();
    }

    public function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id) {
        return $this->db->where('id_jurusan', $id)->update($this->table, $data);
    }

    public function hapus($id) {
        return $this->db->where('id_jurusan', $id)->delete($this->table);
    }

    function get_nama($id) {
        $query = $this->db->get_where($this->table, array('id_jurusan' => $id));
        return $query->num_rows() > 0 ? $query->row()->nama_jurusan : "";
    }

    public function get_kode($id) {
        return $this->db->select('kode_jurusan')->where('id_jurusan', $id)->get($this->table)->row_object();
    }

    function get_nama_bykode($kode_jurusan) {
        $query = $this->db->get_where($this->table, array('kode_jurusan' => $kode_jurusan));
        return $query->num_rows() > 0 ? $query->row()->nama_jurusan : "";
    }

    public function cek_kode_jurusan($kode_jurusan) {
        return $this->db->get_where($this->table, array('kode_jurusan' => $kode_jurusan));
    }

}
