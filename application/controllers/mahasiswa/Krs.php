<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Krs extends CI_Controller
{

    public $data_matakuliah;
    public $semester;
    public $status_pendaftaran;
    public $paket;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(
            array(
                'jurusan/m_tahun_akademik',
                'jurusan/program_studi/Nama_jurusan_model',
                'jurusan/program_studi/Jenjang_model',
                'jurusan/program_studi/Kode_jurusan_model',
                'jurusan/program_studi/Ketua_jurusan_model',
                'jurusan/program_studi/Kompetensi_model',
                'jurusan/kurikulum/m_data_kurikulum',
                'jurusan/kurikulum/m_matakuliah',
                'jurusan/Perwalian_model',
                'jurusan/m_dosen',
                'keuangan/Status_perkuliahan_model',
                'akademik/Krs_model',
                'akademik/Krs_detail_model',
                'akademik/Khs_model',
                'akademik/Mahasiswa_model',
                'kuisioner/kuisioner_model',

            )
        );
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
        $this->semester = $this->semester_saat_ini();
        $this->cek_kuisioner();
    }

    public function old($tahun_akademik,$semester){
        $ta = $this->m_tahun_akademik->get_aktif();
        $dosen_wali = $this->Perwalian_model->get_perwalian_by_nim($this->session->userdata('nim'));
        $nim = $this->session->userdata('nim');
        $kode_jejang = substr($nim, 4, 1);
        $gelombang = substr($nim, 5, 1);
        $kode_jurusan = substr($nim, 2, 2);
        $data['krs_mhs'] = $this->mahasiswaservice->getKrsMhsHistory($nim, $ta);
        $data['aktif_dosen'] = $this->mahasiswaservice->getKonsultasiPerwalianAktif($nim, $tahun_akademik);
        $data['bayar_sks'] = $this->mahasiswaservice->getBayarSks($nim, $tahun_akademik);
        $kode_krs = $this->Krs_model->get_kode_krs($this->session->userdata('nim'), $tahun_akademik);
        $data['conten'] = "mahasiswa/V_Krs";
        $data['beban_sks'] = $this->maksimum_sks_lalu($tahun_akademik,$semester);
        $data['data'] = $this->Krs_detail_model->get_data_krs($kode_krs);
        $data['judul'] = "KRS | Semester " . $semester;
        $data['prodi'] = $this->Nama_jurusan_model->get_all_byid($this->session->userdata('kode_program_studi'));
        if (!empty($data['prodi'])) {
            $data['jenjang'] = $this->Jenjang_model->get_nama($data['prodi']->id_jenjang);
        } else {
            $data['jenjang'] = '';
        }
        $data['nama_mahasiswa'] = $this->session->userdata('nama_mahasiswa');
        $data['dosen_wali'] = !empty($dosen_wali) && is_object($dosen_wali) ? $dosen_wali->nama_dosen : '-';
        $data['nim'] = $this->session->userdata('nim');
        $data['semester'] = $semester;
        $data['data_mahasiswa'] = $this->Mahasiswa_model->get($this->session->userdata('nim'));
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($tahun_akademik);
        $data['ta'] = $ta;
        $this->load->view('mahasiswa/template/V_main', $data);
    }

    public function index()
    {
        $this->starter();
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data['ta'] = $this->m_tahun_akademik->get_aktif();
        $dosen_wali = $this->Perwalian_model->get_perwalian_by_nim($this->session->userdata('nim'));
        $cek_exis = $this->Krs_model->cek_krs_exis($this->session->userdata('nim'), $tahun_akademik);
        $nim = $this->session->userdata('nim');
        $kode_jejang = substr($nim, 4, 1);
        $gelombang = substr($nim, 5, 1);
        $kode_jurusan = substr($nim, 2, 2);
        $data['krs_mhs'] = $this->mahasiswaservice->getKrsMhsHistoryWithoutTa($nim, $data['ta']);
        
        if ($cek_exis !== null) {
            $data['aktif_dosen'] = $this->mahasiswaservice->getKonsultasiPerwalianAktif($nim, $tahun_akademik);
            $data['bayar_sks'] = $this->mahasiswaservice->getBayarSks($nim, $tahun_akademik);
            $kode_krs = $this->Krs_model->get_kode_krs($this->session->userdata('nim'), $tahun_akademik);
            $data['conten'] = "mahasiswa/V_Krs";
            $data['beban_sks'] = $this->maksimum_sks();
            $data['data'] = $this->Krs_detail_model->get_data_krs($kode_krs);
            
        } else {
          	if(false){
             	redirect('home/Access_krs_denied'); 
            }
            if (($this->semester == 1 || $this->semester == 2) && $this->status_pendaftaran !== 'B' && available_kompetensi($nim)) {
                //                tambahan
                $cek_krs_biasa = $this->mahasiswaservice->getCekKrsBiasa($nim, $tahun_akademik);
                if (!empty($cek_krs_biasa)) {
                    $krs_lalu = $this->Krs_model->get_kodemk_krs($nim);
                    $beban = $this->maksimum_sks();
                } else {
                    $krs_lalu = $this->Krs_model->get_kodemk_krs_konversi($nim);
                    $beban = $this->maksimum_sks();
                    //                    $beban['beban_sks'] = 19;
                }
                //                tambahan ahir
                if ($this->cek_makul_transfer()) {
                    $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                    if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                        redirect(site_url('mahasiswa/kompetensi'));
                    } else {
                        $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                        $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                        //                        $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                        $data['jumlah_maksimum_sks'] = $beban;
                        $data['conten'] = "mahasiswa/V_Krs_add";
                        $data['data_matakuliah'] = $this->data_matakuliah;
                        //                        $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs_konversi($nim);
                        $data['krs_lalu'] = $krs_lalu; // perubahan
                    }
                } else {
                    //                    $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                    $data['jumlah_maksimum_sks'] = $beban;
                    $data['conten'] = "mahasiswa/V_Krs_add";
                    $data['data_matakuliah'] = $this->data_matakuliah;
                    //                    $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs_konversi($this->session->userdata('nim'));
                    $data['krs_lalu'] = $krs_lalu; //perubahan
                }
            } elseif ($this->semester == 1 && $this->status_pendaftaran !== 'B' && !available_kompetensi($nim)) {
                $beban = $this->maksimum_sks();
                //                $beban['beban_sks'] = 19;
//                $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                $data['jumlah_maksimum_sks'] = $beban;
                $data['conten'] = "mahasiswa/V_Krs_add";
                $data['data_matakuliah'] = $this->data_matakuliah;
                $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs_konversi($this->session->userdata('nim'));
            } elseif ($this->semester == 1) {
                redirect('mahasiswa/krs/matakuliah_awal');
            } else {
                if ($this->paket == 'Y') {
                    //                    $data['data_matakuliah'] = $this->data_matakuliah;
//                    $data['conten'] = 'mahasiswa/V_Krs_paket';
                    if ($this->status_pendaftaran !== 'B' && $this->semester == 1) {
                        $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                        // $data['conten'] = "mahasiswa/V_Krs_add";
                        $data['data_matakuliah'] = $this->data_matakuliah;
                        $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs_konversi($this->session->userdata('nim'));
                        //mulai coba
                    } elseif ($this->status_pendaftaran !== 'B' && $this->semester > 1) {
                        $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                        $data['conten'] = "mahasiswa/V_Krs_add";
                        $data['data_matakuliah'] = $this->data_matakuliah;
                        $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs($this->session->userdata('nim'));
                        //sampai coba
                    } elseif ($this->semester > 6) {
                        $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                        $data['conten'] = "mahasiswa/V_Krs_add";
                        $data['data_matakuliah'] = $this->data_matakuliah;
                        $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs($this->session->userdata('nim'));
                        //sampai coba
                    } else {
                        $data['data_matakuliah'] = $this->data_matakuliah;
                        $data['conten'] = 'mahasiswa/V_Krs_paket';
                    }
                } else {

                    $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                    $data['conten'] = "mahasiswa/V_Krs_add";
                    $data['data_matakuliah'] = $this->data_matakuliah;
                    $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs($this->session->userdata('nim'));
                }
            }
        }
        
        if (!empty($dosen_wali->kode_dosen_perwakilan)) {
            $dosen_perwakilan = $this->m_dosen->get_dosen_by_kode($dosen_wali->kode_dosen_perwakilan);
            $data['dosen_perwakilan'] = ($dosen_perwakilan !== null)
                ? array(
                    'nama_dosen' => $dosen_perwakilan->nama_dosen,
                    'no_telp' => isset($dosen_perwakilan->no_telp) ? $dosen_perwakilan->no_telp : '',
                )
                : null;
        }
        $data['judul'] = "KRS | Semester " . $this->semester;
        $data['prodi'] = $this->Nama_jurusan_model->get_all_byid($this->session->userdata('kode_program_studi'));
        if (!empty($data['prodi'])) {
            $data['jenjang'] = $this->Jenjang_model->get_nama($data['prodi']->id_jenjang);
        } else {
            $data['jenjang'] = '';
        }
        $data['nama_mahasiswa'] = $this->session->userdata('nama_mahasiswa');
        $data['dosen_wali'] = !empty($dosen_wali) && is_object($dosen_wali) ? $dosen_wali->nama_dosen : '-';
        $data['nim'] = $this->session->userdata('nim');
        $data['semester'] = $this->semester;
        $data['data_mahasiswa'] = $this->Mahasiswa_model->get($this->session->userdata('nim'));
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($tahun_akademik);
        // echo json_encode($data['tahun_akademik']);break;
        // echo "<pre>";
        // print_r($data);
        // echo json_encode($data['data_matakuliah']);break;
        $this->load->view('mahasiswa/template/V_main', $data);
    }

    public function starter()
    {
        $nim = $this->session->userdata('nim');
        $mahasiswa = $this->Mahasiswa_model->get($nim);
        $this->status_pendaftaran = $mahasiswa->status_pendaftaran;
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $cek_paket = $this->m_data_kurikulum->get_nama_kurikulum($nim);
        $this->paket = $cek_paket->paket;
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $semester = $tahun_akademik->semester;
        $status_perkuliahan = $this->status_perkuliahan();
        $kode_jejang = substr($nim, 4, 1);
        $gelombang = substr($nim, 5, 1);
        $kode_jurusan = substr($nim, 2, 2);
        $kompetensi_manajemen_semester4 = substr($nim, 2, 4);
        if ($status_perkuliahan) {
            if ($kompetensi_manajemen_semester4 == "0108") {
                if (($this->semester_saat_ini() >= 2) && ($this->status_pendaftaran == 'B')) {
                    $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                    if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                        redirect(site_url('mahasiswa/kompetensi'));
                    } elseif (available_extensi($nim)) {
                        $this->data_matakuliah = $this->coba_krs_pilihan();
                    } else {
                        $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                        $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                    }
                } else {
                    $this->data_matakuliah = $this->m_data_kurikulum->get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $this->semester, $this->status_pendaftaran);
                }
            }elseif ($kompetensi_manajemen_semester4 == "0301" && substr($nim, 0, 2) < 24 || $kompetensi_manajemen_semester4 == "0501" && substr($nim, 0, 2) >= 24) {
                if (($this->semester_saat_ini() >= 4) && ($this->status_pendaftaran == 'B')) {
                    $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                    if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                        redirect(site_url('mahasiswa/kompetensi'));
                    } elseif (available_extensi($nim)) {
                        $this->data_matakuliah = $this->coba_krs_pilihan();
                    } else {
                        $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                        $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                    }
                } else {
                    if (available_kompetensi($nim) && ($this->semester_saat_ini() >= 5) && $this->status_pendaftaran == 'B' || available_kompetensi($nim) && ($this->semester_saat_ini() >= 5) && $this->status_pendaftaran == 'T') {
                        $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                        if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                            redirect(site_url('mahasiswa/kompetensi'));
                        } elseif (available_extensi($nim)) {
                            $this->data_matakuliah = $this->coba_krs_pilihan();
                        } else {
                            $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                            $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                        }
                    } elseif ($this->semester >= 1 && $this->status_pendaftaran !== 'B' && available_kompetensi($nim)) {
                        if ($this->cek_makul_transfer()) {
                            $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                            if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                                redirect(site_url('mahasiswa/kompetensi'));
                            } elseif (available_extensi($nim)) {
                                $this->data_matakuliah = $this->coba_krs_pilihan();
                            } else {
                                $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                                $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                            }
                        } else {
                            $this->data_matakuliah = $this->m_data_kurikulum->get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $this->semester);
                        }
                    } else {

                        $this->data_matakuliah = $this->m_data_kurikulum->get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $this->semester, $this->status_pendaftaran);
                    }
                }
            } else {

                if (available_kompetensi($nim) && ($this->semester_saat_ini() >= 5) && $this->status_pendaftaran == 'B') {
                    $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                    if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                        redirect(site_url('mahasiswa/kompetensi'));
                    } elseif (available_extensi($nim)) {
                        $this->data_matakuliah = $this->coba_krs_pilihan();
                    } else {
                        $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                        $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                    }
                } elseif ($this->semester >= 1 && $this->status_pendaftaran !== 'B' && available_kompetensi($nim) ) {
                    if ($this->cek_makul_transfer()) {
                        $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                        if ($kompetensi->num_rows() == 0 && !available_extensi($nim)) {
                            redirect(site_url('mahasiswa/kompetensi'));
                        } elseif (available_extensi($nim)) {
                            $this->data_matakuliah = $this->coba_krs_pilihan();
                        } else {
                            $data_kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim)->row_object();
                            $this->data_matakuliah = $this->coba_krs_pilihan($data_kompetensi->kode_kompetensi);
                        }
                    } else {
                        $this->data_matakuliah = $this->m_data_kurikulum->get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $this->semester);
                    }
                } else {

                    $this->data_matakuliah = $this->m_data_kurikulum->get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $this->semester, $this->status_pendaftaran);
                }
            }

        } else {
            $this->session->set_flashdata('info', '<div class="callout callout-danger flat">
                <h4><i class="fa fa-ban"></i> Peringatan!</h4>

                <p>Anda tidak dapat melakukan pengisian KRS untuk semester berlangsung, karena status perkuliahan Anda belum "AKTIF"!<br>Silakan melunasi SPP atau mengurus dispensasi SPP terlebih dahulu agar Anda berhak melakukan pengisian KRS ini!. Jika sudah melakukan pembayaran SPP silahkan untuk menunggu validasi pembayaran SPP maksimal 1 hari setelah pembayaran.</p>
              </div>');
            // redirect('home/Access_denied');
            redirect('home/Access_krs_denied');
        }
    }
    public function simpan_krs()
    {
        $krs_lalu = $this->Krs_model->get_kodemk_krs($this->session->userdata('nim'));
        $data_baru = $this->input->post('baru');
        $data_ulang = $this->input->post('ulang');
        $total_sks_dipilih = $this->input->post('total_sks_dipilih');
        $beban = $this->maksimum_sks();
        $beban_sekarang = $beban['beban_sks'];
        $nim = $this->session->userdata('nim');

        // Server-side recalculation of total SKS from submitted checkboxes
        $total_sks_server = 0;
        $data_baru = $this->input->post('baru');
        $data_ulang = $this->input->post('ulang');
        if (is_array($data_baru)) {
            foreach ($data_baru as $kode_mk) {
                $mak = explode(",", $kode_mk);
                if (isset($mak[1])) $total_sks_server += (int)$mak[1];
            }
        }
        if (is_array($data_ulang)) {
            foreach ($data_ulang as $kode_mk) {
                $mak = explode(",", $kode_mk);
                if (isset($mak[1])) $total_sks_server += (int)$mak[1];
            }
        }

        if ($total_sks_server > $beban_sekarang) {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Gagal","Jumlah SKS Matakuliah yang anda pilih lebih besar dari beban yang di berikan '.$total_sks_dipilih.'","error")</script>'
            );
            redirect('mahasiswa/krs');

        } else {
            $tahun = $this->m_tahun_akademik->get_semester();
            $sem = $tahun->semester;
            $tahun_akademik = $tahun->tahun_akademik;
            $kode_tahun_akademik = $tahun->kode_tahun_akademik;
            $tahun_angkatan = substr($nim, 0, 2);
            if ($sem == 0) {
                $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
            } else {
                $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
            }
            if (substr($nim, 2, 4) == "0108") {
                if(substr($nim, 0, 2) == "24" && substr($nim, 10, 2) > 7  ){
                    $semester = ($semester - 1);
                }
            }
            $cek_krs_exis = $this->mahasiswaservice->getCekKrsExis($this->session->userdata('nim'), $kode_tahun_akademik);
            if (empty($cek_krs_exis)) {
                $this->mahasiswaservice->transStart();
                //        Simpan Data KRS
                $data_krs = array(
                    'kode_tahun_akademik' => $kode_tahun_akademik,
                    'nim' => $nim,
                    'semester' => $semester,
                );

                $kode_krs = $this->Krs_model->simpan_krs($data_krs);
                //        Simpan data Khs
                if ($kode_krs !== null) {
                    $data_khs = array(
                        'kode_krs' => $kode_krs,
                    );
                    $this->Khs_model->simpan_khs($data_khs);
                    //        Simpan Data Krs_Detail
                    if (is_array($data_baru)):
                    foreach ($data_baru as $kode_mk) {
                        $mak = explode(",", $kode_mk);
                        if (in_array($mak[0], $krs_lalu)):
                            $status = 'U';
                        else:
                            $status = 'B';
                        endif;
                        $data_krs_detail = array(
                            'kode_krs' => $kode_krs,
                            'id_matakuliah' => $mak[0],
                            'status' => $status,
                        );

                        $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                        $data_khs_detail = array(
                            'kode_krs_detail' => $kode_krs_detail,
                        );

                        $this->Krs_detail_model->simpan_khs($data_khs_detail);
                    }
                    endif;

                    if (!empty($data_ulang)):
                        foreach ($data_ulang as $kode_mk) {
                            $mak = explode(",", $kode_mk);
                            if (in_array($mak[0], $krs_lalu)):
                                $status = 'U';
                            else:
                                $status = 'B';
                            endif;
                            $data_krs_detail = array(
                                'kode_krs' => $kode_krs,
                                'id_matakuliah' => $mak[0],
                                'status' => $status,
                            );

                            $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                            $data_khs_detail = array(
                                'kode_krs_detail' => $kode_krs_detail,
                            );

                            $this->Krs_detail_model->simpan_khs($data_khs_detail);
                        }

                    endif;

                }
                $this->mahasiswaservice->transComplete();
                if ($this->mahasiswaservice->transStatus() === FALSE) {
                    $this->mahasiswaservice->transRollback();
                    redirect('mahasiswa/krs');
                } else {
                    $this->mahasiswaservice->transCommit();
                }
            } else {
                redirect('mahasiswa/krs');
            }

            redirect('mahasiswa/krs');
        }
    }

    public function edit_krs()
    {
        $this->starter();
        $nim = $this->session->userdata('nim');
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $dosen_wali = $this->Perwalian_model->get_perwalian_by_nim($this->session->userdata('nim'));
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $status_cetak = $this->Perwalian_model->cek_status_cetak($this->session->userdata('nim'), $kode_tahun_akademik);
        if ($this->semester == 1 || $this->paket == 'Y') {
            $this->session->set_flashdata('info', '<div class="alert alert-info alert-dismissible flat">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Info!</h4>
                Perubahan KRS tidak bisa dilakukan untuk mahasiswa semeter 1 atau mahasiswa yang menggunakan sistem paket.
              </div>');

            redirect('mahasiswa/krs');
        } else {
            if (!empty($status_cetak)) {
                $this->session->set_flashdata('info', '<div class="alert alert-info alert-dismissible flat">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Info!</h4>
                Untuk melakukan perubahan KRS silahkan konsultasi dengan Dosen Wali terlebih dahulu.
              </div>');

                redirect('mahasiswa/krs');
            } else {
              	if(false){
                 	redirect('home/Access_krs_denied'); 
                }
                $kode_krs = $this->Krs_model->get_kode_krs($this->session->userdata('nim'), $kode_tahun_akademik);
                $krs = $this->Krs_detail_model->get_data_krs($kode_krs);
                $data_krs = array();
                foreach ($krs as $row) {
                    $data_krs[] = $row->id_matakuliah;
                }
                $cek_krs_biasa = $this->mahasiswaservice->getCekKrsBiasa($nim, $tahun_akademik);
                if (!empty($cek_krs_biasa)) {
                    $beban = $this->maksimum_sks();
                    $krs_lalu = $this->Krs_model->get_kodemk_krs($nim);
                } else {
                    $beban = $this->maksimum_sks();
                    $beban['beban_sks'] = 19;
                    $krs_lalu = $this->Krs_model->get_kodemk_krs_konversi($nim);
                }
                $data['krs_exis'] = $data_krs;
                //                $data['jumlah_maksimum_sks'] = $this->maksimum_sks();
                $data['jumlah_maksimum_sks'] = $beban;
                $data['conten'] = "mahasiswa/V_edit_krs";
                $data['data_matakuliah'] = $this->data_matakuliah;
                //                $data['krs_lalu'] = $this->Krs_model->get_kodemk_krs($this->session->userdata('nim'));
                $data['krs_lalu'] = $krs_lalu;
                $data['judul'] = "KRS | Semester " . $this->semester;
                $data['prodi'] = $this->Nama_jurusan_model->get_all_byid($this->session->userdata('kode_program_studi'));
                if (!empty($data['prodi'])) {
                    $data['jenjang'] = $this->Jenjang_model->get_nama($data['prodi']->id_jenjang);
                } else {
                    $data['jenjang'] = '';
                }
                $data['nama_mahasiswa'] = $this->session->userdata('nama_mahasiswa');
                $data['dosen_wali'] = !empty($dosen_wali) && is_object($dosen_wali) ? $dosen_wali->nama_dosen : '-';
                $data['nim'] = $this->session->userdata('nim');
                $data['semester'] = $this->semester;
                $data['data_mahasiswa'] = $this->Mahasiswa_model->get($this->session->userdata('nim'));
                $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);

                $this->load->view('mahasiswa/template/V_main', $data);
            }
        }

    }

    public function cek_prasyarat2()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        if ($this->status_pendaftaran == 'B') {
            $data_krs = $this->Krs_model->get_kodemk_krs($nim);
        } else {
            $data_krs = $this->Krs_model->get_kodemk_krs_sudah_ambil($nim);
        }
        echo json_encode($data_krs);
        $id_matakuliah = 1785;
        $matakuliah_yg_diambil = $this->Krs_model->get_kodemk_diambil($kode_nama_kurikulum);
        
        $get_pra = $this->mahasiswaservice->getMatakuliahPrasyaratSelect($kode_nama_kurikulum, $id_matakuliah);
        

        if ($get_pra) {
            $re['pra'] = false;
            $re['mak_ambil'] = $get_pra->matakuliah_yg_diambil;
                       echo json_encode($re);
        }
        if (stup_grade($kode_nama_kurikulum, $this->semester_saat_ini())) {
            $data_penilaian = stup_grade($kode_nama_kurikulum, $this->semester_saat_ini());
        } else {
            //            $data_penilaian = $this->sistem_penilaian($nim);
            $data_penilaian = sistem_penilaian($nim);
        }
        if (in_array($id_matakuliah, $matakuliah_yg_diambil)) {
            $cek = $this->mahasiswaservice->getMatakuliahPrasyaratById($id_matakuliah, $kode_nama_kurikulum);
            $n = 0;
            $res = array('st' => array());
            
            foreach ($cek as $row) {
                
                if (!in_array($row->id_matakuliah_syarat, $data_krs)) {
                    if ($row->jenis_prasyarat == 'LA') {
                        $res['st'][] = false;
                        $re['res'][$n]['la'] = false;
                        $re['res'][$n]['kode_prasyarat'] = $row->id_matakuliah_syarat;
                        $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                        $re['res'][$n]['msg'] = 'Anda harus memilih matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';

                    } else {
                        $res['st'][] = false;
                        $re['res'][$n]['data'] = $row->id_matakuliah_syarat;
                        $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                        $re['res'][$n]['msg'] = 'Anda harus sudah mengambil matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';
                    }
                } else {
                    //Cek jumlah matakuliah
                    $mak = $this->mahasiswaservice->getMakKrsDetail($nim, $row->id_matakuliah_syarat);
                    // echo $mak->id_matakuliah.' => '.$mak->nama_matakuliah. ' = '.$mak->nilai_akhir.'<br>';
                    if ($row->jenis_prasyarat == 'LU') {
                        if ($mak->jumlah == 1) {
                            foreach ($data_penilaian as $key) {
                                if (($key['nilai_minimum'] <= $mak->nilai_akhir) && ($mak->nilai_akhir <= $key['nilai_maksimum'])) {
                                    $ket = strtolower($key['keterangan']);
                                    if ($ket !== 'lulus') {
                                        $res['st'][] = false;
                                        $re['res'][$n]['data'] = $row->id_matakuliah_syarat;
                                        $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                                        $re['res'][$n]['msg'] = 'Anda harus lulus matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';
                                    } else {
                                        $res['st'][] = true;
                                    }
                                }
                            }
                        } else {
                            $lebih = $this->mahasiswaservice->getLebihKrsDetail($nim, $row->id_matakuliah_syarat);
                            foreach ($data_penilaian as $key) {
                                if (($key['nilai_minimum'] <= $lebih->nilai_akhir) && ($lebih->nilai_akhir <= $key['nilai_maksimum'])) {
                                    $ket = strtolower($key['keterangan']);
                                    if ($ket !== 'lulus') {
                                        $res['st'][] = false;
                                        $re['res'][$n]['data'] = $lebih;
                                        $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                                        $re['res'][$n]['msg'] = 'Anda harus lulus matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';
                                    } else {
                                        $res['st'][] = true;
                                    }
                                }
                            }
                        }
                        //                            echo json_encode($res);
                    } else {
                        $res['st'][] = true;
                        //                            echo json_encode($res);
                    }
                }
                $n++;
            }
            if (count(array_unique($res['st'])) === 1) {
                if (current($res['st']) == true) {
                    $re['status'] = true;
                } else {
                    $re['status'] = false;
                }
            } else {
                $re['status'] = false;
            }
            echo json_encode($re);
        } else {
            $re['status'] = true;
            echo json_encode($re);
        }

    }
    // ===================================================================
    public function cek_prasyarat()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        if ($this->status_pendaftaran == 'B') {
            $data_krs = $this->Krs_model->get_kodemk_krs($nim);
        } else {
            $data_krs = $this->Krs_model->get_kodemk_krs_sudah_ambil($nim);
        }
        $id_matakuliah = $this->input->post('id_matakuliah');
        $matakuliah_yg_diambil = $this->Krs_model->get_kodemk_diambil($kode_nama_kurikulum);

        //Sistem penilaian
        $get_pra = $this->mahasiswaservice->getMatakuliahPrasyaratSelect($kode_nama_kurikulum, $id_matakuliah);
        

        if ($get_pra) {
            $re['pra'] = false;
            $re['mak_ambil'] = $get_pra->matakuliah_yg_diambil;
            //            echo json_encode($re);
        }
        if (stup_grade($kode_nama_kurikulum, $this->semester_saat_ini())) {
            $data_penilaian = stup_grade($kode_nama_kurikulum, $this->semester_saat_ini());
        } else {
            //            $data_penilaian = $this->sistem_penilaian($nim);
            $data_penilaian = sistem_penilaian($nim);
        }
        // $re['semester'] = $this->semester;
        $matakuliah_2 = $this->mahasiswaservice->getSemesterFromKurikulum($kode_nama_kurikulum, $id_matakuliah);
        $mk_semester = !empty($matakuliah_2) ? $matakuliah_2->semester : 0;
        $semester = $this->semester;
        $mhs = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);
        $status_pen = !empty($mhs) ? $mhs->status_pendaftaran : '';
        if ($semester == 2 && $mk_semester != 2 && $status_pen != 'T') {
        //if (false) {
            //$re['data_mahasiswa'] = ;
            $re['semester'] = 2;
            $re['status'] = false;
            echo json_encode($re);
        }else{
            if (in_array($id_matakuliah, $matakuliah_yg_diambil)) {
                $cek = $this->mahasiswaservice->getMatakuliahPrasyaratById($id_matakuliah, $kode_nama_kurikulum);
                $n = 0;
                $res = array('st' => array());
                foreach ($cek as $row) {
                    if (!in_array($row->id_matakuliah_syarat, $data_krs)) {
                        if ($row->jenis_prasyarat == 'LA') {
                            $res['st'][] = false;
                            $re['res'][$n]['la'] = false;
                            $re['res'][$n]['kode_prasyarat'] = $row->id_matakuliah_syarat;
                            $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                            $re['res'][$n]['msg'] = 'Anda harus memilih matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';

                        } else {
                            $res['st'][] = false;
                            $re['res'][$n]['data'] = $row->id_matakuliah_syarat;
                            $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                            $re['res'][$n]['msg'] = 'Anda harus sudah mengambil matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';

                        }
                        //                        echo json_encode($res);
                    } else {
                        //Cek jumlah matakuliah
                        $mak = $this->mahasiswaservice->getMakKrsDetail($nim, $row->id_matakuliah_syarat);

                        if ($row->jenis_prasyarat == 'LU') {
                            if ($mak->jumlah == 1) {
                                foreach ($data_penilaian as $key) {
                                    if (($key['nilai_minimum'] <= $mak->nilai_akhir) && ($mak->nilai_akhir <= $key['nilai_maksimum'])) {
                                        $ket = strtolower($key['keterangan']);
                                        if ($ket !== 'lulus') {
                                            $res['st'][] = false;
                                            $re['res'][$n]['data'] = $row->id_matakuliah_syarat;
                                            $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                                            $re['res'][$n]['msg'] = 'Anda harus lulus matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';
                                        } else {
                                            $res['st'][] = true;
                                        }
                                    }
                                }
                            } else {
                                $lebih = $this->mahasiswaservice->getLebihKrsDetail($nim, $row->id_matakuliah_syarat);
                                foreach ($data_penilaian as $key) {
                                    if (($key['nilai_minimum'] <= $lebih->nilai_akhir) && ($lebih->nilai_akhir <= $key['nilai_maksimum'])) {
                                        $ket = strtolower($key['keterangan']);
                                        if ($ket !== 'lulus') {
                                            $res['st'][] = false;
                                            $re['res'][$n]['data'] = $lebih;
                                            $res['nama_makul'] = $this->m_matakuliah->get_nama_matakuliah($row->id_matakuliah_syarat);
                                            $re['res'][$n]['msg'] = 'Anda harus lulus matakuliah <strong>' . $res['nama_makul'] . '</strong> untuk mengambil matakuliah ini';
                                        } else {
                                            $res['st'][] = true;
                                        }
                                    }
                                }
                            }
                            //                            echo json_encode($res);
                        } else {
                            $res['st'][] = true;
                            //                            echo json_encode($res);
                        }
                    }
                    $n++;
                }
                if (count(array_unique($res['st'])) === 1) {
                    if (current($res['st']) == true) {
                        $re['status'] = true;
                    } else {
                        $re['status'] = false;
                    }
                } else {
                    $re['status'] = false;
                }
                //echo json_encode($re);
            } else {
                $re['status'] = true;
            }
        echo json_encode($re);
        }
       
    }

    public function maksimum_sks()
    {
        $nim = $this->session->userdata('nim');
		$ta = $this->m_tahun_akademik->get_aktif();
        $kode_jenjang = substr($nim, 4, 1);
        $kode_jurusan = substr($nim, 2, 2);
        $angkatan = substr($nim, 0, 2);

        $kode_program_studi = $this->session->userdata('kode_program_studi');
       $khs_lama = $this->mahasiswaservice->getKhsLamaMaksimumSks($nim, $ta);
      
       $data_penilaian = $khs_lama ? data_penilaian($nim, $khs_lama['semester']) : [];
        //        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        //$data_penilaian = data_penilaian($nim, $this->semester - 1);
        //        if (stup_grade($kode_nama_kurikulum, $this->semester-1))
//        {
//            $data_penilaian = stup_grade($kode_nama_kurikulum, $this->semester-1);
//        }else{
//            $data_penilaian = sistem_penilaian($nim);
//        }
        if ($this->semester !== 1) {
            if ($this->semester >= 2 && $this->status_pendaftaran !== 'B' && !empty($khs_lama)) {
                $tahun_akademik =$khs_lama['kode_tahun_akademik'];
                // $status_p = $this->select('*')->from('status_perkuliahan')->get()->row();
                // if ($status_p->status_perkuliahan == 'c') {
                //     $tahun_akademik = $tahun_akademik - 1;
                // }
                //                $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);
                //$kode_kr = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                //if ($kode_kr == 0) {
                //    $kode_krs = $this->Krs_model->get_krs_konversi($nim);
                //} else {
                //    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                //}
                //if (!$kode_krs) {
                //    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik-1);
                //}
                //Generate
//                $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
                $data_krs = $this->Khs_model->khs($khs_lama['kode_krs']);

                $khs['sksn'] = 0;
                $khs['total_sks'] = 0;
                $khs['total_bobot'] = 0;
                $sksn = 0;
                $sks = 0;
                $i = 0;
                foreach ($data_krs as $row) {
                    $khs['nim'] = $row->nim;
                    $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                    $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                    $khs['semester'] = $row->semester;
                    $khs['kurikulum'] = nama_kurikulum_nama($nim);
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
                    //                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $khs['data_nilai'][$i]['grade'] = $key['grade'];
                            $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                        }
                    }
                    $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                    $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                    $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                    $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                    $i++;
                }
                if ($sks == 0) {
                    $ipk_semester_lalu = 0;
                } else {
                    $ipk_semester_lalu = $sksn / $sks;
                }
                if ($ipk_semester_lalu >= 3.5) {
                    $jumlah_maksimum_sks = 24;
                } elseif ($ipk_semester_lalu >= 3.25) {
                    $jumlah_maksimum_sks = 23;
                } elseif ($ipk_semester_lalu >= 3) {
                    $jumlah_maksimum_sks = 22;
                } elseif ($ipk_semester_lalu >= 2.75) {
                    $jumlah_maksimum_sks = 21;
                } elseif ($ipk_semester_lalu >= 2.5) {
                    $jumlah_maksimum_sks = 20;
                } elseif ($ipk_semester_lalu >= 2.25) {
                    $jumlah_maksimum_sks = 19;
                } elseif ($ipk_semester_lalu >= 2) {
                    $jumlah_maksimum_sks = 18;
                } elseif ($ipk_semester_lalu >= 1.75) {
                    $jumlah_maksimum_sks = 16;
                } elseif ($ipk_semester_lalu >= 1.5) {
                    $jumlah_maksimum_sks = 14;
                } else {
                    $jumlah_maksimum_sks = 12;
                }
                $data['ip_semester_lalu'] = $ipk_semester_lalu;
                $data['beban_sks'] = $jumlah_maksimum_sks;

                return $data;
            } else {
                // Gunakan khs_lama (query all KRS sebelum TA aktif) untuk cari KRS terakhir
                $data_krs = [];
                if ($khs_lama && !empty($khs_lama['kode_krs'])) {
                    $data_krs = $this->Khs_model->khs($khs_lama['kode_krs']);
                }

                $khs['sksn'] = 0;
                $khs['total_sks'] = 0;
                $khs['total_bobot'] = 0;
                $sksn = 0;
                $sks = 0;
                $i = 0;
                foreach ($data_krs as $row) {
                    $khs['nim'] = $row->nim;
                    $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                    $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                    $khs['semester'] = $row->semester;
                    $khs['kurikulum'] = nama_kurikulum_nama($nim);
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
                    //                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $khs['data_nilai'][$i]['grade'] = $key['grade'];
                            $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                        }
                    }
                    $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                    $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                    $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                    $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                    $i++;
                }
                if ($sks == 0) {
                    $ipk_semester_lalu = 0;
                } else {
                    $ipk_semester_lalu = $sksn / $sks;
                }
                if ($ipk_semester_lalu >= 3.5) {
                    $jumlah_maksimum_sks = 24;
                } elseif ($ipk_semester_lalu >= 3.25) {
                    $jumlah_maksimum_sks = 23;
                } elseif ($ipk_semester_lalu >= 3) {
                    $jumlah_maksimum_sks = 22;
                } elseif ($ipk_semester_lalu >= 2.75) {
                    $jumlah_maksimum_sks = 21;
                } elseif ($ipk_semester_lalu >= 2.5) {
                    $jumlah_maksimum_sks = 20;
                } elseif ($ipk_semester_lalu >= 2.25) {
                    $jumlah_maksimum_sks = 19;
                } elseif ($ipk_semester_lalu >= 2) {
                    $jumlah_maksimum_sks = 18;
                } elseif ($ipk_semester_lalu >= 1.75) {
                    $jumlah_maksimum_sks = 16;
                } elseif ($ipk_semester_lalu >= 1.5) {
                    $jumlah_maksimum_sks = 14;
                } else {
                    $jumlah_maksimum_sks = 12;
                }
                $data['ip_semester_lalu'] = $ipk_semester_lalu;
                $data['beban_sks'] = $jumlah_maksimum_sks;

                return $data;
            }
        } elseif ($this->semester == 1 && $this->status_pendaftaran !== 'B') {
            $tahun_akademik = $this->m_tahun_akademik->get_aktif();
            $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);

            //Generate
