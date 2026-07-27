<?php

class Penilaian extends CI_Controller {

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
        $data['content'] = 'dosen/penilaian/V_presentasi_penilaian';
        $data['judul'] = 'Presentasi Penilaian';
        $data['a_penilaian'] = 'active';
        $data['a_presentasi_penilaian'] = 'active';

        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpu($kode_dosen, $tahun_akademik->kode_tahun_akademik);

        $data['data'] = $data_kelas_ampu;
        $data['tahun_akademik'] = $ta;
        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;

        $data['setting'] = $this->dosenakademikservice->getSettingKuisioner();
        $data['chat_id'] = $this->dosenakademikservice->getChatIdDosen($kode_dosen);

        $this->load->view('dosen/template/V_main', $data);
    }

    public function isi_default($id) {
        $query = $this->dosenakademikservice->insertPersentasiNilaiDefault($id);

        if ($query) {
            redirect('dosen/penilaian/presentasi_penilaian');
        }
    }

    public function penilaian_uts() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpuResult($kode_dosen, $tahun_akademik->kode_tahun_akademik);

        $data['content'] = 'dosen/penilaian/V_penilaian_uts';
        $data['judul'] = 'Penilaian UTS';
        $data['a_penilaian'] = 'active';
        $data['a_penilaian_uts'] = 'active';
        $data['data'] = $data_kelas_ampu;
        $data['tahun_akademik'] = $ta;
        $data['setting'] = $this->dosenakademikservice->getSettingKuisioner();
        $data['chat_id'] = $this->dosenakademikservice->getChatIdDosen($kode_dosen);

        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
        
    }

    public function penilaian_harian_uas() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpuResult($kode_dosen, $tahun_akademik->kode_tahun_akademik);

        $data['content'] = 'dosen/penilaian/V_penilaian_harian_uas';
        $data['judul'] = 'Penilaian';
        $data['a_penilaian'] = 'active';
        $data['data'] = $data_kelas_ampu;
        $data['tahun_akademik'] = $ta;
        $data['setting'] = $this->dosenakademikservice->getSettingKuisioner();
        $data['chat_id'] = $this->dosenakademikservice->getChatIdDosen($kode_dosen);

        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
    }

    public function penilaian_kuisioner() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpuResult($kode_dosen, $tahun_akademik->kode_tahun_akademik);

        $data['content'] = 'dosen/penilaian/V_penilaian_kuisioner';
        $data['judul'] = 'Hasil Kuisioner PBM (Proses Belajar Mengajar)';
        $data['a_penilaian'] = 'active';
        $data['data'] = $data_kelas_ampu;
        $data['tahun_akademik'] = $ta;
        $data['setting'] = $this->dosenakademikservice->getSettingKuisioner();
        $data['chat_id'] = $this->dosenakademikservice->getChatIdDosen($kode_dosen);

        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;

        $this->load->view('dosen/template/V_main', $data);
    }

    public function nilai_mahasiswa_uts($kelas_id) {
        $data_kelas = $this->dosenakademikservice->getKelasById($kelas_id);

        $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUts($kelas_id);

        if (($data_kelas->param_uts == '1')) {
            $data['content'] = 'dosen/penilaian/V_input_penilaian_uts';
        } else {
            if ($data_kelas->status_nilai_uts != 'T') {
                $data['content'] = 'dosen/penilaian/V_input_penilaian_uts';
            } else {
                $data['content'] = 'dosen/penilaian/V_nilai_uts';
            }
        }


        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['exp'] = false;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));

        $this->load->view('dosen/template/V_main', $data);
    }

    public function nilai_mahasiswa_uts_exp($kelas_id, $kode_tahun_akademik) {
        $data_kelas = $this->dosenakademikservice->getKelasById($kelas_id);

        $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUts($kelas_id);
        // } else {
        //     $kelas_mahasiswa = $this->db->select('grade, dummy_id,khd.kode_khs_detail, khd.kode_krs_detail, mah.nim, nama_mahasiswa, dummy_harian, dummy_uts, dummy_uas, dummy_na')
        //                     ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
        //                     ->join('kelas', 'kelas.kelas_id=km.kelas_id')
        //                     ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
        //                     ->join('krs', 'krs.kode_krs=kd.kode_krs')
        //                     ->join('mahasiswa as mah', 'mah.nim=krs.nim')
        //                     ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
        //                     ->join('dummy_nilai as dumm', 'dumm.kode_khs_detail=khd.kode_khs_detail', 'left')
        //                     ->where('spd.kode_sistem_penilaian', 1)
        //                     ->where('dummy_na >= spd.nilai_minimum AND dummy_na <= spd.nilai_maksimum')
        //                     ->where('km.kelas_id', $kelas_id)
        //                     ->get()->result();
        // }
        if ($data_kelas->status_nilai_uts != 'T') {
            $data['content'] = 'dosen/penilaian/V_input_penilaian_uts';
        } else {
            $data['content'] = 'dosen/penilaian/V_nilai_uts';
        }
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;

        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $data['exp'] = true;
        $this->load->view('dosen/template/V_main', $data);
    }

    public function nilai_mahasiswa_uas($kelas_id) {
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $data_kelas = $this->dosenakademikservice->getKelasById($kelas_id);

        if ($data_kelas->status_nilai != 'T') {
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUas($kelas_id);
        } else {
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUasWithGrade($kelas_id);
        }
        if ($data_kelas->status_nilai != 'T') {
            $data['content'] = 'dosen/penilaian/V_input_penilaian_uas';
        } else {
            $data['content'] = 'dosen/penilaian/V_nilai_uas';
        }
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $data['exp'] = false;
        $data['homebase'] = $this->dosenakademikservice->getHomebaseDosen();
        $this->load->view('dosen/template/V_main', $data);
    }

    public function nilai_mahasiswa_uas_exp($kelas_id) {
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $data_kelas = $this->dosenakademikservice->getKelasById($kelas_id);

        if ($data_kelas->status_nilai != 'T') {
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUas($kelas_id);
        } else {
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUasWithGrade($kelas_id);
        }
        if ($data_kelas->status_nilai != 'T') {
            $data['content'] = 'dosen/penilaian/V_input_penilaian_uas';
        } else {
            $data['content'] = 'dosen/penilaian/V_nilai_uas';
        }
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $data['exp'] = true;
        // return 
        // $this->load->view('dosen/template/V_main', $data);
    }

    public function nilai($kode_khs_detail, $kode_krs_detail) {
        $nilai_akhir = $this->input->post('nilai_akhir');
        $data_add = array(
            'dummy_id' => $kode_khs_detail,
            'kode_khs_detail' => $kode_khs_detail,
            'kode_krs_detail' => $kode_krs_detail,
        );
        if ($nilai_akhir == "") {
            $data_add['dummy_na'] = null;
            $data_update['dummy_na'] = null;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        } else {
            $data_add['dummy_na'] = $nilai_akhir;
            $data_update['dummy_na'] = $nilai_akhir;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        }
        if ($ubah) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function harian($kode_khs_detail, $kode_krs_detail, $kelas) {
        $nilai_harian = $this->input->post('nilai_harian');
        $data_add = array(
            'dummy_id' => $kode_khs_detail,
            'kode_khs_detail' => $kode_khs_detail,
            'kode_krs_detail' => $kode_krs_detail,
        );
        if ($nilai_harian == "") {
            $data_add['dummy_harian'] = null;
            $data_update['dummy_harian'] = null;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        } else {
            $data_add['dummy_harian'] = $nilai_harian;
            $data_update['dummy_harian'] = $nilai_harian;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        }

        $persentasi_nilai = $this->dosenakademikservice->getPersentasiNilai($kelas);
        $nil = $this->dosenakademikservice->getDummyNilaiByKhs($kode_khs_detail);
        $nilai_akhir = ($nil->dummy_harian * $persentasi_nilai->nilai_harian / 100) + ($nil->dummy_uts * $persentasi_nilai->nilai_uts / 100) + ($nil->dummy_uas * $persentasi_nilai->nilai_uas / 100);
        $this->dosenakademikservice->updateDummyNilaiNa($kode_khs_detail, $nilai_akhir);
        if ($ubah) {
            $res['status'] = 'true';
            $res['na'] = $nilai_akhir;
        } else {
            $res['status'] = 'false';
            $res['na'] = $nilai_akhir;
        }

        echo json_encode($res);
    }

    public function uts($kode_khs_detail, $kode_krs_detail) {
        $nilai_uts = $this->input->post('nilai_uts');
        $data_add = array(
            'dummy_id' => $kode_khs_detail,
            'kode_khs_detail' => $kode_khs_detail,
            'kode_krs_detail' => $kode_krs_detail,
        );
        if ($nilai_uts == "") {
            $data_add['dummy_uts'] = null;
            $data_update['dummy_uts'] = null;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        } else {
            $data_add['dummy_uts'] = $nilai_uts;
            $data_update['dummy_uts'] = $nilai_uts;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        }
        $persentasi_nilai = $this->dosenakademikservice->getPersentasiNilai($this->session->userdata('sess_kelas_id'));
        $nil = $this->dosenakademikservice->getDummyNilaiByKhs($kode_khs_detail);
        $nilai_akhir = ($nil->dummy_harian * $persentasi_nilai->nilai_harian / 100) + ($nil->dummy_uts * $persentasi_nilai->nilai_uts / 100) + ($nil->dummy_uas * $persentasi_nilai->nilai_uas / 100);
        $this->dosenakademikservice->updateDummyNilaiNa($kode_khs_detail, $nilai_akhir);
        if ($ubah) {
            $res['status'] = 'true';
            $res['na'] = $nilai_akhir;
        } else {
            $res['status'] = 'false';
            $res['na'] = $nilai_akhir;
        }

        echo json_encode($res);
    }

    public function uas($kode_khs_detail, $kode_krs_detail, $kelas) {
        $nilai_uas = $this->input->post('nilai_uas');
        $data_add = array(
            'dummy_id' => $kode_khs_detail,
            'kode_khs_detail' => $kode_khs_detail,
            'kode_krs_detail' => $kode_krs_detail,
        );
        if ($nilai_uas == "") {
            $data_add['dummy_uas'] = null;
            $data_update['dummy_uas'] = null;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        } else {
            $data_add['dummy_uas'] = $nilai_uas;
            $data_update['dummy_uas'] = $nilai_uas;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        }
        $persentasi_nilai = $this->dosenakademikservice->getPersentasiNilai($kelas);
        $nil = $this->dosenakademikservice->getDummyNilaiByKhs($kode_khs_detail);
        $nilai_akhir = ($nil->dummy_harian * $persentasi_nilai->nilai_harian / 100) + ($nil->dummy_uts * $persentasi_nilai->nilai_uts / 100) + ($nil->dummy_uas * $persentasi_nilai->nilai_uas / 100);
        $this->dosenakademikservice->updateDummyNilaiNa($kode_khs_detail, $nilai_akhir);
        if ($ubah) {
            $res['status'] = 'true';
            $res['na'] = $nilai_akhir;
        } else {
            $res['status'] = 'false';
            $res['na'] = $nilai_akhir;
        }

        echo json_encode($res);
    }

    public function kuisioner() {
        if ($this->input->post('kode_tahun_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');

        $kelas_kuisioner = $this->dosenakademikservice->getKuisionerKelas($kode_dosen, $kode_tahun_akademik);

        if ($kelas_kuisioner) {

            $i = 0;
            foreach ($kelas_kuisioner as $item) {
                $sub = $this->dosenakademikservice->getKuisionerSub($kode_dosen, $item->kelas_id);

                $kuisioner['isi'][$i] = $this->dosenakademikservice->getKuisionerRata($sub);
                $kuisioner['mhs'][$i] = $item->jum_mhs;
                $i++;
            }
        } else {
            $kuisioner = [];
        }
        $data['kuisioner'] = $kuisioner;
        $this->load->view('dosen/penilaian/V_kuisioner', $data);
    }

    public function choose_presentasi_nilai() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }

        $kode_dosen = $this->session->userdata('kode_dosen');

        $data = $this->dosenakademikservice->getChoosePresentasiNilai($kode_dosen, $kode_tahun_akademik);

        foreach ($data as $key => $value) {
            $tmp = $this->dosenakademikservice->getMengajarDosen($value->kelas_id);
            $data[$key]->dosen_pengampu = $tmp;
        }
        $data['data'] = $data;
        $this->load->view('dosen/penilaian/V_choose_presentasi_nilai', $data);
    }

    public function choose_nilai() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $data['data'] = $this->dosenakademikservice->getChooseNilaiUts($kode_dosen, $kode_tahun_akademik);
        $this->load->view('dosen/penilaian/V_choose_nilai', $data);
    }

    public function choose_nilai_uts() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $time = $this->dosenakademikservice->getAktivasi($kode_tahun_akademik);
        $kode_dosen = $this->session->userdata('kode_dosen');
        
        $data['data'] = $this->dosenakademikservice->getChooseNilaiUts($kode_dosen, $kode_tahun_akademik);
        $data1['data'] = $this->dosenakademikservice->getChooseNilaiUts($kode_dosen, $kode_tahun_akademik);

        $pesan_prodi = array();
        $pesan_dekan = array();
        foreach ($data['data'] as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi', 'dekan');
            $pesan_prodi[$key] = count($tmp_prodi);
            $pesan_dekan[$key] = count($tmp_dekan);
        }

        foreach ($data1['data'] as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi', 'dekan');
            $pesan_prodi[$key] = count($tmp_prodi);
            $pesan_dekan[$key] = count($tmp_dekan);
        }

        $data['pesan_dekan'] = $pesan_dekan;
        $data['pesan_prodi'] = $pesan_prodi;

        $data1['pesan_dekan'] = $pesan_dekan;
        $data1['pesan_prodi'] = $pesan_prodi;

        $data['time'] = $time;
        $data1['time'] = $time;
        if ($time->param_uts == "0") {
            $this->load->view('dosen/penilaian/V_failed_uts', $data);
        } else {
            if (strtotime($time->tgl_awal_uts) <= strtotime(date('Y-m-d H:s:i')) && strtotime($time->tgl_akhir_uts) >= strtotime(date('Y-m-d H:s:i'))) {
                $this->load->view('dosen/penilaian/V_choose_nilai_uts', $data);
            } else {
                $data_update = array(
                    'param_uts' => "1",
                );

                $this->dosenakademikservice->updateKelasParamUts($kode_tahun_akademik);

                if (date('Y-m-d H:s:i') >= date('Y-m-d H:s:i', strtotime('+2 days', strtotime($time->tgl_akhir_uts)))) {
                    $data_update1 = array(
                        'param_uts' => "1",
                        'status_revisi_uts' => "",
                    );

                    $this->dosenakademikservice->updateKelasParamUtsRevisi($kode_tahun_akademik);
                }
                // echo json_encode($data1);
                $this->load->view('dosen/penilaian/V_choose_nilai_uts_validated', $data1);
            }
        }
    }

    public function cetak_validasi_uts($id) {
        $data['query1'] = $this->dosenakademikservice->getCetakQuery1($id);
        $data['query2'] = $this->dosenakademikservice->getCetakQuery2($id);
        $data['query3'] = $this->dosenakademikservice->getCetakQuery3($id);
        $data['query4'] = $this->dosenakademikservice->getCetakQuery4();
        $data['nama_dosen'] = $this->dosenakademikservice->getCetakNamaDosen($id);
        
        $namafile = $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $html = $this->load->view("admin/akademik/nilai/V_cetak_nilai_uts", $data, true);
        $header = $this->load->view('admin/akademik/nilai/V_cetak_header_uts', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }

    public function cetak_validasi_uas($id, $ta =  null) {
        $data['query1'] = $this->dosenakademikservice->getCetakQuery1($id);
        $data['query2'] = $this->dosenakademikservice->getCetakQuery2WithBlock($id, $ta);
        $data['query3'] = $this->dosenakademikservice->getCetakQuery3($id);
        $data['query4'] = $this->dosenakademikservice->getCetakQuery4();
        $data['nama_dosen'] = $this->dosenakademikservice->getCetakNamaDosen($id);
        
        $namafile = $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        // $this->load->view("admin/akademik/nilai/V_cetak_nilai_uas", $data);
        $html = $this->load->view("admin/akademik/nilai/V_cetak_nilai_uas", $data, true);
        $header = $this->load->view('admin/akademik/nilai/V_cetak_header_uts', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }
    
    public function cetak_validasi_not_valid_uts($id) {
        $data['query1'] = $this->dosenakademikservice->getCetakQuery1($id);
        $data['query2'] = $this->dosenakademikservice->getCetakQuery2Dummy($id);
        $data['query3'] = $this->dosenakademikservice->getCetakQuery3($id);
        $data['query4'] = $this->dosenakademikservice->getCetakQuery3Kaprodi($id);
        $data['nama_dosen'] = $this->dosenakademikservice->getCetakNamaDosen($id);

        // $data['data'] = base_url() . 'verifikasi/nilai/' . hash("sha256", $id);
        // $data['level'] = 'C';
        // $data['size'] = 2;
        // $data['savename'] = FCPATH . 'tes.png';
        // $this->ciqrcode->generate($data);

        $namafile = "Validasi Nilai - ". $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $html = $this->load->view("dosen/penilaian/V_cetak_validasi", $data, true);
        $header = $this->load->view('dosen/penilaian/V_cetak_validasi_header', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");

    }
    public function cetak_validasi_not_valid_uas($id) {
        $data['query1'] = $this->dosenakademikservice->getCetakQuery1($id);
        $data['query2'] = $this->dosenakademikservice->getCetakQuery2DummyUas($id);
        $data['query3'] = $this->dosenakademikservice->getCetakQuery3($id);
        $data['query4'] = $this->dosenakademikservice->getCetakQuery3Kaprodi($id);
        $data['nama_dosen'] = $this->dosenakademikservice->getCetakNamaDosen($id);

        // $data['data'] = base_url() . 'verifikasi/nilai/' . hash("sha256", $id);
        // $data['level'] = 'C';
        // $data['size'] = 2;
        // $data['savename'] = FCPATH . 'tes.png';
        // $this->ciqrcode->generate($data);

        $namafile = "Validasi Nilai - ". $data['query1']->mtkm . " - " . $data['query1']->nama_matakuliah . " - Kelas " . $data['query1']->nama_kelas . ".pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $html = $this->load->view("dosen/penilaian/V_cetak_validasi_uas", $data, true);
        $header = $this->load->view('dosen/penilaian/V_cetak_validasi_header', $data, TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");

    }

    public function choose_nilai_uas() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $time = $this->dosenakademikservice->getAktivasi($kode_tahun_akademik);

        $kode_dosen = $this->session->userdata('kode_dosen');

        $data['data'] = $this->dosenakademikservice->getChooseNilaiUas($kode_dosen, $kode_tahun_akademik);
        $data1['data'] = $this->dosenakademikservice->getChooseNilaiUasWithProdi($kode_dosen, $kode_tahun_akademik);
        
        $pesan_prodi = array();
        $pesan_dekan = array();
        foreach ($data['data'] as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi_uas', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi_uas', 'dekan');
            $pesan_prodi[$key] = count($tmp_prodi);
            $pesan_dekan[$key] = count($tmp_dekan);
        }
        foreach ($data1['data'] as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi_uas', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'catatan_revisi_uas', 'dekan');
            $pesan_prodi[$key] = count($tmp_prodi);
            $pesan_dekan[$key] = count($tmp_dekan);
        }
        $data1['pesan_dekan'] = $pesan_dekan;
        $data1['pesan_prodi'] = $pesan_prodi;

        $data['pesan_dekan'] = $pesan_dekan;
        $data['pesan_prodi'] = $pesan_prodi;

        $data1['pesan_dekan'] = $pesan_dekan;
        $data1['pesan_prodi'] = $pesan_prodi;

        $data['time'] = $time;
        $data1['time'] = $time;

        if ($time->param_uas == "0") {
            $this->load->view('dosen/penilaian/V_failed_uas', $data);
        } else {
            if (strtotime($time->tgl_awal_uas) <= strtotime(date('Y-m-d H:s:i')) && strtotime($time->tgl_akhir_uas) >= strtotime(date('Y-m-d H:s:i'))) {
                $this->load->view('dosen/penilaian/V_choose_nilai_uas', $data);
            } else {
                $data_update = array(
                    'param_uas' => "1",
                );

                $this->dosenakademikservice->updateKelasParamUas($kode_tahun_akademik);

                if (date('Y-m-d H:s:i') >= date('Y-m-d H:s:i', strtotime('+2 days', strtotime($time->tgl_akhir_uas)))) {
                    $data_update1 = array(
                        'param_uas' => "1",
                        'status_revisi_uas' => "",
                    );
                    $this->dosenakademikservice->updateKelasParamUasRevisi($kode_tahun_akademik);
                }
                $this->load->view('dosen/penilaian/V_choose_nilai_uas_validated', $data1);
            }
        }
    }

    // show comment lama
    public function show_comment($kelas_id, $jenis) {
        if ($jenis == 'kuisioner') {
            $komentar = $this->dosenakademikservice->getKomentarKuisioner($kelas_id);
        } else {
            $komentar = $this->dosenakademikservice->getKomentarCatatan($kelas_id);
        }
        return $this->load->view("dosen/penilaian/V_comment", array('komentar' => $komentar, 'jenis' => $jenis));
    }

    // pesan uts
    public function pesan_uts($kelas_id, $user1, $user2) {
        if ($user1 == 'dosen' && $user2 == 'prodi') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosen($kelas_id, 'catatan_revisi');
        } elseif ($user2 == 'dosen' && $user1 == 'prodi') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiProdi($kelas_id, 'catatan_revisi');
        } elseif ($user1 == 'dosen' && $user2 == 'dekan') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosenDekan($kelas_id, 'catatan_revisi');
        } elseif ($user2 == 'dosen' && $user1 == 'dekan') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDekan($kelas_id, 'catatan_revisi');
        }

        $dosen = $this->dosenakademikservice->getDosenPengampu($kelas_id);
        $prodi = $this->dosenakademikservice->getKaprodiFromKelas($kelas_id);
        $dekan = $this->dosenakademikservice->getDekanFromKelas($kelas_id);
        $data = [
            'pesan' => $pesan,
            'param' => 'uts',
            'target' => $user2,
            'dosen' => $dosen->nama_dosen,
            'prodi' => $prodi->nama_dosen,
            'dekan' => $dekan->nama_dosen,
        ];

        if ($user1 == 'dosen') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_dosen", $data);
        } else if ($user1 == 'prodi') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_kaprodi", $data);
        } else if ($user1 == 'dekan') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_dekan", $data);
        }
    }

    // pesan uas dan harian
    public function pesan_uas($kelas_id, $user1, $user2) {
        if ($user1 == 'dosen' && $user2 == 'prodi') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosen($kelas_id, 'catatan_revisi_uas');
        } elseif ($user2 == 'dosen' && $user1 == 'prodi') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiProdi($kelas_id, 'catatan_revisi_uas');
        } elseif ($user1 == 'dosen' && $user2 == 'dekan') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosenDekan($kelas_id, 'catatan_revisi_uas');
        } elseif ($user2 == 'dosen' && $user1 == 'dekan') {
            $pesan = $this->dosenakademikservice->getCatatanRevisi($kelas_id, $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDekan($kelas_id, 'catatan_revisi_uas');
        }

        $dosen = $this->dosenakademikservice->getDosenPengampu($kelas_id);
        $prodi = $this->dosenakademikservice->getKaprodiFromKelas($kelas_id);
        $dekan = $this->dosenakademikservice->getDekanFromKelas($kelas_id);
        $data = [
            'pesan' => $pesan,
            'param' => 'uas',
            'target' => $user2,
            'dosen' => $dosen->nama_dosen,
            'prodi' => $prodi->nama_dosen,
            'dekan' => $dekan->nama_dosen,
        ];

        if ($user1 == 'dosen') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_dosen", $data);
        } else if ($user1 == 'prodi') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_kaprodi", $data);
        } else if ($user1 == 'dekan') {
            return $this->load->view("dosen/penilaian/V_catatan_revisi_dekan", $data);
        }
    }

    public function pesan_all($kelas_id, $user1, $param, $user2) {
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

            if ($param == 'uts') {
                $resutl = $this->dosenakademikservice->insertCatatanRevisi('catatan_revisi', $data);
            } else if ($param == 'uas') {
                $resutl = $this->dosenakademikservice->insertCatatanRevisi('catatan_revisi_uas', $data);
            }
            if ($resutl) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }

    // komentar khusus untuk history penilaian
    public function show_history_comment_uts($kelas_id) {
        $history = $this->dosenakademikservice->getHistoryComment($kelas_id);
        $detail = $history['detail'];
        $dosen = $history['dosen'];
        $komentar = $history['komentar'];
//        dDebug($dosen);
        $data = [
            'komentar' => $komentar,
            'detail' => $detail,
            'dosen' => $dosen
        ];
        return $this->load->view("dosen/penilaian/V_history_comment_uts", $data);
    }

    public function show_history_comment($kelas_id) {
        $history = $this->dosenakademikservice->getHistoryComment($kelas_id);
        $detail = $history['detail'];
        $dosen = $history['dosen'];
        $komentar = $history['komentar'];
//        dDebug($dosen);
        $data = [
            'komentar' => $komentar,
            'detail' => $detail,
            'dosen' => $dosen
        ];
        return $this->load->view("dosen/penilaian/V_history_comment", $data);
        // echo json_encode($komentar);
    }

    public function addOrUpdate($kode_khs_detail, array $data_add, array $data_update) {
        return $this->dosenakademikservice->addOrUpdateDummyNilai($kode_khs_detail, $data_add, $data_update);
    }

    public function exis($kode_khs_detail) {
        return $this->dosenakademikservice->exisDummyNilai($kode_khs_detail);
    }

    // pengiriman nilai yang sudah diisi atau diperbaiki.
    public function selesai($kelas_id, $validasi_prodi = null) {

        $query_dosen_kelas = $this->dosenakademikservice->getQueryDosenKelas($kelas_id);
        $query_prodi = $this->dosenakademikservice->getQueryProdi($kelas_id);
        $query_fakultas = $this->dosenakademikservice->getQueryFakultas($kelas_id);

        try {
            $this->dosenakademikservice->transBegin();
            $this->dosenakademikservice->updateKelasStatusNilai($kelas_id);
            if ($validasi_prodi == 'T') {
                $this->dosenakademikservice->insertKelasValidasi(['kelas_id' => $kelas_id, 'isian' => 'T', 'validasi_prodi' => 'T']);

                $message_text1 = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai direvisi oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi ulang dari " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
            } else {
                $this->dosenakademikservice->insertKelasValidasi(['kelas_id' => $kelas_id, 'isian' => 'T']);
                if ($validasi_prodi == "F") {
                    $message_text2 = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai diinputkan oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
                    kirim_ke_telegram($query_prodi['chatid'], $message_text2);
                } else {
                    $message_text3 = "*SISKA UBG* - Nilai akhir matakuliah " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai direvisi oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi ulang dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
                    kirim_ke_telegram($query_prodi['chatid'], $message_text3);
                }
            }
            $this->dosenakademikservice->transCommit();
        } catch (PDOException $exception) {
            $this->dosenakademikservice->transRollback();
        }

        return redirect(site_url('dosen/penilaian/nilai_mahasiswa_uas/' . $kelas_id));
    }

    // pengiriman nilai yang sudah diisi atau diperbaiki.
    public function selesai_uts($kelas_id, $validasi_prodi = null) {
        $query_dosen_kelas = $this->dosenakademikservice->getQueryDosenKelas($kelas_id);
        $query_prodi = $this->dosenakademikservice->getQueryProdi($kelas_id);
        $query_fakultas = $this->dosenakademikservice->getQueryFakultas($kelas_id);

        $kelas_mahasiswa_1 = $this->dosenakademikservice->getNilaiMahasiswaUts($kelas_id);
        $kelas_mahasiswa_2 = $this->dosenakademikservice->getNilaiMahasiswaUasWithGrade($kelas_id);

        if (count($kelas_mahasiswa_1) != count($kelas_mahasiswa_2)) {
            $this->dosenakademikservice->checkAndInsertDummyNilai($kelas_mahasiswa_1, 'dummy_nilai', 'dummy_uts');
        }
        try {
            $this->dosenakademikservice->transBegin();
            $this->dosenakademikservice->updateKelasStatusNilaiUts($kelas_id);
            if ($validasi_prodi == 'T') {
                $this->dosenakademikservice->insertKelasValidasi(['kelas_id' => $kelas_id, 'isian_uts' => 'T', 'validasi_prodi_uts' => 'T']);

                $message_text1 = "*SISKA UBG* - Nilai UTS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai direvisi oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi ulang dari " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
            } else {
                $this->dosenakademikservice->insertKelasValidasi(['kelas_id' => $kelas_id, 'isian_uts' => 'T']);
                if ($validasi_prodi == "F") {
                    $message_text2 = "*SISKA UBG* - Nilai UTS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai diinputkan oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
                    kirim_ke_telegram($query_prodi['chatid'], $message_text2);
                } else {
                    $message_text3 = "*SISKA UBG* - Nilai UTS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai direvisi oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi ulang dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
                    kirim_ke_telegram($query_prodi['chatid'], $message_text3);
                }
            }
            $this->dosenakademikservice->transCommit();
        } catch (PDOException $exception) {
            $this->dosenakademikservice->transRollback();
        }
        return redirect(site_url('dosen/penilaian/nilai_mahasiswa_uts/' . $kelas_id));
    }

    public function selesai_uas($kelas_id, $validasi_prodi = null) {
        $query_dosen_kelas = $this->dosenakademikservice->getQueryDosenKelas($kelas_id);
        $query_prodi = $this->dosenakademikservice->getQueryProdi($kelas_id);
        $query_fakultas = $this->dosenakademikservice->getQueryFakultas($kelas_id);

        $kelas_mahasiswa_1 = $this->dosenakademikservice->getNilaiMahasiswaUts($kelas_id);
        $kelas_mahasiswa_2 = $this->dosenakademikservice->getNilaiMahasiswaUasWithGrade($kelas_id);

        if (count($kelas_mahasiswa_1) != count($kelas_mahasiswa_2)) {
            $this->dosenakademikservice->checkAndInsertDummyNilai($kelas_mahasiswa_1, 'dummy_nilai', 'dummy_uas');
        }
        try {
            $this->dosenakademikservice->transBegin();
            $this->dosenakademikservice->updateKelasStatusNilai($kelas_id);
            if ($validasi_prodi == 'T') {
                $this->dosenakademikservice->insertKelasValidasi(['kelas_id' => $kelas_id, 'isian' => 'T', 'validasi_prodi' => 'T']);

                $message_text1 = "*SISKA UBG* - Nilai UAS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai direvisi oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi ulang dari " . $query_fakultas['nama_dosen'] . " selaku Dekan " . $query_fakultas['nama_fakultas'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
            } else {
                $this->dosenakademikservice->insertKelasValidasi(['kelas_id' => $kelas_id, 'isian' => 'T']);
                if ($validasi_prodi == "F") {
                    $message_text2 = "*SISKA UBG* - Nilai UAS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai diinputkan oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
                    kirim_ke_telegram($query_prodi['chatid'], $message_text2);
                } else {
                    $message_text3 = "*SISKA UBG* - Nilai UAS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai direvisi oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi ulang dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
                    kirim_ke_telegram($query_prodi['chatid'], $message_text3);
                }
            }
            $this->dosenakademikservice->transCommit();
        } catch (PDOException $exception) {
            $this->dosenakademikservice->transRollback();
        }
        return redirect(site_url('dosen/penilaian/nilai_mahasiswa_uas/' . $kelas_id));
    }

    public function store_persentasi_penilaian() {
        $data_store = $this->input->post();
        $jml = $data_store['nilai_harian'] + $data_store['nilai_uts'] + $data_store['nilai_uas'];
        if ($jml != 100) {
            $this->session->set_flashdata('info', 'swal("Gagal!","Jumlah Gabungan Presentase Nilai Harus 100 %","error")');
            return redirect($_SERVER['HTTP_REFERER']);
        }
        $save = $this->dosenakademikservice->storePersentasiNilai($data_store);
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
        $save = $this->dosenakademikservice->updatePersentasiNilai($data_store['kelas_id'], $data_store);
        if ($save) {
            $this->session->set_flashdata('info', 'swal("Success!","Data berhasil di ubah","success")');
        } else {
            $this->session->set_flashdata('info', 'swal("Gagal!","Data gagal di ubah","error")');
        }
        return redirect($_SERVER['HTTP_REFERER']);
    }

    // ===================== PENILAIAN FINAL =====================
    public function penilaian_final() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data['content'] = 'dosen/penilaian/V_penilaian_final';
        $data['judul'] = 'Penilaian Final';
        $data['a_penilaian'] = 'active';
        $data['tahun_akademik'] = $ta;
        $data['select'] = $tahun_akademik->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
    }

    public function choose_final() {
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $data_kelas = $this->dosenakademikservice->getChooseFinal($kode_dosen, $kode_tahun_akademik);
        foreach ($data_kelas as $key => $row) {
            if ($row->cek) {
                $data_kelas[$key]->final_route = 'revisi_final';
            } elseif ($row->param_uts == '1') {
                $data_kelas[$key]->final_route = 'uts_final';
            } else {
                $data_kelas[$key]->final_route = 'uas_final';
            }
        }
        $data['data'] = $data_kelas;
        $this->load->view('dosen/penilaian/V_choose_final', $data);
    }

    // revisiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
    public function penilaian_revisi() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpuWithUpdate($kode_dosen, $tahun_akademik->kode_tahun_akademik);
        foreach ($data_kelas_ampu as $value) {
            if (!$value->cek) {
                $this->dosenakademikservice->insertDummyUpdateKelas(array('id_kelas'=>$value->kelas_id,'level'=>'1','status'=>'4'));
            }
        }

        $data['content'] = 'dosen/penilaian/V_penilaian_revisi';
        $data['judul'] = 'Penilaian';
        $data['a_penilaian'] = 'active';
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
        $data_kelas = $this->dosenakademikservice->getChooseRevisi($kode_dosen, $kode_tahun_akademik);
        
        foreach ($data_kelas as $key => $value) {
            $data_cek = $this->dosenakademikservice->getDummyUpdateKelasStatus($value->kelas_id);
            if ($data_cek > 0) {
                $data_kelas_valid = $this->dosenakademikservice->getDummyUpdateKelasValid($value->kelas_id);
            }else{
                $data_kelas_valid = $this->dosenakademikservice->getDummyUpdateKelasLast($value->kelas_id);
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
        $this->load->view('dosen/penilaian/V_choose_revisi', $data);
    }
    public function nilai_mahasiswa_uas_revisi($kelas_id, $ta = null) {
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $ta = ($ta) ? $ta : $tahun_akademik; 
        $semua_kelas = $this->dosenakademikservice->getDummyUpdateKelas($kelas_id);
        if (!$semua_kelas) {
            $this->dosenakademikservice->insertDummyUpdateKelas(array('id_kelas' => $kelas_id, 'level' => '1'));
            $semua_kelas = $this->dosenakademikservice->getDummyUpdateKelas($kelas_id);
        }
		$level = $semua_kelas[0]->level;
        $data_kelas = $this->dosenakademikservice->getKelasRevisi($kelas_id);

        $kelas_mahasiswa1 = $this->dosenakademikservice->getNilaiRevisiLevel1($kelas_id, $level, $ta);
        $kelas_mahasiswa2 = $this->dosenakademikservice->getNilaiRevisiLevel2($kelas_id, $level, $ta);
        $sistem_nilai = $this->dosenakademikservice->getSistemPenilaian();
        
        foreach ($kelas_mahasiswa1 as $key => $val) {
            $tmp = $val->nilai_akhir;
            if ($val->nilai_akhir) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa1[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa1[$key]->grade = ''; }
            }
        }
        foreach ($kelas_mahasiswa2 as $key => $val) {
            $tmp = $val->nilai_akhir;
            if ($val->nilai_akhir) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa2[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa2[$key]->grade = ''; }
            }
        }
        
        foreach ($kelas_mahasiswa2 as $key => $val) {
            if ($kelas_mahasiswa2[$key]->na == null) {
                $kelas_mahasiswa2[$key]->na = $kelas_mahasiswa1[$key]->na ?? $kelas_mahasiswa1[$key]->khs_na;
                $kelas_mahasiswa2[$key]->uas = $kelas_mahasiswa1[$key]->uas ?? $kelas_mahasiswa1[$key]->khs_uas;
                $kelas_mahasiswa2[$key]->uts = $kelas_mahasiswa1[$key]->uts ?? $kelas_mahasiswa1[$key]->khs_uts;
                $kelas_mahasiswa2[$key]->harian = $kelas_mahasiswa1[$key]->harian ?? $kelas_mahasiswa1[$key]->khs_harian;
            }
            $tmp = $val->khs_na;
            if ($val->khs_na) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa2[$key]->khs_grade = reset($result)->grade; } else { $kelas_mahasiswa2[$key]->khs_grade = '-'; }
            } else {
                $kelas_mahasiswa2[$key]->khs_grade = '-';
            }
            if ($kelas_mahasiswa2[$key]->na) {
                $tmp = $kelas_mahasiswa2[$key]->na;
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ($tmp) && $obj->nilai_maksimum >= ($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa2[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa2[$key]->grade = '-'; }
            } else {
                if (!isset($kelas_mahasiswa2[$key]->grade)) { $kelas_mahasiswa2[$key]->grade = '-'; }
            }
        }
        // echo json_encode($kelas_mahasiswa2);die();
        
        $data['semua_kelas'] = $semua_kelas;
        $data['content'] = 'dosen/penilaian/V_nilai_revisi';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa2;
        $data['data_kelas'] = $data_kelas;
        $data['kelas_id'] = $kelas_id;
        $data['ta'] = $ta;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $data['exp'] = false;
        $data['homebase'] = $this->dosenakademikservice->getHomebaseDosen();
        $this->load->view('dosen/template/V_main', $data);
    }
    function nilai_revisi() {
        try {
        $id = $this->input->POST('id');
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $persentase = $this->dosenakademikservice->getPersentaseFrom('persentasi_nilai_dosen', $kelas);
        if (!$persentase) {
            echo json_encode(array(
                    'status' => false,
                    'data' => null,
                )   
            );
            return;
        }
        $harian = (float) $this->input->POST('harian');
        $uts = (float) $this->input->POST('uts');
        $uas = (float) $this->input->POST('uas');
        $status = $this->dosenakademikservice->checkDummyUpdateNilai($id, $level);
        $na = round($harian*$persentase->nilai_harian/100 + $uts*$persentase->nilai_uts/100 + $uas*$persentase->nilai_uas/100, 1);
        if ($status) {
            $data = array(
                'kelas_id' => $kelas,
                'harian' => $harian,
                'uts' => $uts,
                'uas' => $uas,
                'na' => $na,
                'level' => $level,
            );
            $this->dosenakademikservice->updateDummyUpdateNilai($id, $level, $data);
        }else{
            $data = array(
                'kelas_id' => $kelas,
                'kode_khs_detail' => $id,
                'harian' => $harian,
                'uts' => $uts,
                'uas' => $uas,
                'na' => $na,
                'level' => $level,
            );
            $this->dosenakademikservice->insertDummyUpdateNilai($data);
        }
        $sistem_nilai = $this->dosenakademikservice->getSistemPenilaian();
        
        $tmp = $na;
        $grade = '-';
        if (!empty($sistem_nilai)) {
            $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                return $obj->nilai_minimum <= $tmp && $obj->nilai_maksimum >= $tmp;
            });
            if (!empty($result)) {
                $grade = reset($result)->grade;
            }
        }

        echo json_encode(array(
                'status' => true,
                'data' => array(
                    'grade' => $grade,
                    'na' => $na,
                ),
            )   
        );
        } catch (\Exception $e) {
            echo json_encode(array('status' => false, 'error' => $e->getMessage()));
        }
    }
    public function revisi_dosen_selesai(){
        $kelas = $this->input->POST('kelas');
        $jum = $this->input->POST('jum');
        $level = $this->input->POST('level');
        $persentase = $this->dosenakademikservice->getPersentaseFrom('persentasi_nilai_dosen', $kelas);
        if (!$persentase) {
            echo json_encode(array('status' => false, 'msg' => 'Persentase penilaian belum diisi'));
            return;
        }
        for ($i=0; $i <= $jum; $i++) { 
            $obj = $this->input->POST('data'.$i);
            if (!$obj || !isset($obj['id'])) continue;
            $num = $this->dosenakademikservice->checkDummyUpdateNilai($obj['id'], $level);
            if (!$num) {
                $xxxx = $this->dosenakademikservice->getDummyUpdateNilaiDataPrev($obj['id'], $level);
                $new_obj = array(
                    'kelas_id' => $kelas,
                    'kode_khs_detail' => $obj['id'],
                    'harian' => (float) $obj['harian'],
                    'uts' => (float) $obj['uts'],
                    'uas' => (float) $obj['uas'],
                    'ket' => $xxxx ? $xxxx->ket : '',
                    'na' => round((float)$obj['harian']*$persentase->nilai_harian/100 + (float)$obj['uts']*$persentase->nilai_uts/100 + (float)$obj['uas']*$persentase->nilai_uas/100,1),
                    'level' => $level
                );
                $this->dosenakademikservice->insertDummyUpdateNilai($new_obj);
            }
        }
        $this->dosenakademikservice->updateDummyUpdateKelas($kelas, $level);
        echo json_encode(array('status' => true));
    }
    public function revisi_dosen_update($id){
         $this->dosenakademikservice->updateDosenKelasRevisi($id);
         redirect('dosen/penilaian/nilai_mahasiswa_uas_revisi/'.$id);
    }
    public function revisi_new_penilaian(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $new_data = array(
            'id_kelas' => $kelas,
            'level' => $level+1,
            'status' => '4',
            'status_dosen' => 'T',
            'status_prodi' => 'T',
            'status_dekan' => 'T',
        );
        $this->dosenakademikservice->insertDummyUpdateKelas($new_data);
        echo json_encode(array('status' => 'success'));
    }
    public function revisi_pebatalan_penilaian(){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $this->dosenakademikservice->deleteDummyUpdateKelas($kelas, $level);
        $this->dosenakademikservice->deleteDummyUpdateNilai($kelas, $level);
        echo json_encode(array('status' => 'success'));
    }
    public function revisi_nilai_mahasiswa($ta = null){
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $ta = ($ta) ? $ta : $tahun_akademik;
        $kelas_mahasiswa = $this->dosenakademikservice->getRevisiNilaiMahasiswa($kelas, $level, $ta);
        $sistem_nilai = $this->dosenakademikservice->getSistemPenilaian();
        foreach ($kelas_mahasiswa as $key => $val) {
            if ($val->na == null) {
                $kelas_mahasiswa[$key]->harian = $val->khs_harian;
                $kelas_mahasiswa[$key]->uts = $val->khs_uts;
                $kelas_mahasiswa[$key]->uas = $val->khs_uas;
                $kelas_mahasiswa[$key]->na = $val->khs_na;
            }
            $tmp = $kelas_mahasiswa[$key]->na;
            if ($tmp) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ($tmp) && $obj->nilai_maksimum >= ($tmp);
                });
                $kelas_mahasiswa[$key]->grade = !empty($result) ? reset($result)->grade : '';
            }
        }
        $data['data'] = $kelas_mahasiswa;
        $this->load->view('dosen/penilaian/V_revisi_nilai_mahasiswa', $data);
    }
    public function cetak_nilai_revisi_kelas($id,$level,$ta) {
        $data['query1'] = $this->dosenakademikservice->getCetakQuery1($id);
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
    public function revisi_ket(){
        $id = $this->input->POST('id');
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');

        $data = $this->dosenakademikservice->getDummyUpdateNilaiKet($id, $kelas, $level);
        if (!$data) {
            $data = $this->dosenakademikservice->getDummyUpdateNilaiKetPrev($id, $kelas, $level);
        }
        echo json_encode($data);
    }
    public function revisi_ket_val(){
        $id = $this->input->POST('id');
        $kelas = $this->input->POST('kelas');
        $level = $this->input->POST('level');
        $ket = $this->input->POST('ket');

        $data = $this->dosenakademikservice->getDummyUpdateNilaiData($id, $level);

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
                $this->dosenakademikservice->insertDummyUpdateNilai($new_obj);
            }else{
                $datax = $this->dosenakademikservice->getDummyUpdateNilaiData($id, $level - 1);

                $new_obj = array(
                    'kelas_id' => $kelas,
                    'kode_khs_detail' => $id,
                    'harian' => $datax->harian,
                    'uts' => $datax->uts,
                    'uas' => $datax->uas,
                    'ket' => $ket,
                    'na' => $datax->na,
                    'level' => $level
                );
                $this->dosenakademikservice->insertDummyUpdateNilai($new_obj);
            }
        }
        
        $result = $this->dosenakademikservice->updateDummyUpdateNilaiKet($kelas, $level, $id, $ket);  

        if ($result) {
            echo json_encode(array('status' => true));
        }else{
            echo json_encode(array('status' => false));
        }
    }
    public function excel($kelas_id){
        $filename = 'data_nilai '.$kelas_id;
        $kelas_mahasiswa = $this->dosenakademikservice->getExcelMahasiswa($kelas_id);
        $persentase = $this->dosenakademikservice->getPersentaseFrom('persentasi_nilai_dosen', $kelas_id);
        if (!$persentase) {
            $this->session->set_flashdata('fail_download', 'Tidak Dapar Mendownload Excel Anda Harus Mengisi <b>Presentasi Penilaian</b> Terlebih Dahulu');
            redirect($_SERVER['HTTP_REFERER']);
        }

        require_once FCPATH . 'vendor/autoload.php';
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        $sheet->setCellValue('A1', 'No');$sheet->getColumnDimension('B')->setWidth(2);
        $sheet->setCellValue('B1', 'Nim');$sheet->getColumnDimension('B')->setWidth(15);
        $sheet->setCellValue('C1', 'Nama Mahasiswa');$sheet->getColumnDimension('C')->setWidth(30);
        $sheet->setCellValue('D1', 'Harian');
        $sheet->setCellValue('E1', 'UTS');
        $sheet->setCellValue('F1', 'UAS');
        $sheet->setCellValue('G1', 'Nilai Akhir');
        $sheet->setCellValue('H1', 'Grade');
        $sheet->setCellValue('I1', 'Keterangan');

        $row = 2;
        foreach ($kelas_mahasiswa as $key => $value) {
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, $value->nim);
            $sheet->setCellValue('C'.$row, $value->nama_mahasiswa);
            $sheet->setCellValue('D'.$row, '');
            $sheet->setCellValue('E'.$row, '');
            $sheet->setCellValue('F'.$row, '');
            $sheet->setCellValue('G'.$row, '=ROUND(D'.$row.'*'.$persentase->nilai_harian.'/100+E'.$row.'*'.$persentase->nilai_uts.'/100+F'.$row.'*'.$persentase->nilai_uas.'/100,1)');
            $sheet->setCellValue('H'.$row, '=IF(G'.$row.'>=81,"A",IF(G'.$row.'>=71,"B+",IF(G'.$row.'>=66,"B",IF(G'.$row.'>=61,"C",IF(G'.$row.'>=50,"C+",IF(G'.$row.'>=40,"D","E"))))))');
            $sheet->setCellValue('I'.$row, '=IF(G'.$row.'>=81,"Sempurna",IF(G'.$row.'>=71,"Baik",IF(G'.$row.'>=66,"Baik",IF(G'.$row.'>=61,"Cukup",IF(G'.$row.'>=50,"Cukup",IF(G'.$row.'>=40,"Kurang","Kurang"))))))');
            $row++;
        }
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename.xlsx\"");
        header("Cache-Control: max-age=0");

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $writer->save('php://output');
        exit;
        
    }
    public function import_excel($kelas,$level){
        require_once FCPATH . 'vendor/autoload.php';
        if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] != 0) {
            $this->session->set_flashdata('fail_download', 'Tidak DapaT Mengupload Excel Anda');
            redirect($_SERVER['HTTP_REFERER']);
        }
        $tmp_file = $_FILES['file_excel']['tmp_name'];
        $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($tmp_file);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $excel = $reader->load($tmp_file);
        $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);
   
        $this->dosenakademikservice->deleteDummyUpdateNilai($kelas, $level);
        $kelas_mahasiswa = $this->dosenakademikservice->getImportExcelMahasiswa($kelas);
        foreach ($sheet as $key => $value) {
            if ($key == 1) {
                continue;
            }
            $search_nim = $value['B'];
            
            $filtered = array_filter($kelas_mahasiswa, function($row) use ($search_nim) {
                return $row->nim == $search_nim;
            });
        
            $obj[] = array(
                'kode_khs_detail' => reset($filtered)->kode_khs_detail,
                'kelas_id' => $kelas,
                'level' => $level,
                'harian' => $value['D'],
                'uts' => $value['E'],
                'uas' => $value['F'],
                'na' => $value['G'],
            );
        }
        $this->dosenakademikservice->insertBatchDummyUpdateNilai($obj);
        $this->session->set_flashdata('success', 'Data Berhasil di Import');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function uts_final($kelas_id) {
        $data_kelas = $this->dosenakademikservice->getKelasById($kelas_id);
        $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUts($kelas_id);
        $data['content'] = 'dosen/penilaian/V_input_penilaian_uts_final';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['exp'] = false;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $this->load->view('dosen/template/V_main', $data);
    }

    public function uas_final($kelas_id) {
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $data_kelas = $this->dosenakademikservice->getKelasById($kelas_id);
        if ($data_kelas->status_nilai != 'T') {
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUas($kelas_id);
        } else {
            $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUasWithGrade($kelas_id);
        }
        $data['content'] = 'dosen/penilaian/V_input_penilaian_uas_final';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_penilaian'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $data['exp'] = false;
        $data['homebase'] = $this->dosenakademikservice->getHomebaseDosen();
        $this->load->view('dosen/template/V_main', $data);
    }

    public function revisi_final($kelas_id, $ta = null) {
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $ta = ($ta) ? $ta : $tahun_akademik;
        $semua_kelas = $this->dosenakademikservice->getDummyUpdateKelas($kelas_id);
        if (!$semua_kelas) {
            $this->dosenakademikservice->insertDummyUpdateKelas(array('id_kelas' => $kelas_id, 'level' => '1'));
            $semua_kelas = $this->dosenakademikservice->getDummyUpdateKelas($kelas_id);
        }
        $level = $semua_kelas[0]->level;
        $data_kelas = $this->dosenakademikservice->getKelasRevisi($kelas_id);
        $kelas_mahasiswa1 = $this->dosenakademikservice->getNilaiRevisiLevel1($kelas_id, $level, $ta);
        $kelas_mahasiswa2 = $this->dosenakademikservice->getNilaiRevisiLevel2($kelas_id, $level, $ta);
        $sistem_nilai = $this->dosenakademikservice->getSistemPenilaian();
        foreach ($kelas_mahasiswa1 as $key => $val) {
            $tmp = $val->nilai_akhir;
            if ($val->nilai_akhir) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa1[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa1[$key]->grade = ''; }
            }
        }
        foreach ($kelas_mahasiswa2 as $key => $val) {
            $tmp = $val->nilai_akhir;
            if ($val->nilai_akhir) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa2[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa2[$key]->grade = ''; }
            }
        }
        foreach ($kelas_mahasiswa2 as $key => $val) {
            if ($kelas_mahasiswa2[$key]->na == null) {
                $kelas_mahasiswa2[$key]->na = $kelas_mahasiswa1[$key]->na ?? $kelas_mahasiswa1[$key]->khs_na;
                $kelas_mahasiswa2[$key]->uas = $kelas_mahasiswa1[$key]->uas ?? $kelas_mahasiswa1[$key]->khs_uas;
                $kelas_mahasiswa2[$key]->uts = $kelas_mahasiswa1[$key]->uts ?? $kelas_mahasiswa1[$key]->khs_uts;
                $kelas_mahasiswa2[$key]->harian = $kelas_mahasiswa1[$key]->harian ?? $kelas_mahasiswa1[$key]->khs_harian;
            }
            $tmp = $val->khs_na;
            if ($val->khs_na) {
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ceil($tmp) && $obj->nilai_maksimum >= ceil($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa2[$key]->khs_grade = reset($result)->grade; } else { $kelas_mahasiswa2[$key]->khs_grade = '-'; }
            } else {
                $kelas_mahasiswa2[$key]->khs_grade = '-';
            }
            if ($kelas_mahasiswa2[$key]->na) {
                $tmp = $kelas_mahasiswa2[$key]->na;
                $result = array_filter($sistem_nilai, function($obj) use ($tmp) {
                    return $obj->nilai_minimum <= ($tmp) && $obj->nilai_maksimum >= ($tmp);
                });
                if (!empty($result)) { $kelas_mahasiswa2[$key]->grade = reset($result)->grade; } else { $kelas_mahasiswa2[$key]->grade = '-'; }
            } else {
                if (!isset($kelas_mahasiswa2[$key]->grade)) { $kelas_mahasiswa2[$key]->grade = '-'; }
            }
        }
        $data['semua_kelas'] = $semua_kelas;
        $data['content'] = 'dosen/penilaian/V_nilai_revisi_final';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['data'] = $kelas_mahasiswa2;
        $data['data_kelas'] = $data_kelas;
        $data['kelas_id'] = $kelas_id;
        $data['ta'] = $ta;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $this->load->view('dosen/template/V_main', $data);
    }
}