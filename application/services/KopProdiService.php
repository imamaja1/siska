<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KopProdiService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    private function kopDir() {
        return FCPATH . 'assets/gambar/kop/';
    }

    public function getProdiList() {
        return $this->db->select('ps.kode_program_studi, ps.nama_program_studi, ps.singkatan_program_studi, ps.kode_fakultas, fk.nama_fakultas')
            ->from('program_studi as ps')
            ->join('fakultas as fk', 'fk.kode_fakultas=ps.kode_fakultas', 'left')
            ->order_by('ps.kode_fakultas', 'ASC')
            ->order_by('ps.kode_program_studi', 'ASC')
            ->get()->result_object();
    }

    public function getKopFile($kode_program_studi) {
        $files = glob($this->kopDir() . 'prodi_' . $kode_program_studi . '.*');
        return !empty($files) ? basename($files[0]) : null;
    }

    public function uploadKop($kode_program_studi) {
        $prodi = $this->db->select('kode_program_studi')
            ->from('program_studi')
            ->where('kode_program_studi', $kode_program_studi)
            ->get()->row_object();

        if (!$prodi) {
            return array('status' => false, 'msg' => 'Program studi tidak ditemukan');
        }

        $dir = $this->kopDir();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $config['upload_path']   = $dir;
        $config['allowed_types'] = 'png|jpg|jpeg';
        $config['max_size']      = 5120;
        $config['file_name']     = 'prodi_' . $kode_program_studi;
        $config['overwrite']     = true;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto')) {
            return array('status' => false, 'msg' => strip_tags($this->upload->display_errors('', '')));
        }

        $new_file = $this->upload->data('file_name');

        foreach (glob($dir . 'prodi_' . $kode_program_studi . '.*') as $f) {
            if (basename($f) !== $new_file && is_file($f)) {
                @unlink($f);
            }
        }

        return array('status' => true, 'msg' => 'Kop prodi berhasil diunggah', 'file' => $new_file);
    }

    public function resetKop($kode_program_studi) {
        $files = glob($this->kopDir() . 'prodi_' . $kode_program_studi . '.*');
        if (empty($files)) {
            return array('status' => false, 'msg' => 'Kop prodi belum ada');
        }
        foreach ($files as $f) {
            if (is_file($f)) {
                @unlink($f);
            }
        }
        return array('status' => true, 'msg' => 'Kop prodi direset ke default fakultas');
    }
}
