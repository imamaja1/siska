<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Khs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
                'jurusan/m_tahun_akademik',
                'jurusan/program_studi/Nama_jurusan_model',
                'jurusan/program_studi/Jenjang_model',
                'jurusan/program_studi/Kode_jurusan_model',
                'jurusan/program_studi/Ketua_jurusan_model',
                'akademik/Khs_model',
                'akademik/Krs_model',
                'kuisioner/kuisioner_model',
        ));
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }

        if ($this->semester_saat_ini() == 1) {
            $this->session->set_flashdata('info', '<div class="callout callout-info">
                <h4><i class="fa fa-info-circle"></i> Informasi!</h4>

                <p>Mohon Maaf, Untuk Mahasiswa Semester 1 Halaman KHS hanya dapat diakses setelah nilai UAS keluar.</p>
              </div>');

            redirect('home/access_denied');
        }

        $this->cek_kuisioner();
      	$this->block();
    }
	function block(){
      	$nim = $this->session->userdata('nim');
    		$block = $this->mahasiswaservice->getBlockByNim($nim);
        if ($block) {
            $this->session->set_flashdata('info', '<div class="callout callout-danger">
            <h4><i class="fa fa-ban"></i> Perhatian!</h4>

            <p><span style="font-size: 12pt"> Anda tidak bisa mengakses halaman ini, Silahkan hubungi bagian <b>Keuangan</b> terkait dengan pembayaran yang mungking belum anda bayar. Adapun kemungkinan pembayaran yang belum anda lunasi sebagai berikut</span></p>
            <ul>
                <li>Pembayaran DPP</li>
                <li>Dispensaisi Pembayaran SPP</li>
                <li>Dispensaisi Pembayaran SKS</li>
                <li>DLL.</li>
            </ul>
            <p style="font-size: 12pt">Untuk info lebih jelasnya silahkan hubungi baigian <b>Keuangan</b>. Terimakasih.</p>
          </div>');

            redirect('home/Access_denied');
        }
  		
    }
    function index($kode_krs = null)
    {
        $nim = $this->session->userdata('nim');
        $kode_jenjang = substr($nim, 4, 1);
        $kode_jurusan = substr($nim, 2, 2);
        $angkatan = substr($nim, 0, 2);
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $tahun_akademik = $this->m_tahun_akademik->get_aktif() - 1;
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data['kode_krs'] = $this->mahasiswaservice->getKrsListForKhs($nim, $kode_tahun_akademik);
        $kode_program_studi = $this->session->userdata('kode_program_studi');
        if ($kode_krs == null) {
            $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
        }

//Generate
        $semester = $this->mahasiswaservice->getSemesterByKodeKrs($kode_krs);
        if ($semester) {
            $data_krs = $this->Khs_model->khs($kode_krs);

            $data_penilaian = data_penilaian($nim, $semester->semester);
            $khs['sksn'] = 0;
            $khs['total_sks'] = 0;
            $khs['total_bobot'] = 0;
            $i = 0;
            foreach ($data_krs as $row) {
                $khs['krs'] = $row->kode_krs; 
                $khs['nim'] = $row->nim;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = nama_kurikulum_nama($nim);
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['sks'] = $row->sks;
                $nilai_akhir = $row->nilai_akhir * 1;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $khs['data_nilai'][$i]['grade'] = $key['grade'];
                        $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                    }
                }
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
                $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
                $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

                $i++;
                $data['judul'] = "KHS | Semester " . $row->semester;
            }
        } else {
            $khs = [];
        }
        $data['conten'] = "mahasiswa/V_Khs";
        $data['data'] = $khs;
        $data['prodi'] = $this->Nama_jurusan_model->get_prodi_by_nim($nim);
        // echo json_encode($khs);break;
        $this->load->view("mahasiswa/template/V_main", $data);
    }

    function semester_saat_ini()
    {
        $nim = $this->session->userdata('nim');
        $tahun_angkatan = substr($nim, 0, 2);
        $tahun = $this->m_tahun_akademik->get_semester();
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;

        if ($sem == 0) {

            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }
		if (substr($nim, 2, 4) == "0108") {
            if(substr($nim, 0, 2) == "24" && substr($nim, 9, 2) > 7  ){
                return $semester-1;
            }
        }
      	if (substr($nim, 2, 4) == "0402") {
            if(substr($nim, 0, 2) == "24" && substr($nim, 9, 2) > 3  ){
                return ($semester - 1);
            }
        }
        return $semester;
    }

    public function cek_kuisioner()
    {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $nim = $this->session->userdata('nim');
        $status_kuisioner = $this->kuisioner_model->get_setting();
        $cek_pengisian = $this->kuisioner_model->get_matakuliah_kuisioner($nim, $kode_tahun_akademik);
//        $cek_pengisian = $this->kuisioner_model->get_matakuliah_kuisioner($nim);
        $axis = $this->kuisioner_model->layanan_axis($nim);
        if ($status_kuisioner == 'A' && !$this->mahasiswaservice->isMahasiswaBaru($nim)) {
//            sementara
       //if ($status_kuisioner == 'A') {
            if (count($cek_pengisian) > 0 || !$axis){
//            if (count($cek_pengisian) > 0) {
                $this->session->set_flashdata('info',
                        '<div class="callout callout-info">
                    <h4><i class="fa fa-info-circle"></i> Information!</h4>
                    <p>Silahkan melakukan pengisian kuisioner proses belajar mengajar (PBM) dan kuisioner kepuasan pelayanan untuk bisa melakukan pengaksesan <strong>KHS</strong> .</p>
                    </div>');

                redirect(site_url('mahasiswa/kuisioner'));
            }
        }
    }

    public function cetak($kode_krs, $nim) {
        $get_semester = $this->mahasiswaservice->getSemesterByKodeKrs($kode_krs);
        if (!$get_semester) { redirect('home/access_denied'); }
      	$ta = $this->mahasiswaservice->getTahunAkademikById($get_semester->kode_tahun_akademik);
        if (!$ta) { redirect('home/access_denied'); }
//        $semester = $this->session->userdata('input_semester');
        $semester = $get_semester->semester;
//        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
//        $kode_program_studi = $this->Nama_jurusan_model->get_id($kode_jurusan, $kode_jenjang);
        $program_studi = get_kode_prodi($nim);
//        $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
        $data_krs = $this->Khs_model->khs($kode_krs);
        $data_penilaian = data_penilaian($nim, $semester);
//        if (stup_grade($kode_kurikulum_angkatan, $semester))
//        {
//            $data_penilaian = stup_grade($kode_kurikulum_angkatan, $semester);
//        }else{
//            $data_penilaian = sistem_penilaian($nim);
//        }
        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
        $khs['kaprodi'] = null;
        $khs['maksimum_sks'] = 0;
      
       	$khs['nim'] = $nim;
        $khs['nama_mahasiswa'] = $this->mahasiswaservice->getNamaMahasiswa($nim);
        $khs['tahun_akademik'] = $ta->tahun_akademik;
        $khs['semester'] = $semester;
      
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $nim;
            //$khs['nama_mahasiswa'] = $row->nama_mahasiswa;
            //$khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
            //$khs['semester'] = $row->semester;
          	//$khs['tahun_akademik'] = $ta->tahun_akademik;
            //$khs['semester'] = $semester;
            $khs['kurikulum'] = nama_kurikulum_nama($nim);
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks;
            $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;
//            $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
            $nilai_akhir = $row->nilai_akhir * 1;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                }
            }
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
//            $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
//            $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);
            $khs['maksimum_sks'] = $this->maksimum_sks($nim, $row->kode_tahun_akademik, $program_studi->kode_program_studi);

            $i++;
        }
        $nik = bodo_kop($nim)['nik'];
        $data['data'] = $khs;
        $data['prodi'] = $program_studi;
        $khsssssss = $this->mahasiswaservice->getDosenByNik($nik);
        $data['signature'] = $khsssssss ? $khsssssss->signature : null;
        // $this->load->view('akademik/cetak_khs', $data);
        $namafile = $nim . "-KHS.pdf";
        $this->load->library('pdf');
        $this->pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 38, 'margin_bottom' => 20, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->pdf;
        $html = $this->load->view('admin/akademik/khs/Cetak', $data, true);
        $header = $this->load->view('admin/akademik/khs/Header_khs',$data,TRUE);
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($html);
        $mpdf->Output($namafile, "D");
    }
    public function maksimum_sks($nim, $tahun_akademik, $kode_program_studi) {
//        $kode_jenjang = substr($nim, 4, 1);
//        $kode_jurusan = substr($nim, 2, 2);
//        $angkatan = substr($nim, 0, 2);
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
//        $semester = $this->session->userdata('input_semester');
        $get_semester = $this->mahasiswaservice->getSemesterByKodeKrs($kode_krs);
        if (!$get_semester) { redirect('home/access_denied'); }
        $ta = $this->mahasiswaservice->getTahunAkademikById($get_semester->kode_tahun_akademik);
//        $semester = $this->session->userdata('input_semester');
        $semester = $get_semester->semester;
//        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
//        $kode_kurikulum_angkatan = nama_kurikulum($nim)->kode_kurikulum_angkatan;

        //Generate
//        $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
        $data_penilaian = data_penilaian($nim, $semester);
//        if (stup_grade($kode_kurikulum_angkatan, $semester))
//        {
//            $data_penilaian = stup_grade($kode_kurikulum_angkatan, $semester);
//        }else{
//            $data_penilaian = sistem_penilaian($nim);
//        }
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
            $khs['kurikulum'] = nama_kurikulum_nama($nim);
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks;
//            $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
            $nilai_akhir = $row->nilai_akhir * 1;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                }
            }
            $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
            $sks = $sks + $khs['data_nilai'][$i]['sks'];
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
//            $khs['nama_jenjang'] = $this->Jenjang_model->get_nama_bykode($kode_jenjang);
//            $khs['nama_jurusan'] = $this->Kode_jurusan_model->get_nama_bykode($kode_jurusan);
//            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($kode_program_studi);

            $i++;
        }
        if ($sks == 0) {
            $ipk_semester_lalu = 0;
        } else {
            $ipk_semester_lalu = $sksn / $sks;
        }
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

        return $jumlah_maksimum_sks;
    }
    public function print_view($kode_krs, $nim) {

        $get_semester = $this->mahasiswaservice->getSemesterByKodeKrs($kode_krs);
        if (!$get_semester) { redirect('home/access_denied'); }
      	$ta = $this->mahasiswaservice->getTahunAkademikById($get_semester->kode_tahun_akademik);
        $semester = $get_semester->semester;
        $program_studi = get_kode_prodi($nim);

        $data_krs = $this->Khs_model->khs($kode_krs);
        $data_penilaian = data_penilaian($nim, $semester);

        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
        $khs['kaprodi'] = null;
        $khs['maksimum_sks'] = 0;
      
       	$khs['nim'] = $nim;
        $khs['nama_mahasiswa'] = $this->mahasiswaservice->getNamaMahasiswa($nim);
        $khs['tahun_akademik'] = $ta->tahun_akademik;
        $khs['semester'] = $semester;
      
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $nim;
            //$khs['nama_mahasiswa'] = $row->nama_mahasiswa;
            //$khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
            //$khs['semester'] = $row->semester;
          	//$khs['tahun_akademik'] = $ta->tahun_akademik;
            //$khs['semester'] = $semester;
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
            $khs['maksimum_sks'] = $this->maksimum_sks($nim, $row->kode_tahun_akademik, $program_studi->kode_program_studi);

            $i++;
        }

        $data['data'] = $khs;
        $data['prodi'] = $program_studi;
        $this->load->view('admin/akademik/khs/print_view', $data);
    }
}