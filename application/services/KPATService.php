<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KPATService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    // --- Kelas ---

    public function getKelasMahasiswaDetail($matakuliah_id, $kode_tahun_akademik) {
        return $this->db->select('km.kode_krs_detail')->from('kelas_kpat')
            ->join('kelas_mahasiswa_kpat as km', 'kelas_kpat.kelas_id=km.kelas_id')
            ->where('kelas_kpat.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kelas_kpat.id_matakuliah', $matakuliah_id)
            ->get()->result();
    }

    public function getSemuaMahasiswaKpat($kode_tahun_akademik, $matakuliah_id) {
        $this->db->select('*')->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kd.id_matakuliah', $matakuliah_id);
        return $this->db;
    }

    public function getNamaKelas() {
        return $this->db->get('nama_kelas')->result_object();
    }

    public function hapusKelasKpat($kelas_id) {
        return $this->db->where('kelas_id', $kelas_id)->delete('kelas_kpat');
    }

    public function getAllDosen() {
        return $this->db->get('dosen')->result_object();
    }

    public function getMatakuliahKpat($ta, $prodi) {
        return $this->db->select('mak.id_matakuliah, mak.kode_matakuliah, mak.id_matakuliah, nama_matakuliah, kelas_kpat.kelas_id')
            ->from('nama_kurikulum as nk')
            ->join('kurikulum as kur', 'nk.kode_nama_kurikulum=kur.kode_nama_kurikulum')
            ->join('krs_detail as kd', 'kd.id_matakuliah=kur.id_matakuliah')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('kelas_kpat', 'kelas_kpat.id_matakuliah=mak.id_matakuliah AND kelas_kpat.kode_tahun_akademik=krs.kode_tahun_akademik', 'left')
            ->where('nk.kode_program_studi', $prodi)
            ->where('krs.kode_tahun_akademik', $ta)
            ->where_in('kd.status', ['K'])
            ->group_by('mak.id_matakuliah')
            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
            ->get()->result();
    }

    public function tambahMahasiswaKpat($data) {
        return $this->db->insert('kelas_mahasiswa_kpat', $data);
    }

    // --- Khs ---

    public function getKrsByKode($kode_krs) {
        return $this->db->where('kode_krs', $kode_krs)->get('krs')->row_object();
    }

    // --- Krs ---

    public function getKrsByKodeKrs($kode_krs) {
        return $this->db->where(array('kode_krs' => $kode_krs))->get('krs')->row_object();
    }

    public function updateKhsDetailRaw($kode_krs_detail, $nilai_harian, $nilai_uts, $nilai_uas, $nilai_akhir, $tidak_berhak) {
        $row = $this->db->where('kode_krs_detail', $kode_krs_detail)->get('khs_detail')->row();
        $this->db->set('nilai_harian', $nilai_harian)
            ->set('nilai_uts', $nilai_uts)
            ->set('nilai_uas', $nilai_uas)
            ->set('nilai_akhir', $nilai_akhir)
            ->set('tidak_berhak', $tidak_berhak)
            ->where('kode_krs_detail', $kode_krs_detail)
            ->update('khs_detail');
        $data = array('nilai_harian' => $nilai_harian, 'nilai_uts' => $nilai_uts, 'nilai_uas' => $nilai_uas, 'nilai_akhir' => $nilai_akhir);
        if ($row) {
            $lama = array();
            $baru = array();
            foreach ($data as $field => $b) {
                $l = isset($row->$field) ? $row->$field : null;
                if ($l != $b) {
                    $lama[$field] = $l;
                    $baru[$field] = $b;
                }
            }
            if (!empty($lama)) {
                log_aktivitas_nilai('update', array_keys($lama), $lama, $baru, 'kpat', null, $kode_krs_detail);
            }
        }
    }

    public function deleteKrsDetail($kode_krs_detail) {
        $row = $this->db->where('kode_krs_detail', $kode_krs_detail)->get('khs_detail')->row();
        if ($row) {
            log_aktivitas_nilai('delete', 'nilai_harian,nilai_uts,nilai_uas,nilai_akhir', $this->nilaiJson($row), null, 'kpat', null, $kode_krs_detail);
        }
        $this->db->where('kode_krs_detail', $kode_krs_detail)->delete('krs_detail');
    }

    public function deleteKrsKpat($kode_krs) {
        $krs_details = $this->db->select('kode_krs_detail')->where('kode_krs', $kode_krs)->get('krs_detail')->result();
        foreach ($krs_details as $kd) {
            $this->deleteKhsDetail($kd->kode_krs_detail);
            $this->deleteKrsDetail($kd->kode_krs_detail);
        }
        $this->db->where('kode_krs', $kode_krs)->delete('khs');
        log_aktivitas_nilai('delete', 'kode_krs', $kode_krs, null, 'krs', null, null, $kode_krs);
        $this->db->where('kode_krs', $kode_krs)->delete('krs');
    }

    public function deleteKhsDetail($kode_krs_detail) {
        $row = $this->db->where('kode_krs_detail', $kode_krs_detail)->get('khs_detail')->row();
        if ($row) {
            log_aktivitas_nilai('delete', 'nilai_harian,nilai_uts,nilai_uas,nilai_akhir', $this->nilaiJson($row), null, 'kpat', null, $kode_krs_detail);
        }
        $this->db->where('kode_krs_detail', $kode_krs_detail)->delete('khs_detail');
    }

    public function restoreKhsDetail($kode_khs_detail) {
        $this->db->set('deleted', 0)->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail');
        log_aktivitas_nilai('restore', 'deleted', '1', '0', 'kpat', $kode_khs_detail);
    }

    private function nilaiJson($row) {
        return array(
            'nilai_harian' => isset($row->nilai_harian) ? $row->nilai_harian : null,
            'nilai_uts' => isset($row->nilai_uts) ? $row->nilai_uts : null,
            'nilai_uas' => isset($row->nilai_uas) ? $row->nilai_uas : null,
            'nilai_akhir' => isset($row->nilai_akhir) ? $row->nilai_akhir : null,
        );
    }

    // --- Nilai ---

    public function getMatakuliahByProdiTa($kode_program_studi, $kode_tahun_akademik) {
        return $this->db->select('mak.id_matakuliah, nama_matakuliah, mak.kode_matakuliah')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('mak.kode_program_studi', $kode_program_studi)
            ->where('kd.status','K')
            ->group_by('kd.id_matakuliah')
            ->get()->result_object();
    }

    public function updateNilaiKhsDetail($kode_khs_detail, $field, $value) {
        $allowed = ['nilai_harian', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'tidak_berhak', 'grade', 'deleted'];
        if (!in_array($field, $allowed)) {
            return;
        }
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $lama = $row && isset($row->$field) ? $row->$field : null;
        $this->db->set($field, $value)->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail');
        if ($lama != $value) {
            log_aktivitas_nilai('update', $field, array($field => $lama), array($field => $value), 'kpat', $kode_khs_detail);
        }
    }

    public function softDeleteKhsDetail($kode_khs_detail) {
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $this->db->set('deleted', 1)->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail');
        if ($row) {
            log_aktivitas_nilai('soft_delete', 'nilai_harian,nilai_uts,nilai_uas,nilai_akhir', $this->nilaiJson($row), null, 'kpat', $kode_khs_detail);
        }
    }

    public function restoreKhsDetailNilai($kode_khs_detail) {
        $this->db->set('deleted', 0)->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail');
        log_aktivitas_nilai('restore', 'deleted', '1', '0', 'kpat', $kode_khs_detail);
    }
}
