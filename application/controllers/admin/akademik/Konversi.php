<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Konversi extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'akademik/Mahasiswa_model',
            'jurusan/m_tahun_akademik',
            'jurusan/kurikulum/m_nama_kurikulum',
            'jurusan/kurikulum/m_data_kurikulum',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'akademik/Mahasiswa_model',
            'akademik/Krs_model',
            'akademik/Khs_model',
            'akademik/Krs_detail_model',
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
        $this->load->service('PerkuliahanService');
    }

    public function index()
    {
        $tahun_akademik = $this->m_tahun_akademik->get_semester();

        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Mahasiswa Tranfer/Lanjut';
        $data['content'] = 'admin/akademik/konversi/V_mahasiswa_transfer';
        $data['data'] = $this->Mahasiswa_model->get_mahasiswa_transfer($tahun_akademik->tahun_akademik) ;

        $this->load->view('admin/template/V_main', $data);
    }

    public function konversi($nim)
    {
//        $nama_kurikulum = $this->m_nama_kurikulum->get_nama_kurikulum_by_nim($nim);
//        $kode_nama_kurikulum = $nama_kurikulum->kode_nama_kurikulum;
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data['content'] = 'admin/akademik/konversi/V_konversi';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Konversi Matakuliah';
        $data['data_mahasiswa'] = $this->Mahasiswa_model->get($nim);
        $data['data_kurikulum'] = $this->m_data_kurikulum->get_data_kurikulum($kode_nama_kurikulum);

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_konversi()
    {
        $nim = $this->input->post('nim');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $nilai_akhir = $this->input->post('nilai_akhir');

        $this->perkuliahanservice->simpan_konversi($nim, $id_matakuliah, $nilai_akhir);

        redirect('admin/akademik/konversi');
    }

    public function edit($nim)
    {
        $res = $this->perkuliahanservice->get_edit_konversi_data($nim);

        $data['data'] = $res['khs'];
        $data['matakuliah'] = $res['matakuliah'];
        $data['prodi'] = $res['prodi'];
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "Edit Konversi";
        $data['content'] = "admin/akademik/konversi/V_ubah";

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_tambah_konversi() {
        $kode_krs = $this->input->post('kode_krs');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $nilai_akhir = $this->input->post('nilai_ahir');
        $nim = $this->input->post('nim');

        if ($this->perkuliahanservice->simpan_tambah_konversi($kode_krs, $id_matakuliah, $nilai_akhir)) {
            $this->session->set_flashdata('info', '<script>swal("Success", "Data berhasil di simpan", "success");</script>');
            redirect('admin/akademik/konversi/edit/' . $nim);
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal", "Data gagal di simpan", "error");</script>');
            redirect('admin/akademik/konversi/edit/' . $nim);
        }
    }


    public function ubah_krs_nilai_konversi() {
        $input = filter_input_array(INPUT_POST);
        $res = $this->perkuliahanservice->ubah_krs_nilai_konversi($input);

        echo json_encode($res);
    }
}