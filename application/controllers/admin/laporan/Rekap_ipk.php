<?php

class Rekap_ipk extends CI_Controller
{
    var $limit = 50;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'laporan/laporan_model',
        ));
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
    }

    public function index()
    {
        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Laporan Rekap IPK Mahasiswa';
        $data['content'] = 'admin/laporan/rekap_ipk/V_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter()
    {
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $tahun_angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');

//        $kode = $this->Nama_jurusan_model->get_kode($kode_program_studi);
//        $kode_jurusan = $this->Kode_jurusan_model->get_kode($kode->id_jurusan)->kode_jurusan;
//        $kode_jenjang = $this->Jenjang_model->get_kode($kode->id_jenjang)->kode_jenjang;
//        Data sesson
        $data_session = array(
            'sess_kode_tahun_akademik' => $kode_tahun_akademik,
            'sess_tahun_angkatan' => $tahun_angkatan,
            'sess_kode_program_studi' => $kode_program_studi,
//            'sess_kode_jurusan' => $kode_jurusan,
//            'sess_kode_jenjang' => $kode_jenjang
        );

        $this->session->set_userdata($data_session);

        redirect(site_url('admin/laporan/rekap_ipk/data_rekap_ipk'));
    }

    public function data_rekap_ipk($offset = 0)
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);

        $this->load->library('pagination');
//         Offset
        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }

        $data_count = $this->laporan_model->rekap_ipk_count($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);


        $config = array(
            'base_url' => site_url('admin/laporan/rekap_ipk/data_rekap_ipk'),
            'total_rows' => $data_count,
            'per_page' => $this->limit,
            'uri_segment' => $uri_segment,
            'full_tag_open' => '<div class="btn-group">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a href="#!" class="btn btn-sm btn-primary disabled">',
            'cur_tag_close' => '</a>',
            'attributes' => array('class' => 'btn btn-sm btn-default'),
        );

        $this->pagination->initialize($config);

        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Data Rekap IPK';
        $data['content'] = 'admin/laporan/rekap_ipk/V_rekap_ipk';
        $data['halaman'] = $this->pagination->create_links();
        $data['jumlah_data'] = $data_count;
        $data['data'] = $this->laporan_model->rekap_ipk_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi,$this->limit, $offset);
        $data['prodi'] = $prodi;
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);

        $this->load->view('admin/template/V_main', $data);
    }
  
  // tes exsport menggunakan phpexcel
    public function cetak_new()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);
        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        $datas = $this->laporan_model->rekap_all_ipk($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
//        $data['data'] = $data;
//        echo '<pre>';
//        var_dump($data['data']);
//        die();
        $semester = $ta->semester == 0 ? 'Genap' : 'Ganjil';
        $data['file_name'] = 'Rekap IPK - ' . $prodi->singkatan_program_studi . ' - Angkatan: 20' . $tahun_angkatan . ' - TA : ' . $ta->tahun_akademik . '-' . $semester;

//        $this->load->view('admin/laporan/rekap_ipk/V_spreadsheet_view_new', $data);
        // Panggil class PHPExcel nya
//        $this->load->library("PHPExcel");
        require_once FCPATH . 'vendor/autoload.php';
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Settingan awal fil excel
        $excel->getProperties()->setCreator('SISKA')
            ->setLastModifiedBy('Administrator');

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', $data['file_name']); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NIM"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Nama"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "SKS"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "IP"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "IPK"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Total SKS"); // Set kolom E3 dengan tulisan "ALAMAT"

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        $jumlah_data = count($datas);
      
        foreach ($datas as $data) { // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data['nim']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['nama_mahasiswa']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['sks']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data['ip']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data['ipk']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data['total_sks']);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
            $total_ipk = $total_ipk + $data['ipk'];
        }
      
      
        $row_total = $numrow + 1;
        $rata_ipk = number_format($total_ipk / $jumlah_data, 2);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $row_total, 'Rata - Rata IPK');
        $excel->getActiveSheet()->mergeCells('A' . $row_total . ':E' . $row_total);
        $excel->setActiveSheetIndex(0)->setCellValue('F' . $row_total, $rata_ipk);

        // Set width kolom
        // TODO::setwidht auto
        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true); // Set width kolom D

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan IPK");
        $excel->setActiveSheetIndex(0);

        $filename = 'Rekap IPK - ' . $prodi->singkatan_program_studi . ' - Angkatan: 20' . $tahun_angkatan . ' - TA : ' . $ta->tahun_akademik . '-' . $semester . '.xlsx';

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=" . $filename);
        header('Cache-Control: max-age=0');

        $write = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $write->save('php://output');

    }

    public function ipk_rata($offset = 0){
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);

        $this->load->library('pagination');
