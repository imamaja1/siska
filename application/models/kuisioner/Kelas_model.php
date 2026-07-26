<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kelas_model extends CI_Model
{
    private $table = 'kelas';

    function get_kelas()
    {
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk', 'kelas.nama_kelas_id=nk.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->get()->result();
    }
    function get_nama_kelas($nama_kelas_id = null )
    {
        if ($nama_kelas_id == null)
        {
            $query = $this->db->get('nama_kelas')->result_array();
        }else{
            $query = $this->db->where('nama_kelas_id', $nama_kelas_id)->get('nama_kelas')->row_object();
        }
        return $query;
    }

    function get_nama_kelas_by_kelas_id($kelas_id)
    {
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk','kelas.nama_kelas_id=nk.nama_kelas_id')
            ->where('kelas_id', $kelas_id)
            ->get()->row_object();
    }

    function get_matakuliah_kelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        $query = $this->db->select('id_matakuliah')
            ->from('krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('id_matakuliah', $id_matakuliah)
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('id_matakuliah ')
            ->get()->result();

        return $query;
    }

    function add_kelas_mahasiswa($data)
    {
        return $this->db->insert('kelas_mahasiswa', $data);
    }

    function simpan_kelas($data)
    {
        $this->db->insert('kelas', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function cek_exis_kelas_mahasiswa($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        $query = $this->db->select('*')
            ->from('kelas')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kode_program_studi', $kode_program_studi)
            ->where('id_matakuliah', $id_matakuliah)
            ->get()->result();

        return $query;
    }

    function get_kelas_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        $query = $this->db->select('* ,count(kode_krs_detail) as jml,kelas.kelas_id')
            ->from('kelas')
            ->join('nama_kelas as nk', 'kelas.nama_kelas_id=nk.nama_kelas_id')
            ->join('kelas_mahasiswa as km', 'kelas.kelas_id=km.kelas_id','left')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kode_program_studi', $kode_program_studi)
            ->where('id_matakuliah', $id_matakuliah)
            ->group_by('kelas.nama_kelas_id')
            ->order_by('kelas.nama_kelas_id ASC')
            ->get()->result();
        return $query;
    }

    function get_matakuliah_combobox($kode_tahun_akademik, $kode_program_studi, $semester)
    {
        $query = $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk', 'kelas.nama_kelas_id=nk.nama_kelas_id')
            ->join('matakuliah as mk', 'kelas.id_matakuliah=mk.id_matakuliah')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kelas.kode_program_studi', $kode_program_studi)
            ->where('substr(kelas.kode_matakuliah,6,1)', $semester)
            ->group_by('kelas.id_matakuliah')
            ->order_by('kelas.id_matakuliah ASC')
            ->get()->result();
        return $query;
    }

    function get_mahasiswa_kelas($kelas_id)
    {
        $query = $this->db->select('*')
            ->from('kelas')
            ->join('kelas_mahasiswa as km','km.kelas_id=kelas.kelas_id')
            ->join('krs_detail as kd','kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs','krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->where('kelas.kelas_id',$kelas_id)
            ->order_by('substr(mah.nim,1,2) asc')
            ->order_by('substr(mah.nim,6,1) asc')
            ->order_by('substr(mah.nim,-4,4) asc')
            ->get()->result();

        return $query;
    }

    function get_matakuliah_by_kode_matakuliah($kode_matakuliah)
    {
        return $this->db->where('kode_matakuliah', $kode_matakuliah)->get('matakuliah')->row_object();
    }

    function get_matakuliah_by_kelas_id($kelas_di)
    {
        return $this->db->select('*')
            ->from('kelas')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas_id', $kelas_di)
            ->get()->row_object();
    }

    function get_matakuliah($kode_tahun_akademik)
    {
        $query = $this->db->select('kelas.id_matakuliah, mak.kode_matakuliah, mak.nama_matakuliah')
            ->from('kelas')
            ->join('matakuliah as mak','kelas.id_matakuliah=mak.id_matakuliah')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.id_matakuliah')
            ->order_by('kelas.id_matakuliah ASC')
            ->get()->result();

        return $query;
    }

    function get_kelas_by_kode_makul($id_matakuliah, $kode_tahun_akademik)
    {
        $query = $this->db->select('kelas.kelas_id, nk.nama_kelas')
            ->from('kelas')
            ->join('nama_kelas as nk', 'kelas.nama_kelas_id=nk.nama_kelas_id')
            ->where('id_matakuliah', $id_matakuliah)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.nama_kelas_id')
            ->order_by('kelas.nama_kelas_id ASC')
            ->get()->result();

        return $query;
    }

    function pindah_kelas($data, $id)
    {
        return $this->db->where('kelas_mahasiswa_id', $id)->update('kelas_mahasiswa', $data);
    }

    function get_matakuliah_by_kode_tahun_akademik($kode_tahun_akademik)
    {
        return $this->db->select('*')
            ->from('kelas')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.id_matakuliah')
            ->order_by('mak.kode_matakuliah ASC')
            ->get()->result();
    }

    function autocomplate($keyword, $id_matakuliah, $kode_tahun_akademik) {
//        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $tahun_akademik = $kode_tahun_akademik;
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->where('kd.id_matakuliah', $id_matakuliah)
            ->where('krs.kode_tahun_akademik', $tahun_akademik)
            ->where_not_in('kd.status', ['K'])
            ->where('(krs.nim like "'.$keyword.'%" OR nama_mahasiswa like "%'.$keyword.'%")')
            ->order_by('krs.nim asc')
            ->limit(6)
            ->get()->result();
    }

    function cek_exis($kode_krs_detail)
    {
        return $this->db->select('*,kd.status')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('nama_kelas as nk', 'kelas.nama_kelas_id=nk.nama_kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->where('kd.kode_krs_detail', $kode_krs_detail)
            ->get()->row_object();
    }

    function hapus($id)
    {
        return $this->db->where('kelas_mahasiswa_id', $id)->delete('kelas_mahasiswa');
    }

    public function get_kelas_exist($id_matakuliah, $kode_tahun_akademik)
    {
//        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $tahun_akademik = $kode_tahun_akademik;
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk','kelas.nama_kelas_id=nk.nama_kelas_id')
            ->where('id_matakuliah', $id_matakuliah)
            ->where('kode_tahun_akademik', $tahun_akademik)
            ->get()->result_object();
    }

}