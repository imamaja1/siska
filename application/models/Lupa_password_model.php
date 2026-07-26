<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lupa_password_model extends CI_Model
{
    private $dosen = 'dosen';
    private $mhs = 'mahasiswa';

    public function getUserInfo($id)
    {
        $q = $this->db->get_where('users', array('id_user' => $id), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        } else {
            error_log('no user found getUserInfo(' . $id . ')');
            return false;
        }
    }

    public function getUserInfoByEmail($email, $tabel)
    {
        switch ($tabel) {
            case 'dosen':
                $q = $this->db->get_where($tabel, array('alamat_email' => $email), 1);
                break;
            default :
                $q = $this->db->get_where($tabel, array('email' => $email, 'status' => 'A'), 1);
                break;
        }
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        }
        return false;
    }

    public function insertToken($user_email, $status)
    {
        $token = substr(sha1(rand()), 0, 30);
        $date = date('Y-m-d');

        $data = array(
            'token' => $token,
            'email' => $user_email,
            'status' => $status,
            'created' => $date
        );
        $query = $this->db->insert_string('tokens', $data);
        $this->db->query($query);
        return $token . $user_email;
    }

    public function isTokenActive($uid)
    {
        $this->db->get_where('tokens', array('tokens.user_id' => $uid), 1);
        if ($this->db->affected_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isTokenValid($token)
    {
        $tkn = substr($token, 0, 30);
        $email = substr($token, 30);

        $q = $this->db->get_where('tokens', array(
            'tokens.token' => $tkn,
            'tokens.email' => $email
        ), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();

            $created = $row->created;
            $createdTS = strtotime($created);
            $today = date('Y-m-d');
            $todayTS = strtotime($today);

            if ($createdTS != $todayTS) {
                return false;
            }

            $user_info = $this->getUserInfoByEmail($row->email, $row->status);
//            $data = array();
            if ($row->status == 'dosen') {
                $data = $user_info->nama_dosen;
            } else {
                $data = $user_info->nama_mahasiswa;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function updatePassword($post, $token)
    {
        $tkn = substr($token, 0, 30);
        $email = substr($token, 30);

        $q = $this->db->get_where('tokens', array('token' => $tkn, 'email' => $email), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            $status = $row->status;
            $email = $row->email;
            if ($status == 'dosen') {
                $this->db->update($status, array('sandi_pengguna' => $post), array('alamat_email' => $email));
                return true;
            } else if ($status == 'mahasiswa') {
                $this->db->update($status, array('sandi' => $post), array('email' => $email));
                return true;
            }
            return false;
        }
        return false;
    }

    public function removeToken($token)
    {
        $tkn = substr($token, 0, 30);
        $email = substr($token, 30);

        $q = $this->db->delete('tokens', array(
            'tokens.token' => $tkn,
            'tokens.email' => $email
        ));

        if ($q == false) {
            return false;
        }
        return true;
    }


}