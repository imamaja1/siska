<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kkp extends CI_Controller {

    var $limit = 50;

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/Kkp_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model'
        ));
        $this->load->library(array('form_validation', 'pagination'));
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

    function index() {
        $data['content'] = 'admin/akademik/kkp/V_kkp';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Nilai Kuliah Kerja Profesi (KKP)';
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_tahun();
        $data['program_studi'] = $this->nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    function get_mahasiswa_process() {

        $this->form_validation->set_rules('angkatan', 'angkatan', 'required', array('required' => 'Field angkatan harus diisi'));
        $this->form_validation->set_rules('kode_program_studi', 'kode_program_studi', 'required', array('required' => ' Field program studi harus diisi'));
        $this->form_validation->set_rules('semester', 'Semester', 'required', array('required' => 'Field semester harus diisi'));

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'content' => 'admin/akademik/kkp/V_kkp',
                'judul' => 'Akademik',
                'sub_judul' => 'Nilai Kuliah Kerja Profesi (KKP)',
                'tahun_akademik' => $this->m_tahun_akademik->get_tahun(),
                'program_studi' => $this->nama_jurusan_model->get()
            );

            $this->load->view('admin/template/V_main', $data);
        } else {
            $kode_program_studi = $this->input->post('kode_program_studi');
            $nama_jurusan = $this->nama_jurusan_model->get_all_byid($kode_program_studi);
//            $kode_jurusan = substr($this->input->post('jurusan'), 0, 2);
//            $kode_jenjang = substr($this->input->post('jurusan'), 2, 2);
//
//            $nama_jurusan = $this->nama_jurusan_model->get_kode_nama_jurusan($kode_jurusan, $kode_jenjang);
//            $kode_angkatan_and_jurusan = $this->input->post('angkatan') . '' . $kode_jurusan . $kode_jenjang;

            $this->session->set_userdata('singkatan_program_studi', $nama_jurusan->singkatan_program_studi);
            $this->session->set_userdata('kode_program_studi', $kode_program_studi);
            $this->session->set_userdata('semester', $this->input->post('semester'));
//            $this->session->set_userdata('kode_angkatan_and_jurusan', $kode_angkatan_and_jurusan);
//            $this->session->set_userdata('kode_jurusan', $kode_jurusan);
//            $this->session->set_userdata('kode_jenjang', $kode_jenjang);
//            $this->session->set_userdata('angkatan', "20" . $this->input->post('angkatan'));
            $this->session->set_userdata('angkatan', $this->input->post('angkatan'));

            redirect('admin/akademik/kkp/get_all_nilai_kkp_by_angkatan_and_jurusan_semester');
        }
    }

    function get_all_nilai_kkp_by_angkatan_and_jurusan_semester($offset = 0) {

        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }
        $kode_program_studi = $this->session->userdata('kode_program_studi');
        $angkatan = $this->session->userdata('angkatan');
        $semester = $this->session->userdata('semester');

        $kode_nama_kurikulum = get_kode_nama_kurikulum_by_prodi_angkatan($kode_program_studi, $angkatan);
        $matakuliah = get_makul_kkp_by_kode_nama_kurikulum($kode_nama_kurikulum);
//        $kode_nama_jurusan = $this->session->userdata('kode_nama_jurusan');
//        switch ($kode_nama_jurusan) {
//            # 003 kode untuk program studi D3MI
//            case '003':
////                $kode_matakuliah = 'MDKB240123';
//                $kode_matakuliah = 'MDKB240123,MDBB340015';
//                break;
//            # 103 kode untuk program studi D3TI
//            case '103':
////                $kode_matakuliah = 'TDBB350020';
//                $kode_matakuliah = 'TDBB350020';
//                break;
//            # 105 kode untuk program studi S1TI
//            case '105':
////                $kode_matakuliah = 'TSBB370068';
//                $kode_matakuliah = 'TSBB370068';
//                break;
//        }

//        $data_count = count($this->Kkp_model->count_all_results_nilai_kkp_by_kode_angkatan_and_jurusan_matakuliah_semester($this->session->userdata('kode_angkatan_and_jurusan'), $kode_matakuliah, $this->session->userdata('semester')));
//        $data_count = count($this->Kkp_model->count_all_nilai_kkp_by_fileter($this->session->userdata('kode_angkatan_and_jurusan'), $kode_matakuliah, $this->session->userdata('semester')));
        $data_count = count($this->Kkp_model->count_all_nilai_kkp_by_fileter($kode_program_studi, $angkatan, $matakuliah->kode_matakuliah, $semester));
        $config = array(
            'base_url' => site_url('admin/akademik/kkp/get_all_nilai_kkp_by_angkatan_and_jurusan_semester'),
            'total_rows' => $data_count,
            'per_page' => $this->limit,
            'uri_segment' => $uri_segment,
            'full_tag_open' => '<div class="btn-group">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a href="#!" class="btn btn-sm btn-flat btn-default disabled">',
            'cur_tag_close' => '</a>',
            'attributes' => array('class' => 'btn btn-flat btn-sm btn-default'),
        );

        $this->pagination->initialize($config);
        $nilai_kkp = $this->Kkp_model->get_all_nilai_kkp_by_fileter($kode_program_studi, $angkatan, $matakuliah->kode_matakuliah, $semester, $this->limit, $offset);
