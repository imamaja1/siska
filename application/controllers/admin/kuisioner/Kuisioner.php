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

        $this->load->library("Excel");

        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
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
                    'style' => PHPExcel_Style_Border::BORDER_THIN
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

            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
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

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="Data dokter.xls"');

        $object_writer->save('php://output');
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