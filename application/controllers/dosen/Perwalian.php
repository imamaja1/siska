<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class perwalian extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/perwalian_model',
            'jurusan/m_dosen',
        ));
        $this->load->library('form_validation');

        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    function index() {

        $data = array(
            'content' => 'dosen/V_perwalian',
            'judul' => 'Perwalian',
            'tahun_akademik' => $this->m_tahun_akademik->get_tahun(),
            'program_studi' => $this->nama_jurusan_model->get(),
            'a_perwalian' => 'active',
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function search() {
        $data = array(
            'content' => 'dosen/V_search_proses',
            'judul' => 'Perwalian',
            'hidden' => 'hidden',
            'a_perwalian' => 'active',
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function search_process() {
        $data = array(
            'content' => 'dosen/V_search_proses',
            'judul' => 'Perwalian',
            'title_h1' => '<li>Perwalian</li>',
            'title_h2' => '<li> Pencarian</li>',
            'hidden' => '',
            'a_perwalian' => 'active',
        );



        $this->form_validation->set_rules('berdasarkan', 'berdasarkan', 'required', array('required' => 'Field Berdasarkan harus dipilih'));
        $this->form_validation->set_rules('kata_kunci', 'kata_kunci', 'required|max_length[75]', array('required' => 'Field Kata Kunci harus diisi', 'max_length' => 'Field Kata Kunci tidak boleh lebih dari 75 Karakter'));
        if ($this->input->post('berdasarkan') == 'nim') {
            $this->form_validation->set_rules('kata_kunci', 'kata_kunci', 'required|numeric|exact_length[10]', array('required' => 'Field Kata Kunci harus diisi', 'numeric' => 'Field Kata Kunci harus mengandung angka', 'exact_length' => 'Field Kata Kunci harus 10 karakter'));
        }
        if ($this->form_validation->run() == TRUE) {
            $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
            $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
            if ($this->input->post('berdasarkan') == 'nim') {
                $data['perwalian'] = $this->perwalian_model->get_perwalian_by_nim_dan_kode_dosen($this->input->post('kata_kunci'), $this->session->userdata('auth_kode_dosen'), $kode_tahun_akademik);
                $this->load->view('dosen/template/V_main', $data);
            } else {
                $data['perwalian'] = $this->perwalian_model->get_perwalian_by_nama_dan_kode_dosen($this->input->post('kata_kunci'), $this->session->userdata('auth_kode_dosen'), $kode_tahun_akademik);
                $this->load->view('dosen/template/V_main', $data);
            }
        } else {
            $data = array(
                'content' => 'dosen/V_search_proses',
                'judul' => 'Perwalian',
                'title_h1' => '<li>Perwalian</li>',
                'title_h2' => '<li> Pencarian</li>',
                'hidden' => 'hidden',
                'a_perwalian' => 'active',
            );
            $this->load->view('dosen/template/V_main', $data);
        }
    }

    function get_perwalian_prosess() {
        $angkatan = $this->input->post('angkatan');
        $jurusan_jenjang = $this->input->post('jurusan');

        $kode_jenjang = substr($jurusan_jenjang, 2, 2);
        $kode_jurusan = substr($jurusan_jenjang, 0, 2);

        $data = array(
            'content' => 'dosen/V_perwalian',
            'judul' => 'Perwalian',
            'tahun_akademik' => $this->m_tahun_akademik->get_tahun(),
            'program_studi' => $this->nama_jurusan_model->get(),
            'a_perwalian' => 'active',
        );

        $this->form_validation->set_rules('angkatan', 'angkatan', 'required', array('required' => 'Field Angkatan harus diisi'));
        $this->form_validation->set_rules('jurusan', 'jurusan', 'required', array('required' => 'Field jurusan harus diisi'));

        if ($this->form_validation->run() == TRUE) {

            # Menentukan angkatan 
            $nama_angkatan = '20' . $angkatan;
            $this->session->set_userdata('nama_angkatan', $nama_angkatan);

            #mengirim singkatan program studi
            $nama_jurusan = $this->nama_jurusan_model->get_kode_nama_jurusan($kode_jurusan, $kode_jenjang);
            $this->session->set_userdata('singkatan_jurusan', $nama_jurusan->singkatan_program_studi);



            $kode_angkatan_and_jurusan = $angkatan . '' . $jurusan_jenjang;
            $this->session->set_userdata('kode_angkatan_and_jurusan', $kode_angkatan_and_jurusan);
            redirect('dosen/perwalian/get_all_perwalian_by_angkatan_jurusan');
        } else {
            $this->load->view('dosen/template/V_main', $data);
        }
    }

    function get_all_perwalian_by_angkatan_jurusan() {

        $data = array(
            'content' => 'dosen/V_search_angkatan_jurusan',
            'judul' => 'Perwalian',
            'title_h1' => '<li>Perwalian</li>',
            'title_h2' => '<li>Angkatan ' . $this->session->userdata('nama_angkatan') . '</li>',
            'title_h3' => '<li>Jurusan ' . $this->session->userdata('singkatan_jurusan') . '</li>',
            'a_perwalian' => 'active',
        );

        $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
        $kode_tahun_akademik_aktif = $tahun_akademik->kode_tahun_akademik;


        $this->session->set_userdata('kode_tahun_akademik_aktif', $kode_tahun_akademik_aktif);
        $data['perwalian'] = $this->perwalian_model->get_all_status_perkuliahan_perwalian_by_kode_tahun_akademik_angkatan_jurusan_and_dosen($this->session->userdata('kode_tahun_akademik_aktif'), $this->session->userdata('kode_angkatan_and_jurusan'), $this->session->userdata('auth_kode_dosen'));
        $this->load->view('dosen/template/V_main', $data);
    }

    function perwakilan() {
        $data = array(
            'content' => 'dosen/V_get_perwakilan_perwalian_form',
            'judul' => 'Perwakilan',
            'sub_judul' => 'Perwakilan',
            'hidden' => '',
            'a_perwalian' => 'active',
        );

        $perwakilan_perwalian = $this->perwalian_model->get_nama_dosen_perwalian_by_kode_dosen_perwakilan($this->session->userdata('auth_kode_dosen'));
        $num_rows = $perwakilan_perwalian->num_rows();


        if ($num_rows > 0) {

            $data['content'] = 'dosen/V_get_perwakilan_perwalian_form';
            $data['judul'] = 'Perwakilan';
            $data['sub_judul'] = 'Perwakilan';
            $data['form_action'] = site_url('dosen/perwalian/perwakilan');

            $options_dosen = array('' => 'Pilih Dosen Perwakilan');
            $data['options_dosen'] = $options_dosen;

            foreach ($perwakilan_perwalian->result() as $row) {
                $data['options_dosen'][$row->kode_dosen] = $row->nama_dosen;
            }

            $this->form_validation->set_rules('kode_dosen', 'Nama Dosen', 'required');

            // jika validasi sukses
            if ($this->form_validation->run() == TRUE) {

                $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
                $kode_tahun_akademik_aktif = $tahun_akademik->kode_tahun_akademik;

                $kode_dosen_perwakilan = $this->input->post('kode_dosen');
                $perwalian = $this->perwalian_model->get_perwalian_by_kode_dosen_dan_kode_dosen_perwakilan($kode_tahun_akademik_aktif, $kode_dosen_perwakilan, $this->session->userdata('auth_kode_dosen'));
                $num_rows = $perwalian->num_rows();

                if ($num_rows > 0) {
                    // mengambil nama dosen
                    $dosen = $this->m_dosen->get_dosen_by_kode($kode_dosen_perwakilan);
                    $nama_dosen = $dosen->nama_dosen;
                    $data['header'] = "<div align=\"center\"><h4><b>PERWAKILAN PERWALIAN DOSEN \"$nama_dosen\"</b></h4></div>";
                    $data['num_rows'] = $num_rows;
                    $table = '<div class="table-responsive"><table class="table demo-table">';
                    $table .= '<thead><tr>';
                    $table .= '<th style="text-align: center;">NO</th>';
                    $table .= '<th style="text-align: center;" >NIM</th>';
                    $table .= '<th style="text-align: center;">NAMA MAHASISWA</th>';
                    $table .= '<th style="text-align: center;">DATA AKADEMIK</th>';
                    $table .= '<th style="text-align: center;">STATUS PERKULIAHAN</th>';
                    $table .= '</tr></thead>';
                    $no = 1;
                    foreach ($perwalian->result() as $row) {
                        $table .= '<tr>';
                        $table .= '<td><div align="center">' . $no . '.</div></td>';
                        $table .= '<td><div align="center">' . $row->nim . '</div></td>';
                        $table .= '<td>' . $row->nama_mahasiswa . '</td>';
                        switch ($row->status_perkuliahan) {
                            case 'A':
                                $status_perkuliahan = 'AKTIF';
                                break;
                            case 'C':
                                $status_perkuliahan = 'CUTI';
                                break;
                            case 'T':
                                $status_perkuliahan = 'TANPA KETERANGAN';
                                break;
                            case 'B':
                                $status_perkuliahan = 'BERHENTI';
                                break;
                            case 'P':
                                $status_perkuliahan = 'PINDAH/TRANSFER';
                                break;
                            default:
                                $status_perkuliahan = '-';
                        }
                        $link_data_akademik = anchor_popup('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim, '<i class="fa fa-user"></i> Biodata', array('class' => 'btn-xs btn btn-default')) . ' ' . anchor_popup('dosen/konsultasi_perwalian/krs_mahasiswa/' . $row->nim, '<i class="fa fa-file"></i> KRS', array('class' => 'btn-xs btn btn-default')) . ' ' . anchor_popup('dosen/konsultasi_perwalian/khs_mahasiswa/' . $row->nim, '<i class="fa fa-file"></i> KHS Semester Lalu', array('class' => 'btn-xs btn btn-default')) . ' ' . anchor_popup('dosen/konsultasi_perwalian/petikan_nilai_mahasiswa/' . $row->nim, '<i class="fa fa-clipboard"></i> Petikan Nilai', array('class' => 'btn-xs btn btn-default'));
                        $table .= '<td><div align="center">' . $link_data_akademik . '</div></td>';
                        $table .= '<td><div align="center">' . $status_perkuliahan . '</div></td>';
                        $table .= '</tr>';
                        $no++;
                    }
                    $table .= '</table></div>';
                    $data['table'] = $table;
                } else {
                    $data['message'] = '<div class="callout callout-warning flat"><p>Data mahasiswa untuk perwakilan perwalian dosen lain yang Anda pilih tidak ditemukan!</p> </div>';
                }
            } else {
                $data['default']['kode_dosen'] = $this->input->post('kode_dosen');
            }
        } else {
            $data['hidden'] = 'hidden';
            $data['message'] = '<div class="callout callout-warning flat"><p>Anda tidak memiliki perwakilan perwalian dari dosen lain!</p> </div>';
        }


        $this->load->view('dosen/template/V_main', $data);
    }

}
