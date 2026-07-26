<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

   public function __construct() {
        parent::__construct();
        $this->load->model(array(
              'akademik/Petikan_nilai_model',
              'akademik/Petikan_mahasiswa_model',
              'akademik/mahasiswa_model',
              'jurusan/program_studi/Kode_jurusan_model',
              'jurusan/program_studi/Nama_jurusan_model',
              'jurusan/program_studi/Jenjang_model',
              'jurusan/m_tahun_akademik',
              'kuisioner/kuisioner_model',
              'akademik/Krs_model',
              'akademik/Krs_detail_model',
        ));
        $this->load->service('AuthService');
    }

   public function dosen(){
        $data = $this->authservice->getAllProgramStudi();
    	foreach($data as $key => $item){
           	$xx = $this->authservice->getDosenByHomebase($item->kode_program_studi);
          	$data[$key]->data_dosen = $xx;
        }
     
     	echo json_encode($data);
    }
}