<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DosenAkademikService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
        ));
    }

    // ========== KELAS / MENGAJAR ==========

    public function getKelasAmpu($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function getKelasAmpuResult($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getKelasAmpuKpat($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*,duk.id_kelas as cek')
            ->from('mengajar_kpat as meng')
            ->join('kelas_kpat', 'kelas_kpat.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas_kpat.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->join('dummy_update_kelas_kpat as duk', 'duk.id_kelas=kelas_kpat.kelas_id', 'left')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function getKelasAmpuWithUpdate($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*,duk.id_kelas as cek')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas=kelas.kelas_id', 'left')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $tahun_akademik->kode_tahun_akademik)
            ->get()->result_object();
    }

    public function getKelasById($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function getKelasByIdWithUpdate($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas = kelas.kelas_id')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function getKelasKpatByIdWithDummy($kelas_id, $level = null) {
        $this->db->select('*')
            ->from('kelas_kpat')
            ->join('dummy_update_kelas_kpat as duk', 'duk.id_kelas = kelas_kpat.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas_kpat.id_matakuliah')
            ->where('duk.id_kelas', $kelas_id);
        if ($level !== null) {
            $this->db->where('duk.level', $level);
        }
        return $this->db->get()->row_object();
    }

    public function getKelasKpatByIdDesc($kelas_id) {
        return $this->db->select('*')
            ->from('kelas_kpat')
            ->join('dummy_update_kelas_kpat as duk', 'duk.id_kelas = kelas_kpat.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas_kpat.id_matakuliah')
            ->where('duk.id_kelas', $kelas_id)
            ->order_by('duk.level', 'desc')
            ->get()->row_object();
    }

    public function getMengajarDosen($kelas_id) {
        return $this->db->select('*')->from('mengajar')
            ->join('dosen', 'dosen.kode_dosen=mengajar.kode_dosen')
            ->where('kelas_id', $kelas_id)
            ->get()->result_object();
    }

    public function getMengajarDosenKpat($kelas_id) {
        return $this->db->select('*')->from('mengajar_kpat')
            ->join('dosen', 'dosen.kode_dosen=mengajar_kpat.kode_dosen')
            ->where('kelas_id', $kelas_id)
            ->get()->result_object();
    }

    // ========== DUMMY NILAI ==========

    public function getDummyNilai($kode_khs_detail) {
        return $this->db->select('*')->from('dummy_nilai')->where('kode_khs_detail', $kode_khs_detail)->get()->row_object();
    }

    public function exisDummyNilai($kode_khs_detail) {
        $cek = $this->db->get_where('dummy_nilai', array('kode_khs_detail' => $kode_khs_detail))->row_object();
        return !empty($cek);
    }

    public function addOrUpdateDummyNilai($kode_khs_detail, array $data_add, array $data_update) {
        if ($this->exisDummyNilai($kode_khs_detail)) {
            return $this->db->where('kode_khs_detail', $kode_khs_detail)->update('dummy_nilai', $data_update);
        } else {
            return $this->db->insert('dummy_nilai', $data_add);
        }
    }

    public function updateDummyNilaiNa($kode_khs_detail, $nilai_akhir) {
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('dummy_nilai', array('dummy_na' => round($nilai_akhir, 1)));
    }

    public function getDummyNilaiByKhs($kode_khs_detail) {
        return $this->db->select('*')->from('dummy_nilai')->where('kode_khs_detail', $kode_khs_detail)->get()->row_object();
    }

    // ========== PERSENTASI NILAI ==========

    public function getPersentasiNilai($kelas_id) {
        return $this->db->get_where('persentasi_nilai_dosen', array('kelas_id' => $kelas_id))->row_object();
    }

    public function getPersentasiNilaiKpat($kelas_id) {
        return $this->db->from('persentasi_nilai_dosen_kpat')->where('kelas_id', $kelas_id)->get()->row_object();
    }

    public function insertPersentasiNilai($data) {
        return $this->db->insert('persentasi_nilai_dosen', $data);
    }

    public function insertPersentasiNilaiDefault($id) {
        $data = array(
            'kelas_id' => $id,
            'nilai_harian' => 20,
            'nilai_uts' => 30,
            'nilai_uas' => 50,
        );
        return $this->db->insert('persentasi_nilai_dosen', $data);
    }

    public function insertPersentasiNilaiKpat($id) {
        $data = array(
            'kelas_id' => $id,
            'nilai_harian' => 20,
            'nilai_uts' => 30,
            'nilai_uas' => 50,
        );
        return $this->db->insert('persentasi_nilai_dosen_kpat', $data);
    }

    public function updatePersentasiNilai($kelas_id, $data_store) {
        return $this->db->where('kelas_id', $data_store['kelas_id'])->update('persentasi_nilai_dosen', $data_store);
    }

    public function updatePersentasiNilaiKpat($kelas_id, $data_store) {
        return $this->db->where('kelas_id', $data_store['kelas_id'])->update('persentasi_nilai_dosen_kpat', $data_store);
    }

    public function storePersentasiNilai($data_store) {
        $jml = $data_store['nilai_harian'] + $data_store['nilai_uts'] + $data_store['nilai_uas'];
        if ($jml != 100) {
            return false;
        }
        return $this->db->insert('persentasi_nilai_dosen', $data_store);
    }

    public function storePersentasiNilaiKpat($data_store) {
        $jml = $data_store['nilai_harian'] + $data_store['nilai_uts'] + $data_store['nilai_uas'];
        if ($jml != 100) {
            return false;
        }
        return $this->db->insert('persentasi_nilai_dosen_kpat', $data_store);
    }

    // ========== SETTING / CHAT ID ==========

    public function getSettingKuisioner() {
        return $this->db->get_where('setting_kuisioner', array('setting_id' => 2))->row_object();
    }

    public function getChatIdDosen($kode_dosen) {
        return $this->db->select('chatid')->from('dosen')->where('kode_dosen', $kode_dosen)->get()->row_array();
    }

    // ========== NILAI MAHASISWA ==========

    public function getNilaiMahasiswaUts($kelas_id) {
        return $this->db->select('dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('km.kelas_id', $kelas_id)
            ->order_by("mah.nim", "asc")
            ->get()->result();
    }

    public function getNilaiMahasiswaUas($kelas_id) {
        return $this->db->select('dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na,block.id as block_id')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->order_by('mah.nim')
            ->where('km.kelas_id', $kelas_id)
            ->get()->result();
    }

    public function getNilaiMahasiswaUasWithGrade($kelas_id) {
        return $this->db->select('grade, dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na,block.id as block_id')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dummy_na >= spd.nilai_minimum AND dummy_na <= spd.nilai_maksimum')
            ->where('km.kelas_id', $kelas_id)
            ->order_by('mah.nim')
            ->get()->result();
    }

    public function getHomebaseDosen() {
        return $this->db->select('*')->from('dosen')->get()->row()->homebase;
    }

    // ========== CHOOSE PENILAIAN ==========

    public function getChoosePresentasiNilai($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*, kelas.kelas_id as kelas_id')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('persentasi_nilai_dosen as pnd', 'pnd.kelas_id=kelas.kelas_id', 'left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where("EXISTS (
                SELECT 1 FROM kelas_mahasiswa km
                JOIN krs_detail kd ON kd.kode_krs_detail = km.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa m ON m.nim = krs.nim
                WHERE km.kelas_id = kelas.kelas_id
                AND LEFT(m.nim, 2) <= '24'
            )")
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getChoosePresentasiNilaiKpat($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*, kelas_kpat.kelas_id as kelas_id')
            ->from('mengajar_kpat as meng')
            ->join('kelas_kpat', 'kelas_kpat.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas_kpat.id_matakuliah=mak.id_matakuliah')
            ->join('persentasi_nilai_dosen_kpat as pnd', 'pnd.kelas_id=kelas_kpat.kelas_id', 'left')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getChooseNilaiUts($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getChooseNilaiUas($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getChooseNilaiUasWithProdi($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getAktivasi($kode_tahun_akademik) {
        return $this->db->select('*')->from('aktivasi')->where('kode_tahun_akademik', $kode_tahun_akademik)->get()->row();
    }

    public function updateKelasParamUts($kode_tahun_akademik) {
        $where_in = array('F', 'R');
        $this->db->where_in('status_nilai_uts', $where_in);
        $this->db->where('kode_tahun_akademik', $kode_tahun_akademik);
        $this->db->where_not_in('status_revisi_uts', "1");
        $this->db->update('kelas', array('param_uts' => "1"));
    }

    public function updateKelasParamUtsRevisi($kode_tahun_akademik) {
        $this->db->where('kode_tahun_akademik', $kode_tahun_akademik);
        $this->db->where('status_revisi_uts', "1");
        $this->db->update('kelas', array('param_uts' => "1", 'status_revisi_uts' => ""));
    }

    public function updateKelasParamUas($kode_tahun_akademik) {
        $where_in = array('F', 'R');
        $this->db->where_in('status_nilai', $where_in);
        $this->db->where('kode_tahun_akademik', $kode_tahun_akademik);
        $this->db->where_not_in('status_revisi_uas', "1");
        $this->db->update('kelas', array('param_uas' => "1"));
    }

    public function updateKelasParamUasRevisi($kode_tahun_akademik) {
        $this->db->where('kode_tahun_akademik', $kode_tahun_akademik);
        $this->db->where('status_revisi_uas', "1");
        $this->db->update('kelas', array('param_uas' => "1", 'status_revisi_uas' => ""));
    }

    // ========== KUIZIONER ==========

    public function getKuisionerKelas($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*, count(distinct km.kelas_mahasiswa_id) as jum_mhs')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kelas.kelas_id')
            ->join('kuisioner as kus', 'kus.kelas_mahasiswa_id=km.kelas_mahasiswa_id', 'left')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->get()->result();
    }

    public function getKuisionerSub($kode_dosen, $kelas_id) {
        return $this->db->select('kelas.kelas_id,nama_matakuliah, mak.kode_matakuliah, nama_kelas,sum(hasil) as hasil, count(kus.kelas_mahasiswa_id) as jml_mhs, FORMAT(sum(hasil)/count(km.kelas_mahasiswa_id),1) as rata')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kelas.kelas_id')
            ->join('kuisioner as kus', 'kus.kelas_mahasiswa_id=km.kelas_mahasiswa_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kelas.kelas_id', $kelas_id)
            ->group_by('soal_kuisioner_id')->get_compiled_select();
    }

    public function getKuisionerRata($sub_query) {
        return $this->db->select('*, sum(rata) as total_rata, FORMAT(sum(rata)/count(kelas_id),2) as hasil_akhir')
            ->from("($sub_query) as m")
            ->get()->row();
    }

    // ========== CETAK VALIDASI ==========

    public function getCetakQuery1($id) {
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

    public function getCetakQuery2($id) {
        return $this->db->select('mah.nim, nama_mahasiswa, nilai_harian, nilai_uts, nilai_uas, nilai_akhir')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->where('kelas.kelas_id', $id)
            ->where('spd.kode_sistem_penilaian', 1)
            ->group_by('nama_mahasiswa')
            ->order_by('substr(mah.nim,1,2) asc')
            ->order_by('substr(mah.nim,6,1) asc')
            ->order_by('substr(mah.nim,-4,4) asc')
            ->get()->result();
    }

    public function getCetakQuery2WithBlock($id, $ta = null) {
        $q = $this->db->select('mah.nim, nama_mahasiswa, nilai_harian, nilai_uts, nilai_uas, nilai_akhir, block.id as block_id')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->where('kelas.kelas_id', $id)
            ->where('spd.kode_sistem_penilaian', 1)
            ->group_by('nama_mahasiswa')
            ->order_by('substr(mah.nim,1,2) asc')
            ->order_by('substr(mah.nim,6,1) asc')
            ->order_by('substr(mah.nim,-4,4) asc');
        return $q->get()->result();
    }

    public function getCetakQuery3($id) {
        return $this->db->select('ds.nama_dosen as dosen_fakultas, ds.nik as nik_dosen_fakultas')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $id)
            ->get()->row();
    }

    public function getCetakQuery4() {
        return $this->db->select('*')
            ->from('sistem_penilaian_detail')
            ->where('kode_sistem_penilaian=', 1)
            ->order_by('bobot_nilai', 'desc')
            ->get()->result();
    }

    public function getCetakNamaDosen($id) {
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

    public function getCetakQuery2Dummy($id) {
        return $this->db->select('dummy_uts, dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, nilai_harian, nilai_uts, nilai_uas, nilai_akhir')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dummy_na >= spd.nilai_minimum AND dummy_na <= spd.nilai_maksimum')
            ->where('km.kelas_id', $id)
            ->order_by('mah.nim', 'asc')
            ->get()->result();
    }

    public function getCetakQuery3Kaprodi($id) {
        return $this->db->select('ds.nama_dosen as dosen_kaprodi, ds.nik as nik_dosen_kaprodi')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=kp.kode_dosen')
            ->where('kl.kelas_id=', $id)
            ->get()->row();
    }

    public function getCetakQuery2DummyUas($id) {
        return $this->db->select('dummy_harian, dummy_uas, dummy_na, dummy_uts, dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, nilai_harian, nilai_uts, nilai_uas, nilai_akhir')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dummy_na >= spd.nilai_minimum AND dummy_na <= spd.nilai_maksimum')
            ->where('km.kelas_id', $id)
            ->order_by('mah.nim', 'asc')
            ->get()->result();
    }

    // ========== CATATAN REVISI ==========

    public function getCatatanRevisi($kelas_id, $user1, $user2) {
        if ($user1 == 'dosen' && $user2 == 'prodi') {
            return $this->db->select('*')->from('catatan_revisi')
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_prodi !=', null)
                ->get()->result_object();
        } elseif ($user2 == 'dosen' && $user1 == 'prodi') {
            return $this->db->select('*')->from('catatan_revisi')
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_prodi !=', null)
                ->get()->result_object();
        } elseif ($user1 == 'dosen' && $user2 == 'dekan') {
            return $this->db->select('*')->from('catatan_revisi')
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_dekan !=', null)
                ->get()->result_object();
        } elseif ($user2 == 'dosen' && $user1 == 'dekan') {
            return $this->db->select('*')->from('catatan_revisi')
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_dekan !=', null)
                ->get()->result_object();
        }
    }

    public function updateCatatanRevisiDosen($kelas_id, $table) {
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_prodi !=', null)
            ->where('param_dosen', null)
            ->where('param_prodi', 1)
            ->get()->result_object();
        $id_field = ($table == 'catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_dosen' => 1));
        }
    }

    public function updateCatatanRevisiProdi($kelas_id, $table) {
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_prodi !=', null)
            ->where('param_dosen', 1)
            ->where('param_prodi', null)
            ->get()->result_object();
        $id_field = ($table == 'catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_prodi' => 1));
        }
    }

    public function updateCatatanRevisiDosenDekan($kelas_id, $table) {
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_dekan !=', null)
            ->where('param_dosen', null)
            ->where('param_dekan', 1)
            ->get()->result_object();
        $id_field = ($table == 'catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_dosen' => 1));
        }
    }

    public function updateCatatanRevisiDekan($kelas_id, $table) {
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_dekan !=', null)
            ->where('param_dosen', 1)
            ->where('param_dekan', null)
            ->get()->result_object();
        $id_field = ($table == 'catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_dekan' => 1));
        }
    }

    public function getDosenPengampu($kelas_id) {
        return $this->db->select('kelas.kelas_id, DS.nama_dosen')
            ->from('kelas')
            ->join('mengajar', 'mengajar.kelas_id=kelas.kelas_id')
            ->join('dosen as DS', 'DS.kode_dosen=mengajar.kode_dosen')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row();
    }

    public function getKaprodiFromKelas($kelas_id) {
        return $this->db->select('kelas.kelas_id, KP.nama_dosen')->from('kelas')
            ->join('program_studi', 'kelas.kode_program_studi = program_studi.kode_program_studi')
            ->join('kaprodi', 'kaprodi.kode_program_studi=program_studi.kode_program_studi')
            ->join('dosen as KP', 'KP.kode_dosen=kaprodi.kode_dosen')
            ->where('kelas_id', $kelas_id)
            ->get()->row();
    }

    public function getDekanFromKelas($kelas_id) {
        return $this->db->select('kelas.kelas_id, DK.nama_dosen')->from('kelas')
            ->join('program_studi', 'kelas.kode_program_studi = program_studi.kode_program_studi')
            ->join('fakultas', 'fakultas.kode_fakultas=program_studi.kode_fakultas')
            ->join('dosen as DK', 'DK.kode_dosen=fakultas.dekan')
            ->where('kelas_id', $kelas_id)
            ->get()->row();
    }

    public function insertCatatanRevisi($table, $data) {
        return $this->db->insert($table, $data);
    }

    public function getCatatanRevisiCount($kelas_id, $table, $user_type) {
        if ($user_type == 'prodi') {
            return $this->db->select('*')->from($table)
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_prodi !=', null)
                ->where('param_dosen', null)
                ->where('param_prodi', 1)
                ->get()->result_object();
        } else {
            return $this->db->select('*')->from($table)
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_dekan !=', null)
                ->where('param_dosen', null)
                ->where('param_dekan', 1)
                ->get()->result_object();
        }
    }

    // ========== SHOW COMMENT ==========

    public function getKomentarKuisioner($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kelas.kelas_id')
            ->join('kuisioner as kus', 'kus.kelas_mahasiswa_id=km.kelas_mahasiswa_id', 'left')
            ->where('kelas.kelas_id', $kelas_id)
            ->where("(kritik != '' or saran != '')")
            ->group_by('km.kelas_mahasiswa_id')
            ->get()->result();
    }

    public function getKomentarCatatan($kelas_id) {
        return $this->db->select('catatan_prodi, catatan_dekan')
            ->from('kelas')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
    }

    public function getHistoryComment($kelas_id) {
        $detail = $this->db->select('mak.kode_matakuliah, mak.nama_matakuliah, nama_kelas')
            ->from('kelas')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row_object();
        $dosen = $this->db->select('dosen.nama_dosen')
            ->from('mengajar as meng')
            ->join('dosen', 'dosen.kode_dosen=meng.kode_dosen')
            ->where('meng.kelas_id', $kelas_id)
            ->get()->result();
        $komentar = $this->db->select('*')
            ->from('kelas_validasi')
            ->where('kelas_id', $kelas_id)
            ->order_by('updated_at', 'Desc')
            ->get()->result();
        return array('detail' => $detail, 'dosen' => $dosen, 'komentar' => $komentar);
    }

    // ========== SELESAI NILAI ==========

    public function getQueryDosenKelas($kelas_id) {
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

    public function getQueryProdi($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=kp.kode_dosen')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function getQueryFakultas($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function updateKelasStatusNilai($kelas_id, $status = 'T') {
        $this->db->where('kelas_id', $kelas_id)->update('kelas', array('status_nilai' => $status));
    }

    public function updateKelasStatusNilaiUts($kelas_id, $status = 'T') {
        $this->db->where('kelas_id', $kelas_id)->update('kelas', array('status_nilai_uts' => $status));
    }

    public function insertKelasValidasi($data) {
        $this->db->insert('kelas_validasi', $data);
    }

    public function transBegin() {
        $this->db->trans_begin();
    }

    public function transCommit() {
        $this->db->trans_commit();
    }

    public function transRollback() {
        $this->db->trans_rollback();
    }

    // ========== REVISI NILAI ==========

    public function getDummyUpdateKelas($kelas_id) {
        return $this->db->select('*')->from('dummy_update_kelas')->where('id_kelas', $kelas_id)->get()->result_object();
    }

    public function insertDummyUpdateKelas($data) {
        $this->db->insert('dummy_update_kelas', $data);
    }

    public function getDummyUpdateKelasById($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas', $kelas_id)
            ->order_by('level', 'desc')
            ->get()->row_object();
    }

    public function getKelasRevisi($kelas_id) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas = kelas.kelas_id')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->where('duk.id_kelas', $kelas_id)
            ->order_by('duk.level', 'desc')
            ->get()->row_object();
    }

    public function getNilaiRevisiLevel1($kelas_id, $level, $ta) {
        return $this->db->select('khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, max(ket) as ket, max(dumm.harian) as nilai_harian,  max(dumm.uts) as nilai_uts,  max(dumm.uas) as nilai_uas, max(dumm.na) as nilai_akhir,block.id as block_id,mbkm.id as mbkm_id,harian,uts,uas,na,dumm.level')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = ' . $this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail and dumm.level = ' . $this->db->escape($level), 'left')
            ->where('km.kelas_id', $kelas_id)
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function getNilaiRevisiLevel2($kelas_id, $level, $ta) {
        return $this->db->select('khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, max(ket) as ket, max(dumm.harian) as nilai_harian,  max(dumm.uts) as nilai_uts,  max(dumm.uas) as nilai_uas, max(dumm.na) as nilai_akhir,block.id as block_id,mbkm.id as mbkm_id,harian,uts,uas,na,dumm.level')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = ' . $this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail and dumm.level = ' . $this->db->escape($level + 1), 'left')
            ->where('km.kelas_id', $kelas_id)
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function getSistemPenilaian() {
        return $this->db->select('*')
            ->from('sistem_penilaian_detail as spd')
            ->where('spd.kode_sistem_penilaian', 1)
            ->get()->result();
    }

    public function getPersentaseFrom($table, $kelas_id) {
        return $this->db->from($table)->where('kelas_id', $kelas_id)->get()->row_object();
    }

    public function checkDummyUpdateNilai($id, $level) {
        return $this->db->select('*')->from('dummy_update_nilai')
            ->where('kode_khs_detail', $id)
            ->where('level', $level)
            ->get()->num_rows();
    }

    public function updateDummyUpdateNilai($id, $level, $data) {
        $this->db->where('kode_khs_detail', $id)
            ->where('level', $level)
            ->update('dummy_update_nilai', $data);
    }

    public function insertDummyUpdateNilai($data) {
        $this->db->insert('dummy_update_nilai', $data);
    }

    public function getGradeNilaiRevisi($id, $level) {
        return $this->db->select('grade,dun.harian,dun.uts,dun.uas,dun.na')
            ->from('dummy_update_nilai as dun,sistem_penilaian_detail as spd')
            ->where('kode_khs_detail', $id)
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->get()->row_object();
    }

    public function getDummyUpdateKelasStatus($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas', $kelas_id)
            ->where('status !=', 3)
            ->get()->num_rows();
    }

    public function getDummyUpdateKelasValid($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas', $kelas_id)
            ->where('status !=', 3)
            ->order_by('level', 'asc')
            ->limit('1')
            ->get()->result_object();
    }

    public function getDummyUpdateKelasLast($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas')
            ->where('id_kelas', $kelas_id)
            ->order_by('level', 'desc')
            ->limit('1')
            ->get()->result_object();
    }

    public function getDummyUpdateKelasByLevel($kelas_id) {
        return $this->db->where('status_dosen', 'R')->where('id_kelas', $kelas_id)->get('dummy_update_kelas')->row_object();
    }

    // ========== KPAT SPECIFIC ==========

    public function getChooseRevisi($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where("EXISTS (
                SELECT 1 FROM kelas_mahasiswa km
                JOIN krs_detail kd ON kd.kode_krs_detail = km.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa m ON m.nim = krs.nim
                WHERE km.kelas_id = kelas.kelas_id
                AND LEFT(m.nim, 2) <= '24'
            )")
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function getChooseRevisiKpat($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar_kpat as meng')
            ->join('kelas_kpat', 'kelas_kpat.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas_kpat.id_matakuliah=mak.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas_kpat.kode_program_studi')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas_kpat.nama_kelas_id')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result_object();
    }

    public function getDummyUpdateKelasKpatStatus($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas', $kelas_id)
            ->where('status !=', 3)
            ->get()->num_rows();
    }

    public function getDummyUpdateKelasKpatValid($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas', $kelas_id)
            ->where('status !=', 3)
            ->order_by('level', 'asc')
            ->limit('1')
            ->get()->result_object();
    }

    public function getDummyUpdateKelasKpatLast($kelas_id) {
        return $this->db->select('*')
            ->from('dummy_update_kelas_kpat')
            ->where('id_kelas', $kelas_id)
            ->order_by('level', 'desc')
            ->limit('1')
            ->get()->result_object();
    }

    public function getDummyUpdateKelasKpatAll($kelas_id) {
        return $this->db->select('*')->from('dummy_update_kelas_kpat')->where('id_kelas', $kelas_id)->get()->result_object();
    }

    public function getDummyUpdateKelasKpatByLevel($kelas_id) {
        return $this->db->where('status_dosen', 'R')->where('id_kelas', $kelas_id)->get('dummy_update_kelas_kpat')->row_object();
    }

    public function getNilaiMahasiswaKpatRevisi($kelas_id, $level, $ta) {
        return $this->db->select('khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, ket, dumm.harian as nilai_harian,  dumm.uts as nilai_uts,  dumm.uas as nilai_uas, dumm.na as nilai_akhir,block.id as block_id,mbkm.id as mbkm_id,harian,uts,uas,na')
            ->from('kelas_mahasiswa_kpat as km')
            ->join('kelas_kpat', 'kelas_kpat.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = ' . $this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai_kpat as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail')
            ->where('dumm.kelas_id', $kelas_id)
            ->where('dumm.level', $level)
            ->group_by('mah.nim')
            ->get()->result();
    }

    public function getNilaiMahasiswaKpatRevisiPrev($kelas_id, $level, $ta) {
        return $this->db->select('khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, max(ket) as ket, max(dumm.harian) as nilai_harian,  max(dumm.uts) as nilai_uts,  max(dumm.uas) as nilai_uas, max(dumm.na) as nilai_akhir,block.id as block_id,mbkm.id as mbkm_id,harian,uts,uas,na')
            ->from('kelas_mahasiswa_kpat as km')
            ->join('kelas_kpat', 'kelas_kpat.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = ' . $this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai_kpat as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail and dumm.level = ' . $this->db->escape($level - 1), 'left')
            ->where('km.kelas_id', $kelas_id)
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function deleteDummyUpdateKelasKpat($kelas, $level) {
        $this->db->where(array('id_kelas' => $kelas, 'level' => $level))->delete('dummy_update_kelas_kpat');
    }

    public function deleteDummyUpdateNilaiKpat($kelas, $level) {
        $this->db->where(array('kelas_id' => $kelas, 'level' => $level))->delete('dummy_update_nilai_kpat');
    }

    public function deleteDummyUpdateKelas($kelas, $level) {
        $this->db->where(array('id_kelas' => $kelas, 'level' => $level))->delete('dummy_update_kelas');
    }

    public function deleteDummyUpdateNilai($kelas, $level) {
        $this->db->where(array('kelas_id' => $kelas, 'level' => $level))->delete('dummy_update_nilai');
    }

    public function checkDummyUpdateNilaiKpat($id, $level) {
        return $this->db->select('*')->from('dummy_update_nilai_kpat')
            ->where('kode_khs_detail', $id)
            ->where('level', $level)
            ->get()->num_rows();
    }

    public function updateDummyUpdateNilaiKpat($id, $level, $data) {
        $this->db->where('kode_khs_detail', $id)
            ->where('level', $level)
            ->update('dummy_update_nilai_kpat', $data);
    }

    public function insertDummyUpdateNilaiKpat($data) {
        $this->db->insert('dummy_update_nilai_kpat', $data);
    }

    public function getGradeNilaiKpatRevisi($id) {
        return $this->db->select('grade,dun.harian,dun.uts,dun.uas,dun.na')
            ->from('dummy_update_nilai_kpat as dun,sistem_penilaian_detail as spd')
            ->where('kode_khs_detail', $id)
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->get()->row_object();
    }

    public function getDummyUpdateNilaiKet($id, $kelas_id, $level) {
        $data = $this->db->select('ket')
            ->from('dummy_update_nilai')
            ->where('kode_khs_detail', $id)
            ->where('kelas_id', $kelas_id)
            ->where('level', $level)
            ->get()->row_object();
        return $data;
    }

    public function getDummyUpdateNilaiKetPrev($id, $kelas_id, $level) {
        $data = $this->db->select('ket')
            ->from('dummy_update_nilai')
            ->where('kode_khs_detail', $id)
            ->where('kelas_id', $kelas_id)
            ->where('level', ($level - 1))
            ->get()->row_object();
        return $data;
    }

    public function getDummyUpdateNilaiKpatKet($id, $kelas_id, $level) {
        return $this->db->select('ket')
            ->from('dummy_update_nilai_kpat')
            ->where('kode_khs_detail', $id)
            ->where('kelas_id', $kelas_id)
            ->where('level', $level)
            ->get()->row_object();
    }

    public function getDummyUpdateNilaiKpatPrev($id, $kelas_id, $level) {
        return $this->db->select('ket')
            ->from('dummy_update_nilai_kpat')
            ->where('kode_khs_detail', $id)
            ->where('kelas_id', $kelas_id)
            ->where('level', ($level - 1))
            ->get()->row_object();
    }

    public function getDummyUpdateNilaiData($id, $level) {
        return $this->db->select('*')
            ->from('dummy_update_nilai')
            ->where('kode_khs_detail', $id)
            ->where('level', $level)
            ->get()->row_object();
    }

    public function getDummyUpdateNilaiKpatData($id, $level) {
        return $this->db->select('*')
            ->from('dummy_update_nilai_kpat')
            ->where('kode_khs_detail', $id)
            ->where('level', $level)
            ->get()->row_object();
    }

    public function getDummyUpdateNilaiKpatDataPrev($id, $level) {
        return $this->db->select('*')
            ->from('dummy_update_nilai_kpat')
            ->where('kode_khs_detail', $id)
            ->where('level', $level - 1)
            ->get()->row_object();
    }

    public function updateDummyUpdateNilaiKpatKet($kelas_id, $level, $id, $ket) {
        return $this->db->where('kelas_id', $kelas_id)->where('level', $level)->where('kode_khs_detail', $id)->update('dummy_update_nilai_kpat', array('ket' => $ket));
    }

    public function updateDummyUpdateNilaiKet($kelas, $level, $id, $ket) {
        return $this->db->where('kelas_id', $kelas)->where('level', $level)->where('kode_khs_detail', $id)->update('dummy_update_nilai', array('ket' => $ket));
    }

    public function getExcelMahasiswa($kelas_id) {
        return $this->db->select('mah.nim, mah.nama_mahasiswa')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->where('km.kelas_id', $kelas_id)
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function getImportExcelMahasiswa($kelas) {
        return $this->db->select('*')
            ->from('kelas_mahasiswa')
            ->join('krs_detail', 'krs_detail.kode_krs_detail = kelas_mahasiswa.kode_krs_detail')
            ->join('krs', 'krs.kode_krs = krs_detail.kode_krs')
            ->join('khs_detail', 'khs_detail.kode_krs_detail = krs_detail.kode_krs_detail')
            ->where('kelas_mahasiswa.kelas_id', $kelas)
            ->get()->result_object();
    }

    public function insertBatchDummyUpdateNilai($obj) {
        $this->db->insert_batch('dummy_update_nilai', $obj);
    }

    public function updateDummyUpdateKelasKpat($kelas, $level) {
        $this->db->where('id_kelas', $kelas)->where('level', $level)->update('dummy_update_kelas_kpat', array('status_dosen' => 'T', 'status' => '1'));
    }

    public function updateDummyUpdateKelas($kelas, $level) {
        $this->db->where('id_kelas', $kelas)->where('level', $level)->update('dummy_update_kelas', array('status_dosen' => 'T', 'status' => '1'));
    }

    public function insertDummyUpdateKelasKpat($data) {
        $this->db->insert('dummy_update_kelas_kpat', $data);
    }

    public function getAllDummyUpdateKelasKpat($kelas_id) {
        return $this->db->select('*')->from('dummy_update_kelas_kpat')->where('id_kelas', $kelas_id)->get()->result_object();
    }

    public function getRevisiNilaiMahasiswa($kelas, $level, $ta) {
        return $this->db->select('mah.nim,mah.nama_mahasiswa,ket,grade,dun.harian,dun.uts,dun.uas,dun.na,mbkm.id as mbkm_id,block.id as block_id')
            ->from('dummy_update_nilai_kpat as dun,sistem_penilaian_detail as spd')
            ->join('khs_detail as khd', 'khd.kode_khs_detail=dun.kode_khs_detail')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = ' . $this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('dun.kelas_id', $kelas)
            ->where('level', $level)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->group_by('mah.nim')
            ->get()->result_object();
    }

    public function getRevisiDosenKelas($kelas_id) {
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
            ->get()->row();
    }

    public function getRevisiQuery2($id, $level, $ta) {
        return $this->db->select('mah.nim, nama_mahasiswa, ket, harian, uts, uas, na, grade, block.id as block_id, mbkm.id as mbkm_id')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('mbkm', 'mbkm.nim = mah.nim AND mbkm.kode_ta = ' . $this->db->escape($ta), 'left')
            ->join('block', 'block.nim = mah.nim', 'left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dun', 'dun.kode_khs_detail=khd.kode_khs_detail')
            ->where('kelas.kelas_id', $id)
            ->where('spd.kode_sistem_penilaian', 1)
            ->where('na >= spd.nilai_minimum AND na <= spd.nilai_maksimum')
            ->where('block.nim', null)
            ->group_by('nim')
            ->order_by('substr(mah.nim,1,2) asc')
            ->order_by('substr(mah.nim,6,1) asc')
            ->order_by('substr(mah.nim,-4,4) asc')
            ->get()->result();
    }

    public function getPersentaseNilaiDosen($id) {
        return $this->db->from('persentasi_nilai_dosen')->where('kelas_id', $id)->get()->row_object();
    }

    // ========== UPDATE NILAI ==========

    public function getMengajarWithUpdate($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas = kelas.kelas_id', 'left')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getMengajarWithUpdateProdi($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('mengajar as meng')
            ->join('kelas', 'kelas.kelas_id=meng.kelas_id')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->join('matakuliah as mak', 'kelas.id_matakuliah=mak.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('dummy_update_kelas as duk', 'duk.id_kelas = kelas.kelas_id', 'left')
            ->where('kode_dosen', $kode_dosen)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function exisDummyUpdateNilai($kode_khs_detail) {
        $cek = $this->db->get_where('dummy_update_nilai', array('kode_khs_detail' => $kode_khs_detail))->row_object();
        return !empty($cek);
    }

    public function addOrUpdateDummyUpdateNilai($kode_khs_detail, array $data_add, array $data_update) {
        if ($this->exisDummyUpdateNilai($kode_khs_detail)) {
            return $this->db->where('kode_khs_detail', $kode_khs_detail)->update('dummy_update_nilai', $data_update);
        } else {
            return $this->db->insert('dummy_update_nilai', $data_add);
        }
    }

    public function getDummyUpdateNilai($kode_khs_detail) {
        return $this->db->select('*')->from('dummy_update_nilai')->where('kode_khs_detail', $kode_khs_detail)->get()->row_object();
    }

    public function getNilaiMahasiswaUpdateUts($kelas_id) {
        return $this->db->select('khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa,nilai_harian,nilai_uts,nilai_uas,dun.harian,dun.uts,dun.uas')
            ->from('kelas_mahasiswa as km')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'mah.nim=krs.nim')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('dummy_update_nilai as dun', 'dun.kode_khs_detail = khd.kode_khs_detail', 'left')
            ->where('km.kelas_id', $kelas_id)
            ->order_by("mah.nim", "asc")
            ->get()->result();
    }

    public function checkDummyUpdateKelas($kelas_id) {
        return $this->db->from('dummy_update_kelas')
            ->where('id_kelas', $kelas_id)
            ->get()->row_object();
    }

    public function updateDummyUpdateNilaiNa($kode_khs_detail, $nilai_akhir) {
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('dummy_update_nilai', array('na' => round($nilai_akhir)));
    }

    public function getSelesaiUtsQueryDosenKelas($kelas_id) {
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

    public function getSelesaiUtsQueryProdi($kelas_id) {
        return $this->db->select('*')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('kaprodi as kp', 'kp.kode_program_studi=ps.kode_program_studi')
            ->join('dosen as ds', 'ds.kode_dosen=kp.kode_dosen')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row_array();
    }

    public function updateDummyKelasStatusUts($kelas_id) {
        $data_update = array('status_uts_dosen' => 'T');
        $this->db->where('id_kelas', $kelas_id)->update('dummy_update_kelas', $data_update);
    }

    public function transCommitOrRedirect($redirect_url) {
        try {
            $this->db->trans_commit();
        } catch (\Throwable $th) {
            $this->db->trans_commit();
        }
    }

    // ========== CATATAN REVISI (UPDATE) ==========

    public function getUpdateCatatanRevisi($kelas_id, $table, $user1, $user2) {
        if ($user1 == 'dosen' && $user2 == 'prodi') {
            return $this->db->select('*')->from($table)
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_prodi !=', null)
                ->get()->result_object();
        } elseif ($user2 == 'dosen' && $user1 == 'prodi') {
            return $this->db->select('*')->from($table)
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_prodi !=', null)
                ->get()->result_object();
        } elseif ($user1 == 'dosen' && $user2 == 'dekan') {
            return $this->db->select('*')->from($table)
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_dekan !=', null)
                ->get()->result_object();
        } elseif ($user2 == 'dosen' && $user1 == 'dekan') {
            return $this->db->select('*')->from($table)
                ->where('kelas_id', $kelas_id)
                ->where('kode_dosen !=', null)
                ->where('kode_dekan !=', null)
                ->get()->result_object();
        }
    }

    public function updateCatatanRevisiDosenBatch($kelas_id, $table) {
        $id_field = ($table == 'update_catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_prodi !=', null)
            ->where('param_dosen', null)
            ->where('param_prodi', 1)
            ->get()->result_object();
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_dosen' => 1));
        }
    }

    public function updateCatatanRevisiProdiBatch($kelas_id, $table) {
        $id_field = ($table == 'update_catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_prodi !=', null)
            ->where('param_dosen', 1)
            ->where('param_prodi', null)
            ->get()->result_object();
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_prodi' => 1));
        }
    }

    public function updateCatatanRevisiDosenDekanBatch($kelas_id, $table) {
        $id_field = ($table == 'update_catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_dekan !=', null)
            ->where('param_dosen', null)
            ->where('param_dekan', 1)
            ->get()->result_object();
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_dosen' => 1));
        }
    }

    public function updateCatatanRevisiDekanBatch($kelas_id, $table) {
        $id_field = ($table == 'update_catatan_revisi') ? 'id_catatan_revisi' : 'id_catatan_revisi_uas';
        $tmp = $this->db->select('*')->from($table)
            ->where('kelas_id', $kelas_id)
            ->where('kode_dosen !=', null)
            ->where('kode_dekan !=', null)
            ->where('param_dosen', 1)
            ->where('param_dekan', null)
            ->get()->result_object();
        foreach ($tmp as $value) {
            $this->db->where($id_field, $value->$id_field)->update($table, array('param_dekan' => 1));
        }
    }

    public function insertUpdateCatatanRevisi($table, $data) {
        return $this->db->insert($table, $data);
    }

    // ========== DUMMY NILAI UTILS ==========

    public function checkAndInsertDummyNilai($kelas_mahasiswa, $table_dummy, $field_check) {
        foreach ($kelas_mahasiswa as $key => $value) {
            if (!$value->$field_check) {
                $data_add = array(
                    'dummy_id' => $value->kode_khs_detail,
                    'kode_khs_detail' => $value->kode_khs_detail,
                    'kode_krs_detail' => $value->kode_krs_detail,
                    'dummy_harian' => 0,
                    'dummy_uts' => 0,
                    'dummy_uas' => 0,
                );
                $X = $this->db->from($table_dummy)->where('dummy_id', $value->kode_khs_detail)->get()->result();
                if ($X) {
                    $this->db->where('dummy_id', $value->kode_khs_detail);
                    $this->db->update($table_dummy, $data_add);
                } else {
                    $this->db->insert($table_dummy, $data_add);
                }
            }
        }
    }

    // ========== PESAN ALL (UAS/KPAT) ==========

    public function getDosenPengampuFromKelas($kelas_id) {
        return $this->db->select('kelas_kpat.kelas_id, DS.nama_dosen')
            ->from('kelas_kpat')
            ->join('mengajar_kpat', 'mengajar_kpat.kelas_id=kelas_kpat.kelas_id')
            ->join('dosen as DS', 'DS.kode_dosen=mengajar_kpat.kode_dosen')
            ->where('kelas_kpat.kelas_id', $kelas_id)
            ->get()->row();
    }

    public function getKaprodiFromKelasKpat($kelas_id) {
        return $this->db->select('kelas_kpat.kelas_id, KP.nama_dosen')->from('kelas_kpat')
            ->join('program_studi', 'kelas_kpat.kode_program_studi = program_studi.kode_program_studi')
            ->join('kaprodi', 'kaprodi.kode_program_studi=program_studi.kode_program_studi')
            ->join('dosen as KP', 'KP.kode_dosen=kaprodi.kode_dosen')
            ->where('kelas_id', $kelas_id)
            ->get()->row();
    }

    public function getDekanFromKelasKpat($kelas_id) {
        return $this->db->select('kelas_kpat.kelas_id, DK.nama_dosen')->from('kelas_kpat')
            ->join('program_studi', 'kelas_kpat.kode_program_studi = program_studi.kode_program_studi')
            ->join('fakultas', 'fakultas.kode_fakultas=program_studi.kode_fakultas')
            ->join('dosen as DK', 'DK.kode_dosen=fakultas.dekan')
            ->where('kelas_id', $kelas_id)
            ->get()->row();
    }

    public function updateDosenKelasRevisi($id) {
        $this->db->where('id_kelas', $id)->update('dummy_update_kelas', array('status_dosen' => 'F'));
    }

    public function updateDosenKelasRevisiKpat($id) {
        $this->db->where('id_kelas', $id)->update('dummy_update_kelas_kpat', array('status_dosen' => 'F'));
    }
}
