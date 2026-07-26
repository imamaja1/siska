<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KurikulumService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/kurikulum/m_nama_kurikulum',
            'jurusan/kurikulum/m_data_kurikulum',
            'jurusan/kurikulum/m_kurikulum_angkatan',
            'jurusan/program_studi/Nama_jurusan_model'
        ));
    }

    // --- NAMA KURIKULUM LOGIC ---

    public function getNamaKurikulumLengkap() {
        return $this->m_nama_kurikulum->get();
    }

    public function getProgramStudi() {
        return $this->Nama_jurusan_model->get();
    }

    public function simpanNamaKurikulum($post_data) {
        $data_nama_kurikulum = array(
            'nama_kurikulum' => $post_data['nama_kurikulum'],
            'kode_program_studi' => $post_data['jurusan']
        );
        return $this->m_nama_kurikulum->simpan($data_nama_kurikulum);
    }

    public function ubahNamaKurikulum($post_data) {
        $param = $post_data['param'];
        $data_nama_kurikulum = array(
            'nama_kurikulum' => $post_data['nama_kurikulum'],
            'kode_program_studi' => $post_data['jurusan']
        );
        return $this->m_nama_kurikulum->ubah($data_nama_kurikulum, $param);
    }

    public function hapusNamaKurikulum($kode_nama_kurikulum) {
        return $this->m_nama_kurikulum->hapus($kode_nama_kurikulum);
    }

    // --- KURIKULUM ANGKATAN LOGIC ---

    public function getKurikulumAngkatanLengkap() {
        return $this->m_kurikulum_angkatan->get();
    }

    public function getKurikulumAngkatanById($id) {
        return $this->m_kurikulum_angkatan->get($id);
    }

    public function simpanKurikulumAngkatan($post_data) {
        if ($this->m_kurikulum_angkatan->add($post_data)) {
            return array('status' => true, 'msg' => "Data berhasil di simpan");
        } else {
            return array('status' => false, 'msg' => "Data gagal di simpan");
        }
    }

    public function ubahKurikulumAngkatan($id, $post_data) {
        if ($this->m_kurikulum_angkatan->update($id, $post_data)) {
            return array('status' => true, 'msg' => "Data berhasil di update");
        } else {
            return array('status' => false, 'msg' => "Data gagal di update");
        }
    }

    public function hapusKurikulumAngkatan($id) {
        if ($this->m_kurikulum_angkatan->hapus($id)) {
            return array('status' => true, 'msg' => "Data berhasil di hapus");
        } else {
            return array('status' => false, 'msg' => "Data gagal di hapus");
        }
    }

    // --- DATA KURIKULUM LOGIC ---

    public function getMatakuliahByProdi($kode_prodi) {
        $this->load->model('jurusan/kurikulum/m_matakuliah');
        return $this->m_matakuliah->get_matakuliah_byid_prodi($kode_prodi);
    }

    public function getNamaKurikulumById($kode_nama_kurikulum) {
        return $this->m_nama_kurikulum->get_byid($kode_nama_kurikulum);
    }

    public function getKodeProdiFromKurikulum($kode_nama_kurikulum) {
        return $this->m_nama_kurikulum->get_kode_prodi($kode_nama_kurikulum);
    }

    public function getDataKurikulum($kode_nama_kurikulum) {
        return $this->m_data_kurikulum->get_data_kurikulum($kode_nama_kurikulum);
    }

    public function getKompetensiPilihan($kode_program_studi) {
        $kompetensi = $this->db->select('makom.id_matakuliah as id_matakuliah, kom.nama_kompetensi as nama')
            ->from('kompetensi as kom')
            ->join('matakuliah_kompetensi as makom', 'makom.kode_kompetensi=kom.kode_kompetensi')
            ->where('kom.kode_program_studi', $kode_program_studi)
            ->get()->result_array();

        if (count($kompetensi) > 0) {
            return array(
                'mk_pilihan' => array_column($kompetensi, 'id_matakuliah'),
                'nama_pilihan' => array_column($kompetensi, 'nama', 'id_matakuliah')
            );
        }
        return array('mk_pilihan' => array(), 'nama_pilihan' => array());
    }

    public function simpanDataKurikulumMultiple($post_data) {
        $semester = $post_data['semester'];
        $id_matakuliah = $post_data['id_matakuliah'];
        $kode_nama_kurikulum = $post_data['kode_nama_kurikulum'];

        if (!empty($id_matakuliah) && is_array($id_matakuliah)) {
            foreach ($id_matakuliah as $row => $val) {
                $data_kurikulum = array(
                    'id_matakuliah' => $val,
                    'semester' => $semester,
                    'kode_nama_kurikulum' => $kode_nama_kurikulum,
                );
                $this->m_data_kurikulum->simpan($data_kurikulum);
            }
        }
        return $kode_nama_kurikulum;
    }

    public function simpanDataKurikulum($post_data) {
        $data_kurikulum = array(
            'kode_nama_kurikulum' => $post_data['kode_nama_kurikulum'],
            'kode_matakuliah' => $post_data['kode_matakuliah'],
        );
        return $this->m_data_kurikulum->simpan($data_kurikulum);
    }

    public function ubahDataKurikulum($post_data) {
        $param = $post_data['param'];
        $data_kurikulum = array(
            'kode_nama_kurikulum' => $post_data['kode_nama_kurikulum'],
            'kode_matakuliah' => $post_data['kode_matakuliah'],
        );
        return $this->m_data_kurikulum->ubah($data_kurikulum, $param);
    }

    public function hapusDataKurikulum($id) {
        return $this->m_data_kurikulum->hapus($id);
    }

}
