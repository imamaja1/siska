<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DekanService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    // ===================== GENERIC HELPERS =====================

    public function get_result($select, $from, $joins = [], $where = [], $group_by = null, $order_by = null) {
        $this->db->select($select)->from($from);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1], isset($j[2]) ? $j[2] : '');
        }
        if (!empty($where)) $this->db->where($where);
        if ($group_by) $this->db->group_by($group_by);
        if ($order_by) $this->db->order_by($order_by);
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

    // ===================== DEKAN-SPECIFIC =====================

    public function get_dekan_prodi($kode_dosen) {
        return $this->db->select('kode_program_studi')
            ->from('fakultas')->where('dekan', $kode_dosen)
            ->join('program_studi', 'fakultas.kode_fakultas=program_studi.kode_fakultas')
            ->get()->result_array();
    }

    public function get_kelas_validasi($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.nama_program_studi, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where("EXISTS (
                SELECT 1 FROM kelas_mahasiswa km
                JOIN krs_detail kd ON kd.kode_krs_detail = km.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa m ON m.nim = krs.nim
                WHERE km.kelas_id = kelas.kelas_id
                AND LEFT(m.nim, 2) <= '24'
            )")
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->get()->result();
    }

    public function get_kelas_validasi_simple($kode_prodi, $kode_tahun_akademik) {
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

    public function get_kelas_uts_dekan($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan,status_nilai_uts, validasi_nilai_uts, validasi_dekan_uts, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah,param_uts')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'ASC')
            ->get()->result();
    }

    public function get_kelas_uas_dekan($kode_prodi, $kode_tahun_akademik) {
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

    public function get_kelas_uas_dekan_simple($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan,status_nilai_uts, validasi_nilai_uts, validasi_dekan_uts, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'ASC')
            ->get()->result();
    }

    public function get_mahasiswa_uts_dekan($kelas_id) {
        return $this->db->select('dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('km.kelas_id', $kelas_id)
            ->get()->result();
    }

    public function get_mahasiswa_uas_dekan($kelas_id) {
        return $this->db->select('grade, dummy_id, khd.kode_khs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
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
            ->order_by('dummy_na','asc')
            ->get()->result();
    }

    public function get_data_kelas_dekan($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function get_catatan_revisi_dekan_count($kelas_id) {
        return $this->db->select('*')->from('catatan_revisi')
            ->where('kelas_id',$kelas_id)
            ->where('kode_dosen', 1)
            ->where('kode_dekan', 1)
            ->where('param_dosen', 1)
            ->where('param_dekan', null)
            ->get()->result();
    }

    public function get_query_dosen_kelas_dekan($kelas_id) {
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

    public function get_query_prodi_dekan($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=kp.kode_dosen')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function get_query_fakultas_dekan($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function get_dummy_nilai_kelas($kelas_id) {
        return $this->db->select('dn.*')
            ->from('kelas_mahasiswa as km')
            ->join('dummy_nilai as dn', 'dn.kode_krs_detail=km.kode_krs_detail')
            ->where('kelas_id', $kelas_id)
            ->get()->result();
    }

    public function update_kelas($where, $data) {
        return $this->db->where($where)->update('kelas', $data);
    }

    public function insert_kelas_validasi($data) {
        return $this->db->insert('kelas_validasi', $data);
    }

    public function insert_catatan_revisi($data) {
        return $this->db->insert('catatan_revisi', $data);
    }

    public function insert_catatan_revisi_uas($data) {
        return $this->db->insert('catatan_revisi_uas', $data);
    }

    public function insert_catatan_revisi_kpat($data) {
        return $this->db->insert('catatan_revisi_kpat', $data);
    }

    // ===================== REVISI =====================

    public function get_kelas_revisi_dekan($kode_prodi, $kode_tahun_akademik) {
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
            ->order_by('ps.singkatan_program_studi','asc')
            ->group_by('kelas.kelas_id')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function get_kelas_revisi_dekan_choose($kode_prodi, $kode_tahun_akademik) {
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
            ->order_by('dum.status','desc')
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

    public function get_nama_dosen_kelas($kelas_id) {
        return $this->db->select('nama_dosen')
            ->from('mengajar')
            ->join('dosen', 'mengajar.kode_dosen=dosen.kode_dosen')
            ->where('kelas_id',$kelas_id)
            ->get()->result_object();
    }

    public function get_nilai_revisi_dekan($id) {
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
            ->where('km.kelas_id', $id)
            ->order_by('mah.nim')
            ->get()->result();
    }

    public function get_data_kelas_revisi_dekan($id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $id)
            ->get()->row_object();
    }

    public function get_revisi_nilai_validasi_dekan($kelas, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id,ket')
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

    public function get_revisi_nilai_kelas_dekan($kelas, $level) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas',$kelas)
            ->where('level',$level)
            ->get()->row_object();
    }

    public function get_revisi_nilai_divalidasi_dekan($kelas) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas',$kelas)
            ->where('status_dekan','T')
            ->get()->result_object();
    }

    public function get_isi_nilai_divalidasi_dekan($kelas, $level, $kode_ta) {
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

    public function get_mahasiswa_kelas_only_dekan($kelas) {
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

    public function get_nilai_revisi_all_dekan($kelas, $level) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
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
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function update_dummy_update_kelas($where, $data) {
        return $this->db->where($where)->update('dummy_update_kelas', $data);
    }

    public function get_dummy_update_nilai($kelas, $level) {
        return $this->db->from('dummy_update_nilai')->where('kelas_id', $kelas)->where('level', $level)->get()->result_object();
    }

    public function get_khs_detail_row($kode_khs_detail) {
        return $this->db->from('khs_detail')->where('kode_khs_detail', $kode_khs_detail)->get()->row_object();
    }

    public function update_khs_detail($kode_khs_detail, $data) {
        return $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', $data);
    }

    // ===================== KPAT DEKAN =====================

    public function get_kelas_revisi_kpat_dekan($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('kelas_kpat.kelas_id,mak.nama_matakuliah,ps.singkatan_program_studi,semester,nama_kelas,mak.kode_matakuliah')
            ->from('kelas_kpat')
            ->join('dummy_update_kelas_kpat as dum ','dum.id_kelas = kelas_kpat.kelas_id','left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas_kpat.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas_kpat.kode_program_studi')
            ->order_by('dum.status','desc')
            ->order_by('ps.singkatan_program_studi','asc')
            ->group_by('kelas_kpat.kelas_id')
            ->where_in('kelas_kpat.kode_program_studi', $kode_prodi)
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

    public function get_nama_dosen_kelas_kpat($kelas_id) {
        return $this->db->select('nama_dosen')
            ->from('mengajar_kpat')
            ->join('dosen', 'mengajar_kpat.kode_dosen=dosen.kode_dosen')
            ->where('kelas_id',$kelas_id)
            ->get()->result_object();
    }

    public function get_revisi_nilai_validasi_kpat_dekan($kelas, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id,ket')
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

    public function get_revisi_nilai_kelas_kpat_dekan($kelas, $level) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas',$kelas)
            ->where('level',$level)
            ->get()->row_object();
    }

    public function get_revisi_nilai_divalidasi_kpat_dekan($kelas) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas',$kelas)
            ->where('status_dekan','T')
            ->get()->result_object();
    }

    public function get_isi_nilai_divalidasi_kpat_dekan($kelas, $level, $kode_ta) {
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

    public function get_mahasiswa_kelas_only_kpat_dekan($kelas) {
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

    public function get_dummy_update_kelas_kpat_all($kelas) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas',$kelas)
            ->where('status_dekan','T')
            ->get()->result_object();
    }

    public function get_nilai_revisi_all_kpat_dekan($kelas, $level) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
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

    public function get_dummy_update_kelas_kpat_all_status($kelas) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas',$kelas)
            ->where('status_prodi','T')
            ->get()->result_object();
    }

    public function get_nilai_revisi_all_kpat_dekan_with_ta($kelas, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
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

    public function update_dummy_update_kelas_kpat($where, $data) {
        return $this->db->where($where)->update('dummy_update_kelas_kpat', $data);
    }

    public function get_dummy_update_nilai_kpat($kelas, $level) {
        return $this->db->from('dummy_update_nilai_kpat')->where('kelas_id', $kelas)->where('level', $level)->get()->result_object();
    }

    // ===================== UPDATE PENILAIAN DEKAN =====================

    public function get_kelas_update_uts_dekan($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan,status_nilai_uts, validasi_nilai_uts, validasi_dekan_uts, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah,status_uts_dosen,status_uts_prodi,status_uts_dekan')
            ->from('kelas')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'ASC')
            ->get()->result();
    }

    public function get_kelas_update_uas_dekan($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('max(kv.updated_at) as updated_at, semester, ps.nama_program_studi, ps.singkatan_program_studi, kelas.status_nilai, kelas.validasi_nilai, kelas.validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(DISTINCT nama_dosen SEPARATOR ",") as nama_dosen, mak.kode_matakuliah,status_uts_dosen,status_uts_prodi,status_uts_dekan')
            ->from('kelas')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
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

    public function get_mahasiswa_update_uts_dekan($kelas_id) {
        return $this->db->select('*')
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

    public function get_kelas_update_detail_dekan($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('dummy_update_kelas as duk','duk.id_kelas = kelas.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function get_dummy_nilai_update_uts($kelas_id) {
        return $this->db->select('dn.*')
            ->from('kelas_mahasiswa as km')
            ->join('khs_detail as kd','km.kode_krs_detail = kd.kode_krs_detail')
            ->join('dummy_update_nilai as dn', 'dn.kode_khs_detail=kd.kode_khs_detail')
            ->where('kelas_id', $kelas_id)
            ->get()->result();
    }

    public function get_dummy_nilai_uas($kelas_id) {
        return $this->db->select('dn.*')
            ->from('kelas_mahasiswa as km')
            ->join('dummy_nilai as dn', 'dn.kode_krs_detail=km.kode_krs_detail')
            ->where('kelas_id', $kelas_id)
            ->get()->result();
    }

}
