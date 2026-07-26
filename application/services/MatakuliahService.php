<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MatakuliahService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/kurikulum/m_matakuliah',
            'jurusan/kurikulum/m_matakuliah_prasyarat',
            'jurusan/program_studi/kompetensi_model',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_nama_kurikulum',
        ));
    }

    // --- MATAKULIAH LOGIC ---

    public function getMatakuliahByProdi($kode_prod, $limit, $offset) {
        return $this->m_matakuliah->get_matakuliah($kode_prod, $limit, $offset);
    }

    public function countMatakuliahByProdi($kode_prod) {
        return count($this->m_matakuliah->count_matakuliah($kode_prod));
    }

    public function getMatakuliahCari($keyword, $kode_prod, $limit, $offset) {
        return $this->m_matakuliah->get_matakuliah_cari($keyword, $kode_prod, $limit, $offset);
    }

    public function countMatakuliahCari($keyword, $kode_prod) {
        return count($this->m_matakuliah->count_matakuliah_cari($keyword, $kode_prod));
    }

    public function getKompetensi() {
        return $this->kompetensi_model->get_kompetensi();
    }

    public function getJurusan() {
        return $this->nama_jurusan_model->get();
    }

    public function simpanMatakuliah($post_data) {
        $data = $post_data;
        $data['kode_program_studi'] = $post_data['kode_nama_jurusan'];
        if (empty($post_data['kode_kompetensi'])) {
            $data['kode_kompetensi'] = null;
        }
        unset($data['kode_nama_jurusan']);
        
        $simpan = $this->db->insert('matakuliah', $data);
        if ($simpan) {
            return array('status' => true, 'msg' => 'Matakuliah berhasil di simpan');
        } else {
            return array('status' => false, 'msg' => 'Matakuliah gagal di simpan');
        }
    }

    public function getMatakuliahByKode($id) {
        return $this->m_matakuliah->get_matakuliah_by_kode($id);
    }

    public function ubahMatakuliah($post_data) {
        $param = $post_data['param_edit'];
        $data_matakuliah = array(
            'kode_matakuliah' => $post_data['kode_matakuliah'],
            'nama_matakuliah' => $post_data['nama_matakuliah'],
            'sks_teori' => $post_data['sks_teori'],
            'sks_praktek' => $post_data['sks_praktek'],
            'sks_praktikum' => $post_data['sks_praktikum'],
            'kode_program_studi' => $post_data['kode_nama_jurusan'],
            'jenis' => $post_data['jenis'],
            'block' => $post_data['block'],
        );

        if (!empty($post_data['kode_kompetensi'])) {
            $data_matakuliah['kode_kompetensi'] = $post_data['kode_kompetensi'];
        }

        if ($this->m_matakuliah->ubah($data_matakuliah, $param)) {
            return array('status' => true, 'msg' => 'Matakuliah berhasil di simpan');
        } else {
            return array('status' => false, 'msg' => 'Matakuliah gagal di simpan');
        }
    }

    public function hapusMatakuliah($id) {
        if ($this->m_matakuliah->hapus($id)) {
            return array('status' => true, 'msg' => 'Matakuliah berhasil di hapus');
        } else {
            return array('status' => false, 'msg' => 'Matakuliah gagal di hapus');
        }
    }


    // --- MATAKULIAH PRASYARAT LOGIC ---

    public function getPrasyarat() {
        return $this->m_matakuliah_prasyarat->get_prasyarat();
    }

    public function getNamaKurikulum() {
        return $this->m_nama_kurikulum->get();
    }

    public function simpanPrasyarat($post_data) {
        $jenis = $post_data['jenis_prasyarat'];
        $jenis_prasyarat = ($jenis == 'AM' || $jenis == '') ? null : $jenis;

        $data_syarat = array(
            'kode_nama_kurikulum' => $post_data['nama_kurikulum'],
            'id_matakuliah_ambil' => $post_data['id_matakuliah_ambil'],
            'id_matakuliah_syarat' => $post_data['id_matakuliah_syarat'],
            'jenis_prasyarat' => $jenis_prasyarat,
        );

        if ($this->m_matakuliah_prasyarat->simpan_prasyarat($data_syarat)) {
            return true;
        }
        return false;
    }

    public function ubahPrasyarat($post_data) {
        $param = $post_data['id'];
        $jenis = $post_data['jenis_prasyarat'];
        $jenis_prasyarat = ($jenis == 'AM' || $jenis == '') ? null : $jenis;

        $data_syarat = array(
            'id_matakuliah_ambil' => $post_data['id_matakuliah_ambil'],
            'id_matakuliah_syarat' => $post_data['id_matakuliah_syarat'],
            'jenis_prasyarat' => $jenis_prasyarat,
        );

        if ($this->m_matakuliah_prasyarat->ubah($data_syarat, $param)) {
            return true;
        }
        return false;
    }

    public function getEditPrasyaratData($id, $kode_nama_kuikulum) {
        $query = $this->db->where('kode_matakuliah_prasyarat', $id)->get('matakuliah_prasyarat')->row_object();
        $data_kurikulum = $this->db->select('*')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->where('kode_nama_kurikulum', $kode_nama_kuikulum)
            ->get()->result();
            
        return array(
            'data' => $query,
            'data_kurikulum' => $data_kurikulum
        );
    }

    public function hapusPrasyarat($id) {
        return $this->m_matakuliah_prasyarat->hapus($id);
    }

    public function getMatakuliahByKurikulum($nama_kurikulum) {
        $query = $this->db->select('*')
                ->from('kurikulum as kur')
                ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
                ->where('kode_nama_kurikulum', $nama_kurikulum)
                ->get()->result();
        return $query;
    }
}
