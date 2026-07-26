<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class pengguna_model extends CI_Model {

    private $table = "pengguna";

    function get() {
        return $this->db->get($this->table)->result();
    }

    function countAll() {
        return $this->db->count_all($this->table);
    }

    function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    function edit($kode) {
        return $this->db->get_where($this->table, array('kode_pengguna' => $kode))->row();
    }

    function del($kode) {
        return $this->db->delete($this->table, array('kode_pengguna' => $kode));
    }

    function get_pagination($limit, $offset) {
        return $this->db->select('*')
            ->from('pengguna')
            ->join('role', 'role.id_role=pengguna.id_role')
            ->limit($limit)
            ->offset($offset)
            ->get()->result();
    }

    function getPagination($limit, $offset) {
        return $this->get_pagination($limit, $offset);
    }

    function update($kode, $data = []) {
        return $this->db->update($this->table, $data, array('kode_pengguna' => $kode));
    }

    function update_password($id_pengguna, $data)
    {
        return $this->db->where('kode_pengguna', $id_pengguna)->update('pengguna',$data);
    }

    function getAllRoles() {
        return $this->db->get('role')->result();
    }

}
