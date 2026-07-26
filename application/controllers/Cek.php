<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cek extends CI_Controller {

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
              'akademik/Mahasiswa_model',
              'akademik/Krs_kpat_model',
        ));
        $this->load->service('CekService');
    }
  
  	public function gas(){
      $data = $this->cekservice->getDummyUpdateNilaiByTa(28);
      
echo '<!DOCTYPE html>';
echo '<html lang="id">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Tabel Data</title>';
echo '<style>
        table { border-collapse: collapse; width: 300px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
      </style>';
echo '</head>';
echo '<body>';
      
      $jj = -1;
        echo '<table>';
        echo '<tr><th>kode_khs</th><th>nilai dummy ke-1</th><th>nilai dummy ke-2</th></tr>';
		foreach($data as $val){
                echo '<tr>';
                  echo '<td>' . $val->kode_khs_detail . '</td>';
          		  echo '<td>' . $val->na . '</td>';
           			if($jj == $val->kode_khs_detail){
                        echo '<td>' . $val->na . '</td>';
                      	continue;
                    }
          		  $jj = $val->kode_khs_detail;
                echo '</tr>';     
    	}
        echo '</table>';

echo '</body>';
echo '</html>';
      
    }
    public function gas2(){
      $data1 = $this->cekservice->getKhsDetailByTa(28);
      
echo '<!DOCTYPE html>';
echo '<html lang="id">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Tabel Data</title>';
echo '<style>
        table { border-collapse: collapse; width: 300px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
      </style>';
echo '</head>';
echo '<body>';
      
      $jj = -1;
        echo '<table>';
        echo '<tr><th>kode_khs</th><th>nilai akhir</th><th>nilai</th></tr>';
		foreach($data1 as $val){
                echo '<tr>';
          		  echo '<td>' . $val->kode_khs_detail . '</td>';
                  echo '<td>' . $val->nilai_akhir . '</td>';
           			if($jj == $val->kode_khs_detail){
                      	continue;
                    }
          		  
          		  $jj = $val->kode_khs_detail;
                echo '</tr>';     
    	}
        echo '</table>';

echo '</body>';
echo '</html>';
      
    }
}