//            $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
            $data_krs = $this->Khs_model->khs($kode_krs);

            $khs['sksn'] = 0;
            $khs['total_sks'] = 0;
            $khs['total_bobot'] = 0;
            $sksn = 0;
            $sks = 0;
            $i = 0;
            foreach ($data_krs as $row) {
                $khs['nim'] = $row->nim;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = nama_kurikulum_nama($nim);
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['sks'] = $row->sks;
                //                $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                $nilai_akhir = $row->nilai_akhir * 1;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $khs['data_nilai'][$i]['grade'] = $key['grade'];
                        $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                    }
                }
                $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                $sks = $sks + $khs['data_nilai'][$i]['sks'];
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                $i++;
            }
            if ($sks == 0) {
                $ipk_semester_lalu = 0;
            } else {
                $ipk_semester_lalu = $sksn / $sks;
            }
            if ($ipk_semester_lalu >= 3.5) {
                $jumlah_maksimum_sks = 24;
            } elseif ($ipk_semester_lalu >= 3.25) {
                $jumlah_maksimum_sks = 23;
            } elseif ($ipk_semester_lalu >= 3) {
                $jumlah_maksimum_sks = 22;
            } elseif ($ipk_semester_lalu >= 2.75) {
                $jumlah_maksimum_sks = 21;
            } elseif ($ipk_semester_lalu >= 2.5) {
                $jumlah_maksimum_sks = 20;
            } elseif ($ipk_semester_lalu >= 2.25) {
                $jumlah_maksimum_sks = 19;
            } elseif ($ipk_semester_lalu >= 2) {
                $jumlah_maksimum_sks = 18;
            } elseif ($ipk_semester_lalu >= 1.75) {
                $jumlah_maksimum_sks = 16;
            } elseif ($ipk_semester_lalu >= 1.5) {
                $jumlah_maksimum_sks = 14;
            } else {
                $jumlah_maksimum_sks = 12;
            }
            $data['ip_semester_lalu'] = $ipk_semester_lalu;
            $data['beban_sks'] = $jumlah_maksimum_sks;

            return $data;
        }
    }

    public function semester_saat_ini()
    {
        $nim = $this->session->userdata('nim');
        $tahun_angkatan = substr($nim, 0, 2);
        $tahun = $this->m_tahun_akademik->get_semester();
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;
        //        $tidak_aktif = $this->db->select('*')
//            ->from('status_perkuliahan')
//            ->where('nim', $nim)
//            ->where_not_in('status_perkuliahan', 'A')
//            ->get()->result();
//        $dec_semester = count($tidak_aktif);

        if ($sem == 0) {
            # code...
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }
		if (substr($nim, 2, 4) == "0108") {
            if(substr($nim, 0, 2) == "24" && substr($nim, 9, 2) > 7  ){
                return ($semester - 1);
            }
          	if(substr($nim, 0, 2) == "25" && substr($nim, 9, 2) > 9  ){
                return ($semester - 1);
            }
        }
      	if (substr($nim, 2, 4) == "0402") {
            if(substr($nim, 0, 2) == "24" && substr($nim, 9, 2) > 3  ){
                return ($semester - 1);
            }
          	if(substr($nim, 0, 2) == "25" && substr($nim, 9, 2) > 5  ){
                return ($semester - 1);
            }
        }
        return $semester;
    }

    public function get_krs_matakuliah_pilihan($kode_kompetensi, $matakuliah_pilihan)
    {
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $matakuliah_wajib = $this->m_data_kurikulum->get_krs_matakuliah_wajib($this->session->userdata('kode_nama_kurikulum'), $tahun_akademik->semester);
        $matakuliah_pilihan = $this->m_data_kurikulum->get_data_kurikulum_krs_pilihan($this->session->userdata('kode_nama_kurikulum'), $tahun_akademik->semester, $kode_kompetensi);
        $i = 1;
        $j = 0;
        $k = 0;
        foreach ($matakuliah_wajib as $item) {
            $semester = $item['semester'];
            $data[$i]['semester'] = $semester;
            foreach ($item['data'] as $makul) {
                $data[$i]['data'][$j]['nama_matakuliah'] = $makul['nama_matakuliah'];
                $data[$i]['data'][$j]['kode_matakuliah'] = $makul['kode_matakuliah'];
                $data[$i]['data'][$j]['kode_nama_kurikulum'] = $makul['kode_nama_kurikulum'];
                $data[$i]['data'][$j]['sks_teori'] = $makul['sks_teori'];
                $data[$i]['data'][$j]['sks_praktek'] = $makul['sks_praktek'];
                $data[$i]['data'][$j]['sks_praktikum'] = $makul['sks_praktikum'];
                $j++;
                $k = $j;
                foreach ($matakuliah_pilihan as $item_pilih) {
                    if ($semester == $item_pilih['semester']) {
                        foreach ($item_pilih['data'] as $mak_pilihan) {
                            $data[$i]['data'][$k]['nama_matakuliah'] = $mak_pilihan['nama_matakuliah'];
                            $data[$i]['data'][$k]['kode_matakuliah'] = $mak_pilihan['kode_matakuliah'];
                            $data[$i]['data'][$k]['kode_nama_kurikulum'] = $mak_pilihan['kode_nama_kurikulum'];
                            $data[$i]['data'][$k]['sks_teori'] = $mak_pilihan['sks_teori'];
                            $data[$i]['data'][$k]['sks_praktek'] = $mak_pilihan['sks_praktek'];
                            $data[$i]['data'][$k]['sks_praktikum'] = $mak_pilihan['sks_praktikum'];
                            $k++;
                        }
                    }
                }
            }
            $j = 0;
            $k = 0;
            $i++;
        }
        return $data;
    }

    function coba_krs_pilihan($kode_kompetensi = null)
    {
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        if ($kode_kompetensi == null) {
            $matakuliah_pilihan = $this->m_data_kurikulum->get_data_kurikulum_krs_pilihan($this->session->userdata('kode_nama_kurikulum'), $tahun_akademik->semester);
        } else {
            $komp = $this->mahasiswaservice->getKompetensiByKode($kode_kompetensi);
            $kode_mk_pilihan = $komp->matakuliah_pilihan;
            $matakuliah_pilihan = $this->m_data_kurikulum->get_data_kurikulum_krs_pilihan($this->session->userdata('kode_nama_kurikulum'), $tahun_akademik->semester, $kode_kompetensi, $kode_mk_pilihan);
        }
        $matakuliah_wajib = $this->m_data_kurikulum->get_krs_matakuliah_wajib($this->session->userdata('kode_nama_kurikulum'), $tahun_akademik->semester);

        $i = 1;
        $j = 0;
        $k = 0;
        foreach ($matakuliah_wajib as $item) {
            $semester = $item['semester'];
            $data[$i]['semester'] = $semester;
            foreach ($item['data'] as $makul) {
                $data[$i]['data'][$j]['nama_matakuliah'] = $makul['nama_matakuliah'];
                $data[$i]['data'][$j]['kode_matakuliah'] = $makul['kode_matakuliah'];
                $data[$i]['data'][$j]['id_matakuliah'] = $makul['id_matakuliah'];
                $data[$i]['data'][$j]['kode_nama_kurikulum'] = $makul['kode_nama_kurikulum'];
                $data[$i]['data'][$j]['sks_teori'] = $makul['sks_teori'];
                $data[$i]['data'][$j]['sks_praktek'] = $makul['sks_praktek'];
                $data[$i]['data'][$j]['sks_praktikum'] = $makul['sks_praktikum'];
                $j++;

            }
            $mk_pilihan = $matakuliah_pilihan[$semester]['data'];
            if (!empty($mk_pilihan)) {
                foreach ($mk_pilihan as $pilih) {
                    $data[$i]['pilihan'][$k]['nama_matakuliah'] = $pilih['nama_matakuliah'];
                    $data[$i]['pilihan'][$k]['id_matakuliah'] = $pilih['id_matakuliah'];
                    $data[$i]['pilihan'][$k]['kode_matakuliah'] = $pilih['kode_matakuliah'];
                    $data[$i]['pilihan'][$k]['kode_nama_kurikulum'] = $pilih['kode_nama_kurikulum'];
                    $data[$i]['pilihan'][$k]['sks_teori'] = $pilih['sks_teori'];
                    $data[$i]['pilihan'][$k]['sks_praktek'] = $pilih['sks_praktek'];
                    $data[$i]['pilihan'][$k]['sks_praktikum'] = $pilih['sks_praktikum'];
                    $k++;
                }
            }
            $i++;
        }
        return $data;
        //        echo "<pre>s";
//        print_r($komp);
    }

    public function status_perkuliahan()
    {
        $nim = $this->session->userdata('nim');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();

        return $this->Status_perkuliahan_model->cek_status_aktif_mahasiswa($nim, $kode_tahun_akademik);
    }

    public function simpan_ubah()
    {
        $krs_lalu = $this->Krs_model->get_kodemk_krs($this->session->userdata('nim'));
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_krs = $this->Krs_model->get_kode_krs($this->session->userdata('nim'), $kode_tahun_akademik);


        $total_sks_dipilih = $this->input->post('total_sks_dipilih');
        $beban = $this->maksimum_sks();
        $beban_sekarang = $beban['beban_sks'];
        $nim = $this->session->userdata('nim');

        $total_sks_server = 0;
        $data_baru = $this->input->post('baru');
        $data_ulang = $this->input->post('ulang');
        if (is_array($data_baru)) {
            foreach ($data_baru as $kode_mk) {
                $mak = explode(",", $kode_mk);
                if (isset($mak[2])) $total_sks_server += (int)$mak[2];
            }
        }
        if (is_array($data_ulang)) {
            foreach ($data_ulang as $kode_mk) {
                $mak = explode(",", $kode_mk);
                if (isset($mak[2])) $total_sks_server += (int)$mak[2];
            }
        }

        $tahun_angkatan = substr($nim, 0, 2);
        $tahun = $this->m_tahun_akademik->get_semester();
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;

        if ($sem == 0) {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }
        if ($total_sks_server > $beban_sekarang) {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Gagal","Jumlah SKS Matakuliah yang anda pilih lebih besar dari beban yang di berikan","error")</script>'
            );
            redirect('mahasiswa/krs/edit_krs');

        } else {
            $this->Krs_model->hapus($kode_krs);
            //        Simpan Data KRS
            $data_krs = array(
                'kode_tahun_akademik' => $kode_tahun_akademik,
                'nim' => $nim,
                'semester' => $semester,
            );

            $kode_krs = $this->Krs_model->simpan_krs($data_krs);
            //        Simpan data Khs
            if ($kode_krs !== null) {
                $data_khs = array(
                    'kode_krs' => $kode_krs,
                );
                $this->Khs_model->simpan_khs($data_khs);
                //        Simpan Data Krs_Detail
                if (is_array($data_baru)):
                foreach ($data_baru as $kode_mk) {
                    $mak = explode(',', $kode_mk);
                    if (in_array($mak[0], $krs_lalu)):
                        $status = 'U';
                    else:
                        $status = 'B';
                    endif;
                    $data_krs_detail = array(
                        'kode_krs' => $kode_krs,
                        'id_matakuliah' => $mak[0],
                        'status' => $status,
                    );

                    $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                    $data_khs_detail = array(
                        'kode_krs_detail' => $kode_krs_detail,
                    );

                    $this->Krs_detail_model->simpan_khs($data_khs_detail);
                }
                endif;

                if (!empty($data_ulang)):
                    foreach ($data_ulang as $kode_mk) {
                        $mak = explode(',', $kode_mk);
                        if (in_array($mak[0], $krs_lalu)):
                            $status = 'U';
                        else:
                            $status = 'B';
                        endif;
                        $data_krs_detail = array(
                            'kode_krs' => $kode_krs,
                            'id_matakuliah' => $mak[0],
                            'status' => $status,
                        );

                        $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                        $data_khs_detail = array(
                            'kode_krs_detail' => $kode_krs_detail,
                        );

                        $this->Krs_detail_model->simpan_khs($data_khs_detail);
                    }

                endif;

                redirect('mahasiswa/krs');
            }
        }

    }

    public function matakuliah_awal()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');

        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $dosen_wali = $this->Perwalian_model->get_perwalian_by_nim($this->session->userdata('nim'));
        $matakuliah_awal = $this->m_data_kurikulum->get_matakuliah_awal($kode_nama_kurikulum);
        $data['judul'] = "KRS Semester " . $this->semester;
        $data['prodi'] = $this->Nama_jurusan_model->get_all_byid($this->session->userdata('kode_program_studi'));
        if (!empty($data['prodi'])) {
            $data['jenjang'] = $this->Jenjang_model->get_nama($data['prodi']->id_jenjang);
        } else {
            $data['jenjang'] = '';
        }
        $data['nama_mahasiswa'] = $this->session->userdata('nama_mahasiswa');
        $data['dosen_wali'] = !empty($dosen_wali) && is_object($dosen_wali) ? $dosen_wali->nama_dosen : '-';
        $data['nim'] = $this->session->userdata('nim');
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($tahun_akademik);
        $data['matakuliah_awal'] = $matakuliah_awal;
        $data['conten'] = 'mahasiswa/V_Krs_one';

        $this->load->view('mahasiswa/template/V_main', $data);

    }

    //    fungsi untuk menyimpan matakuliah awal mahasiswa.
    public function add_one()
    {
        $nim = $this->session->userdata('nim');
        $matakuiah_awal = $this->input->post('id_matakuliah');
        $semester = $this->semester;
        $tahun = $this->m_tahun_akademik->get_semester();
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;
        //        Simpan Data KRS
        $data_krs = array(
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'nim' => $nim,
            'semester' => $semester,
        );

        $kode_krs = $this->Krs_model->simpan_krs($data_krs);
        //        Simpan data Khs
        if ($kode_krs !== null) {
            $data_khs = array(
                'kode_krs' => $kode_krs,
            );
            $this->Khs_model->simpan_khs($data_khs);
            //        Simpan Data Krs_Detail
            foreach ($matakuiah_awal as $key => $kode_mk) {
                $data_krs_detail = array(
                    'kode_krs' => $kode_krs,
                    'id_matakuliah' => $kode_mk,
                );

                $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                $data_khs_detail = array(
                    'kode_krs_detail' => $kode_krs_detail,
                );

                $this->Krs_detail_model->simpan_khs($data_khs_detail);
            }

            redirect('mahasiswa/krs');
        }
    }

    public function cek_makul_transfer()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulium = $this->session->userdata('kode_nama_kurikulum');

        $jml_makul_wajib = $this->m_data_kurikulum->get_jml_makul_wajib($kode_nama_kurikulium);
        $jml_makul_sebelum = $this->Krs_model->get_jml_makul_transfer($nim);

        if ($jml_makul_sebelum >= $jml_makul_wajib) {
            return true;
        } else {
            return false;
        }

    }

    public function cek_kuisioner()
    {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $nim = $this->session->userdata('nim');
        $status_kuisioner = $this->kuisioner_model->get_setting();
        $cek_pengisian = $this->kuisioner_model->get_matakuliah_kuisioner($nim, $kode_tahun_akademik);
        //        $cek_pengisian = $this->kuisioner_model->get_matakuliah_kuisioner($nim);
        $axis_layanan = $this->kuisioner_model->layanan_axis($nim);
        //        sementera
        // TODO::cek pengisian quisioner mahasiswa baru krs
         if ($status_kuisioner == 'A' && !$this->mahasiswaservice->isMahasiswaBaru($nim)) {
//            sementara
        //if ($status_kuisioner == 'A') {
            if (!empty($cek_pengisian) || !$axis_layanan) {
                //            if (count($cek_pengisian) > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="callout callout-info">
                    <h4><i class="fa fa-info-circle"></i> Information!</h4>
                    <p>Silahkan melakukan pengisian kuisioner proses belajar mengajar (PBM) dan kuisioner kepuasan pelayanan untuk bisa melakukan pengaksesan <strong>KRS</strong> .</p>
                    </div>'
                );

                redirect(site_url('mahasiswa/kuisioner'));
            }
            if (block($nim)) {
                $this->session->set_flashdata('info', '<div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Perhatian!</h4>

                <p><span style="font-size: 12pt"> Anda tidak bisa mengakses halaman ini, Silahkan hubungi bagian <b>Keuangan</b> terkait dengan pembayaran yang mungking belum anda bayar. Adapun kemungkinan pembayaran yang belum anda lunasi sebagai berikut</span></p>
                <ul>
                    <li>Pembayaran DPP</li>
                    <li>Dispensaisi Pembayaran SPP</li>
                    <li>Dispensaisi Pembayaran SKS</li>
                    <li>DLL.</li>
                </ul>
                <p style="font-size: 12pt">Untuk info lebih jelasnya silahkan hubungi baigian <b>Keuangan</b>. Terimakasih.</p>
              </div>');

                redirect('home/Access_denied');
                // $data['conten'] = "mahasiswa/V_Krs_denied";
                // $this->load->view('mahasiswa/template/V_main', $data);
            }
        }
    }

    public function cetak()
    {
        $nim = $this->session->userdata('nim');
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_jurusan = substr($nim, 2, 2);
        $kode_jenjang = substr($nim, 4, 1);
        $data['kode_jenjang'] = $kode_jenjang;
        $mahasiswa = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);

            $data['beban_sks'] = $this->maksimum_sks();
            $data['kajur'] = $this->Ketua_jurusan_model->get_kaprodi(get_kode_prodi($nim)->kode_program_studi);
            $data['jurusan'] = get_kode_prodi($nim);
            $semester = $this->semester;
            $data['semester'] = $semester;
            $data['krs_mahasiswa'] = $this->Krs_model->get_krs_mahasiswa_by_nim($nim, $semester);
            $data['krs_matakuliah'] = $this->Krs_model->get_krs_matakuliah_by_nim_semester($nim, $semester);

            $header = $this->load->view('admin/akademik/krs/V_header_krs', $data, TRUE);
            $content = $this->load->view('admin/akademik/krs/V_cetak_krs', $data, true);

            $nama_mahasiswa = $mahasiswa->nama_mahasiswa;

            $this->load->library('pdf');
            $this->pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 42, 'margin_bottom' => 20, 'margin_header' => 3, 'margin_footer' => 3]);
            $mpdf = $this->pdf;
            $mpdf->SetHeader($header);
            $mpdf->WriteHTML($content);
            $mpdf->defaultheaderline = true;
            $mpdf->Output("KRS - $nim - $nama_mahasiswa - Semester $semester.pdf", 'D');

    }

    public function cetak_lalu($tahun_akademik, $semester)
    {
        $nim = $this->session->userdata('nim');
        $kode_jurusan = substr($nim, 2, 2);
        $kode_jenjang = substr($nim, 4, 1);
        $data['kode_jenjang'] = $kode_jenjang;
        $mahasiswa = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);
        $prodi = get_kode_prodi($nim);
        if (!$mahasiswa || !$prodi) {
            $this->session->set_flashdata('info', '<div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Error!</h4>
                <p>Data mahasiswa atau program studi tidak ditemukan.</p>
              </div>');
            redirect('mahasiswa/krs');
        }

            $data['beban_sks'] = $this->maksimum_sks_lalu($tahun_akademik, $semester);
            $data['kajur'] = $this->Ketua_jurusan_model->get_kaprodi($prodi->kode_program_studi);
            $data['jurusan'] = $prodi;
            $data['semester'] = $semester;
            $data['krs_mahasiswa'] = $this->Krs_model->get_krs_mahasiswa_by_nim($nim, $semester);
            $data['krs_matakuliah'] = $this->Krs_model->get_krs_matakuliah_by_nim_semester($nim, $semester);

            $header = $this->load->view('admin/akademik/krs/V_header_krs', $data, TRUE);
            $content = $this->load->view('admin/akademik/krs/V_cetak_krs', $data, true);

            $nama_mahasiswa = $mahasiswa->nama_mahasiswa;

            $this->load->library('pdf');
            $this->pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 42, 'margin_bottom' => 20, 'margin_header' => 3, 'margin_footer' => 3]);
            $mpdf = $this->pdf;
            $mpdf->SetHeader($header);
            $mpdf->WriteHTML($content);
            $mpdf->defaultheaderline = true;
            $mpdf->Output("KRS - $nim - $nama_mahasiswa - Semester $semester.pdf", 'D');

        }

    public function cek_nik()
    {
        $nim = $this->session->userdata('nim');
        //      $nim = "180998899";
        $tahun_akademik = tahun_akademik();
        $c1 = substr($nim, 0, 2);
        $c2 = substr($tahun_akademik->tahun_akademik, 2, 2);
        if ($c1 == $c2) {
            $nik = $this->mahasiswaservice->getNikTeleponEmail($nim);
            // echo json_encode($nik);break;
            $panjang = strlen($nik->nik);
            $number = is_numeric($nik->nik);
            $ln_telepon = strlen($nik->telepon);
            $is_telepon = is_numeric($nik->telepon);
            $is_email = filter_var($nik->email, FILTER_VALIDATE_EMAIL);
            if (($panjang == 16) && $number && ($ln_telepon > 8) && $is_telepon && $is_email) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function maksimum_sks_lalu($tahun_akademik, $semester)
    {
        // return $tahun_akademik;
        $nim = $this->session->userdata('nim');

        $kode_jenjang = substr($nim, 4, 1);
        $kode_jurusan = substr($nim, 2, 2);
        $angkatan = substr($nim, 0, 2);

        $kode_program_studi = $this->session->userdata('kode_program_studi');
        //        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data_penilaian = data_penilaian($nim, $semester - 1);
        //        if (stup_grade($kode_nama_kurikulum, $this->semester-1))
//        {
//            $data_penilaian = stup_grade($kode_nama_kurikulum, $this->semester-1);
//        }else{
//            $data_penilaian = sistem_penilaian($nim);
//        }
        if ($semester !== 1) {
            if ($semester >= 2 && $this->status_pendaftaran !== 'B') {
                $tahun_akademik = $tahun_akademik - 1;
                //                $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);
                $kode_kr = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                if ($kode_kr == 0) {
                    $kode_krs = $this->Krs_model->get_krs_konversi($nim);
                } else {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                }

                //Generate
//                $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
                $data_krs = $this->Khs_model->khs($kode_krs);

                $khs['sksn'] = 0;
                $khs['total_sks'] = 0;
                $khs['total_bobot'] = 0;
                $sksn = 0;
                $sks = 0;
                $i = 0;
                foreach ($data_krs as $row) {
                    $khs['nim'] = $row->nim;
                    $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                    $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                    $khs['semester'] = $row->semester;
                    $khs['kurikulum'] = nama_kurikulum_nama($nim);
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
                    //                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $khs['data_nilai'][$i]['grade'] = $key['grade'];
                            $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                        }
                    }
                    $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                    $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                    $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                    $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                    $i++;
                }
                if ($sks == 0) {
                    $ipk_semester_lalu = 0;
                } else {
                    $ipk_semester_lalu = $sksn / $sks;
                }
                if ($ipk_semester_lalu >= 3.5) {
                    $jumlah_maksimum_sks = 24;
                } elseif ($ipk_semester_lalu >= 3.25) {
                    $jumlah_maksimum_sks = 23;
                } elseif ($ipk_semester_lalu >= 3) {
                    $jumlah_maksimum_sks = 22;
                } elseif ($ipk_semester_lalu >= 2.75) {
                    $jumlah_maksimum_sks = 21;
                } elseif ($ipk_semester_lalu >= 2.5) {
                    $jumlah_maksimum_sks = 20;
                } elseif ($ipk_semester_lalu >= 2.25) {
                    $jumlah_maksimum_sks = 19;
                } elseif ($ipk_semester_lalu >= 2) {
                    $jumlah_maksimum_sks = 18;
                } elseif ($ipk_semester_lalu >= 1.75) {
                    $jumlah_maksimum_sks = 16;
                } elseif ($ipk_semester_lalu >= 1.5) {
                    $jumlah_maksimum_sks = 14;
                } else {
                    $jumlah_maksimum_sks = 12;
                }
                $data['ip_semester_lalu'] = $ipk_semester_lalu;
                $data['beban_sks'] = $jumlah_maksimum_sks;

                return $data;
            } else {
                $tahun_akademik = $tahun_akademik - 1;
                //                $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                for ($x = 0; $x <= 3; $x++) {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                    if ($kode_krs > 0) {
                        break;
                    } else {
                        $tahun_akademik = $tahun_akademik - 1;
                    }
                }

                //Generate
//                $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
                $data_krs = $this->Khs_model->khs($kode_krs);

                $khs['sksn'] = 0;
                $khs['total_sks'] = 0;
                $khs['total_bobot'] = 0;
                $sksn = 0;
                $sks = 0;
                $i = 0;
                foreach ($data_krs as $row) {
                    $khs['nim'] = $row->nim;
                    $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                    $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                    $khs['semester'] = $row->semester;
                    $khs['kurikulum'] = nama_kurikulum_nama($nim);
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
                    //                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $khs['data_nilai'][$i]['grade'] = $key['grade'];
                            $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                        }
                    }
                    $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                    $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                    $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                    $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                    $i++;
                }
                if ($sks == 0) {
                    $ipk_semester_lalu = 0;
                } else {
                    $ipk_semester_lalu = $sksn / $sks;
                }
                if ($ipk_semester_lalu >= 3.5) {
                    $jumlah_maksimum_sks = 24;
                } elseif ($ipk_semester_lalu >= 3.25) {
                    $jumlah_maksimum_sks = 23;
                } elseif ($ipk_semester_lalu >= 3) {
                    $jumlah_maksimum_sks = 22;
                } elseif ($ipk_semester_lalu >= 2.75) {
                    $jumlah_maksimum_sks = 21;
                } elseif ($ipk_semester_lalu >= 2.5) {
                    $jumlah_maksimum_sks = 20;
                } elseif ($ipk_semester_lalu >= 2.25) {
                    $jumlah_maksimum_sks = 19;
                } elseif ($ipk_semester_lalu >= 2) {
                    $jumlah_maksimum_sks = 18;
                } elseif ($ipk_semester_lalu >= 1.75) {
                    $jumlah_maksimum_sks = 16;
                } elseif ($ipk_semester_lalu >= 1.5) {
                    $jumlah_maksimum_sks = 14;
                } else {
                    $jumlah_maksimum_sks = 12;
                }
                $data['ip_semester_lalu'] = $ipk_semester_lalu;
                $data['beban_sks'] = $jumlah_maksimum_sks;

                return $data;
            }
        } elseif ($this->semester == 1 && $this->status_pendaftaran !== 'B') {
            $tahun_akademik = $this->m_tahun_akademik->get_aktif();
            $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);

            //Generate
