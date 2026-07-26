<?php

class M_kurikulum_angkatan extends CI_Model
{
    public function get($id = null)
    {
        if ($id == null)
        {
            return $this->db->select('*')
                ->from('kurikulum_angkatan as ka')
                ->join('nama_kurikulum as nk','ka.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                ->join('program_studi as ps','ps.kode_program_studi=nk.kode_program_studi')
                ->order_by('angkatan','DESC')
                ->order_by('nama_program_studi ASC')
                ->get()->result_object();
        }else{
            return $this->db->select('*')
                ->from('kurikulum_angkatan as ka')
                ->join('nama_kurikulum as nk','ka.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                ->join('program_studi as ps','ps.kode_program_studi=nk.kode_program_studi')
                ->where('kode_kurikulum_angkatan', $id)
                ->get()->row_object();
        }
    }

    public function add($data)
    {
        return $this->db->insert('kurikulum_angkatan', $data);
    }
    public function hapus($id)
    {
        return $this->db->where('kode_kurikulum_angkatan', $id)->delete('kurikulum_angkatan');
    }

    public function update($id, $data)
    {
        return $this->db->where('kode_kurikulum_angkatan', $id)->update('kurikulum_angkatan', $data);
    }
}