<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Krs_mahasiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/Krs_model',
            'jurusan/m_tahun_akademik',
        ));
        $this->load->service('NilaiService');

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

    public function index() {
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KRS Mahasiswa";
        $data['judul_sub_judul'] = "Perubahan";
        $data['content'] = "admin/akademik/perubahan/krs_mahasiswa/V_index";

        $this->load->view('admin/template/V_main', $data);
    }

    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $result = $this->Krs_model->autocomplate($keyword);
        if ($keyword != '') {
            if (!empty($result)) {
                echo '<ul id="nim-list" class="list-group">';
                foreach ($result as $row) {
                    echo '<li onClick="selectNim(\'' . $row->nim . '\')" class="list-group-item">' . $row->nim . '</li>';
                }
                echo '</ul>';
            } else {
                echo "Data tidak ditemukan";
            }
        }
    }

    public function tampil($nim) {
        $mahasiswa = $this->db->select('mahasiswa.*, program_studi.nama_program_studi')
            ->from('mahasiswa')
            ->join('program_studi', 'program_studi.kode_program_studi = mahasiswa.program_studi_kode', 'left')
            ->where('mahasiswa.nim', $nim)
            ->get()->row_object();

        if (!$mahasiswa) {
            $this->session->set_flashdata('info', '<script>swal("Gagal", "Mahasiswa tidak ditemukan", "error");</script>');
            redirect('admin/akademik/perubahan/krs_mahasiswa');
        }

        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KRS Mahasiswa";
        $data['judul_sub_judul'] = "Perubahan";
        $data['content'] = "admin/akademik/perubahan/krs_mahasiswa/V_krs_mahasiswa";
        $data['mahasiswa'] = $mahasiswa;

        $data_krs = $this->Krs_model->get_all_krs_detail_by_nim($nim);
        foreach ($data_krs as $row) {
            $grade = $this->nilaiservice->get_grade($nim, $row->semester, $row->nilai_akhir);
            $row->grade = $grade['grade'];
        }
        $data['data'] = $data_krs;

        $this->load->view('admin/template/V_main', $data);
    }

    public function ubah_krs_nilai() {
        $input = filter_input_array(INPUT_POST);

        if (isset($input['action']) && $input['action'] === 'delete') {
            $this->nilaiservice->delete_krs_detail_cascade(isset($input['kode_krs_detail']) ? $input['kode_krs_detail'] : '');
            echo json_encode(array('status' => true, 'action' => 'delete'));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}
