<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_log_model extends CI_Model {

    protected $table = 'log_audit_aktivitas';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Catat log aktivitas audit.
     *
     * @param string $submodul    Nama submodul (misal: 'mahasiswa', 'kelas', 'kpat', 'petikan', 'nilai')
     * @param string $aksi        Jenis aksi (misal: 'SINKRON_BIODATA', 'UPDATE', dll.)
     * @param string $identitas   Identitas objek (misal: NIM, Kode MK, ID Kelas)
     * @param string $deskripsi   Deskripsi ringkas perubahan
     * @param mixed  $data_sebelum Data sebelum diubah (array/object atau json)
     * @param mixed  $data_sesudah Data setelah diubah (array/object atau json)
     * @return int|bool Insert ID atau false
     */
    public function catat($submodul, $aksi, $identitas, $deskripsi = '', $data_sebelum = null, $data_sesudah = null) {
        $id_user = $this->session->userdata('id');
        $nama_user = $this->session->userdata('nama_login') 
            ?: ($this->session->userdata('username') ?: ($this->session->userdata('nama_lengkap') ?: 'Admin'));

        $insert_data = [
            'submodul'     => (string) $submodul,
            'aksi'         => (string) $aksi,
            'identitas'    => (string) $identitas,
            'deskripsi'    => (string) $deskripsi,
            'data_sebelum' => is_string($data_sebelum) ? $data_sebelum : ($data_sebelum !== null ? json_encode($data_sebelum, JSON_UNESCAPED_UNICODE) : null),
            'data_sesudah' => is_string($data_sesudah) ? $data_sesudah : ($data_sesudah !== null ? json_encode($data_sesudah, JSON_UNESCAPED_UNICODE) : null),
            'id_user'      => $id_user ?: null,
            'nama_user'    => $nama_user,
            'ip_address'   => $this->input->ip_address(),
            'user_agent'   => substr((string) $this->input->user_agent(), 0, 255),
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        $this->db->insert($this->table, $insert_data);
        return $this->db->insert_id();
    }

    /**
     * Ambil data log dengan filter dan limit/offset.
     */
    public function get_logs($filters = [], $limit = 50, $offset = 0) {
        $this->db->from($this->table);

        if (!empty($filters['submodul'])) {
            $this->db->where('submodul', $filters['submodul']);
        }
        if (!empty($filters['aksi'])) {
            $this->db->where('aksi', $filters['aksi']);
        }
        if (!empty($filters['identitas'])) {
            $this->db->like('identitas', $filters['identitas']);
        }
        if (!empty($filters['tgl_mulai'])) {
            $this->db->where('created_at >=', $filters['tgl_mulai'] . ' 00:00:00');
        }
        if (!empty($filters['tgl_selesai'])) {
            $this->db->where('created_at <=', $filters['tgl_selesai'] . ' 23:59:59');
        }

        $this->db->order_by('id', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    /**
     * Hitung total data log untuk pagination.
     */
    public function count_logs($filters = []) {
        $this->db->from($this->table);

        if (!empty($filters['submodul'])) {
            $this->db->where('submodul', $filters['submodul']);
        }
        if (!empty($filters['aksi'])) {
            $this->db->where('aksi', $filters['aksi']);
        }
        if (!empty($filters['identitas'])) {
            $this->db->like('identitas', $filters['identitas']);
        }
        if (!empty($filters['tgl_mulai'])) {
            $this->db->where('created_at >=', $filters['tgl_mulai'] . ' 00:00:00');
        }
        if (!empty($filters['tgl_selesai'])) {
            $this->db->where('created_at <=', $filters['tgl_selesai'] . ' 23:59:59');
        }

        return $this->db->count_all_results();
    }

    /**
     * Ambil log berdasarkan ID untuk detail modal.
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }
}
