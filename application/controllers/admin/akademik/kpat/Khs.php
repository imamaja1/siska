<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Khs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'keuangan/Status_perkuliahan_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'akademik/Mahasiswa_model',
            'akademik/Krs_kpat_model',
            'akademik/Khs_kpat_model',
        ));

        $this->load->service('KPATService');

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

    public function index() {
        $data['content'] = 'admin/akademik/kpat/khs/V_index';
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KHS KPAT";
        $data['judul_sub_judul'] = "KPAT";
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();


        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $tahun_angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');

//        buat data session
        $data_session = array(
            'sess_kode_tahun_akademik' =>$kode_tahun_akademik,
            'sess_tahun_angkatan' => $tahun_angkatan,
            'sess_kode_program_studi' => $kode_program_studi,
        );

        $this->session->set_userdata($data_session);
        redirect(site_url('admin/akademik/kpat/khs/data_khs_kpat'));
    }

    public function data_khs_kpat()
    {
        $data['content'] = 'admin/akademik/kpat/khs/V_mahasiswa_kpat';
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "Mahasiswa KHS KPAT";
//        extrak data session
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
//        $kode_jenjang = $this->session->userdata('sess_kode_jenjang');
//        $kode_jurusan = $this->session->userdata('sess_kode_jurusan');
        $data['data'] = $this->Khs_kpat_model->filter($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);

        $this->load->view('admin/template/V_main', $data);
    }

    public function lihat_khs($kode_krs, $nim) {
        $angkatan = substr($nim,0,2);
        $kode_jurusan = substr($nim,2,2);
        $kode_jenjang = substr($nim,4,1);
        $data['content'] = 'admin/akademik/kpat/khs/V_lihat_khs';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'KHS';
        $sem = $this->kpatservice->getKrsByKode($kode_krs);
        $data_penilaian = data_penilaian($nim, $sem->semester);

        //Generate
        $program_studi = get_kode_prodi($nim);
        $data_krs = $this->Khs_kpat_model->khs($kode_krs);

        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $row->nim;
            $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
            $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
            $khs['semester'] = $row->semester;
            $khs['kurikulum'] = nama_kurikulum_nama($nim);
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
            $nilai_akhir = $row->nilai_akhir * 1;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * ($row->sks_teori + $row->sks_praktek + $row->sks_praktikum);
                }
            }
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);

            $i++;
        }

        $data['data'] = $khs;
        $data['prodi'] = $program_studi;
        $tahun = $this->m_tahun_akademik->get_semester();
        $data['semester'] = $khs['semester'];
        $data['tahun_akademik'] = $tahun->tahun_akademik;

        $this->load->view('admin/template/V_main', $data);
    }

    public function cetak($kode_krs, $nim) {

        $data['content'] = 'admin/akademik/kpat/khs/V_lihat_khs';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'KHS';
        $sem = $this->kpatservice->getKrsByKode($kode_krs);
        $data_penilaian = data_penilaian($nim, $sem->semester);


        //Generate
        $program_studi = get_kode_prodi($nim);
        $data_krs = $this->Khs_kpat_model->khs($kode_krs);

        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $row->nim;
            $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
            $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
            $khs['semester'] = $row->semester;
            $khs['kurikulum'] = nama_kurikulum_nama($nim);
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
            $nilai_akhir = $row->nilai_akhir * 1;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * ($row->sks_teori + $row->sks_praktek + $row->sks_praktikum);
                }
            }
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);

            $i++;
        }

        $data['data'] = $khs;
        $data['semester'] = $this->get_semester($nim);
        $tahun = $this->m_tahun_akademik->get_semester();
        $data['tahun_akademik'] = $tahun->tahun_akademik;
        $data['prodi'] = $program_studi;


        $namafile = $nim . "-KHS_KPAT.pdf";
        $this->load->library('pdf');
        $this->pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 45, 'margin_bottom' => 20, 'margin_header' => 10, 'margin_footer' => 10]);
        $mpdf = $this->pdf;
        $content = $this->load->view('admin/akademik/kpat/khs/Cetak_khs', $data, true);
        $header = $this->load->view('admin/akademik/kpat/khs/Header_khs', $data, true);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }

    function get_semester($nim)
    {
        $tahun_angkatan = substr($nim, 0, 2);
        $tahun = $this->m_tahun_akademik->get_semester();
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;

        if ($sem == 0) {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }

        return $semester;
    }

}

?>