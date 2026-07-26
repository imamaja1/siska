<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rbac_model extends CI_Model
{
    function get_rbac($con, $id_user)
    {
        return $this->db->select('*')
            ->from('pengguna')
            ->join('role', 'pengguna.id_role=role.id_role')
            ->join('access', 'role.id_role=access.id_role')
            ->where('kode_pengguna', $id_user)
            ->where_in('nama_controller', $con)
            ->get()->result();
    }

    public function get_rbac_role($id_role)
    {
        return $this->db->select('access.nama_controller as nama')
            ->from('access')
            ->where('id_role', $id_role)
//            ->where_in('nama_controller', $con)
            ->get()->result_array();
    }
}