//        echo "<pre>";
//        print_r($nilai_kkp);
//        die();
        $data = array(
            'content' => 'admin/akademik/kkp/V_nilai_kkp',
            'judul' => 'Akademik',
            'sub_judul' => 'Kuliah Kerja Profesi(KKP)',
            'halaman' => $this->pagination->create_links(),
            'jumlah_data' => $data_count,
            'data' => $nilai_kkp,
        );
        if ($data_count <= 0) {
            $data['message'] = "Tidak ditemukan satupun Data nilai KKP mahasiswa untuk Angkatan " . $this->session->userdata('angkatan') . " dan Jurusan " . $this->session->userdata('singkatan_program_studi') . " serta Semester " . $this->session->userdata('semester') . "!";
        }
        $this->load->view('admin/template/V_main', $data);
    }

    function search() {
        $data = array(
            'content' => 'admin/akademik/kkp/V_search',
            'judul' => 'Akademik',
            'sub_judul' => 'Pencarian',
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function search_process() {
        $berdasarkan = $this->input->post('berdasarkan');
        $kata_kunci = $this->input->post('kata_kunci');

        $this->form_validation->set_rules('berdasarkan', 'berdasarkan', 'required', array('required' => 'Field Berdasarkan harus dipilih'));
        $this->form_validation->set_rules('kata_kunci', 'kata_kunci', 'required|max_length[75]', array('required' => 'Field Kata Kunci harus diisi', 'max_length' => 'Field Kata Kunci tidak boleh lebih dari 75 Karakter'));
        if ($berdasarkan == 'nim') {
            $this->form_validation->set_rules('kata_kunci', 'kata kunci', 'required|numeric|exact_length[10]', array('required' => 'Field Kata Kunci harus diisi', 'numeric' => 'Field Kata Kunci harus mengandung bilangan positif', 'exact_length' => 'Field Kata Kunci harus 10 karakter'));
        }

        if ($this->form_validation->run() == TRUE) {
            $nim = $this->input->post('kata_kunci');
//            if ($this->mahasiswa_model->valid_nim($nim)) {
            if ($berdasarkan == 'nim'){
                $this->session->set_userdata('search', true);
                $data['data'] = $this->Kkp_model->get_nilai_kkp_by_nim($kata_kunci);
                $data['message'] = 'Kata kunci <strong>'.$kata_kunci.'</strong> tidak ditemukan';
                $data['content'] = 'admin/akademik/kkp/V_search_find';
                $data['judul'] = 'Akademik';
                $data['sub_judul'] = 'Pencarian "<i>'.e($kata_kunci).'</i>"';

                $this->load->view('admin/template/V_main',$data);
            }else{
                $this->session->set_userdata('search', true);
                $data['data'] = $this->Kkp_model->get_nilai_kkp_by_nama_mahasiswa($kata_kunci);
                $data['message'] = 'Kata kunci <strong>'.$kata_kunci.'</strong> tidak ditemukan';
                $data['content'] = 'admin/akademik/kkp/V_search_find';
                $data['judul'] = 'Akademik';
                $data['sub_judul'] = 'Pencarian "<i>'.e($kata_kunci).'</i>"';

                $this->load->view('admin/template/V_main',$data);
            }

//            } else {
//
//            }
        } else {
            $data_a = array(
                'content' => 'admin/akademik/kkp/V_search',
                'judul' => 'Akademik',
                'sub_judul' => 'Mahasiswa',
                'title_h1' => '<i class="fa fa-map"></i> <li>Akademik</li>',
                'title_h2' => '<li>Mahasiswa</li>',
                'title_h3' => '<li>Pencarian</li>',
            );
            $this->load->view('admin/template/V_main', $data_a);
        }
    }

    function save_edit_nilai_kkp($kode_krs_detail)
    {
        $input = filter_input_array(INPUT_POST);
        if ($input['action'] == 'edit') {
            $this->Kkp_model->update_nilai_akhir($input['kode_krs_detail'], $input['edit_nilai_akhir']);
        }
        echo json_encode(array('status' => true));
    }

}
