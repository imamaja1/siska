<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petikan_nilai extends CI_Controller
{
    private const PETIKAN_FONT_SIZES = array(8, 7.5, 7, 6.5, 6, 5.5);

    private const PETIKAN_PDF_MARGINS = array(
        'mode'          => 'win-1252',
        'format'        => 'Legal',
        'margin_left'   => 15,
        'margin_right'  => 15,
        'margin_top'    => 25,
        'margin_bottom' => 10,
        'margin_header' => 5,
        'margin_footer' => 5,
    );

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

            <p><span style="font-size: 12pt"> Anda tidak bisa mengakses halaman ini, Silahkan hubungi bagian <b>Keuangan</b> terkait dengan pembayaran yang mungkin belum anda bayar. Adapun kemungkinan pembayaran yang belum anda lunasi sebagai berikut</span></p>
            <ul>
                <li>Pembayaran DPP</li>
                <li>Dispensasi Pembayaran SPP</li>
                <li>Dispensasi Pembayaran SKS</li>
                <li>DLL.</li>
            </ul>
            <p style="font-size: 12pt">Untuk info lebih jelasnya silahkan hubungi bagian <b>Keuangan</b>. Terimakasih.</p>
          </div>');

            redirect('home/Access_denied');
        }
  		
    }

    public function index()
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        if (empty($kode_nama_kurikulum)) {
            $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        }
        $data['conten'] = "mahasiswa/V_Petikan_nilai";
        $data['judul'] = "Petikan Nilai";
        $data['data'] = $this->Petikan_nilai_model->petikan_nilai($nim, $kode_nama_kurikulum) ?: [];
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
        if (empty($kode_nama_kurikulum)) {
            $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        }
        $data['conten'] = "mahasiswa/V_Petikan_nilai";
        $data['judul'] = "Petikan Nilai";
        $data['data'] = $this->Petikan_mahasiswa_model->petikan_nilai($nim, $kode_nama_kurikulum) ?: [];

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

                <p><span style="font-size: 12pt"> Anda tidak bisa mengakses halaman ini, Silahkan hubungi bagian <b>Keuangan</b> terkait dengan pembayaran yang mungkin belum anda bayar. Adapun kemungkinan pembayaran yang belum anda lunasi sebagai berikut</span></p>
                <ul>
                    <li>Pembayaran DPP</li>
                    <li>Dispensasi Pembayaran SPP</li>
                    <li>Dispensasi Pembayaran SKS</li>
                    <li>DLL.</li>
                </ul>
                <p style="font-size: 12pt">Untuk info lebih jelasnya silahkan hubungi bagian <b>Keuangan</b>. Terimakasih.</p>
              </div>');

                redirect('home/Access_denied');
            }
        }
    }

    public function cetak()
    {
        $this->render_petikan_pdf($this->build_petikan_data(-1), $this->petikan_filename());
    }

    /**
     * Menyusun data untuk view cetak petikan nilai.
     *
     * @param int $semester_offset -1 = semester yang sudah berjalan (cetak),
     *                              0  = semester berjalan saat ini (Cetak_now).
     * @return array
     */
    private function build_petikan_data($semester_offset)
    {
        $nim = $this->session->userdata('nim');
        $kode_nama_kurikulum = $this->session->userdata('kode_nama_kurikulum');
        if (empty($kode_nama_kurikulum)) {
            $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        }

        $ta = tahun_akademik();
        $kode_tahun_akademik = $ta ? $ta->kode_tahun_akademik - 1 : null;

        $sem = $this->mahasiswaservice->getLastSemesterKrs($nim);
        $semester = ($sem && isset($sem->semester) && is_numeric($sem->semester))
            ? $sem->semester + $semester_offset
            : 0;

        return array(
            'mahasiswa'      => $this->mahasiswa_model->get($nim),
            'tahun_akademik' => $this->mahasiswaservice->getTahunAkademikById($kode_tahun_akademik),
            'prodi'          => get_kode_prodi($nim),
            'semester'       => $semester,
            'semester_jalan' => substr($ta ? $ta->tahun_akademik : '', -2) - substr($nim, 0, 2),
            'data'           => $this->Petikan_nilai_model->petikan_nilai_new($nim, $kode_nama_kurikulum, $semester + 1) ?: [],
            'ttd'            => $this->mahasiswaservice->getSignatureDosen(bodo_kop($nim)['nik']),
        );
    }

    public function Cetak_now()
    {
        $this->render_petikan_pdf($this->build_petikan_data(0), $this->petikan_filename());
    }

    private function petikan_filename()
    {
        return $this->session->userdata('nim') . "-Petikan_nilai.pdf";
    }

    /**
     * Render PDF petikan nilai lalu kirim sebagai unduhan.
     *
     * Ukuran font diturunkan bertahap sampai isi + header (kop) muat
     * dalam satu halaman.
     *
     * @param array  $data
     * @param string $namafile
     * @return void
     */
    private function render_petikan_pdf(array $data, $namafile)
    {
        $this->load->library('pdf');

        $content_view = 'admin/akademik/petikan_nilai/cetak_petikan_nilai';
        $header_view  = 'admin/akademik/petikan_nilai/header_petikan_nilai';
        $header = $this->load->view($header_view, $data, true);

        foreach (self::PETIKAN_FONT_SIZES as $font_size) {
            $data['font_size'] = $font_size;

            $this->pdf = new Pdf();
            $this->pdf->reinitialize(self::PETIKAN_PDF_MARGINS);
            $this->pdf->SetHTMLHeader($header);
            $this->pdf->WriteHTML($this->load->view($content_view, $data, true));
            $this->pdf->Output('', 'S'); // render untuk menghitung jumlah halaman

            if ($this->pdf->getDompdf()->getCanvas()->get_page_count() <= 1) {
                break;
            }
        }

        $this->pdf->Output($namafile, 'D');
    }
}