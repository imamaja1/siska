<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_nama_kurikulum extends CI_Model {

    public $table = "nama_kurikulum";

    public function get() {
//        return $this->db->query("SELECT * from nama_kurikulum, program_studi where nama_kurikulum.kode_program_studi=program_studi.kode_program_studi")->result_object();
        return $this->db->select('*')
                        ->from('nama_kurikulum  as nk')
                        ->join('program_studi as ps', 'ps.kode_program_studi=nk.kode_program_studi')
                        ->order_by('nk.kode_nama_kurikulum', 'DESC')
                        ->get()->result();
    }

    public function get_kurikulum_per_dosen($kode_dosen) {
//        return $this->db->query("SELECT * from nama_kurikulum, program_studi where nama_kurikulum.kode_program_studi=program_studi.kode_program_studi")->result_object();
        return $this->db->select('*')
                        ->from('nama_kurikulum  as nk')
                        ->join('program_studi as ps', 'ps.kode_program_studi=nk.kode_program_studi')
                        ->join('dosen', 'dosen.homebase=ps.kode_program_studi')
                        //->where('dosen.kode_dosen', $kode_dosen)
                        ->order_by('nk.kode_nama_kurikulum', 'DESC')
                        ->get()->result();
    }

    public function get_byid($kode_nama_kurikulum) {
//        return $this->db->query("SELECT * from nama_kurikulum, program_studi where nama_kurikulum.kode_program_studi=program_studi.kode_program_studi and kode_nama_kurikulum={$kode_nama_kurikulum}")->row_object();
        return $this->db->select('*')
                        ->from('nama_kurikulum as nk')
                        ->join('kurikulum_angkatan as ka', 'ka.kode_nama_kurikulum=nk.kode_nama_kurikulum', 'left')
                        ->join('program_studi as pa', 'pa.kode_program_studi=nk.kode_program_studi')
                        ->where('nk.kode_nama_kurikulum', $kode_nama_kurikulum)
                        ->get()->row_object();
    }

    public function get_kode_prodi($kode_nama_kurikulum) {
        $query = $this->db->where('kode_nama_kurikulum', $kode_nama_kurikulum)->get($this->table)->row_object();

        return $query->kode_program_studi;
    }

    public function simpan($data) {
        # code...
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id) {
        # code...
        return $this->db->where('kode_nama_kurikulum', $id)->update($this->table, $data);
    }

    public function hapus($id) {
        # code...
        return $this->db->where('kode_nama_kurikulum', $id)->delete($this->table);
    }

    function get_kode_nama_kurikulum($kode_program_studi) {
        return $this->db->get_where($this->table, array('kode_program_studi' => $kode_program_studi), 1)->row();
    }

    public function get_nama_kurikulum_by_nim($nim) {
        $angkatan = substr($nim, 0, 2);
        $jurusan = substr($nim, 2, 2);
        $jenjang = substr($nim, 4, 1);
        $query = $this->db->select('*')
                        ->from('nama_kurikulum as nk')
                        ->join('program_studi as ps', 'nk.kode_program_studi=ps.kode_program_studi')
                        ->join('jenjang as jen', 'jen.id_jenjang=ps.id_jenjang')
                        ->join('jurusan as jur', 'jur.id_jurusan=ps.id_jurusan')
                        ->where('jen.kode_jenjang', $jenjang)
                        ->where('jur.kode_jurusan', $jurusan)
                        ->where('substr(nk.angkatan,-2,2)', $angkatan)
                        ->get()->row_object();
        return $query;
    }

}
