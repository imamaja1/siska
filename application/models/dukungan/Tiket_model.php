<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tiket_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==========================================
    // KATEGORI & FORM BUILDER DINAMIS
    // ==========================================

    public function get_kategori($active_only = true)
    {
        $this->db->select('*')->from('tiket_kategori');
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        $this->db->order_by('nama_kategori', 'ASC');
        return $this->db->get()->result_object();
    }

    public function get_kategori_by_id($id)
    {
        return $this->db->where('id', $id)->get('tiket_kategori')->row_object();
    }

    public function simpan_kategori($data, $id = null)
    {
        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->update('tiket_kategori', $data);
            return $id;
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->insert('tiket_kategori', $data);
            return $this->db->insert_id();
        }
    }

    public function toggle_kategori($id)
    {
        $row = $this->get_kategori_by_id($id);
        if ($row) {
            $new_status = $row->is_active ? 0 : 1;
            $this->db->where('id', $id)->update('tiket_kategori', [
                'is_active' => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $new_status;
        }
        return false;
    }

    // ==========================================
    // GENERATE NOMOR TIKET
    // Format: [PREFIX]-[YYYYMM]-[URUT]
    // Contoh: BUG-202610-0001, REQ-202610-0002
    // ==========================================

    public function generate_nomor_tiket($prefix)
    {
        $prefix = strtoupper(trim($prefix));
        $ym = date('Ym');
        $pola = $prefix . '-' . $ym . '-%';

        $last = $this->db->select('nomor_tiket')
            ->from('tiket')
            ->like('nomor_tiket', $pola, 'none')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()->row_object();

        $urutan = 1;
        if ($last && !empty($last->nomor_tiket)) {
            $parts = explode('-', $last->nomor_tiket);
            $last_num = end($parts);
            $urutan = intval($last_num) + 1;
        }

        return sprintf("%s-%s-%04d", $prefix, $ym, $urutan);
    }

    // ==========================================
    // MANAJEMEN TIKET
    // ==========================================

    public function buat_tiket($data_tiket, $riwayat_keterangan = 'Tiket dibuat')
    {
        $data_tiket['created_at'] = date('Y-m-d H:i:s');
        $data_tiket['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('tiket', $data_tiket);
        $tiket_id = $this->db->insert_id();

        // Catat riwayat status awal
        $this->db->insert('tiket_riwayat_status', [
            'tiket_id' => $tiket_id,
            'status_lama' => null,
            'status_baru' => $data_tiket['status'],
            'diubah_oleh_id' => $data_tiket['user_id'],
            'diubah_oleh_nama' => $data_tiket['user_nama'],
            'keterangan' => $riwayat_keterangan,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $tiket_id;
    }

    public function get_tiket_list($filter = [], $limit = 50, $offset = 0)
    {
        $this->db->select('t.*, k.nama_kategori, k.kode_prefix, k.tipe_alur')
            ->from('tiket as t')
            ->join('tiket_kategori as k', 'k.id = t.kategori_id', 'left');

        if (!empty($filter['status']) && $filter['status'] !== 'all') {
            $this->db->where('t.status', $filter['status']);
        }
        if (!empty($filter['kategori_id']) && $filter['kategori_id'] !== 'all') {
            $this->db->where('t.kategori_id', $filter['kategori_id']);
        }
        if (!empty($filter['urgensi']) && $filter['urgensi'] !== 'all') {
            $this->db->where('t.urgensi', $filter['urgensi']);
        }
        if (!empty($filter['user_type']) && $filter['user_type'] !== 'all') {
            $this->db->where('t.user_type', $filter['user_type']);
        }
        if (!empty($filter['user_id'])) {
            $this->db->where('t.user_id', $filter['user_id']);
        }
        if (!empty($filter['keyword'])) {
            $kw = $filter['keyword'];
            $this->db->group_start()
                ->like('t.nomor_tiket', $kw)
                ->or_like('t.judul', $kw)
                ->or_like('t.user_nama', $kw)
                ->or_like('t.deskripsi', $kw)
            ->group_end();
        }

        $this->db->order_by('t.id', 'DESC');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_object();
    }

    public function count_tiket($filter = [])
    {
        $this->db->from('tiket as t');
        if (!empty($filter['status']) && $filter['status'] !== 'all') {
            $this->db->where('t.status', $filter['status']);
        }
        if (!empty($filter['kategori_id']) && $filter['kategori_id'] !== 'all') {
            $this->db->where('t.kategori_id', $filter['kategori_id']);
        }
        if (!empty($filter['urgensi']) && $filter['urgensi'] !== 'all') {
            $this->db->where('t.urgensi', $filter['urgensi']);
        }
        if (!empty($filter['user_type']) && $filter['user_type'] !== 'all') {
            $this->db->where('t.user_type', $filter['user_type']);
        }
        if (!empty($filter['user_id'])) {
            $this->db->where('t.user_id', $filter['user_id']);
        }
        if (!empty($filter['keyword'])) {
            $kw = $filter['keyword'];
            $this->db->group_start()
                ->like('t.nomor_tiket', $kw)
                ->or_like('t.judul', $kw)
                ->or_like('t.user_nama', $kw)
            ->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_tiket_by_id($id)
    {
        return $this->db->select('t.*, k.nama_kategori, k.kode_prefix, k.tipe_alur, k.form_fields')
            ->from('tiket as t')
            ->join('tiket_kategori as k', 'k.id = t.kategori_id', 'left')
            ->where('t.id', $id)
            ->get()->row_object();
    }

    public function get_tiket_by_nomor($nomor_tiket)
    {
        return $this->db->select('t.*, k.nama_kategori, k.kode_prefix, k.tipe_alur, k.form_fields')
            ->from('tiket as t')
            ->join('tiket_kategori as k', 'k.id = t.kategori_id', 'left')
            ->where('t.nomor_tiket', $nomor_tiket)
            ->get()->row_object();
    }

    // ==========================================
    // PESAN & CHAT TIMELINE
    // ==========================================

    public function get_pesan($tiket_id, $include_internal = false)
    {
        $this->db->from('tiket_pesan')->where('tiket_id', $tiket_id);
        if (!$include_internal) {
            $this->db->where('is_internal', 0);
        }
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get()->result_object();
    }

    public function tambah_pesan($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('tiket_pesan', $data);

        // Update updated_at di tiket utama
        $this->db->where('id', $data['tiket_id'])->update('tiket', [
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->db->insert_id();
    }

    // ==========================================
    // LIFECYCLE & STATUS WORKSPACE
    // ==========================================

    public function ubah_status($tiket_id, $status_baru, $user_id, $user_nama, $keterangan = '')
    {
        $tiket = $this->db->where('id', $tiket_id)->get('tiket')->row_object();
        if (!$tiket) return false;

        $update_data = [
            'status' => $status_baru,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status_baru === 'RESOLVED' || $status_baru === 'RELEASED') {
            $update_data['solved_at'] = date('Y-m-d H:i:s');
        } elseif ($status_baru === 'CLOSED') {
            $update_data['closed_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $tiket_id)->update('tiket', $update_data);

        // Catat riwayat
        $this->db->insert('tiket_riwayat_status', [
            'tiket_id' => $tiket_id,
            'status_lama' => $tiket->status,
            'status_baru' => $status_baru,
            'diubah_oleh_id' => $user_id,
            'diubah_oleh_nama' => $user_nama,
            'keterangan' => $keterangan ?: 'Perubahan status tiket ke ' . $status_baru,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function simpan_catatan_internal($tiket_id, $catatan)
    {
        return $this->db->where('id', $tiket_id)->update('tiket', [
            'catatan_internal' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_riwayat_status($tiket_id)
    {
        return $this->db->where('tiket_id', $tiket_id)
            ->order_by('created_at', 'ASC')
            ->get('tiket_riwayat_status')->result_object();
    }

    // ==========================================
    // STATISTIK DASHBOARD
    // ==========================================

    public function get_statistik_admin()
    {
        $total = $this->db->count_all('tiket');
        $open = $this->db->where_in('status', ['OPEN', 'SUBMITTED'])->count_all_results('tiket');
        $in_progress = $this->db->where_in('status', ['IN_PROGRESS', 'IN_REVIEW', 'UNDER_REVIEW', 'PLANNED', 'IN_DEVELOPMENT'])->count_all_results('tiket');
        $resolved = $this->db->where_in('status', ['RESOLVED', 'RELEASED'])->count_all_results('tiket');
        $closed = $this->db->where('status', 'CLOSED')->count_all_results('tiket');

        return (object)[
            'total' => $total,
            'open' => $open,
            'in_progress' => $in_progress,
            'resolved' => $resolved,
            'closed' => $closed
        ];
    }

    public function get_statistik_user($user_type, $user_id)
    {
        $this->db->where('user_type', $user_type)->where('user_id', $user_id);
        $total = $this->db->count_all_results('tiket');

        $this->db->where('user_type', $user_type)->where('user_id', $user_id)->where_in('status', ['OPEN', 'SUBMITTED']);
        $open = $this->db->count_all_results('tiket');

        $this->db->where('user_type', $user_type)->where('user_id', $user_id)->where_in('status', ['IN_PROGRESS', 'IN_REVIEW', 'UNDER_REVIEW', 'PLANNED', 'IN_DEVELOPMENT']);
        $in_progress = $this->db->count_all_results('tiket');

        $this->db->where('user_type', $user_type)->where('user_id', $user_id)->where_in('status', ['RESOLVED', 'RELEASED', 'CLOSED']);
        $selesai = $this->db->count_all_results('tiket');

        return (object)[
            'total' => $total,
            'open' => $open,
            'in_progress' => $in_progress,
            'selesai' => $selesai
        ];
    }
}
