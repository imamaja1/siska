<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Konsultasi_perwalian extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/Perwalian_model',
            'jurusan/Detail_konsultasi_perwalian_model',
            'akademik/Mahasiswa_model',
            'akademik/Krs_model',
            'akademik/Khs_model',
            'akademik/Krs_detail_model',
            'akademik/Petikan_mahasiswa_model',
            'akademik/Petikan_nilai_model',
            'akademik/mahasiswa_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/program_studi/Jenjang_model',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/kurikulum/m_data_kurikulum',
            'jurusan/konsultasi_perwalian_model',
        ));

        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->library(array('pagination', 'form_validation'));
        $this->load->service('DosenService');
    }

    function index() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $mahasiswa = $this->dosenservice->getKonsultasiMahasiswa($kode_dosen, $kode_tahun_akademik);
        $mahasiswa2 = $this->dosenservice->getKonsultasiMahasiswaAktif($kode_dosen, $kode_tahun_akademik);

        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_proses_konsultasi_perwalian',
            'judul' => 'Konsultasi Perwalian Belum Aktif',
            'a_konsultasi_perwalian' => 'active',
            'title_h1' => '<li>Konsultasi Perwalian</li>',
            'data' => $mahasiswa,
            'data_aktif' => $mahasiswa2,
        );
        $this->load->view('dosen/template/V_main', $data);
    }

    function perkuliahan_perwalian_tidak_aktif() {


        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
//        $query = $this->db->select("*")
//                        ->from('perwalian as p')
//                        ->join('status_perkuliahan as sp', 'sp.nim=p.nim')
//                        ->join('mahasiswa', 'mahasiswa.nim=p.nim')
//                        ->where('p.kode_dosen', $kode_dosen)
//                        ->where('sp.kode_tahun_akademik', 24)
//                        ->where('sp.pembayaran_spp', 0)
//                        ->or_where('sp.pembayaran_sks', 0)
//                        ->get()->result();

        $query = $this->dosenservice->getPerwalianTidakAktif($kode_dosen, $kode_tahun_akademik);

        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_perkuliahan_perwalian_tidak_aktif',
            'judul' => 'Konsultasi Perkuliahan Perwalian Tidak Aktif',
            'a_konsultasi_perwalian' => 'active',
            'title_h1' => '<li>Konsultasi Perkuliahan & Perwalian Tidak Aktif</li>',
            'data_non_aktif' => $query
        );

        // echo "<pre>";
        // print_r($query);

        $this->load->view('dosen/template/V_main', $data);
    }

    function konsultasi_perwalian_aktif() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $mahasiswa = $this->dosenservice->getKonsultasiMahasiswa($kode_dosen, $kode_tahun_akademik);
        $mahasiswa2 = $this->dosenservice->getKonsultasiMahasiswaAktif($kode_dosen, $kode_tahun_akademik);

        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_proses_konsultasi_perwalian_aktif',
            'judul' => 'Konsultasi Perwalian Aktif',
            'a_konsultasi_perwalian' => 'active',
            'title_h1' => '<li>Konsultasi Perwalian</li>',
            'data' => $mahasiswa,
            'data_aktif' => $mahasiswa2,
        );
        $this->load->view('dosen/template/V_main', $data);
    }

    function biodata_mahasiswa($nim) {
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_biodata_mahasiswa',
            'data' => $this->Mahasiswa_model->get($nim),
        );

        $this->load->view('dosen/template/V_open_window', $data);
    }

    function krs_mahasiswa($nim) {
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
//        $kode_jurusan = substr($nim, 2, 2);
//        $kode_jenjang = substr($nim, 4, 1);
        $semester = $this->semester_saat_ini($nim);
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik->kode_tahun_akademik);
//        $ps = $this->db->select('kode_program_studi')
//            ->from('program_studi as ps')
//            ->join('jurusan as jur', 'jur.id_jurusan=ps.id_jurusan')
//            ->join('jenjang as jen', 'jen.id_jenjang=ps.id_jenjang')
//            ->where('kode_jurusan', $kode_jurusan)
//            ->where('kode_jenjang', $kode_jenjang)
//            ->get()->row_object();
        $ps = get_kode_prodi($nim);
        $sp = $this->dosenservice->getStatusPendaftaranMahasiswa($nim);
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_krs_mahasiswa',
            'data' => $this->Krs_detail_model->get_data_krs($kode_krs),
            'semester' => $this->semester_saat_ini($nim),
            'data_mahasiswa' => $this->Mahasiswa_model->get($nim),
            'tahun_akademik' => $tahun_akademik,
