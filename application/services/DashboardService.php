<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Statistik ringkas untuk dashboard admin.
 */
class DashboardService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getTahunAkademikAktif() {
        return $this->db->select('*')
            ->from('tahun_akademik')
            ->where('status', 'A')
            ->get()->row_object();
    }

    /**
     * Statistik dashboard untuk satu tahun akademik.
     *
     * Universe: mahasiswa yang terdaftar di status_perkuliahan pada TA tsb.
     *
     * @return array bayar_spp, isi_krs, masuk_kelas, tanpa_kelas
     */
    public function getStatistikDashboard($kode_ta = null) {
        if (!$kode_ta) {
            $ta = $this->getTahunAkademikAktif();
            $kode_ta = $ta ? $ta->kode_tahun_akademik : null;
        }

        if (!$kode_ta) {
            return array('bayar_spp' => 0, 'isi_krs' => 0, 'masuk_kelas' => 0, 'tanpa_kelas' => 0);
        }

        // 1. Sudah bayar SPP (Lunas '1' atau Dispen '2')
        $bayar_spp = $this->db->where('kode_tahun_akademik', $kode_ta)
            ->where_in('pembayaran_spp', array('1', '2'))
            ->count_all_results('status_perkuliahan');

        // 2. Sudah mengisi KRS (punya baris krs pada TA aktif)
        $isi_krs = $this->db->select('COUNT(DISTINCT sp.nim) AS jml', false)
            ->from('status_perkuliahan as sp')
            ->join('krs as k', 'k.nim = sp.nim AND k.kode_tahun_akademik = sp.kode_tahun_akademik')
            ->where('sp.kode_tahun_akademik', $kode_ta)
            ->get()->row()->jml;

        // 4. Sudah masuk kelas: SELURUH matakuliah reguler (status B/U) pada KRS
        //    mahasiswa sudah terpetakan ke kelas_mahasiswa. Bila ada satu saja yang
        //    belum, mahasiswa dianggap belum. (Matakuliah KPAT/status K tidak dihitung
        //    karena distribusinya memakai tabel terpisah.)
        $masuk_kelas = $this->db->query("
            SELECT COUNT(*) AS jml FROM (
                SELECT sp.nim
                FROM status_perkuliahan AS sp
                JOIN krs AS k ON k.nim = sp.nim AND k.kode_tahun_akademik = sp.kode_tahun_akademik
                JOIN krs_detail AS kd ON kd.kode_krs = k.kode_krs AND kd.status IN ('B', 'U')
                LEFT JOIN kelas_mahasiswa AS km ON km.kode_krs_detail = kd.kode_krs_detail
                WHERE sp.kode_tahun_akademik = ?
                GROUP BY sp.nim
                HAVING COUNT(DISTINCT kd.kode_krs_detail) > 0
                   AND COUNT(DISTINCT km.kode_krs_detail) = COUNT(DISTINCT kd.kode_krs_detail)
            ) AS t
        ", array($kode_ta))->row()->jml;

        // 3. Tidak memiliki kelas (sudah isi KRS tapi belum ada di kelas)
        $tanpa_kelas = max(0, (int) $isi_krs - (int) $masuk_kelas);

        return array(
            'bayar_spp'   => (int) $bayar_spp,
            'isi_krs'     => (int) $isi_krs,
            'masuk_kelas' => (int) $masuk_kelas,
            'tanpa_kelas' => (int) $tanpa_kelas,
        );
    }

    /**
     * Rekap per program studi: mahasiswa yang sudah isi KRS tapi belum masuk kelas.
     *
     * @return array of ['kode_program_studi','nama_program_studi','isi_krs','masuk_kelas','tanpa_kelas']
     */
    public function getMahasiswaTanpaKelasPerProdi($kode_ta = null) {
        if (!$kode_ta) {
            $ta = $this->getTahunAkademikAktif();
            $kode_ta = $ta ? $ta->kode_tahun_akademik : null;
        }

        if (!$kode_ta) {
            return array();
        }

        // Sudah isi KRS per program studi
        $isi = $this->db->select('m.program_studi_kode, ps.nama_program_studi, COUNT(DISTINCT sp.nim) AS jml', false)
            ->from('status_perkuliahan as sp')
            ->join('krs as k', 'k.nim = sp.nim AND k.kode_tahun_akademik = sp.kode_tahun_akademik')
            ->join('mahasiswa as m', 'm.nim = sp.nim')
            ->join('program_studi as ps', 'ps.kode_program_studi = m.program_studi_kode')
            ->where('sp.kode_tahun_akademik', $kode_ta)
            ->group_by('m.program_studi_kode')
            ->get()->result();

        // Sudah masuk kelas per program studi: mahasiswa yang SELURUH matakuliah
        // reguler (status B/U) pada KRS-nya sudah terpetakan ke kelas_mahasiswa.
        $kelas = $this->db->query("
            SELECT m.program_studi_kode, COUNT(*) AS jml
            FROM (
                SELECT sp.nim
                FROM status_perkuliahan AS sp
                JOIN krs AS k ON k.nim = sp.nim AND k.kode_tahun_akademik = sp.kode_tahun_akademik
                JOIN krs_detail AS kd ON kd.kode_krs = k.kode_krs AND kd.status IN ('B', 'U')
                LEFT JOIN kelas_mahasiswa AS km ON km.kode_krs_detail = kd.kode_krs_detail
                WHERE sp.kode_tahun_akademik = ?
                GROUP BY sp.nim
                HAVING COUNT(DISTINCT kd.kode_krs_detail) > 0
                   AND COUNT(DISTINCT km.kode_krs_detail) = COUNT(DISTINCT kd.kode_krs_detail)
            ) AS t
            JOIN mahasiswa AS m ON m.nim = t.nim
            GROUP BY m.program_studi_kode
        ", array($kode_ta))->result();

        $masuk = array();
        foreach ($kelas as $r) {
            $masuk[$r->program_studi_kode] = (int) $r->jml;
        }

        $out = array();
        foreach ($isi as $r) {
            $m = isset($masuk[$r->program_studi_kode]) ? $masuk[$r->program_studi_kode] : 0;
            $out[] = array(
                'kode_program_studi' => $r->program_studi_kode,
                'nama_program_studi' => $r->nama_program_studi,
                'isi_krs'            => (int) $r->jml,
                'masuk_kelas'        => $m,
                'tanpa_kelas'        => max(0, (int) $r->jml - $m),
            );
        }

        usort($out, function ($a, $b) {
            return $b['tanpa_kelas'] - $a['tanpa_kelas'];
        });

        return $out;
    }
}
