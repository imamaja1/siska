<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{

    function login_mahasiswa($nim, $sandi)
    {
        $this->db->where('nim', $nim);
        $this->db->where('sandi', $sandi);
        $this->db->where('status', 'A');
        return $this->db->get('mahasiswa')->row_object();
    }

    function login_dosen($email, $sandi)
    {
        return $this->db->select('*')->where(array('alamat_email' => $email, 'sandi_pengguna' => $sandi))->get('dosen');
    }

    function login_admin($nama_login, $sandi)
    {
        return $this->db->select('*')->where(array('nama_login' => $nama_login, 'sandi_pengguna' => $sandi))->get('pengguna');
    }

    function get_admin_by_username($nama_login)
    {
        return $this->db->select('*')->where('nama_login', $nama_login)->get('pengguna')->row_object();
    }

    function get_mahasiswa_by_nim($nim)
    {
        return $this->db->select('*')->where(array('nim' => $nim, 'status' => 'A'))->get('mahasiswa')->row_object();
    }

    function get_dosen_by_email($email)
    {
        return $this->db->select('*')->where('alamat_email', $email)->get('dosen')->row_object();
    }

}
