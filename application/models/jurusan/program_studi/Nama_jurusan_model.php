<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nama_jurusan_model extends CI_Model
{

    private $table = 'program_studi';

    public function get()
    {
        return $this->db->select('*')
                ->join('jenjang as je', 'p.id_jenjang=je.id_jenjang')
                ->join('jurusan as ju', 'p.id_jurusan = ju.id_jurusan')
                ->get('program_studi as p')->result();
    }

    public function get_homebase()
    {
        return $this->db->get($this->table)->result();
    }

    public function add($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id)
    {

        return $this->db->where('kode_program_studi', $id)->update($this->table, $data);
    }

    public function hapus($id)
    {
        return $this->db->where('kode_program_studi', $id)->delete($this->table);
    }

    function get_nama($id)
    {
        $query = $this->db->get_where($this->table, array('kode_program_studi' => $id));

        return $query->num_rows() > 0 ? $query->row()->singkatan_program_studi : "";

        return $query->num_rows() > 0 ? $query->row()->singkatan_jurusan : "";
    }

    function get_all_byid($id)
    {
        $query = $this->db->get_where($this->table, array('kode_program_studi' => $id));
        return $query->row_object();
    }

    public function get_kode($id)
    {
        return $this->db->select(array('id_jenjang', 'id_jurusan'))
                ->where('kode_program_studi', $id)
                ->get($this->table)->row_object();
    }

    function get_kode_by_program_studi($kode_program_studi)
    {
        return $this->db->select('*')
                ->from('program_studi as p')
                ->join('jenjang as je', 'je.id_jenjang=p.id_jenjang')
                ->join('jurusan as ju', 'ju.id_jurusan=p.id_jurusan')
                ->where('p.kode_program_studi', $kode_program_studi)
                ->get()->row_object();
    }

    public function get_id($kode_jurusan, $kode_jenjang)
    {
        $query = $this->db->query("SELECT kode_program_studi FROM program_studi, jurusan, jenjang WHERE program_studi.id_jurusan=jurusan.id_jurusan and program_studi.id_jenjang=jenjang.id_jenjang and kode_jurusan=? and kode_jenjang=?", array($kode_jurusan, $kode_jenjang))->row_object();
        return $query->kode_program_studi;
    }

    function get_kode_jurusan_and_jenjang($kode_porgram_studi)
    {
        return $this->db->select('kode_jurusan, kode_jenjang')
                ->from('program_studi as p')
                ->join('jurusan as jur', 'p.id_jurusan=jur.id_jurusan')
                ->join('jenjang as jen', 'p.id_jenjang=jen.id_jenjang')
                ->where('kode_program_studi', $kode_porgram_studi)
                ->get()->row_object();
    }

    function get_nama_jurusan_by_kode($kode_jurusan, $kode_jenjang)
    {
        return $this->db->select('*')
                ->from('program_studi as p')
                ->join('jurusan as ju', 'p.id_jurusan=ju.id_jurusan')
                ->join('jenjang as je', 'p.id_jenjang=.je.id_jenjang')
                ->join('kaprodi as kap', 'kap.kode_program_studi=p.kode_program_studi')
                ->where(array('ju.kode_jurusan' => $kode_jurusan, 'je.kode_jenjang' => $kode_jenjang))
                ->get()->row();
    }

    function get_kode_nama_jurusan($kode_jurusan, $kode_jenjang)
    {
        return $this->db->select('*')
                ->from('program_studi')
                ->join('jenjang', 'program_studi.id_jenjang=jenjang.id_jenjang')
                ->join('jurusan', 'program_studi.id_jurusan=jurusan.id_jurusan')
                ->where(array('jenjang.kode_jenjang' => $kode_jenjang, 'jurusan.kode_jurusan' => $kode_jurusan))
                ->group_by('jurusan.kode_jurusan')->get()->row();
    }

    function get_prodi_by_nim($nim)
    {
//        $jenjang = substr($nim,4,1);
//        $jurusan = substr($nim,2,2);
//        $angkatan = substr($nim, 0, 2);
//        $fakultas = substr($nim,2,2);
//        $kode_prodi = substr($nim,4,2);
//
//        if ($angkatan < 19)
//        {
//            $query = $this->db->select('*')
//                ->from('program_studi as ps')
//                ->join('jurusan as jur', 'ps.id_jurusan=jur.id_jurusan')
//                ->join('jenjang as jen', 'ps.id_jenjang=jen.id_jenjang')
//                ->join('fakultas as fk','fk.kode_fakultas=ps.kode_fakultas')
//                ->where('jur.kode_jurusan', $jurusan)
//                ->where('jen.kode_jenjang', $jenjang)
//                ->get()->row_object();
//        }else{
//            $query = $this->db->select('*')
//                ->from('program_studi as ps')
//                ->join('fakultas as fk','fk.kode_fakultas=ps.kode_fakultas')
//                ->where('fk.kode_fakultas', $fakultas)
//                ->where('kode_prodi_univ', $kode_prodi)
//                ->get()->row_object();
//        }
        $query = get_kode_prodi($nim);

        return $query;
    }

    function get_jumlah_mahasiswa_by_kode_prodi($kode_program_studi, $id_matakuliah)
    {
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $query = $this->db->select('krs.nim,kd.kode_krs_detail')
                ->from('krs')
                ->join('mahasiswa as mah', 'mah.nim=krs.nim')
                ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
//                ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('kd.id_matakuliah', $id_matakuliah)
                ->where('krs.kode_tahun_akademik', $tahun_akademik)
                ->where_not_in('krs.semester', 'K')
                ->where_not_in('kd.status', 'K')
                ->group_by('krs.nim')
                ->get()->result();


        return count($query);
    }

    function get_mahasiswa_by_kode_prodi($kode_program_studi, $id_matakuliah, $off = null, $limit = null)
    {
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        if ($off == null && $limit == null) {
            $query = $this->db->select('krs.nim,kd.kode_krs_detail')
                    ->from('krs')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
//                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->where('mah.program_studi_kode', $kode_program_studi)
                    ->where('kd.id_matakuliah', $id_matakuliah)
                    ->where('krs.kode_tahun_akademik', $tahun_akademik)
                    ->where_not_in('krs.semester', 'K')
                    ->where_not_in('kd.status', 'K')
                    ->group_by('krs.nim')
                    ->get_compiled_select();

            $mahasiswa = $this->db->query($query . " ORDER BY substr(mah.nim,-4,4) ASC")->result();
        } else {
            $query = $this->db->select('krs.nim,kd.kode_krs_detail')
                    ->from('krs')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
//                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->where('mah.program_studi_kode', $kode_program_studi)
                    ->where('kd.id_matakuliah', $id_matakuliah)
                    ->where('krs.kode_tahun_akademik', $tahun_akademik)
                    ->where_not_in('krs.semester', 'K')
                    ->where_not_in('kd.status', 'K')
                    ->group_by('krs.nim')
                    ->get_compiled_select();

            $mahasiswa = $this->db->query($query . " ORDER BY substr(mah.nim,-4,4) ASC LIMIT ? OFFSET ?", array($limit, $off))->result();
        }

        return $mahasiswa;
    }
	function get_jumlah_mahasiswa_by_kode_prodi_kpat($kode_program_studi, $id_matakuliah)
    {
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $query = $this->db->select('krs.nim,kd.kode_krs_detail')
                ->from('krs')
                ->join('mahasiswa as mah', 'mah.nim=krs.nim')
                ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
//                ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('kd.id_matakuliah', $id_matakuliah)
                ->where('krs.kode_tahun_akademik', $tahun_akademik)
                ->where_not_in('krs.semester', 'K')
                ->where('kd.status', 'K')
                ->group_by('krs.nim')
                ->get()->result();


        return count($query);
    }
  	function get_mahasiswa_by_kode_prodi_kpat($kode_program_studi, $id_matakuliah, $off = null, $limit = null)
    {
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        if ($off == null && $limit == null) {
            $query = $this->db->select('krs.nim,kd.kode_krs_detail,sp.pembayaran_sks as status_krs,kp.status_cetak as status_perwalian')
                    ->from('krs')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
//                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->join('konsultasi_perwalian as kp',"kp.nim=krs.nim and kp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->where('mah.program_studi_kode', $kode_program_studi)
                    ->where('kd.id_matakuliah', $id_matakuliah)
                    ->where('krs.kode_tahun_akademik', $tahun_akademik)
                    ->where_not_in('krs.semester', 'K')
                    ->where('kd.status', 'K')
                    ->group_by('krs.nim')
                    ->get_compiled_select();

            $mahasiswa = $this->db->query($query . " ORDER BY substr(mah.nim,-4,4) ASC")->result();
        } else {
            $query = $this->db->select('krs.nim,kd.kode_krs_detail')
                    ->from('krs')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
//                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->join('konsultasi_perwalian as kp',"kp.nim=krs.nim and kp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->where('mah.program_studi_kode', $kode_program_studi)
                    ->where('kd.id_matakuliah', $id_matakuliah)
                    ->where('krs.kode_tahun_akademik', $tahun_akademik)
                    ->where_not_in('krs.semester', 'K')
                    ->where('kd.status', 'K')
                    ->group_by('krs.nim')
                    ->get_compiled_select();

            $mahasiswa = $this->db->query($query . " ORDER BY substr(mah.nim,-4,4) ASC LIMIT ? OFFSET ?", array($limit, $off))->result();
        }

        return $mahasiswa;
    }
  	function get_mahasiswa_kelas_by_kode_prodi($kode_program_studi, $id_matakuliah, $off = null, $limit = null)
    {
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        if ($off == null && $limit == null) {
            $query = $this->db->select('krs.nim,kd.kode_krs_detail,sp.pembayaran_sks as status_krs,kp.status_cetak as status_perwalian')
                    ->from('krs')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
//                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->join('konsultasi_perwalian as kp',"kp.nim=krs.nim and kp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->where('mah.program_studi_kode', $kode_program_studi)
                    ->where('kd.id_matakuliah', $id_matakuliah)
                    ->where('krs.kode_tahun_akademik', $tahun_akademik)
                    ->where('kp.status_cetak','A')
                    ->where_not_in('sp.pembayaran_sks','0')
                    ->where_not_in('krs.semester', 'K')
                    ->where_not_in('kd.status', 'K')
                    ->group_by('krs.nim')
                    ->get_compiled_select();

            $mahasiswa = $this->db->query($query . " ORDER BY substr(mah.nim,-4,4) ASC")->result();
        } else {
            $query = $this->db->select('krs.nim,kd.kode_krs_detail,sp.pembayaran_sks as status_krs,kp.status_cetak as status_perwalian')
                    ->from('krs')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
//                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik='$tahun_akademik' and sp.pembayaran_sks !='0'")
                    ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->join('konsultasi_perwalian as kp',"kp.nim=krs.nim and kp.kode_tahun_akademik=".$this->db->escape($tahun_akademik))
                    ->where('mah.program_studi_kode', $kode_program_studi)
                    ->where('kd.id_matakuliah', $id_matakuliah)
                    ->where('krs.kode_tahun_akademik', $tahun_akademik)
                    ->where('kp.status_cetak','A')
                    ->where_not_in('sp.pembayaran_sks','0')
                    ->where_not_in('krs.semester', 'K')
                    ->where_not_in('kd.status', 'K')
                    ->group_by('krs.nim')
                    ->get_compiled_select();

            $mahasiswa = $this->db->query($query . " ORDER BY substr(mah.nim,-4,4) ASC LIMIT ? OFFSET ?", array($limit, $off))->result();
        }

        return $mahasiswa;
    }

}
