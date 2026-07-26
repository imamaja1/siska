<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Krs extends CI_Controller
{

    var $limit = 50;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
                'jurusan/m_tahun_akademik',
                'jurusan/program_studi/Nama_jurusan_model',
                'jurusan/program_studi/Jenjang_model',
                'jurusan/program_studi/Kode_jurusan_model',
                'keuangan/Status_perkuliahan_model',
                'jurusan/program_studi/Ketua_jurusan_model',
                'jurusan/Perwalian_model',
                'akademik/Mahasiswa_model',
                'akademik/Krs_model',
                'akademik/Khs_model',
                'akademik/Krs_detail_model',
                'akademik/Krs_kpat_model',
                'jurusan/kurikulum/m_data_kurikulum',
        ));

        $this->load->service('KPATService');

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

    public function index()
    {
        $data['content'] = 'admin/akademik/kpat/krs/V_index';
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KRS KPAT";
        $data['judul_sub_judul'] = "KPAT";
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();
        $data['nim_mahasiswa'] = $this->Mahasiswa_model->get_nim();

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

        redirect(site_url('admin/akademik/kpat/krs/data_krs_kpat'));
    }

    public function data_krs_kpat()
    {
        $data['content'] = 'admin/akademik/kpat/krs/V_mahasiswa_kpat';
        $data['judul'] = "Data KRS KPAT";
        $data['sub_judul'] = "Mahasiswa KPAT";

//        extrak data session
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $tahun_angkatan = $this->session->userdata('sess_tahun_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');
//        $kode_jenjang = $this->session->userdata('sess_kode_jenjang');
//        $kode_jurusan = $this->session->userdata('sess_kode_jurusan');
        $data['data'] = $this->Krs_kpat_model->filter($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi);


        $this->load->view('admin/template/V_main', $data);
    }

    public function lihat_krs($kode_krs)
    {
        $data['content'] = 'admin/akademik/kpat/krs/V_lihat_krs';
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KRS KPAT";
        $data['data'] = $this->Krs_kpat_model->lihat_krs($kode_krs);
        $data['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($data['data']['data_mahasiswa']->kode_program_studi);
        $data['prodi'] = get_kode_prodi($data['data']['data_mahasiswa']->nim);
//        $prodi = $this->Nama_jurusan_model->get_kode($data['data']['data_mahasiswa']->kode_program_studi);
//        $data['jurusan'] = $this->Kode_jurusan_model->get_nama($prodi->id_jurusan);
//        $data['jenjang'] = $this->Jenjang_model->get_nama($prodi->id_jenjang);

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah($nim)
    {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data['content'] = 'admin/akademik/kpat/krs/V_tambah_kpat';
        $data['judul'] = "TAMBAH KRS KPAT";
        $data['sub_judul'] = "Tambah KPAT";

        $data['data'] = $this->Krs_kpat_model->get_krs_sebelumnya($nim, $kode_tahun_akademik);
        $data['mahasiswa'] = $this->Mahasiswa_model->get($nim);

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_krs()
    {
        $matakuliah_kpat = $this->input->post('kpat');
        $nim = $this->input->post('nim');
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
//        Simpan Data KRS
        $data_krs = array(
                'kode_tahun_akademik' => $kode_tahun_akademik,
                'nim' => $nim,
                'semester' => $semester,
        );

        $kode_krs = $this->Krs_model->simpan_krs($data_krs);
//        Simpan data Khs
        if ($kode_krs !== null) {
            $data_khs = array(
                    'kode_krs' => $kode_krs,
            );
            $this->Khs_model->simpan_khs($data_khs);
//        Simpan Data Krs_Detail
            foreach ($matakuliah_kpat as $kode_mk) {
                $arr = explode(",", $kode_mk);
                $data_krs_detail = array(
                        'kode_krs' => $kode_krs,
                        'id_matakuliah' => $arr[0],
                        'status' => 'K',
                );

                $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                $data_khs_detail = array(
                        'kode_krs_detail' => $kode_krs_detail,
                );

                $this->Krs_detail_model->simpan_khs($data_khs_detail);
            }

            $this->session->set_flashdata(
                    'info', '<script>swal("Success!", "Data berhasil disimpan", "success");</script>');

            redirect('admin/akademik/kpat/krs', 'refresh');
        }
    }

    public function autocomplate()
    {
        $keyword = $this->input->post('keyword');
        $result = $this->Krs_kpat_model->autocomplate($keyword);
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

    public function cetak($kode_krs)
    {

        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
        $krs = $this->Krs_kpat_model->lihat_krs($kode_krs);
        $tahun_angkatan = substr($krs['data_mahasiswa']->nim, 0, 2);
//        $tahun = $this->m_tahun_akademik->get_semester();
        $tahun = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun;
//        $kode_tahun_akademik = $tahun->kode_tahun_akademik;

        if ($sem == 0) {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }
        $data['data_mahasiswa'] = $krs['data_mahasiswa'];
//        $data['prodi'] = $this->Nama_jurusan_model->get_prodi_by_nim($krs['data_mahasiswa']->nim);
        $data['prodi'] = get_kode_prodi($krs['data_mahasiswa']->nim);
        $data['semester'] = $semester;
        $data['tahun_akademik'] = $tahun;
        $data['maksimum_sks'] = 10;
        $data['mahasiswa'] = $this->Mahasiswa_model->get($krs['data_mahasiswa']->nim);
        $data['data_matakuliah'] = $krs['data_matakuliah'];
        $data['dosen_wali'] = $this->Perwalian_model->get_perwalian_by_nim($krs['data_mahasiswa']->nim);
        $data['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($krs['data_mahasiswa']->kode_program_studi);
//        $prodi = $this->Nama_jurusan_model->get_kode($krs['data_mahasiswa']->kode_program_studi);
//        $data['jurusan'] = $this->Kode_jurusan_model->get_nama($prodi->id_jurusan);
//        $data['jenjang'] = $this->Jenjang_model->get_nama($prodi->id_jenjang);

        $namafile = $krs['data_mahasiswa']->nim . "-" . $krs['data_mahasiswa']->nama_mahasiswa . "-KHS.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 40, 'margin_bottom' => 15, 'margin_header' => 3, 'margin_footer' => 3]);
        $mpdf = $this->m_pdf;
        $content = $this->load->view('admin/akademik/kpat/krs/Cetak_krs', $data, true);
        $header = $this->load->view('admin/akademik/kpat/krs/Header_krs', '', true);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }

    public function edit($kode_krs, $nim)
    {
//        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
//        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
        $cek = $this->kpatservice->getKrsByKodeKrs($kode_krs);
        $data_penilaian = data_penilaian($nim, $cek->semester);
        $program_studi = get_kode_prodi($nim);
        $data_krs = $this->Krs_model->khs($kode_krs);

        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
        $i = 0;
        if ($data_krs) :
            foreach ($data_krs as $row) {
                $khs['nim'] = $row->nim;
                $khs['kode_krs'] = $row->kode_krs;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'];
                $khs['data_nilai'][$i]['kode_krs_detail'] = $row->kode_krs_detail;
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['nilai_harian'] = $row->nilai_harian;
                $khs['data_nilai'][$i]['nilai_uts'] = $row->nilai_uts;
                $khs['data_nilai'][$i]['nilai_uas'] = $row->nilai_uas;
                $khs['data_nilai'][$i]['sks'] = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
                $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;
                $nilai_akhir = $row->nilai_akhir * 1;
                $khs['data_nilai'][$i]['nilai_akhir'] = $nilai_akhir;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $khs['data_nilai'][$i]['grade'] = $key['grade'];
                        $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * ($row->sks_teori + $row->sks_praktek + $row->sks_praktikum);
                    }
                }
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $khs['prodi'] = $program_studi;
                $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);

                $i++;
            }
        endif;

        $data['data'] = $khs;
        $data['matakuliah'] = $this->m_data_kurikulum->get_matakuliah_bynim($nim);
        $data['content'] = 'admin/akademik/kpat/krs/V_edit';
        $data['judul'] = "Edit KRT KPAT";
        $data['sub_judul'] = "KRS KPAT";

        $this->load->view('admin/template/V_main', $data);
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

//                redirect('admin/akademik/perubahan/semester_ini/perubahan/' . $nim);
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal", "Data gagal di simpan", "error");</script>');

//            redirect('admin/akademik/perubahan/semester_ini/perubahan/' . $nim);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function ubah_krs_nilai() {
        $input = filter_input_array(INPUT_POST);

        if ($input['action'] === 'edit') {
            $this->kpatservice->updateKhsDetailRaw($input['kode_krs_detail'], $input['edit_nilai_harian'], $input['edit_nilai_uts'], $input['edit_nilai_uas'], $input['edit_nilai_akhir'], $input['tidak_berhak']);
        } else if ($input['action'] === 'delete') {
            $this->kpatservice->deleteKrsDetail($input['kode_krs_detail']);
            $this->kpatservice->deleteKhsDetail($input['kode_krs_detail']);
        } else if ($input['action'] === 'restore') {
            $this->kpatservice->restoreKhsDetail($input['kode_khs_detail']);
        }

        echo json_encode($input);
    }

}

?>