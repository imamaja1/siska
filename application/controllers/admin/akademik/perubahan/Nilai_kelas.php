<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_kelas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kuisioner/kelas_model',
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
        $data['sub_judul'] = "Nilai Per Kelas";
        $data['judul_sub_judul'] = "Perubahan";
        $data['content'] = "admin/akademik/perubahan/nilai_kelas/V_index";
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function get_matakuliah($kode_tahun_akademik) {
        $result = $this->kelas_model->get_matakuliah($kode_tahun_akademik);
        if (!empty($result)) {
            echo '<option value="">Pilih Matakuliah</option>';
            foreach ($result as $row) {
                echo '<option value="' . e($row->id_matakuliah) . '">' . e($row->kode_matakuliah) . ' - ' . e($row->nama_matakuliah) . '</option>';
            }
        } else {
            echo '<option value="">Matakuliah tidak ditemukan</option>';
        }
    }

    public function get_kelas($kode_tahun_akademik, $id_matakuliah) {
        $result = $this->kelas_model->get_kelas_angkatan_bawah($id_matakuliah, $kode_tahun_akademik);
        if (!empty($result)) {
            echo '<option value="">Pilih Kelas</option>';
            foreach ($result as $row) {
                echo '<option value="' . e($row->kelas_id) . '">' . e($row->nama_kelas) . ' (' . (int) $row->jml . ' mhs)</option>';
            }
        } else {
            echo '<option value="">Tidak ada kelas yang memenuhi (angkatan 25+)</option>';
        }
    }

    public function hasil($kode_tahun_akademik, $id_matakuliah, $kelas_id) {
        $data['kelas'] = $this->kelas_model->get_nama_kelas_by_kelas_id($kelas_id);
        $data['matakuliah'] = $this->kelas_model->get_matakuliah_by_kelas_id($kelas_id);
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_tahun_akademik_by_kode_one($kode_tahun_akademik);
        $data['data'] = $this->kelas_model->get_mahasiswa_kelas_nilai($kelas_id);
        foreach ($data['data'] as $row) {
            if ($row->nilai_akhir !== null && $row->nilai_akhir !== '') {
                $grade = $this->nilaiservice->get_grade($row->nim, $row->semester, $row->nilai_akhir);
                $row->grade = $grade['grade'];
            } else {
                $row->grade = '-';
            }
        }
        $this->load->view('admin/akademik/perubahan/nilai_kelas/V_mahasiswa_kelas', $data);
    }

    public function ubah_krs_nilai() {
        $input = filter_input_array(INPUT_POST);

        if (isset($input['action']) && $input['action'] === 'edit') {
            $kode_krs_detail = isset($input['kode_krs_detail']) ? $input['kode_krs_detail'] : '';
            $nilai_harian = isset($input['edit_nilai_harian']) ? $input['edit_nilai_harian'] : null;
            $nilai_uts = isset($input['edit_nilai_uts']) ? $input['edit_nilai_uts'] : null;
            $nilai_uas = isset($input['edit_nilai_uas']) ? $input['edit_nilai_uas'] : null;
            $nilai_akhir = isset($input['edit_nilai_akhir']) ? $input['edit_nilai_akhir'] : null;
            $tidak_berhak = isset($input['tidak_berhak']) ? $input['tidak_berhak'] : 'N';

            $updated = $this->nilaiservice->edit_khs_detail_full($kode_krs_detail, $nilai_harian, $nilai_uts, $nilai_uas, $nilai_akhir, $tidak_berhak);
            if (!$updated) {
                $this->db->insert('khs_detail', array('kode_krs_detail' => $kode_krs_detail));
                $this->nilaiservice->edit_khs_detail_full($kode_krs_detail, $nilai_harian, $nilai_uts, $nilai_uas, $nilai_akhir, $tidak_berhak);
            }

            $grade = '';
            if ($nilai_akhir !== null && $nilai_akhir !== '') {
                $mhs = $this->db->select('krs.nim, krs.semester')
                    ->from('krs_detail as kd')
                    ->join('krs', 'krs.kode_krs=kd.kode_krs')
                    ->where('kd.kode_krs_detail', $kode_krs_detail)
                    ->get()->row_object();
                if ($mhs) {
                    $grade_data = $this->nilaiservice->get_grade($mhs->nim, $mhs->semester, $nilai_akhir);
                    $grade = $grade_data['grade'];
                }
            }

            echo json_encode(array('status' => true, 'action' => 'edit', 'grade' => $grade));
        } else if (isset($input['action']) && $input['action'] === 'delete') {
            $this->nilaiservice->delete_krs_detail_cascade(isset($input['kode_krs_detail']) ? $input['kode_krs_detail'] : '');
            echo json_encode(array('status' => true, 'action' => 'delete'));
        } else {
            echo json_encode(array('status' => false));
        }
    }

}