//            $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
            $data_krs = $this->Khs_model->khs($kode_krs);

            $khs['sksn'] = 0;
            $khs['total_sks'] = 0;
            $khs['total_bobot'] = 0;
            $sksn = 0;
            $sks = 0;
            $i = 0;
            foreach ($data_krs as $row) {
                $khs['nim'] = $row->nim;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = nama_kurikulum_nama($nim);
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['sks'] = $row->sks;
                //                $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                $nilai_akhir = $row->nilai_akhir * 1;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $khs['data_nilai'][$i]['grade'] = $key['grade'];
                        $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                    }
                }
                $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                $sks = $sks + $khs['data_nilai'][$i]['sks'];
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                $i++;
            }
            if ($sks == 0) {
                $ipk_semester_lalu = 0;
            } else {
                $ipk_semester_lalu = $sksn / $sks;
            }
            if ($ipk_semester_lalu >= 3.5) {
                $jumlah_maksimum_sks = 24;
            } elseif ($ipk_semester_lalu >= 3.25) {
                $jumlah_maksimum_sks = 23;
            } elseif ($ipk_semester_lalu >= 3) {
                $jumlah_maksimum_sks = 22;
            } elseif ($ipk_semester_lalu >= 2.75) {
                $jumlah_maksimum_sks = 21;
            } elseif ($ipk_semester_lalu >= 2.5) {
                $jumlah_maksimum_sks = 20;
            } elseif ($ipk_semester_lalu >= 2.25) {
                $jumlah_maksimum_sks = 19;
            } elseif ($ipk_semester_lalu >= 2) {
                $jumlah_maksimum_sks = 18;
            } elseif ($ipk_semester_lalu >= 1.75) {
                $jumlah_maksimum_sks = 16;
            } elseif ($ipk_semester_lalu >= 1.5) {
                $jumlah_maksimum_sks = 14;
            } else {
                $jumlah_maksimum_sks = 12;
            }
            $data['ip_semester_lalu'] = $ipk_semester_lalu;
            $data['beban_sks'] = $jumlah_maksimum_sks;

            return $data;
        }
    }
    function print_view()
    {
        $nim = $this->session->userdata('nim');
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
        $krs = $this->Krs_model->get_krs($nim, $tahun_akademik);
        $data['data'] = $this->Krs_detail_model->get_data_krs($kode_krs);
        $semester = $krs->semester;

        $kode_jurusan = substr($nim, 2, 2);
        $kode_jenjang = substr($nim, 4, 1);
        $data['kode_jenjang'] = $kode_jenjang;

        $data['jurusan'] = get_kode_prodi($nim);
        $ps = get_kode_prodi($nim);
        $sp = $this->mahasiswaservice->getStatusPendaftaranByNim($nim);
        $data['beban_sks'] = $this->maksimum_sks();

        $data['krs_mahasiswa'] = $this->Krs_model->get_krs_mahasiswa_by_nim($nim, $semester);
        $data['krs_matakuliah'] = $this->Krs_model->get_krs_matakuliah_by_nim_semester($nim, $semester);

        $data['kajur'] = $this->Ketua_jurusan_model->get_kaprodi($ps->kode_program_studi);
        $data['prodi'] = get_kode_prodi($nim);

        $this->load->view('admin/akademik/krs/print_view', $data);
    }
    function print_view_lalu($ta,$semester)
    {
        $nim = $this->session->userdata('nim');
        $tahun_akademik = $ta;
      	$tahun_akademik_x = $this->m_tahun_akademik->get_aktif();
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
        $krs = $this->Krs_model->get_krs($nim, $tahun_akademik);
        $krs_gg = $this->Krs_model->get_krs($nim, $tahun_akademik_x);
        $data['data'] = $this->Krs_detail_model->get_data_krs($kode_krs);
        // $semester = $krs->semester;
        if (!$krs_gg || $tahun_akademik_x <= $tahun_akademik || (isset($krs_gg->semester) && $krs_gg->semester == $semester)) {
            $this->session->set_flashdata('info', '<div class="callout callout-warning">
                <h4><i class="fa fa-warning"></i> Informasi!</h4>
                <p>KRS yang diminta tidak dapat dicetak.</p>
              </div>');
            redirect('mahasiswa/krs');
        }
        $kode_jurusan = substr($nim, 2, 2);
        $kode_jenjang = substr($nim, 4, 1);
        $data['kode_jenjang'] = $kode_jenjang;

        $data['jurusan'] = get_kode_prodi($nim);
        $ps = get_kode_prodi($nim);
        $sp = $this->mahasiswaservice->getStatusPendaftaranByNim($nim);
        // $data['beban_sks'] = $this->maksimum_sks($nim, $semester, $ps->kode_program_studi, $sp->status_pendaftaran);
        $data['beban_sks'] = $this->maksimum_sks_lalu($tahun_akademik,$semester);
        // echo json_encode($data['beban_sks']);
        $data['krs_mahasiswa'] = $this->Krs_model->get_krs_mahasiswa_by_nim($nim, $semester);
        $data['krs_matakuliah'] = $this->Krs_model->get_krs_matakuliah_by_nim_semester($nim, $semester);

        $data['kajur'] = $this->Ketua_jurusan_model->get_kaprodi($ps->kode_program_studi);
        $data['prodi'] = get_kode_prodi($nim);

        $this->load->view('admin/akademik/krs/print_view', $data);
    }
}