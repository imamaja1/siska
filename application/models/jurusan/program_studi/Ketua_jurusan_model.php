<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ketua_jurusan_model extends CI_Model {

    private $table = 'kaprodi';

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        // return $this->db->get($this->table)->result();
//        return $this->db->query("SELECT * FROM kaprodi, dosen, program_studi, jenjang WHERE jenjang.id_jenjang=program_studi.id_jenjang and  kaprodi.kode_dosen=dosen.kode_dosen and kaprodi.kode_program_studi=program_studi.kode_program_studi")->result_object();
        return $this->db->select("*")
            ->from('kaprodi')
            ->join('dosen','kaprodi.kode_dosen=dosen.kode_dosen')
            ->join('program_studi as ps','ps.kode_program_studi=kaprodi.kode_program_studi')
            ->get()->result();
    }

    public function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id) {

        return $this->db->where('kode_kaprodi', $id)->update($this->table, $data);
    }

    public function hapus($id) {
        # code...
        return $this->db->where('kode_kaprodi', $id)->delete($this->table);
    }

    public function get_kaprodi($kode_program_studi) {
        return $this->db->select('d.nama_dosen, d.nik, k.tanda_tangan, d.signature')
                        ->from('dosen as d')
                        ->join('kaprodi as k', 'd.kode_dosen=k.kode_dosen')
                        ->where('k.kode_program_studi', $kode_program_studi)
                        ->get()->row();
    }

    function get_kode_dosen($id) {
        return $this->db->select('tanda_tangan')->from('kaprodi')->where('kode_kaprodi', $id)->get()->result();
    }

}
