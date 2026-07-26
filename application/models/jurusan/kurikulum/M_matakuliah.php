<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_matakuliah extends CI_Model {

    private $table = "matakuliah";

    public function __construct() {
        parent::__construct();
    }

    public function get_matakuliah($kode_prodi,$limit, $offset) {
        return $this->db->where('kode_program_studi',$kode_prodi)->order_by('substr(kode_matakuliah,6,1) ASC')->order_by('substr(kode_matakuliah,-2,2) ASC')->get($this->table, $limit, $offset)->result();
    }

    public function count_matakuliah($kode_prodi) {
        return $this->db->where('kode_program_studi', $kode_prodi)->get($this->table)->result();
    }

    public function get_matakuliah_cari($keyword,$kode_prodi,$limit, $offset) {
        return $this->db->where('kode_program_studi',$kode_prodi)->like('nama_matakuliah',$keyword,'both')->get($this->table, $limit, $offset)->result();
    }

    public function count_matakuliah_cari($keyword,$kode_prodi) {
        return $this->db->where('kode_program_studi', $kode_prodi)->like('nama_matakuliah',$keyword,'both')->get($this->table)->result();
    }

    function get_nama_matakuliah($id_matakuliah) {
        $query = $this->db->where('id_matakuliah', $id_matakuliah)->get($this->table)->row_object();

        return $query->nama_matakuliah;
    }

    public function get_matakuliah_byid_prodi($kode_program_studi) {
        return $this->db->where('kode_program_studi', $kode_program_studi)
                        ->order_by('substr(kode_matakuliah,6,1) ASC')
                        ->order_by('substr(kode_matakuliah,-2,2) ASC')
                        ->get($this->table)->result();
    }

    public function get_matakuliah_byid_kurikulum($kode_nama_kurikulum) {
//        return $this->db->query("SELECT * FROM nama_kurikulum, matakuliah WHERE nama_kurikulum.kode_program_studi=matakuliah.kode_program_studi and kode_nama_kurikulum={$kode_nama_kurikulum}")->result();
        return $this->db->select('*')
            ->from('nama_kurikulum as nk')
            ->join('matakuliah as mak', 'nk.kode_program_studi=mak.kode_program_studi')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->get()->result();
    }

    public function get_matakuliah_kuirkulum($kode_nama_kurikulum)
    {
        return $this->db->select('*')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->get()->result();
    }

    public function simpan($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id) {

        return $this->db->where('id_matakuliah', $id)->update($this->table, $data);
    }

    public function hapus($id) {
        return $this->db->where('id_matakuliah', $id)->delete($this->table);
    }

    public function cek_kode_matakuliah($id_matakuliah) {
        return $this->db->get_where($this->table, array('id_matakuliah' => $id_matakuliah));
    }

    function get_matakuliah_by_kode($id_matakuliah) {
        return $this->db->get_where($this->table, array('id_matakuliah' => $id_matakuliah))->row();
    }

}
