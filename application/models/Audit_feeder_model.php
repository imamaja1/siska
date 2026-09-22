<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_feeder_model extends CI_Model {

    public function getNilaiSiskaByNim($nim, $kode_tahun_akademik)
    {
        $sql = "SELECT m.nim, m.nama_mahasiswa, mk.kode_matakuliah, mk.nama_matakuliah,
                       (SELECT nk.nama_kelas
                          FROM kelas_mahasiswa km
                          JOIN kelas kl ON kl.kelas_id = km.kelas_id
                          JOIN nama_kelas nk ON nk.nama_kelas_id = kl.nama_kelas_id
                         WHERE km.kode_krs_detail = krd.kode_krs_detail
                         LIMIT 1) AS nama_kelas,
                       kd.nilai_akhir
                  FROM krs k
                  JOIN krs_detail krd ON krd.kode_krs = k.kode_krs
                  LEFT JOIN khs_detail kd ON kd.kode_krs_detail = krd.kode_krs_detail
                  JOIN mahasiswa m ON m.nim = k.nim
                  JOIN matakuliah mk ON mk.id_matakuliah = krd.id_matakuliah
                 WHERE k.nim = ? AND k.kode_tahun_akademik = ?
                 ORDER BY mk.nama_matakuliah";

        return $this->db->query($sql, [$nim, $kode_tahun_akademik])->result_object();
    }

    public function getNilaiSiskaByKelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nama_kelas_id = NULL)
    {
        $sql = "SELECT m.nim, m.nama_mahasiswa, mk.kode_matakuliah, mk.nama_matakuliah, nk.nama_kelas, kd.nilai_akhir
                  FROM kelas kl
                  JOIN kelas_mahasiswa km ON km.kelas_id = kl.kelas_id
                  JOIN krs_detail krd ON krd.kode_krs_detail = km.kode_krs_detail
                  JOIN krs k ON k.kode_krs = krd.kode_krs
                  JOIN mahasiswa m ON m.nim = k.nim
                  JOIN matakuliah mk ON mk.id_matakuliah = kl.id_matakuliah
                  JOIN nama_kelas nk ON nk.nama_kelas_id = kl.nama_kelas_id
                  LEFT JOIN khs_detail kd ON kd.kode_krs_detail = krd.kode_krs_detail
                 WHERE kl.kode_tahun_akademik = ?
                   AND kl.kode_program_studi = ?
                   AND kl.id_matakuliah = ?";

        $params = [$kode_tahun_akademik, $kode_program_studi, $id_matakuliah];

        if ($nama_kelas_id !== NULL && $nama_kelas_id !== '') {
            $sql .= " AND kl.nama_kelas_id = ?";
            $params[] = $nama_kelas_id;
        }

        $sql .= " ORDER BY nk.nama_kelas, m.nim";

        return $this->db->query($sql, $params)->result_object();
    }

    public function getMatakuliahByProdi($kode_program_studi)
    {
        return $this->db->select('id_matakuliah, kode_matakuliah, nama_matakuliah')
            ->from('matakuliah')
            ->where('kode_program_studi', $kode_program_studi)
            ->order_by('nama_matakuliah', 'ASC')
            ->get()->result_object();
    }

    public function getKelasByMatakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        return $this->db->select('kl.kelas_id, kl.nama_kelas_id, nk.nama_kelas')
            ->from('kelas kl')
            ->join('nama_kelas nk', 'nk.nama_kelas_id = kl.nama_kelas_id')
            ->where('kl.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kl.kode_program_studi', $kode_program_studi)
            ->where('kl.id_matakuliah', $id_matakuliah)
            ->order_by('nk.nama_kelas', 'ASC')
            ->get()->result_object();
    }
}
