<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kompetensi extends CI_Controller
{
    var $limit = 50;

    public function __construct()
    {
        parent::__construct();
        $this->load->service('PerkuliahanService');
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

  	public function update($nim, $prodi)
    {
        $data['content'] = 'admin/akademik/kompetensi/v_edit';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Edit Kompetensi';
        $data['judul_sub_judul'] = 'NIM : ' . $nim;

        $data['kompetensi_prodi'] = $this->perkuliahanservice->get_kompetensi($prodi);
        $data['data_mahasiswa'] = $this->perkuliahanservice->get_kompetensi_jurusan_mahasiswa($nim)->row_object();
        $this->load->view('admin/template/V_main', $data);
    }
    public function index()
    {
        $data['content'] = 'admin/akademik/kompetensi/v_index';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Kompetensi';
        $data['judul_sub_judul'] = '';

        $this->load->view('admin/template/V_main', $data);
    }

    public function search()
    {
        $data['content'] = 'admin/akademik/kompetensi/v_index';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Kompetensi';
        $data['judul_sub_judul'] = '';

        $kata_kunci = $this->input->post('kata_kunci');
        $data_mahasiswa = $this->perkuliahanservice->get_kompetensi_jurusan_mahasiswa($kata_kunci)->result();
        $jml = count($data_mahasiswa);
//        echo '<pre>';
//        var_dump($data_mahasiswa);
//        die();
        if ($jml > 0) {
            $no = 1;
            $table = '<div class="box box-primary flat" >';
            $table .= '<div class="box-body">';
            $table .= '<table class="table demo-table">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th id="th">NIM</th>';
            $table .= '<th id="th">NAMA</th>';
            $table .= '<th id="th">PRODI</th>';
            $table .= '<th id="th">STATUS</th>';
            $table .= '<th id="th">KOMPETENSI</th>';
            $table .= '<th id="th">TINDAKAN</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            foreach ($data_mahasiswa as $row) {
                $table .= '<tr>';
                $table .= '<td align="center">' . $row->nim . '</td>';
                $table .= '<td align="center">' . $row->nama_mahasiswa . '</td>';
                $table .= '<td align="center">' . $row->nama_program_studi . '</td>';
                $table .= '<td align="center">' . $row->status_pendaftaran . '</td>';
                $table .= '<td align="center">' . $row->nama_kompetensi . '</td>';
                $table .= '<td align="center">';
                $table .= '<a href="' . site_url('admin/akademik/kompetensi/update/' . $row->nim . '/' . $row->kode_program_studi) . '" class=" btn-primary btn-xs flat"><i class="fa fa-edit"></i> Edit</a>';
                $table .= '</td>';
                $table .= '</tr>';
            }
            $table .= '</table>';
            $table .= '</div>';
            $table .= '</div>';
            $data['table'] = $table;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Tidak ditemukan data mahasiswa dengan kata kunci NIM <b>' . $kata_kunci . '</b> !</div>');
        }
        $this->load->view('admin/template/V_main', $data);

    }

    

    public function proses_update()
    {
        $nim = $this->input->post('nim');
        $kode_kompetensi = $this->input->post('kode_kompetensi');
        $kode_kompetensi_mahasiswa = $this->input->post('kode_kompetensi_mahasiswa');
        $data = [
            'kode_kompetensi' => $kode_kompetensi
        ];
        $this->perkuliahanservice->ubah_kompetensi_mahasiswa($data, $kode_kompetensi_mahasiswa);
        $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Kompetensi Telah Berhasil dirubah.</b> !</div>');

        return redirect(site_url('admin/akademik/kompetensi'));

    }

}