<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{

    function login_mahasiswa($nim, $sandi)
    {
        $row = $this->db->where('nim', $nim)->where('status', 'A')->get('mahasiswa')->row_object();
        if (!$row) {
            return false;
        }
        if (!$this->verify_password($sandi, $row->sandi)) {
            return false;
        }
        return $row;
    }

    function login_dosen($email, $sandi)
    {
        $row = $this->db->select('*')->where('alamat_email', $email)->get('dosen')->row_object();
        if ($row && $this->verify_password($sandi, $row->sandi_pengguna)) {
            return $this->db->select('*')->where('alamat_email', $email)->get('dosen');
        }
        return $this->db->select('*')->where('alamat_email', $email)->where('1', '0')->get('dosen');
    }

    private function verify_password($plain, $stored)
    {
        if ($stored === null || $stored === '') {
            return false;
        }
        if (password_get_info($stored)['algo']) {
            return password_verify($plain, $stored);
        }
        return hash_equals($stored, md5($plain));
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
