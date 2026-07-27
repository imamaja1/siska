<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CekService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getDummyUpdateNilaiByTa($kode_tahun_akademik) {
        return $this->db->select('dummy_update_nilai.*')->from('kelas')
            ->join('dummy_update_nilai','dummy_update_nilai.kelas_id = kelas.kelas_id')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kode_khs_detail !=', '0')
            ->order_by('dummy_update_nilai.kode_khs_detail')
            ->get()->result_object();
    }

    public function getKhsDetailByTa($kode_tahun_akademik) {
        return $this->db->select('khs_detail.*')->from('kelas')
            ->join('dummy_update_nilai','dummy_update_nilai.kelas_id = kelas.kelas_id')
            ->join('khs_detail','khs_detail.kode_khs_detail = dummy_update_nilai.kode_khs_detail','left')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('dummy_update_nilai.kode_khs_detail !=', '0')
            ->order_by('dummy_update_nilai.kode_khs_detail')
            ->get()->result_object();
    }

    public function getAuditNilai($kode_tahun_akademik, $kode_program_studi, $filter_angkatan = true) {
        $sql = "SELECT dun.kode_khs_detail, dun.na, dun.level,
                       khd.nilai_akhir, mah.nim, mah.nama_mahasiswa,
                       mak.kode_matakuliah, mak.nama_matakuliah, nk.nama_kelas,
                        CASE WHEN dun.na != khd.nilai_akhir OR dun.na IS NULL OR khd.nilai_akhir IS NULL THEN 'Tidak Sinkron' ELSE 'Sinkron' END as status
                FROM dummy_update_nilai dun
                JOIN (
                    SELECT kode_khs_detail, MAX(level) as max_level
                    FROM dummy_update_nilai
                    WHERE kode_khs_detail != '0'
                    GROUP BY kode_khs_detail
                ) latest ON latest.kode_khs_detail = dun.kode_khs_detail AND latest.max_level = dun.level
                JOIN khs_detail khd ON khd.kode_khs_detail = dun.kode_khs_detail
                JOIN krs_detail kd ON kd.kode_krs_detail = khd.kode_krs_detail
                JOIN krs ON krs.kode_krs = kd.kode_krs
                JOIN mahasiswa mah ON mah.nim = krs.nim
                JOIN kelas ON kelas.kelas_id = dun.kelas_id
                JOIN matakuliah mak ON mak.id_matakuliah = kelas.id_matakuliah
                JOIN nama_kelas nk ON nk.nama_kelas_id = kelas.nama_kelas_id
                WHERE kelas.kode_tahun_akademik = ? AND kelas.kode_program_studi = ?";
        if ($filter_angkatan) {
            $sql .= " AND EXISTS (SELECT 1 FROM mahasiswa m2 WHERE m2.nim = mah.nim AND LEFT(m2.nim, 2) <= '24')";
        }
        $sql .= " ORDER BY CASE WHEN dun.na IS NULL OR khd.nilai_akhir IS NULL THEN 2 WHEN dun.na < khd.nilai_akhir THEN 0 WHEN dun.na > khd.nilai_akhir THEN 1 ELSE 3 END, mak.nama_matakuliah, nk.nama_kelas, mah.nim";
        return $this->db->query($sql, [$kode_tahun_akademik, $kode_program_studi])->result_object();
    }
}
