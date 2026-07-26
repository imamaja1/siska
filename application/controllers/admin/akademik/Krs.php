<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Krs extends CI_Controller {

    var $limit = 50;

    public function __construct() {
        parent::__construct();
        $this->load->service('KrsKhsService');
        $this->load->model(array(
            'akademik/Mahasiswa_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/program_studi/nama_jurusan_model',
            'akademik/Krs_model',
            'akademik/Khs_model',
            'akademik/Krs_detail_model',
            'jurusan/kurikulum/m_matakuliah',
            'jurusan/program_studi/jenjang_model'
        ));
        $this->load->library(array('form_validation', 'pagination'));

        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function status_krs() {
        $ta = $this->input->post('ta');
        $prodi = $this->input->post('prodi');
        $angkatan = $this->input->post('angkatan');
        $status = $this->input->post('status');
        
        $data_mhs = $this->krskhsservice->status_krs($ta, $prodi, $angkatan, $status);

        $data = array(
            'content' => 'admin/akademik/krs/V_status_krs',
            'judul' => 'Akademik',
            'sub_judul' => 'Mahasiswa Aktif',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'title_h3' => '<li>Mahasiswa Aktif</li>',
            'data_mhs' => $data_mhs,
            'prodi' => $prodi,
            'angaktan' => $angkatan,
            'status' => $status,
            'ta' => $ta,
            'data_ta' => $this->m_tahun_akademik->get(),
            'data_prodi' => $this->nama_jurusan_model->get()
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function index() {
        $data = array(
            'content' => 'admin/akademik/krs/V_krs',
            'judul' => 'Akademik',
            'sub_judul' => 'KRS',
            'judul_sub_judul' => '',
            'tahun_akademik' => $this->m_tahun_akademik->get_tahun(),
            'program_studi' => $this->nama_jurusan_model->get(),
            'title_h1' => '<li><i class="fa fa-sticky-note"></i> Akademik</li>',
            'title_h2' => '<li>KRS</li>'
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function get_krs_process() {
        $angkatan = $this->input->post('angkatan');
        $jurusan = $this->input->post('jurusan');
        $semester = $this->input->post('semester');

        $this->form_validation->set_rules('angkatan', 'angkatan', 'required', array('required' => 'Field Angkatan harus diisi'));
        $this->form_validation->set_rules('jurusan', 'jurusan', 'required', array('required' => 'Field Jurusan harus diisi'));
        $this->form_validation->set_rules('semester', 'semester', 'required', array('required' => 'Field Semester harus diisi'));

        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $this->session->set_userdata(array(
                'nama_angkatan' => $angkatan,
                'nama_jurusan' => $jurusan,
                'nama_semester' => $semester,
            ));
            redirect('admin/akademik/krs/get_krs_by_angkatan_jurusan_semester');
        }
    }

    public function get_krs_by_angkatan_jurusan_semester($offset = 0) {
        $semester = $this->session->userdata('nama_semester');
        $kode_program_studi = $this->session->userdata('nama_jurusan');
        $nama_angkatan = $this->session->userdata('nama_angkatan');

        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
        
        $res = $this->krskhsservice->get_krs_by_angkatan_jurusan_semester($nama_angkatan, $kode_program_studi, $semester, $this->limit, $offset);

        $config = array(
            'base_url' => site_url('admin/akademik/krs/get_krs_by_angkatan_jurusan_semester'),
            'total_rows' => $res['count'],
            'per_page' => $this->limit,
            'uri_segment' => $uri_segment,
            'full_tag_open' => '<div class="btn-group">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
            'cur_tag_close' => '</a>',
            'attributes' => array('class' => 'btn btn-flat btn-xs btn-default'),
        );
        $this->pagination->initialize($config);

        $data = array(
            'content' => 'admin/akademik/krs/V_krs_view',
            'judul' => 'Akademik',
            'sub_judul' => 'KRS',
            'halaman' => $this->pagination->create_links(),
            'jumlah_data' => $res['count'],
            'title_h1' => '<li>Angkatan 20' . $nama_angkatan . '</li>',
            'title_h2' => '<li>Jurusan ' . $res['singkatan_program_studi'] . ' </li>',
            'title_h3' => '<li>Semester ' . $semester . ' </li>',
            'data_mahasiswa' => $res['data'],
        );

        $this->session->set_userdata('nama_semester', $semester);
        $this->load->view('admin/template/V_main', $data);
    }

    public function get_rekapitulasi_mahasiswa_per_matakuliah() {
        $this->form_validation->set_rules('kode_program_studi', 'kode_program_studi', 'required', array('required' => 'Field Program Studi harus dipilih'));

        if ($this->form_validation->run() == false) {
            $data = array(
                'content' => 'admin/akademik/krs/V_rekapmhs_permatakuliah',
                'judul' => 'Akademik',
                'sub_judul' => 'Rekapitulasi Mahasiswa Per Matakuliah',
                'program_studi' => $this->nama_jurusan_model->get(),
                'hidden' => 'hidden'
            );
            $this->load->view('admin/template/V_main', $data);
        } else {
            $tahun_akademik = $this->m_tahun_akademik->get_tahun_akademik_aktif();
            $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
            $kode_program_studi = $this->input->post('kode_program_studi');

            $nama_jurusan = $this->nama_jurusan_model->get_all_byid($kode_program_studi);
            $singkatan_jurusan = $nama_jurusan ? $nama_jurusan->singkatan_program_studi : '';

            $this->session->set_userdata(array(
                'kode_tahun_akademik' => $kode_tahun_akademik,
                'singkatan_jurusan' => $singkatan_jurusan,
            ));

            $data = array(
                'content' => 'admin/akademik/krs/V_rekapmhs_permatakuliah',
                'judul' => 'Akademik',
                'sub_judul' => 'Rekapitulasi Mahasiswa Per Matakuliah',
                'program_studi' => $this->nama_jurusan_model->get(),
                'hidden' => '',
                'data' => $this->Krs_model->get_rekapitulasi_matakuliah_per_tahun_akademik($kode_program_studi, $kode_tahun_akademik)
            );

            $this->load->view('admin/template/V_main', $data);
        }
    }

    private function generate_krs_data($nim, $semester) {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
        
        $data['data'] = $this->Krs_detail_model->get_data_krs($kode_krs);
        $data['kode_jenjang'] = substr($nim, 4, 1);
        $data['jurusan'] = get_kode_prodi($nim);
        $ps = get_kode_prodi($nim);
        
        $sp = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);
        
        $data['beban_sks'] = $this->krskhsservice->maksimum_sks($nim, $semester, $ps->kode_program_studi, $sp ? $sp->status_pendaftaran : '');
        $data['krs_mahasiswa'] = $this->Krs_model->get_krs_mahasiswa_by_nim($nim, $semester);
        $data['krs_matakuliah'] = $this->Krs_model->get_krs_matakuliah_by_nim_semester($nim, $semester);
        $data['kajur'] = $this->Ketua_jurusan_model->get_kaprodi($ps->kode_program_studi);
        $data['prodi'] = get_kode_prodi($nim);
        
        return $data;
    }

    public function cetak($nim) {
        $semester = $this->session->userdata('nama_semester');
        $data = $this->generate_krs_data($nim, $semester);

        $header = $this->load->view('admin/akademik/krs/V_header_krs', $data, TRUE);
        $content = $this->load->view('admin/akademik/krs/V_cetak_krs', $data, true);
        
        $mahasiswa = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);
        $nama_mahasiswa = $mahasiswa ? $mahasiswa->nama_mahasiswa : 'Unknown';

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 42, 'margin_bottom' => 20, 'margin_header' => 3, 'margin_footer' => 3]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->defaultheaderline = true;
        $mpdf->Output("KRS - $nim - $nama_mahasiswa.pdf", 'D');
    }

    public function print_view_any($nim) {
        $semester = $this->session->userdata('nama_semester');
        $data = $this->generate_krs_data($nim, $semester);
        $this->load->view('admin/akademik/krs/print_view', $data);
    }

    public function cetak_cepat($nim) {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $krs = $this->Krs_model->get_krs($nim, $tahun_akademik);
        $semester = $krs ? $krs->semester : 1;

        $data = $this->generate_krs_data($nim, $semester);

        $header = $this->load->view('admin/akademik/krs/V_header_krs', $data, TRUE);
        $content = $this->load->view('admin/akademik/krs/V_cetak_krs', $data, true);

        $mahasiswa = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);
        $nama_mahasiswa = $mahasiswa ? $mahasiswa->nama_mahasiswa : 'Unknown';

        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 42, 'margin_bottom' => 20, 'margin_header' => 3, 'margin_footer' => 3]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->defaultheaderline = true;
        $mpdf->Output("KRS - $nim - $nama_mahasiswa.pdf", 'D');
    }

    public function print_view($nim) {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $krs = $this->Krs_model->get_krs($nim, $tahun_akademik);
        $semester = $krs ? $krs->semester : 1;

        $data = $this->generate_krs_data($nim, $semester);
        $this->load->view('admin/akademik/krs/print_view', $data);
    }

    public function download($id_matakuliah) {
        $matakuliah = $this->m_matakuliah->cek_kode_matakuliah($id_matakuliah)->row();
        $nama_matakuliah = $matakuliah ? $matakuliah->nama_matakuliah : '';
        $file_name = $matakuliah->kode_matakuliah . "-" . $nama_matakuliah . " (" . $this->session->userdata('singkatan_jurusan') . ")";
        $query = $this->Krs_model->get_rekapitulasi_mahasiswa_per_matakuliah($id_matakuliah, $this->session->userdata('kode_tahun_akademik'));

        $table = '<table border="1"><tr><td>NO.</td><td>NIM</td><td>NAMA MAHASISWA</td></tr>';
        $i = 0;
        foreach ($query as $row) {
            $table .= '<tr><td><div align="center">' . ++$i . '.</div></td><td><div align="center">' . $row->nim . '</div></td><td>' . $row->nama_mahasiswa . '</td></tr>';
        }
        $table .= '</table>';

        $data['table'] = $table;
        $data['file_name'] = $file_name;
        $this->load->view('admin/akademik/krs/V_spreadsheet_view', $data);
    }

    public function lihat_rekap($id_matakuliah) {
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik');
        $matakuliah = $this->m_matakuliah->cek_kode_matakuliah($id_matakuliah)->row();
        $nama_matakuliah = $matakuliah ? $matakuliah->nama_matakuliah : '';
        $file_name = $matakuliah->kode_matakuliah . "-" . $nama_matakuliah . " (" . $this->session->userdata('singkatan_jurusan') . ")";
        
        $query = $this->krskhsservice->get_rekapitulasi_matakuliah($id_matakuliah, $kode_tahun_akademik);
        
        $data = array(
            'content' => 'admin/akademik/krs/V_lihat_rekap',
            'judul' => 'Akademik',
            'sub_judul' => 'Rekap Mahasiswa Per Matakuliah',
            'data' => $query,
            'file_name' => $file_name,
            'id_matakuliah' => $id_matakuliah,
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function download_rekap($id_matakuliah) {
        $kode_tahun_akademik = $this->session->userdata('kode_tahun_akademik');
        $matakuliah = $this->m_matakuliah->cek_kode_matakuliah($id_matakuliah)->row();
        $nama_matakuliah = $matakuliah ? $matakuliah->nama_matakuliah : '';
        $file_name = $matakuliah->kode_matakuliah . "-" . $nama_matakuliah . " (" . $this->session->userdata('singkatan_jurusan') . ")";
        
        $query = $this->krskhsservice->get_rekapitulasi_matakuliah($id_matakuliah, $kode_tahun_akademik);
        
        $data = array(
            'data' => $query,
            'file_name' => $file_name,
        );
        $this->load->view('admin/akademik/krs/Excel_rekap', $data);
    }

    public function rekapitulasi_mahasiswa_per_matakuliah() {
        $data = array(
            'content' => 'admin/akademik/krs/V_rekapmhs_permatakuliah',
            'judul' => 'Akademik',
            'sub_judul' => 'Rekapitulasi Mahasiswa Per Matakuliah',
            'program_studi' => $this->nama_jurusan_model->get(),
            'hidden' => 'hidden'
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function pencarian_mahasiswa() {
        $data = array(
            'content' => 'admin/akademik/krs/V_pencarian_mahasiswa',
            'judul' => 'Akademik',
            'sub_judul' => 'KRS',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>KRS</li>',
            'title_h3' => '<li>Pencarian</li>',
            'hidden' => 'hidden',
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function search_process() {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $berdasarkan = $this->input->post('berdasarkan');
        $kata_kunci = $this->input->post('kata_kunci');
        $semester = $this->input->post('semester');

        $this->form_validation->set_rules('semester', 'semester', 'required', array('required' => 'Field Semester harus dipilih'));
        $this->form_validation->set_rules('berdasarkan', 'berdasarkan', 'required', array('required' => 'Field Berdasarkan harus dipilih'));
        $this->form_validation->set_rules('kata_kunci', 'kata_kunci', 'required|max_length[75]', array('required' => 'Field Kata Kunci harus diisi'));
        
        if ($this->form_validation->run() == TRUE) {
            if ($berdasarkan == 'nim') {
                $data_mahasiswa = $this->Krs_model->get_mahasiswa_by_nim($tahun_akademik, $kata_kunci, $semester);
                $data_count = count($data_mahasiswa);
                
                $data = array(
                    'content' => 'admin/akademik/krs/V_pencarian_mahasiswa',
                    'judul' => 'Akademik',
                    'sub_judul' => 'KRS',
                    'title_h1' => '<li>Akademik</li>',
                    'title_h2' => '<li> KRS</li>',
                    'title_h3' => '<li> Pencarian Mahasiswa Semester <b>' . $semester . '</b> Kata Kunci <b>' . $kata_kunci . '</b></li>',
                );

                if ($data_count > 0) {
                    $table = '<div class="box box-primary flat"><div clas="box-body"><table class="table demo-table"><thead><tr><th id="th">NIM</th><th id="th">NAMA MAHASISWA</th><th id="th">TINDAKAN</th></tr></thead>';
                    foreach ($data_mahasiswa as $row) {
                        $table .= '<tr><td align="center">' . $row->nim . '</td><td align="center">' . $row->nama_mahasiswa . '</td><td align="center">';
                        $table .= '<a href="' . site_url('admin/akademik/krs/cetak/' . $row->nim) . '" class="btn btn-info btn-xs flat"><i class="fa fa-download"></i> Download</a>&nbsp;';
                        $table .= '<a href="' . site_url('admin/akademik/krs/print_view_any/' . $row->nim) . '" target="_blank" class="btn btn-danger btn-xs flat"><i class="fa fa-print"></i> Cetak</a>&nbsp;</td></tr>';
                    }
                    $table .= '</table></div></div>';
                    $data['table'] = $table;
                    $this->session->set_userdata('nama_semester', $semester);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Tidak ditemukan data mahasiswa dengan kata kunci NIM <b>' . $kata_kunci . '</b> !</div>');
                }
                $this->load->view('admin/template/V_main', $data);
            } else {
                $this->session->set_userdata(array(
                    'berdasarkan' => $berdasarkan,
                    'kata_kunci' => $kata_kunci,
                    'semester' => $semester,
                ));
                redirect('admin/akademik/krs/pencarian_mahasiswa_by_nama');
            }
        } else {
            $this->pencarian_mahasiswa();
        }
    }

    public function pencarian_mahasiswa_by_nama($offset = 0) {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kata_kunci = $this->session->userdata('kata_kunci');
        $semester = $this->session->userdata('semester');

        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
        $data_count = count($this->Krs_model->count_mahasiswa_by_nama($tahun_akademik, $kata_kunci, $semester));

        $config = array(
            'base_url' => site_url('admin/akademik/krs/pencarian_mahasiswa_by_nama'),
            'total_rows' => $data_count,
            'per_page' => $this->limit,
            'uri_segment' => $uri_segment,
            'full_tag_open' => '<div class="btn-group">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a href="#!" class="btn btn-xs flat btn-default disabled">',
            'cur_tag_close' => '</a>',
            'attributes' => array('class' => 'btn flat btn-xs btn-default'),
        );
        $this->pagination->initialize($config);

        $data = array(
            'content' => 'admin/akademik/krs/V_pencarian_mahasiswa',
            'judul' => 'Akademik',
            'sub_judul' => 'KRS',
            'pagination' => $this->pagination->create_links(),
            'jumlah_data' => '<button class="btn btn-xs btn-default flat">Terdapat <b>' . $data_count . ' Record</b></button>',
        );

        $data_mahasiswa = $this->Krs_model->get_mahasiswa_by_nama($tahun_akademik, $kata_kunci, $semester, $this->limit, $offset);

        if ($data_count > 0) {
            $no = 1 + $offset;
            $table = '<div class="box box-primary flat"><div clas="box-body"><table class="table demo-table"><thead><tr><th id="th">NO.</th><th id="th">NIM</th><th id="th">NAMA MAHASISWA</th><th id="th">TINDAKAN</th></tr></thead>';
            foreach ($data_mahasiswa as $row) {
                $table .= '<tr><td align="center">' . $no++ . '.</td><td align="center">' . $row->nim . '</td><td>' . $row->nama_mahasiswa . '</td><td align="center">';
                $table .= '<a href="' . site_url('admin/akademik/krs/cetak/' . $row->nim) . '" class="btn btn-info btn-xs flat"><i class="fa fa-download"></i> Download</a>&nbsp;';
                $table .= '<a href="' . site_url('admin/akademik/krs/print_view_any/' . $row->nim) . '" target="_blank" class="btn btn-danger btn-xs flat"><i class="fa fa-print"></i> Cetak</a>&nbsp;</td></tr>';
            }
            $table .= '</table></div></div>';
            $data['table'] = $table;
            $this->session->set_userdata('nama_semester', $semester);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Tidak ditemukan data mahasiswa dengan kata kunci NIM <b>' . $kata_kunci . '</b> !</div>');
        }
        $this->load->view('admin/template/V_main', $data);
    }

    public function maksimum_sks($nim, $semester, $kode_program_studi, $status_pendaftaran) {
        $res = $this->krskhsservice->maksimum_sks($nim, $semester, $kode_program_studi, $status_pendaftaran);
        return $res;
    }

    public function quick_search() {
        $data = array(
            'content' => 'admin/akademik/krs/V_quick_search',
            'judul' => 'Akademik',
            'sub_judul' => 'Quick Search',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>KRS</li>',
            'title_h3' => '<li>Quick Search</li>',
            'hidden' => 'hidden',
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function quick_search_proses() {
        $keyword = $this->input->post('keyword');
        $data['data'] = $this->Krs_model->get_current_krs($keyword);
        $this->load->view('admin/akademik/krs/V_res_search', $data);
    }
}
?>
