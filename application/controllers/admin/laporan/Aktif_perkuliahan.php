<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Aktif_perkuliahan extends CI_Controller{
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
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index()
    {
        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Laporan Aktif Perkuliahan';
        $data['content'] = 'admin/laporan/aktif_perkuliahan/V_index';
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

        redirect(site_url('admin/laporan/aktif_perkuliahan/data_aktif_kuliah'));
    }

    public function data_aktif_kuliah()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
//        $kode_jenjang = $this->session->userdata('sess_kode_jenjang');
//        $kode_jurusan = $this->session->userdata('sess_kode_jurusan');
//        $prodi = $this->Nama_jurusan_model->get_nama_jurusan_by_kode($kode_jurusan, $kode_jenjang);
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);


//        echo 'Jumlah = '.count($data);
        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Data mahasiswa aktif perkuliahan';
        $data['content'] = 'admin/laporan/aktif_perkuliahan/V_aktif_perkuliahan';
        $data['data'] = $this->laporan_model->aktif_perkuliahan($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
        $data['prodi'] = $prodi;
        $this->load->view('admin/template/V_main', $data);
    }

    public function cetak_aktif_perkuliahan()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
//        $kode_jenjang = $this->session->userdata('sess_kode_jenjang');
//        $kode_jurusan = $this->session->userdata('sess_kode_jurusan');
//        $prodi = $this->Nama_jurusan_model->get_nama_jurusan_by_kode($kode_jurusan, $kode_jenjang);
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);

        $data = $this->laporan_model->aktif_perkuliahan($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);

        $table = '<table border="1">';
        $table .= '<tr>';
        $table .= '<th>NO.</th>';
        $table .= '<th>NIM</th>';
        $table .= '<th>NAMA AMAHASISWA</th>';
        $table .= '<th>JUMLAH SKS</th>';
        $table .= '</tr>';
        $i=1;
        foreach ($data as $row) :
        $table .= '<tr>';
        $table .= '<th>'.$i++.'.</th>';
        $table .= '<th>'.$row->nim.'</th>';
        $table .= '<th>'.$row->nama_mahasiswa.'</th>';
        $table .= '<th>'.$row->jumlah_sks.'</th>';
        $table .= '</tr>';
        endforeach;
        $table .= '</table>';

        $data['table'] = $table;
        $data['file_name'] = $prodi->singkatan_program_studi.'-'.$prodi->nama_program_studi;

        $this->load->view('admin/laporan/aktif_perkuliahan/V_spreadsheet_view', $data);


    }
}