<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class mahasiswa_model extends CI_Model {

    private $table = 'mahasiswa';
    private $provinsi = 'provinsi';

    public function get($nim = null) {
        if ($nim == null) {
            return $this->db->get($this->table)->result();
        } else {
            return $this->db->get_where($this->table, array('nim' => $nim))->row_object();
        }
    }

    public function get_provinsi() {
        return $this->db->get($this->provinsi)->result();
    }

    public function get_pagination_search($angkatan, $kode_program_studi, $limit, $offset) {
        if ($angkatan < 19)
        {
            return $this->db->select('*')
                ->from($this->table)
                ->where('mid(nim,1,2)', $angkatan)
                ->where('program_studi_kode', $kode_program_studi)
                ->where_in('mid(nim,6,1)', array('1', '2', '3'))
                ->limit($limit, $offset)
                ->get()->result();
        }else{
            return $this->db->select('*')
                ->from($this->table)
                ->where('mid(nim,1,2)', $angkatan)
                ->where('program_studi_kode', $kode_program_studi)
                ->limit($limit, $offset)
                ->get()->result();
        }

    }

    public function get_mahasiswa_by_angkatan_jurusan($angkatan, $kode_program_studi) {
        if ($angkatan < 19)
        {
            return $this->db->select('*')
                ->from($this->table)
                ->where('mid(nim,1,2)', $angkatan)
                ->where('program_studi_kode', $kode_program_studi)
                ->where_in('mid(nim,6,1)', array('1', '2', '3'))
                ->get()->result();
        }else{
            $kode = $this->db->select('*')
                ->from('program_studi as ps')
                ->where('kode_program_studi',$kode_program_studi)
                ->get()->row_object();

            return $this->db->select('*')
                ->from($this->table)
                ->where('mid(nim,1,2)', $angkatan)
                ->where('program_studi_kode', $kode_program_studi)
                ->get()->result();
        }
    }

    public function get_mahasiswa_ekstensi($angkatan, $gelombang) {
        return $this->db->get_where($this->table, array('mid(nim,1,2)' => $angkatan, 'mid(nim,6,1)' => $gelombang))->result();
    }

    public function get_mahasiswa_ekstensi_count($angkatan, $gelombang) {
        return $this->db->get_where($this->table, array('mid(nim,1,2)' => $angkatan, 'mid(nim,6,1)' => $gelombang))->result();
    }

    public function get_mahasiswa_ekstensi_pagination($angkatan, $gelombang, $limit, $offset) {
        return $this->db->get_where($this->table, array('mid(nim,1,2)' => $angkatan, 'mid(nim,6,1)' => $gelombang), $limit, $offset)->result();
    }

    public function get_count_search($angkatan, $kode_program_studi) {
        if ($angkatan < 19)
        {
            return $this->db->select('*')
                ->from($this->table)
                ->where('mid(nim,1,2)', $angkatan)
                ->where('program_studi_kode', $kode_program_studi)
                ->where_in('mid(nim,6,1)', array('1', '2', '3'))
                ->get()->result();
        }else{
            return $this->db->select('*')
                ->from($this->table)
                ->where('mid(nim,1,2)', $angkatan)
                ->where('program_studi_kode', $kode_program_studi)
                ->get()->result();
        }
    }

    public function get_nim() {
        return $this->db->get($this->table)->result();
    }

    public function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function del($nim) {
        return $this->db->delete($this->table, array('nim' => $nim)); // Also fixed bareword nim
    }

    public function update($nim, $data = []) {
        return $this->db->update($this->table, $data, array('nim' => $nim));
    }

    public function search_by_nim($kata_kunci) {
        return $this->db->get_where($this->table, array('nim' => $kata_kunci))->result();
    }

    public function search_by_nama($kata_kunci, $limit, $offset) {
        return $this->db->select('*')->from($this->table)->like('nama_mahasiswa', $kata_kunci)->limit($limit, $offset)->get()->result();
    }

    public function count_search_by_nama($kata_kunci) {
        return $this->db->select('*')->from($this->table)->like('nama_mahasiswa', $kata_kunci)->get()->result();
    }

    function get_mahasiswa_by_nim($nim) {
        return $this->db->get_where($this->table, array('nim' => $nim))->row();
    }

    public function cek_mahasiswa_baru($homebase, $ta) {

        $query = $this->db->select('nim')
            ->from('mahasiswa as mah')
            ->where('mah.program_studi_kode', $homebase)
            ->where('mid(nim,1,2)', $ta)
            ->get()->result_object();

        return $query;
    }

    public function get_mahasiswa_perwalian($ta, $limit, $offset) {
        return $this->db->select('nim')
                        ->from($this->table)
                        ->where('mid(nim,1,2)', $ta)
                        ->limit($limit, $offset)
                        ->order_by('nim ASC')
                        ->get()->result();
    }

    function valid_nim($nim) {
        $this->db->where('nim', $nim);
        $this->db->from($this->table);
        if ($this->db->count_all_results() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_mahasiswa_transfer($tahun_akademik)
    {
        $query = $this->db->select('*, mah.nim, ps.nama_program_studi')            
            ->from('mahasiswa as mah')          	
          	->join('program_studi as ps', 'mah.program_studi_kode=ps.kode_program_studi')
            ->join('krs', 'mah.nim=krs.nim and krs.semester="K"', 'LEFT')
            ->where_not_in('status_pendaftaran','B')
//            ->where('krs.semester','K')
            ->where('substr(mah.nim,1,2)', $tahun_akademik)
            ->group_by('mah.nim')
            ->get()->result_object();
        return $query;
    }

}
