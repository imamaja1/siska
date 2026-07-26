<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KeuanganService extends MY_Service {

    public function getMahasiswaAktif() {
        return $this->db->select('nim,nama_mahasiswa')->where('status','A')->get('mahasiswa')->result();
    }

    public function getTahunAkademikList() {
        return $this->db->select('kode_tahun_akademik, tahun_akademik, semester, status')->order_by('kode_tahun_akademik', 'desc')->get('tahun_akademik')->result();
    }

    public function getBlockData() {
        return $this->db->select('*')
                ->from('block')
                ->join('mahasiswa as mah','mah.nim=block.nim')
                ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=block.kode_tahun_akademik')
                ->order_by('block.created_at', 'desc')
                ->get()->result();
    }

    public function getMahasiswaByKeyword($keyword) {
        return $this->db->select('nim, nama_mahasiswa')
                ->like('nama_mahasiswa',"$keyword",'both')
                ->or_like('nim',"$keyword",'both')
                ->limit(20)
                ->get()->result();
    }

    public function insertBlock($data) {
        $this->db->insert('block', $data);
    }

    public function deleteBlock($id) {
        $this->db->where('id', $id)->delete('block');
    }

    public function getTahunAkademikRow($kode_tahun_akademik) {
        return $this->db->where('kode_tahun_akademik', $kode_tahun_akademik)->get('tahun_akademik')->row_object();
    }

    public function getMahasiswaAktifByTA($kode_tahun_akademik) {
        return $this->db->select('mah.nim, nama_mahasiswa, mah.email, nama_program_studi, mah.telepon')
                ->from('krs')
                ->join('mahasiswa as mah','mah.nim=krs.nim')
                ->join('program_studi as ps','ps.kode_program_studi=mah.program_studi_kode')
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('krs.semester', ['K'])
                ->order_by('mah.program_studi_kode')
                ->order_by('mah.nim', 'desc')
                ->group_by('mah.nim')
                ->get()->result();
    }

    public function getRekapPembayaran($kode_tahun_akademik, $kode_program_studi) {
        return $this->db->select('kode_status_perkuliahan,pembayaran_spp, pembayaran_sks, pembayaran_lab, krs.semester,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek)) as teori, sum(sks_praktikum) as praktikum')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_not_in('krs.semester', ['K'])
                ->where_not_in('sp.pembayaran_sks', ['0'])
                ->group_by('mah.nim')
                ->get()->result();
    }

    public function getProgramStudiRow($kode_program_studi) {
        return $this->db->where('kode_program_studi', $kode_program_studi)->get('program_studi')->row_object();
    }

    public function getJenisPembayaranAktif() {
        return $this->db->where('active','1')->get('jenis_pembayaran')->result();
    }

    public function getMahasiswaStatusA() {
        return $this->db->where('status','A')->get('mahasiswa')->result();
    }

    public function getRekening() {
        return $this->db->get('rekening')->result();
    }

    public function insertPembayaran($data) {
        $this->db->insert('pembayaran', $data);
    }

    public function getLastPembayaran() {
        return $this->db->select('*, pem.id as pembayaran_id')
                ->from('pembayaran as pem')
                ->join('mahasiswa as mah','mah.nim=pem.nim')
                ->join('rekening as rek','rek.id=pem.rekening_id')
                ->join('jenis_pembayaran as jp','jp.id=pem.jenis_pembayaran_id')
                ->order_by('pem.created_at','desc')
                ->limit(50)
                ->get()->result();
    }

    public function deletePembayaran($id) {
        $this->db->where('id', $id)->delete('pembayaran');
    }

    public function autocompleteMahasiswa($keyword) {
        return $this->db->select('nim, nama_mahasiswa')
                ->from('mahasiswa')
                ->like('nim',$keyword,'both')
                ->or_like('nama_mahasiswa',$keyword,'both')
                ->limit(6)
                ->get()->result_object();
    }

    public function getHistoryPembayaran($nim) {
        return $this->db->select('mah.nama_mahasiswa,pem.*,jp.*,rek.*, ta.*')
                ->from('pembayaran as pem')
                ->join('jenis_pembayaran as jp','jp.id=pem.jenis_pembayaran_id')
                ->join('rekening as rek','rek.id=pem.rekening_id')
                ->join('tahun_akademik as ta','ta.kode_tahun_akademik=pem.kode_tahun_akademik')
                ->join('mahasiswa as mah','mah.nim=pem.nim')
                ->where('pem.nim', $nim)
                ->get()->result();
    }

    public function updateStatusMahasiswa($nim, $status) {
        $this->db->where('nim', $nim)->update('mahasiswa', array('status' => $status));
    }

    public function getMahasiswaByNim($nim) {
        return $this->db->select('nama_mahasiswa, nim')
                ->from('mahasiswa')
                ->where('nim', $nim)
                ->get()->row_object();
    }

    public function getStatusPerkuliahanByKode($kode_status_perkuliahan) {
        return $this->db->where('kode_status_perkuliahan', $kode_status_perkuliahan)->get('status_perkuliahan')->row_object();
    }

    public function getNimFromStatusPerkuliahan($id) {
        return $this->db->select('nim')
            ->from('status_perkuliahan')
            ->where('kode_status_perkuliahan', $id)
            ->get()->row_object();
    }

    public function updateStatusPerkuliahan($id, $data) {
        $this->db->where('kode_status_perkuliahan', $id)->update('status_perkuliahan', $data);
    }

    public function getNamaKurikulumByAngkatan($angkatan, $kode_prodi) {
        return $this->db->select('nk.kode_nama_kurikulum')
                ->from('nama_kurikulum as nk')
                ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                ->where('substr(angkatan,3,2)', $angkatan)
                ->where('kode_program_studi', $kode_prodi)
                ->where('ekstensi', 'N')
                ->get()->row_object();
    }

    public function getTugasAkhirId($kode_nama_kurikulum) {
        return $this->db->select('mak.id_matakuliah')
                ->from('kurikulum as kur')
                ->join('matakuliah as mak', 'mak.id_matakuliah=kur.id_matakuliah')
                ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
                ->where("(nama_matakuliah like '%skripsi%' or nama_matakuliah like '%tugas akhir%' or nama_matakuliah like '%tugas ahir%')")
                ->get()->row_object();
    }

    public function getMhsTugasAkhirOld($kode_tahun_akademik, $tugas_akhir, $kode_jurusan, $kode_jenjang, $angkatan) {
        return $this->db->select('*, count(krs.kode_krs) as jml')
                    ->from('krs')
                    ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                    ->where('id_matakuliah', $tugas_akhir)
                    ->where('kode_tahun_akademik', $kode_tahun_akademik)
                    ->where('substring(nim,3,2)', $kode_jurusan)
                    ->where('substring(nim,5,1)', $kode_jenjang)
                    ->where('substring(nim,1,2)', $angkatan)
                    ->group_by('nim')
                    ->get()->result();
    }

    public function getProgramStudiByKode($kode_prodi) {
        return $this->db->select('*')
                    ->from('program_studi as ps')
                    ->where('kode_program_studi', $kode_prodi)
                    ->get()->row_object();
    }

    public function getMhsTugasAkhirNew($kode_tahun_akademik, $tugas_akhir, $kode_fakultas, $kode_prodi_univ, $angkatan) {
        return $this->db->select('*, count(krs.kode_krs) as jml')
                    ->from('krs')
                    ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                    ->where('id_matakuliah', $tugas_akhir)
                    ->where('kode_tahun_akademik', $kode_tahun_akademik)
                    ->where('substring(nim,3,2)', $kode_fakultas)
                    ->where('substring(nim,5,2)', $kode_prodi_univ)
                    ->where('substring(nim,1,2)', $angkatan)
                    ->group_by('nim')
                    ->get()->result();
    }

    public function getStatusPerkuliahanCountOld($kode_tahun_akademik, $angkatan, $kode_jurusan, $kode_jenjang, $status) {
        return $this->db->select('nim, status_perkuliahan, count(kode_status_perkuliahan) as jumlah')
                        ->from('status_perkuliahan')
                        ->where('kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('substring(nim,1,2)', $angkatan)
                        ->where('substring(nim,3,2)', $kode_jurusan)
                        ->where('substring(nim,5,1)', $kode_jenjang)
                        ->where('status_perkuliahan', $status)
                        ->group_by('status_perkuliahan')
                        ->get()->row_object();
    }

    public function getStatusPerkuliahanCountNew($kode_tahun_akademik, $angkatan, $kode_fakultas, $kode_prodi_univ, $status) {
        return $this->db->select('nim, status_perkuliahan, count(kode_status_perkuliahan) as jumlah')
                        ->from('status_perkuliahan')
                        ->where('kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('substring(nim,1,2)', $angkatan)
                        ->where('substring(nim,3,2)', $kode_fakultas)
                        ->where('substring(nim,5,2)', $kode_prodi_univ)
                        ->where('status_perkuliahan', $status)
                        ->group_by('status_perkuliahan')
                        ->get()->row_object();
    }

    public function getRekapSksData($kode_tahun_akademik, $kkp_skripsi) {
        return $this->db->select('kode_status_perkuliahan,pembayaran_spp, pembayaran_sks, pembayaran_lab, krs.semester,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek)) as teori, sum(sks_praktikum) as praktikum')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('kd.status', ['K'])
                ->where_not_in('krs.semester', ['K'])
                ->where_not_in('mak.kode_matakuliah', $kkp_skripsi)
                ->group_by('mah.nim')
                ->get()->result();
    }

    public function getRekapSksSkripsiData($kode_tahun_akademik, $kkp_skripsi) {
        return $this->db->select('pengumpulan_krs,kode_status_perkuliahan,pembayaran_spp, pembayaran_sks, pembayaran_lab, krs.semester,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek)) as teori, sum(sks_praktikum) as praktikum')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('kd.status', ['K'])
                ->where_not_in('krs.semester', ['K'])
                ->where_in('mak.kode_matakuliah', $kkp_skripsi)
                ->group_by('mah.nim')
                ->get()->result();
    }

    public function updatePembayaranStatus($kode_status_perkuliahan, $data) {
        return $this->db->where('kode_status_perkuliahan', $kode_status_perkuliahan)
                ->update('status_perkuliahan', $data);
    }

    public function getTahunAkademikByKode($kode_tahun_akademik) {
        return $this->db->get_where('tahun_akademik', array('kode_tahun_akademik'=>$kode_tahun_akademik))->row();
    }
}
