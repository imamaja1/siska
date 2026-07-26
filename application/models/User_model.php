<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'pengguna';

    public function getAll()
    {
        return $this->db->select('pengguna.*, role.nama_role')
            ->from($this->table)
            ->join('role', 'role.id_role = pengguna.id_role')
            ->order_by('pengguna.kode_pengguna', 'DESC')
            ->get()
            ->result();
    }

    public function getPaginated($limit, $offset)
    {
        return $this->db->select('pengguna.*, role.nama_role')
            ->from($this->table)
            ->join('role', 'role.id_role = pengguna.id_role')
            ->order_by('pengguna.kode_pengguna', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result();
    }

    public function countAll()
    {
        return $this->db->count_all($this->table);
    }

    public function findById($kode)
    {
        return $this->db->select('pengguna.*, role.nama_role')
            ->from($this->table)
            ->join('role', 'role.id_role = pengguna.id_role')
            ->where('pengguna.kode_pengguna', $kode)
            ->get()
            ->row();
    }

    public function findByLogin($nama_login)
    {
        return $this->db->get_where($this->table, ['nama_login' => $nama_login])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($kode, $data)
    {
        return $this->db->where('kode_pengguna', $kode)->update($this->table, $data);
    }

    public function delete($kode)
    {
        return $this->db->where('kode_pengguna', $kode)->delete($this->table);
    }

    public function getAllRoles()
    {
        return $this->db->get('role')->result();
    }
}
