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
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
        $this->load->service('DosenService');
    }

    public function index()
    {
        $data['content'] = 'dosen/kaprodi/mbkm/v_index';
        $data['judul'] = 'MBKM';
        $data['sub_judul'] = 'Data Mahasiswa';
        $data['sub_judul'] = 'Halaman Mahasiswa';
        $data['semester'] = $this->m_tahun_akademik->get_semester();        
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();   
        $this->load->view('dosen/template/V_main', $data);
        
    }
    public function get_mahasiswa($ta = null) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_program_studi = $this->dosenservice->getKaprodiKode($kode_dosen);
        $data['mahasiswa'] = $this->dosenservice->getMahasiswaMbkm($kode_program_studi['kode_program_studi'], $ta);
        $this->load->view('dosen/kaprodi/mbkm/V_data_mhs_mbkm',$data);
        // echo json_encode($data);
    }
    public function search_mahasiswa($nim, $ta) {
        // if (strlen($nim) == "10") {
        //     $data['cek'] = 3;
        //     $this->load->view('admin/mbkm/V_search_mhs',$data);
        // }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_program_studi = $this->dosenservice->getKaprodiFull($kode_dosen);
        $nama_program_studi = $this->dosenservice->getNamaProdi($kode_program_studi['kode_program_studi']);
        
        $cek_mahasiswa = $this->dosenservice->getCekMahasiswaMbkm($nim, $ta);
       
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
            $mahasiswa_data = $this->dosenservice->getMahasiswaByProdi($kode_program_studi['kode_program_studi'], $nim);
            
            if ($mahasiswa_data) {
                $data['mahasiswa'] = $mahasiswa_data;
                $data['cek'] = 0;
            }else{
                $data['prodi'] = $nama_program_studi;
                $data['cek'] = 2;
            } 
        }
        $this->load->view('dosen/kaprodi/mbkm/V_search_mhs',$data);
    }

    public function tambah_mhs_mbkm($nim, $ta){
        $cek = $this->dosenservice->cekMbkm($nim, $ta);
        if (!$cek) {
            $data_insert = array(
                'nim' => $nim,
                'kode_ta' => $ta,
            );
            $insert = $this->dosenservice->insertMbkm($data_insert);
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
        $hapus = $this->dosenservice->deleteMbkm($id);
        if ($hapus) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }
        echo json_encode($res);
    }

}