//         Offset
        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }

        $data_count = $this->laporan_model->ipk_rata_count($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);

        $config = array(
                'base_url' => site_url('admin/laporan/rekap_ipk/ipk_rata'),
                'total_rows' => $data_count,
                'per_page' => $this->limit,
                'uri_segment' => $uri_segment,
                'full_tag_open' => '<div class="btn-group">',
                'full_tag_close' => '</div>',
                'cur_tag_open' => '<a href="#!" class="btn btn-sm btn-primary disabled">',
                'cur_tag_close' => '</a>',
                'attributes' => array('class' => 'btn btn-sm btn-default'),
        );

        $this->pagination->initialize($config);
        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Data Rata-rata IPK';
        $data['content'] = 'admin/laporan/rekap_ipk/V_ipk_rata';
        $data['halaman'] = $this->pagination->create_links();
        $data['jumlah_data'] = $data_count;
        $data['data'] = $this->laporan_model->ipk_rata_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi,$this->limit, $offset);
        $data['prodi'] = $prodi;
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);
        // echo json_encode($data['data']);
        $this->load->view('admin/template/V_main', $data);
    }

    public function cetak_ipk_rata(){
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);

        $data = $this->laporan_model->all_ipk_rata_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
        
        $table = '<table border="1">';
        $table .= '<tr>';
        $table .= '<th>NO.</th>';
        $table .= '<th>NIM</th>';
        $table .= '<th>NAMA MAHASISWA</th>';
        $table .= '<th>L/P</th>';
        $table .= '<th>IP (SEMESTER)</th>';
        $table .= '<th>IPK</th>';
        $table .= '<th>SKS</th>';
        $table .= '<th>KET</th>';
        $table .= '<th>KKP</th>';
        $table .= '<th>PRAKTIKUM</th>';
        $table .= '</tr>';
        $i=1;
        $total_ipk = 0;
        $jumah_data = count($data);
        foreach ($data as $row) :
            $table .= '<tr>';
            $table .= '<td>'.$i++.'.</td>';
            $table .= '<td>'. $row->nim.'</td>';
            $table .= '<td>'. $row->nama_mahasiswa.'</td>';
            $table .= '<td>'. $row->jenis_kelamin.'</td>';
            $table .= '<td>'. $row->ip.'</td>';
            $table .= '<td>'. $row->ipk.'</td>';
            $table .= '<td>'. $row->total_sks.'</td>';
            if ($row->skripsi == 0){
                $table .= '<td></td>';
            }else{
                $table .= '<td>SKRIPSI</td>';
            }
            if ($row->kkp == 0){
                $table .= '<td></td>';
            }else{
                $table .= '<td>KKP</td>';
            }
            if ($row->praktikum == 0){
                $table .= '<td></td>';
            }else{
                $table .= '<td>PRAKTIKUM</td>';
            }
            $table .= '</tr>';
            $total_ipk = $total_ipk + $row->ipk;
        endforeach;
        $table .= '<tr>';
        $table .= '<td colspan="5" style="text-align: center"><b>IPK Rata-rata</b></td>';
        $table .= '<td style="text-align: center; font-weight: bold">'.number_format($total_ipk/$jumah_data,2).'</td>';
        $table .= '<td colspan="4">-</td>';
        $table .= '</tr>';
        $table .= '</table>';

        $data['table'] = $table;
        $data['file_name'] = $prodi->singkatan_program_studi.'-'.$prodi->nama_program_studi;

        $this->load->view('admin/laporan/rekap_ipk/V_spreadsheet_view', $data);
    }
	public function cetak_baru()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);
        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        $semester = $ta->semester == 0 ? 'Genap' : 'Ganjil';

        $data = $this->laporan_model->cetak_rekap_ipk_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
      
         $table .= '<table border="1">';
        $table .= '<tr>';
        $table .= '<th colspan ="7" style="align:center">Rekap IPK - ' . $prodi->singkatan_program_studi . ' - Angkatan: 20' . $tahun_angkatan . ' - TA : ' . $ta->tahun_akademik . '-' . $semester.'</th>';
        $table .= '<tr>';
        $table .= '</tr>';
        $table .= '</tr>';
        $table .= '<tr>';
        $table .= '<th>NO.</th>';
        $table .= '<th>NIM</th>';
        $table .= '<th>NAMA MAHASISWA</th>';
        $table .= '<th>SKS (SEMESTER)</th>';
        $table .= '<th>IP (SEMESTER)</th>';
        $table .= '<th>IPK</th>';
        $table .= '<th>SKS</th>';
        $table .= '</tr>';
        $i=1;
        $total_ipk = 0;
        $jumah_data = count($data);
        foreach ($data as $row) :
            $table .= '<tr>';
            $table .= '<td>'.$i++.'.</td>';
            $table .= '<td>'. $row->nim.'</td>';
            $table .= '<td>'. $row->nama_mahasiswa.'</td>';
            $table .= '<td>'. $row->sks.'</td>';
            $table .= '<td>'. $row->ip.'</td>';
            $table .= '<td>'. $row->ipk.'</td>';
            $table .= '<td>'. $row->total_sks.'</td>';
            $table .= '</tr>';
            $total_ipk = $total_ipk + $row->ipk;
        endforeach;
        $table .= '<tr>';
        $table .= '<td colspan="5" style="text-align: center"><b>IPK Rata-rata</b></td>';
        $table .= '<td style="text-align: center; font-weight: bold">'.number_format($total_ipk/$jumah_data,2).'</td>';
        $table .= '<td colspan="1">-</td>';
        $table .= '</tr>';
        $table .= '</table>';

        $data['table'] = $table;
        $data['file_name'] = $prodi->singkatan_program_studi.'-'.$prodi->nama_program_studi;
        $this->load->view('admin/laporan/rekap_ipk/V_spreadsheet_view', $data);
    }
}