<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class nilai extends CI_Controller {

    var $limit = 50;
    var $limit_10 = 10;
    var $limit_500 = 5000;

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'akademik/nilai_model',
            'jurusan/kurikulum/m_matakuliah',
            'kuisioner/kuisioner_model',
        ));
        $this->load->service('NilaiService');
        $this->load->library(array('form_validation', 'pagination'));

        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
//            $tes = array('02-50-41-00-00-01');
//            $ce = in_array(get_mac_addres(), $tes);
            if (!$cek) {
                redirect(site_url('denied'));
            }
//            elseif(!$ce){
//                redirect(site_url('denied'));
//            }
        }
    }

    function index() {
        $data = array(
            'content' => 'admin/akademik/nilai/View_nilai',
            'judul' => 'Akademik',
            'sub_judul' => 'Nilai',
//            'tahun_akademik' => $this->m_tahun_akademik->get_tahun_ganjil_genap(),
            'tahun_akademik' => $this->nilaiservice->get_all_tahun_akademik(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function validasi_nilai($kelas_id) {
        $this->nilaiservice->validasi_kelas_nilai($kelas_id, 'uts');
        redirect('admin/akademik/validasikhusus_uts');
    }

    public function validasi_nilai_uas($kelas_id) {
        $this->nilaiservice->validasi_kelas_nilai($kelas_id, 'uas');
        redirect('admin/akademik/validasikhusus_uas');
    }

    function get_nilai_process() {
        $data = array(
            'content' => 'admin/akademik/nilai/View_nilai',
            'judul' => 'Akademik',
            'sub_judul' => 'Nilai',
            'tahun_akademik' => $this->m_tahun_akademik->get_tahun_ganjil_genap(),
        );
        $this->load->view('admin/template/V_main', $data);

        $tahun_akademik = $this->m_tahun_akademik->get_all_tahun_akademik()->result();
        $options_tahun_akademik = array();
        foreach ($tahun_akademik as $row) {
            if ($row->semester % 2 == 1) {
                $semester = 'Ganjil';
            } else {
                $semester = 'Genap';
            }
            $options_tahun_akademik[$row->kode_tahun_akademik] = $row->tahun_akademik . ' - ' . $semester;
        }

        $data['options_tahun_akademik'] = $options_tahun_akademik;

        $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'required');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        $this->form_validation->set_rules('matakuliah', 'Matakuliah', 'required');

        if ($this->form_validation->run() == TRUE) {

            $kode_tahun_akademik = $this->input->post('tahun_akademik');
            $id_matakuliah = $this->input->post('matakuliah');

            $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_by_kode($kode_tahun_akademik);
            if ($tahun_akademik->semester % 2 == 1) {
                $nama_semester = 'Ganjil';
            } else {
                $nama_semester = 'Genap';
            }

            $nama_tahun_akademik = $tahun_akademik->tahun_akademik;
            $nama_jurusan = $this->nama_jurusan_model->get_kode_by_program_studi($this->input->post('jurusan'));
            $kode_jurusan_jenjang = $nama_jurusan->kode_jurusan . '' . $nama_jurusan->kode_jenjang;
            $matakuliah = $this->m_matakuliah->get_matakuliah_by_kode($id_matakuliah);
            $nama_matakuliah = $matakuliah->nama_matakuliah;

            $this->session->set_userdata('kode_tahun_akademik', $kode_tahun_akademik);
            $this->session->set_userdata('nama_semester', $nama_semester);
            $this->session->set_userdata('singkatan_jurusan', $nama_jurusan->singkatan_program_studi);
            $this->session->set_userdata('kode_jurusan_jenjang', $kode_jurusan_jenjang);
            $this->session->set_userdata('id_matakuliah', $id_matakuliah);
            $this->session->set_userdata('nama_matakuliah', $nama_matakuliah);
            $this->session->set_userdata('nama_tahun_akademik', $nama_tahun_akademik);
            $this->session->set_userdata('kode_program_studi', $this->input->post('jurusan'));

            redirect('admin/akademik/nilai/get_all_nilai_matakuliah');
        } else {
            $data['default']['tahun_akademik'] = $this->input->post('tahun_akademik');
            $data['default']['jurusan'] = $this->input->post('jurusan');
            $data['default']['matakuliah'] = $this->input->post('matakuliah');
            $this->load->view('admin/template/V_main', $data);
        }
    }

    function get_all_nilai_matakuliah($offset = 0) {

        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }

        $kode_program_studi = $this->session->userdata('kode_program_studi');
        $id_matakuliah = $this->session->userdata('id_matakuliah');
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik');
        $data_count = count($this->nilai_model->count_all_results_nilai_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah));

        if ($data_count > 0) {
            // Generate pagination			
            $config = array(
                'base_url' => site_url('admin/akademik/nilai/get_all_nilai_matakuliah'),
                'total_rows' => $data_count,
                'per_page' => $this->limit,
                'uri_segment' => $uri_segment,
                'full_tag_open' => '<div class="btn-group">',
                'full_tag_close' => '</div>',
                'cur_tag_open' => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
                'cur_tag_close' => '</a>',
                'attributes' => array('class' => 'btn btn-flat btn-xs btn-default'),
            );

            $this->pagination->initialize($config);

            $data = array(
                'content' => 'admin/akademik/nilai/View_nilai_proses',
                'judul' => 'Akademik',
                'sub_judul' => 'Nilai',
                'title_h1' => '<li> Tahun Akademik ' . $this->session->userdata('nama_tahun_akademik') . ' </li> <li>Semester ' . $this->session->userdata('nama_semester') . '</li> <li>Jurusan ' . $this->session->userdata('singkatan_jurusan') . '</li> <li>Matakuliah ' . $this->session->userdata('nama_matakuliah') . '</li>',
                'halaman' => $this->pagination->create_links(),
                'jumlah_data' => $data_count,
            );

//            $nilai = $this->nilai_model->get_all_nilai_matakuliah($this->session->userdata('kode_tahun_akademik'), $this->session->userdata('kode_jurusan_jenjang'), $this->session->userdata('id_matakuliah'), $this->limit, $offset);
            $nilai = $this->nilai_model->get_all_nilai_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $this->limit, $offset);
//            echo "<pre>";
//            print_r($nilai);
//            die();
            $i = 0 + $offset;

            $table = '<div class="box box-primary flat"><div class="box-body"><table class="table demo-table">';
            $table .= '<thead><tr>';
            $table .= '<th>NO.</th>';
            $table .= '<th id="th">NIM</th>';
            $table .= '<th id="th">NAMA MAHASISWA</th>';
            $table .= '<th id="th">NILAI HARIAN</th>';
            $table .= '<th id="th">NILAI UTS</th>';
            $table .= '<th id="th">NILAI UAS</th>';
            $table .= '<th id="th">NILAI AKHIR</th>';
            $table .= '<th id="th">GRADE</th>';
            $table .= '<th id="th">KETERANGAN</th>';
            $table .= '<th id="th">STATUS TB</th>';
            $table .= '</tr></thead>';
            foreach ($nilai as $row) {
                $table .= '<tr>';
                $table .= '<td align="center">' . ++$i . '</td>';
                $table .= '<td align="center">' . $row->nim . '</td>';
                $table .= '<td>' . $row->nama_mahasiswa . '</td>';
                $table .= '<td align="center">' . $row->nilai_harian . '</div></td>';
                $table .= '<td align="center">' . $row->nilai_uts . '</td>';
                $table .= '<td align="center">' . $row->nilai_uas . '</td>';
                $table .= '<td align="center">' . $nilai_akhir = $row->nilai_akhir . '</td>';

//                $kode_jurusan = substr($row->nim, 2, 2);
//                $kode_jenjang = substr($row->nim, 4, 1);
//
//                $jurusan = $this->db->query("select id_jurusan from jurusan where kode_jurusan='$kode_jurusan'")->row();
//                $jenjang = $this->db->query("select id_jenjang from jenjang where kode_jenjang='$kode_jenjang'")->row();
//
//                # mencari id jurusan dan id jenjang
//                $id_jurusan = $jurusan->id_jurusan;
//                $id_jenjang = $jenjang->id_jenjang;
//
//                $program_studi = $this->db->query("select kode_program_studi from program_studi where id_jenjang='$id_jenjang' and id_jurusan='$id_jurusan'")->row();
                #mencari kode program studi dan angkatan 
//                $kode_program_studi = $program_studi->kode_program_studi;
                $angkatan = "20" . substr($row->nim, 0, 2);

                #mencari kode kurikulum 
//                $kode_kurikulum = $this->db->query("select kode_nama_kurikulum from nama_kurikulum where angkatan='$angkatan' and kode_program_studi='$kode_program_studi'")->row();
//                $kode_nama_kurikulum = $kode_kurikulum->kode_nama_kurikulum;
                $kode_nama_kurikulum = kode_nama_kurikulum($row->nim);
//                echo $kode_nama_kurikulum;
//                die();
                $grade_data = $this->nilaiservice->get_grade($row->nim, $row->semester, $row->nilai_akhir);
                $table .= '<td align="center">' . $grade_data['grade'] . '</td>';
                $table .= '<td align="center">' . $grade_data['keterangan'] . '</td>';
                $table .= '<td align="center">' . $row->tidak_berhak . '</td>';
                $table .= '</tr>';
            }
            $table .= '</table></div></div>';
            $data['table'] = $table;
        } else {
            $data['message'] = "Tidak ditemukan satupun data nilai untuk Tahun Akademik " . $this->session->userdata('nama_tahun_akademik') . " dan Jurusan " . $this->session->userdata('nama_jurusan') . " serta Matakuliah " . $this->session->userdata('kode_matakuliah') . "-" . $this->session->userdata('nama_matakuliah') . "!";
        }
        $this->load->view('admin/template/V_main', $data);
    }

    function get_jurusan($id) {
        $this->session->set_userdata('kode_tahun_akademik', $id);

        $tmp = '';
        $data = $this->nama_jurusan_model->get();
        if (!empty($data)) {
            $tmp .= "<option value='' disabled selected>Pilih Jurusan</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row->kode_program_studi . "'>" . $row->nama_program_studi . "</option>";
            }
        } else {
            $tmp .= "<option value='' disabled selected>Pilih Jurusan</option>";
        }
        die($tmp);
    }

    function get_matakuliah($id, $kode_tahun_akademik = null) {
        $tmp = '';
        if ($kode_tahun_akademik == null) {
//            $tahun = tahun_akademik();
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $kode_program_studi = $id;
        $nama_jurusan = $this->nama_jurusan_model->get_kode_by_program_studi($kode_program_studi);

        $this->session->set_userdata('singkatan_program_studi', $nama_jurusan->singkatan_program_studi);

//        $kode_jurusan_jenjang = $nama_jurusan->kode_jurusan . '' . $nama_jurusan->kode_jenjang;
//        $data = $this->nilai_model->get_all_matakuliah_by_tahun_akademik_and_jurusan($tahun->kode_tahun_akademik, $kode_jurusan_jenjang);
//        $data = $this->nilai_model->get_all_matakuliah_by_tahun_akademik_and_jurusan($tahun->kode_tahun_akademik, $kode_program_studi);
        $data = $this->nilai_model->get_all_matakuliah_by_tahun_akademik_and_jurusan($kode_tahun_akademik, $kode_program_studi);

        if (!empty($data)) {
            $tmp .= "<option value='' disabled selected>Pilih Matakuliah</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row->id_matakuliah . "'>" . $row->kode_matakuliah . ' - ' . $row->nama_matakuliah . "</option>";
            }
        } else {
            $tmp .= "<option value='' disabled selected>Pilih Matakuliah</option>";
        }
        die($tmp);
    }

    function download() {

        $id_matakuliah = $this->session->userdata('id_matakuliah');
        $kode_program_studi = $this->session->userdata('kode_program_studi');
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik');

        $matakuliah = $this->m_matakuliah->get_matakuliah_by_kode($id_matakuliah);
        $nama_matakuliah = $matakuliah->nama_matakuliah;
        $file_name = $matakuliah->kode_matakuliah . "-" . $nama_matakuliah . " (" . $this->session->userdata('singkatan_jurusan') . ")";

        // Load data
        $nilai = $this->nilai_model->count_all_results_nilai_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);

        $table = '<table border="1">';
        $table .= '<tr>';
        $table .= '<th>NO.</th>';
        $table .= '<th>NIM</th>';
        $table .= '<th>NAMA MAHASISWA</th>';
        $table .= '<th>NILAI HARIAN</th>';
        $table .= '<th>NILAI UTS</th>';
        $table .= '<th>NILAI UAS</th>';
        $table .= '<th>NILAI AKHIR</th>';
        $table .= '<th id="th">GRADE</th>';
        $table .= '<th id="th">KETERANGAN</th>';
        $table .= '<th>STATUS TB</th>';
        $table .= '</tr>';
        $no = 0;
        foreach ($nilai as $row) {
            $table .= '<tr>';
            $table .= '<td>' . ++$no . '.</td>';
            $table .= '<td>' . $row->nim . '</td>';
            $table .= '<td>' . $row->nama_mahasiswa . '</td>';
            $table .= '<td>' . $nilai_harian = $row->nilai_harian . '</td>';
            $table .= '<td>' . $nilai_uts = $row->nilai_uts . '</td>';
            $table .= '<td>' . $nilai_uas = $row->nilai_uas . '</td>';
            $table .= '<td align="center">' . $nilai_akhir = $row->nilai_akhir . '</td>';

//            $kode_jurusan = substr($row->nim, 2, 2);
//            $kode_jenjang = substr($row->nim, 4, 1);
//
//            $jurusan = $this->db->query("select id_jurusan from jurusan where kode_jurusan='$kode_jurusan'")->row();
//            $jenjang = $this->db->query("select id_jenjang from jenjang where kode_jenjang='$kode_jenjang'")->row();
//
//            # mencari id jurusan dan id jenjang
//            $id_jurusan = $jurusan->id_jurusan;
//            $id_jenjang = $jenjang->id_jenjang;
//
//            $program_studi = $this->db->query("select kode_program_studi from program_studi where id_jenjang='$id_jenjang' and id_jurusan='$id_jurusan'")->row();
//
//            #mencari kode program studi dan angkatan
//            $kode_program_studi = $program_studi->kode_program_studi;
            $angkatan = "20" . substr($row->nim, 0, 2);

            #mencari kode kurikulum 
//            $kode_kurikulum = $this->db->query("select kode_nama_kurikulum from nama_kurikulum where angkatan='$angkatan' and kode_program_studi='$kode_program_studi'")->row();
//            $kode_nama_kurikulum = $kode_kurikulum->kode_nama_kurikulum;
            $grade_data = $this->nilaiservice->get_grade($row->nim, $row->semester, $row->nilai_akhir);
            $table .= '<td align="center">' . $grade_data['grade'] . '</td>';
            $table .= '<td align="center">' . $grade_data['keterangan'] . '</td>';
            $table .= '<td align="center">' . $row->tidak_berhak . '</td>';
            $table .= '</tr>';
        }
        $table .= '</table>';
        $data['table'] = $table;
        $data['file_name'] = $file_name;
        $this->load->view('admin/akademik/nilai/spreadsheet_view', $data);
    }

    function get_update_nilai() {
        $data = array(
            'content' => 'admin/akademik/nilai/View_update_nilai',
            'judul' => 'Akademik',
            'sub_judul' => 'Update Nilai Per Matakuliah',
            'program_studi' => $this->nama_jurusan_model->get(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    function get_update_nilai_process() {

        $data = array(
            'content' => 'admin/akademik/nilai/View_update_nilai',
            'judul' => 'Akademik',
            'sub_judul' => 'Update Nilai',
            'program_studi' => $this->nama_jurusan_model->get(),
        );

        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        $this->form_validation->set_rules('matakuliah', 'Matakuliah', 'required');

        if ($this->form_validation->run() == TRUE) {

            $nama_jurusan = $this->nama_jurusan_model->get_kode_by_program_studi($this->input->post('jurusan'));

            $kode_jurusan_jenjang = $nama_jurusan->kode_jurusan . '' . $nama_jurusan->kode_jenjang;

            $id_matakuliah = $this->input->post('matakuliah');

            $matakuliah = $this->m_matakuliah->get_matakuliah_by_kode($id_matakuliah);
            $nama_matakuliah = $matakuliah->nama_matakuliah;

            $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
            $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
            $nama_tahun_akademik = $tahun_akademik->tahun_akademik;

            $this->session->set_userdata('singkatan_jurusan', $nama_jurusan->singkatan_program_studi);
            $this->session->set_userdata('kode_jurusan_jenjang', $kode_jurusan_jenjang);
            $this->session->set_userdata('nama_matakuliah', $nama_matakuliah);
            $this->session->set_userdata('kode_tahun_akademik', $kode_tahun_akademik);
            $this->session->set_userdata('nama_tahun_akademik', $nama_tahun_akademik);
            $this->session->set_userdata('id_matakuliah', $id_matakuliah);
            $this->session->set_userdata('kode_program_studi', $this->input->post('jurusan'));

            // tambahan input filter menggunakan kelas
            $this->session->set_userdata('kelas_id', $this->input->post('kelas_id'));

            redirect('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah');
        } else {
            $data['default']['jurusan'] = $this->input->post('jurusan');
            $data['default']['matakuliah'] = $this->input->post('matakuliah');
            $this->load->view('admin/template/V_main', $data);
        }
    }

    function get_update_nilai_per_mahasiswa() {
        $data = array(
            'content' => 'admin/akademik/nilai/View_update_nilai_per_mahasiswa',
            'judul' => 'Akademik',
            'sub_judul' => 'Update Nilai Per Mahasiswa',
            'program_studi' => $this->nama_jurusan_model->get(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    function get_update_nilai_per_mahasiswa_process() {

        $data = array(
            'content' => 'admin/akademik/nilai/View_update_nilai',
            'judul' => 'Akademik',
            'sub_judul' => 'Update Nilai',
            'program_studi' => $this->nama_jurusan_model->get(),
        );

        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        $this->form_validation->set_rules('matakuliah', 'Matakuliah', 'required');
//        $this->form_validation->set_rules('nim', 'nim', 'required');

        $this->form_validation->set_rules('nim', 'nim', 'required|numeric|exact_length[10]', array('required' => 'Field NIM harus diisi', 'numeric' => 'Field NIM harus mengandung angka', 'exact_length' => 'Field NIM harus 10 karakter'));

        if ($this->form_validation->run() == TRUE) {

            $nama_jurusan = $this->nama_jurusan_model->get_kode_by_program_studi($this->input->post('jurusan'));

            $kode_jurusan_jenjang = $nama_jurusan->kode_jurusan . '' . $nama_jurusan->kode_jenjang;

            $id_matakuliah = $this->input->post('matakuliah');

            $matakuliah = $this->m_matakuliah->get_matakuliah_by_kode($id_matakuliah);
            $nama_matakuliah = $matakuliah->nama_matakuliah;

            $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
            $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
            $nama_tahun_akademik = $tahun_akademik->tahun_akademik;

            $this->session->set_userdata('singkatan_jurusan', $nama_jurusan->singkatan_program_studi);
            $this->session->set_userdata('kode_jurusan_jenjang', $kode_jurusan_jenjang);
            $this->session->set_userdata('nama_matakuliah', $nama_matakuliah);
            $this->session->set_userdata('kode_tahun_akademik', $kode_tahun_akademik);
            $this->session->set_userdata('nama_tahun_akademik', $nama_tahun_akademik);
            $this->session->set_userdata('id_matakuliah', $id_matakuliah);
            $this->session->set_userdata('nim', $this->input->post('nim'));
            $this->session->set_userdata('kode_program_studi', $this->input->post('jurusan'));

            redirect('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah_per_mahasiswa');
        } else {
//            dfdfdfdf
            $data = array(
                'content' => 'admin/akademik/nilai/View_update_nilai_per_mahasiswa',
                'judul' => 'Akademik',
                'sub_judul' => 'Update Nilai Per Mahasiswa',
                'program_studi' => $this->nama_jurusan_model->get(),
            );
            $this->load->view('admin/template/V_main', $data);
        }
    }

    function get_all_mahasiswa_for_update_nilai_matakuliah_per_mahasiswa() {
        $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
        $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
        $kode_program_studi = $this->session->userdata('kode_program_studi');
        $id_matakuliah = $this->session->userdata('id_matakuliah');
        $nim = $this->session->userdata('nim');
        $data_count = count($this->nilai_model->get_nilai_per_mahasiswa_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nim));

        if ($data_count > 0) {

            $data = array(
                'content' => 'admin/akademik/nilai/View_proses_update_per_mahasiswa',
                'judul' => 'Akademik',
                'sub_judul' => 'Update Nilai',
                'title_h1' => '<li> Tahun Akademik ' . $this->session->userdata('nama_tahun_akademik') . ' </li> <li>Semester ' . $this->session->userdata('nama_semester') . '</li> <li>Jurusan ' . $this->session->userdata('singkatan_jurusan') . '</li> <li>Matakuliah ' . $this->session->userdata('kode_matakuliah') . ' - ' . $this->session->userdata('nama_matakuliah') . '</li>',
                'halaman' => '',
                'jumlah_data' => $data_count,
                'nilai_matakuliah' => $this->nilai_model->get_nilai_per_mahasiswa_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $nim),
            );
            $this->load->view('admin/template/V_main', $data);
        } else {
            $this->session->set_flashdata('info',
                    '<script>swal("","Data tidak ditemukan!","error")</script>');
            redirect('admin/akademik/nilai/get_update_nilai_per_mahasiswa');
        }
    }

    function get_all_mahasiswa_for_update_nilai_matakuliah($offset = 0, $id_kelas = 0) {
        $matakuliah = $this->m_matakuliah->get_matakuliah_by_kode($this->session->userdata('id_matakuliah'));
        $nama_matakuliah = $matakuliah->nama_matakuliah;
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik');
        $kode_program_studi = $this->session->userdata('kode_program_studi');
        $id_matakuliah = $this->session->userdata('id_matakuliah');

        if ($id_kelas == 0) {
            // mengambil data filter kelas diawal pemilihan matakuliah dan kelas
            $kelas_id = $this->session->userdata('kelas_id');
        } elseif ($id_kelas == 01) {
            // untuk menampilkan semua data mahasiswa tanpa melihat kelas
            $this->session->set_userdata('kelas_id', 0);
            $kelas_id = 0;
        } else {
            // menampilkan filter spesifik perkelas
            $this->session->set_userdata('kelas_id', $id_kelas);
            $kelas_id = $id_kelas;
        }


        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }

//        $data_count = count($this->nilai_model->count_nilai_per_matakuliah_for_update($this->session->userdata('kode_tahun_akademik'), $this->session->userdata('kode_jurusan_jenjang'), $this->session->userdata('id_matakuliah')));
        $data_count = count($this->nilai_model->count_nilai_per_matakuliah_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah));

        if ($data_count > 0) {
            $config = array(
                'base_url' => site_url('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah'),
                'total_rows' => $data_count,
                'per_page' => $this->limit_500,
                'uri_segment' => $uri_segment,
                'full_tag_open' => '<div class="btn-group">',
                'full_tag_close' => '</div>',
                'cur_tag_open' => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
                'cur_tag_close' => '</a>',
                'attributes' => array('class' => 'btn btn-flat btn-xs btn-default'),
            );

            $this->pagination->initialize($config);

            $data = array(
                'content' => 'admin/akademik/nilai/View_proses_update',
                'judul' => 'Akademik',
                'sub_judul' => 'Update Nilai',
                'title_h1' => '<li> Tahun Akademik ' . $this->session->userdata('nama_tahun_akademik') . ' </li> <li>Semester ' . $this->session->userdata('nama_semester') . '</li> <li>Jurusan ' . $this->session->userdata('singkatan_jurusan') . '</li> <li>Matakuliah ' . $matakuliah->kode_matakuliah . ' - ' . $this->session->userdata('nama_matakuliah') . '</li>',
                'halaman' => $this->pagination->create_links(),
                'jumlah_data' => $data_count,
                'nilai_matakuliah' => $this->nilai_model->get_nilai_per_matakuliah_for_update($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $kelas_id, $this->limit_500, $offset),
                'data_kelas' => $this->nilai_model->get_kelas_matakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $kelas_id),
                'dosen' => $this->kuisioner_model->get_kelas_dosen($kelas_id)
            );
            $this->load->view('admin/template/V_main', $data);
        } else {
            $this->session->set_flashdata('message', "Data nilai untuk matakuliah tersebut tidak ditemukan!");
            redirect('admin/nilai/get_update_nilai');
        }
    }

    function get_all_mahasiswa_for_update_nilai_matakuliah_process() {
        $input = filter_input_array(INPUT_POST);
        if ($input['action'] === 'edit') {
            if (isset($input['nilai_harian'])) {
                $this->nilaiservice->update_khs_detail_field($input['kode_khs_detail'], 'nilai_harian', $input['nilai_harian']);
            }
            if (isset($input['nilai_uts'])) {
                $this->nilaiservice->update_khs_detail_field($input['kode_khs_detail'], 'nilai_uts', $input['nilai_uts']);
            }
            if (isset($input['nilai_uas'])) {
                $this->nilaiservice->update_khs_detail_field($input['kode_khs_detail'], 'nilai_uas', $input['nilai_uas']);
            }

            if (isset($input['nilai_akhir'])) {
                $this->nilaiservice->update_khs_detail_field($input['kode_khs_detail'], 'nilai_akhir', $input['nilai_akhir']);
            }

            $this->nilaiservice->set_tidak_berhak_status($input['kode_khs_detail'], $input['tidak_berhak']);
        } else if ($input['action'] === 'delete') {
            $this->nilaiservice->delete_khs_detail($input['kode_khs_detail']);
        } else if ($input['action'] === 'restore') {
            $this->nilaiservice->restore_khs_detail($input['kode_khs_detail']);
        }

        echo json_encode($input);
    }

    function update_nilai($kode_khs_detail) {
        $input_name = $this->input->post('input_name');
        $nilai = $this->input->post('nilai');
        if ($input_name == 'nilai_harian') {
            $ubah = $this->nilaiservice->update_khs_detail_field($kode_khs_detail, 'nilai_harian', empty($nilai) ? null : $nilai);
        } elseif ($input_name == 'nilai_uts') {
            $ubah = $this->nilaiservice->update_khs_detail_field($kode_khs_detail, 'nilai_uts', empty($nilai) ? null : $nilai);
        } elseif ($input_name == 'nilai_uas') {
            $ubah = $this->nilaiservice->update_khs_detail_field($kode_khs_detail, 'nilai_uas', empty($nilai) ? null : $nilai);
        } else {
            $ubah = $this->nilaiservice->update_khs_detail_field($kode_khs_detail, 'nilai_akhir', empty($nilai) ? null : $nilai);
        }

        if ($ubah) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function update_tidak_berhak($kode_khs_detail) {
        $tidak_berhak = $this->input->post('tidak_berhak');
        $ubah = $this->nilaiservice->update_khs_detail_field($kode_khs_detail, 'tidak_berhak', empty($tidak_berhak) ? null : $tidak_berhak);

        if ($ubah) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function upload() {
        if ($this->session->userdata('file_name')) {
            $path = "./assets/excel/" . $this->session->userdata('file_name');
            if (unlink($path)) {
                $this->session->unset_userdata('file_name');
            }
        }
        $this->load->library('upload'); // Load librari upload

        $config['upload_path'] = './assets/excel/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
//        $config['file_name'] = "tes";

        $this->upload->initialize($config); // Load konfigurasi uploadnya
        if ($this->upload->do_upload('file')) { // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $file_name = $this->upload->data('file_name');
            $this->session->set_userdata(array('file_name' => $file_name));
            $page = $this->load->view('admin/akademik/nilai/View_preview_nilai', $this->data_upload(), TRUE);
            $return = array('status' => true, 'file_name' => $file_name, 'data', 'page' => $page);
//            return $return;
            echo json_encode($return);
        } else {
            // Jika gagal :
            $return = array('status' => false, 'file' => '', 'error' => $this->upload->display_errors());
            echo json_encode($return);
        }
    }

    public function import() {
        // Load plugin PHPExcel nya
        $file_name = $this->session->userdata('file_name');
        require_once FCPATH . 'vendor/autoload.php';

        $excelreader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $loadexcel = $excelreader->load('assets/excel/' . $file_name); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
        $kode_matakuliah = $loadexcel->getActiveSheet()->getCell('C1')->getValue();

        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
//        $data = array();
        $error = array();

        $numrow = 1;
        $i = 0;
        foreach ($sheet as $row) {
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if ($numrow > 3) {
                // Kita push (add) array data ke variabel data
                $nim = $row['B'];
                $kode_nama_kurikulum = kode_nama_kurikulum($nim);
                $id_matakuliah = $this->nilaiservice->get_id_matakuliah_by_kode_kurikulum($kode_matakuliah, $kode_nama_kurikulum);
                $khs_detail = $this->nilaiservice->get_khs_detail_by_nim_matakuliah($nim, $id_matakuliah);
                if (count($khs_detail) > 0) {
                    $data = array(
                        'nilai_harian' => $row['D'],
                        'nilai_uts' => $row['E'],
                        'nilai_uas' => $row['F'],
                        'nilai_akhir' => $row['G'],
                        'tidak_berhak' => $row['I'],
                    );
                    $this->nilaiservice->update_khs_detail($khs_detail->kode_khs_detail, $data);
//                    array_push($data, array(
//                        'nim'=>$nim, // Insert data nama dari kolom B di excel
//                        'id_matakuliah'=>$id_matakuliah, // Insert data nama dari kolom B di excel
//                        'nama'=>$row['C'], // Insert data jenis kelamin dari kolom C di excel
//                        'nilai_harian'=>$row['D'], // Insert data alamat dari kolom D di excel
//                        'nilai_uts'=>$row['E'], // Insert data alamat dari kolom D di excel
//                        'nilai_uas'=>$row['F'], // Insert data alamat dari kolom D di excel
//                        'nilai_akhir'=>$row['G'], // Insert data alamat dari kolom D di excel
//                        'tidak_berhak'=>$row['I'], // Insert data alamat dari kolom D di excel
//                        'kode_nama_kurikulum'=>$kode_nama_kurikulum, // Insert data alamat dari kolom D di excel
//                        'kode_khs_detail'=>$khs_detail->kode_khs_detail, // Insert data alamat dari kolom D di excel
//                    ));
                } else {
                    $error['error'][$i]['msg'] = "Gagal ubah numrow excel <b>" . $numrow . "</b>, data tidak ditemukan";
                    $i++;
                }
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
//        return redirect(site_url('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah'));
        return $this->load->view("admin/akademik/nilai/Error_report", $error);

//        echo "<pre>";
//        print_r($kode_matakuliah);
//        print_r($data);
    }

    public function data_upload() {
        $file_name = $this->session->userdata('file_name');
        // Load plugin PHPExcel nya
        require_once FCPATH . 'vendor/autoload.php';

        $excelreader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $loadexcel = $excelreader->load('assets/excel/' . $file_name); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
        $kode_matakuliah = $loadexcel->getActiveSheet()->getCell('C1')->getValue();
        $nama_matakuliah = $loadexcel->getActiveSheet()->getCell('C2')->getValue();

        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $res['kode_matakuliah'] = $kode_matakuliah;
        $res['nama_matakuliah'] = $nama_matakuliah;
        $data = array();

        $numrow = 1;
        foreach ($sheet as $row) {
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if ($numrow > 3) {
                // Kita push (add) array data ke variabel data
                array_push($data, array(
                    'nim' => $row['B'], // Insert data nama dari kolom B di excel
                    'nama' => $row['C'], // Insert data jenis kelamin dari kolom C di excel
                    'nilai_harian' => $row['D'], // Insert data alamat dari kolom D di excel
                    'nilai_uts' => $row['E'], // Insert data alamat dari kolom D di excel
                    'nilai_uas' => $row['F'], // Insert data alamat dari kolom D di excel
                    'nilai_akhir' => $row['G'], // Insert data alamat dari kolom D di excel
                    'grade' => $row['H'], // Insert data alamat dari kolom D di excel
                    'tidak_berhak' => $row['I'], // Insert data alamat dari kolom D di excel
                ));
            }
            $numrow++; // Tambah 1 setiap kali looping
        }
        $res['data'] = $data;
        return $res;
    }

    public function delete_file() {
        $this->load->helper("file");
        if ($this->session->userdata('file_name')) {
            $path = "./assets/excel/" . $this->session->userdata('file_name');
            if (unlink($path)) {
                echo "berhasil hapus";
                $this->session->unset_userdata('file_name');
            } else {
                echo "gagal hapus";
            }
        }
    }

    public function persentase_penginputan() {
        $tahun_akademik = tahun_akademik();
        $persen = $this->nilaiservice->get_persentase_penginputan_semua($tahun_akademik->kode_tahun_akademik);

        $data = array(
            'content' => 'admin/akademik/nilai/persentase_penginputan/V_index',
            'judul' => 'Akademik',
            'sub_judul' => 'Persentase Penginputan',
            'prodi' => $persen,
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function persentase_penginputan_prodi($kode_program_studi) {
        $tahun_akademik = tahun_akademik();
        $hasil = $this->nilaiservice->get_persentase_penginputan_prodi($kode_program_studi, $tahun_akademik->kode_tahun_akademik);

        $data = array(
            'matakuliah' => $hasil,
        );
        $this->load->view('admin/akademik/nilai/persentase_penginputan/V_matakuliah', $data);
    }

    public function distribusi_nilai() {
        $data = array(
            'content' => 'admin/akademik/nilai/distribusi_nilai/V_index',
            'judul' => 'Akademik',
            'sub_judul' => 'Pendistribusian Nilai',
            'tahun_akademik' => $this->nilaiservice->get_all_tahun_akademik(),
            'program_studi' => $this->nama_jurusan_model->get(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function filter_distribusi() {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_program_studi = $this->input->post('kode_program_studi');
        $res['kode_tahun_akademik'] = $kode_tahun_akademik;
        $res['kode_program_studi'] = $kode_program_studi;
        
        $res['data'] = $this->nilaiservice->get_distribusi_nilai($kode_tahun_akademik, $kode_program_studi);
        $this->load->view('admin/akademik/nilai/distribusi_nilai/V_lists', $res);
    }

    public function distribusi_excel($kode_tahun_akademik, $kode_program_studi) {
        $res['tahun_akademik'] = $this->nilaiservice->get_tahun_akademik_by_kode($kode_tahun_akademik);
        $res['program_studi'] = $this->nilaiservice->get_program_studi_by_kode($kode_program_studi);
        
        $res['data'] = $this->nilaiservice->get_distribusi_nilai($kode_tahun_akademik, $kode_program_studi);

        $semester = $res['tahun_akademik']->semester == '1' ? "GANJIL" : 'GENAP';
        $res['filename'] = "REKAPITULASI PENDISTRIBUSIAN NILAI PROGRAM STUDI " . strtoupper($res['program_studi']->nama_program_studi) . " SEMESTER " . $semester . " " . $res['tahun_akademik']->tahun_akademik;
        $this->load->view('admin/akademik/nilai/distribusi_nilai/Excel', $res);
    }

}
