<?php

class Status_perkuliahan extends CI_Controller
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
        $prodi = $this->perkuliahanservice->getProgramStudi();
        $data['content'] = 'admin/akademik/status_perkuliahan/V_index';
        $data['judul'] = 'Status Perkuliahan';
        $data['sub_judul'] = 'Status Perkuliahan';
        $data['prodi'] = $prodi;

        return $this->load->view('admin/template/V_main', $data);
    }

    public function rekap($kode_program_studi){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $res = $this->perkuliahanservice->get_rekap_status_perkuliahan($kode_program_studi, $kode_tahun_akademik);
        
        $res['kode_program_studi'] = $kode_program_studi;

        return $this->load->view('admin/akademik/status_perkuliahan/V_rekap', $res);
    }

    public function list_rekap($angkatan, $kode_program_studi){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $res = $this->perkuliahanservice->get_list_rekap_status_perkuliahan($angkatan, $kode_program_studi, $kode_tahun_akademik);
        
        $res['angkatan'] = $angkatan;
        $res['kode_program_studi'] = $kode_program_studi;
        $res['content'] = 'admin/akademik/status_perkuliahan/V_detail';
        $res['judul'] = 'Detail Status Perkuliahan';
        $res['sub_judul'] = 'Detail  Status Perkuliahan';

        return $this->load->view('admin/template/V_main', $res);
    }

    public function excel($angkatan, $kode_program_studi){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $res = $this->perkuliahanservice->get_list_rekap_status_perkuliahan($angkatan, $kode_program_studi, $kode_tahun_akademik);
        
        $res['angkatan'] = $angkatan;
        $res['file_name'] = "REKAPITULASI MAHASISWA AKTIF KULIAH ".$res['prodi']->jenjang." ".$res['prodi']->nama_program_studi." ANGKATAN ". $angkatan;
        
        return $this->load->view('admin/akademik/status_perkuliahan/excel', $res);
    }

    public function excel_rekap($kode_program_studi){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $res = $this->perkuliahanservice->get_rekap_status_perkuliahan($kode_program_studi, $kode_tahun_akademik);
        
        $res['file_name'] = "REKAPITULASI MAHASISWA AKTIF KULIAH ".$res['prodi']->jenjang." ".$res['prodi']->nama_program_studi;
        
        return $this->load->view('admin/akademik/status_perkuliahan/excel_rekap', $res);
    }

}