<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_dosen extends CI_Model {

    private $table = "dosen";

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        return $this->db->get($this->table)->result();
    }

    function get_pagination($limit, $offset) {
        return $this->db->select('*')
            ->from('dosen')
            ->join('program_studi as ps','dosen.homebase=ps.kode_program_studi','left')
            ->limit($limit)
            ->offset($offset)
            ->get()->result();
//        get($this->table, $limit, $offset)->result();
    }

    function get_count() {
        return $this->db->get($this->table)->result();
    }

    public function add($data) {
        return $this->db->insert($this->table, $data);
    }

    public function edit($kode_dosen, $data) {
        return $this->db->update($this->table, $data, array('kode_dosen' => $kode_dosen));
    }

    public function hapus($id) {
        return $this->db->where('kode_dosen', $id)->delete($this->table);
    }

    function get_nama($id) {
        $query = $this->db->get_where($this->table, array('kode_dosen' => $id));
        return $query->num_rows() > 0 ? $query->row()->nama_dosen : "";
    }

    function edit_dosen($kode_dosen) {
        return $this->db->get_where($this->table, array('kode_dosen' => $kode_dosen))->row();
    }

    public function get_dosen_pengganti($kode_dosen) {
        return $this->db->select('kode_dosen, nama_dosen')
                        ->from('dosen')
                        ->where_not_in('kode_dosen', $kode_dosen)
                        ->order_by('kode_dosen ASC')
                        ->get()->result();
    }

    public function get_dosen_tetap() {
        return $this->db->order_by('kode_dosen DESC')->get_where($this->table, array('status_dosen' => 'T'))->result();
    }

    function get_dosen_by_kode($kode_dosen) {
        return $this->db->get_where($this->table, array('kode_dosen' => $kode_dosen))->row();
    }

    function ubah_sandi($kode_dosen, $dosen) {
        $this->db->where('kode_dosen', $kode_dosen);
        $this->db->update($this->table, $dosen);
    }

    function ubah_sandi_admin($kode_dosen, $dosen) {
        return $this->db->where('kode_dosen', $kode_dosen)->update('dosen', $dosen);
//        $this->db->where('kode_dosen', $kode_dosen);
//        $this->db->update('dosen', $dosen);
    }

    function get_pagination_search($kata_kunci) {

        return $this->db->select('*')
            ->from('dosen')
            ->join('program_studi as ps','dosen.homebase=ps.kode_program_studi','left')
            ->like('nama_dosen', $kata_kunci,'both')
            ->limit(20)
            ->get()
            ->result();
    }

    public function search_by_kode_dosen($kode_dosen) {
        return $this->db->get_where($this->table, array('kode_dosen' => $kode_dosen))->result();
    }

    function update($kode_dosen, $data = []) {
        return $this->db->update($this->table, $data, array('kode_dosen' => $kode_dosen));
    }

    function get_dosen_and_homebase() {

        $query = $this->db->select('*')
                        ->from('dosen as d')
                        ->join('program_studi as ps', 'ps,kode_program_studi=d.homebase')
                        ->order_by('kode_dosen ASC')
                        ->get()->result();

        return $query;
    }

    function autocomplate($keyword)
    {
        return $this->db->like('nama_dosen', $keyword)->order_by('nama_dosen')->limit(6)->get('dosen')->result();
    }

}
