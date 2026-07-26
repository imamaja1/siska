<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Khs_model extends CI_Model {

    private $table = "khs";

    public function __construct() {
        parent::__construct();
    }

    public function filter($angkatan, $kode_program_studi, $semester, $limit, $offset) {

            $query = $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
                ->from('krs')
                ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->where('krs.semester', $semester)
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('substring(krs.nim,1,2)', $angkatan)
                ->where_not_in('kd.status','K')
                ->order_by('krs.nim ASC')
                ->group_by('krs.kode_krs')
                ->limit($limit, $offset)
                ->get()->result_object();

        return $query;
    }

    public function count_data_filter($angkatan, $kode_program_studi, $semester) {

            $query = $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
                ->from('krs')
                ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->where('krs.semester', $semester)
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('substring(krs.nim,1,2)', $angkatan)
                ->where_not_in('kd.status','K')
                ->order_by('krs.nim ASC')
                ->group_by('krs.kode_krs')
                ->get()->result_object();

        return $query;
    }

    public function khs($kode_krs) {
        $krs_detail = $this->db->select('krs.nim, mah.nama_mahasiswa,krs.kode_krs, krd.kode_krs_detail,mk.kode_matakuliah,nilai_harian, nilai_uts, nilai_uas,nilai_akhir,nama_matakuliah, (sks_teori+sks_praktek+sks_praktikum) as sks, kode_tahun_akademik, krs.semester, tidak_berhak')
            ->from('krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('krs_detail as krd', 'krd.kode_krs=krs.kode_krs')
            ->join('khs_detail as khd', 'khd.kode_krs_detail=krd.kode_krs_detail','right')
            ->join('matakuliah as mk', 'mk.id_matakuliah=krd.id_matakuliah')
            ->where('krs.kode_krs', $kode_krs)
            //->where_not_in('mk.kode_matakuliah', array('MDKB240123','MDBB340015','TSBB360039','TSBB370068','TDBB340127','TDBB350020','DSPB470401','TSKB670084','MDKB460139','MDPB650016','TSKB670052', 'TSKB670084', 'TSKB670054', 'TDKB650134', 'TDPB650021','DSKB680522','GSKB470049', 'TSBB260102', 'ITBB260102'))
            ->group_by('mk.kode_matakuliah')
            ->order_by('substr(mk.kode_matakuliah,6,1) ASC')
            ->order_by('substr(mk.kode_matakuliah,-3,3) ASC')
            ->get()->result();

        return $krs_detail;
    }

    public function kurikulum_penilaian($angkatan, $kode_program_studi) {
        $penilaian = $this->db->query("SELECT * FROM (SELECT distinct kode_sistem_penilaian_detail, mid(angkatan1,-2) as angkatan, nama_kurikulum.kode_nama_kurikulum, nilai_minimum, nilai_maksimum, grade, bobot_nilai, kategori, keterangan, nama_kurikulum, kode_program_studi FROM nama_kurikulum, kurikulum, sistem_penilaian, sistem_penilaian_detail WHERE nama_kurikulum.kode_nama_kurikulum=kurikulum.kode_nama_kurikulum and nama_kurikulum.kode_nama_kurikulum=sistem_penilaian.kode_nama_kurikulum and sistem_penilaian.kode_sistem_penilaian=sistem_penilaian_detail.kode_sistem_penilaian) as mhs WHERE angkatan=? and kode_program_studi=?", array($angkatan, $kode_program_studi))->result_array();

        return $penilaian;
    }

    public function simpan_khs($data) {
        return $this->db->insert($this->table, $data);
    }
  	public function khs_kpat_aktif($kode_krs) {
        $krs_detail = $this->db->select('krs.nim, mah.nama_mahasiswa,krs.kode_krs, krd.kode_krs_detail,mk.kode_matakuliah,nilai_harian, nilai_uts, nilai_uas,nilai_akhir,nama_matakuliah, (sks_teori+sks_praktek+sks_praktikum) as sks, kode_tahun_akademik, krs.semester, tidak_berhak')
            ->from('krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('krs_detail as krd', 'krd.kode_krs=krs.kode_krs')
            ->join('khs_detail as khd', 'khd.kode_krs_detail=krd.kode_krs_detail','right')
            ->join('matakuliah as mk', 'mk.id_matakuliah=krd.id_matakuliah')
            ->join('mk_mbkm as mm','krd.kode_krs_detail = mm.kode_krs_detail','left')
            ->where('mm.status','1')
            ->where('krs.kode_krs', $kode_krs)
            //  ->where_not_in('mk.kode_matakuliah', array('MDKB240123','MDBB340015','TSBB360039','TSBB370068','TDBB340127','TDBB350020','DSPB470401','TSKB670084','MDKB460139','MDPB650016','TSKB670052', 'TSKB670084', 'TSKB670054', 'TDKB650134', 'TDPB650021','DSKB680522','GSKB470049', 'TSBB260102', 'ITBB260102'))
            ->group_by('mk.kode_matakuliah')
            ->order_by('substr(mk.kode_matakuliah,6,1) ASC')
            ->order_by('substr(mk.kode_matakuliah,-3,3) ASC')
            ->get()->result();

        return $krs_detail;
    }
    public function khs_kpat_non_aktif($kode_krs) {
        $krs_detail = $this->db->select('mm.status,krs.nim, mah.nama_mahasiswa,krs.kode_krs, krd.kode_krs_detail,mk.kode_matakuliah,nilai_harian, nilai_uts, nilai_uas,nilai_akhir,nama_matakuliah, (sks_teori+sks_praktek+sks_praktikum) as sks, kode_tahun_akademik, krs.semester, tidak_berhak')
            ->from('krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('krs_detail as krd', 'krd.kode_krs=krs.kode_krs')
            ->join('khs_detail as khd', 'khd.kode_krs_detail=krd.kode_krs_detail','right')
            ->join('matakuliah as mk', 'mk.id_matakuliah=krd.id_matakuliah')
            ->join('mk_mbkm as mm','krd.kode_krs_detail = mm.kode_krs_detail','left')
            ->where('krs.kode_krs', $kode_krs)->where('mm.status ', null)
            ->or_where('krs.kode_krs', $kode_krs)->where('mm.status ', '0')
            //  ->where_not_in('mk.kode_matakuliah', array('MDKB240123','MDBB340015','TSBB360039','TSBB370068','TDBB340127','TDBB350020','DSPB470401','TSKB670084','MDKB460139','MDPB650016','TSKB670052', 'TSKB670084', 'TSKB670054', 'TDKB650134', 'TDPB650021','DSKB680522','GSKB470049', 'TSBB260102', 'ITBB260102'))
            ->group_by('mk.kode_matakuliah')
            ->order_by('substr(mk.kode_matakuliah,6,1) ASC')
            ->order_by('substr(mk.kode_matakuliah,-3,3) ASC')
            ->get()->result();
        // echo json_encode($krs_detail);die();
        return $krs_detail;
    }

}
