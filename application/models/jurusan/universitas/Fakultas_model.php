<?php


class Fakultas_model extends CI_Model
{
  private $table = "fakultas";

  public function get($id = null)
    {
        if ($id == null) {

            return $this->db->select('f.kode_fakultas, f.nama_fakultas, d.nama_dosen as dekan')
                ->from('fakultas as f')
                ->join('dosen as d', 'f.dekan=d.kode_dosen')
                ->get()->result();
        } else {
            return $this->db->select('f.kode_fakultas, f.nama_fakultas, d.kode_dosen, d.nama_dosen as dekan')
                ->from('fakultas as f')
                ->join('dosen as d', 'f.dekan=d.kode_dosen')
                ->where('kode_fakultas', $id)
                ->get()->row_object();
        }
    }

    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('kode_fakultas', $id)->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where('kode_fakultas', $id)->delete($this->table);
    }
    public function getProdiFromDekan($id)
    {
        return $this->db->select('ps.kode_program_studi, ps.nama_program_studi, f.kode_fakultas, f.nama_fakultas')
            ->from('fakultas as f')
            ->join('program_studi as ps', 'f.kode_fakultas=ps.kode_fakultas')
            ->where('f.dekan', $id)
            ->get()->result_array();
    }
     public function getProdi($id)
    {
        return $this->db->select('ps.kode_program_studi, ps.nama_program_studi, f.kode_fakultas, f.nama_fakultas')
            ->from('fakultas as f')
            ->join('program_studi as ps', 'f.kode_fakultas=ps.kode_fakultas')
            ->where('f.kode_fakultas', $id)
            ->get()->result_array();
    }
}