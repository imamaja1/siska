<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PembimbingKkpService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getBimbinganList() {
        return $this->db->select('nama_dosen,dos.kode_dosen,count(id_pembimbing_kkp) as jumlah_bimbingan')
            ->from('pembimbing_kkp as pk')
            ->join('mahasiswa as mah','mah.nim=pk.nim')
            ->join('dosen as dos','dos.kode_dosen=pk.kode_dosen')
            ->where('mah.status','A')
            ->group_by('dos.kode_dosen')
            ->get()->result();
    }

    public function getExistingPembimbing() {
        return $this->db->select('nim')->from('pembimbing_kkp')->get()->result();
    }

    public function getMahasiswaKkp($kode_mak_kkp, $exis, $kode_tahun_akademik) {
        return $this->db->select('mah.nim,nama_mahasiswa')
            ->from('krs')
            ->join('mahasiswa as mah','krs.nim=mah.nim')
            ->join('krs_detail as kd','krs.kode_krs = kd.kode_krs')
            ->join('matakuliah as mak','kd.id_matakuliah= mak.id_matakuliah')
            ->where_in('mak.kode_matakuliah', $kode_mak_kkp)
            ->where_not_in('mah.nim', $exis)
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }

    public function simpanPembimbing($data) {
        return $this->db->insert('pembimbing_kkp', $data);
    }

    public function getBimbinganByDosen($kode_dosen) {
        return $this->db->select('*,pk.id_pembimbing_kkp')
            ->from('pembimbing_kkp as pk')
            ->join('mahasiswa as mah','pk.nim=mah.nim')
            ->join('nilai_kkp as nk','pk.id_pembimbing_kkp=nk.id_pembimbing_kkp','left')
            ->where('mah.status','A')
            ->where('kode_dosen', $kode_dosen)
            ->get()->result();
    }

    public function getPembimbingById($id) {
        return $this->db->get_where('pembimbing_kkp', array('id_pembimbing_kkp' => $id))->row_object();
    }

    public function updatePembimbing($id, $data) {
        return $this->db->where('id_pembimbing_kkp', $id)->update('pembimbing_kkp', $data);
    }

    public function hapusPembimbing($id) {
        return $this->db->where('id_pembimbing_kkp', $id)->delete('pembimbing_kkp');
    }

    public function pindahPembimbing($id, $kode_dosen) {
        return $this->db->where('id_pembimbing_kkp', $id)->update('pembimbing_kkp', array('kode_dosen' => $kode_dosen));
    }

    public function cariBimbingan($keyword) {
        return $this->db->select('nama_dosen,dos.kode_dosen,count(id_pembimbing_kkp) as jumlah_bimbingan')
            ->from('pembimbing_kkp as pk')
            ->join('mahasiswa as mah','mah.nim=pk.nim')
            ->join('dosen as dos','dos.kode_dosen=pk.kode_dosen')
            ->where('mah.status','A')
            ->group_start()
                ->like('mah.nim', $keyword, 'both')
                ->or_like('mah.nama_mahasiswa', $keyword, 'both')
            ->group_end()
            ->group_by('dos.kode_dosen')
            ->get()->result();
    }

    public function getDataPenilaian($id_pembimbing_kkp) {
        return $this->db->select('lokasi_kkp, bidang_kkp,nik,nama_dosen,nama_mahasiswa,mah.nim,bab_1,bab_2,bab_3,bab_4,bab_5,laporan,kinerja,nilai_akhir')
            ->from('pembimbing_kkp as pk')
            ->join('nilai_kkp as nk','pk.id_pembimbing_kkp=nk.id_pembimbing_kkp')
            ->join('mahasiswa as mah','mah.nim=pk.nim')
            ->join('dosen as dos','dos.kode_dosen=pk.kode_dosen')
            ->where('pk.id_pembimbing_kkp', $id_pembimbing_kkp)
            ->get()->row_object();
    }

    public function getRekapKkp($kode_tahun_akademik) {
        return $this->db->select('mah.nim,nama_mahasiswa,lokasi_kkp, tgl_pelaksanaan, batas_pelaksanaan, telepon, bidang_kkp,alamat')
            ->from('pembimbing_kkp as pk')
            ->join('mahasiswa as mah','mah.nim=pk.nim')
            ->where('mah.status','A')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->result();
    }
}
