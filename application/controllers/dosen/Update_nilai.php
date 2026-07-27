<?php

class Update_nilai extends CI_Controller {

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

    public function update_uts() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $ta = $this->m_tahun_akademik->get();
        $tahun_akademik = tahun_akademik();
        $data_kelas_ampu = $this->dosenakademikservice->getKelasAmpuResult($kode_dosen, $tahun_akademik->kode_tahun_akademik);

        $data['content'] = 'dosen/penilaian/V_update_penilaian_uts';
        $data['judul'] = 'Update Penilaian UTS';
        $data['a_update_penilaian'] = 'active';
        $data['a_update_penilaian_uts'] = 'active';
        $data['data'] = $data_kelas_ampu;
        $data['tahun_akademik'] = $ta;
        $data['setting'] = $this->dosenakademikservice->getSettingKuisioner();
        $data['chat_id'] = $this->dosenakademikservice->getChatIdDosen($kode_dosen);

        $data['get_kode_ta'] = $tahun_akademik->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
    }
    // update uts
    public function update_nilai_uts(){
        if ($this->input->post('kode_nilai_akademik')) {
            $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
        } else {
            $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        }
        $time = $this->dosenakademikservice->getAktivasi($kode_tahun_akademik);
        $kode_dosen = $this->session->userdata('kode_dosen');
        
        $data['data'] = $this->dosenakademikservice->getMengajarWithUpdate($kode_dosen, $kode_tahun_akademik);
        $data1['data'] = $this->dosenakademikservice->getMengajarWithUpdateProdi($kode_dosen, $kode_tahun_akademik);
        
        $pesan_prodi = array();
        $pesan_dekan = array();
        foreach ($data['data'] as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'update_catatan_revisi', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'update_catatan_revisi', 'dekan');
            $pesan_prodi[$key] = count($tmp_prodi);
            $pesan_dekan[$key] = count($tmp_dekan);
        }

        foreach ($data1['data'] as $key => $value) {
            $tmp_prodi = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'update_catatan_revisi', 'prodi');
            $tmp_dekan = $this->dosenakademikservice->getCatatanRevisiCount($value->kelas_id, 'update_catatan_revisi', 'dekan');
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
                $this->load->view('dosen/penilaian/V_choose_update_nilai_uts', $data1);
            } else {
                 $data_update = array(
                    'param_uts' => "1",
                );

                $where_in = array('F', 'R');
                $this->dosenakademikservice->updateKelasParamUts($kode_tahun_akademik);

               
                $this->load->view('dosen/penilaian/V_choose_update_nilai_uts', $data1);
            }
        }
    }
    public function nilai_mahasiswa_uts($kelas_id) {
        $data_kelas = $this->dosenakademikservice->getKelasByIdWithUpdate($kelas_id);

        $cek = $this->dosenakademikservice->checkDummyUpdateKelas($kelas_id);

        if(!$cek){
            $this->dosenakademikservice->insertDummyUpdateKelas(array('id_kelas'=>$kelas_id));
        }

        $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUpdateUts($kelas_id);

        $data['content'] = 'dosen/penilaian/V_update_input_penilaian_uts';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_update_penilaian'] = 'active';
        $data['a_update_penilaian_uts'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['exp'] = false;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $this->load->view('dosen/template/V_main', $data);
    }
    public function exis($kode_khs_detail) {
        $cek = $this->dosenakademikservice->exisDummyUpdateNilai($kode_khs_detail);
        if (count($cek) > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function addOrUpdate($kode_khs_detail, array $data_add, array $data_update) {
        return $this->dosenakademikservice->addOrUpdateDummyUpdateNilai($kode_khs_detail, $data_add, $data_update);
    }
    public function uts($kode_khs_detail, $kode_krs_detail){
        $nilai_uts = $this->input->post('nilai_uts');
        $data_add = array(
            'kode_khs_detail' => $kode_khs_detail,
        );
        if ($nilai_uts == "") {
            $data_add['uts'] = null;
            $data_update['uts'] = null;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        } else {
            $data_add['uts'] = $nilai_uts;
            $data_update['uts'] = $nilai_uts;
            $ubah = $this->addOrUpdate($kode_khs_detail, $data_add, $data_update);
        }
        $persentasi_nilai = $this->dosenakademikservice->getPersentasiNilai($this->session->userdata('sess_kelas_id'));
        $nil = $this->dosenakademikservice->getDummyUpdateNilai($kode_khs_detail);
        $nilai_akhir = ($nil->harian * $persentasi_nilai->nilai_harian / 100) + ($nil->uts * $persentasi_nilai->nilai_uts / 100) + ($nil->uas * $persentasi_nilai->nilai_uas / 100);
        $this->dosenakademikservice->updateDummyUpdateNilaiNa($kode_khs_detail, $nilai_akhir);
        if ($ubah) {
            $res['status'] = 'true';
        } else {
            $res['status'] = 'false';
        }
        echo json_encode($res);
    }
    public function selesai_uts($kelas_id) {
        $query_dosen_kelas = $this->dosenakademikservice->getSelesaiUtsQueryDosenKelas($kelas_id);
        $query_prodi = $this->dosenakademikservice->getSelesaiUtsQueryProdi($kelas_id);
        
        $this->dosenakademikservice->updateDummyKelasStatusUts($kelas_id);
        try {
            $message_text2 = "*SISKA UBG* - Nilai UTS " . $query_dosen_kelas['kdmk'] . " - " . $query_dosen_kelas['nama_matakuliah'] . " Kelas " . $query_dosen_kelas['nama_kelas'] . " Semester " . $query_dosen_kelas['semester'] . ", sudah selesai diinputkan oleh " . $query_dosen_kelas['nama_dosen'] . " selaku dosen pengampu dan menunggu validasi dari " . $query_prodi['nama_dosen'] . " selaku Ketua Program Studi " . $query_prodi['nama_program_studi'] . ", Kode Kelas *[" . $query_dosen_kelas['kelas_id'] . "]*";
            kirim_ke_telegram($query_prodi['chatid'], $message_text2);
            $this->dosenakademikservice->transCommit();
        } catch (\Throwable $th) {
            $this->dosenakademikservice->transCommit();
        }
        redirect('dosen/update_nilai/nilai_mahasiswa_uts/'.$kelas_id);
    }
    
    // pesan uts
    public function pesan_uts($kelas_id, $user1, $user2) {
        if ($user1 == 'dosen' && $user2 == 'prodi') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'update_catatan_revisi', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosenBatch($kelas_id, 'update_catatan_revisi');
        } elseif ($user2 == 'dosen' && $user1 == 'prodi') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'update_catatan_revisi', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiProdiBatch($kelas_id, 'update_catatan_revisi');
        } elseif ($user1 == 'dosen' && $user2 == 'dekan') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'update_catatan_revisi', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDosenDekanBatch($kelas_id, 'update_catatan_revisi');
        } elseif ($user2 == 'dosen' && $user1 == 'dekan') {
            $pesan = $this->dosenakademikservice->getUpdateCatatanRevisi($kelas_id, 'update_catatan_revisi', $user1, $user2);
            $this->dosenakademikservice->updateCatatanRevisiDekanBatch($kelas_id, 'update_catatan_revisi');
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
            return $this->load->view("dosen/penilaian/V_update_catatan_revisi_dosen", $data);
        } else if ($user1 == 'prodi') {
            return $this->load->view("dosen/penilaian/V_update_catatan_revisi_kaprodi", $data);
        } else if ($user1 == 'dekan') {
            return $this->load->view("dosen/penilaian/V_update_catatan_revisi_dekan", $data);
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
                $resutl = $this->dosenakademikservice->insertUpdateCatatanRevisi('update_catatan_revisi', $data);
            } else if ($param == 'uas') {
                $resutl = $this->dosenakademikservice->insertUpdateCatatanRevisi('update_catatan_revisi_uas', $data);
            }
            if ($resutl) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }

    public function uts_final($kelas_id) {
        $data_kelas = $this->dosenakademikservice->getKelasByIdWithUpdate($kelas_id);
        $cek = $this->dosenakademikservice->checkDummyUpdateKelas($kelas_id);
        if (!$cek) {
            $this->dosenakademikservice->insertDummyUpdateKelas(array('id_kelas' => $kelas_id));
        }
        $kelas_mahasiswa = $this->dosenakademikservice->getNilaiMahasiswaUpdateUts($kelas_id);
        $data['content'] = 'dosen/penilaian/V_update_input_penilaian_uts_final';
        $data['judul'] = 'Penilaian Mahasiswa';
        $data['a_update_penilaian'] = 'active';
        $data['a_update_penilaian_uts'] = 'active';
        $data['data'] = $kelas_mahasiswa;
        $data['data_kelas'] = $data_kelas;
        $data['exp'] = false;
        $data['kelas_id'] = $kelas_id;
        $data['persentasi_nilai'] = $this->dosenakademikservice->getPersentasiNilai($kelas_id);
        $this->session->set_userdata(array('sess_kelas_id' => $kelas_id));
        $this->load->view('dosen/template/V_main', $data);
    }
}