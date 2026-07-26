<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class kurikulum extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_nama_kurikulum',
            'jurusan/kurikulum/m_data_kurikulum',
            'akademik/Krs_model',
            'jurusan/program_studi/Kompetensi_model'
        ));
        $this->load->library('form_validation');

        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    function index() {
        $this->get_kurikulum();
    }

    function get_kurikulum() {
        $data['content'] = 'dosen/V_kurikulum';
        $data['judul'] = 'Kurikulum';
        $data['a_kurikulum'] = 'active';
        $data['form_action'] = site_url('dosen/kurikulum/get_kurikulum');

        $options_nama_kurikulum = array('' => 'Pilih...');
        $data['options_nama_kurikulum'] = $options_nama_kurikulum;

        $kode_dosen = $this->session->userdata('kode_dosen');
        $options_nama_kurikulum = $this->m_nama_kurikulum->get_kurikulum_per_dosen($kode_dosen);
        foreach ($options_nama_kurikulum as $row) {
            $data['options_nama_kurikulum'][$row->kode_nama_kurikulum] = $row->nama_kurikulum . " - " . $row->singkatan_program_studi;
        }
      
        $this->form_validation->set_rules('kode_nama_kurikulum', 'kode_nama_kurikulum', 'required');
        $status_kurikulum = false;
        // jika validasi sukses	akan redirect ke sistem proses	
        if ($this->form_validation->run() == TRUE) {
           $status_kurikulum = true;

            // Mengambil kode dan nama kurikulum berdasarkan kode program studi
            $kode_nama_kurikulum = $this->input->post('kode_nama_kurikulum');
            $data['data_kurikulum'] = $this->m_data_kurikulum->get_data_kurikulum($kode_nama_kurikulum);
            $data['nama_kurikulum'] = $this->m_nama_kurikulum->get_byid($kode_nama_kurikulum);

            $get_prodi = $this->dosenservice->getProdiFromKurikulum($kode_nama_kurikulum);
            if (!$get_prodi) {
                $this->session->set_flashdata('info', 'swal("Gagal!","Data program studi tidak ditemukan","error")');
                $this->load->view('dosen/template/V_main', $data);
                return;
            }
              // Mengambil nama_jurusan berdasarkan kode_nama_jurusan
            $data['singkatan_prodi'] = $get_prodi->singkatan_program_studi;
            //            // Mengambil nama jenjang berdasarkan kode_jenjang
            $data['nama_prodi'] = $get_prodi->nama_program_studi;
//
 //            cek program studi punya kompetensi atau tidak
            $kompetensi = $this->dosenservice->getKompetensiByProdi($get_prodi->kode_program_studi);

           if (count($kompetensi) > 0) {
               $data['mk_pilihan'] = array_column($kompetensi, 'id_matakuliah');
                $data['nama_pilihan'] = array_column($kompetensi, 'nama', 'id_matakuliah');
            }
        } else {
            $data['default']['kode_jurusan'] = $this->input->post('kode_nama_kurikulum');
        }
        $data['status_kurikulum'] = $status_kurikulum;

        $this->load->view('dosen/template/V_main', $data);
    }

}
