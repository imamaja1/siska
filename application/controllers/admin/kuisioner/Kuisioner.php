<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kuisioner extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'kuisioner/kuisioner_model',
            'kuisioner/kelas_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
        ));
        $this->load->service('KuisionerService');
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    function index()
    {
        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = 'Kuisioner |';
        $data['content'] = 'admin/kelas/V_kuisioner';
        $data['status'] = $this->kuisioner_model->get_setting();
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function aktif()
    {
        $data_ubah = array(
            'aktif_kuisioner' => 'A',
        );
        $this->kuisioner_model->update_setting($data_ubah);

        redirect(site_url('admin/kuisioner/kuisioner'));
    }

    public function nonaktif()
    {
        $data_ubah = array(
            'aktif_kuisioner' => 'N',
        );
        $this->kuisioner_model->update_setting($data_ubah);

        redirect(site_url('admin/kuisioner/kuisioner'));
    }

    public function combobox($kode_tahun_akademik)
    {
        $data['data'] = $this->kelas_model->get_matakuliah_by_kode_tahun_akademik($kode_tahun_akademik);
        if (count($data['data'])> 0)
        {
            $this->load->view('admin/kelas/dropbox', $data);

        }else{
            echo 'data tidak ditemukan';
        }
    }

    public function get_kelas($id_matakuliah, $kode_tahun_akademik)
    {
//        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data = $this->kelas_model->get_kelas_by_kode_makul($id_matakuliah, $kode_tahun_akademik);
        if (count($data) > 0)
        {
            foreach ($data as $row)
            {
                echo '<option value="'.$row->kelas_id.'" > Kelas - '.$row->nama_kelas.'</option>';
            }
        }else{
          echo '<option value="0">Kelas Belum dibagi</option>';
            //echo 'Data tidak ditemukan';
        }
    }

    public function filter()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $kelas_id = $this->input->post('kelas_id');

        $data_sess = array(
            'kode_tahun_akademik_sess' => $kode_tahun_akademik,
            'id_matakuliah_sess' => $id_matakuliah,
            'kelas_id_sess' => $kelas_id,
        );
        $this->session->set_userdata($data_sess);
        redirect(site_url('admin/kuisioner/kuisioner/data_kuisioner'));
    }

    public function data_kuisioner()
    {
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik_sess');
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $kelas_id= $this->session->userdata('kelas_id_sess');
        $kelas = $this->kelas_model->get_nama_kelas_by_kelas_id($kelas_id);

        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = 'Hasil Kuisioner |';
        $data['content'] = 'admin/kelas/V_hasil_kuisioner';
        $data['data'] = $this->kuisioner_model->get_hasil_kuisioner($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $data['top'] = $this->kuisioner_model->get_matakuliah_dan_dosen($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $data['nama_kelas'] = $kelas->nama_kelas;
        $this->load->view('admin/template/V_main', $data);
    }

    public function cetak_kuisioner()
    {
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik_sess');
        $id_matakuliah= $this->session->userdata('id_matakuliah_sess');
        $kelas_id= $this->session->userdata('kelas_id_sess');
        $kelas = $this->kelas_model->get_nama_kelas_by_kelas_id($kelas_id);
        $data['top'] = $this->kuisioner_model->get_matakuliah_dan_dosen($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $data['nama_kelas'] = $kelas->nama_kelas;
        $data['tahun_akademik'] = $tahun_akademik;
        $data['data'] = $this->kuisioner_model->get_hasil_kuisioner($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $data['file_name'] = 'Kusisioner Kelas-'.$kelas->nama_kelas.'-'. $data['top']['nama_matakuliah']->nama_matakuliah;

        $this->load->view('admin/kelas/V_cetak_kuisioner', $data);

    }

    public function cetakAll($kode_tahun_akademik)
    {
        $data['kode_tahun_akademik'] = $kode_tahun_akademik;

                $this->load->view('admin/kelas/V_cetak_all',$data);


    }

    public function excel()
    {

        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik_sess');
        $id_matakuliah= $this->session->userdata('id_matakuliah_sess');
        $kelas_id= $this->session->userdata('kelas_id_sess');
        $kelas = $this->kelas_model->get_nama_kelas_by_kelas_id($kelas_id);
        $data['top'] = $this->kuisioner_model->get_matakuliah_dan_dosen($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $data['nama_kelas'] = $kelas->nama_kelas;
        $data['tahun_akademik'] = $tahun_akademik;
        $data['data'] = $this->kuisioner_model->get_hasil_kuisioner($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $file_name = 'Kusisioner Kelas-'.$kelas->nama_kelas.'-'. $data['top']['nama_matakuliah']->nama_matakuliah;

        require_once FCPATH . 'vendor/autoload.php';

        $object = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $object->setActiveSheetIndex(0);
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000'),
                'size'  => 11,
                'name'  => 'Calibri'
            )
        );

        $styleData = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'color' => array('rgb' => '000'),
                'size'  => 11,
                'name'  => 'Calibri'
            )
        );

        $table_columns = array("No.","Nip","Nama", "Alamat","Telepon");

        $column = 0;

        foreach($table_columns as $field){

            $object->getActiveSheet()->setCellValueByColumnAndRow($column + 1, 1, $field);
            $object->getActiveSheet()->getStyle("A1:E1")->applyFromArray($styleArray);

            $column++;

        }

//        $employee_data =  $this->dao->read($this->table)->result();
//
//        $excel_row = 2;
//        $no=1;
//
//        foreach($employee_data as $row){
//
//            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->nip);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->nama);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->alamat);
//            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->telp);
//            $object->getActiveSheet()->getStyle("A".$excel_row.":E".$excel_row)->applyFromArray($styleData);
//
//            $excel_row++;
//
//        }

        $object_writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($object);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="Data dokter.xls"');

        $object_writer->save('php://output');
    }
    public function cetak_all_pmb()
    {
        $kode_tahun_akademik = $this->input->get('kode_tahun_akademik');
        $kode_program_studi = $this->input->get('kode_program_studi');

        require_once FCPATH . 'vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Sheet1');
        $first = true;

        $kelas_list = $this->kuisionerservice->getKelasByTahunProdi($kode_tahun_akademik, $kode_program_studi);

        if (!$kelas_list) {
            show_error('Tidak ada data kelas untuk filter yang dipilih.');
            return;
        }

        $tahun_akademik_obj = $this->kuisionerservice->getTahunAkademikByKode($kode_tahun_akademik);
        $ta_label = $tahun_akademik_obj ? $tahun_akademik_obj->tahun_akademik . ' - ' . ($tahun_akademik_obj->semester == 1 ? 'Ganjil' : 'Genap') : '';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Sheet1');
        $first = true;

        $styleHeader = [
            'borders' => ['allBorders' => ['style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]],
            'font' => ['bold' => true, 'size' => 11, 'name' => 'Calibri'],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $styleData = [
            'borders' => ['allBorders' => ['style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'font' => ['size' => 10, 'name' => 'Calibri'],
        ];
        $styleBold = ['font' => ['bold' => true, 'size' => 11, 'name' => 'Calibri']];

        foreach ($kelas_list as $kl) {
            $kelas_id = $kl->kelas_id;
            $id_matakuliah = $kl->id_matakuliah;

            if ($first) {
                $sheet = $spreadsheet->getActiveSheet();
                $first = false;
            } else {
                $sheet = $spreadsheet->createSheet();
            }
            $sheet->setTitle(substr($kl->nama_kelas, 0, 31));

            $top = $this->kuisioner_model->get_matakuliah_dan_dosen($kode_tahun_akademik, $id_matakuliah, $kelas_id);
            $hasil = $this->kuisioner_model->get_hasil_kuisioner($kode_tahun_akademik, $id_matakuliah, $kelas_id);
            if (!isset($hasil['hasil'])) $hasil['hasil'] = [];

            $dosen_str = '';
            if (isset($top['dosen']) && count($top['dosen']) > 0) {
                foreach ($top['dosen'] as $d) {
                    $dosen_str .= $d->nama_dosen . ', ';
                }
                $dosen_str = rtrim($dosen_str, ', ');
            }
            $matakuliah_str = isset($top['nama_matakuliah']) ? $top['nama_matakuliah']->nama_matakuliah : '';

            $r = 1;
            $sheet->setCellValue('A' . $r, 'HASIL KUISIONER PBM');
            $sheet->getStyle('A' . $r)->getFont()->setBold(true)->setSize(13);
            $r += 2;
            $sheet->setCellValue('A' . $r, 'Tahun Akademik: ' . $ta_label); $r++;
            $sheet->setCellValue('A' . $r, 'Matakuliah: ' . $matakuliah_str); $r++;
            $sheet->setCellValue('A' . $r, 'Kelas: ' . $kl->nama_kelas); $r++;
            $sheet->setCellValue('A' . $r, 'Dosen: ' . $dosen_str); $r += 2;

            $has_praktikum = isset($hasil['soal_kuisioner']['P']);

            if ($has_praktikum) {
                $parts = ['T' => 'Teori', 'P' => 'Praktikum'];
            } else {
                $parts = ['T' => ''];
                $hasil['soal_kuisioner'] = ['T' => $hasil['soal_kuisioner']];
                $hasil['jumlah_soal'] = ['T' => $hasil['jumlah_soal']];
            }

            foreach ($parts as $jenis => $label) {
                $soal = $hasil['soal_kuisioner'][$jenis];
                $data_hasil = isset($hasil['hasil'][$jenis]) ? $hasil['hasil'][$jenis] : (isset($hasil['hasil']) && !$has_praktikum ? $hasil['hasil'] : []);

                if ($label) {
                    $sheet->setCellValue('A' . $r, 'KUISIONER ' . $label);
                    $sheet->getStyle('A' . $r)->getFont()->setBold(true);
                    $r++;
                }

                $col = 1;
                $c = function($i) { return chr(64 + $i); };
                $sheet->setCellValue($c($col++) . $r, 'No');
                $kategori_cols = [];
                foreach ($soal as $kat) {
                    $start_col = $col;
                    for ($i = 0; $i < $kat->colspan; $i++) {
                        $sheet->setCellValue($c($col++) . $r, $kat->kategori);
                    }
                    $kategori_cols[] = ['start' => $start_col, 'end' => $col - 1];
                }
                $skor_col = $col;
                $sheet->setCellValue($c($col++) . $r, 'Skor');
                $tingkatkan_col = $col;
                $sheet->setCellValue($c($col++) . $r, 'Di tingkatkan');
                $pertahankan_col = $col;
                $sheet->setCellValue($c($col++) . $r, 'Di pertahankan');
                $total_cols = $col - 1;

                foreach ($kategori_cols as $kc) {
                    if ($kc['end'] > $kc['start']) {
                        $sheet->mergeCells($c($kc['start']) . $r . ':' . $c($kc['end']) . $r);
                    }
                }

                $sheet->getStyle($c(1) . $r . ':' . $c($total_cols) . $r)->applyFromArray($styleHeader);
                $r++;

                $no = 1;
                $total_scores = [];
                foreach ($data_hasil as $km_id => $items) {
                    $col = 1;
                    $sheet->setCellValue($c($col++) . $r, $no++);
                    $idx = 0;
                    foreach ($items as $val) {
                        $sheet->setCellValue($c($col++) . $r, $val->hasil);
                        if (!isset($total_scores[$idx])) $total_scores[$idx] = 0;
                        $total_scores[$idx] += $val->hasil;
                        $idx++;
                    }
                    $sheet->setCellValue($c($skor_col) . $r, '');
                    $sheet->setCellValue($c($tingkatkan_col) . $r, isset($val->kritik) ? $val->kritik : '');
                    $sheet->setCellValue($c($pertahankan_col) . $r, isset($val->saran) ? $val->saran : '');

                    $sheet->getStyle($c(1) . $r . ':' . $c($total_cols) . $r)->applyFromArray($styleData);
                    $r++;
                }

                if (count($total_scores) > 0) {
                    $col = 1;
                    $sheet->setCellValue($c($col++) . $r, 'Jumlah');
                    $sheet->getStyle($c(1) . $r)->applyFromArray($styleBold);
                    $grand_total = 0;
                    foreach ($total_scores as $ts) {
                        $sheet->setCellValue($c($col++) . $r, $ts);
                        $grand_total += $ts;
                    }
                    $sheet->setCellValue($c($skor_col) . $r, '');
                    $sheet->setCellValue($c($tingkatkan_col) . $r, '');
                    $sheet->setCellValue($c($pertahankan_col) . $r, '');
                    $r++;

                    $col = 1;
                    $sheet->setCellValue($c($col++) . $r, 'Rata-rata');
                    $jml_mah = count($data_hasil) > 0 ? count($data_hasil) : 1;
                    foreach ($total_scores as $ts) {
                        $sheet->setCellValue($c($col++) . $r, round($ts / $jml_mah, 1));
                    }
                    $sheet->setCellValue($c($skor_col) . $r, round($grand_total / ($jml_mah * count($total_scores)), 2));
                    $sheet->setCellValue($c($tingkatkan_col) . $r, '');
                    $sheet->setCellValue($c($pertahankan_col) . $r, '');

                    $styleRata = $styleData;
                    $styleRata['font']['bold'] = true;
                    $sheet->getStyle($c(1) . ($r - 1) . ':' . $c($total_cols) . $r)->applyFromArray($styleRata);
                    $r += 2;
                }
            }

            foreach (range(1, $total_cols) as $ci) {
                $sheet->getColumnDimensionByColumn($ci)->setAutoSize(true);
            }
        }

        $filename = 'Download-PBM-' . ($tahun_akademik_obj ? $tahun_akademik_obj->tahun_akademik : $kode_tahun_akademik) . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
        $writer->save('php://output');
    }
    public function download_pmb()
    {
        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = 'Download PMB |';
        $data['content'] = 'admin/kelas/pmb/V_download';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function kelas_json()
    {
        $kode_tahun_akademik = $this->input->get('kode_tahun_akademik');
        $kode_program_studi = $this->input->get('kode_program_studi');
        $data = $this->kuisionerservice->getKelasByTahunProdi($kode_tahun_akademik, $kode_program_studi);
        echo json_encode($data);
    }

    public function cetak_pmb($kelas_id)
    {
        $kode_tahun_akademik = $this->input->get('kode_tahun_akademik');
        if (!$kode_tahun_akademik) {
            $row = $this->db->select('kode_tahun_akademik')->from('kelas')->where('kelas_id', $kelas_id)->get()->row_object();
            $kode_tahun_akademik = $row ? $row->kode_tahun_akademik : tahun_akademik()->kode_tahun_akademik;
        }
        $kelas = $this->kelas_model->get_nama_kelas_by_kelas_id($kelas_id);
        if (!$kelas) {
            show_404();
            return;
        }
        $id_matakuliah = $kelas->id_matakuliah;
        $tahun_akademik_obj = $this->kuisionerservice->getTahunAkademikByKode($kode_tahun_akademik);
        if (!$tahun_akademik_obj) {
            $tahun_akademik_obj = $this->m_tahun_akademik->get_semester();
        } else {
            $tahun_akademik_obj->ta = $tahun_akademik_obj->tahun_akademik;
        }

        $data['top'] = $this->kuisioner_model->get_matakuliah_dan_dosen($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        $data['nama_kelas'] = $kelas->nama_kelas;
        $data['tahun_akademik'] = $tahun_akademik_obj;
        $hasil = $this->kuisioner_model->get_hasil_kuisioner($kode_tahun_akademik, $id_matakuliah, $kelas_id);
        if (!isset($hasil['hasil'])) {
            $hasil['hasil'] = [];
        }
        $data['data'] = $hasil;
        $nama_matakuliah = isset($data['top']['nama_matakuliah']) ? $data['top']['nama_matakuliah']->nama_matakuliah : '';
        $data['file_name'] = 'Kelas-' . $kelas->nama_kelas . '-' . $nama_matakuliah;

        $this->load->view('admin/kelas/V_cetak_kuisioner', $data);
    }

//    baru dibuat kuisioner layanan
    public function kuisioner_layanan(){
        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = 'Kuisioner Pelayanan |';
        $data['content'] = 'admin/kelas/kuisioner_layanan/V_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();
        $data['angkatan'] = $this->kuisionerservice->getAngkatanTahunAkademik();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter_layanan(){
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
//        $angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('kode_program_studi');

        $header = $this->kuisionerservice->getKuisionerLayananHeader();
        $mah = $this->kuisionerservice->getMahasiswaKuisionerLayanan($kode_tahun_akademik, $kode_program_studi);
        $data['data'] = [];
        $i = 0;
        foreach ($mah as $item) {
            $data['data'][$i] = $this->kuisionerservice->getKuisionerLayananByNim($item->nim, $kode_tahun_akademik);
            $i++;
        }

        $data['header'] = $header;
        $data['prodi'] = $this->kuisionerservice->getProgramStudiByKode($kode_program_studi);
        $data['tahun_akademik'] = $this->kuisionerservice->getTahunAkademikByKode($kode_tahun_akademik);
//        $data['angkatan'] = "20".$angkatan;
        $data['judul'] = 'Hasil Kuisioner Pelayanan';
        $data['sub_judul'] = 'Hasil Kuisioner Pelayanan';
        $this->load->view('admin/kelas/kuisioner_layanan/V_hasil_kuisioner', $data);
    }

    public function cetak_kuisioner_layanan($kode_tahun_akademik, $kode_program_studi){
        $header = $this->kuisionerservice->getKuisionerLayananHeader();
        $mah = $this->kuisionerservice->getMahasiswaKuisionerLayanan($kode_tahun_akademik, $kode_program_studi);
        $data['data'] = [];
        $i = 0;
        foreach ($mah as $item) {
            $data['data'][$i] = $this->kuisionerservice->getKuisionerLayananByNim($item->nim, $kode_tahun_akademik);
            $i++;
        }

        $data['header'] = $header;
        $data['judul'] = 'Hasil Kuisioner Pelayanan';
        $data['sub_judul'] = 'Hasil Kuisioner Pelayanan';
        $data['prodi'] = $this->kuisionerservice->getProgramStudiByKode($kode_program_studi);
        $data['tahun_akademik'] = $this->kuisionerservice->getTahunAkademikByKode($kode_tahun_akademik);
//        $data['angkatan'] = "20".$angkatan;
        $data['file_name'] = "Kuisioner Pelayanan Prodi ".$data['prodi']->nama_program_studi." TA.".$data['tahun_akademik']->tahun_akademik;
        $this->load->view('admin/kelas/kuisioner_layanan/V_cetak_kuisioner', $data);
    }
}