//            'prodi' => $this->Nama_jurusan_model->get_kode_nama_jurusan($kode_jurusan, $kode_jenjang),
            'prodi' => get_kode_prodi($nim),
            'beban_sks' => $this->maksimum_sks($nim, $semester, $ps->kode_program_studi, $sp->status_pendaftaran),
        );

        $this->load->view('dosen/template/V_open_window', $data);
    }

    function khs_mahasiswa($nim) {
        if ($this->semester_saat_ini($nim) !== 1) :
            $kode_jenjang = substr($nim, 4, 1);
            $kode_jurusan = substr($nim, 2, 2);
            $angkatan = substr($nim, 0, 2);
            $tahun_akademik = $this->m_tahun_akademik->get_aktif() - 1;
//            $kode_program_studi = $this->Nama_jurusan_model->get_id($kode_jurusan, $kode_jenjang);
            $kode_program_studi = get_kode_prodi($nim);
            $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);

//Generate
//            $kode_nama_kurikulum = kode_nama_kurikulum($nim);
            $data_penilaian = data_penilaian($nim, $this->semester_saat_ini($nim));
//            if (stup_grade($kode_nama_kurikulum, $this->semester_saat_ini($nim)))
//            {
//                $data_penilaian = stup_grade($kode_nama_kurikulum, $this->semester_saat_ini($nim));
//            }else{
//                $data_penilaian = sistem_penilaian($nim);
//            }
            $data_krs = $this->Khs_model->khs($kode_krs);

            $khs['sksn'] = 0;
            $khs['total_sks'] = 0;
            $khs['total_bobot'] = 0;
            $i = 0;
            foreach ($data_krs as $row) {
                $khs['nim'] = $row->nim;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'] ?? '';
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['sks'] = $row->sks;
//                $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                $nilai_akhir = ($row->nilai_akhir * 1);
                if (!empty($data_penilaian)) {
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $khs['data_nilai'][$i]['grade'] = $key['grade'];
                            $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * ($row->sks);
                        }
                    }
                }
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $khs['prodi'] = $kode_program_studi->nama_program_studi;
                $khs['fakultas'] = $kode_program_studi->nama_fakultas;
                $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi->kode_program_studi);

                $i++;
            }
            $data['content'] = "dosen/konsultasi_perwalian/V_khs_mahasiswa";
            $data['data'] = $khs;

        else:
            $data['data'] = false;
            $data['content'] = "dosen/konsultasi_perwalian/V_khs_mahasiswa";
        endif;
        $this->load->view("dosen/template/V_open_window", $data);
    }

    function petikan_nilai_mahasiswa($nim) {
//        $kode_nama_kurikulum = $this->m_data_kurikulum->get_kode_nama_kurikulum($nim);
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data['content'] = "dosen/konsultasi_perwalian/V_petikan_nilai_mahasiswa";
        $data['data'] = $this->Petikan_nilai_model->petikan_nilai($nim, $kode_nama_kurikulum);
//        $data['jenjang'] = $this->Jenjang_model->get_nama_bykode(substr($nim, 4, 1));
//        $data['jurusan'] = $this->Kode_jurusan_model->get_nama_bykode(substr($nim, 2, 2));
        $data['prodi'] = get_kode_prodi($nim);
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_semester();

        $this->load->view('dosen/template/V_open_window', $data);
    }

    function exis_krs($nim) {
        $semeter = $this->semester_saat_ini($nim);
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik->kode_tahun_akademik);
        if (count($this->Krs_detail_model->get_data_krs($kode_krs)) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function semester_saat_ini($nim) {
        $tahun_angkatan = substr($nim, 0, 2);
        $tahun = $this->m_tahun_akademik->get_semester();
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;

        if ($sem == 0) {
            # code...
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }

        return $semester;
    }

    function status_cetak() {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_dosen = $this->session->userdata('kode_dosen');

        $status = $this->input->post('status');
        $param = $this->input->post('param');

        $data['status_cetak'] = $status;

        if ($this->Perwalian_model->status_cetak($data, $param)) {

            $data_redirect = array(
                'content' => 'dosen/konsultasi_perwalian/V_proses_konsultasi_perwalian',
                'judul' => 'Konsultasi Perwalian',
                'a_konsultasi_perwalian' => 'active',
                'data' => $this->Perwalian_model->get_konsultasi_perwalian_by_dosen_angkatan_jurusan($kode_dosen, $kode_tahun_akademik, $this->session->userdata('nama_angkatan'), $this->session->userdata('nama_jurusan')),
            );

            $this->load->view('dosen/template/V_main', $data_redirect);
        }
    }

    function konsultasi_krs($nim_mhs) {

        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_konsultasi_krs',
            'judul' => 'Konsultasi Perwalian',
            'data' => $this->Perwalian_model->get_konsultasi_perwalian_by_nim($this->session->userdata('kode_dosen'), $kode_tahun_akademik, $nim_mhs),
        );

        $this->load->view('dosen/template/V_open_window', $data);
    }

    function konsultasi_perwalian($nim_mhs) {

        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_konsul_perwalian',
            'judul' => 'Konsultasi Perwalian',
            'konsultasi_perwalian' => $this->Detail_konsultasi_perwalian_model->get_konsultasi_perwalian($nim_mhs),
            'data' => $this->Perwalian_model->get_konsultasi_perwalian_by_nim($this->session->userdata('kode_dosen'), $kode_tahun_akademik, $nim_mhs),
        );

        $this->load->view('dosen/template/V_open_window', $data);
    }

    function tambah_konsultasi_krs() {
        $kode_konsultasi = $this->input->post('kode_konsultasi_perwalian');
        $jenis_konsultasi = $this->input->post('jenis_konsultasi');
        $kode_dosen = $this->session->userdata('kode_dosen');

        $data = array(
            'kode_dosen' => $kode_dosen,
            'kode_konsultasi_perwalian' => $kode_konsultasi,
            'isi_konsultasi' => $this->input->post('isi_konsultasi'),
            'tanggapan' => $this->input->post('tanggapan'),
            'jenis_konsultasi' => $jenis_konsultasi,
            'date_created' => date("Y-m-d H:i:s"),
        );

        if ($this->Perwalian_model->ubah_kp($kode_konsultasi, $data)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Tambah isi konsultasi berhasil.","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_krs/' . $this->input->post('nim'));
        }
    }

    function tambah_konsultasi_perwalian() {
        $kode_konsultasi = $this->input->post('kode_konsultasi_perwalian');
        $jenis_konsultasi = $this->input->post('jenis_konsultasi');

        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();

        $data_perwalian = array('kode_dosen' => $kode_dosen);
        $data_detail = array(
            'kode_konsultasi_perwalian' => $kode_konsultasi,
            'isi_konsultasi' => $this->input->post('isi_konsultasi'),
            'tanggapan' => $this->input->post('tanggapan'),
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'jenis_konsultasi' => $jenis_konsultasi,
            'date_created' => date("Y-m-d H:i:s"),
        );

        if ($this->Perwalian_model->ubah_kp($kode_konsultasi, $data_perwalian) && $this->Detail_konsultasi_perwalian_model->simpan($data_detail)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Tambah isi konsultasi berhasil.","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_perwalian/' . $this->input->post('nim'));
        }
    }

    function ubah_konsultasi_perwalian() {
        $kode = $this->input->post('kode_konsultasi_perwalian');
        $data = array(
            'kode_konsultasi_perwalian_detail' => $kode,
            'jenis_konsultasi' => $this->input->post('jenis_konsultasi_krs'),
            'isi_konsultasi' => $this->input->post('isi_konsultasi'),
            'tanggapan' => $this->input->post('tanggapan'),
        );

        if ($this->Detail_konsultasi_perwalian_model->ubah_konsultasi_krs($kode, $data)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Ubah Data Perwalian berhasil","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_perwalian/' . $this->input->post('nim'));
        }
    }

    function ubah_konsultasi_umum() {
        $kode = $this->input->post('kode_konsultasi_perwalian');
        $this->Detail_konsultasi_perwalian_model->kode_konsultasi_perwalian_detail = $kode;
        $this->Detail_konsultasi_perwalian_model->jenis_konsultasi = $this->input->post('jenis_konsultasi_umum');
        $this->Detail_konsultasi_perwalian_model->isi_konsultasi = $this->input->post('isi_konsultasi');
        $this->Detail_konsultasi_perwalian_model->tanggapan = $this->input->post('tanggapan');

        if ($this->Detail_konsultasi_perwalian_model->ubah_konsultasi_krs($kode)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Ubah Data Perwalian berhasil","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_umum/' . $this->input->post('nim'));
        }
    }

    function pencarian_data() {
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_cari',
            'a_konsultasi_perwalian' => 'active',
            'judul' => 'Konsultasi Perwalian',
            'sub_judul' => 'Konsultasi Perwalian',
            'hidden' => 'hidden',
        );
        $this->load->view('dosen/template/V_main', $data);
    }

    function search_process() {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $berdasarkan = $this->input->post('berdasarkan');
        $this->form_validation->set_rules('berdasarkan', 'berdasarkan', 'required', array('required' => 'Field Berdasarkan harus dipilih'));
        $this->form_validation->set_rules('kata_kunci', 'kata_kunci', 'required|max_length[75]', array('required' => 'Field Kata Kunci harus diisi', 'max_length' => 'Field Kata Kunci tidak boleh lebih dari 75 Karakter'));
        if ($berdasarkan == 'nim') {
            $this->form_validation->set_rules('kata_kunci', 'kata kunci', 'required|numeric', array('required' => 'Field Kata Kunci harus diisi', 'numeric' => 'Field Kata Kunci harus mengandung angka'));
        }

        if ($this->form_validation->run() == false) {
            $data = array(
                'content' => 'dosen/konsultasi_perwalian/V_cari',
                'a_konsultasi_perwalian' => 'active',
                'judul' => 'Konsultasi Perwalian',
                'sub_judul' => 'Konsultasi Perwalian',
                'hidden' => 'hidden',
            );
            $this->load->view('dosen/template/V_main', $data);
        } else {
            if ($berdasarkan == "nim") {
                $data = array(
                    'content' => 'dosen/konsultasi_perwalian/V_cari',
                    'a_konsultasi_perwalian' => 'active',
                    'judul' => 'Konsultasi Perwalian',
                    'sub_judul' => 'Konsultasi Perwalian',
                    'konsultasi_perwakilan' => $this->Perwalian_model->get_konsultasi_perwalian_by_nim($this->session->userdata('kode_dosen'), $kode_tahun_akademik, $this->input->post('kata_kunci')),
                );
                $this->session->set_userdata('kata_kunci', $this->input->post('kata_kunci'));
                $this->load->view('dosen/template/V_main', $data);
            } else {
                redirect("pencarian_mahasiswa_konsultasi_berdasarkan_nama");
            }
        }
    }

    function get_konsultasi_process() {
        $this->form_validation->set_rules('angkatan', 'angkatan', 'required', array('required' => 'Field Angkatan harus dipilih'));
        $this->form_validation->set_rules('jurusan', 'jurusan', 'required', array('required' => 'Field Jurusan harus dipilih'));

        if ($this->form_validation->run() == false) {

            $data = array(
                'content' => 'dosen/konsultasi_perwalian/V_konsultasi_perwalian',
                'judul' => 'Konsultasi Perwalian',
                'tahun_akademik' => $this->m_tahun_akademik->get_tahun(),
                'program_studi' => $this->Nama_jurusan_model->get(),
                'a_konsultasi_perwalian' => 'active',
            );
            $this->load->view('dosen/template/V_main', $data);
        } else {
            $kode_dosen = $this->session->userdata('kode_dosen');
            $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
            $angkatan = $this->input->post('angkatan');
            $jurusan = $this->input->post('jurusan');

            $kode_jenjang = substr($jurusan, 2, 2);
            $kode_jurusan = substr($jurusan, 0, 2);

            $query = $this->Krs_model->get_singkatan_program_studi_by_kode_jurusan_jenjang($kode_jenjang, $kode_jurusan);

            $data = array(
                'content' => 'dosen/konsultasi_perwalian/V_proses_konsultasi_perwalian',
                'judul' => 'Konsultasi Perwalian',
                'a_konsultasi_perwalian' => 'active',
                'title_h1' => '<li>Konsultasi Perwalian</li>',
                'title_h2' => '<li>Angkatan 20' . $angkatan . '</li>',
                'title_h3' => '<li>Jurusan ' . $query->singkatan_program_studi . '</li>',
                'data' => $this->Perwalian_model->get_konsultasi_perwalian_by_dosen_angkatan_jurusan($kode_dosen, $kode_tahun_akademik, $angkatan, $jurusan),
            );

            $current_method = $this->router->fetch_method();
            $this->session->set_userdata(array(
                'nama_angkatan' => $this->input->post('angkatan'),
                'nama_jurusan' => $this->input->post('jurusan'),
                'current_method' => $current_method,
            ));

            $this->load->view('dosen/template/V_main', $data);
        }
    }

    function aktif($kode_konsultasi_perwalian = null) {

        $row = $this->Perwalian_model->cek_status_konsultasi_krs($kode_konsultasi_perwalian)->row();

        if (empty($row->isi_konsultasi) or empty($row->tanggapan)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Data Konsultasi pengaktifan KRS belum diupdate atau KRS mahasiswa belum diisi!","error")</script>');
//            redirect('dosen/konsultasi_perwalian/change_status');
            redirect('dosen/konsultasi_perwalian');
        } else if (is_null($kode_konsultasi_perwalian)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
            redirect('dosen/konsultasi_perwalian');
//            redirect('dosen/konsultasi_perwalian/change_status');
        } else {
            $konsultasi_perwalian = $this->Perwalian_model->get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian);
            $num_rows = $konsultasi_perwalian->num_rows();
//          COBA CEK DATA KRS
            $cek_data_krs = $this->dosenservice->getCekKrsAktif($row->nim, $row->kode_tahun_akademik);
//          END CEK DATA KRS
            if ($cek_data_krs) {
//          tanbahan
                if ($num_rows > 0) {
                    $konsultasi = $konsultasi_perwalian->row();
                    $nim = $konsultasi->nim;
                    $perwalian = $this->Perwalian_model->get_perwalian_by_nim($nim);
                    $kode_dosen = $perwalian->kode_dosen;
                    $kode_dosen_perwakilan = $perwalian->kode_dosen_perwakilan;

                    if ($this->session->userdata('kode_dosen') == $kode_dosen || $this->session->userdata('kode_dosen') == $kode_dosen_perwakilan) {
                        $aktif = $this->Perwalian_model->aktif($kode_konsultasi_perwalian);
                        if ($aktif == TRUE) {
                            $this->session->set_flashdata('message', '<script>swal("Sukses!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut berhasil diaktifkan!","success")</script>');
//                            redirect('dosen/konsultasi_perwalian/change_status');
                            redirect('dosen/konsultasi_perwalian');
                        } else {
                            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
//                            redirect('dosen/konsultasi_perwalian/change_status');
                            redirect('dosen/konsultasi_perwalian');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<script>swal("Gagal!","Ilegal operation. Anda tidak berhak mengaktifkan konsultasi perwalian untuk mahasiswa diluar perwalian Anda!","error")</script>');
//                        redirect('dosen/konsultasi_perwalian/change_status');
                        redirect('dosen/konsultasi_perwalian');
                    }
                }
//                tambahan
            } else {
                $this->session->set_flashdata('message', '<script>swal("Gagal!","Mahasiswa belum melakukan pengisian KRS!","error")</script>');
//                redirect('dosen/konsultasi_perwalian/change_status');
                redirect('dosen/konsultasi_perwalian');
            }
        }
    }

    function nonaktif($kode_konsultasi_perwalian = null) {

        if (is_null($kode_konsultasi_perwalian)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
//            redirect('dosen/konsultasi_perwalian/change_status');
            redirect('dosen/konsultasi_perwalian');
        } else {
            $konsultasi_perwalian = $this->Perwalian_model->get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian);
            $num_rows = $konsultasi_perwalian->num_rows();

            if ($num_rows > 0) {
                $konsultasi = $konsultasi_perwalian->row();
                $nim = $konsultasi->nim;
                $perwalian = $this->Perwalian_model->get_perwalian_by_nim($nim);
                $kode_dosen = $perwalian->kode_dosen;
                $kode_dosen_perwakilan = $perwalian->kode_dosen_perwakilan;
                if ($this->session->userdata('kode_dosen') == $kode_dosen || $this->session->userdata('kode_dosen') == $kode_dosen_perwakilan) {
                    $nonaktif = $this->Perwalian_model->nonaktif($kode_konsultasi_perwalian);
                    if ($nonaktif == TRUE) {
                        $this->session->set_flashdata('message', '<script>swal("Sukses!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut berhasil dinonaktifkan!","success")</script>');
//                        redirect('dosen/konsultasi_perwalian/change_status');
                        redirect('dosen/konsultasi_perwalian');
                    } else {
                        $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal dinonaktifkan!","error")</script>');
//                        redirect('dosen/konsultasi_perwalian/change_status');
                        redirect('dosen/konsultasi_perwalian');
                    }
                } else {
                    $this->session->set_flashdata('message', '<script>swal("Gagal!","Ilegal operation. Anda tidak berhak mengnonaktifkan konsultasi perwalian untuk mahasiswa diluar perwalian Anda!","error")</script>');
//                    redirect('dosen/konsultasi_perwalian/change_status');
                    redirect('dosen/konsultasi_perwalian');
                }
            }
        }
    }

    function aktif_search($kode_konsultasi_perwalian = null) {
        $jenis_konsultasi = "K";
        $cek_kode_jenis_konsultasi = $this->Detail_konsultasi_perwalian_model->cek_kode_status_konsultasi($kode_konsultasi_perwalian, $jenis_konsultasi);

        if (is_null($kode_konsultasi_perwalian)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
            redirect('dosen/konsultasi_perwalian/change_status');
        } else if ($cek_kode_jenis_konsultasi->num_rows() == 0) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Gagal diaktifkan, silahkan mengisi konsultasi pengaktifan KRS di tombol Detail terlebih dahulu!","error")</script>');
            redirect('dosen/konsultasi_perwalian/change_status');
        } else {
            $konsultasi_perwalian = $this->Perwalian_model->get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian);
            $num_rows = $konsultasi_perwalian->num_rows();

            if ($num_rows > 0) {
                $konsultasi = $konsultasi_perwalian->row();
                $nim = $konsultasi->nim;
                $perwalian = $this->Perwalian_model->get_perwalian_by_nim($nim);
                $kode_dosen = $perwalian->kode_dosen;
                $kode_dosen_perwakilan = $perwalian->kode_dosen_perwakilan;

                if ($this->session->userdata('kode_dosen') == $kode_dosen || $this->session->userdata('kode_dosen') == $kode_dosen_perwakilan) {
                    $aktif = $this->Perwalian_model->aktif($kode_konsultasi_perwalian);
                    if ($aktif == TRUE) {
                        $this->session->set_flashdata('message', '<script>swal("Sukses!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut berhasil diaktifkan!","success")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status');
                    } else {
                        $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status');
                    }
                } else {
                    $this->session->set_flashdata('message', '<script>swal("Gagal!","Ilegal operation. Anda tidak berhak mengaktifkan konsultasi perwalian untuk mahasiswa diluar perwalian Anda!","error")</script>');
                    redirect('dosen/konsultasi_perwalian/change_status');
                }
            }
        }
    }

    function nonaktif_search($kode_konsultasi_perwalian = null) {
        if (is_null($kode_konsultasi_perwalian)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
            redirect('dosen/konsultasi_perwalian/change_status');
        } else {
            $konsultasi_perwalian = $this->Perwalian_model->get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian);
            $num_rows = $konsultasi_perwalian->num_rows();

            if ($num_rows > 0) {
                $konsultasi = $konsultasi_perwalian->row();
                $nim = $konsultasi->nim;
                $perwalian = $this->Perwalian_model->get_perwalian_by_nim($nim);
                $kode_dosen = $perwalian->kode_dosen;
                $kode_dosen_perwakilan = $perwalian->kode_dosen_perwakilan;
                if ($this->session->userdata('kode_dosen') == $kode_dosen || $this->session->userdata('kode_dosen') == $kode_dosen_perwakilan) {
                    $nonaktif = $this->Perwalian_model->nonaktif($kode_konsultasi_perwalian);
                    if ($nonaktif == TRUE) {
                        $this->session->set_flashdata('message', '<script>swal("Sukses!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut berhasil dinonaktifkan!","success")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status');
                    } else {
                        $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal dinonaktifkan!","error")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status');
                    }
                } else {
                    $this->session->set_flashdata('message', '<script>swal("Gagal!","Ilegal operation. Anda tidak berhak mengnonaktifkan konsultasi perwalian untuk mahasiswa diluar perwalian Anda!","error")</script>');
                    redirect('dosen/konsultasi_perwalian/change_status');
                }
            }
        }
    }

    function aktif_nim_nama($kode_konsultasi_perwalian = null) {

        if (is_null($kode_konsultasi_perwalian)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
            redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
        } else {
            $konsultasi_perwalian = $this->Perwalian_model->get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian);
            $num_rows = $konsultasi_perwalian->num_rows();

            if ($num_rows > 0) {
                $konsultasi = $konsultasi_perwalian->row();
                $nim = $konsultasi->nim;
                $perwalian = $this->Perwalian_model->get_perwalian_by_nim($nim);
                $kode_dosen = $perwalian->kode_dosen;
                $kode_dosen_perwakilan = $perwalian->kode_dosen_perwakilan;

                if ($this->session->userdata('kode_dosen') == $kode_dosen || $this->session->userdata('kode_dosen') == $kode_dosen_perwakilan) {
                    $aktif = $this->Perwalian_model->aktif($kode_konsultasi_perwalian);
                    if ($aktif == TRUE) {
                        $this->session->set_flashdata('message', '<script>swal("Sukses!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut berhasil diaktifkan!","success")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
                    } else {
                        $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
                    }
                } else {
                    $this->session->set_flashdata('message', '<script>swal("Gagal!","Ilegal operation. Anda tidak berhak mengaktifkan konsultasi perwalian untuk mahasiswa diluar perwalian Anda!","error")</script>');
                    redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
                }
            }
        }
    }

    function nonaktif_nim_nama($kode_konsultasi_perwalian = null) {

        if (is_null($kode_konsultasi_perwalian)) {
            $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal diaktifkan!","error")</script>');
            redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
        } else {
            $konsultasi_perwalian = $this->Perwalian_model->get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian);
            $num_rows = $konsultasi_perwalian->num_rows();

            if ($num_rows > 0) {
                $konsultasi = $konsultasi_perwalian->row();
                $nim = $konsultasi->nim;
                $perwalian = $this->Perwalian_model->get_perwalian_by_nim($nim);
                $kode_dosen = $perwalian->kode_dosen;
                $kode_dosen_perwakilan = $perwalian->kode_dosen_perwakilan;
                if ($this->session->userdata('kode_dosen') == $kode_dosen || $this->session->userdata('kode_dosen') == $kode_dosen_perwakilan) {
                    $nonaktif = $this->Perwalian_model->nonaktif($kode_konsultasi_perwalian);
                    if ($nonaktif == TRUE) {
                        $this->session->set_flashdata('message', '<script>swal("Sukses!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut berhasil dinonaktifkan!","success")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
                    } else {
                        $this->session->set_flashdata('message', '<script>swal("Gagal!","Konsultasi perwalian untuk mahasiswa dengan NIM tersebut gagal dinonaktifkan!","error")</script>');
                        redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
                    }
                } else {
                    $this->session->set_flashdata('message', '<script>swal("Gagal!","Ilegal operation. Anda tidak berhak mengnonaktifkan konsultasi perwalian untuk mahasiswa diluar perwalian Anda!","error")</script>');
                    redirect('dosen/konsultasi_perwalian/change_status_nim_nama');
                }
            }
        }
    }

    function change_status() {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $nama_jurusan = $this->session->userdata('nama_jurusan');
        $nama_angkatan = $this->session->userdata('nama_angkatan');
        $kode_jenjang = substr($nama_jurusan, 2, 2);
        $kode_jurusan = substr($nama_jurusan, 0, 2);

        $query = $this->Krs_model->get_singkatan_program_studi_by_kode_jurusan_jenjang($kode_jenjang, $kode_jurusan);

        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_proses_konsultasi_perwalian',
            'judul' => 'Konsultasi Perwalian',
            'title_h1' => '<li>Konsultasi Perwalian</li>',
            'title_h2' => '<li>Angkatan 20' . $nama_angkatan . '</li>',
            'title_h3' => '<li>Jurusan ' . $query->singkatan_program_studi . '</li>',
            'a_konsultasi_perwalian' => 'active',
            'data' => $this->Perwalian_model->get_konsultasi_perwalian_by_dosen_angkatan_jurusan($kode_dosen = $this->session->userdata('kode_dosen'), $kode_tahun_akademik, $nama_angkatan, $nama_jurusan),
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function change_status_nim_nama() {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();

        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_cari',
            'judul' => 'Konsultasi Perwalian',
            'a_konsultasi_perwalian' => 'active',
            'konsultasi_perwakilan' => $this->Perwalian_model->get_konsultasi_perwalian_by_nim($this->session->userdata('kode_dosen'), $kode_tahun_akademik, $this->session->userdata('kata_kunci')),
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function hapus_perwalian($kode) {
        $kode_detail = substr($kode, 0, 2);
        $nim_detail = substr($kode, 2, 10);

        if ($this->Detail_konsultasi_perwalian_model->hapus_perwalian($kode_detail)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Hapus data berhasil.","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_perwalian/' . $nim_detail);
        }
    }

    function hapus_umum($kode) {
        $kode_detail = substr($kode, 0, 2);
        $nim_detail = substr($kode, 2, 10);

        if ($this->Detail_konsultasi_perwalian_model->hapus_perwalian($kode_detail)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Hapus data berhasil.","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_umum/' . $nim_detail);
        }
    }

    function pencarian_konsultasi_umum() {
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_pencarian_konsultasi_umum',
            'judul' => 'Pencarian Data Konsultasi Umum',
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    function proses_pencarian_konsultasi_umum() {

        $this->form_validation->set_rules('nim', 'NIM', 'required|numeric', array('required' => 'Field NIM Kunci harus diisi', 'numeric' => 'Field NIM harus mengandung angka'));

        if ($this->form_validation->run() == false) {
            $data = array(
                'content' => 'dosen/konsultasi_perwalian/V_pencarian_konsultasi_umum',
                'judul' => 'Pencarian Data Konsultasi Umum',
            );

            $this->load->view('dosen/template/V_main', $data);
        } else {
            $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();

            $data = array(
                'content' => 'dosen/konsultasi_perwalian/V_proses_konsultasi_umum',
                'judul' => 'Konsultasi Umum',
                'a_konsultasi_perwalian' => 'active',
                'title_h1' => '<li>Konsultasi Perwalian</li>',
                'title_h2' => '<li>Angkatan </li>',
                'title_h3' => '<li>Jurusan </li>',
                'data' => $this->Perwalian_model->get_konsultasi_perwalian_umum($this->input->post('nim'), $kode_tahun_akademik),
            );
            $this->load->view('dosen/template/V_main', $data);
        }
    }

    function konsultasi_umum($nim_mhs) {

        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data = array(
            'content' => 'dosen/konsultasi_perwalian/V_konsultasi_umum',
            'judul' => 'Konsultasi Perwalian',
            'konsultasi_umum' => $this->Detail_konsultasi_perwalian_model->get_konsultasi_umum($nim_mhs),
            'data' => $this->Perwalian_model->get_konsultasi_perwalian_by_umum($kode_tahun_akademik, $nim_mhs),
        );

        $this->load->view('dosen/template/V_open_window', $data);
    }

    function tambah_konsultasi_umum() {
        $kode_konsultasi = $this->input->post('kode_konsultasi_perwalian');
        $jenis_konsultasi = $this->input->post('jenis_konsultasi');

        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();

        $data_perwalian = array('kode_dosen' => $kode_dosen);
        $data_detail = array(
            'kode_konsultasi_perwalian' => $kode_konsultasi,
            'isi_konsultasi' => $this->input->post('isi_konsultasi'),
            'tanggapan' => $this->input->post('tanggapan'),
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'jenis_konsultasi' => $jenis_konsultasi,
            'date_created' => date("Y-m-d H:i:s"),
        );

        if ($this->Perwalian_model->ubah_kp($kode_konsultasi, $data_perwalian) && $this->Detail_konsultasi_perwalian_model->simpan($data_detail)) {
            $this->session->set_flashdata(
                    'message', '<script>swal("Sukses!","Tambah isi konsultasi berhasil.","success")</script>'
            );
            redirect('dosen/konsultasi_perwalian/konsultasi_umum/' . $this->input->post('nim'));
        }
    }

    public function maksimum_sks($nim, $semester, $kode_program_studi, $status_pendaftaran) {
        $angkatan = substr($nim, 0, 2);
       	$krs_sebelumnya = $this->dosenservice->getKrsSebelumnya($nim, $semester);
        $data_penilaian = data_penilaian($nim, $krs_sebelumnya->semester);
//        if (stup_grade($kode_nama_kurikulum, $semester-1))
//        {
//            $data_penilaian = stup_grade($kode_nama_kurikulum, $semester-1);
//        }else{
//            $data_penilaian = sistem_penilaian($nim);
//        }
        if ($semester !== 1) {
            if ($semester == 2 && $status_pendaftaran !== 'B') {
                $tahun_akademik = $this->m_tahun_akademik->get_aktif() - 1;
//                $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);
                $kode_kr = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                //Generate
                $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
                if ($kode_kr == 0) {
                    $kode_krs = $this->Krs_model->get_krs_konversi($nim);
                } else {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                }
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
                    $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'] ?? '';
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
//                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    if (!empty($data_penilaian)) {
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                                $khs['data_nilai'][$i]['grade'] = $key['grade'];
                                $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                            }
                        }
                    }
                    $sksn = $sksn + ($khs['data_nilai'][$i]['sksn'] ?? 0);
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                    $i++;
                }
                $ipk_semester_lalu = $sks != 0 ? $sksn / $sks : 0;
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
                $kode_krs = $this->Krs_model->get_kode_krs($nim, $krs_sebelumnya->tahun_akademik);

                //Generate
                $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
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
                    $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'] ?? '';
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
//                $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    if (!empty($data_penilaian)) {
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                                $khs['data_nilai'][$i]['grade'] = $key['grade'];
                                $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                            }
                        }
                    }
                    $sksn = $sksn + ($khs['data_nilai'][$i]['sksn'] ?? 0);
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];

                    $i++;
                }
                $ipk_semester_lalu = $sks != 0 ? $sksn / $sks : 0;
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
        } elseif ($semester == 1 && $status_pendaftaran !== 'B') {
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
                    $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'] ?? '';
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
//                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    if (!empty($data_penilaian)) {
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                                $khs['data_nilai'][$i]['grade'] = $key['grade'];
                                $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                            }
                        }
                    }
                    $sksn = $sksn + ($khs['data_nilai'][$i]['sksn'] ?? 0);
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                    $i++;
                }
                $ipk_semester_lalu = $sks != 0 ? $sksn / $sks : 0;
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

    public function export_perwalian() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $data['perwalian'] = $this->dosenservice->getPerwalianExport($kode_dosen);

        $namafile = "Absen Perwalian.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 15, 'margin_bottom' => 15, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $html = $this->load->view('dosen/konsultasi_perwalian/V_export_pdf', $data, true);
