<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petikan_nilai extends CI_Controller {
    var $limit = 50;
    
    public function __construct() {
        parent::__construct();
        $this->load->service('NilaiService');
        $this->load->model(array(
            'akademik/Petikan_nilai_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_data_kurikulum',
            'akademik/mahasiswa_model',
            'akademik/krs_model',
        ));
        $this->load->library('pagination');

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
        $data['content'] = 'admin/akademik/petikan_nilai/V_Petikan_nilai';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Petikan Nilai';
        $data['judul_sub_judul'] ='';
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');
        $status_krs = $this->input->post('status_krs');
        
        $data_session = array(
            'input_angkatan' => $angkatan,
            'input_kode_program_studi' => $kode_program_studi,
            'status_krs' => $status_krs,
        );

        $this->session->set_userdata($data_session);
        redirect(site_url('admin/akademik/Petikan_nilai/data_mahasiswa_petikan_nilai'));
    }

    public function data_mahasiswa_petikan_nilai() {
        $data['content'] = 'admin/akademik/petikan_nilai/V_petikan_mahasiswa';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Petikan Nilai';
        
        $angkatan = $this->session->userdata('input_angkatan');
        $kode_porgram_studi = $this->session->userdata('input_kode_program_studi');
        $status_krs = $this->session->userdata('status_krs');
        
        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
        
        $data['data'] = $this->Petikan_nilai_model->filter($angkatan, $kode_porgram_studi, $status_krs, $this->limit, $offset);
        $data_count = count($this->Petikan_nilai_model->count_data_filter($angkatan, $kode_porgram_studi, $status_krs));

        if ($data_count > 0) {
            $config['base_url'] = site_url('admin/akademik/petikan_nilai/data_mahasiswa_petikan_nilai');
            $config['total_rows'] = $data_count;
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $uri_segment;
            $config['full_tag_open'] = '<div class="btn-group">';
            $config['full_tag_close'] = '</div>';
            $config['cur_tag_open'] = '<a href="#!" class="btn btn-sm flat btn-primary disabled">';
            $config['cur_tag_close'] = '</a>';
            $config['attributes'] = array('class' => 'btn flat btn-sm btn-default');

            $this->pagination->initialize($config);
            $data['halaman'] = $this->pagination->create_links();
            $data['jumlah_data'] = $data_count;
        } else {
            $this->session->set_flashdata('keterangan', 'Tidak ditemukan satupun data mahasiswa untuk Angkatan dan Jurusan !');
        }
        $this->load->view('admin/template/V_main', $data);
    }

    public function cari() {
        $data['content'] = 'admin/akademik/petikan_nilai/V_Cari';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Petikan Nilai';
        $this->load->view('admin/template/V_main', $data);
    }

    public function data_cari() {
        $cari = $this->input->post('cari');
        $data['content'] = 'admin/akademik/petikan_nilai/V_data_cari';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Petikan Nilai';
        $data_count = count($this->Petikan_nilai_model->data_cari($cari));
        $data['data'] = $this->Petikan_nilai_model->data_cari($cari);
        if ($data_count > 0) {
            $data['jumlah_data'] = $data_count;
        } else {
            $this->session->set_flashdata('keterangan', 'Data tidak ditemukan !');
        }
        $this->load->view('admin/template/V_main', $data);
    }

    public function detail($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'all');
        $data['content'] = 'admin/akademik/petikan_nilai/V_Detail';
        $data['judul'] = 'Akademik';
        $this->load->view('admin/akademik/petikan_nilai/V_Detail', $data);
    }

    public function detail_ganjil($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'ganjil');
        $data['content'] = 'admin/akademik/petikan_nilai/V_Detail_New';
        $data['judul'] = 'Akademik';
        $this->load->view('admin/akademik/petikan_nilai/V_Detail_New', $data);
    }

    public function detail_genap($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'genap');
        $data['content'] = 'admin/akademik/petikan_nilai/V_Detail_New';
        $data['judul'] = 'Akademik';
        $this->load->view('admin/akademik/petikan_nilai/V_Detail_New', $data);
    }
  
    public function cetak($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'all');

        $content = $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data, true);
        $header = $this->load->view('admin/akademik/petikan_nilai/header_petikan_nilai', $data, true);
        $namafile = $nim . "-Petikan_nilai.pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Legal', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 37, 'margin_bottom' => 10, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }

    public function cetak_ganjil($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'ganjil');

        $content = $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data, true);
        $header = $this->load->view('admin/akademik/petikan_nilai/header_petikan_nilai', $data, true);
        $namafile = $nim . "-Petikan_nilai.pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Legal', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 37, 'margin_bottom' => 10, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }

    public function cetak_genap($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'genap');

        $content = $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data, true);
        $header = $this->load->view('admin/akademik/petikan_nilai/header_petikan_nilai', $data, true);
        $namafile = $nim . "-Petikan_nilai.pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Legal', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 37, 'margin_bottom' => 10, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }

    public function print_view($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'all');
        $this->load->view('admin/akademik/petikan_nilai/print_view', $data);
    }
  
    public function print_view_ganjil($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'ganjil');
        $this->load->view('admin/akademik/petikan_nilai/print_view_new', $data);
    }

    public function print_view_genap($nim) {
        $data = $this->nilaiservice->get_petikan_nilai_data($nim, 'genap');
        $this->load->view('admin/akademik/petikan_nilai/print_view_new', $data);
    }
}
?>