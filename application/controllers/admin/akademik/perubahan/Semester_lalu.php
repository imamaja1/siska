<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semester_lalu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/kurikulum/m_data_kurikulum',
            'keuangan/Status_perkuliahan_model',
            'akademik/Mahasiswa_model',
            'akademik/Krs_model',
            'akademik/Krs_detail_model',
        ));
        $this->load->service('NilaiService');
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
      	//redirect(site_url('denied'));
    }

    public function index() {
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KRS & Nilai Semester Lalu";
        $data['judul_sub_judul'] = "Perubahan";
        $data['content'] = "admin/akademik/perubahan/semester_lalu/V_index.php";

        $this->load->view('admin/template/V_main', $data);
    }

    public function perubahan($nim) {
        // get param
//        $kode_jurusan = substr($nim, 2, 2);
//        $kode_jenjang = substr($nim, 4, 1);
//        $angkatan = substr($nim, 0, 2);

        $tahun_akademik = $this->m_tahun_akademik->get_aktif() - 1;
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
        if ($kode_krs):
            //Generate
            $cek = $this->nilaiservice->get_krs_by_nim_ta($nim, $tahun_akademik);
//            $kode_nama_kurikulum = kode_nama_kurikulum($nim);
            $data_penilaian = data_penilaian($nim, $cek->semester);
//            if (stup_grade($kode_nama_kurikulum, $cek->semester))
//            {
//                $data_penilaian = stup_grade($kode_nama_kurikulum, $cek->semester);
//            }else{
//                $data_penilaian = sistem_penilaian($nim);
//            }
//            $kode_program_studi = $this->Nama_jurusan_model->get_id($kode_jurusan, $kode_jenjang);
            $program_studi = get_kode_prodi($nim);
//            $data_penilaian = $this->Krs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
            $data_krs = $this->Krs_model->khs($kode_krs);

            $khs['sksn'] = 0;
            $khs['total_sks'] = 0;
            $khs['total_bobot'] = 0;
            $i = 0;
            foreach ($data_krs as $row) {
                $khs['nim'] = $row->nim;
                $khs['kode_krs'] = $row->kode_krs;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = nama_kurikulum_nama($nim);
                $khs['data_nilai'][$i]['kode_krs_detail'] = $row->kode_krs_detail;
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['nilai_harian'] = $row->nilai_harian;
                $khs['data_nilai'][$i]['nilai_uts'] = $row->nilai_uts;
                $khs['data_nilai'][$i]['nilai_uas'] = $row->nilai_uas;
                $khs['data_nilai'][$i]['sks'] = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
                $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;
//                $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                $nilai_akhir = $row->nilai_akhir * 1;
                $khs['data_nilai'][$i]['nilai_akhir'] = $nilai_akhir;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $khs['data_nilai'][$i]['grade'] = $key['grade'];
                        $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * ($row->sks_teori + $row->sks_praktek + $row->sks_praktikum);
                    }
                }
                $khs['total_sks'] += $khs['data_nilai'][$i]['sks'];
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $khs['prodi'] = $program_studi;
                $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);

                $i++;
            }

            $data['data'] = $khs;
            $data['matakuliah'] = $this->m_data_kurikulum->get_matakuliah_bynim($nim);
        endif;
        $data['judul'] = "Krs dan NIlai";
        $data['sub_judul'] = "Semester Lalu";
        $data['content'] = "admin/akademik/perubahan/semester_lalu/V_krs_nilai";

        $this->load->view('admin/template/V_main', $data);

        // echo $kode_krs;
    }

    public function ubah_krs_nilai() {
        $input = filter_input_array(INPUT_POST);
        $editdata = $this->nilaiservice->cek_matakuliah_khusus($input['kode_krs_detail']);

        if ($input['action'] === 'edit') {
            if ($editdata) {
                $this->nilaiservice->edit_khs_detail_full($input['kode_krs_detail'], $input['nilai_harian'], $input['nilai_uts'], $input['nilai_uas'], $input['nilai_akhir'], $input['tidak_berhak']);
                echo json_encode(true);
            }else{
                echo json_encode(false);
            }
        } else if ($input['action'] === 'delete') {
            $this->nilaiservice->delete_krs_detail_cascade($input['kode_krs_detail']);
            echo json_encode(true);
        } else if ($input['action'] === 'restore') {
            $this->nilaiservice->restore_khs_detail($input['kode_khs_detail']);
            echo json_encode(true);
        }
    }

    public function simpan() {
        $kode_krs = $this->input->post('kode_krs');
//        $kode_matakuliah = $this->input->post('kode_matakuliah');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $nilai_harian = $this->input->post('nilai_harian');
        $nilai_uts = $this->input->post('nilai_uts');
        $nilai_uas = $this->input->post('nilai_uas');
        $nilai_akhir = $this->input->post('nilai_akhir');
        $tidak_berhak = $this->input->post('tidak_berhak');
        $nim = $this->input->post('nim');
        if (empty($nilai_harian))
        {
            $nilai_harian = null;
        }
        if (empty($nilai_uts))
        {
            $nilai_uts=null;
        }
        if (empty($nilai_uas))
        {
            $nilai_uas = null;
        }
        if (empty($nilai_akhir))
        {
            $nilai_akhir=null;
        }
        $data_krs = array(
            'kode_krs' => $kode_krs,
            'id_matakuliah' => $id_matakuliah,
        );
        $id = $this->Krs_detail_model->simpan_krs($data_krs);

        if ($id !== null) {
            $data_khs = array(
                'nilai_harian' => $nilai_harian,
                'nilai_uts' => $nilai_uts,
                'nilai_uas' => $nilai_uas,
                'nilai_akhir' => $nilai_akhir,
                'tidak_berhak' => $tidak_berhak,
                'kode_krs_detail' => $id,
            );
            if ($this->Krs_detail_model->simpan_khs($data_khs)) {
                $this->session->set_flashdata('info', '<script>swal("Success", "Data berhasil di simpan", "success");</script>');

                redirect('admin/akademik/perubahan/semester_lalu/perubahan/' . $nim);
            }
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal", "Data gagal di simpan", "error");</script>');

            redirect('admin/akademik/perubahan/semester_lalu/perubahan/' . $nim);
        }
    }

    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $result = $this->Krs_model->autocomplate($keyword);
        if (!empty($result)) {


            echo '<ul id="nim-list" class="list-group">';
            foreach ($result as $nim) {


                echo '<li onClick="selectNim(' . $nim->nim . ')" class="list-group-item">' . $nim->nim . '</li>';
            }
            echo '</ul>';
        } else {
            echo "Data tidak ditemukan";
        }
    }

}
