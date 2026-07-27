<?php

class MBKM extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'laporan/laporan_model',
        ));
        $this->load->service('KaprodiService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['content'] = 'dosen/kaprodi/mbkm/v_index';
        $data['judul'] = 'MBKM';
        $data['sub_judul'] = 'Data Mahasiswa';
        $data['semester'] = $this->m_tahun_akademik->get_semester();        
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();   
        $data['kode_tahun_akademik'] = $data['semester']->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
        
    }
    public function get_mahasiswa($ta = null) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_program_studi = $this->kaprodiservice->get_kaprodi_prodi_row_array($kode_dosen);
        $data['mahasiswa'] = $this->kaprodiservice->get_mahasiswa_mbkm($ta, $kode_program_studi['kode_program_studi'] ?? '');
        $this->load->view('dosen/kaprodi/mbkm/v_data_mhs_mbkm',$data);
    }
    public function search_mahasiswa($nim, $ta) {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_program_studi = $this->kaprodiservice->get_kaprodi_prodi_row_array($kode_dosen);
        if (!$kode_program_studi) {
            show_error('Kaprodi tidak ditemukan.', 500);
            return;
        }
        $nama_program_studi = $this->kaprodiservice->get_prodi_nama($kode_program_studi['kode_program_studi']);
        
        $cek_mahasiswa = $this->kaprodiservice->search_mahasiswa_mbkm($nim, $kode_program_studi['kode_program_studi'], $ta);
       
        $data['ta'] =  $this->m_tahun_akademik->get_tahun_akademik_by_kode_one($ta);
       
        if ($cek_mahasiswa) {
            if ($cek_mahasiswa->program_studi_kode != $kode_program_studi['kode_program_studi']) {
                $data['cek'] = 2;
                $data['prodi'] = $nama_program_studi;
            }else{
                $data['mahasiswa'] = array($cek_mahasiswa);
                $data['cek'] = 1;
            }            
        }else{
            $mahasiswa_data = $this->kaprodiservice->search_mahasiswa_by_prodi($nim, $kode_program_studi['kode_program_studi']);
            
            if ($mahasiswa_data) {
                $data['mahasiswa'] = $mahasiswa_data;
                $data['cek'] = 0;
            }else{
                $data['prodi'] = $nama_program_studi;
                $data['cek'] = 2;
            } 
        }
        $this->load->view('dosen/kaprodi/mbkm/v_search_mhs',$data);
    }

    public function tambah_mhs_mbkm($nim, $ta){
        $cek = $this->kaprodiservice->cek_mbkm($nim, $ta);
        if (!$cek) {
            $data_insert = array(
                'nim' => $nim,
                'kode_ta' => $ta,
            );
            $insert = $this->kaprodiservice->insert('mbkm', $data_insert);
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
        $hapus = $this->kaprodiservice->delete('mbkm', array('id' => $id));
        if ($hapus) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }
        echo json_encode($res);
    }

}
