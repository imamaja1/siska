<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi extends CI_Controller {

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
        $this->load->service('VerifikasiService');
    }
  
  	public function validasi_penilaiaan_dekan($kelas){
        $data = $this->verifikasiservice->validasiPenilaianDekan($kelas);
      	foreach($data as $val){
        	echo $val->kode_krs.' '.$val->nama_matakuliah.'<br>';
        }
    }
	
  	
  	public function coba_kpat_lihat($kode_krs){
    	$data['data'] = $this->Krs_kpat_model->lihat_krs($kode_krs);
      	echo json_encode($data['data']);
    }
  public function coba_kpat_kurikulum_lihat($kode_krs){
     	$kode_nama_kurikulum = kode_nama_kurikulum($nim);
        for ($j = 1; $j <= 8; $j++) {
            $data_kurikulum = $this->verifikasiservice->getKurikulumByNim($kode_nama_kurikulum, $j);
            if (count($data_kurikulum) <= 0) {
                break;
            }
          	$data[$n]['semester'] = $j;
          	$data[$n]['data_nilai'][$i]['semester'] = $cek->semester;
       }
       echo json_encode($data);
  	} 
  public function krs_tidak_masuk(){
        $data = $this->verifikasiservice->getStatusPerkuliahanByTa('28', 'A');
        foreach ($data as $key => $value) {
            $cek = $this->verifikasiservice->getKrsByNimTa($value->nim, 28);
            $semester = (24 - substr($value->nim,0,2))*2 +2;
            
            if($cek <= 0){
                $data1 = array(
                    "kode_tahun_akademik" => 28,
                    "nim" => $value->nim,
                  	"semester" => $semester,
                );
                $post[$key]=$data1;
            }
        }
     	echo json_encode($post);
    }         
  
   public function krs_kpat_pindah_angaktan(){
        $data = $this->verifikasiservice->getKrsKpatPindah('26', 'k');
        $tmp = 0;
        foreach ($data as $value) {
            if ($value->semester % 2 == 0 || $value->kode_krs == $tmp) {
                continue;
            }
            $this->verifikasiservice->updateKrsSemester($value->kode_krs, ($value->semester-1));
            $tmp = $value->kode_krs;
       }
    }
 
  	
	function get_rekapitulasi_matakuliah_per_tahun_akademik_new($kode_program_studi, $kode_tahun_akademik)
    {
        $query1 = $this->verifikasiservice->getRekapMatakuliahQuery($kode_program_studi, $kode_tahun_akademik);
        $query3 = $this->verifikasiservice->getRekapMatakuliahFinal($query1);
        echo json_encode($query3);
    }
  	public function grade(){
        $sub_query = $this->verifikasiservice->getGradeData(6554, '1');
      $total = $this->verifikasiservice->getGradeTotal(6554, '1');
      
      echo json_encode($total);
     }
    public function time() {
      if ($this->input->post('kode_nilai_akademik')) {
              $kode_tahun_akademik = $this->input->post('kode_nilai_akademik');
          } else {
              $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
          }
          $time = $this->verifikasiservice->getAktivasi($kode_tahun_akademik);
      	
      
      	echo strtotime($time->tgl_akhir_uts) - strtotime(date('Y-m-d H:s:i'));
    }
  	public function data_mahasiswa_uts($kelas_id) {
        $kelas_mahasiswa = $this->verifikasiservice->getDataMahasiswaUts($kelas_id);
      	
      echo json_encode(count($kelas_mahasiswa));
    }
  	public function mhs_download() {
        $mhs_krs = $this->verifikasiservice->getMhsDownload();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename= letgo.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('no','nim','nama mahasiswa','program studi','telepon'));
        foreach ($mhs_krs as $key => $value) {
            $row[0] = $key+1;
            $row[1] = $value->nim;
            $row[2] = $value->nama_mahasiswa;
            $row[3] = $value->nama_program_studi;
            fputcsv($output, $row);
        }
    }
  	public function not_krs() {
        $mhs_krs = $this->verifikasiservice->getMhsNotKrs();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename= letgo.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('no','nim','nama mahasiswa','program studi','telepon'));
        foreach ($mhs_krs as $key => $value) {
            $row[0] = $key+1;
            $row[1] = $value->nim;
            $row[2] = $value->nama_mahasiswa;
            $row[3] = $value->nama_program_studi;
            $row[4] = $value->telepon;
            fputcsv($output, $row);
        }
    }
    
    public function kuisoner_dosen($kode_tahun_akademik,$kode_program_studi){
        $data = $this->verifikasiservice->getKuisonerDosen($kode_tahun_akademik, $kode_program_studi);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename= letgo.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('kode','no','nama dosen','mana matakuliah','kelas'));
        foreach ($data as $key => $value) {
            $row[1] = $value->kode_matakuliah;
            $row[0] = $key+1;
            $row[2] = $value->nama_dosen;
            $row[3] = $value->nama_matakuliah;
            $row[4] = $value->nama_kelas;
            fputcsv($output, $row);
        }
    }
    public function mhs_krs($nim){
        $data = $this->verifikasiservice->getMhsKrs($nim);
        echo json_encode($data);
    }

    public function non_aktif_mhs($nim){
        $this->verifikasiservice->nonAktifMhs($nim);
        $mhs = $this->verifikasiservice->getMhsByNim($nim);
        echo json_encode($mhs);
    }

  	public function skripsi(){
    	$data = $this->verifikasiservice->getSkripsi();
        echo json_encode($data);
    }
  	public function semester($nim){
    	$data = $this->verifikasiservice->getSemester($nim);
      echo $data->semester;
    }
    public function petikan_nilai($nim) {
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data['data'] = $this->Petikan_nilai_model->petikan_nilai($nim, $kode_nama_kurikulum); 
        echo json_encode($data['data']); 
	}
    
    public function nilai_dup($nim){
        $lebih = $this->verifikasiservice->getNilaiDup($nim);
        echo json_encode($lebih);
    }

  	public function pembayaran(){
        $kode_tahun_akademik = 23;
        $kkp_skripsi = get_kode_matakuliah_skripsi();
        $data['data'] = $this->verifikasiservice->getPembayaran($kode_tahun_akademik, $kkp_skripsi);
    	echo json_encode($data['data']);
    }
  	public function jumlah_sks($tahun,$nim) {
        $mhs = $this->verifikasiservice->getJumlahSks($tahun, $nim);
        echo json_encode($mhs->result_object());
	}
	
  	public function sks($nim) {
        $mhs = $this->verifikasiservice->getSks($nim);
        echo json_encode($mhs->result_object());
	}
  	public function get_mhs_lulus_tepat_waktu() {
        $mhs = $this->verifikasiservice->getMhsLulusTepatWaktu();
        $data = array(
            'status' => 200,
            'jumlah_mhs' => $mhs->num_rows(),
            'data' => $mhs->result_object()
        );
        echo json_encode($data);
	}
    public function get_mhs_lulus_tidak_tepat_waktu() {
        $mhs = $this->verifikasiservice->getMhsLulusTidakTepatWaktu();
        $data = array(
            'status' => 200,
            'jumlah_mhs' => $mhs->num_rows(),
            'data' => $mhs->result_object()
        );
        echo json_encode($data);
	}
    public function get_mhs_aktif() {
        $mhs = $this->verifikasiservice->getMhsAktif();
        $data = array(
            'status' => 200,
            'data' => $mhs->result_object()
        );
		echo json_encode( $data );
	}

    public function testing_date() {
        $tau = $this->verifikasiservice->getTestingDate();
        
        echo $tau['tgl_akhir_uts'];
        echo "<br>";

        if ($tgl_akhir_uts['tgl_akhir_uts'] >= date('Y-m-d H:s:i', strtotime($this->input->post('tgl_akhir_uts')))) {
            echo "OK";
        } else {
            echo "NOT OK";
        }
    }

    public function get_matakulaih($kode_tahun_akademik, $kode_program_studi) {
        $tahun_akademik = $kode_tahun_akademik;
        $query = $this->verifikasiservice->getMatakulaih($kode_program_studi, $tahun_akademik);
        $output = print_r($query, true);
        echo "<pre>" . $output . "</pre>";
    }

    public function generateqrcode() {
        $this->load->library('qrcode/ciqrcode');
        $mahasiswa = $this->verifikasiservice->getAllKelas();
        foreach ($mahasiswa as $row) {
            $data['data'] = base_url() . 'verifikasi/nilai/' . hash("sha256", $row->kelas_id);
            $data['level'] = 'C';
            $data['size'] = 2;
            $data['savename'] = FCPATH . 'qrcodeimage/' . $row->kelas_id . '.png';
            $this->ciqrcode->generate($data);
        }
    }

    public function mhsmanajemen() {
        $query = $this->verifikasiservice->getMhsManajemen();
        $output = print_r($query, true);
        echo "<pre>" . $output . "</pre>";
    }

    public function tampildoang() {
        $query_baru = $this->verifikasiservice->getTampilDoang();
        $nox = 1;
        echo "<table border=1>";
        echo "<tr>";
        echo "<th>NO</th>";
        echo "<th>Kode Kelas</th>";
        echo "<th>Status Nilai</th>";
        echo "</tr>";

        foreach ($query_baru as $datax) {
            echo "<tr>";
            echo "<td>" . $nox++ . "</td>";
            echo "<td>" . $datax->kelas_id . "</td>";
            echo "<td>" . $datax->status_nilai . "</td>";
            echo "</tr>";
        }
        echo "<table>";
    }

    public function testing() {
        $data['query1'] = $this->verifikasiservice->getTestingQuery(4752);
        var_dump($data['query1']);
    }

    public function asdf() {
        $mahasiswa = $this->verifikasiservice->getMahasiswaByLikeNim('230601');
        $ilkom = $this->verifikasiservice->getKrsByLikeNimSemester('220101', 1);
        $D3SI = $this->verifikasiservice->getKrsByLikeNimSemester('220106', 1);
        $D3RPL = $this->verifikasiservice->getKrsByLikeNimSemester('220105', 1);
        $S1DKV = $this->verifikasiservice->getKrsByLikeNimSemester('220501', 1);
        $S1TI = $this->verifikasiservice->getKrsByLikeNimSemester('220102', 1);
        $S1HUKUM = $this->verifikasiservice->getKrsByLikeNimSemester('220601', 1);
        $S1RPL = $this->verifikasiservice->getKrsByLikeNimSemester('220105', 1);
        $S1TEKPANG = $this->verifikasiservice->getKrsByLikeNimSemester('220107', 1);
        $S1BD = $this->verifikasiservice->getKrsByLikeNimSemester('220303', 1);
        $S1PTI = $this->verifikasiservice->getKrsByLikeNimSemester('220103', 1);

        $manajemen = $this->verifikasiservice->getMahasiswaByLikeNimOnly('220101');
        echo "Jumlah mahasiswa manajemen angkatan 23 = " . count($manajemen) . " Orang";
        echo "<br>";
        echo "MAHASISWA MANAJEMEN SEMESTER 4 YANG SUDAH KRSAN";
        $query_baru = $this->verifikasiservice->getAsdfQuery('220101', 1);
        $nox = 1;
        echo "<table border=1>";
        echo "<tr>";
        echo "<th>NO</th>";
        echo "<th>Kode KRS</th>";
        echo "<th>SEMESTER</th>";
        echo "<th>EMAIL</th>";
        echo "<th>NIM (USERNAME)</th>";
        echo "<th>TANGGAL LAHIR (PASSWORD)</th>";
        echo "<th>KETERANGAN PASSWORD</th>";
        echo "</tr>";

        foreach ($query_baru as $datax) {
            echo "<tr>";
            echo "<td>" . $nox++ . "</td>";
            echo "<td>" . $datax->kode_krs . "</td>";
            echo "<td>" . $datax->semester . "</td>";
            echo "<td>" . $datax->email . "</td>";
            echo "<td>" . $datax->nim . "</td>";
            echo "<td>" . date('dmY', strtotime($datax->tanggal_lahir)) . "</td>";

            if ($datax->sandi == md5(date('dmY', strtotime($datax->tanggal_lahir)))) {
                $KET = "OK";
            } else {
                $KET = "PASSWORD SUDAH DISALIN";
            }
            echo "<td>" . $KET . "</td>";
            echo "</tr>";
        }
        echo "<table>";
    }

    public function nilai($id) {
        $banding = $id;
        $get_id = $this->verifikasiservice->getKelasIdList();

        foreach ($get_id as $row) {
            $pembanding = hash("sha256", $row->kelas_id);
            if ($pembanding == $banding) {
                $has_id = $row->kelas_id;
            }
        }

        $data['query1'] = $this->verifikasiservice->getNilaiViewQuery1($has_id);
        $data['nama_dosen'] = $this->verifikasiservice->getNilaiViewNamaDosen($has_id);
        $data['query2'] = $this->verifikasiservice->getNilaiViewQuery2($has_id);
        $data['query3'] = $this->verifikasiservice->getNilaiViewQuery3($has_id);
        $data['query4'] = $this->verifikasiservice->getNilaiViewQuery4($has_id);

        if ($id != hash("sha256", $has_id)) {
            $this->output->set_status_header('404');
            $this->load->view('View_notfound');
        } else {
            $this->load->view("dosen/V_verifikasi", $data);
        }
    }

}