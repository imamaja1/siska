<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VerifikasiService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function validasiPenilaianDekan($kelas) {
        return $this->db->from('krs')
            ->join('krs_detail','krs_detail.kode_krs = krs.kode_krs')
            ->join('khs_detail','khs_detail.kode_krs_detail = krs_detail.kode_krs_detail')
            ->join('matakuliah','matakuliah.id_matakuliah = krs_detail.id_matakuliah')
            ->where('kode_tahun_akademik',28)
            ->limit(10)
            ->get()->result_object();
    }

    public function getKurikulumByNim($kode_nama_kurikulum, $semester) {
        return $this->db->select('*,mk.id_matakuliah as kompetensi')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->join('matakuliah_kompetensi as mk', 'mk.id_matakuliah=mak.id_matakuliah','left')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('semester', $semester)
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->group_by('mak.id_matakuliah')
            ->get()->result();
    }

    public function getStatusPerkuliahanByTa($kode_tahun_akademik, $status) {
        return $this->db->from('status_perkuliahan')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('status_perkuliahan', $status)
            ->get()->result_object();
    }

    public function getKrsByNimTa($nim, $kode_tahun_akademik) {
        return $this->db->from('krs')
            ->where('nim', $nim)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->num_rows();
    }

    public function getKrsKpatPindah($kode_tahun_akademik_lama, $status) {
        return $this->db->from('krs')
            ->join('krs_detail','krs_detail.kode_krs = krs.kode_krs')
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik_lama)
            ->where('krs_detail.status', $status)
            ->get()->result_object();
    }

    public function updateKrsSemester($kode_krs, $semester) {
        $row = $this->db->where('kode_krs', $kode_krs)->get('krs')->row();
        $this->db->where('kode_krs', $kode_krs)->update('krs', array('semester' => $semester));
        $lama = $row && isset($row->semester) ? $row->semester : null;
        if ($lama != $semester) {
            log_aktivitas_nilai('update', 'semester', array('semester' => $lama), array('semester' => $semester), 'verifikasi', null, null, $kode_krs);
        }
    }

    public function getRekapMatakuliahQuery($kode_program_studi, $kode_tahun_akademik) {
        return $this->db->select('krs.kode_tahun_akademik as kode_akademik, sp.kode_tahun_akademik as kode_mhs_aktif,matakuliah.id_matakuliah,matakuliah.kode_matakuliah, matakuliah.sks_praktikum, matakuliah.nama_matakuliah, count(DISTINCT(krs.nim)) as jum')
            ->from('krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('status_perkuliahan as sp', 'sp.nim=krs.nim')
            ->join('krs_detail', 'krs_detail.kode_krs=krs.kode_krs')
            ->join('matakuliah', 'krs_detail.id_matakuliah=matakuliah.id_matakuliah')
            ->where(array('mah.program_studi_kode' => $kode_program_studi, 'krs.kode_tahun_akademik' => $kode_tahun_akademik))
            ->where_in('krs_detail.status', array('B', 'U'))
            ->where_not_in('krs.semester', 'K')
            ->group_by('matakuliah.id_matakuliah')
            ->get_compiled_select();
    }

    public function getRekapMatakuliahFinal($query1) {
        return $this->db->query("SELECT *, SUM(mah.jum) as jml FROM (" . $query1 . ") as mah GROUP BY mah.id_matakuliah")->result();
    }

    public function getGradeData($kelas_id, $sistem_penilaian_id) {
        return $this->db->select('grade, nilai_akhir, count(kd.kode_krs_detail) as jumlah')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail', 'left')
            ->join('khs_detail as khd', 'khd.kode_krs_detail=kd.kode_krs_detail', 'left')
            ->where('kelas_id', $kelas_id)
            ->where('spd.kode_sistem_penilaian', $sistem_penilaian_id)
            ->where('spd.nilai_minimum <= khd.nilai_akhir')
            ->where('spd.nilai_maksimum >= khd.nilai_akhir')
            ->group_by('grade')
            ->get()->result_object();
    }

    public function getGradeTotal($kelas_id, $sistem_penilaian_id) {
        return $this->db->select('*')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail', 'left')
            ->join('khs_detail as khd', 'khd.kode_krs_detail=kd.kode_krs_detail', 'left')
            ->where('kelas_id', $kelas_id)
            ->where('spd.kode_sistem_penilaian', $sistem_penilaian_id)
            ->where('spd.nilai_minimum <= khd.nilai_akhir')
            ->where('spd.nilai_maksimum >= khd.nilai_akhir')
            ->group_by('kd.kode_krs_detail')
            ->get()->num_rows();
    }

    public function getAktivasi($kode_tahun_akademik) {
        return $this->db->select('*')->from('aktivasi')->where('kode_tahun_akademik', $kode_tahun_akademik)->get()->row();
    }

    public function getDataMahasiswaUts($kelas_id) {
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

    public function getMhsDownload() {
        return $this->db->select("mahasiswa.nim,ps.nama_program_studi,nama_mahasiswa")
            ->from('mahasiswa')
            ->join('program_studi as ps','mahasiswa.program_studi_kode = ps.kode_program_studi')
            ->where('mahasiswa.nim >', '2400000000')
            ->get()->result();
    }

    public function getMhsNotKrs() {
        return $this->db->select("mahasiswa.nim,mahasiswa.telepon, ps.nama_program_studi,nama_mahasiswa")
            ->from('mahasiswa')
            ->join('program_studi as ps','mahasiswa.program_studi_kode = ps.kode_program_studi')
            ->join('krs', 'krs.nim=mahasiswa.nim', 'left')
            ->where('mahasiswa.nim >', '2300000000')
            ->where('kode_krs', null)
            ->get()->result();
    }

    public function getKuisonerDosen($kode_tahun_akademik, $kode_program_studi) {
        return $this->db->select('ds.nama_dosen, mk.kode_matakuliah, mk.nama_matakuliah, nk.nama_kelas')
            ->from('kelas')
            ->join('nama_kelas as nk','nk.nama_kelas_id = kelas.nama_kelas_id')
            ->join('mengajar as mr','mr.kelas_id = kelas.kelas_id')
            ->join('dosen as ds','ds.kode_dosen = mr.kode_dosen')
            ->join('matakuliah as mk','kelas.id_matakuliah = mk.id_matakuliah')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kelas.kode_program_studi', $kode_program_studi)
            ->get()->result_object();
    }

    public function getMhsKrs($nim) {
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','kd.kode_krs = krs.kode_krs')
            ->where('krs.nim', $nim)
            ->get()
            ->result_object();
    }

    public function nonAktifMhs($nim) {
        $this->db->where('nim', $nim)->update('mahasiswa', array('status' => 'N'));
    }

    public function getMhsByNim($nim) {
        return $this->db->select('nim, nama_mahasiswa, status')->from('mahasiswa')->where('nim', $nim)->get()->result_object();
    }

    public function getSkripsi() {
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','kd.kode_krs = krs.kode_krs')
            ->join('khs_detail as ks','ks.kode_krs_detail = kd.kode_krs_detail')
            ->join('matakuliah as mk','mk.id_matakuliah = kd.id_matakuliah')
            ->where('nama_matakuliah','skripsi')
            ->where('nilai_akhir >', 0)
            ->limit('1')
            ->get()
            ->result_object();
    }

    public function getSemester($nim) {
        return $this->db->select('*')
            ->from('krs')
            ->where('nim', $nim)
            ->order_by('kode_krs','desc')
            ->limit('1')
            ->get()
            ->row();
    }

    public function getNilaiDup($nim) {
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
            ->where('nim', $nim)
            ->order_by('khd.nilai_akhir', 'DESC')
            ->get()->result_object();
    }

    public function getPembayaran($kode_tahun_akademik, $kkp_skripsi) {
        return $this->db->select('pengumpulan_krs,kode_status_perkuliahan,pembayaran_spp, pembayaran_sks, pembayaran_lab, krs.semester,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek)) as teori, sum(sks_praktikum) as praktikum')
            ->from('krs')
            ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
            ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->where_not_in('kd.status', ['K'])
            ->where_not_in('mak.kode_matakuliah', $kkp_skripsi)
            ->group_by('mah.nim')
            ->get()->result();
    }

    public function getJumlahSks($tahun, $nim) {
        return $this->db->select('SUM(sks_teori) as T,SUM(sks_praktek) as P,SUM(sks_praktikum) as PS')
            ->from('krs')
            ->join('krs_detail as kd','kd.kode_krs = krs.kode_krs')
            ->join('matakuliah as mt', 'mt.id_matakuliah = kd.id_matakuliah')
            ->where('nim', $nim)
            ->where('kode_tahun_akademik <', $tahun)
            ->get();
    }

    public function getSks($nim) {
        return $this->db->select('nama_matakuliah,sks_teori,sks_praktek,sks_praktikum')
            ->from('krs')
            ->join('krs_detail as kd','kd.kode_krs = krs.kode_krs')
            ->join('matakuliah as mt', 'mt.id_matakuliah = kd.id_matakuliah')
            ->where('semester !=', '6')
            ->where('nim', $nim)
            ->order_by('nama_matakuliah')
            ->get();
    }

    public function getMhsLulusTepatWaktu() {
        return $this->db->select('mhs.nim, mhs.nama_mahasiswa, mhs.status, MAX(krs.semester) as semester')
            ->from('mahasiswa as mhs')
            ->join('krs','krs.nim = mhs.nim')
            ->group_by('mhs.nim', 'desc')
            ->where('status', 'N')
            ->where('semester', 8)
            ->get();
    }

    public function getMhsLulusTidakTepatWaktu() {
        return $this->db->select('mhs.nim, mhs.nama_mahasiswa, mhs.status, MAX(krs.semester) as semester')
            ->from('mahasiswa as mhs')
            ->join('krs','krs.nim = mhs.nim')
            ->group_by('mhs.nim', 'desc')
            ->where('status', 'N')
            ->where('semester >', 8)
            ->get();
    }

    public function getMhsAktif() {
        return $this->db->select('mhs.nim, mhs.nama_mahasiswa, mhs.status, MAX(krs.semester) as semester')
            ->from('mahasiswa as mhs')
            ->join('krs','krs.nim = mhs.nim')
            ->group_by('mhs.nim', 'desc')
            ->where('status', 'A')
            ->where('semester < ', 14)
            ->get();
    }

    public function getTestingDate() {
        return $this->db->select('*')
            ->from('aktivasi')
            ->where('kode_tahun_akademik', 24)
            ->get()
            ->row_array();
    }

    public function getMatakulaih($kode_program_studi, $tahun_akademik) {
        return $this->db->select('mak.id_matakuliah, mak.kode_matakuliah, mak.id_matakuliah, nama_matakuliah, kelas_id')
            ->from('nama_kurikulum as nk')
            ->join('kurikulum as kur', 'nk.kode_nama_kurikulum=kur.kode_nama_kurikulum')
            ->join('krs_detail as kd', 'kd.id_matakuliah=kur.id_matakuliah')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('kelas', 'kelas.id_matakuliah=mak.id_matakuliah AND kelas.kode_tahun_akademik=krs.kode_tahun_akademik', 'left')
            ->where('nk.kode_program_studi', $kode_program_studi)
            ->where('krs.kode_tahun_akademik', $tahun_akademik)
            ->where_not_in('kd.status', ['K'])
            ->group_by('mak.kode_matakuliah')
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->get()->result();
    }

    public function getAllKelas() {
        return $this->db->select("*")->from('kelas')->get()->result();
    }

    public function getMhsManajemen() {
        return $this->db->select("*")
            ->from('krs')
            ->like('nim', '220301', 'both')
            ->get()->result();
    }

    public function getTampilDoang() {
        return $this->db->select("*")
            ->from('kelas')
            ->where('status_nilai', 'R')
            ->get()->result();
    }

    public function getTestingQuery($kelas_id) {
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
            ->join('persentasi_nilai_dosen as pnd', 'pnd.kelas_id=kl.kelas_id', 'left')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row();
    }

    public function getMahasiswaByLikeNim($nim_pattern) {
        return $this->db->select("*")->from('mahasiswa')->like('nim', $nim_pattern, 'both')->get()->result();
    }

    public function getKrsByLikeNimSemester($nim_pattern, $semester) {
        return $this->db->select("*")->from("krs")->like('nim', $nim_pattern, 'both')->where('semester', $semester)->get()->result();
    }

    public function getMahasiswaByLikeNimOnly($nim_pattern) {
        return $this->db->select("*")->from("mahasiswa")->like('nim', $nim_pattern, 'both')->get()->result();
    }

    public function getAsdfQuery($nim_pattern, $semester) {
        return $this->db->select("*, mahasiswa.nim, krs.kode_krs")
            ->from('mahasiswa')
            ->join('krs', 'krs.nim=mahasiswa.nim', 'left')
            ->where('krs.semester', $semester)
            ->like('mahasiswa.nim', $nim_pattern, 'both')
            ->get()->result();
    }

    public function getKelasIdList() {
        return $this->db->select("kelas_id")->from('kelas')->get()->result();
    }

    public function getNilaiViewQuery1($kelas_id) {
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
            ->join('persentasi_nilai_dosen as pnd', 'pnd.kelas_id=kl.kelas_id')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row();
    }

    public function getNilaiViewNamaDosen($kelas_id) {
        return $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen SEPARATOR "/") as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row();
    }

    public function getNilaiViewQuery2($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl, sistem_penilaian_detail as spd')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kl.kelas_id')
            ->join('krs_detail as kde', 'kde.kode_krs_detail=km.kode_krs_detail')
            ->join('khs_detail as khde', 'khde.kode_krs_detail=kde.kode_krs_detail')
            ->join('krs as rs', 'rs.kode_krs=kde.kode_krs')
            ->join('mahasiswa as mhs', 'mhs.nim=rs.nim')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->result();
    }

    public function getNilaiViewQuery3($kelas_id) {
        return $this->db->select('ds.nama_dosen as dosen_fakultas, ds.nik as nik_dosen_fakultas')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row();
    }

    public function getNilaiViewQuery4($kelas_id) {
        return $this->db->select('ds.nama_dosen as dosen_program_studi, kv.updated_at as tanggal_tandantangan')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=kp.kode_dosen')
            ->join('kelas_validasi as kv', 'kv.kelas_id=kl.kelas_id')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row();
    }
}