//        $header = $this->load->view('admin/akademik/khs/Header_khs',$data,TRUE);
//        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }
   	public function detail($nim){
        $data['perwalian'] = $this->dosenservice->getDetailPerwalian($nim);
        $data['data'] = $this->konsultasi_perwalian_model->detail_manipulasi($nim);
        $data['dosen'] = true;
        if (!$data['perwalian']) {
            echo '<p>Data perwalian tidak ditemukan untuk NIM ini.</p>';
            return;
        }
        $this->load->view('admin/jurusan/konsultasi_perwalian/V_Detail', $data);
    }
    function tambah_konsultasi_krs_new($nim) {
        $isi_konsultasi = $this->input->post('isi_konsultasi');
        $tanggapan = $this->input->post('tanggapan');
        $date_created = date("Y-m-d H:i:s");
        $data = $this->dosenservice->getKonsultasiPerwalianByNim($nim);
        $obj = array(
            'kode_konsultasi_perwalian' => $data->kode_konsultasi_perwalian,
            'isi_konsultasi' => $isi_konsultasi,
            'tanggapan' => $tanggapan,
            'jenis_konsultasi' => 'P',
            'date_created' => $date_created,
            'kode_tahun_akademik' => $data->kode_tahun_akademik
        );            
        $this->dosenservice->insertKonsultasiPerwalianDetail($obj);
    }
}
