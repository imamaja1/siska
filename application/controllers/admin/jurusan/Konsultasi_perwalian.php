<?php

class Konsultasi_perwalian extends CI_Controller
{
    var $limit = 25;
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'laporan/laporan_model',
        ));
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
        $this->load->library('pagination');
        $this->load->service('PerwalianService');
        $this->load->service('MahasiswaService');
    }

    public function index(){

        $data['content'] = 'admin/jurusan/konsultasi_perwalian/V_index';
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Konsultasi Perwalian';
        $data['title_h1'] = '<i class="fa fa-map"></i> <li>Jurusan</li>';
        $data['title_h2'] = '<li>Konsultasi Perwalian</li>';
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');

        $data_session = array(
            'sess_angkatan' => $angkatan,
            'sess_kode_program_studi' => $kode_program_studi
        );

        $this->session->set_userdata($data_session);
        redirect(site_url('admin/jurusan/konsultasi_perwalian/data_mahasiswa'));
    }

    public function data_mahasiswa() {
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Konsultasi Perwalian';
        $angkatan = $this->session->userdata('sess_angkatan');
        $kode_program_studi = $this->session->userdata('sess_kode_program_studi');

        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }
        $data['data'] = $this->konsultasi_perwalian_model->filter($angkatan, $kode_program_studi, $this->limit, $offset);
        $data_count = count($this->konsultasi_perwalian_model->count_data_filter($angkatan, $kode_program_studi));

        if ($data_count > 0) {
            $config['base_url'] = site_url('admin/jurusan/konsultasi_perwalian/data_mahasiswa');
            $config['total_rows'] = $data_count;
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $uri_segment;

            $config['full_tag_open'] = '<div class="btn-group" id="halaman">';
            $config['full_tag_close'] = '</div>';

            $config['cur_tag_open'] = '<a href="#!" class="btn btn-sm flat btn-primary disabled">';
            $config['cur_tag_close'] = '</a>';
            $config['attributes'] = array('class' => 'btn flat btn-sm btn-default');

            $this->pagination->initialize($config);

            $data['halaman'] = $this->pagination->create_links();
            $data['jumlah_data'] = $data_count;
        } else {
            $this->session->set_flashdata('keterangan', 'Tidak ditemukan satupun data mahasiswa untuk Angkatan dan Jurusan !');
        }
        $this->load->view('admin/jurusan/konsultasi_perwalian/V_data_mahasiswa', $data);
    }

    public function cari()
    {
        $key = $this->input->post('keyword');
        $this->session->set_userdata(array('sess_keyword'=>$key));
        redirect(site_url('admin/jurusan/konsultasi_perwalian/data_cari'));
    }

    public function data_cari()
    {
        $keyword = $this->session->userdata('sess_keyword');
        $uri_segment = 5;
        if ($this->uri->segment($uri_segment) == FALSE) {
            $offset = 0;
        } else {
            $offset = $this->uri->segment($uri_segment);
        }
        $data['data'] = $this->konsultasi_perwalian_model->cari($keyword, $this->limit, $offset);
        $data_count = count($this->konsultasi_perwalian_model->count_cari($keyword));

        if ($data_count > 0) {
            $config['base_url'] = site_url('admin/akademik/konsultasi_perwalian/data_cari');
            $config['total_rows'] = $data_count;
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $uri_segment;

            $config['full_tag_open'] = '<div class="btn-group" id="halaman">';
            $config['full_tag_close'] = '</div>';

            $config['cur_tag_open'] = '<a href="#!" class="btn btn-sm flat btn-primary disabled">';
            $config['cur_tag_close'] = '</a>';
            $config['attributes'] = array('class' => 'btn flat btn-sm btn-default');

            $this->pagination->initialize($config);

            $data['halaman'] = $this->pagination->create_links();
            $data['jumlah_data'] = $data_count;
        } else {
            $this->session->set_flashdata('keterangan', 'Tidak ditemukan satupun data mahasiswa untuk Angkatan dan Jurusan !');
        }
        $this->load->view('admin/jurusan/konsultasi_perwalian/V_data_mahasiswa', $data);
    }

    public function detail($nim){
        $data['perwalian'] = $this->perwalianservice->getPerwalianDetailByNim($nim);
        $data['data'] = $this->konsultasi_perwalian_model->detail_manipulasi($nim);
        $this->load->view('admin/jurusan/konsultasi_perwalian/V_Detail', $data);
    }

    public function print_view($nim){
        $data['perwalian'] = $this->perwalianservice->getPerwalianDetailByNim($nim);
        $data['data'] = $this->konsultasi_perwalian_model->detail($nim);
        $data['prodi'] = get_kode_prodi($nim);

        $this->load->view('admin/jurusan/konsultasi_perwalian/print_view', $data);
    }

    public function grafik_nilai($nim){
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data['mahasiswa'] = $this->mahasiswaservice->getMahasiswaRowByNim($nim);
        $data_krs = $this->perwalianservice->getKrsByNim($nim);
        $i = 0;
        foreach ($data_krs as $row){
            $data['ipk'][$i] = $this->laporan_model->ipok($nim, $kode_nama_kurikulum, $row->kode_tahun_akademik)['ipk'];
            $data['semester'][$i] = 'Semester '.$row->semester;
            $i++;
        }
        $this->load->view('admin/jurusan/konsultasi_perwalian/grafik', $data);
    }

}