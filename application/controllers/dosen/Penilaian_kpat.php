<?php

class Penilaian_kpat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
        ));
        $this->load->service('DosenAkademikService');
    }

    public function presentasi_penilaian() {
        $data['content'] = 'dosen/penilaian_kpat/V_presentasi_penilaian';
        $data['judul'] = 'Presentasi Penilaian';
        $data['a_penilaian_kpat'] = 'active';
        $data['a_presentasi_penilaian_kpat'] = 'active';
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data['tahun_akademik'] = $ta;
        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
    }
    public function choose_presentasi_nilai() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $data = $this->dosenakademikservice->getChoosePresentasiNilaiKpat($kode_dosen, $kode_tahun_akademik);

        foreach ($data as $key => $value) {
            $tmp = $this->dosenakademikservice->getMengajarDosenKpat($value->kelas_id);
            $data[$key]->dosen_pengampu = $tmp;
        }
        $data['data'] = $data;
        $this->load->view('dosen/penilaian_kpat/V_choose_presentasi_nilai', $data);
    }
    public function isi_default($id) {
        $query = $this->dosenakademikservice->insertPersentasiNilaiKpat($id);

        if ($query) {
            redirect('dosen/penilaian_kpat/presentasi_penilaian');
        }
    }
    public function store_persentasi_penilaian() {
        $data_store = $this->input->post();
        $jml = $data_store['nilai_harian'] + $data_store['nilai_uts'] + $data_store['nilai_uas'];
        if ($jml != 100) {
            $this->session->set_flashdata('info', 'swal("Gagal!","Jumlah Gabungan Presentase Nilai Harus 100 %","error")');
            return redirect($_SERVER['HTTP_REFERER']);
        }
        $save = $this->dosenakademikservice->storePersentasiNilaiKpat($data_store);
        if ($save) {
            $this->session->set_flashdata('info', 'swal("Success!","Data berhasil di simpan","success")');
        } else {
            $this->session->set_flashdata('info', 'swal("Gagal!","Data gagal di simpan","error")');
        }
        return redirect($_SERVER['HTTP_REFERER']);
    }
    public function update_persentasi_penilaian($id) {
        $data_store = $this->input->post();
        $jml = $data_store['nilai_harian'] + $data_store['nilai_uts'] + $data_store['nilai_uas'];
        if ($jml != 100) {
            $this->session->set_flashdata('info', 'swal("Gagal!","Jumlah Gabungan Presentase Nilai Harus 100 %","error")');
            return redirect($_SERVER['HTTP_REFERER']);
        }
        $save = $this->dosenakademikservice->updatePersentasiNilaiKpat($data_store['kelas_id'], $data_store);
        if ($save) {
            $this->session->set_flashdata('info', 'swal("Success!","Data berhasil di ubah","success")');
        } else {
            $this->session->set_flashdata('info', 'swal("Gagal!","Data gagal di ubah","error")');
        }
        return redirect($_SERVER['HTTP_REFERER']);
    }
    // penilaian
    public function penilaian_revisi() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get_nilai_26();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpuKpat($kode_dosen, $tahun_akademik->kode_tahun_akademik);

        $data['content'] = 'dosen/penilaian_kpat/V_penilaian_revisi';
        $data['judul'] = 'Penilaian';
        $data['a_penilaian_kpat'] = 'active';
        $data['data'] = $data_kelas_ampu;
        $data['tahun_akademik'] = $ta;
        $data['setting'] = $this->dosenakademikservice->getSettingKuisioner();
        $data['chat_id'] = $this->dosenakademikservice->getChatIdDosen($kode_dosen);
        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
    }
    public function choose_revisi() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $data_kelas = $this->dosenakademikservice->getChooseRevisiKpat($kode_dosen, $kode_tahun_akademik);
        
        foreach ($data_kelas as $key => $value) {
            $data_cek = $this->dosenakademikservice->getDummyUpdateKelasKpatStatus($value->kelas_id);
            if ($data_cek > 0) {
                $data_kelas_valid = $this->dosenakademikservice->getDummyUpdateKelasKpatValid($value->kelas_id);
            }else{
                $data_kelas_valid = $this->dosenakademikservice->getDummyUpdateKelasKpatLast($value->kelas_id);
            }
            $data_kelas[$key]->validasi = $data_kelas_valid;
            $row_status = !empty($data_kelas_valid) ? $data_kelas_valid[0] : null;
            $data_kelas[$key]->status_dosen = $row_status->status_dosen ?? null;
            $data_kelas[$key]->status_prodi = $row_status->status_prodi ?? null;
            $data_kelas[$key]->status_dekan = $row_status->status_dekan ?? null;
        }

        $pesan_prodi = array();
        $pesan_dekan = array();
        foreach ($data_kelas as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi_uas', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi_uas', 'dekan');
            $pesan_prodi[$key] = count($tmp_prodi);
            $pesan_dekan[$key] = count($tmp_dekan);
        }
        $data['pesan_dekan'] = $pesan_dekan;
        $data['pesan_prodi'] = $pesan_prodi;
        $data['data'] =  $data_kelas;
        $data['kode_tahun_akademik'] =  $kode_tahun_akademik;
        $this->load->view('dosen/penilaian_kpat/V_choose_revisi', $data);
    }
    public function nilai_mahasiswa_uas_revisi($kelas_id, $ta = null) {
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $ta = ($ta) ? $ta : $tahun_akademik; 
        $semua_kelas = $this->dosenakademikservice->getDummyUpdateKelasKpatAll($kelas_id);
        if (!$semua_kelas) {
            $this->dosenakademikservice->insertDummyUpdateKelasKpat(array('id_kelas' => $kelas_id, 'level' => '1'));
            $semua_kelas = $this->dosenakademikservice->getDummyUpdateKelasKpatAll($kelas_id);
        }

        $cek_kelas = $this->dosenakademikservice->getDummyUpdateKelasKpatByLevel($kelas_id);
        if ($cek_kelas) {
            $data_kelas = $this->dosenakademikservice->getKelasKpatByIdWithDummy($cek_kelas->id_kelas, $cek_kelas->level);
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaKpatRevisi($cek_kelas->id_kelas, $cek_kelas->level, $ta);
        }else{
            $data_kelas = $this->dosenakademikservice->getKelasKpatByIdDesc($kelas_id);
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaKpatRevisiPrev($kelas_id, $data_kelas->level, $ta);
        }

        $sistem_nilai = $this->dosenakademikservice->getSistemPenilaian();
        
        foreach ($kelas_mahasiswa as $key => $val) {
            $tmp = $val->nilai_akhir;
            if ($val->nilai_akhir) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa[$key]->grade = ''; }
            }
        }

        $data['semua_kelas'] = $semua_kelas;
        $data['content'] = 'dosen/penilaian_kpat/V_nilai_revisi';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian_kpat'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['kelas_id'] = $kelas_id;
        $data['ta'] = $ta;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $data['exp'] = false;
        $data['homebase'] = $this->dosenakademikservice->getHomebaseDosen();
        $this->load->view('dosen/template/V_main', $data);
    }
    public function revisi_pebatalan_penilaian(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $this->dosenakademikservice->deleteDummyUpdateKelasKpat($kelas, $level);
        $this->dosenakademikservice->deleteDummyUpdateNilaiKpat($kelas, $level);
        echo json_encode(array('status' => 'success'));
    }
    function nilai_revisi() {
        $id = $this->input->POST('id');
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $persentase = $this->dosenakademikservice->getPersentaseFrom('persentasi_nilai_dosen_kpat', $kelas);
        if (!$persentase) {
            echo json_encode(array(
                    'status' => false,
                    'data' =>$new_nilai,
                )   
            );
        }else{
            $harian = $this->input->POST('harian');
            $uts = $this->input->POST('uts');
            $uas = $this->input->POST('uas');
            $status = $this->dosenakademikservice->checkDummyUpdateNilaiKpat($id, $level);
            if ($status) {
                $data = array(
                    'kelas_id' => $kelas,
                    'harian' => $harian,
                    'uts' => $uts,
                    'uas' => $uas,
                    'na' => ceil($harian*$persentase->nilai_harian/100 + $uts*$persentase->nilai_uts/100 + $uas*$persentase->nilai_uas/100),
                    'level' => $level,
                );
                $this->dosenakademikservice->updateDummyUpdateNilaiKpat($id, $level, $data);
            }else{
                $data = array(
                    'kelas_id' => $kelas,
                    'kode_khs_detail' => $id,
                    'harian' => $harian,
                    'uts' => $uts,
                    'uas' => $uas,
                    'na' => ceil($harian*$persentase->nilai_harian/100 + $uts*$persentase->nilai_uts/100 + $uas*$persentase->nilai_uas/100),
                    'level' => $level,
                );
                $this->dosenakademikservice->insertDummyUpdateNilaiKpat($data);
            }
            $new_nilai = $this->dosenakademikservice->getGradeNilaiKpatRevisi($id);
            $sistem_nilai = $this->dosenakademikservice->getSistemPenilaian();
            
            $tmp = $data['na'];
            $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                return $obj->nilai_minimum <= $tmp && $obj->nilai_maksimum >= $tmp;
            });
            if (!empty($result)) { $data['grade'] = reset($result)->grade; } else { $data['grade'] = ''; }

            echo json_encode(array(
                    'status' => true,
                    'data' =>$data,
                )   
            );
        }
        
    }
    public function revisi_nilai_mahasiswa($ta = null){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $ta = ($ta) ? $ta : $tahun_akademik; 
        $data['data'] = $this->dosenakademikservice->getRevisiNilaiMahasiswaKpat($kelas, $level, $ta);
        $this->load->view('dosen/penilaian/V_revisi_nilai_mahasiswa', $data);
    }
    public function revisi_dosen_selesai(){
        $kelas = $this->input->POST('kelas');
        $jum = $this->input->POST('jum');
        $level = $this->input->POST('level');
        $persentase = $this->dosenakademikservice->getPersentaseFrom('persentasi_nilai_dosen_kpat', $kelas);
        if (!$persentase) {
            echo json_encode(array('status' => false, 'msg' => 'Persentase tidak ditemukan'));
            return;
        }
        for ($i=0; $i <= $jum; $i++) { 
            $obj = $this->input->POST('data'.$i);
            $num = $this->dosenakademikservice->checkDummyUpdateNilaiKpat($obj['id'], $level);
            if (!$num) {
                $xxxx = $this->dosenakademikservice->getDummyUpdateNilaiKpatDataPrev($obj['id'], $level);
                $new_obj = array(
                    'kelas_id' => $kelas,
                    'kode_khs_detail' => $obj['id'],
                    'harian' => $obj['harian'],
                    'uts' => $obj['uts'],
                    'uas' => $obj['uas'],
                    'ket' => $xxxx->ket,
                    'na' => ceil($obj['harian']*$persentase->nilai_harian/100 + $obj['uts']*$persentase->nilai_uts/100 + $obj['uas']*$persentase->nilai_uas/100),
                    'level' => $level
                );
                $this->dosenakademikservice->insertDummyUpdateNilaiKpat($new_obj);
            }
        }
        $this->dosenakademikservice->updateDummyUpdateKelasKpat($kelas, $level);        
    }
    public function revisi_new_penilaian(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $new_data = array(
            'id_kelas' => $kelas,
            'level' => $level+1,
            'status' => '4',
        );
        $this->dosenakademikservice->insertDummyUpdateKelasKpat($new_data);
        echo json_encode(array('status' => 'success'));
    }
    public function revisi_ket(){
        $id = $this->input->POST('id');
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');

        $data = $this->dosenakademikservice->getDummyUpdateNilaiKpatKet($id, $kelas, $level);
        if (!$data) {
            $data = $this->dosenakademikservice->getDummyUpdateNilaiKpatPrev($id, $kelas, $level);
        }
        echo json_encode($data);
    }
    public function revisi_ket_val(){
        $id = $this->input->POST('id');
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $ket = $this->input->POST('ket');

        $data = $this->dosenakademikservice->getDummyUpdateNilaiKpatData($id, $level);

        if (!$data) {
            if ($level == 1) {
                $new_obj = array(
                    'kelas_id' => $kelas,
                    'kode_khs_detail' => $id,
                    'harian' => 0,
                    'uts' => 0,
                    'uas' => 0,
                    'ket' => $ket,
                    'na' => 0,
                    'level' => $level
                );
                $this->dosenakademikservice->insertDummyUpdateNilaiKpat($new_obj);
            }else{
                $datax = $this->dosenakademikservice->getDummyUpdateNilaiKpatDataPrev($id, $level);

                $new_obj = array(
                    'kelas_id' => $kelas,
                    'kode_khs_detail' => $id,
                    'harian' => $datax->harian,
                    'uts' => $datax->uts,
                    'uas' => $datax->uas,
                    'ket' => $ket,
                    'na' => $datax->harian,
                    'level' => $level
                );
                $this->dosenakademikservice->insertDummyUpdateNilaiKpat($new_obj);
            }
        }
        
        $result = $this->dosenakademikservice->updateDummyUpdateNilaiKpatKet($kelas, $level, $id, $ket);  

        if ($result) {
            echo json_encode(array('status' => true));
        }else{
            echo json_encode(array('status' => false));
        }
    }
    public function revisi_dosen_update($id){
        $this->dosenakademikservice->updateDosenKelasRevisi($id);
        redirect('dosen/penilaian/nilai_mahasiswa_uas_revisi/'.$id);
    }
   
    public function cetak_nilai_revisi_kelas($id,$level,$ta) {
        $data['query1'] = $this->dosenakademikservice->getRevisiDosenKelas($id);
        $data['query2'] = $this->dosenakademikservice->getRevisiQuery2($id, $level, $ta);
        $data['query3'] = $this->dosenakademikservice->getCetakQuery3($id);
        $data['query4'] = $this->dosenakademikservice->getCetakQuery4();
        $data['nama_dosen'] = $this->dosenakademikservice->getCetakNamaDosen($id);
        $data['persentase'] = $this->dosenakademikservice->getPersentaseFrom('persentasi_nilai_dosen', $id);
        
        $namafile = $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";
        $data['dosen'] = 'true';
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        // $this->load->view("admin/akademik/nilai/V_cetak_nilai_revisi", $data);
        $html = $this->load->view("admin/akademik/nilai/V_cetak_nilai_revisi", $data, true);
        $header = $this->load->view('admin/akademik/nilai/V_cetak_header_uts', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }
    public function pesan_kpat($kelas_id, $user1, $user2) {
        if ($user1 == 'dosen' && $user2 == 'prodi') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'catatan_revisi_kpat', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosenBatch($kelas_id, 'catatan_revisi_kpat');
        } elseif ($user2 == 'dosen' && $user1 == 'prodi') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'catatan_revisi_kpat', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiProdiBatch($kelas_id, 'catatan_revisi_kpat');
        } elseif ($user1 == 'dosen' && $user2 == 'dekan') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'catatan_revisi_kpat', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosenDekanBatch($kelas_id, 'catatan_revisi_kpat');
        } elseif ($user2 == 'dosen' && $user1 == 'dekan') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'catatan_revisi_kpat', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDekanBatch($kelas_id, 'catatan_revisi_kpat');
        }

        $dosen = $this->dosenakademikservice->getDosenPengampuFromKelas($kelas_id);
        $prodi = $this->dosenakademikservice->getKaprodiFromKelasKpat($kelas_id);
        $dekan = $this->dosenakademikservice->getDekanFromKelasKpat($kelas_id);
        $data = [
            'pesan' => $pesan,
            'param' => 'uas',
            'target' => $user2,
            'dosen' => $dosen->nama_dosen,
            'prodi' => $prodi->nama_dosen,
            'dekan' => $dekan->nama_dosen,
        ];

        if ($user1 == 'dosen') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_dosen_kpat", $data);
        } else if ($user1 == 'prodi') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_kaprodi_kpat", $data);
        } else if ($user1 == 'dekan') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_dekan_kpat", $data);
        }
    }
    public function pesan_all_kpat($kelas_id, $user1, $param, $user2) {
        if (isset($_POST['pesan']) && !empty($_POST['pesan'])) {
            if ($user1 == 'dosen') {
                if ($user2 == 'prodi') {
                    $data = array(
                        'kelas_id' => $kelas_id,
                        'kode_dosen' => 1,
                        'kode_prodi' => 1,
                        'param_dosen' => 1,
                        'pesan_dosen' => $_POST['pesan'],
                        'tgl_dosen' => $_POST['tgl']
                    );
                } else {
                    $data = array(
                        'kelas_id' => $kelas_id,
                        'kode_dosen' => 1,
                        'kode_dekan' => 1,
                        'param_dosen' => 1,
                        'pesan_dosen' => $_POST['pesan'],
                        'tgl_dosen' => $_POST['tgl']
                    );
                }
            } else if ($user1 == 'koprodi') {
                $data = array(
                    'kelas_id' => $kelas_id,
                    'kode_dosen' => 1,
                    'kode_prodi' => 1,
                    'param_prodi' => 1,
                    'pesan_prodi' => $_POST['pesan'],
                    'tgl_prodi' => $_POST['tgl']
                );
            } else if ($user1 == 'dekan') {
                $data = array(
                    'kelas_id' => $kelas_id,
                    'kode_dosen' => 1,
                    'kode_dekan' => 1,
                    'param_dekan' => 1,
                    'pesan_dekan' => $_POST['pesan'],
                    'tgl_prodi' => $_POST['tgl']
                );
            }
            $resutl = $this->dosenakademikservice->insertCatatanRevisi('catatan_revisi_kpat', $data);
       
            if ($resutl) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }
}