<?php

class validasinilai_kpat extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/universitas/Fakultas_model',
            'laporan/laporan_model',
            'akademik/Nilai_model'
        ));
        $this->load->service('DekanService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isDekan($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function validasi_nilai_revisi($ta = null) {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $data['content'] = 'dosen/dekan/V_index_revisi';
        $data['judul'] = 'Validas Nilai Mahasiswa';
        $data['select'] = $kode_tahun_akademik;
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['a_validasi_nilai_dekan'] = 'active';
        $data['a_validasi_nilai_uts_dekan'] = 'active';

        $this->load->view('dosen/template/V_main', $data);
    }
    public function choose_nilai_revisi() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }

        $dekan = $this->dekanservice->get_dekan_prodi($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');

        $kelas = $this->dekanservice->get_kelas_revisi_kpat_dekan($kode_prodi, $kode_tahun_akademik);

        foreach ($kelas as $key => $value) {
            $num = $this->dekanservice->get_dummy_update_kelas_kpat_status($value->kelas_id, '1');
            $num2 = $this->dekanservice->get_dummy_update_kelas_kpat_status($value->kelas_id, '2');

            $tmp = $this->dekanservice->get_dummy_update_kelas_kpat_row($value->kelas_id, $num, $num2);

            $nama_dosen = $this->dekanservice->get_nama_dosen_kelas_kpat($value->kelas_id);

            $kelas[$key]->data_kelas = $tmp;
            $kelas[$key]->order = $tmp->status ? $tmp->status : '5';
            $kelas[$key]->dosen_pengampu = $nama_dosen;
        }
        $orderedObjects = $this->customSort($kelas);
        $data['kelas'] = $orderedObjects;
        $this->load->view('dosen/dekan/V_choose_revisi', $data);
    }
    public function revisi_nilai_validasi(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $kode_ta = $this->input->POST('kode_ta');
   
        $data['data'] = $this->dekanservice->get_revisi_nilai_validasi_kpat_dekan($kelas, $level, $kode_ta);
        
        $data['kelas'] = $this->dekanservice->get_revisi_nilai_kelas_kpat_dekan($kelas, $level);
                        
        $data['nilai'] = true;
        $this->load->view('dosen/dekan/V_revisi_nilai_mahasiswa', $data);
    }
    public function revisi_nilai_validasi_mhs(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
   
        $data['data'] = $this->dekanservice->get_mahasiswa_kelas_only_kpat_dekan($kelas);
        $data['nilai'] = false;
        $this->load->view('dosen/dekan/V_revisi_nilai_mahasiswa', $data);
    }
    public function revisi_nilai_divalidasi(){
        $kelas = $this->input->POST('kelas');
        $kode_ta = $this->input->POST('kode_ta');

        $data['kelas'] = $this->dekanservice->get_revisi_nilai_divalidasi_kpat_dekan($kelas);
        
        foreach ($data['kelas'] as $key => $value) {
            $data['kelas'][$key]->isi_nilai = $this->dekanservice->get_isi_nilai_divalidasi_kpat_dekan($kelas, $value->level, $kode_ta);
        }
        $this->load->view('dosen/kaprodi/validasi-nilai/V_revisi_nilai_mahasiswa_tervalidasi', $data);
    }
    public function revisi_nilai_selesai(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $this->dekanservice->update_dummy_update_kelas_kpat(array('id_kelas' => $kelas, 'level' => $level), array('status_dekan' => 'T','status' => '3'));
        $nilai = $this->dekanservice->get_dummy_update_nilai_kpat($kelas, $level);
        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_harian' => $row->harian,
                'nilai_uts' => $row->uts,
                'nilai_uas' => $row->uas,
                'nilai_akhir' => $row->na,
            );
            $tmp = $this->dekanservice->get_khs_detail_row($row->kode_khs_detail);
            if($row->na){
                $this->dekanservice->update_khs_detail($row->kode_khs_detail, $data_nilai);
            }
        }
        echo json_encode(array('status'=> true));
    }
    public function revisi_nilai_revisi(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $pesan = $this->input->POST('pesan');

        $this->dekanservice->update_dummy_update_kelas_kpat(array('id_kelas' => $kelas, 'level' => $level), array('status_prodi' => 'R','status_dosen' => 'R','status_dekan' => 'R','status' => '4'));
        
        $massage1 = array('kelas_id' => $kelas,
            'pesan_dekan' => $pesan,
            'param_dekan' => 1,
            'kode_dosen' => 1,
            'kode_dekan' => 1,
            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));

        $this->dekanservice->insert('catatan_revisi_kpat', $massage1);
        echo json_encode(array('status'=> true));
    }

    // =============================================================================
    public function index() {
        $id_dekan = $this->session->userdata('kode_dosen');

        $prodi = $this->Fakultas_model->getProdiFromDekan($id_dekan);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dekanservice->get_kelas_validasi_simple($kode_prodi, $kode_tahun_akademik);
        $data['content'] = 'dosen/dekan/V_penilaian';
        $data['judul'] = 'Validas Nilai';
        $data['kelas'] = $kelas;

        $this->load->view('dosen/template/V_main', $data);
    }

    public function kelas_uts() {
        $id_dekan = $this->session->userdata('kode_dosen');
        $prodi = $this->Fakultas_model->getProdiFromDekan($id_dekan);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dekanservice->get_kelas_uts_dekan($kode_prodi, $kode_tahun_akademik);
        $data['kelas'] = $kelas;

        $pesan_dosen = array();
        foreach ($kelas as $key => $value) {
            $tmp_dosen = $this->dekanservice->get_catatan_revisi_dekan_count($value->kelas_id);
            $pesan_dosen[$value->kelas_id] = count($tmp_dosen);
        }
        $data['pesan_dosen'] = $pesan_dosen;
        $this->load->view('dosen/dekan/V_kelas_uts', $data);
    }

    public function kelas_uas() {
        $id_dekan = $this->session->userdata('kode_dosen');
        $prodi = $this->Fakultas_model->getProdiFromDekan($id_dekan);
        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kelas = $this->dekanservice->get_kelas_uas_dekan($kode_prodi, $kode_tahun_akademik);

        $data['kelas'] = $kelas;

        $this->load->view('dosen/dekan/V_kelas_uas', $data);
    }

    public function data_mahasiswa_uts($kelas_id) {
        $kelas_mahasiswa = $this->dekanservice->get_mahasiswa_uts_dekan($kelas_id);
        $data_kelas = $this->dekanservice->get_data_kelas_dekan($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/dekan/V_nilai_uts', $data);
    }

    public function data_mahasiswa_uas($kelas_id) {
        $kelas_mahasiswa = $this->dekanservice->get_mahasiswa_uas_dekan($kelas_id);
        $data_kelas = $this->dekanservice->get_data_kelas_dekan($kelas_id);
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $this->load->view('dosen/dekan/V_nilai_uas', $data);
    }

    public function revisi_uas() {
        $note = htmlspecialchars($this->input->post('catatan_prodi'));
        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));
        
        $this->dekanservice->update('kelas', array('catatan_dekan' => $note, 'status_nilai' => 'R', 'validasi_dekan' => 'R', 'validasi_nilai' => 'R'), array('kelas_id' => $kelas_id));
        $this->dekanservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'R', 'validasi_dekan' => 'R', 'validasi_prodi' => 'R']);

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);
        
        $query_prodi = $this->dekanservice->get_query_prodi_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Catatan " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . " untuk nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", yaitu: " . $note . ", Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);
        kirim_ke_telegram($query_prodi['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $note,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));

        $this->dekanservice->insert('catatan_revisi_uas',$massage1);
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function revisi_uts() {
        $note = htmlspecialchars($this->input->post('catatan_prodi'));
        $kelas_id = htmlspecialchars($this->input->post('kelas_id'));
        
        $this->dekanservice->update('kelas', array('catatan_dekan' => $note, 'status_nilai_uts' => 'R', 'validasi_dekan_uts' => 'R', 'validasi_nilai_uts' => 'R'), array('kelas_id' => $kelas_id));
        $this->dekanservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian_uts' => 'R', 'validasi_dekan_uts' => 'R', 'validasi_prodi_uts' => 'R']);

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);
        
        $query_prodi = $this->dekanservice->get_query_prodi_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Catatan " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . " untuk nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", yaitu: " . $note . ", Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);
        kirim_ke_telegram($query_prodi['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $note,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));

        $this->dekanservice->insert('catatan_revisi',$massage1);
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function validasi_uts($kelas_id) {
        $nilai = $this->dekanservice->get_dummy_nilai_kelas($kelas_id);
        $this->dekanservice->update('kelas', array('validasi_dekan_uts' => 'T'), array('kelas_id' => $kelas_id));
        $this->dekanservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian_uts' => 'T', 'validasi_prodi_uts' => 'T', 'validasi_dekan_uts' => 'T']);

        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_harian' => $row->dummy_harian,
                'nilai_uts' => $row->dummy_uts,
                'nilai_uas' => $row->dummy_uas,
                'nilai_akhir' => $row->dummy_na,
            );
            $ubah = $this->Nilai_model->validasi_dekan($row->dummy_id, $data_nilai);
            if ($ubah) {
                $res['st'][] = true;
            } else {
                $res['st'][] = false;
            }
        }

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah disetujui oleh " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", nilai per matakuliah dapat dicetak pada menu penilaian untuk keperluan BKD/Lainnya, Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $message_text,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));
       
        $resss = $this->dekanservice->insert('catatan_revisi',$massage1);
        echo $resss;
    }

    public function validasi_uas($kelas_id) {
        $nilai = $this->dekanservice->get_dummy_nilai_kelas($kelas_id);
        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_harian' => $row->dummy_harian,
                'nilai_uts' => $row->dummy_uts,
                'nilai_uas' => $row->dummy_uas,
                'nilai_akhir' => $row->dummy_na,
            );
            $ubah = $this->Nilai_model->validasi_dekan($row->dummy_id, $data_nilai);
            if ($ubah) {
                $res['st'][] = true;
            } else {
                $res['st'][] = false;
            }
        }
        $this->dekanservice->update('kelas', array('validasi_dekan' => 'T'), array('kelas_id' => $kelas_id));
        $this->dekanservice->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'T', 'validasi_prodi' => 'T', 'validasi_dekan' => 'T']);

        $query_dosen_kelas = $this->dekanservice->get_query_dosen_kelas_dekan($kelas_id);

        $query_fakultas = $this->dekanservice->get_query_fakultas_dekan($kelas_id);

        $message_text = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah disetujui oleh " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", nilai per matakuliah dapat dicetak pada menu penilaian untuk keperluan BKD/Lainnya, Kode Kelas *[". $query_dosen_kelas['kelas_id'] ."]*";
        kirim_ke_telegram($query_dosen_kelas['chatid'], $message_text);

        $massage1 = array('kelas_id' => $kelas_id, 
                            'pesan_dekan' => $message_text,
                            'param_dekan' => 1,
                            'kode_dosen' => 1,
                            'kode_dekan' => 1,
                            'tgl_dekan' => date_create('now', timezone_open('Asia/Singapore'))->format('Y-m-d H:i:s'));
       
        $resss = $this->dekanservice->insert('catatan_revisi_uas',$massage1);
        echo $resss;
    }

   public function validasi_nilai_uas() {
        $dekan = $this->dekanservice->get_dekan_prodi($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $kelas = $this->dekanservice->get_kelas_validasi_simple($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/dekan/V_penilaian_uas';
        $data['judul'] = 'Validas Nilai UAS';
        $data['kelas'] = $kelas;
        
        $data['a_validasi_nilai_dekan'] = 'active';
        $data['a_validasi_nilai_uas_dekan'] = 'active';

       
        $this->load->view('dosen/template/V_main', $data);
    }
    public function validasi_nilai_uts($ta = null) {
        $dekan = $this->dekanservice->get_dekan_prodi($this->session->userdata('kode_dosen'));

        $kode_prodi = array_column($prodi, 'kode_program_studi');
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;

        $kelas = $this->dekanservice->get_kelas_validasi_simple($kode_prodi, $kode_tahun_akademik);

        $data['content'] = 'dosen/dekan/V_penilaian_uts';
        $data['judul'] = 'Validas Nilai UTS';
        $data['kelas'] = $kelas;
        
        $data['a_validasi_nilai_dekan'] = 'active';
        $data['a_validasi_nilai_uts_dekan'] = 'active';

        $this->load->view('dosen/template/V_main', $data);
    }

    function customSort($array) {
        usort($array, function($a, $b) {
            $order = [2, 3, 1, 4, 5];
            $indexA = array_search($a->order, $order);
            $indexB = array_search($b->order, $order);
            return $indexA - $indexB;
        });
        return $array;
    }

    public function show_nilai_revisi($id) {
        $data['data'] = $this->dekanservice->get_nilai_revisi_dekan($id);

        $data['data_kelas'] = $this->dekanservice->get_data_kelas_revisi_dekan($id);

        echo json_encode($data);
    }
    
    public function revisi_nilai_all_mahasiswa(){
        $kelas = $this->input->POST('kelas');
        $saber = $this->dekanservice->get_dummy_update_kelas_kpat_all_status($kelas);
        foreach ($saber as $key => $value) {
            $saber[$key]->nilai_mhs = $this->dekanservice->get_nilai_revisi_all_kpat_dekan($kelas, $value->level);
        }
        $data['content'] = 'dosen/kaprodi/validasi-nilai/V_show_nilai_revisi';
        $data['judul'] = 'Nilai Mahasiswa';
        $data['kelas'] = $kelas;
        $data['all'] = $saber;
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['select'] = $kode_tahun_akademik;
        $data['a_validasi_nilai_kaprodi'] = 'active';
        $data['a_validasi_nilai_uas_prodi'] = 'active';
        $this->load->view('dosen/template/V_main', $data);
    }



}
