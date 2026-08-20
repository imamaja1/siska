<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petikan_nilai extends CI_Controller
{

    public function __construct()
    {
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
        ));
        $this->load->service('MahasiswaService');

        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
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

    public function index()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $data['conten'] = "mahasiswa/V_Petikan_nilai";
        $data['judul'] = "Petikan Nilai - " . $nim;
        $data['data'] = $this->Petikan_nilai_model->petikan_nilai($nim, $kode_nama_kurikulum);
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_semester();
        $data['prodi'] = $this->Nama_jurusan_model->get_prodi_by_nim($nim);

        $this->load->view('mahasiswa/template/V_main', $data);
        //echo '<pre>';
        //print_r($data['data']);
    }

    public function petikan_nilai()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $data['conten'] = "mahasiswa/V_Petikan_nilai";
        $data['judul'] = "Petikan Nilai";
        $data['data'] = $this->Petikan_mahasiswa_model->petikan_nilai($nim, $kode_nama_kurikulum);

        $data['jenjang'] = $this->Jenjang_model->get_nama_bykode(substr($nim, 4, 1));
        $data['jurusan'] = $this->Kode_jurusan_model->get_nama_bykode(substr($nim, 2, 2));
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_semester();
        $data['prodi'] = $this->Nama_jurusan_model->get_prodi_by_nim($nim);

        $this->load->view('mahasiswa/template/V_main', $data);
    }

    public function cek_kuisioner()
    {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $nim = $this->session->userdata('nim');
        $status_kuisioner = $this->kuisioner_model->get_setting();
        $cek_pengisian = $this->kuisioner_model->get_matakuliah_kuisioner($nim, $kode_tahun_akademik);
        $axis = $this->kuisioner_model->layanan_axis($nim);
        if ($status_kuisioner == 'A' && !$this->mahasiswaservice->isMahasiswaBaru($nim)) {
            if (count($cek_pengisian) > 0 || !$axis){
//            if (count($cek_pengisian) > 0) {
                $this->session->set_flashdata('info',
                        '<div class="callout callout-info">
                    <h4><i class="fa fa-info-circle"></i> Information!</h4>
                    <p>Silahkan melakukan pengisian kuisioner proses belajar mengajar (PBM) dan kuisioner kepuasan pelayanan untuk bisa melakukan pengaksesan <strong>Petikan Nilai</strong> .</p>
                    </div>');

                redirect(site_url('mahasiswa/kuisioner'));
            }
            if (block($nim)) {
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
    }
    public function cetak(){
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik - 1;
        $nim = $this->session->userdata('nim');
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        // echo json_encode($data['mahasiswa']);break;
        $data['tahun_akademik'] = $this->mahasiswaservice->getTahunAkademikById($tahun_akademik);
        $data['prodi'] = get_kode_prodi($nim);
        $sem = $this->mahasiswaservice->getLastSemesterKrs($nim);
        $data['semester'] = ($sem && isset($sem->semester) && is_numeric($sem->semester)) ? $sem->semester - 1 : 0;
        $data['semester_jalan'] = substr(tahun_akademik()->tahun_akademik, -2) - substr($nim, 0,2);
        $data['data'] = $this->Petikan_nilai_model->petikan_nilai_new($nim, $kode_nama_kurikulum,$data['semester']+1);
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        $data['tahun_akademik'] = $this->mahasiswaservice->getTahunAkademikById($tahun_akademik);
        $data['prodi'] = get_kode_prodi($nim);
      	$nik = bodo_kop($nim)['nik'];
        $nik = bodo_kop($nim)['nik'];
        $ttd = $this->mahasiswaservice->getSignatureDosen($nik);
        $data['ttd'] = $ttd;
        // $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data);
        $content = $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data, true);
        $header = $this->load->view('admin/akademik/petikan_nilai/header_petikan_nilai',$data, true);
        $namafile = $nim . "-Petikan_nilai.pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Legal', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 37, 'margin_bottom' => 10, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }
   	public function Cetak_now(){
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik - 1;
        $nim = $this->session->userdata('nim');
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        // echo json_encode($data['mahasiswa']);break;
        $data['tahun_akademik'] = $this->mahasiswaservice->getTahunAkademikById($tahun_akademik);
        $data['prodi'] = get_kode_prodi($nim);
        $sem = $this->mahasiswaservice->getLastSemesterKrs($nim);
        $data['semester'] = ($sem && isset($sem->semester) && is_numeric($sem->semester)) ? $sem->semester : 0;
        $data['semester_jalan'] = substr(tahun_akademik()->tahun_akademik, -2) - substr($nim, 0,2);
        $data['data'] = $this->Petikan_nilai_model->petikan_nilai_new($nim, $kode_nama_kurikulum,$data['semester']+1);
        $data['mahasiswa'] = $this->mahasiswa_model->get($nim);
        $data['tahun_akademik'] = $this->mahasiswaservice->getTahunAkademikById($tahun_akademik);
        $data['prodi'] = get_kode_prodi($nim);
        
        $nik = bodo_kop($nim)['nik'];
        $ttd = $this->mahasiswaservice->getSignatureDosen($nik);
        $data['ttd'] = $ttd;
        // $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data);
        $content = $this->load->view('admin/akademik/petikan_nilai/cetak_petikan_nilai', $data, true);
        $header = $this->load->view('admin/akademik/petikan_nilai/header_petikan_nilai',$data, true);
        $namafile = $nim . "-Petikan_nilai.pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Legal', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 37, 'margin_bottom' => 10, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHTMLHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output($namafile, "D");
    }
}