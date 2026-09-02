<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class cetak_nilai extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect(site_url('login_admin/login'));
        }
        $this->load->library('qrcode/ciqrcode');
        $this->load->service('DosenAkademikService');
    }

    public function index($id) {
        $data['query1'] = $this->dosenakademikservice->getCetakQuery1($id);
        $data['query2'] = $this->dosenakademikservice->getCetakQuery2($id);
        $data['query3'] = $this->dosenakademikservice->getCetakQuery3($id);
        $data['query4'] = $this->dosenakademikservice->getCetakQuery4();
        $data['nama_dosen'] = $this->dosenakademikservice->getCetakNamaDosen($id);

       // $data['data'] = base_url() . 'verifikasi/nilai/' . hash("sha256", $id);
       // $data['level'] = 'C';
       // $data['size'] = 2;
       // $data['savename'] = FCPATH . 'tes.png';
       // $this->ciqrcode->generate($data);

        $namafile = $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";
        $this->load->library('pdf');
        $this->pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->pdf;
        $html = $this->load->view("dosen/V_cetak_nilai", $data, true);
        $header = $this->load->view('dosen/V_cetak_header', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");

//      $this->load->view("dosen/V_cetak_nilai", $data);
    }

}
