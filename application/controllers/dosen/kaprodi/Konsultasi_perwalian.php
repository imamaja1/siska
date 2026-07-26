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
            'jurusan/m_dosen',
            'jurusan/Perwalian_model',
        ));
        $this->load->service('KaprodiService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))){
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function index(){

        $data['content'] = 'dosen/kaprodi/perwalian/V_index';
        $data['judul'] = 'Perwalian';
        $data['sub_judul'] = 'Konsultasi Perwalian';
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();

        $this->load->view('dosen/template/V_main', $data);
    }

    public function filter() {
        $angkatan = $this->input->post('angkatan');
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_row($this->session->userdata('kode_dosen'));
        $kode_program_studi = $prodi->kode_program_studi;

        $data_session = array(
            'sess_angkatan' => $angkatan,
            'sess_kode_program_studi' => $kode_program_studi
        );

        $this->session->set_userdata($data_session);
        redirect(site_url('dosen/kaprodi/konsultasi_perwalian/data_mahasiswa'));
    }

    public function data_mahasiswa() {
        $data['judul'] = 'Perwalian';
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
            $config['base_url'] = site_url('dosen/kaprodi/konsultasi_perwalian/data_mahasiswa');
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
        $this->load->view('dosen/kaprodi/perwalian/V_data_mahasiswa', $data);
    }

    public function cari()
    {
        $key = $this->input->post('keyword');
        $this->session->set_userdata(array('sess_keyword'=>$key));
        redirect(site_url('dosen/kaprodi/konsultasi_perwalian/data_cari'));
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
            $config['base_url'] = site_url('dosen/kaprodi/konsultasi_perwalian/data_cari');
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
        $this->load->view('dosen/kaprodi/perwalian/V_data_mahasiswa', $data);
    }

    public function detail($nim){
        $data['perwalian'] = $this->kaprodiservice->get_detail_perwalian($nim);
        $data['data'] = $this->konsultasi_perwalian_model->detail($nim);
        $this->load->view('dosen/kaprodi/perwalian/V_Detail', $data);
    }

    public function print_view($nim){
        $data['perwalian'] = $this->kaprodiservice->get_detail_perwalian_print($nim);
        $data['data'] = $this->konsultasi_perwalian_model->detail($nim);
        $data['prodi'] = get_kode_prodi($nim);

        $this->load->view('dosen/kaprodi/perwalian/print_view', $data);
    }

    public function grafik_nilai($nim){
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $data['mahasiswa'] = $this->kaprodiservice->get_mahasiswa_by_nim($nim);
        $data_krs = $this->kaprodiservice->get_krs_by_nim($nim);

        $i = 0;
        foreach ($data_krs as $row){
            $data['ipk'][$i] = $this->laporan_model->ipok($nim, $kode_nama_kurikulum, $row->kode_tahun_akademik)['ipk'];
            $data['semester'][$i] = 'Semester '.$row->semester;
            $i++;
        }
        $this->load->view('dosen/kaprodi/perwalian/grafik', $data);
    }

    public function edit_konsultasi($id){
        $data = $this->kaprodiservice->get_konsultasi_perwalian($id);
        echo json_encode($data);
    }

    public function update_konsultasi($kode_konsultasi){
        $data = $this->input->post();
        $this->kaprodiservice->update('konsultasi_perwalian', $data, array('kode_konsultasi_perwalian' => $kode_konsultasi));
    }
  
      public function autocomplatedosen()
    {
        $keyword = $this->input->post('keyword');
        $result = $this->kaprodiservice->autocomplete_dosen($keyword);
        if ($keyword !== "") {
            if (!empty($result)) {
                echo '<ul id="nim-list" class="list-group">';
                foreach ($result as $row) {
                    $nama = "'$row->nama_dosen'";
                    echo '<li onClick="selectDosen(' . $row->kode_dosen . ',' . $nama . ')" class="list-group-item">' . $row->nama_dosen . '</li>';
                }
                echo '</ul>';
            } else {
                echo "Data tidak ditemukan";
            }
        }
    }

    public function filter_perdosen($kode_dosen)
    {
        $data_perwalian = $this->kaprodiservice->get_perwalian_by_dosen($kode_dosen);
        $data_dosen_perwalian = $this->kaprodiservice->get_dosen_perwalian();

        $data = array(
            'sub_judul' => 'Data Perwalian Mahasiswa',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Perwalian</li>',
            'title_h3' => '<li>Data Perwalian Mahasiswa</li>',
            'dosen_pengganti' => $this->m_dosen->get_dosen_pengganti($kode_dosen),
            'nama_dosen' => $this->m_dosen->get_nama($kode_dosen),
            'kode_dosen' => $kode_dosen,
            'dosen_perwalian' => $data_dosen_perwalian,
            'data' => $data_perwalian,
        );
        $this->load->view('dosen/kaprodi/perwalian/v_filter_per_dosen', $data);
    }

    public function rekap_dosen_wali($kode_dosen = 0)
    {
        if ($kode_dosen == 0) {
            $data['filename'] = 'Rekap Prewalian Mahasiswa ' . date('d-M-Y H:i:s');
            $data['data'] = $this->Perwalian_model->rekap_dosen_wali();
        } else {
            $data['filename'] = "Rekap Prewalian Perdosen " . date('d-M-Y H:i:s');
            $data['data'] = $this->Perwalian_model->rekap_dosen_wali_perdosen($kode_dosen);
        }
        $this->load->view('dosen/kaprodi/perwalian/export_excel', $data);
    }

}
