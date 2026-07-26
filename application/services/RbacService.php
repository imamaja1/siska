<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RbacService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getAllRole() {
        return $this->db->get('role')->result();
    }

    public function deleteAccessByRole($id_role) {
        return $this->db->where('id_role', $id_role)->delete('access');
    }

    public function insertAccess($data) {
        return $this->db->insert('access', $data);
    }
}
