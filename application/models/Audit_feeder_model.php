<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_feeder_model extends CI_Model {

    public function getNilaiSiskaByNim($nim, $kode_tahun_akademik)
    {
        $sql = "SELECT k.nim, m.nama_mahasiswa, mk.kode_matakuliah, mk.nama_matakuliah,
                       (mk.sks_teori + mk.sks_praktek + mk.sks_praktikum) AS sks,
                       (SELECT nk.nama_kelas
                          FROM kelas_mahasiswa km
                          JOIN kelas kl ON kl.kelas_id = km.kelas_id
                          JOIN nama_kelas nk ON nk.nama_kelas_id = kl.nama_kelas_id
                         WHERE km.kode_krs_detail = krd.kode_krs_detail
                         LIMIT 1) AS nama_kelas,
                       k.semester, MAX(kd.nilai_akhir) AS nilai_akhir
                  FROM krs k
                  JOIN krs_detail krd ON krd.kode_krs = k.kode_krs
                  LEFT JOIN khs_detail kd ON kd.kode_krs_detail = krd.kode_krs_detail
                  LEFT JOIN mahasiswa m ON m.nim = k.nim
                  JOIN matakuliah mk ON mk.id_matakuliah = krd.id_matakuliah
                 WHERE k.nim = ? AND k.kode_tahun_akademik = ?
                 GROUP BY krd.id_matakuliah
                 ORDER BY mk.nama_matakuliah";

        return $this->db->query($sql, [$nim, $kode_tahun_akademik])->result_object();
    }

    public function getNilaiSiskaByKelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nama_kelas_id = NULL)
    {
        $sql = "SELECT m.nim, m.nama_mahasiswa, mk.kode_matakuliah, mk.nama_matakuliah,
                       (mk.sks_teori + mk.sks_praktek + mk.sks_praktikum) AS sks,
                       (SELECT nk.nama_kelas
                          FROM kelas_mahasiswa km
                          JOIN kelas kl2 ON kl2.kelas_id = km.kelas_id
                          JOIN nama_kelas nk ON nk.nama_kelas_id = kl2.nama_kelas_id
                         WHERE km.kode_krs_detail = krd.kode_krs_detail
                         LIMIT 1) AS nama_kelas,
                       k.semester, MAX(kd.nilai_akhir) AS nilai_akhir
                  FROM krs k
                  JOIN krs_detail krd ON krd.kode_krs = k.kode_krs
                  JOIN mahasiswa m ON m.nim = k.nim
                  JOIN matakuliah mk ON mk.id_matakuliah = krd.id_matakuliah
                  LEFT JOIN khs_detail kd ON kd.kode_krs_detail = krd.kode_krs_detail
                 WHERE k.kode_tahun_akademik = ?
                   AND m.program_studi_kode = ?
                   AND krd.id_matakuliah = ?";

        $params = [$kode_tahun_akademik, $kode_program_studi, $id_matakuliah];

        if ($nama_kelas_id !== NULL && $nama_kelas_id !== '') {
            $sql .= " AND EXISTS (
                        SELECT 1
                          FROM kelas_mahasiswa km2
                          JOIN kelas kl3 ON kl3.kelas_id = km2.kelas_id
                         WHERE km2.kode_krs_detail = krd.kode_krs_detail
                           AND kl3.nama_kelas_id = ?
                      )";
            $params[] = $nama_kelas_id;
        }

        $sql .= " GROUP BY m.nim, krd.id_matakuliah";
        $sql .= " ORDER BY m.nim";

        return $this->db->query($sql, $params)->result_object();
    }

    public function getMatakuliahByProdiTa($kode_tahun_akademik, $kode_program_studi)
    {
        return $this->db->distinct()
            ->select('mk.id_matakuliah, mk.kode_matakuliah, mk.nama_matakuliah')
            ->from('krs k')
            ->join('krs_detail krd', 'krd.kode_krs = k.kode_krs')
            ->join('mahasiswa m', 'm.nim = k.nim')
            ->join('matakuliah mk', 'mk.id_matakuliah = krd.id_matakuliah')
            ->where('k.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('m.program_studi_kode', $kode_program_studi)
            ->order_by('mk.nama_matakuliah', 'ASC')
            ->get()->result_object();
    }

    public function getMatakuliahById($id_matakuliah)
    {
        return $this->db->select('id_matakuliah, kode_matakuliah, nama_matakuliah')
            ->from('matakuliah')
            ->where('id_matakuliah', $id_matakuliah)
            ->get()->row_object();
    }

    public function getNamaKelasById($nama_kelas_id)
    {
        $row = $this->db->select('nama_kelas')
            ->from('nama_kelas')
            ->where('nama_kelas_id', $nama_kelas_id)
            ->get()->row_object();

        return $row ? $row->nama_kelas : '';
    }

    public function getProdiNameByKode($kode_program_studi)
    {
        $row = $this->db->select('nama_program_studi')
            ->from('program_studi')
            ->where('kode_program_studi', $kode_program_studi)
            ->get()->row_object();

        return $row ? $row->nama_program_studi : '';
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

    public function getTahunAkademikFrom($start_tahun_akademik)
    {
        return $this->db->select('kode_tahun_akademik, tahun_akademik, semester')
            ->from('tahun_akademik')
            ->where('tahun_akademik >=', $start_tahun_akademik)
            ->order_by('kode_tahun_akademik', 'ASC')
            ->get()->result_object();
    }

    public function getKhsDanDummyByNimMatakuliah($nim, $kode_tahun_akademik, $kode_matakuliah)
    {
        $khs = $this->db->select('khd.kode_khs_detail, k.semester, khd.nilai_harian, khd.nilai_uts, khd.nilai_uas, khd.nilai_akhir, spd.grade AS nilai_huruf')
            ->from('krs k')
            ->join('krs_detail kd', 'kd.kode_krs = k.kode_krs')
            ->join('matakuliah mk', 'mk.id_matakuliah = kd.id_matakuliah')
            ->join('khs_detail khd', 'khd.kode_krs_detail = kd.kode_krs_detail')
            ->join('sistem_penilaian_detail spd', 'khd.nilai_akhir BETWEEN spd.nilai_minimum AND spd.nilai_maksimum AND spd.kode_sistem_penilaian = 1', 'left')
            ->where('k.nim', $nim)
            ->where('k.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('mk.kode_matakuliah', $kode_matakuliah)
            ->order_by('khd.kode_khs_detail', 'ASC')
            ->get()->result_object();

        // Angkatan di atas 24 hanya dicek pada KHS (tanpa dummy).
        $angkatan  = (int) substr(trim((string) $nim), 0, 2);
        $hanya_khs = ($angkatan > 24);

        $dummy = [];
        $dummy_nilai = [];

        if (!$hanya_khs) {
            foreach ($khs as $row) {
                $rows = $this->db->select('dun.level, dun.harian, dun.uts, dun.uas, dun.na, dun.ket, spd.grade AS nilai_huruf')
                    ->from('dummy_update_nilai as dun')
                    ->join('sistem_penilaian_detail spd', 'dun.na BETWEEN spd.nilai_minimum AND spd.nilai_maksimum AND spd.kode_sistem_penilaian = 1', 'left')
                    ->where('dun.kode_khs_detail', $row->kode_khs_detail)
                    ->order_by('dun.level', 'ASC')
                    ->get()->result_object();

                foreach ($rows as $d) {
                    $dummy[] = $d;
                }

                $rows2 = $this->db->select('dn.dummy_harian, dn.dummy_uts, dn.dummy_uas, dn.dummy_na, spd.grade AS nilai_huruf')
                    ->from('dummy_nilai as dn')
                    ->join('sistem_penilaian_detail spd', 'dn.dummy_na BETWEEN spd.nilai_minimum AND spd.nilai_maksimum AND spd.kode_sistem_penilaian = 1', 'left')
                    ->where('dn.kode_khs_detail', $row->kode_khs_detail)
                    ->get()->result_object();

                foreach ($rows2 as $d2) {
                    $dummy_nilai[] = $d2;
                }
            }
        }

        return ['khs' => $khs, 'dummy' => $dummy, 'dummy_nilai' => $dummy_nilai, 'hanya_khs' => $hanya_khs];
    }
}
