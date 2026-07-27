<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KuisionerService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getSettingKuisioner($setting_id) {
        return $this->db->get_where('setting_kuisioner', array('setting_id' => $setting_id))->row_object();
    }

    public function getTahunAkademikOrdered() {
        return $this->db->order_by('kode_tahun_akademik', 'DESC')->get('tahun_akademik')->result_object();
    }

    public function getMatakuliahByProgramStudi($kode_program_studi, $tahun_akademik) {
        return $this->db->select('*')
            ->from('nama_kurikulum as nk')
            ->join('kurikulum as kur', 'nk.kode_nama_kurikulum=kur.kode_nama_kurikulum')
            ->join('krs_detail as kd', 'kd.id_matakuliah=kur.id_matakuliah')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->where('nk.kode_program_studi', $kode_program_studi)
            ->where('kode_tahun_akademik', $tahun_akademik)
            ->group_by('mak.kode_matakuliah')
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->get()->result();
    }

    public function getAllDosen() {
        return $this->db->get('dosen')->result_object();
    }

    public function deleteKelas($kelas_id) {
        return $this->db->where('kelas_id', $kelas_id)->delete('kelas');
    }

    public function updateSettingKuisioner($setting_id, $data) {
        return $this->db->where('setting_id', $setting_id)->update('setting_kuisioner', $data);
    }

    public function getMatakuliahWithKelas($kode_program_studi, $tahun_akademik) {
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
            ->group_by('mak.id_matakuliah')
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->get()->result();
    }

    public function getAllNamaKelas() {
        return $this->db->get('nama_kelas')->result_object();
    }

    public function getKrsDetailByMatakuliah($kode_tahun_akademik, $matakuliah_id) {
        return $this->db->select('km.kode_krs_detail')
            ->from('kelas')
            ->join('kelas_mahasiswa as km', 'kelas.kelas_id=km.kelas_id')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kelas.id_matakuliah', $matakuliah_id)
            ->get()->result();
    }

    public function getMahasiswaWithoutKelas($kode_tahun_akademik, $matakuliah_id, $kode_krs_detail) {
        return $this->db->select('mah.nim, mah.nama_mahasiswa, kd.kode_krs_detail')
            ->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kd.id_matakuliah', $matakuliah_id)
            ->where_not_in('kd.kode_krs_detail', $kode_krs_detail)
            ->get()->result();
    }

    public function insertKelasMahasiswa($data) {
        return $this->db->insert('kelas_mahasiswa', $data);
    }

    public function getAktivasi($tahun_akademik) {
        return $this->db->select('*')->from('aktivasi')->where('kode_tahun_akademik', $tahun_akademik)->get()->row();
    }

    public function getAktivasiArray($tahun_akademik) {
        return $this->db->select('*')
            ->from('aktivasi')
            ->where('kode_tahun_akademik', $tahun_akademik)
            ->get()
            ->row_array();
    }

    public function insertAktivasi($data) {
        return $this->db->insert('aktivasi', $data);
    }

    public function updateAktivasi($tahun_akademik, $data) {
        return $this->db->where('kode_tahun_akademik', $tahun_akademik)->update('aktivasi', $data);
    }

    public function updateKelasByWhereIn($where_in_field, $where_in_values, $tahun_akademik, $data) {
        $this->db->where_in($where_in_field, $where_in_values);
        $this->db->where('kode_tahun_akademik', $tahun_akademik);
        return $this->db->update('kelas', $data);
    }

    public function getAngkatanTahunAkademik() {
        return $this->db->select('substr(tahun_akademik,3,2) as val,substr(tahun_akademik,1,4) as angkatan')
            ->group_by('tahun_akademik')
            ->get('tahun_akademik')->result();
    }

    public function getKuisionerLayananHeader() {
        return $this->db->select('*,  count(skp.id_bagian) as colspan')
            ->from('soal_kuisioner_pelayanan as skp')
            ->join('bagian', 'bagian.id_bagian=skp.id_bagian')
            ->order_by('skp.id_bagian ASC')
            ->group_by('skp.id_bagian')
            ->get()->result();
    }

    public function getMahasiswaKuisionerLayanan($kode_tahun_akademik, $kode_program_studi) {
        return $this->db->select('kl.*')
            ->from('kuisioner_layanan as kl')
            ->join('mahasiswa as mah', 'mah.nim=kl.nim')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('program_studi_kode', $kode_program_studi)
            ->group_by('kl.nim')
            ->get()->result();
    }

    public function getKuisionerLayananByNim($nim, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('kuisioner_layanan')
            ->where('nim', $nim)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function getProgramStudiByKode($kode_program_studi) {
        return $this->db->where('kode_program_studi', $kode_program_studi)->get('program_studi')->row_object();
    }

    public function getTahunAkademikByKode($kode_tahun_akademik) {
        return $this->db->where('kode_tahun_akademik', $kode_tahun_akademik)->get('tahun_akademik')->row_object();
    }

    public function getKelasByTahunProdi($kode_tahun_akademik, $kode_program_studi) {
        return $this->db->select('kelas.kelas_id, kelas.id_matakuliah, mak.kode_matakuliah, mak.nama_matakuliah, nk.nama_kelas, kelas.kode_program_studi')
            ->from('kelas')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kelas.kode_program_studi', $kode_program_studi)
            ->order_by('mak.kode_matakuliah ASC, nk.nama_kelas ASC')
            ->get()->result_object();
    }

    public function getMahasiswaKuisionerLayananByKelas($kelas_id, $kode_tahun_akademik) {
        $sub = $this->db->select('krs.nim')
            ->from('kelas_mahasiswa as km')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->where('km.kelas_id', $kelas_id)
            ->get()->result();
        $data = [];
        foreach ($sub as $row) {
            $item = $this->db->select('*')
                ->from('kuisioner_layanan')
                ->where('nim', $row->nim)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->get()->result();
            if (count($item) > 0) {
                $data[] = $item;
            }
        }
        return $data;
    }
}
