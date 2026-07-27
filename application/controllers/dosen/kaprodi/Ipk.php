<?php

class Ipk extends CI_Controller
{
    var $limit = 25;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'laporan/laporan_model',
        ));
        $this->load->service('KaprodiService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['content'] = 'dosen/kaprodi/ipk/v_index';
        $data['judul'] = 'Rekap IPK';
        $data['sub_judul'] = 'Rekap IPK';
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('dosen/template/V_main', $data);
    }

    public function filter()
    {
        $angkatan = $this->input->post('angkatan');
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $kode_program_studi = $this->input->post('prodi');

        $data_session = array(
            'sess_tahun_angkatan' => $angkatan,
            'sess_kode_tahun_akademik' => $kode_tahun_akademik,
            'sess_kode_program_studi' => $kode_program_studi
        );
        $this->session->set_userdata($data_session);
        redirect(site_url('dosen/kaprodi/ipk/data_rekap_ipk'));
    }

    public function data_rekap_ipk($offset = 0)
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        if (!$kode_program_studi || !$tahun_angkatan || !$kode_tahun_akademik) {
            echo 'Silakan lakukan filter terlebih dahulu.';
            return;
        }
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);

        $data_count = $this->laporan_model->rekap_ipk_count($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);

        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Data Rekap IPK';
        $data['halaman'] = $this->pagination->create_links();
        $data['jumlah_data'] = $data_count;
        $data['data'] = $this->laporan_model->rekap_ipk($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
        $data['prodi'] = $prodi;
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);

        $this->load->view('dosen/kaprodi/ipk/v_data_ipk', $data);
    }

    public function cetak_new()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);
        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        $datas = $this->laporan_model->rekap_all_ipk($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
        $semester = $ta->semester == 0 ? 'Genap' : 'Ganjil';
        $data['file_name'] = 'Rekap IPK - ' . $prodi->singkatan_program_studi . ' - Angkatan: 20' . $tahun_angkatan . ' - TA : ' . $ta->tahun_akademik . '-' . $semester;

        require_once FCPATH . 'vendor/autoload.php';
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $excel->getProperties()->setCreator('SISKA')
            ->setLastModifiedBy('Administrator');

        $style_col = array(
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'right' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'left' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            )
        );

        $style_row = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'right' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'left' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', $data['file_name']);
        $excel->getActiveSheet()->mergeCells('A1:G1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NIM");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Nama");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "SKS");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "IP");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "IPK");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Total SKS");

        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);

        $no = 1;
        $numrow = 4;
        $total_ipk = 0;
        $jumlah_data = count($datas);
        foreach ($datas as $data) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data['nim']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['nama_mahasiswa']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['sks']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data['ip']);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data['ipk']);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data['total_sks']);

            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
            $total_ipk = $total_ipk + $data['ipk'];
        }
        $row_total = $numrow + 1;
        $rata_ipk = number_format($total_ipk / $jumlah_data, 2);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $row_total, 'Rata - Rata IPK');
        $excel->getActiveSheet()->mergeCells('A' . $row_total . ':E' . $row_total);
        $excel->setActiveSheetIndex(0)->setCellValue('F' . $row_total, $rata_ipk);

        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        $excel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $excel->getActiveSheet(0)->setTitle("Laporan IPK");
        $excel->setActiveSheetIndex(0);

        $filename = 'Rekap IPK - ' . $prodi->singkatan_program_studi . ' - Angkatan: 20' . $tahun_angkatan . ' - TA : ' . $ta->tahun_akademik . '-' . $semester . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=" . $filename);
        header('Cache-Control: max-age=0');

        $write = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $write->save('php://output');

    }

    public function cari()
    {
        $key = $this->input->post('keyword');
        $this->session->set_userdata(array('sess_keyword' => $key));
        redirect(site_url('dosen/kaprodi/ipk/data_cari'));
    }

    public function data_cari()
    {
        $keyword = $this->session->userdata('sess_keyword');
        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }
        $data['data'] = $this->konsultasi_perwalian_model->cari($keyword, $this->limit, $offset);
        $data_count = count($this->konsultasi_perwalian_model->count_cari($keyword));

        if ($data_count > 0) {
            $config['base_url'] = site_url('dosen/kaprodi/ipk/data_cari');
            $config['total_rows'] = $data_count;
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $uri_segment;

            $config['full_tag_open'] = '<div class="btn-group" id="halaman">';
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
        return $this->load->view('dosen/kaprodi/ipk/v_pencarian', $data);
    }

    public function grafik_nilai($nim)
    {
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data['mahasiswa'] = $this->kaprodiservice->get_mahasiswa_ipk($nim);
        $data_krs = $this->kaprodiservice->get_krs_ipk($nim);
        $i = 0;
        foreach ($data_krs as $row) {
            $data['ipk'][$i] = $this->laporan_model->ipok($nim, $kode_nama_kurikulum, $row->kode_tahun_akademik)['ipk'];
            $data['semester'][$i] = 'Semester ' . $row->semester;
            $i++;
        }
        return $this->load->view('dosen/kaprodi/ipk/list', $data);
    }
}
