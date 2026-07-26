<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ValidasiService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_fakultas() {
        return $this->db->select("*")->from('fakultas')->get()->result();
    }

    public function get_fakultas_by_kode($kode) {
        return $this->db->select("*")->from('fakultas')->where('kode_fakultas', $kode)->get()->row_array();
    }

    public function get_kelas_by_prodi($kode_prodi, $ta_status = 'A') {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kelas.kode_tahun_akademik')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('ta.status', $ta_status)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function get_kelas_by_prodi_and_ta($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function get_kelas_uts_by_prodi($kode_prodi, $ta_status = 'A') {
        return $this->db->select('param_uts,kelas.valid_uts, cek_uts, ps.singkatan_program_studi,status_nilai_uts, validasi_nilai_uts, validasi_dekan_uts, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kelas.kode_tahun_akademik')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('ta.status', $ta_status)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function get_kelas_uts_by_prodi_and_ta($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('param_uts, kelas.valid_uts, cek_uts,ps.singkatan_program_studi,status_nilai_uts, validasi_nilai_uts, validasi_dekan_uts, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function get_kelas_uas_by_prodi($kode_prodi, $kode_ta = '25') {
        return $this->db->select('param_uts, cek_uas, ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kelas.kode_tahun_akademik')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('ta.kode_tahun_akademik', $kode_ta)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function get_kelas_uas_by_prodi_and_ta($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('param_uts, cek_uas, ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result_object();
    }

    public function get_kelas_validasi_by_prodi($kode_prodi) {
        return $this->db->select('param_uts, cek_uas, ps.singkatan_program_studi, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kelas.kode_tahun_akademik')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('ta.status', 'A')
            ->where("EXISTS (
                SELECT 1 FROM kelas_mahasiswa km
                JOIN krs_detail kd ON kd.kode_krs_detail = km.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa m ON m.nim = krs.nim
                WHERE km.kelas_id = kelas.kelas_id
                AND LEFT(m.nim, 2) <= '24'
            )", null, false)
            ->group_by('kelas.kelas_id')
            ->order_by('duk.id_kelas', 'DESC')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function get_kelas_validasi_by_prodi_and_ta($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('param_uts, cek_uas, ps.singkatan_program_studi, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kelas.kode_tahun_akademik')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where("EXISTS (
                SELECT 1 FROM kelas_mahasiswa km
                JOIN krs_detail kd ON kd.kode_krs_detail = km.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa m ON m.nim = krs.nim
                WHERE km.kelas_id = kelas.kelas_id
                AND LEFT(m.nim, 2) <= '24'
            )", null, false)
            ->group_by('kelas.kelas_id')
            ->order_by('duk.id_kelas', 'DESC')
            ->order_by('kelas.datecreate', 'DESC')
            ->get()->result();
    }

    public function count_mahasiswa_in_kelas($kelas_id) {
        return $this->db->select('*')->from('kelas')
            ->join('kelas_mahasiswa as km', 'km.kelas_id = kelas.kelas_id')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->num_rows();
    }

    public function get_kelas_row_by_id($id) {
        return $this->db->select('*')
            ->from('kelas')
            ->where('kelas_id', $id)
            ->get()->row();
    }

    public function update_kelas($id, $data) {
        return $this->db->where('kelas_id', $id)->update('kelas', $data);
    }

    public function get_mahasiswa_by_kelas_id($id) {
        return $this->db->select('mhs.nim, nama_mahasiswa, krs.semester, mhs.status')
            ->from('kelas')
            ->join('kelas_mahasiswa as km', 'km.kelas_id = kelas.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail = km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs = kd.kode_krs')
            ->join('mahasiswa as mhs', 'mhs.nim = krs.nim')
            ->where('kelas.kelas_id', $id)
            ->get()->result_object();
    }

    public function get_dummy_update_kelas_by_id_kelas($id_kelas) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas', $id_kelas)
            ->get()->result_object();
    }

    public function get_nilai_revisi_by_kelas_and_level($kelas_id, $level, $kode_ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,
            grade,ket,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai as dun')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = '.$this->db->escape($kode_ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('sistem_penilaian_detail as spd', 'dun.na BETWEEN spd.nilai_minimum AND spd.nilai_maksimum AND spd.kode_sistem_penilaian = 1', 'left')
            ->where('dun.kelas_id', $kelas_id)
            ->where('level', $level)
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function get_cetak_kelas_info($id) {
        return $this->db->select('*,kl.semester as kls, ta.semester as tas, mt.kode_matakuliah as mtkm')
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
            ->where('kl.kelas_id=', $id)
            ->get()->row();
    }

    public function get_cetak_nilai_revisi($id, $level, $ta) {
        return $this->db->select('mah.nim, nama_mahasiswa, ket, harian, uts, uas, na, grade, block.id as block_id, mbkm.id as mbkm_id')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = '.$this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dun', 'dun.kode_khs_detail=khd.kode_khs_detail')
            ->join('sistem_penilaian_detail as spd', 'dun.na BETWEEN spd.nilai_minimum AND spd.nilai_maksimum AND spd.kode_sistem_penilaian = 1', 'left')
            ->where('kelas.kelas_id', $id)
            ->where('dun.level', $level)
            ->where('block.nim', null)
            ->group_by('nim')
            ->order_by('substr(mah.nim,1,2) asc')
            ->order_by('substr(mah.nim,6,1) asc')
            ->order_by('substr(mah.nim,-4,4) asc')
            ->get()->result();
    }

    public function get_dekan_info_by_kelas($id) {
        return $this->db->select('ds.nama_dosen as dosen_fakultas, ds.nik as nik_dosen_fakultas')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $id)
            ->get()->row();
    }

    public function get_all_sistem_penilaian_detail() {
        return $this->db->select('*')
            ->from('sistem_penilaian_detail')
            ->where('kode_sistem_penilaian=', 1)
            ->order_by('bobot_nilai', 'desc')
            ->get()->result();
    }

    public function get_nama_dosen_by_kelas($id) {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen SEPARATOR "/") as nama_dosen, GROUP_CONCAT(nik SEPARATOR "/") as nik, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where('kelas.kelas_id', $id)
            ->get()->row();
    }

    public function get_persentasi_nilai_dosen_by_kelas($id) {
        return $this->db->from('persentasi_nilai_dosen')
            ->where('kelas_id', $id)
            ->get()->row_object();
    }
}
