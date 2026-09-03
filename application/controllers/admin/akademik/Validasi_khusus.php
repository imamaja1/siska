<?php

class Validasi_khusus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/universitas/Fakultas_model',
            'laporan/laporan_model',
            'akademik/Nilai_model',
        ));
        $this->load->service('ValidasiService');
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
        $this->load->library('pagination');
    }

    public function cari() {
//        $id_dekan = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get_nilai_26();
        $data['kode_fakultas'] = $this->validasiservice->get_all_fakultas();

        $kode_fakultas = $this->input->post("kd_fk");
        $kode_tahun_akademik = $this->input->post("kode_tahun_akademik");
        $data['match_kode_fakultas'] = $kode_fakultas;

        $prodi = $this->Fakultas_model->getProdiFromDekan($kode_fakultas);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        //echo json_encode( $kode_tahun_akademik );
//        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->validasiservice->get_kelas_validasi_by_prodi_and_ta($kode_prodi, $kode_tahun_akademik);
        foreach ($kelas as $key => $value) {
            $kelas[$key]->nilai_validasi = $this->validasiservice->get_dummy_update_kelas_by_id_kelas($value->kelas_id);
        }
        $data['content'] = 'admin/akademik/nilai/V_tervalidasi_khusus';
        $data['judul'] = 'Akademik';
        $data['tahun_akademik'] = $ta;
        $data['ta'] = $kode_tahun_akademik;
        $data['sub_judul'] = 'Nilai';
        $data['kelas'] = $kelas;

        $this->load->view('admin/template/V_main', $data);
    }
    public function index() {
        $data['kode_fakultas'] = $this->validasiservice->get_all_fakultas();
        $data['match_kode_fakultas'] = '';
        $kode_fkk = $this->validasiservice->get_fakultas_by_kode(1);
        $ta = $this->m_tahun_akademik->get_nilai_26();
        $prodi = $this->Fakultas_model->getProdiFromDekan($kode_fkk['dekan']);
        $kode_prodi = array_column($prodi, 'kode_program_studi');

        $kelas = $this->validasiservice->get_kelas_validasi_by_prodi($kode_prodi);
        foreach ($kelas as $key => $value) {
            $kelas[$key]->nilai_validasi = $this->validasiservice->get_dummy_update_kelas_by_id_kelas($value->kelas_id);
        }
        $data['content'] = 'admin/akademik/nilai/V_tervalidasi_khusus';
        $data['judul'] = 'Akademik';
        $data['tahun_akademik'] = $ta;
        $data['ta'] = $this->m_tahun_akademik->get_aktif();
        $data['sub_judul'] = 'Nilai';
        $data['kelas'] = $kelas;

        $this->load->view('admin/template/V_main', $data);
    }

    public function cek_uas($id){
        $kelas = $this->validasiservice->get_kelas_row_by_id($id);
        if ($kelas->cek_uas == 1) {
            $this->validasiservice->update_kelas($id, array('cek_uas' => 2));
        }else{
            $this->validasiservice->update_kelas($id, array('cek_uas' => 1));
        }
    }
    public function mhs($id){
        $data['kelas'] = $this->validasiservice->get_mahasiswa_by_kelas_id($id);
        $this->load->view('admin/akademik/nilai/V_mhs', $data);
    }
    public function revisi_nilai_divalidasi(){
        $kelas = $this->input->POST('kelas');
        $kode_ta = $this->input->POST('kode_ta');

        $data['kelas'] = $this->validasiservice->get_dummy_update_kelas_by_id_kelas($kelas);

        foreach ($data['kelas'] as $key => $value) {
            $data['kelas'][$key]->isi_nilai = $this->validasiservice->get_nilai_revisi_by_kelas_and_level($kelas, $value->level, $kode_ta);
        }
        $data['ta'] = $kode_ta;
        $this->load->view('admin/akademik/nilai/V_revisi_nilai_mahasiswa_tervalidasi', $data);
        // echo json_encode($data['kelas']);
    }
    public function cetak_nilai_revisi_kelas($id,$level,$ta) {
        $data['query1'] = $this->validasiservice->get_cetak_kelas_info($id);

        $data['query2'] = $this->validasiservice->get_cetak_nilai_revisi($id, $level, $ta);

        $data['query3'] = $this->validasiservice->get_dekan_info_by_kelas($id);

        $data['query4'] = $this->validasiservice->get_all_sistem_penilaian_detail();

        $data['nama_dosen'] = $this->validasiservice->get_nama_dosen_by_kelas($id);

        $data['persentase'] = $this->validasiservice->get_persentasi_nilai_dosen_by_kelas($id);

        $namafile = $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";
        $data['dosen'] = 'true';
        $this->load->library('pdf');
        $this->pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->pdf;
        // $this->load->view("admin/akademik/nilai/V_cetak_nilai_revisi", $data);
        $html = $this->load->view("admin/akademik/nilai/V_cetak_nilai_revisi", $data, true);
        $header = $this->load->view('admin/akademik/nilai/V_cetak_header_uts', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }

}
