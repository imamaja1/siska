<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserService extends MY_Service
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function getAllUsers()
    {
        return $this->user_model->getAll();
    }

    public function getPaginatedUsers($limit, $offset)
    {
        return $this->user_model->getPaginated($limit, $offset);
    }

    public function countAllUsers()
    {
        return $this->user_model->countAll();
    }

    public function getUserById($kode)
    {
        return $this->user_model->findById($kode);
    }

    public function getUserRoles()
    {
        return $this->user_model->getAllRoles();
    }

    public function createUser($data)
    {
        $payload = [
            'nama_pengguna'  => $data['nama_pengguna'],
            'nama_login'     => $data['nama_login'],
            'sandi_pengguna' => md5($data['sandi_pengguna']),
            'id_role'        => $data['id_role'],
        ];

        if ($this->user_model->insert($payload)) {
            return ['status' => true, 'msg' => 'Tambah data berhasil'];
        }
        return ['status' => false, 'msg' => 'Tambah data gagal'];
    }

    public function updateUser($kode, $data)
    {
        $payload = [
            'nama_pengguna' => $data['nama_pengguna'],
            'nama_login'    => $data['nama_login'],
            'id_role'       => $data['id_role'],
        ];

        if ($this->user_model->update($kode, $payload)) {
            return ['status' => true, 'msg' => 'Ubah data berhasil'];
        }
        return ['status' => false, 'msg' => 'Ubah data gagal'];
    }

    public function updatePassword($kode, $password)
    {
        $payload = ['sandi_pengguna' => md5($password)];

        if ($this->user_model->update($kode, $payload)) {
            return ['status' => true, 'msg' => 'Ubah sandi berhasil'];
        }
        return ['status' => false, 'msg' => 'Ubah sandi gagal'];
    }

    public function deleteUser($kode)
    {
        if ($this->user_model->delete($kode)) {
            return ['status' => true, 'msg' => 'Hapus data berhasil'];
        }
        return ['status' => false, 'msg' => 'Hapus data gagal'];
    }

    public function validatePasswordComplexity($password)
    {
        if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password)) {
            return 'Sandi harus memiliki panjang min. 8 karakter, max. 20 karakter, mengandung min. 1 huruf besar, 1 huruf kecil, dan 1 angka!';
        }
        return null;
    }

    public function checkDuplicateLogin($nama_login, $excludeKode = null)
    {
        $existing = $this->user_model->findByLogin($nama_login);
        if ($existing && $existing->kode_pengguna != $excludeKode) {
            return 'Nama Login "' . $nama_login . '" sudah digunakan!';
        }
        return null;
    }
}
