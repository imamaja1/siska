<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class matakuliah_prasyarat extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_nama_kurikulum',
            'akademik/Krs_model',
            'jurusan/kurikulum/m_matakuliah_prasyarat',
        ));
        $this->load->library('form_validation');

        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    function index() {
        $this->get_matakuliah_prasyarat();
    }

    function get_matakuliah_prasyarat() {
        $data['content'] = 'dosen/V_matakuliah_prasyarat';
        $data['judul'] = 'Matakuliah Prasyarat';
        $data['a_matakuliah_prasyarat'] = 'active';
        $data['form_action'] = site_url('dosen/matakuliah_prasyarat/get_matakuliah_prasyarat');

        $options_nama_jurusan = array('' => 'Pilih...');
        $data['options_nama_jurusan'] = $options_nama_jurusan;
      
      	$options_nama_kurikulum = array('' => 'Pilih...');
        $data['option_nama_kurikulum'] = $options_nama_kurikulum;

        $options_nama_jurusan = $this->nama_jurusan_model->get();
        $kode_dosen = $this->session->userdata('kode_dosen');
        $options_nama_kurikulum = $this->m_nama_kurikulum->get_kurikulum_per_dosen($kode_dosen);
        foreach ($options_nama_kurikulum as $row) {
            $data['option_nama_kurikulum'][$row->kode_nama_kurikulum] = $row->nama_kurikulum." - ".$row->singkatan_program_studi;
        }
//        foreach ($options_nama_jurusan as $row) {
//            $data['options_nama_jurusan'][$row->kode_program_studi] = $row->singkatan_program_studi;
//        }

        $this->form_validation->set_rules('kode_nama_kurikulum', 'kode nama kurikulum', 'required');
        $status_kurikulum = false;
        // jika validasi sukses	akan redirect ke sistem proses	
        if ($this->form_validation->run() == TRUE) {
            $status_kurikulum = true;

            // Mengambil kode dan nama kurikulum berdasarkan kode program studi
            $kode_nama_kurikulum = $this->input->post('kode_nama_kurikulum');

//            $nama_kurikulum = $this->m_nama_kurikulum->get_kode_nama_kurikulum($kode_program_studi);
//            $kode_nama_kurikulum = $nama_kurikulum->kode_nama_kurikulum;

            // Mengambil nama_jurusan berdasarkan kode_nama_jurusan
//            $jurusan = $this->nama_jurusan_model->get_kode_by_program_studi($kode_program_studi);
//            $id_jurusan = $jurusan->id_jurusan;
//            $data['jurusan'] = $jurusan;


            // Mengambil nama jenjang berdasarkan kode_jenjang
//            $jenjang = $this->nama_jurusan_model->get_kode_by_program_studi($kode_program_studi);
//            $data['jenjang'] = $jenjang;

            $pilihan = array('13','16','17','19','14','15');
          
          $get_prodi = $this->dosenservice->getProdiFromNamaKurikulum($kode_nama_kurikulum);
            if (!$get_prodi) {
                $this->session->set_flashdata('info', 'swal("Gagal!","Data program studi tidak ditemukan","error")');
                $this->load->view('dosen/template/V_main', $data);
                return;
            }
            $nama_prodi = $get_prodi->nama_program_studi;
            $nama_kurikulum = $this->m_nama_kurikulum->get_byid($kode_nama_kurikulum);

//            $matakuliah_prasyarat = $this->m_matakuliah_prasyarat->get_matakuliah_prasyarat_by_kode_nama_kurikulum($kode_nama_kurikulum);
            $matakuliah_prasyarat = $this->m_matakuliah_prasyarat->get_matakuliah_prasyarat_by_kode_nama_kurikulum($kode_nama_kurikulum);
            if (count($matakuliah_prasyarat) > 0) {
                $table = '<div class="box box-primary flat"><div class="box-body">';
              	$table .= '<div align="center"><h4><b>MK Prasyarat ' . strtoupper($nama_prodi) . ' (Kurikulum : ' . $nama_kurikulum->nama_kurikulum . ')</b></h4></div><br />';
                $table .= '<div class="table-responsive"><table class="table demo-table">';
                $table .= '<thead><tr>';
                $table .= '<th>NO.</th>';
                $table .= '<th align="center">KODE MK. YANG DIAMBIL</th>';
                $table .= '<th align="center">NAMA MATAKULIAH YANG DIAMBIL</th>';
                $table .= '<th align="center">KODE MK. PRASYARAT</th>';
                $table .= '<th align="center">NAMA MATAKULIAH PRASYARAT</th>';
                $table .= '</tr></thead>';
                $no = 1;
                foreach ($matakuliah_prasyarat as $row) {
                    $table .= '<tr>';
                    $table .= '<td><div align="center">' . $no . '.</div></td>';
                    $table .= '<td><div align="center">' . $row->kode_matakuliah_ambil . '</div></td>';
                    if (in_array(substr($row->matakuliah_yg_diambil,6,2),$pilihan))
                    {
                        $table .= '<td><i>' . $row->nama_matakuliah_yg_diambil . '</i></td>';
                    }else{
                        $table .= '<td>' . $row->nama_matakuliah_yg_diambil . '</td>';
                    }
                    $table .= '<td><div  align="center">' . $row->kode_matakuliah_syarat . '</div></td>';
                    if (in_array(substr($row->matakuliah_yg_diambil,6,2),$pilihan))
                    {
                        $table .= '<td><i>' . $row->nama_matakuliah_prasyarat . '</i></td>';

                    }else{
                        $table .= '<td>' . $row->nama_matakuliah_prasyarat . '</td>';

                    }
                    $table .= '</tr>';
                    $no++;
                }
                $table .= '</table></div>';
                $table .= '</div></div>';
                $data['table'] = $table;
            } else {
                $data['table'] = '<div class="callout callout-success flat"><p>Tidak ada data pada jurusan tersebut.</p></div>';
            }



//            $this->load->view('dosen/template/V_main', $data);
        } else {
            $data['default']['kode_jurusan'] = $this->input->post('kode_nama_kurikulum');
        }
        $data['status_kurikulum'] = $status_kurikulum;
        $this->load->view('dosen/template/V_main', $data);
    }

}
