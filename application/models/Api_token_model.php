<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_token_model extends CI_Model {

    private $table_tokens = 'api_tokens';
    private $table_logs = 'api_logs';

    public function get_all_tokens()
    {
        return $this->db->order_by('created_at', 'DESC')
            ->get($this->table_tokens)
            ->result();
    }

    public function get_active_tokens()
    {
        return $this->db->where('is_active', 1)
            ->order_by('created_at', 'DESC')
            ->get($this->table_tokens)
            ->result();
    }

    public function get_token($id)
    {
        return $this->db->where('id', $id)
            ->get($this->table_tokens)
            ->row();
    }

    public function insert_token($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table_tokens, $data);
    }

    public function update_token($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)
            ->update($this->table_tokens, $data);
    }

    public function delete_token($id)
    {
        return $this->db->where('id', $id)
            ->delete($this->table_tokens);
    }

    public function toggle_status($id)
    {
        $token = $this->get_token($id);
        if (!$token) return FALSE;

        $new_status = $token->is_active ? 0 : 1;
        return $this->db->where('id', $id)
            ->update($this->table_tokens, [
                'is_active' => $new_status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function insert_log($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        if (isset($data['detail_data']) && is_array($data['detail_data'])) {
            $data['detail_data'] = json_encode($data['detail_data']);
        }
        return $this->db->insert($this->table_logs, $data);
    }

    public function get_logs($token_id = NULL, $limit = 20, $offset = 0)
    {
        $this->db->select('api_logs.*, api_tokens.nama_aplikasi');
        $this->db->join('api_tokens', 'api_tokens.id = api_logs.token_id', 'left');
        $this->db->order_by('api_logs.created_at', 'DESC');
        $this->db->limit($limit, $offset);

        if ($token_id) {
            $this->db->where('api_logs.token_id', $token_id);
        }

        return $this->db->get($this->table_logs)->result();
    }

    public function get_logs_count($token_id = NULL)
    {
        if ($token_id) {
            $this->db->where('token_id', $token_id);
        }
        return $this->db->count_all_results($this->table_logs);
    }

    public function get_log_detail($log_id)
    {
        return $this->db->where('id', $log_id)
            ->get($this->table_logs)
            ->row();
    }

    public function cek_nim_exists($nim)
    {
        return $this->db->where('nim', (string) $nim)
            ->count_all_results('mahasiswa') > 0;
    }

    public function insert_mahasiswa($data)
    {
        return $this->db->insert('mahasiswa', $data);
    }

    public function update_mahasiswa($nim, $data)
    {
        return $this->db->where('nim', (string) $nim)
            ->update('mahasiswa', $data);
    }

    public function get_mahasiswa_by_nim($nim)
    {
        return $this->db->where('nim', (string) $nim)
            ->get('mahasiswa')
            ->row();
    }
}
