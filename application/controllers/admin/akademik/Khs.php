<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Khs extends CI_Controller {
    var $limit = 50;
    
    public function __construct() {
        parent::__construct();
        $this->load->service('KrsKhsService');
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'akademik/Khs_model',
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
        $data['content'] = 'admin/akademik/khs/V_Khs';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'KHS';
        $data['judul_sub_judul'] ='';
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');
        $semester = $this->input->post('semester');

        $data_session = array(
            'input_angkatan' => $angkatan,
            'input_semester' => $semester,
            'input_kode_program_studi' => $kode_program_studi,
        );

        $this->session->set_userdata($data_session);
        redirect(site_url('admin/akademik/Khs/data_mahasiswa_khs'));
    }

    public function data_mahasiswa_khs() {
        $data['content'] = 'admin/akademik/khs/V_khs_mahasiswa';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'KHS';

        $angkatan = $this->session->userdata('input_angkatan');
        $semester = $this->session->userdata('input_semester');
        $kode_porgram_studi = $this->session->userdata('input_kode_program_studi');
        
        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
        
        $data['data'] = $this->Khs_model->filter($angkatan, $kode_porgram_studi, $semester, $this->limit, $offset);
        $data_count = count($this->Khs_model->count_data_filter($angkatan, $kode_porgram_studi, $semester));

        if ($data_count > 0) {
            $config['base_url'] = site_url('admin/akademik/khs/data_mahasiswa_khs');
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

    public function lihat_khs($kode_krs, $nim) {
        $data['content'] = 'admin/akademik/khs/V_lihat_khs';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'KHS';
        $data['data'] = $this->krskhsservice->generate_khs_data($kode_krs, $nim);
        $data['prodi'] = get_kode_prodi($nim);

        $this->load->view('admin/template/V_main', $data);
    }

    public function cetak($kode_krs, $nim) {
        $khs_data = $this->krskhsservice->generate_khs_data($kode_krs, $nim);
        if(!$khs_data) return;

        $data['data'] = $khs_data;
        $data['prodi'] = $khs_data['prodi'];
        
        $nik = bodo_kop($nim)['nik'];
        $dosen = $this->krskhsservice->getDosenSignature($nik);
        $data['signature'] = $dosen ? $dosen->signature : '';

        $namafile = $nim . "-KHS.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $html = $this->load->view('admin/akademik/khs/Cetak', $data, true);
        $header = $this->load->view('admin/akademik/khs/Header_khs', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }

    public function print_view($kode_krs, $nim) {
        $khs_data = $this->krskhsservice->generate_khs_data($kode_krs, $nim);
        if(!$khs_data) return;

        $data['data'] = $khs_data;
        $data['prodi'] = $khs_data['prodi'];
        $this->load->view('admin/akademik/khs/print_view', $data);
    }

    public function maksimum_sks($nim, $tahun_akademik, $kode_program_studi) {
        $res = $this->krskhsservice->maksimum_sks($nim, $tahun_akademik, $kode_program_studi);
        return $res['beban_sks'];
    }

    public function serch() {
        $data['content'] = 'admin/akademik/khs/V_serch';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Search';
        $this->load->view('admin/template/V_main', $data);
    }

    public function find($keyword = null) {
        if ($keyword == null) {
            $data['data'] = 0;
        } else {
            $query = $this->krskhsservice->searchKrs($keyword);
            $data['data'] = $query;
        }
        $this->load->view('admin/akademik/khs/V_find', $data);
    }

    public function print_excel($kode_krs, $nim) {
        $khs_data = $this->krskhsservice->generate_khs_data($kode_krs, $nim);
        if(!$khs_data) return;

        $data['data'] = $khs_data;
        $data['prodi'] = $khs_data['prodi'];

        $table = '<table style="text-align: left;">';
        $table .= '<tr style="align:left"><td>Nama Mahasiswa</td><td>'. $khs_data['nama_mahasiswa'].'</td></tr>';
        $table .= '<tr><td style="align:left">NIM</td><td>'. $khs_data['nim'].'</td></tr>';
        $table .= '<tr><td>Semester</td><td>'. $khs_data['semester'].'</td></tr>';
        $table .= '<tr><td>Kurikulum</td><td>'. $khs_data['kurikulum'].'</td></tr>';
        $table .= '</table>';
        
        $table .= '<table style="text-align: left;" border="1">';
        $table .= '<tr><th colspan ="7" style="align:center">KHS - ' . $khs_data['prodi']->singkatan_program_studi  . ' - TA : ' . $khs_data['tahun_akademik']->tahun_akademik.'</th></tr>';
        $table .= '<tr><th>NO.</th><th>KODE</th><th>MATAKULIAH</th><th>SKS (SEMESTER)</th><th>GRADE</th><th>SKSN</th><th>KET</th></tr>';
        
        $total_ipk = 0;
        $jumlah_data = 0;
        
        foreach ($khs_data['data_nilai'] as $key => $value) {
            $table .= '<tr>';
            $table .= '<td>'.strval($key+1).'.</td>';
            $table .= '<td>'.$value['kode_matakuliah'].'</td>';
            $table .= '<td>'.$value['nama_matakuliah'].'</td>';
            $table .= '<td>'.$value['sks'].'</td>';
            $table .= '<td>'.$value['grade'].'</td>';
            $table .= '<td>'.$value['sksn'].'</td>';
            $table .= '<td></td>';
            $table .= '</tr>';
            $total_ipk += $value['sksn'];
            $jumlah_data += $value['sks'];
        }
        
        $table .= '<tr><td colspan="5" style="text-align: center"><b>Total SKSN</b></td><td style="text-align: left; font-weight: bold" colspan="1">'.number_format($total_ipk,2).'</td></tr>';
        $table .= '<tr><td colspan="5" style="text-align: center"><b>IPK Rata-rata</b></td><td style="text-align: left; font-weight: bold" colspan="1">'.($jumlah_data > 0 ? sprintf("%.2f",$total_ipk/$jumlah_data) : '0').'</td></tr>';
        $table .= '</table>';

        $data['table'] = $table;
        $data['file_name'] = 'Rekap KHS -'.$khs_data['nim'].'-'.$khs_data['semester'].'('.$khs_data['nama_mahasiswa'].')';
        $this->load->view('admin/laporan/rekap_ipk/V_spreadsheet_view', $data);
    }
}
?>