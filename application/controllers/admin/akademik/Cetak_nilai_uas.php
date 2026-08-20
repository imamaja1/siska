<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_nilai_uas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $this->load->service('NilaiService');
    }

    public function index($id) {
        $data = $this->nilaiservice->get_cetak_nilai_data($id);

        $namafile = $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $html = $this->load->view("admin/akademik/nilai/V_cetak_nilai", $data, true);
        $header = $this->load->view('admin/akademik/nilai/V_cetak_header', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }
}
?>
