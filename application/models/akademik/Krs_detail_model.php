<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Krs_detail_model extends CI_Model{

    private $table = "krs_detail";

    public function __construct()
    {
        parent::__construct();
    }

    public function simpan_krs($data_krs)
    {
        $this->db->insert($this->table, $data_krs);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function simpan_khs($data_khs)
    {
        return $this->db->insert('khs_detail', $data_khs);
    }

    public function get_data_krs($kode_krs)
    {
//        $query = $this->db->query("SELECT * FROM krs_detail, matakuliah WHERE krs_detail.kode_matakuliah=matakuliah.kode_matakuliah and kode_krs='{$kode_krs}'")->result();
        $query = $this->db->select('*')
            ->from('krs_detail as krd')
            ->join('matakuliah as mk', 'krd.id_matakuliah=mk.id_matakuliah')
            ->where('kode_krs', $kode_krs)
            ->where_not_in('krd.status', 'K')
            ->get()->result();
        return $query;
    }
}