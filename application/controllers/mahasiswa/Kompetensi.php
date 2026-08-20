<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kompetensi extends CI_Controller
{
    public $status_pendaftaran;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(
            array(
                'jurusan/program_studi/Kompetensi_model',
                'jurusan/m_tahun_akademik',
                'akademik/Mahasiswa_model',
                'jurusan/kurikulum/m_data_kurikulum',
                'akademik/Krs_model',
            )
        );
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }

        //$this->starter();
    }

    public function index()
    {
        $nim = $this->session->userdata('nim');
        $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
        if ($kompetensi->num_rows() > 0) {
            $data['conten'] = 'mahasiswa/V_kompetensi';
            $data['judul'] = 'Kompetensi Mahasiswa';
            $data['kompetensi_mahasiswa'] = $kompetensi->row_object();

            $this->load->view('mahasiswa/template/V_main', $data);
        } else {
            $this->starter();
        }
    }

    public function starter()
    {
        $nim = $this->session->userdata('nim');
        $mahasiswa = $this->Mahasiswa_model->get($nim);
        if (!$mahasiswa) {
            redirect('mahasiswa/Login_mahasiswa');
        }
        $this->status_pendaftaran = $mahasiswa->status_pendaftaran;
        $kode_jurusan = substr($nim, 2, 2);
        $kompetensi_manajemen_semester4 = substr($nim, 2, 4);
        $kode_jenjang = substr($nim, 4, 1);
        $kompetensi2 = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);


        if ($kompetensi_manajemen_semester4 == "0108") {
            if (($this->semester_saat_ini() >= 2) && ($this->status_pendaftaran == 'B')) {
                $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                if ($kompetensi->num_rows() == 0) {
                    $data['conten'] = 'mahasiswa/V_kompetensi';
                    $data['judul'] = 'Tambah Kompetensi Mahasiswa';
                    $data['data_kompetensi'] = $this->Kompetensi_model->get_kompetensi($this->session->userdata('kode_program_studi'));
                    $data['kompetensi_mahasiswa'] = $kompetensi2->row_object();

                    $this->load->view('mahasiswa/template/V_main', $data);
                } else {
                    $this->session->set_flashdata('info', '<div class="callout callout-info">
                        <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
                         <p>Anda sudah mengisi kompetensi.</p>
                      </div>');
                    redirect('home/Access_denied');
                }
            } else {
                $this->session->set_flashdata('info', '<div class="callout callout-warning">
                    <h4><i class="fa fa-warning-circle"></i> Informasi!</h4>
                     <p>Mohon Maaf, Halaman kompetensi tidak tersedia untuk semester / status anda saat ini.</p>
                  </div>');
                redirect('home/Access_denied');
            }
        }elseif ($kompetensi_manajemen_semester4 == "0301" && substr($nim, 0, 2) < 24 || $kompetensi_manajemen_semester4 == "0501" && substr($nim, 0, 2) >= 24) {
            if (($this->semester_saat_ini() >= 4) && ($this->status_pendaftaran == 'B')) {
                $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                if ($kompetensi->num_rows() == 0) {
                    $data['conten'] = 'mahasiswa/V_kompetensi';
                    $data['judul'] = 'Tambah Kompetensi Mahasiswa';
                    $data['data_kompetensi'] = $this->Kompetensi_model->get_kompetensi($this->session->userdata('kode_program_studi'));
                    $data['kompetensi_mahasiswa'] = $kompetensi2->row_object();

                    $this->load->view('mahasiswa/template/V_main', $data);
                }
            } else {
                if (!available_kompetensi($nim) || available_extensi($nim)) {
                    $this->session->set_flashdata('info', '<div class="callout callout-warning">
                        <h4><i class="fa fa-warning-circle"></i> Informasi!</h4>
        
                        <p>Mohon Maaf, Halaman kompetensi tidak tersedia untuk program studi anda.</p>
                      </div>');

                    redirect('home/Access_denied');
                } elseif (available_kompetensi($nim) && ($this->semester_saat_ini() >= 5) && ($this->status_pendaftaran == 'B') ) {
                    $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);

                    if ($kompetensi->num_rows() == 0) {
                        $data['conten'] = 'mahasiswa/V_kompetensi';
                        $data['judul'] = 'Tambah Kompetensi Mahasiswa';
                        $data['data_kompetensi'] = $this->Kompetensi_model->get_kompetensi($this->session->userdata('kode_program_studi'));
                        $data['kompetensi_mahasiswa'] = $kompetensi2->row_object();

                        $this->load->view('mahasiswa/template/V_main', $data);
                    }

                } elseif (available_kompetensi($nim) && $this->cek_makul_transfer()) {
                    $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                    if ($kompetensi->num_rows() == 0) {
                        $data['conten'] = 'mahasiswa/V_kompetensi';
                        $data['judul'] = 'Tambah Kompetensi Mahasiswa';
                        $data['data_kompetensi'] = $this->Kompetensi_model->get_kompetensi($this->session->userdata('kode_program_studi'));
                        $data['kompetensi_mahasiswa'] = $kompetensi2->row_object();

                        $this->load->view('mahasiswa/template/V_main', $data);
                    }
                } else {
                    $this->session->set_flashdata('info', '<div class="callout callout-info">
                        <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
        
                         <p>Mohon Maaf, Halaman kompetensi hanya dapat diakses bagi mahasiswa jenjang Strata 1 (S1) dan Strata 2 (S2) pada Prodi yang memiliki Konsentrasi.</p>
                        <p>Semester pengisian Konsentrasi berbeda - beda, tergantung pada Kurikulum berlaku pada Prodi tersebut.</p>
                      </div>');

                    redirect('home/Access_denied');
                }
            }
        } else {
            if (!available_kompetensi($nim) || available_extensi($nim)) {
                $this->session->set_flashdata('info', '<div class="callout callout-warning">
                    <h4><i class="fa fa-warning-circle"></i> Informasi!</h4>
    
                    <p>Mohon Maaf, Halaman kompetensi tidak tersedia untuk program studi anda.</p>
                  </div>');

                redirect('home/Access_denied');
            } elseif (available_kompetensi($nim) && ($this->semester_saat_ini() >= 5) && ($this->status_pendaftaran == 'B')) {
                $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);

                if ($kompetensi->num_rows() == 0) {
                    $data['conten'] = 'mahasiswa/V_kompetensi';
                    $data['judul'] = 'Tambah Kompetensi Mahasiswa';
                    $data['data_kompetensi'] = $this->Kompetensi_model->get_kompetensi($this->session->userdata('kode_program_studi'));
                    $data['kompetensi_mahasiswa'] = $kompetensi2->row_object();

                    $this->load->view('mahasiswa/template/V_main', $data);
                }

            } elseif (available_kompetensi($nim) && $this->cek_makul_transfer()) {
                $kompetensi = $this->Kompetensi_model->get_kompetensi_mahasiswa($nim);
                if ($kompetensi->num_rows() == 0) {
                    $data['conten'] = 'mahasiswa/V_kompetensi';
                    $data['judul'] = 'Tambah Kompetensi Mahasiswa';
                    $data['data_kompetensi'] = $this->Kompetensi_model->get_kompetensi($this->session->userdata('kode_program_studi'));
                    $data['kompetensi_mahasiswa'] = $kompetensi2->row_object();

                    $this->load->view('mahasiswa/template/V_main', $data);
                }
            } else {
                $this->session->set_flashdata('info', '<div class="callout callout-info">
                    <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
    
                     <p>Mohon Maaf, Halaman kompetensi hanya dapat diakses bagi mahasiswa jenjang Strata 1 (S1) pada Prodi yang memiliki Konsentrasi.</p>
                    <p>Semester pengisian Konsentrasi berbeda - beda, tergantung pada Kurikulum berlaku pada Prodi tersebut.</p>
                  </div>');

                redirect('home/Access_denied');
            }
        }




    }

    public function simpan()
    {
        $data = array(
            'nim' => $this->session->userdata('nim'),
            'kode_kompetensi' => $this->input->post('kode_kompetensi'),
        );

        if ($this->Kompetensi_model->simpan_kompetensi_mahasiswa($data)) {
            $this->session->set_flashdata('info', '<script>swal("Success", "Data Berhasil di Simpan", "success");</script>');

            redirect('mahasiswa/kompetensi');
        } else {
            $this->session->set_flashdata('info', '<script>swal("Gagal", "Data Gagal di Simpan", "error");</script>');

            redirect('mahasiswa/kompetensi');
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

        if ($sem == 0) {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }
		if (substr($nim, 2, 4) == "0108") {
            if(substr($nim, 0, 2) == "24" && substr($nim, 10, 2) > 7  ){
                return $semester-1;
            }
        }
        return $semester;
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

    public function matakuliah_konsentrasi($kode_kompetensi)
    {
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $matakuliah = $this->mahasiswaservice->getMatakuliahKonsentrasi($kode_nama_kurikulum, $kode_kompetensi);
        $data = array(
            'data' => $matakuliah
        );
        $view = $this->load->view('mahasiswa/konsentrasi/V_matakuliah_konsentrasi', $data, TRUE);
        $res = array(
            'view' => $view,
            'status' => count($matakuliah) > 0 ? true : false
        );

        echo json_encode($res);
    }

}