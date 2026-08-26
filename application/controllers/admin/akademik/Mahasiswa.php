<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {
    
    var $limit = 50;

    public function __construct() {
        parent::__construct();
        $this->load->service('MahasiswaService');
        $this->load->library(array('pagination', 'form_validation'));

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

    public function index() {
        $data = array(
            'content' => 'admin/akademik/mahasiswa/V_mahasiswa',
            'judul' => 'Akademik',
            'sub_judul' => 'Mahasiswa',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'tahun_akademik' => $this->mahasiswaservice->getTahunAkademikLengkap(),
            'program_studi' => $this->mahasiswaservice->getProgramStudiLengkap()
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function Mahasiswa_Validasi_KRS() {
        $ta = $this->input->post('ta');
        $prodi = $this->input->post('prodi');
        
        $res = $this->mahasiswaservice->getValidasiKrsMahasiswa($ta, $prodi);
        
        $data = array(
            'content' => 'admin/akademik/mahasiswa/V_validasi_dosen',
            'judul' => 'Akademik',
            'sub_judul' => 'Mahasiswa Aktif',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'title_h3' => '<li>Mahasiswa Aktif</li>',
            'data_mhs' => $res['data_mhs'],
            'prodi' => $res['prodi'],
            'ta' => $res['ta'],
            'data_ta' => $this->mahasiswaservice->getTahunAkademikLengkap(),
            'data_prodi' => $this->mahasiswaservice->getProgramStudiLengkap()
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function Excel_Validasi_KRS($ta, $prodi) {
        $nama_prodi = $this->mahasiswaservice->getNamaProdi($prodi);
        $res = $this->mahasiswaservice->getValidasiKrsMahasiswa($ta, $prodi);
        $data_mhs = $res['data_mhs'];
        
        $table = '<h3>' . $nama_prodi . '</h3>';
        $table .= '<table style="text-align: left;" border="1">';
        $table .= '<tr><th>NO.</th><th>NIM</th><th>NAMA MAHASISWA</th><th>NAMA DOSEN WALI</th><th>VALIDASI (Dosen)</th><th>VALIDASI SKS (Keuangan)</th></tr>';
        foreach ($data_mhs as $key => $value) {
            $val_dosen = $value->status_cetak == 'A' ? 'Divalidasi' : 'Belum Divalidasi';
            $val_sks = $value->pembayaran_sks == 1 ? 'Divalidasi' : 'Belum Divalidasi';
            $table .= '<tr><td>'.($key+1).'.</td><td>'.$value->nim.'</td><td>'.$value->nama_mahasiswa.'</td><td>'.$value->nama_dosen.'</td><td>'.$val_dosen.'</td><td>'.$val_sks.'</td></tr>';
        }
        $table .= '</table>';
        
        $data['table'] = $table;
        $data['file_name'] = 'Validasi_KRS_'.$nama_prodi;
        $this->load->view('admin/laporan/rekap_ipk/V_spreadsheet_view', $data); 
    }

    public function tambah() {
        $data = array(
            'content' => 'admin/akademik/mahasiswa/V_tambah_mahasiswa',
            'judul' => 'Akademik',
            'sub_judul' => 'Tambah Data Mahasiswa',
            'title_h1' => '<li>Akademik </li>',
            'title_h2' => '<li>Mahasiswa </li>',
            'title_h3' => '<li>Tambah Data </li>',
            'provinsi' => $this->mahasiswaservice->getProvinsiLengkap(),
            'prodi'=> $this->mahasiswaservice->getProgramStudiLengkap(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_data_mahasiswa() {
        $this->form_validation->set_rules('nik', 'nik', 'required');
        $this->form_validation->set_rules('status', 'status', 'required');
        $this->form_validation->set_rules('nim', 'nim', 'required|numeric');
        $this->form_validation->set_rules('nama_mahasiswa', 'nama_mahasiswa', 'required');
        $this->form_validation->set_rules('program_studi_kode', 'program_studi_kode', 'required');

        if ($this->form_validation->run() == false) {
            $this->tambah();
        } else {
            $res = $this->mahasiswaservice->simpanMahasiswa($this->input->post());
            if ($res['status']) {
                $this->session->set_flashdata('info', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
                redirect('admin/akademik/mahasiswa/tambah');
            } else {
                $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '","error")</script>');
                $this->tambah();
            }
        }
    }

    public function update($nim) {
        $data = array(
            'content' => 'admin/akademik/mahasiswa/V_mahasiswa_update',
            'judul' => 'Akademik',
            'sub_judul' => 'Update Data Mahasiswa',
            'data_mahasiswa' => $this->mahasiswaservice->getMahasiswaByNim($nim),
            'provinsi' => $this->mahasiswaservice->getProvinsiLengkap(),
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'title_h3' => '<li>Update Data</li>',
            'prodi'=> $this->mahasiswaservice->getProgramStudiLengkap(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_update() {
        $nim = $this->input->post('nim');
        
        $this->form_validation->set_rules('nama_mahasiswa', 'nama_mahasiswa', 'required');
        $this->form_validation->set_rules('program_studi_kode', 'program_studi_kode', 'required');

        if ($this->form_validation->run() == false) {
            $this->update($nim);
        } else {
            $res = $this->mahasiswaservice->ubahMahasiswa($nim, $this->input->post());
            if ($res['status']) {
                $this->session->set_flashdata('info', '<script>swal("Sukses!","' . $res['msg'] . '","success")</script>');
                redirect('admin/akademik/mahasiswa');
            } else {
                $this->session->set_flashdata('info', '<script>swal("Gagal!","' . $res['msg'] . '","error")</script>');
                $this->update($nim);
            }
        }
    }

    public function get_mahasiswa_process() {
        $this->form_validation->set_rules('angkatan', 'angkatan', 'required');
        $this->form_validation->set_rules('kode_program_studi', 'kode_program_studi', 'required');

        if ($this->form_validation->run() == TRUE) {
            $this->session->set_userdata(array(
                'nama_angkatan' => $this->input->post('angkatan'),
                'kode_program_studi' => $this->input->post('kode_program_studi'),
            ));
            redirect('admin/akademik/mahasiswa/get_all_mahasiswa_by_angkatan_and_jurusan');
        } else {
            $this->index();
        }
    }

    public function get_all_mahasiswa_by_angkatan_and_jurusan($offset = 0) {
        $nama_angkatan = $this->session->userdata('nama_angkatan');
        $kode_program_studi = $this->session->userdata('kode_program_studi');

        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;

        $res = $this->mahasiswaservice->getMahasiswaByAngkatanJurusanPaginated($nama_angkatan, $kode_program_studi, $this->limit, $offset);

        if ($res['count'] > 0) {
            $config = array(
                'base_url' => site_url('admin/akademik/mahasiswa/get_all_mahasiswa_by_angkatan_and_jurusan'),
                'total_rows' => $res['count'],
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
                'content' => 'admin/akademik/mahasiswa/V_mahasiswa_search',
                'judul' => 'Akademik',
                'sub_judul' => 'Data Mahasiswa',
                'title_h1' => '<li>Data Mahasiswa</li>',
                'title_h2' => '<li>Angkatan 20' . $nama_angkatan . '</li>',
                'title_h3' => '<li>Jurusan ' . $res['program_studi'] . '</li>',
                'halaman' => $this->pagination->create_links(),
                'jumlah_data' => $res['count'],
                'data_mahasiswa' => $res['data'],
            );
        } else {
            $this->session->set_flashdata('keterangan', 'Tidak ditemukan satupun data mahasiswa untuk Angkatan dan Jurusan !');
            $data = array('content' => 'admin/akademik/mahasiswa/V_mahasiswa_search');
        }

        $this->load->view('admin/template/V_main', $data);
    }

    public function search() {
        $data = array(
            'content' => 'admin/akademik/mahasiswa/V_search',
            'judul' => 'Akademik',
            'sub_judul' => 'Mahasiswa',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'title_h3' => '<li>Pencarian</li>',
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function search_process() {
        $berdasarkan = $this->input->post('berdasarkan');
        $kata_kunci = $this->input->post('kata_kunci');

        $this->form_validation->set_rules('berdasarkan', 'berdasarkan', 'required');
        $this->form_validation->set_rules('kata_kunci', 'kata_kunci', 'required');

        if ($this->form_validation->run() == TRUE) {
            if ($berdasarkan == 'nim') {
                $res = $this->mahasiswaservice->searchMahasiswa($berdasarkan, $kata_kunci);
                
                $data = array(
                    'content' => 'admin/akademik/mahasiswa/V_search',
                    'judul' => 'Akademik',
                    'sub_judul' => 'Mahasiswa',
                    'title_h1' => '<li>Akademik</li>',
                    'title_h2' => '<li>Mahasiswa</li>',
                    'title_h3' => '<li>Pencarian dengan kata kunci <b>' . e($kata_kunci) . '</b></li>',
                    'jumlah_data' => '<button class="btn btn-xs btn-default flat">Terdapat <b>' . $res['count'] . ' Record</b></button>',
                );

                if ($res['count'] > 0) {
                    $table = '<div class="box box-primary flat" ><div class="box-body"><table class="table demo-table"><thead><tr><th id="th">NIM</th><th id="th">NAMA MAHASISWA</th><th id="th">PRODI</th><th id="th">FOTO</th><th id="th">TINDAKAN</th></tr></thead>';
                    foreach ($res['data'] as $row) {
                        $img = base_url('assets/foto/' . $row->foto);
                        $table .= '<tr><td align="center">' . $row->nim . '</td><td align="center">' . $row->nama_mahasiswa . '</td><td align="center">' . get_kode_prodi($row->nim)->nama_program_studi . '</td><td align="center"><img height="30px" src="' . $img . '"></td><td align="center">';
                        $table .= '<a href="' . site_url('admin/akademik/mahasiswa/update/' . $row->nim) . '" class=" btn-primary btn-xs flat"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                        $table .= anchor_popup('admin/akademik/mahasiswa/biodata_mahasiswa/' . $row->nim, '<i class="fa fa-eye"></i> Detail', array('class' => 'btn-warning btn-xs flat')) . '&nbsp;';
                        $table .= '<a href="' . site_url('admin/akademik/mahasiswa/cetak/' . $row->nim) . '" class=" btn-info btn-xs flat"><i class="fa fa-print"></i> Cetak</a></td></tr>';
                    }
                    $table .= '</table></div></div>';
                    $data['table'] = $table;
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Tidak ditemukan data mahasiswa dengan kata kunci NIM <b>' . $kata_kunci . '</b> !</div>');
                }
                $this->load->view('admin/template/V_main', $data);
            } else {
                $this->session->set_userdata('kunci', $kata_kunci);
                redirect('admin/akademik/mahasiswa/search_by_name');
            }
        } else {
            $this->search();
        }
    }

    public function search_by_name($offset = 0) {
        $uri_segment = 5;
        $offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
        
        $kunci = $this->session->userdata('kunci');
        $res = $this->mahasiswaservice->searchMahasiswaByNamePaginated($kunci, $this->limit, $offset);

        $config = array(
            'base_url' => site_url('admin/akademik/mahasiswa/search_by_name'),
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
            'content' => 'admin/akademik/mahasiswa/V_search',
            'judul' => 'Akademik',
            'sub_judul' => 'Pencarian Mahasiswa',
            'pagination' => $this->pagination->create_links(),
            'jumlah_data' => '<button class="btn btn-xs btn-default flat">Terdapat <b>' . $res['count'] . ' Record</b></button>',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'title_h3' => '<li>Pencarian dengan kata kunci <b>' . e($kunci) . '</b></li>',
        );

        if ($res['count'] > 0) {
            $no = 1 + $offset;
            $table = '<div class="box box-primary flat" ><div class="box-body"><table class="table demo-table"><thead><tr><th id="th">NO.</th><th id="th">NIM</th><th id="th">NAMA MAHASISWA</th><th id="th">PRODI</th><th id="th">FOTO</th><th id="th">TINDAKAN</th></tr></thead>';
            foreach ($res['data'] as $row) {
                $img = base_url('assets/foto/' . $row->foto);
                $table .= '<tr><td align="center">' . $no++ . '.</td><td align="center">' . $row->nim . '</td><td>' . $row->nama_mahasiswa . '</td><td align="center">' . get_kode_prodi($row->nim)->nama_program_studi . '</td><td align="center"><img height="35px" src="' . $img . '"></td><td align="center">';
                $table .= '<a href="' . site_url('admin/akademik/mahasiswa/update/' . $row->nim) . '" class=" btn-primary btn-xs flat"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                $table .= anchor_popup('admin/akademik/mahasiswa/biodata_mahasiswa/' . $row->nim, '<i class="fa fa-eye"></i> Detail', array('class' => 'btn-warning btn-xs flat')) . '&nbsp;';
                $table .= '<a href="' . site_url('admin/akademik/mahasiswa/cetak/' . $row->nim) . '" class=" btn-info btn-xs flat"><i class="fa fa-print"></i> Cetak</a></td></tr>';
            }
            $table .= '</table></div></div>';
            $data['table'] = $table;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Tidak ditemukan data mahasiswa dengan kata kunci <b>' . $kunci . '</b> !</div>');
        }
        $this->load->view('admin/template/V_main', $data);
    }

    public function reset_sandi() {
        $data = array(
            'content' => 'admin/akademik/mahasiswa/V_reset_sandi',
            'judul' => 'Akademik',
            'sub_judul' => 'Pencarian Mahasiswa',
            'title_h1' => '<li>Akademik</li>',
            'title_h2' => '<li>Mahasiswa</li>',
            'title_h3' => '<li>Pencarian</li>',
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function reset_sandi_process() {
        $kata_kunci = $this->input->post('kata_kunci');
        $this->form_validation->set_rules('kata_kunci', 'kata kunci', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->reset_sandi();
        } else {
            $res = $this->mahasiswaservice->searchMahasiswa('nim', $kata_kunci);
            $data = array(
                'content' => 'admin/akademik/mahasiswa/V_reset_sandi',
                'judul' => 'Akademik',
                'sub_judul' => 'Pencarian Mahasiswa',
                'hidden' => 'hidden',
                'title_h1' => '<li>Akademik</li>',
                'title_h2' => '<li>Mahasiswa</li>',
                'title_h3' => '<li>Pencarian</li>',
            );

            if ($res['count'] > 0) {
                $table = '<div class="box box-primary flat" ><div class="box-body"><table class="table demo-table"><thead><tr><th id="th">NIM</th><th id="th">NAMA MAHASISWA</th><th id="th">TINDAKAN</th></tr></thead>';
                foreach ($res['data'] as $row) {
                    $table .= '<tr><td align="center">' . $row->nim . '</td><td align="center">' . $row->nama_mahasiswa . '</td><td align="center">';
                    $table .= '<a href="' . site_url('admin/akademik/mahasiswa/generate_sandi/' . $row->nim) . '" class=" btn-danger btn-xs flat"><i class="fa fa-refresh"></i> Reset Sandi</a>&nbsp;</td></tr>';
                }
                $table .= '</table></div></div>';
                $data['table'] = $table;
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible flat">Tidak ditemukan data mahasiswa dengan NIM <b>' . $kata_kunci . '</b> !</div>');
            }
            $this->load->view('admin/template/V_main', $data);
        }
    }

    public function generate_sandi($nim) {
        $res = $this->mahasiswaservice->generateSandi($nim);
        if ($res['status']) {
            $data = array(
                'content' => 'admin/akademik/mahasiswa/V_sandi',
                'judul' => 'Akademik',
                'sub_judul' => 'Sandi Mahasiswa',
                'kirim_string' => $res['sandi'],
                'data_mahasiswa' => $this->mahasiswaservice->getMahasiswaByNim($nim)
            );
            $this->session->set_flashdata('info_reset_berhasil', '<script>swal("Sukses!","Password Berhasil di ganti","success")</script>');
            $this->load->view('admin/template/V_main', $data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible flat">Data mahasiswa dengan NIM <b>' . e($nim) . '</b> tidak ditemukan!</div>');
            redirect('admin/akademik/mahasiswa/reset_sandi');
        }
    }

    public function cetak_per_angkatan_jurusan() {
        $angkatan = $this->session->userdata('nama_angkatan');
        $kode_program_studi = $this->session->userdata('kode_program_studi');

        $res = $this->mahasiswaservice->getMahasiswaCetak($angkatan, $kode_program_studi);
        $query = $res['data'];
        $singkatan = $res['singkatan'];

        $table = '<table border="1"><tr><th>NO.</th><th>NIM</th><th>NPM</th><th>NO.PENDAFTARAN</th><th>NO.PENDAFTARAN ULANG</th><th>NAMA MAHASISWA</th><th>TEMPAT LAHIR</th><th>TANGGAL LAHIR</th><th>ALAMAT</th><th>KOTA</th><th>PROPINSI</th><th>NO.TELEPON</th><th>JENIS KELAMIN</th><th>AGAMA</th><th>GOLONGAN DARAH</th><th>KEWARGANEGARAAN</th><th>NAMA INSTANSI</th><th>EMAIL</th><th>NAMA AYAH</th><th>AGAMA AYAH</th><th>PEKERJAAN AYAH</th><th>NAMA IBU</th><th>AGAMA IBU</th><th>PEKERJAAN IBU</th><th>ALAMAT ORANG TUA</th><th>KOTA ORANG TUA</th><th>PROPINSI ORANG TUA</th><th>NO.TELEPON ORANG TUA</th><th>STATUS</th><th>STATUS PENDAFTARAN</th></tr>';
        $i = 0;
        foreach ($query as $row) {
            $table .= '<tr><td><div align="center">' . ++$i . '.</div></td><td><div align="center">' . $row->nim . '</div></td><td>' . $row->npm . '</td><td>' . $row->nomor_pendaftaran . '</td><td>' . $row->nomor_pendaftaran_ulang . '</td><td>' . $row->nama_mahasiswa . '</td><td>' . $row->tempat_lahir . '</td><td>' . $row->tanggal_lahir . '</td><td>' . $row->alamat . '</td><td>' . $row->kota . '</td><td>' . $row->propinsi . '</td><td>' . $row->telepon . '</td><td>' . $row->jenis_kelamin . '</td><td>' . $row->agama . '</td><td>' . $row->golongan_darah . '</td><td>' . $row->kewarganegaraan . '</td><td>' . $row->nama_instansi . '</td><td>' . $row->email . '</td><td>' . $row->nama_ayah . '</td><td>' . $row->agama_ayah . '</td><td>' . $row->pekerjaan_ayah . '</td><td>' . $row->nama_ibu . '</td><td>' . $row->agama_ibu . '</td><td>' . $row->pekerjaan_ibu . '</td><td>' . $row->alamat_orangtua . '</td><td>' . $row->kota_orangtua . '</td><td>' . $row->propinsi_orangtua . '</td><td>' . $row->telepon_orangtua . '</td><td>' . $row->status . '</td><td>' . $row->status_pendaftaran . '</td></tr>';
        }
        $table .= '</table>';

        $data = array(
            'table' => $table,
            'file_name' => $singkatan . " Angkatan 20" . $angkatan,
        );

        $this->load->view('admin/akademik/krs/V_spreadsheet_view', $data);
    }

    public function cetak($nim) {
        $data['mahasiswa'] = $this->mahasiswaservice->getMahasiswaByNim($nim);

        $content = $this->load->view('admin/akademik/mahasiswa/V_cetak_mahasiswa', $data, true);
        $header = $this->load->view('admin/akademik/mahasiswa/V_header_cetak_mahasiswa', '', true);
        $this->load->library('m_pdf');
        $this->m_pdf->reinitialize(['mode' => 'win-1252', 'format' => 'Folio', 'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 45, 'margin_bottom' => 20, 'margin_header' => 10, 'margin_footer' => 10]);
        $mpdf = $this->m_pdf;
        $mpdf->SetHeader($header);
        $mpdf->WriteHTML($content);
        $mpdf->Output('data_mahasiswa.pdf', "D");
    }

    public function biodata_mahasiswa($nim) {
        $data = array(
            'content' => 'admin/V_biodata_mahasiswa',
            'data' => $this->mahasiswaservice->getMahasiswaByNim($nim),
        );
        $this->load->view('admin/template/V_open_window', $data);
    }

    public function upload_image($nim) {
        $res = $this->mahasiswaservice->uploadImage($nim, $nim, './assets/foto/');
        echo json_encode($res);
    }
}
