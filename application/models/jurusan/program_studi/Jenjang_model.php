<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jenjang_model extends CI_Model {

    private $table = 'jenjang';

    public function get() {
        return $this->db->get($this->table)->result();
    }

    public function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function edit($id_jenjang, $data = []) {
        return $this->db->update($this->table, $data, array('id_jenjang' => $id_jenjang));
    }

    public function del($id_jenjang) {
        return $this->db->delete($this->table, array('id_jenjang' => $id_jenjang));
    }

    function get_nama($id_jenjang) {
        $query = $this->db->get_where($this->table, array('id_jenjang' => $id_jenjang));
        return $query->num_rows() > 0 ? $query->row()->nama_jenjang : "";
    }

    public function get_kode($id) {
        return $this->db->select('kode_jenjang')->where('id_jenjang', $id)->get($this->table)->row_object();
    }

    function get_nama_bykode($kode_jenjang) {
        $query = $this->db->get_where($this->table, array('kode_jenjang' => $kode_jenjang));
        return $query->num_rows() > 0 ? $query->row()->nama_jenjang : "";
    }

    function cek_kode_jenjang($kode_jenjang) {
        return $this->db->get_where($this->table, array('kode_jenjang' => $kode_jenjang));
    }

}
