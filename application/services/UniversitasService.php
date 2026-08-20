<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UniversitasService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/institusi_model',
            'jurusan/universitas/fakultas_model',
            'jurusan/M_dosen'
        ));
    }

    // --- INSTITUSI LOGIC ---

    public function getInstitusiLengkap() {
        return $this->institusi_model->get();
    }

    public function simpanInstitusi($post_data) {
        $kode_institusi = $post_data['kode'];
        $cek_kode_institusi = $this->institusi_model->cek_kode($kode_institusi);
        
        if ($cek_kode_institusi->num_rows() > 0) {
            return array('status' => false, 'msg' => 'Data Institusi dengan kode ' . $kode_institusi . ' sudah ada.');
        } else {
            $data = array(
                'kode_institusi' => $kode_institusi,
                'nama_institusi' => $post_data['nama'],
                'singkatan' => $post_data['singkatan']
            );
            
            if ($this->institusi_model->add($data)) {
                return array('status' => true, 'msg' => 'Tambah Data Institusi Berhasil.');
            }
            return array('status' => false, 'msg' => 'Tambah Data Institusi Gagal.');
        }
    }

    public function ubahInstitusi($post_data) {
        $kode = $post_data['kode-edit'];
        $data = array(
            'nama_institusi' => $post_data['nama-edit'],
            'singkatan' => $post_data['singkatan-edit']
        );
        
        if ($this->institusi_model->edit($kode, $data)) {
            return array('status' => true, 'msg' => 'Ubah Data Institusi Berhasil.');
        } else {
            return array('status' => false, 'msg' => 'Ubah Data Institusi Gagal.');
        }
    }

    public function hapusInstitusi($kode) {
        if ($this->institusi_model->del($kode)) {
            return array('status' => true, 'msg' => 'Hapus Data Institusi Berhasil.');
        } else {
            return array('status' => false, 'msg' => 'Hapus Data Institusi Gagal.');
        }
    }

    // --- FAKULTAS LOGIC ---

    public function getFakultasLengkap() {
        return $this->fakultas_model->get();
    }

    public function getFakultasById($id) {
        return $this->fakultas_model->get($id);
    }

    public function simpanFakultas($post_data) {
        $data = array(
            'kode_fakultas' => $post_data['kode_fakultas'],
            'nama_fakultas' => $post_data['nama_fakultas'],
            'dekan' => $post_data['dekan']
        );
        if ($this->fakultas_model->add($data)) {
            return array('status' => true, 'msg' => 'Data berhasil disimpan');
        } else {
            return array('status' => false, 'msg' => 'Data gagal disimpan');
        }
    }

    public function ubahFakultas($id, $post_data) {
        $data = array(
            'nama_fakultas' => $post_data['nama_fakultas'],
            'dekan' => $post_data['dekan']
        );
        if ($this->fakultas_model->update($id, $data)) {
            return array('status' => true, 'msg' => 'Data berhasil disimpan');
        } else {
            return array('status' => false, 'msg' => 'Data gagal disimpan');
        }
    }

    public function hapusFakultas($id) {
        if ($this->fakultas_model->delete($id)) {
            return array('status' => true, 'msg' => 'Data berhasil dihapus');
        } else {
            return array('status' => false, 'msg' => 'Data gagal dihapus');
        }
    }

    public function getDosen() {
        return $this->M_dosen->get();
    }

}
