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

    public function getUserInfoByEmail($email)
    {
        $q = $this->db->get_where('mahasiswa', array('email' => $email, 'status' => 'A'), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return array('status' => 'mahasiswa', 'user' => $row);
        }

        $q = $this->db->get_where('dosen', array('alamat_email' => $email), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return array('status' => 'dosen', 'user' => $row);
        }

        return false;
    }

    public function insertToken($user_email, $status)
    {
        $token = bin2hex(random_bytes(32));

        $data = array(
            'token' => $token,
            'email' => $user_email,
            'status' => $status,
            'created' => date('Y-m-d H:i:s')
        );
        $query = $this->db->insert_string('tokens', $data);
        $this->db->query($query);
        return $token . $user_email;
    }

    public function countRecentTokens($user_email, $since)
    {
        return $this->db->where('email', $user_email)
            ->where('created >=', $since)
            ->count_all_results('tokens');
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
        $tkn = substr($token, 0, 64);
        $email = substr($token, 64);

        $q = $this->db->get_where('tokens', array(
            'tokens.token' => $tkn,
            'tokens.email' => $email
        ), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();

            $created = $row->created;
            $createdTS = strtotime($created);
            $expireTS = strtotime('+30 minutes', $createdTS);

            if (time() > $expireTS) {
                $this->removeToken($token);
                return false;
            }

            $user_info = $this->getUserInfoByEmail($row->email);
            if (!$user_info) {
                return false;
            }
            if ($row->status == 'dosen') {
                $data = $user_info['user']->nama_dosen;
            } else {
                $data = $user_info['user']->nama_mahasiswa;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function updatePassword($post, $token)
    {
        $tkn = substr($token, 0, 64);
        $email = substr($token, 64);

        $q = $this->db->get_where('tokens', array('token' => $tkn, 'email' => $email), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            $status = $row->status;
            $email = $row->email;
            $hashed = password_hash($post, PASSWORD_BCRYPT);
            if ($status == 'dosen') {
                $this->db->update($status, array('sandi_pengguna' => $hashed), array('alamat_email' => $email));
                return true;
            } else if ($status == 'mahasiswa') {
                $this->db->update($status, array('sandi' => $hashed), array('email' => $email));
                return true;
            }
            return false;
        }
        return false;
    }

    public function removeToken($token)
    {
        $tkn = substr($token, 0, 64);
        $email = substr($token, 64);

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