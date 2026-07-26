<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mengajar_model_kpat extends CI_Model{

    private $table = 'mengajar_kpat';

    function get_pengajar($kelas_id)
    {
        $query = $this->db->select('*')
            ->from('mengajar_kpat')
            ->join('dosen', 'mengajar_kpat.kode_dosen=dosen.kode_dosen')
            ->join('kelas', 'kelas.kelas_id=mengajar_kpat.kelas_id')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->result();
        return $query;
    }

    function simpan($data)
    {
        return $this->db->insert($this->table, $data);
    }

    function hapus($id)
    {
        return $this->db->where('mengajar_id', $id)->delete($this->table);
    }

    function get_program_studi_by_kode_mk($id_matakuliah)
    {
        $query = $this->db->select('nama_program_studi, singkatan_program_studi')
            ->from('kurikulum as kur')
            ->join('nama_kurikulum as nk', 'kur.kode_nama_kurikulum=nk.kode_nama_kurikulum')
            ->join('program_studi as ps', 'nk.kode_program_studi=ps.kode_program_studi')
            ->where('id_matakuliah', $id_matakuliah)
            ->get()->row_object();
        return $query;
    }
}