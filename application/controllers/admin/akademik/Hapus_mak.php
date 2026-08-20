<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hapus_mak extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $this->load->service('NilaiService');
    }

    public function index() {
        $data['content'] = 'admin/akademik/hapus_mak/V_index';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Hapus Matakuliah';
        $data['judul_sub_judul'] ='';

        $this->load->view('admin/template/V_main', $data);
    }

    public function cari($cari_nim = null) {
        if ($cari_nim == null) {
            $nim = $this->input->post('nim');
            $this->session->set_userdata(array('sess_nim_cari'=>$nim));
        } else {
            $nim = $cari_nim;
        }

        $res = $this->nilaiservice->cari_mak_mahasiswa($nim);

        $data['content'] = 'admin/akademik/hapus_mak/V_hasil_cari';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Hapus Matakuliah';
        $data['judul_sub_judul'] ='';
        $data['data'] = $res['hasil'];
        $data['nim'] = $nim;
        $data['mahasiswa'] = $res['mahasiswa'];

        $this->load->view('admin/template/V_main', $data);
    }

    public function hapus($nim, $kode_matakuliah, $kode_krs_detail) {
        $res = $this->nilaiservice->hapus_mak($nim, $kode_matakuliah, $kode_krs_detail);
        
        $type = $res['status'] ? 'success' : 'warning';
        if (!$res['status'] && strpos($res['message'], 'gagal') !== false) {
            $type = 'error';
        }
        
        $title = $res['status'] ? 'Success!' : ($type == 'error' ? 'Gagal!' : 'Warning!');
        
        $this->session->set_flashdata('info', '<script>swal("'.$title.'", "'.$res['message'].'", "'.$type.'");</script>');
        
        redirect(site_url('admin/akademik/hapus_mak/cari/'.$this->session->userdata('sess_nim_cari')));
    }
}
?>