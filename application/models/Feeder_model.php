<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Feeder_model extends CI_Model {

    private $table = 'feeder_credentials';

    public function get_all_credentials()
    {
        return $this->db->order_by('key_name', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function get_credential($key_name)
    {
        return $this->db->where('key_name', $key_name)
            ->get($this->table)
            ->row();
    }

    public function save_credential($key_name, $key_value, $description, $user_id)
    {
        $now = date('Y-m-d H:i:s');
        $existing = $this->get_credential($key_name);

        if ($existing) {
            return $this->db->where('id', $existing->id)
                ->update($this->table, [
                    'key_value'   => $key_value,
                    'description' => $description,
                    'updated_by'  => $user_id,
                    'updated_at'  => $now,
                ]);
        }

        return $this->db->insert($this->table, [
            'key_name'    => $key_name,
            'key_value'   => $key_value,
            'description' => $description,
            'created_by'  => $user_id,
            'updated_by'  => $user_id,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
    }
}
