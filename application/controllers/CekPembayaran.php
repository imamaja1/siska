<?php


class CekPembayaran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->service('MahasiswaService');
    }

    public function search(){
        $ta = tahun_akademik();
        $kode_tahun_akademik = $ta ? $ta->kode_tahun_akademik : null;
        $nim = trim($this->input->post('nim', true));
        $data['nim'] = $nim;
        $data['ta'] = $ta;
        $data['mahasiswa'] = $this->mahasiswaservice->getMahasiswaRowByNim($nim);
        $data['data'] = $this->mahasiswaservice->getStatusPerkuliahan($nim, $kode_tahun_akademik);
        $this->load->view('extra/v_result_cek_pembayaran', $data);
    }
}