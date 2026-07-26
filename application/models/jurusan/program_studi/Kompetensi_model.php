<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kompetensi_model extends CI_Model {

    public $table = "kompetensi";

    public function __construct() {
        parent::__construct();
    }

    public function get_kompetensi($kode_program_studi = null) {
      	$kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        if ($kode_program_studi == null){
            return $this->db->get($this->table)->result();
        }else{
          	return  $this->db->select('*')
                        ->from('kompetensi')
                        ->join('matakuliah_kompetensi','kompetensi.kode_kompetensi = matakuliah_kompetensi.kode_kompetensi')
                        ->join('kurikulum', 'matakuliah_kompetensi.id_matakuliah=kurikulum.id_matakuliah')
                        ->group_by('kompetensi.kode_kompetensi')
                        ->where('kode_program_studi',$kode_program_studi)
                        //->where('kurikulum.kode_nama_kurikulum', $kode_nama_kurikulum)
                        ->get()->result();
            //return $this->db->get_where($this->table, array('kode_program_studi'=>$kode_program_studi))->result();
        }
    }

    public function simpan($data) {
        # code...
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id) {
        # code...
        return $this->db->where('kode_kompetensi', $id)->update($this->table, $data);
    }

    public function hapus($id) {
        # code...
        return $this->db->where('kode_kompetensi', $id)->delete($this->table);
    }

    public function get_kompetensi_mahasiswa($nim)
    {
        $query = $this->db->select('*')
            ->from('kompetensi_mahasiswa as km')
            ->join('kompetensi as k', 'k.kode_kompetensi=km.kode_kompetensi')
            ->where('km.nim', $nim)
            ->get();

        return $query;
    }
  
  
    public function get_kompetensi_jurusan_mahasiswa($nim)
    {
        return $this->db->select('km.kode_kompetensi_mahasiswa, km.kode_kompetensi,mhs.nim, mhs.nama_mahasiswa, ps.kode_program_studi, ps.nama_program_studi, mhs.status_pendaftaran, k.nama_kompetensi')
            ->from('mahasiswa as mhs')
            ->join('program_studi as ps', 'mhs.program_studi_kode=ps.kode_program_studi')
            ->join('kompetensi_mahasiswa as km', 'km.nim=mhs.nim')
            ->join('kompetensi as k', 'k.kode_kompetensi=km.kode_kompetensi')
            ->where('mhs.nim', $nim)
            ->get();
    }

    public function ubah_kompetensi_mahasiswa($data, $kode_kompetensi_mahasiswa)
    {
        return $this->db->where('kode_kompetensi_mahasiswa', $kode_kompetensi_mahasiswa)->update('kompetensi_mahasiswa', $data);
    }


    public function simpan_kompetensi_mahasiswa($data)
    {
        return $this->db->insert('kompetensi_mahasiswa', $data);
    }

}
