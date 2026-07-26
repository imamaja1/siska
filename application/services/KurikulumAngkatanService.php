<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KurikulumAngkatanService extends MY_Service {

    public function __construct() {
        parent::__construct();
        // Load the required models within the service
        $this->load->model('jurusan/kurikulum/m_kurikulum_angkatan');
        $this->load->model('jurusan/kurikulum/m_nama_kurikulum');
    }

    /**
     * Get all Kurikulum Angkatan data
     */
    public function getAllData() {
        return $this->m_kurikulum_angkatan->get();
    }

    /**
     * Get single data by ID
     */
    public function getDataById($id) {
        return $this->m_kurikulum_angkatan->get($id);
    }

    /**
     * Get data needed for index/form views
     */
    public function getNamaKurikulum() {
        return $this->m_nama_kurikulum->get();
    }

    /**
     * Handle the addition of new Kurikulum Angkatan
     */
    public function addData($data) {
        if (empty($data)) {
            return [
                'status' => false,
                'msg' => "Data kosong, gagal di simpan"
            ];
        }

        $add = $this->m_kurikulum_angkatan->add($data);
        if ($add) {
            return [
                'status' => true,
                'msg' => "Data berhasil di simpan"
            ];
        } else {
            return [
                'status' => false,
                'msg' => "Data gagal di simpan"
            ];
        }
    }

    /**
     * Handle the update of Kurikulum Angkatan
     */
    public function updateData($id, $data) {
        if (empty($id) || empty($data)) {
            return [
                'status' => false,
                'msg' => "Data tidak valid"
            ];
        }

        $update = $this->m_kurikulum_angkatan->update($id, $data);
        if ($update) {
            return [
                'status' => true,
                'msg' => "Data berhasil di update"
            ];
        } else {
            return [
                'status' => false,
                'msg' => "Data gagal di update"
            ];
        }
    }

    /**
     * Handle the deletion of Kurikulum Angkatan
     */
    public function deleteData($id) {
        if (empty($id)) {
            return [
                'status' => false,
                'msg' => "ID tidak valid"
            ];
        }

        $hapus = $this->m_kurikulum_angkatan->hapus($id);
        if ($hapus) {
            return [
                'status' => true,
                'msg' => "Data berhasil di hapus"
            ];
        } else {
            return [
                'status' => false,
                'msg' => "Data gagal di hapus"
            ];
        }
    }
}
