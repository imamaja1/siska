<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_periode_krs extends CI_Model {

    public $table = 'periode_krs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Ambil data periode KRS berdasarkan kode_tahun_akademik.
     */
    public function get_by_ta($kode_tahun_akademik) {
        if (empty($kode_tahun_akademik)) {
            return null;
        }
        return $this->db->where('kode_tahun_akademik', (int) $kode_tahun_akademik)
            ->get($this->table)
            ->row_object();
    }

    /**
     * Simpan atau perbarui data periode KRS berdasarkan kode_tahun_akademik.
     */
    public function upsert_by_ta($kode_tahun_akademik, $data) {
        $kode_ta = (int) $kode_tahun_akademik;
        if ($kode_ta <= 0) {
            return false;
        }

        $existing = $this->get_by_ta($kode_ta);
        if ($existing) {
            return $this->db->where('kode_tahun_akademik', $kode_ta)
                ->update($this->table, $data);
        }

        $data['kode_tahun_akademik'] = $kode_ta;
        return $this->db->insert($this->table, $data);
    }

    /**
     * Ubah status aktif/nonaktif KRS pada tahun akademik tertentu.
     */
    public function ubah_status($kode_tahun_akademik, $status, $updated_by = null) {
        $status_clean = ($status === 'A') ? 'A' : 'N';
        $data = array(
            'status_krs' => $status_clean,
            'updated_by' => $updated_by,
        );
        return $this->upsert_by_ta($kode_tahun_akademik, $data);
    }

    /**
     * Evaluasi apakah pengisian/perubahan KRS pada tahun akademik aktif sedang dibuka.
     *
     * @return array{status: bool, reason: string, msg: string, periode: object|null}
     */
    public function cek_akses_krs($kode_tahun_akademik) {
        if (empty($kode_tahun_akademik)) {
            return array(
                'status'  => false,
                'reason'  => 'no_ta',
                'msg'     => 'Tahun akademik aktif belum ditentukan.',
                'periode' => null,
            );
        }

        $periode = $this->get_by_ta($kode_tahun_akademik);
        if (!$periode || $periode->status_krs !== 'A') {
            return array(
                'status'  => false,
                'reason'  => 'nonaktif',
                'msg'     => 'Pengisian dan perubahan KRS pada semester ini sedang dinonaktifkan oleh Akademik.',
                'periode' => $periode,
            );
        }

        $today = date('Y-m-d');
        $tgl_awal = (!empty($periode->tgl_awal_krs) && $periode->tgl_awal_krs !== '0000-00-00')
            ? $periode->tgl_awal_krs
            : null;
        $tgl_batas = (!empty($periode->tgl_batas_krs) && $periode->tgl_batas_krs !== '0000-00-00')
            ? $periode->tgl_batas_krs
            : null;

        if ($tgl_awal !== null && $today < $tgl_awal) {
            $tgl_awal_indo = function_exists('tgl_indo') ? tgl_indo($tgl_awal) : $tgl_awal;
            $tgl_batas_indo = ($tgl_batas !== null)
                ? (function_exists('tgl_indo') ? tgl_indo($tgl_batas) : $tgl_batas)
                : null;

            $msg = 'Periode pengisian KRS belum dimulai. Jadwal pengisian KRS dibuka mulai tanggal <b>' . $tgl_awal_indo . '</b>'
                . ($tgl_batas_indo !== null ? ' sampai dengan <b>' . $tgl_batas_indo . '</b>.' : '.');

            return array(
                'status'  => false,
                'reason'  => 'belum_mulai',
                'msg'     => $msg,
                'periode' => $periode,
            );
        }

        if ($tgl_batas !== null && $today > $tgl_batas) {
            $tgl_batas_indo = function_exists('tgl_indo') ? tgl_indo($tgl_batas) : $tgl_batas;
            return array(
                'status'  => false,
                'reason'  => 'lewat_batas',
                'msg'     => 'Batas waktu pengisian dan perubahan KRS telah berakhir pada tanggal <b>' . $tgl_batas_indo . '</b>.',
                'periode' => $periode,
            );
        }

        return array(
            'status'  => true,
            'reason'  => 'aktif',
            'msg'     => 'Periode KRS aktif.',
            'periode' => $periode,
        );
    }
}
