<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aktif_perkuliahan extends CI_Controller
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
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
    }

    public function index()
    {
        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Laporan Aktif Perkuliahan';
        $data['content'] = 'dosen/kaprodi/aktif_perkuliahan/v_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();
        $this->load->view('dosen/template/V_main', $data);
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

        redirect(site_url('dosen/kaprodi/aktif_perkuliahan/data_aktif_kuliah'));
    }

    public function data_aktif_kuliah()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);
        $data['angkatan'] = $tahun_angkatan;
        $data['ta'] = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        $data['judul'] = 'Data Mahasiswa Aktif Perkuliahan';
        $data['content'] = 'dosen/kaprodi/aktif_perkuliahan/V_aktif_perkuliahan';
        $data['data'] = $this->laporan_model->aktif_perkuliahan_kprodi($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi)->result_object();
        $result = $this->laporan_model->aktif_perkuliahan_kprodi($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi)->result_array();
        $nim_aktif = array_map(function ($value) {
            return $value['nim'];
        }, $result);
        $data['tidak_aktif'] = $this->laporan_model->tidak_aktif_perangkatan_perprodi($tahun_angkatan, $kode_program_studi, $nim_aktif)->result_object();
        $data['prodi'] = $prodi;
        $this->load->view('dosen/template/V_main', $data);
    }

    public function cetak_aktif_perkuliahan()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);

        $data = $this->laporan_model->aktif_perkuliahan($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);
        $table = '<table border="1">';
        $table .= '<tr>';
        $table .= '<th>NO.</th>';
        $table .= '<th>NIM</th>';
        $table .= '<th>NAMA MAHASISWA</th>';
        $table .= '</tr>';
        $i = 1;
        foreach ($data as $row) :
            $table .= '<tr>';
            $table .= '<td>' . $i++ . '.</td>';
            $table .= '<td>' . $row->nim . '</td>';
            $table .= '<td>' . $row->nama_mahasiswa . '</td>';
            $table .= '</tr>';
        endforeach;
        $table .= '</table>';

        $result = $this->laporan_model->aktif_perkuliahan_kprodi($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi)->result_array();
        $nim_aktif = array_map(function ($value) {
            return $value['nim'];
        }, $result);
        $data['tidak_aktif'] = $this->laporan_model->tidak_aktif_perangkatan_perprodi($tahun_angkatan, $kode_program_studi, $nim_aktif)->result_object();

        $table2 = '<table border="1">';
        $table2 .= '<tr>';
        $table2 .= '<th>NO.</th>';
        $table2 .= '<th>NIM</th>';
        $table2 .= '<th>NAMA MAHASISWA</th>';
        $table2 .= '</tr>';
        $i = 1;
        foreach ($data as $row) :
            $table2 .= '<tr>';
            $table2 .= '<td>' . $i++ . '.</td>';
            $table2 .= '<td>' . $row->nim . '</td>';
            $table2 .= '<td>' . $row->nama_mahasiswa . '</td>';
            $table2 .= '</tr>';
        endforeach;
        $table2 .= '</table>';

        $data['table'] = $table;
        $data['table2'] = $table2;
        $data['file_name'] = $prodi->singkatan_program_studi . '-' . $prodi->nama_program_studi;
        $data['angkatan'] = $tahun_angkatan;
        $data['ta'] = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
        $data['prodi'] = $prodi;

        $this->load->view('dosen/kaprodi/aktif_perkuliahan/V_spreadsheet_view', $data);


    }
}