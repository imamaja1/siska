<?php


class Pembayaran_mahasiswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
        $this->load->service('PerkuliahanService');
    }

    public function index(){
        $data['content'] = 'admin/akademik/status_pembayaran/V_rekap_sks';
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "Pembayaran Mahasiswa";

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter_rekap_sks(){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $data['data'] = $this->perkuliahanservice->get_rekap_pembayaran_sks($kode_tahun_akademik, false);
        return $this->load->view('admin/akademik/status_pembayaran/V_hasil_rekap_sks', $data);
    }

    public function filter_rekap_sks_skripsi(){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $data['data'] = $this->perkuliahanservice->get_rekap_pembayaran_sks($kode_tahun_akademik, true);
        return $this->load->view('admin/akademik/status_pembayaran/V_hasil_rekap_sks_skripsi', $data);
    }

    public function kumpul_krs($kode_status_perkuliahan){
        $res = $this->perkuliahanservice->toggle_pengumpulan_krs($kode_status_perkuliahan);

        echo json_encode($res);
    }
}