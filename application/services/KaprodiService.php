<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KaprodiService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    // ===================== GENERIC HELPERS =====================

    public function get_result($select, $from, $joins = [], $where = [], $group_by = null, $order_by = null, $limit = null) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        if ($group_by) $this->db->group_by($group_by);
        if ($order_by) $this->db->order_by($order_by);
        if ($limit) $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_result_object($select, $from, $joins = [], $where = [], $group_by = null, $order_by = null) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        if ($group_by) $this->db->group_by($group_by);
        if ($order_by) $this->db->order_by($order_by);
        return $this->db->get()->result_object();
    }

    public function get_row_object($select, $from, $joins = [], $where = []) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        return $this->db->get()->row_object();
    }

    public function get_row_array($select, $from, $joins = [], $where = []) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        return $this->db->get()->row_array();
    }

    public function get_row($select, $from, $joins = [], $where = []) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        return $this->db->get()->row();
    }

    public function get_result_array($select, $from, $joins = [], $where = [], $group_by = null, $order_by = null) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        if ($group_by) $this->db->group_by($group_by);
        if ($order_by) $this->db->order_by($order_by);
        return $this->db->get()->result_array();
    }

    public function get_all($table) {
        return $this->db->get($table)->result_object();
    }

    public function get_where_row($table, $where) {
        return $this->db->get_where($table, $where)->row_object();
    }

    public function get_where_row_array($table, $where) {
        return $this->db->get_where($table, $where)->row_array();
    }

    public function update($table, $data, $where) {
        return $this->db->where($where)->update($table, $data);
    }

    public function insert($table, $data) {
        return $this->db->insert($table, $data);
    }

    public function delete($table, $where) {
        return $this->db->where($where)->delete($table);
    }

    public function num_rows($select, $from, $joins = [], $where = []) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        return $this->db->get()->num_rows();
    }

    public function order_by_get($table, $order_by) {
        return $this->db->order_by($order_by)->get($table)->result_object();
    }

    // ===================== KAPRODI-SPECIFIC =====================

    public function get_kaprodi_prodi_array($kode_dosen) {
        return $this->db->select('kode_program_studi')->from('kaprodi')->where('kode_dosen', $kode_dosen)->get()->result_array();
    }

    public function get_kaprodi_prodi_row($kode_dosen) {
        return $this->db->select('kode_program_studi')->from('kaprodi')->where('kode_dosen', $kode_dosen)->get()->row_object();
    }

    public function get_kaprodi_prodi_row_array($kode_dosen) {
        return $this->db->select('*')->from('kaprodi')->where('kode_dosen', $kode_dosen)->get()->row_array();
    }

    public function get_kelas_validasi_uts($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('semester, ps.nama_program_studi, ps.singkatan_program_studi, kelas.status_nilai_uts, kelas.validasi_nilai_uts, kelas.validasi_dekan_uts, nama_kelas, nama_matakuliah, kelas.kelas_id, mengajar_id, GROUP_CONCAT(DISTINCT nama_dosen SEPARATOR ",") as nama_dosen, mak.kode_matakuliah,param_uts')
            ->from('kelas')
            ->join('kelas_validasi as kv', 'kv.kelas_id = kelas.kelas_id', 'left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kv.updated_at','desc')
            ->get()->result();
    }

    public function get_kelas_validasi_uas($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('max(kv.updated_at) as updated_at, semester, ps.nama_program_studi, ps.singkatan_program_studi, kelas.status_nilai, kelas.validasi_nilai, kelas.validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(DISTINCT nama_dosen SEPARATOR ",") as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('kelas_validasi as kv', 'kv.kelas_id = kelas.kelas_id', 'left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('updated_at','DESC')
            ->get()->result();
    }

    public function get_kelas_index_uts($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.nama_program_studi, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->get()->result();
    }

    public function get_kelas_search($kode_program_studi, $kode_tahun_akademik, $keyword) {
        return $this->db->select('status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->group_start()->like('mak.nama_matakuliah', $keyword)->or_like('dosen.nama_dosen', $keyword)->group_end()
            ->where('kelas.kode_program_studi', $kode_program_studi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->get()->result();
    }

    public function get_mahasiswa_uts($kelas_id) {
        return $this->db->select('dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('km.kelas_id', $kelas_id)
            ->order_by('dummy_uts', 'desc')
            ->get()->result();
    }

    public function get_mahasiswa_uts_asc($kelas_id) {
        return $this->db->select('dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('km.kelas_id', $kelas_id)
            ->order_by('mah.nim', 'asc')
            ->get()->result();
    }

    public function get_mahasiswa_uas($kelas_id) {
        return $this->db->select('grade, dummy_id,khd.kode_khs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dummy_na >= spd.nilai_minimum AND dummy_na <= spd.nilai_maksimum')
            ->where('km.kelas_id', $kelas_id)
            ->order_by('dummy_na', 'desc')
            ->get()->result();
    }

    public function get_data_kelas($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function get_catatan_revisi_count($kelas_id) {
        return $this->db->select('*')->from('catatan_revisi')
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen', 1)
            ->where('kode_prodi', 1)
            ->where('param_dosen', 1)
            ->where('param_prodi', null)
            ->get()->result();
    }

    public function get_catatan_revisi_uas_count($kelas_id) {
        return $this->db->select('*')->from('catatan_revisi_uas')
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen', 1)
            ->where('kode_prodi', 1)
            ->where('param_dosen', 1)
            ->where('param_prodi', null)
            ->get()->result();
    }

    public function get_query_dosen_kelas($kelas_id) {
        return $this->db->select('*, mt.kode_matakuliah as kdmk')
            ->from('kelas as kl')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kl.kode_tahun_akademik')
            ->join('matakuliah as mt', 'mt.id_matakuliah=kl.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kl.nama_kelas_id')
            ->join('mengajar as mj', 'mj.kelas_id=kl.kelas_id')
            ->join('dosen as ds', 'ds.kode_dosen=mj.kode_dosen')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kl.kelas_id')
            ->join('krs_detail as kde', 'kde.kode_krs_detail=km.kode_krs_detail')
            ->join('krs as rs', 'rs.kode_krs=kde.kode_krs')
            ->join('persentasi_nilai_dosen as pnd', 'pnd.kelas_id=kl.kelas_id')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function get_query_dosen_kelas_no_pnd($kelas_id) {
        return $this->db->select('*, mt.kode_matakuliah as kdmk')
            ->from('kelas as kl')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kl.kode_tahun_akademik')
            ->join('matakuliah as mt', 'mt.id_matakuliah=kl.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kl.nama_kelas_id')
            ->join('mengajar as mj', 'mj.kelas_id=kl.kelas_id')
            ->join('dosen as ds', 'ds.kode_dosen=mj.kode_dosen')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kl.kelas_id')
            ->join('krs_detail as kde', 'kde.kode_krs_detail=km.kode_krs_detail')
            ->join('krs as rs', 'rs.kode_krs=kde.kode_krs')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function get_query_prodi($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=kp.kode_dosen')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function get_query_fakultas($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function validasi_kelas_dummy($kelas_id) {
        $nilai = $this->db->select('*')
            ->from('kelas_mahasiswa as km')
            ->join('dummy_nilai as dn', 'dn.kode_krs_detail=km.kode_krs_detail')
            ->where('kelas_id', $kelas_id)
            ->get()->result();
        return $nilai;
    }

    public function update_khs_detail($kode_khs_detail, $data) {
        return $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', $data);
    }

    // ===================== KELAS =====================

    public function get_kelas_by_prodi_ta($prodi, $tahun_now) {
        return $this->db->select('mak.id_matakuliah, mak.kode_matakuliah, mak.nama_matakuliah, mak.sks_teori, mak.sks_praktek, mak.sks_praktikum, nk.nama_kurikulum')
            ->from('nama_kurikulum as nk')
            ->join('kurikulum as kur', 'nk.kode_nama_kurikulum=kur.kode_nama_kurikulum')
            ->join('krs_detail as kd', 'kd.id_matakuliah=kur.id_matakuliah')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->where('nk.kode_program_studi', $prodi)
            ->where('kode_tahun_akademik', $tahun_now)
            ->group_by('mak.id_matakuliah')
            ->order_by('mak.nama_matakuliah ASC')
            ->get()->result();
    }

    public function get_all_nama_kelas() {
        return $this->db->get('nama_kelas')->result_object();
    }

    public function get_all_dosen() {
        return $this->db->get('dosen')->result_object();
    }

    // ===================== KONSULTASI PERWALIAN =====================

    public function get_detail_perwalian($nim) {
        return $this->db->select('nama_dosen,mah.nim, nama_mahasiswa, mah.email, mah.telepon')
            ->from('perwalian as per')
            ->join('dosen','per.kode_dosen=dosen.kode_dosen')
            ->join('mahasiswa as mah','mah.nim=per.nim')
            ->where('per.nim', $nim)
            ->get()->row_object();
    }

    public function get_detail_perwalian_print($nim) {
        return $this->db->select('mah.foto,nama_dosen,mah.nim, nama_mahasiswa, mah.email, mah.telepon')
            ->from('perwalian as per')
            ->join('dosen','per.kode_dosen=dosen.kode_dosen')
            ->join('mahasiswa as mah','mah.nim=per.nim')
            ->where('per.nim', $nim)
            ->get()->row_object();
    }

    public function get_mahasiswa_by_nim($nim) {
        return $this->db->select('nim,nama_mahasiswa')->from('mahasiswa')->where('nim', $nim)->get()->row_object();
    }

    public function get_krs_by_nim($nim) {
        return $this->db->select('kode_tahun_akademik, semester')
            ->from('krs')
            ->where('nim', $nim)
            ->where_not_in('semester','K')
            ->group_by('kode_tahun_akademik')
            ->get()->result();
    }

    public function get_konsultasi_perwalian($id) {
        return $this->db->get_where('konsultasi_perwalian', array('kode_konsultasi_perwalian'=>$id))->row_array();
    }

    public function autocomplete_dosen($keyword) {
        return $this->db->select('kode_dosen, nama_dosen')
            ->from('dosen')
            ->like('nama_dosen', $keyword)
            ->get()->result();
    }

    public function get_perwalian_by_dosen($kode_dosen) {
        return $this->db->select('m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, p.kode_dosen_perwakilan, p.date_created')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->where(array('p.kode_dosen' => $kode_dosen, 'm.status' => 'A'))
            ->order_by('m.nim', 'DESC')
            ->get()->result();
    }

    public function get_dosen_perwalian() {
        return $this->db->select('dosen.nama_dosen')
            ->from('dosen')
            ->join('program_studi as ps', 'dosen.homebase=ps.kode_program_studi')
            ->where('status_dosen', 'T')
            ->where('status_login', 'A')
            ->get()->result();
    }

    // ===================== KPAT =====================

    public function get_matakuliah_kpat_by_prodi($kode_tahun_akademik, $kode_program_studi) {
        return $this->db->select('mak.id_matakuliah, nama_matakuliah, mak.kode_matakuliah')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->where_in('mak.kode_program_studi', $kode_program_studi)
            ->where('kd.status','K')
            ->group_by('kd.id_matakuliah')
            ->get()->result_object();
    }

    public function get_matakuliah_by_prodi($kode_program_studi) {
        return $this->db->select('id_matakuliah,kode_matakuliah,nama_matakuliah')->from('matakuliah')->where_in('kode_program_studi',$kode_program_studi)->get()->result_object();
    }

    public function get_mahasiswa_kpat($ta, $kode_matakuliah, $angkatan = null) {
        $this->db->select('sg.grade as grade1,spd.grade as grade2,mah.nim,mah.nama_mahasiswa,kh.*')
            ->from('krs , sistem_penilaian_detail as spd,stup_grade as sg')
            ->join('krs_detail as kd', 'kd.kode_krs = krs.kode_krs')
            ->join('khs_detail as kh', 'kd.kode_krs_detail = kh.kode_krs_detail')
            ->join('mahasiswa as mah','mah.nim = krs.nim')
            ->where('krs.kode_tahun_akademik',$ta)
            ->where('kd.id_matakuliah',$kode_matakuliah)
            ->where('kd.status','K')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('nilai_akhir >= spd.nilai_minimum AND nilai_akhir <= spd.nilai_maksimum')
            ->where('sg.kode_nama_kurikulum', 5)
            ->where('nilai_akhir >= sg.nilai_minimum AND nilai_akhir <= sg.nilai_maksimum');
        if ($angkatan != null && $angkatan != 0) {
            $this->db->where('substring(krs.nim,1,2)',$angkatan);
        }
        return $this->db->get()->result_object();
    }

    // ===================== KRSAN / KELAS GET MAHASISWA =====================

    public function get_kaprodi_prodi_row_kode($kode_dosen) {
        return $this->db->select('kode_program_studi')->from('kaprodi')->where('kode_dosen', $kode_dosen)->get()->row();
    }

    public function get_mahasiswa_krsan($ta, $kode_program_studi, $angkatan = false, $status_krs = false) {
        $this->db->select('count(sp.nim),mah.nim, mah.nama_mahasiswa, krs.kode_krs, pembayaran_sks, nama_dosen, kp.status_cetak')
            ->from('status_perkuliahan as sp')
            ->join('mahasiswa as mah','sp.nim=mah.nim')
            ->join('krs','krs.nim=mah.nim AND krs.kode_tahun_akademik = '.$this->db->escape($ta),'left')
            ->join('perwalian','perwalian.nim = krs.nim')
            ->join('dosen','perwalian.kode_dosen = dosen.kode_dosen')
            ->join('konsultasi_perwalian as kp',"kp.nim=krs.nim and kp.kode_tahun_akademik = ".$this->db->escape($ta),'left')
            ->where('mah.program_studi_kode', $kode_program_studi);
        if ($angkatan != 0) {
            $this->db->where('substring(sp.nim,1,2)', $angkatan);
        }
        if ($status_krs == 1) {
            $this->db->where('krs.kode_krs !=', null);
        } elseif ($status_krs == 2) {
            $this->db->where('krs.kode_krs ', null);
        }
        return $this->db->where('sp.kode_tahun_akademik', $ta)
            ->group_by('sp.nim')
            ->get()->result_object();
    }

    // ===================== MAHASISWA =====================

    public function get_prodi_nama($kode_program_studi) {
        return $this->db->select('nama_program_studi')
            ->from('program_studi')
            ->where('kode_program_studi', $kode_program_studi)
            ->get()->row_array();
    }

    public function get_jumlah_semua_mahasiswa($kode_program_studi) {
        return $this->db->select('SUBSTR(nim,1,2) as angkatan, count(SUBSTR(nim,1,2)) as jumlah')
            ->from('mahasiswa')
            ->where('program_studi_kode', $kode_program_studi)
            ->group_by('angkatan')
            ->having('angkatan in', '(select distinct(SUBSTR(nim,1,2)) from mahasiswa where program_studi_kode=' . $this->db->escape($kode_program_studi) . ')', false)
            ->order_by('angkatan', 'desc')
            ->get()->result();
    }

    public function get_jumlah_aktif_mahasiswa($kode_program_studi) {
        return $this->db->select('SUBSTR(m.nim,1,2) as angkatan, count(SUBSTR(m.nim,1,2)) as jumlah')
            ->from('status_perkuliahan as sp')
            ->join('mahasiswa as m', 'sp.nim=m.nim')
            ->where('sp.status_perkuliahan', 'A')
            ->where('m.program_studi_kode', $kode_program_studi)
            ->where('sp.kode_tahun_akademik in', '(select kode_tahun_akademik from tahun_akademik where status="A")', false)
            ->group_by('angkatan')
            ->having('angkatan in', '(select distinct(SUBSTR(nim,1,2)) from mahasiswa where program_studi_kode=' . $this->db->escape($kode_program_studi) . ')', false)
            ->order_by('angkatan', 'desc')
            ->get()->result();
    }

    public function get_jumlah_tidak_aktif_mahasiswa($kode_program_studi) {
        return $this->db->select('SUBSTR(kp.nim,1,2) as angkatan, count(SUBSTR(kp.nim,1,2)) as jumlah')
            ->from('perwalian as p')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where('kp.status_cetak', 'N')
            ->where('m.program_studi_kode', $kode_program_studi)
            ->where('kp.kode_tahun_akademik in','(select kode_tahun_akademik from tahun_akademik where status="A")', false)
            ->group_by('angkatan')
            ->having('angkatan in', '(select distinct(SUBSTR(nim,1,2)) from mahasiswa where program_studi_kode=' . $this->db->escape($kode_program_studi) . ')', false)
            ->order_by('angkatan', 'desc')
            ->get()->result();
    }

    public function get_mahasiswa_by_angkatan($kode_program_studi, $angkatan) {
        return $this->db->select('*, SUBSTR(nim,1,2) as angkatan')
            ->from('mahasiswa')
            ->where('program_studi_kode', $kode_program_studi)
            ->where('SUBSTR(nim,1,2)', $angkatan)
            ->order_by('nim', 'desc')
            ->get()->result();
    }

    public function get_jumlah_mahasiswa_angkatan($kode_program_studi) {
        return $this->db->select('SUBSTR(nim,1,2) as angkatan, count(SUBSTR(nim,1,2)) as jumlah')
            ->from('mahasiswa')
            ->where('program_studi_kode', $kode_program_studi)
            ->group_by('angkatan')
            ->having('angkatan in', '(select distinct(SUBSTR(nim,1,2)) from mahasiswa where program_studi_kode=' . $this->db->escape($kode_program_studi) . ')', false)
            ->order_by('angkatan', 'desc')
            ->get()->result();
    }

    // ===================== MBKM =====================

    public function get_mahasiswa_mbkm($ta, $kode_program_studi) {
        return $this->db->select('mahasiswa.nim,mahasiswa.nama_mahasiswa,nama_program_studi,tahun_akademik.semester,mbkm.id as id_fix')
            ->from('mbkm')
            ->join('mahasiswa','mahasiswa.nim = mbkm.nim')
            ->join('tahun_akademik','tahun_akademik.kode_tahun_akademik = mbkm.kode_ta')
            ->join('program_studi','program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('mbkm.kode_ta',$ta)
            ->where('program_studi_kode', $kode_program_studi)
            ->order_by('mbkm.id','DESC')
            ->get()->result_object();
    }

    public function search_mahasiswa_mbkm($nim, $kode_program_studi, $ta) {
        return $this->db->select('mahasiswa.*,program_studi.*,mbkm.id as id_mbkm,mbkm.kode_ta as ta_now')
            ->from('mahasiswa')
            ->join('program_studi','program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->join('mbkm','mahasiswa.nim = mbkm.nim')
            ->where('mahasiswa.nim ',$nim)
            ->where('mbkm.kode_ta',$ta)
            ->get()->row_object();
    }

    public function search_mahasiswa_by_prodi($nim, $kode_program_studi) {
        return $this->db->select('mahasiswa.*,program_studi.*')
            ->from('mahasiswa')
            ->join('program_studi','program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('program_studi_kode', $kode_program_studi)
            ->where('mahasiswa.nim ',$nim)
            ->get()->result_object();
    }

    public function cek_mbkm($nim, $ta) {
        return $this->db->select('*')->from('mbkm')->where('mbkm.nim',$nim)->where('mbkm.kode_ta',$ta)->get()->row_object();
    }

    // ===================== VALIDASI REVISI =====================

    public function get_kelas_revisi($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('kelas.kelas_id,mak.nama_matakuliah,ps.singkatan_program_studi,semester,nama_kelas,mak.kode_matakuliah')
            ->from('kelas')
            ->join('dummy_update_kelas as dum ','dum.id_kelas = kelas.kelas_id','left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where("EXISTS (
                SELECT 1 FROM kelas_mahasiswa km
                JOIN krs_detail kd ON kd.kode_krs_detail = km.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa m ON m.nim = krs.nim
                WHERE km.kelas_id = kelas.kelas_id
                AND LEFT(m.nim, 2) <= '24'
            )")
            ->order_by('ISNULL(dum.status),dum.status','asc')
            ->group_by('kelas.kelas_id')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function get_kelas_revisi_order($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('kelas.kelas_id,mak.nama_matakuliah,ps.singkatan_program_studi,semester,nama_kelas,mak.kode_matakuliah')
            ->from('kelas')
            ->join('dummy_update_kelas as dum ','dum.id_kelas = kelas.kelas_id','left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->order_by('ISNULL(dum.status),dum.status','asc')
            ->order_by('ps.singkatan_program_studi','asc')
            ->group_by('kelas.kelas_id')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function get_dummy_update_kelas_status($kelas_id, $status) {
        return $this->db->select('*')
            ->from('dummy_update_kelas as duk')
            ->where('id_kelas',$kelas_id)
            ->where('status',$status)
            ->get()->num_rows();
    }

    public function get_dummy_update_kelas_row($kelas_id, $num, $num2) {
        $this->db->select('*');
        $this->db->from('dummy_update_kelas as duk');
        $this->db->where('id_kelas',$kelas_id);
        if ($num2 > 0) {
            $this->db->where('status','2');
        }
        return $this->db->order_by("CASE WHEN $num > '0' THEN status END", "ASC")
            ->order_by("CASE WHEN $num = '0' THEN level END", "DESC")
            ->get()->row_object();
    }

    public function get_nama_dosen_by_kelas($kelas_id) {
        return $this->db->select('nama_dosen')
            ->from('mengajar')
            ->join('dosen', 'mengajar.kode_dosen=dosen.kode_dosen')
            ->where('kelas_id',$kelas_id)
            ->get()->result_object();
    }

    public function get_nilai_revisi($kelas_id) {
        return $this->db->select('grade, khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, nilai_harian, nilai_uts, nilai_uas, nilai_akhir,block.id as block_id,harian,uts,uas,na')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->where('km.kelas_id', $kelas_id)
            ->order_by('mah.nim')
            ->get()->result();
    }

    public function get_data_kelas_revisi($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function get_revisi_nilai_validasi($kelas, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,ket,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai as dun,sistem_penilaian_detail as spd')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = '.$this->db->escape($kode_ta),'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dun.kelas_id',$kelas)
            ->where('level',$level)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function get_revisi_nilai_kelas($kelas, $level) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas',$kelas)
            ->where('level',$level)
            ->get()->row_object();
    }

    public function get_mahasiswa_kelas_only($kelas) {
        return $this->db->select('mah.nim, nama_mahasiswa')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->where('km.kelas_id', $kelas)
            ->get()->result();
    }

    public function get_dummy_update_kelas_all($kelas) {
        return $this->db->select('*')->from('dummy_update_kelas')->where('id_kelas',$kelas)->where('status_prodi','T')->get()->result_object();
    }

    public function get_nilai_revisi_all($kelas, $level, $kode_ta = null) {
        $this->db->select('mah.nim,mah.nama_mahasiswa,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai as dun,sistem_penilaian_detail as spd')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim','left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dun.kelas_id',$kelas)
            ->where('level',$level)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->group_by('mah.nim');
        if ($kode_ta !== null) {
            $this->db->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = '.$this->db->escape($kode_ta),'left');
        }
        return $this->db->get()->result_object();
    }

    // ===================== KPAT REVISI =====================

    public function get_kelas_revisi_kpat($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('kelas_kpat.kelas_id,mak.nama_matakuliah,ps.singkatan_program_studi,semester,nama_kelas,mak.kode_matakuliah')
            ->from('kelas_kpat')
            ->join('dummy_update_kelas_kpat as dum ','dum.id_kelas = kelas_kpat.kelas_id','left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas_kpat.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas_kpat.kode_program_studi')
            ->order_by('ISNULL(dum.status),dum.status','asc')
            ->order_by('ps.singkatan_program_studi','asc')
            ->group_by('kelas_kpat.kelas_id')
            ->where('kelas_kpat.kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function get_dummy_update_kelas_kpat_status($kelas_id, $status) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat as duk')
            ->where('id_kelas',$kelas_id)
            ->where('status',$status)
            ->get()->num_rows();
    }

    public function get_dummy_update_kelas_kpat_row($kelas_id, $num, $num2) {
        $this->db->select('*');
        $this->db->from('dummy_update_kelas_kpat as duk');
        $this->db->where('id_kelas',$kelas_id);
        if ($num2 > 0) {
            $this->db->where('status','2');
        }
        return $this->db->order_by("CASE WHEN $num > '0' THEN status END", "ASC")
            ->order_by("CASE WHEN $num = '0' THEN level END", "DESC")
            ->get()->row_object();
    }

    public function get_nama_dosen_by_kelas_kpat($kelas_id) {
        return $this->db->select('nama_dosen')
            ->from('mengajar_kpat')
            ->join('dosen', 'mengajar_kpat.kode_dosen=dosen.kode_dosen')
            ->where('kelas_id',$kelas_id)
            ->get()->result_object();
    }

    public function get_revisi_nilai_validasi_kpat($kelas, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,ket,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai_kpat as dun,sistem_penilaian_detail as spd')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = '.$this->db->escape($kode_ta),'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dun.kelas_id',$kelas)
            ->where('level',$level)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function get_revisi_nilai_kelas_kpat($kelas, $level) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas',$kelas)
            ->where('level',$level)
            ->get()->row_object();
    }

    public function get_mahasiswa_kelas_only_kpat($kelas) {
        return $this->db->select('mah.nim, nama_mahasiswa')
            ->from('kelas_mahasiswa_kpat as km')
            ->join('kelas_kpat', 'kelas_kpat.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->where('km.kelas_id', $kelas)
            ->get()->result();
    }

    public function get_dummy_update_kelas_kpat_all($kelas, $status = 'T') {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas',$kelas)
            ->where('status_prodi',$status)
            ->get()->result_object();
    }

    public function get_nilai_revisi_kpat_all($kelas, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,ket,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai_kpat as dun,sistem_penilaian_detail as spd')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = '.$this->db->escape($kode_ta),'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dun.kelas_id',$kelas)
            ->where('level',$level)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function get_nilai_revisi_kpat_all_simple($kelas, $level) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,ket,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai_kpat as dun,sistem_penilaian_detail as spd')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim','left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dun.kelas_id',$kelas)
            ->where('level',$level)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    // ===================== UPDATE PENILAIAN =====================

    public function get_kelas_update_uts($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('semester, ps.nama_program_studi, ps.singkatan_program_studi, kelas.status_nilai_uts, kelas.validasi_nilai_uts, kelas.validasi_dekan_uts, nama_kelas, nama_matakuliah, kelas.kelas_id, mengajar_id, GROUP_CONCAT(DISTINCT nama_dosen SEPARATOR ",") as nama_dosen, mak.kode_matakuliah, status_uts_dosen,status_uts_prodi,status_uts_dekan')
            ->from('kelas')
            ->join('kelas_validasi as kv', 'kv.kelas_id = kelas.kelas_id', 'left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kv.updated_at','desc')
            ->get()->result();
    }

    public function get_kelas_update_uts_index($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.nama_program_studi, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('')
            ->get()->result();
    }

    public function get_mahasiswa_update_uts($kelas_id) {
        return $this->db->select('khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, uts')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('km.kelas_id', $kelas_id)
            ->get()->result();
    }

    public function get_data_kelas_update($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas=kelas.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function update_dummy_update_kelas($where, $data) {
        return $this->db->where($where)->update('dummy_update_kelas', $data);
    }

    public function insert_catatan_revisi_uas($data) {
        return $this->db->insert('catatan_revisi_uas', $data);
    }

    public function insert_catatan_revisi($data) {
        return $this->db->insert('catatan_revisi', $data);
    }

    public function insert_catatan_revisi_kpat($data) {
        return $this->db->insert('catatan_revisi_kpat', $data);
    }

    public function insert_kelas_validasi($data) {
        return $this->db->insert('kelas_validasi', $data);
    }

    public function update_kelas($where, $data) {
        return $this->db->where($where)->update('kelas', $data);
    }

    // ===================== IPK =====================

    public function get_mahasiswa_ipk($nim) {
        return $this->db->select('nim,nama_mahasiswa')->from('mahasiswa')->where('nim', $nim)->get()->row_object();
    }

    public function get_krs_ipk($nim) {
        return $this->db->select('*')
            ->from('krs')
            ->where('nim', $nim)
            ->where_not_in('semester', 'K')
            ->group_by('kode_tahun_akademik')
            ->get()->result();
    }

}
