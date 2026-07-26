<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class institusi_model extends CI_Model {

    private $table = 'institusi';

    public function get() {
        return $this->db->get($this->table)->result();
    }

    public function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function edit($kode, $data = []) {
        return $this->db->update($this->table, $data, array('kode_institusi' => $kode));
    }

    public function del($kode) {
        return $this->db->delete($this->table, array('kode_institusi' => $kode));
    }

    function get_nama($kode_institusi) {
        $query = $this->db->get_where($this->table, array('kode_institusi' => $kode_institusi));
        return $query->num_rows() > 0 ? $query->row()->singkatan : "";
    }

    function cek_kode($kode_institusi) {
        return $this->db->get_where($this->table, array('kode_institusi' => $kode_institusi));
    }

}
