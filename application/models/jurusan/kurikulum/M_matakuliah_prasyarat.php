<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_matakuliah_prasyarat extends CI_Model {

    private $table = 'matakuliah_prasyarat';
    private $table_relasi = "matakuliah";

    public function __construct() {
        parent::__construct();
    }

    function get_matakuliah_prasyarat_by_kode_nama_kurikulum($kode_nama_kurikulum) {
        $sql = "SELECT kode_nama_kurikulum, ";
        $sql .= " matakuliah_yg_diambil, matakuliah.nama_matakuliah as nama_matakuliah_yg_diambil, ";
        $sql .= " matakuliah_prasyarat, m2.nama_matakuliah as nama_matakuliah_prasyarat, matakuliah.kode_matakuliah as kode_matakuliah_ambil, m2.kode_matakuliah as kode_matakuliah_syarat";
        $sql .= " FROM matakuliah_prasyarat ";
        $sql .= " INNER JOIN matakuliah ON matakuliah_prasyarat.id_matakuliah_ambil=matakuliah.id_matakuliah";
        $sql .= " INNER JOIN matakuliah m2 ON m2.id_matakuliah=matakuliah_prasyarat.id_matakuliah_syarat";
        $sql .= " WHERE kode_nama_kurikulum=? ORDER BY nama_matakuliah_yg_diambil,nama_matakuliah_prasyarat";
        return $this->db->query($sql, array($kode_nama_kurikulum))->result();
    }

    public function get_nama_matakuliah($id_matakuliah) {
        $query = $this->db->query("SELECT nama_matakuliah FROM matakuliah where id_matakuliah=?", array($id_matakuliah))->row_object();

        return $query != (null) ? $query->nama_matakuliah : "-";
    }

    public function get_kode_matakuliah_by_id_matakuliah($id_matakuliah)
    {
        $query = $this->db->select('kode_matakuliah')
            ->from('matakuliah')
            ->where('id_matakuliah',$id_matakuliah)
            ->get()->row_object();

        return $query != (null) ? $query->kode_matakuliah : "-";
    }

    public function get_prasyarat() {

        $query = $this->db->query("SELECT distinct kode_matakuliah_prasyarat, matakuliah_yg_diambil, matakuliah_prasyarat FROM matakuliah_prasyarat")->result_object();

        $i = 0;
        foreach ($query as $key) {
            $data[$i]['kode_matakuliah_prasyarat'] = $key->kode_matakuliah_prasyarat;
            $data[$i]['matakuliah_yg_diambil'] = $key->matakuliah_yg_diambil;
            $data[$i]['matakuliah_prasyarat'] = $key->matakuliah_prasyarat;
            $data[$i]['nama_matakuliah_yg_diambil'] = $this->get_nama_matakuliah($key->matakuliah_yg_diambil);
            $data[$i]['nama_matakuliah_prasyarat'] = $this->get_nama_matakuliah($key->matakuliah_prasyarat);
            $i++;
        }

        return $data;
    }

    public function get_byid_kurikulum($kode_nama_kurikulum) {
//        $query = $this->db->where('kode_nama_kurikulum', $kode_nama_kurikulum)->get($this->table)->result();
        $query = $this->db->select('*')
                ->from($this->table)
                ->join('matakuliah as mak','mak.id_matakuliah=matakuliah_prasyarat.id_matakuliah_ambil')
                ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
                ->order_by('substr(mak.kode_matakuliah,6,1) ASC')
                ->order_by('substr(mak.kode_matakuliah,-3,3) ASC')
                ->get()->result();
        if (count($query) > 0) {
            $i = 0;
            foreach ($query as $key) {
                $data[$i]['kode_nama_kurikulum'] = $kode_nama_kurikulum;
                $data[$i]['kode_matakuliah_prasyarat'] = $key->kode_matakuliah_prasyarat;
                $data[$i]['matakuliah_yg_diambil'] = $this->get_kode_matakuliah_by_id_matakuliah($key->id_matakuliah_ambil);
                $data[$i]['matakuliah_prasyarat'] = $this->get_kode_matakuliah_by_id_matakuliah($key->id_matakuliah_syarat);
                $data[$i]['jenis_prasyarat'] = $key->jenis_prasyarat;
                $data[$i]['nama_matakuliah_yg_diambil'] = $this->get_nama_matakuliah($key->id_matakuliah_ambil);
                $data[$i]['nama_matakuliah_prasyarat'] = $this->get_nama_matakuliah($key->id_matakuliah_syarat);
                $i++;
            }
        } else {
            $data = null;
        }

        return $data;
    }

    public function simpan_prasyarat($data) {
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id) {
        # code...
        return $this->db->where('kode_matakuliah_prasyarat', $id)->update($this->table, $data);
    }

    public function hapus($id) {
        return $this->db->where('kode_matakuliah_prasyarat', $id)->delete($this->table);
    }

}

/* End of file m_matakuliah_prasyarat.php */
/* Location: ./application/models/m_matakuliah_prasyarat.php */
?>