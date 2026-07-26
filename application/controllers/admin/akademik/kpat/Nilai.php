<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/kurikulum/m_matakuliah',
            'akademik/Nilai_kpat_model',
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
        $data['content'] = "admin/akademik/kpat/nilai/V_index";
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "Nilai KPAT";
        $data['judul_sub_judul'] = "KPAT";
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $kode_program_studi = $this->input->post('prodi');
        $id_matakuliah = $this->input->post('id_matakuliah');

//        $kode = $this->Nama_jurusan_model->get_kode($kode_program_studi);
//        $kode_jurusan = $this->Kode_jurusan_model->get_kode($kode->id_jurusan)->kode_jurusan;
//        $kode_jenjang = $this->Jenjang_model->get_kode($kode->id_jenjang)->kode_jenjang;
//        Buat data session
        $data_session = array(
            'sess_kode_tahun_akademik' => $kode_tahun_akademik,
//            'sess_kode_jurusan' => $kode_jurusan,
//            'sess_kode_jenjang' => $kode_jenjang,
            'sess_id_matakuliah' => $id_matakuliah,
            'sess_kode_program_studi' => $kode_program_studi,
        );
        $this->session->set_userdata($data_session);

        redirect('admin/akademik/kpat/nilai/data_penilaian_kpat');
    }

    public function data_penilaian_kpat() {
        $data['content'] = "admin/akademik/kpat/nilai/V_nilai";
        $data['judul'] = "Akademik";
//        extrak data session
        $kode_tahun_akademik = $this->session->userdata('sess_kode_tahun_akademik');
//        $kode_jurusan = $this->session->userdata('sess_kode_jurusan');
//        $kode_jenjang = $this->session->userdata('sess_kode_jenjang');
        $id_matakuliah = $this->session->userdata('sess_id_matakuliah');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');

        $data['sub_judul'] = "Nilai KPAT - " . $this->m_matakuliah->get_nama_matakuliah($id_matakuliah);
        $data['data'] = $this->Nilai_kpat_model->filter($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);

        $this->load->view('admin/template/V_main', $data);
    }

    public function get_matakuliah() {
        $kode_program_studi = $this->input->post('kode_program_studi');
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');

//        $data = $this->m_matakuliah->get_matakuliah_byid_prodi($kode_program_studi);
        $data = $this->kpatservice->getMatakuliahByProdiTa($kode_program_studi, $kode_tahun_akademik);

        foreach ($data as $row) {
            echo "<option value='" . $row->id_matakuliah . "'>".$row->kode_matakuliah ." - ". $row->nama_matakuliah . "</option>";
        }
    }

    public function ubah_nilai() {
        $input = filter_input_array(INPUT_POST);

        if ($input['action'] === 'edit') {
            if (isset($input['nilai_harian'])) {
                $this->kpatservice->updateNilaiKhsDetail($input['kode_khs_detail'], 'nilai_harian', $input['nilai_harian']);
            }
            if (isset($input['nilai_uts'])) {
                $this->kpatservice->updateNilaiKhsDetail($input['kode_khs_detail'], 'nilai_uts', $input['nilai_uts']);
            }
            if (isset($input['nilai_uas'])) {
                $this->kpatservice->updateNilaiKhsDetail($input['kode_khs_detail'], 'nilai_uas', $input['nilai_uas']);
            }
            if (isset($input['nilai_akhir'])) {
                $this->kpatservice->updateNilaiKhsDetail($input['kode_khs_detail'], 'nilai_akhir', $input['nilai_akhir']);
            }
        } else if ($input['action'] === 'delete') {
            $this->kpatservice->softDeleteKhsDetail($input['kode_khs_detail']);
        } else if ($input['action'] === 'restore') {
            $this->kpatservice->restoreKhsDetailNilai($input['kode_khs_detail']);
        }

        echo json_encode($input);
    }

}

/* End of file Nilai.php */
/* Location: ./application/controllers/Nilai.php */
?>