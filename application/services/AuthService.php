<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/Mahasiswa_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/kurikulum/m_data_kurikulum',
            'login_model',
        ));
        $this->load->library('ion_auth');
    }

    public function getMahasiswaByNim($nim) {
        return $this->db->get_where('mahasiswa', array('nim' => $nim))->row();
    }

    public function getProgramStudiByKode($kode) {
        return $this->db->get_where('program_studi', array('kode_program_studi' => $kode))->row();
    }

    public function getUserByUsername($username) {
        return $this->db->where('username', $username)->get('users')->row_object();
    }

    public function getUserIdByUsername($username) {
        $result = $this->db->select('id_user')->from('users')->where('username', $username)->limit(1)->get()->row();
        return $result;
    }

    public function updateUserId($username, $id_user) {
        $this->db->where('username', $username)->update('users', array('id' => $id_user));
    }

    public function updateUserKeyRef($nim) {
        $this->db->where('username', $nim)->update('users', array('key_ref' => $nim, 'role' => 2));
    }

    public function updateUserPassword($username, $password_new) {
        $this->db->where('username', $username)->update('users', array('password' => $password_new));
    }

    public function updateUserPasswordByKeyRef($key_ref, $password_new) {
        $this->db->where('key_ref', $key_ref)->update('users', array('password' => $password_new));
    }

    public function getUserByEmail($email) {
        return $this->db->where('username', $email)->get('users')->row_object();
    }

    public function getUserIdByEmail($email) {
        $result = $this->db->select('id_user')->from('users')->where('username', $email)->limit(1)->get()->row();
        return $result;
    }

    public function updateUserIdByEmail($email, $id_user) {
        $this->db->where('username', $email)->update('users', array('id' => $id_user));
    }

    // API methods
    public function getAllProgramStudi() {
        return $this->db->select('*')
            ->from('program_studi')
            ->get()->result_object();
    }

    public function getDosenByHomebase($kode_program_studi) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('homebase', $kode_program_studi)
            ->get()->result_object();
    }
}
