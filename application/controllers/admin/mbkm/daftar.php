<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect(site_url('login_admin/login'));
        }
        $this->load->model(array(
            'akademik/krs_model',
            'kuisioner/kelas_model',
            'kuisioner/mengajar_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_matakuliah',
            'akademik/Khs_model',
            'jurusan/program_studi/Ketua_jurusan_model',
        ));

        $this->load->service('MbkmService');
    }

    public function index() {
        $ta = $this->input->get('ta');
        $prodi = $this->input->get('prodi');
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        
        $data['content'] = 'admin/mbkm/V_mahasiswa';
        $data['judul'] = 'mbkm';
        $data['sub_judul'] = 'MBKM';
        $data['semester'] = $this->m_tahun_akademik->get_semester();        
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();   
        $data['kode_tahun_akademik'] = $ta;
        $data['kode_program_studi_filter'] = $prodi;
        $data['prodi'] = $this->nama_jurusan_model->get();
        $data['mahasiswa'] = $this->mbkmservice->getMahasiswaMbkm($ta, $prodi);
        $this->load->view('admin/template/V_main', $data);
    } 

    public function get_mahasiswa($ta = null) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $data['ta'] = $ta;
        $data['mahasiswa'] = $this->mbkmservice->getMahasiswaMbkm($ta);
        $this->load->view('admin/mbkm/V_data_mhs_mbkm',$data);
        // echo json_encode($data);
    }

    public function search_mahasiswa($nim, $ta) {
        $cek_mahasiswa = $this->mbkmservice->searchMahasiswaMbkm($nim, $ta);
            
        $data['ta'] =  $this->m_tahun_akademik->get_tahun_akademik_by_kode_one($ta);
        
        if ($cek_mahasiswa) {
            $data['mahasiswa'] = $cek_mahasiswa;
            $data['cek'] = 1;
        }else{
            $data['mahasiswa'] = $this->mbkmservice->searchMahasiswaOnly($nim);
            $data['cek'] = 0;
        }
        if (!(strlen($nim) == 10 || strlen($nim) == 11)) {
            $data['cek'] = 3;
        }
        $this->load->view('admin/mbkm/V_search_mhs',$data);
    }

    public function tambah_mhs_mbkm($nim, $ta){
        $cek = $this->mbkmservice->cekMbkm($nim, $ta);
        if (!$cek) {
            $data_insert = array(
                'nim' => $nim,
                'kode_ta' => $ta,
            );
            $insert = $this->mbkmservice->tambahMbkm($data_insert);
            if ($insert) {
                $res['status'] = 1;
            } else {
                $res['status'] = 0;
            }
            echo json_encode($res);
        }else{
            $res['status'] = 2;
            echo json_encode($res);
        }
    }

    public function hapus_mhs_mbkm($id){
        $hapus = $this->mbkmservice->hapusMbkm($id);
        if ($hapus) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }
        echo json_encode($res);
    }

    public function nilai($id, $ta){
        $data['data_mhs'] = $this->mbkmservice->getNilaiMahasiswa($id, $ta);
        $kode_kurikulum = kode_nama_kurikulum($data['data_mhs']->nim);
        $data['kurikulum'] = $this->mbkmservice->getKurikulum($kode_kurikulum);
        $data['data_nilai'] = $this->mbkmservice->getDataNilai($id, $ta);
        $data['semester'] = $this->m_tahun_akademik->get_tahun_akademik_by_kode($ta);        
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        if ($this->input->is_ajax_request()) {
            $this->load->view('admin/mbkm/V_nilai', $data);
        } else {
            $data['content'] = 'admin/mbkm/V_nilai';
            $data['judul'] = 'mbkm';
            $data['sub_judul'] = 'Nilai Mahasiswa';
            $this->load->view('admin/template/V_main', $data);
        }
    }
    public function nilai_mhs($id,$nilai){
        $update = $this->mbkmservice->updateNilaiMhs($id, $nilai);
        
        if ($update) {
            $nim_row = $this->db->select('mhs.nim')
                ->from('khs_detail as khd')
                ->join('krs_detail as krd','krd.kode_krs_detail = khd.kode_krs_detail')
                ->join('krs','krs.kode_krs = krd.kode_krs')
                ->join('mahasiswa as mhs','mhs.nim = krs.nim')
                ->where('khd.kode_khs_detail', $id)
                ->get()->row_object();

            $grade = '-';
            if ($nim_row) {
                $data_penilaian = sistem_penilaian($nim_row->nim);
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai) && ($nilai <= $key['nilai_maksimum'])) {
                        $grade = $key['grade'];
                        break;
                    }
                }
            }

            $res['status'] = 1;
            $res['grade'] = $grade;
        } else {
            $res['status'] = 0;
        }
        echo json_encode($res);
    }
    public function status($id){
        $tmp = $this->mbkmservice->getStatusMbkm($id);

        if ($tmp->id_mk_mbkm) {
            if ($tmp->status_mbkm == 1) {
                $this->mbkmservice->updateStatusMbkm($id, '0');
                $eco = array(
                    'status' => 0
                );
            }else{
                $this->mbkmservice->updateStatusMbkm($id, '1');
                $eco = array(
                    'status' => 1
                );
            }
        }else{
            $this->mbkmservice->insertStatusMbkm(array(
                'kode_krs_detail' => $tmp->kode_krs_detail,
                'status' => 1,
            ));
            $eco = array(
                'status' => 1
            );
        }
        echo json_encode($eco);
    }
    public function print_view_aktif($nim, $kode_ta) {
        $ta = $this->mbkmservice->getTahunAkademik($kode_ta);
        $get_semester = $this->mbkmservice->getKrs($nim, $kode_ta);
      	$semester = $get_semester->semester;
        $program_studi = get_kode_prodi($nim);

        $data_krs = $this->Khs_model->khs_kpat_aktif($get_semester->kode_krs);
        $data_penilaian = data_penilaian($nim, $semester);

        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
      
      	$khs['nim'] = $nim;
        $khs['nama_mahasiswa'] = $this->mbkmservice->getNamaMahasiswa($nim);
        $khs['tahun_akademik'] = $ta->tahun_akademik;
        $khs['semester'] = $semester;
        // die();
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $nim;
            $khs['kurikulum'] = nama_kurikulum_nama($nim);
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks;
            $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;
            $nilai_akhir = $row->nilai_akhir * 1;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                }
            }
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);
            $khs['maksimum_sks'] = 20;

            $i++;
        }

        $data['data'] = $khs;
        $data['prodi'] = $program_studi;
        // echo json_encode($khs);
        $this->load->view('admin/mbkm/print_view_khs_aktif', $data);
    }
    public function print_view_non_aktif($nim, $kode_ta) {
        $ta = $this->mbkmservice->getTahunAkademik($kode_ta);
        $get_semester = $this->mbkmservice->getKrs($nim, $kode_ta);
      	$semester = $get_semester->semester;
        $program_studi = get_kode_prodi($nim);

        $data_krs = $this->Khs_model->khs_kpat_non_aktif($get_semester->kode_krs);
        $data_penilaian = data_penilaian($nim, $semester);

        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
      
       	$khs['nim'] = $nim;
        $khs['nama_mahasiswa'] = $this->mbkmservice->getNamaMahasiswa($nim);
        $khs['tahun_akademik'] = $ta->tahun_akademik;
        $khs['semester'] = $semester;
        
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $nim;
            $khs['kurikulum'] = nama_kurikulum_nama($nim);
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks;
            $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;
            $nilai_akhir = $row->nilai_akhir * 1;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                }
            }
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);
            $khs['maksimum_sks'] = 20;
            $i++;
        }

        $data['data'] = $khs;
        $data['prodi'] = $program_studi;
        $this->load->view('admin/mbkm/print_view_khs_non_aktif', $data);
    }
}
