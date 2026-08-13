<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_nilai extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index() {
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "Log Aktivitas Nilai";
        $data['judul_sub_judul'] = "Perubahan";
        $data['content'] = "admin/akademik/perubahan/log_nilai/V_index";

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil($nim) {
        $this->db->select('l.*, mak.nama_matakuliah, mak.kode_matakuliah');
        $this->db->from('log_aktivitas_nilai as l');
        $this->db->join('matakuliah as mak', 'mak.id_matakuliah=l.id_matakuliah', 'left');
        $this->db->where('l.nim', $nim);
        $this->db->order_by('l.id', 'DESC');
        $data['nim'] = $nim;
        $data['data'] = $this->db->get()->result();
        $data['mahasiswa'] = $this->db->select('mhs.nim, mhs.nama_mahasiswa, ps.nama_program_studi')
            ->from('mahasiswa as mhs')
            ->join('program_studi as ps', 'ps.kode_program_studi=mhs.program_studi_kode', 'left')
            ->where('mhs.nim', $nim)
            ->get()->row_object();
        $this->load->view('admin/akademik/perubahan/log_nilai/V_hasil', $data);
    }

}