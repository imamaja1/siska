<?php


class CekPembayaran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->service('MahasiswaService');
    }

    public function search(){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $nim = $this->input->post('nim');
        $data['nim'] = $nim;
        $data['data'] = $this->mahasiswaservice->getStatusPerkuliahan($nim, $kode_tahun_akademik);
        $this->load->view('extra/v_result_cek_pembayaran', $data);
    }
}