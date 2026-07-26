<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Matakuliah extends CI_Controller
{
    var $limit = 500;

    public function __construct()
    {
        parent::__construct();
        $this->load->service('MatakuliahService');
        
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
        $this->load->library('pagination');
    }

    public function index($offset = 0)
    {
        $data = array(
            'content' => 'admin/jurusan/kurikulum/matakuliah/V_index',
            'judul' => 'Jurusan',
            'sub_judul' => 'Matakuliah',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Matakuliah</li>',
            'kompetensi' => $this->matakuliahservice->getKompetensi(),
            'jurusan' => $this->matakuliahservice->getJurusan(),
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function all($offset = 0)
    {
        if (!$this->session->userdata('mak_prodi') || $this->input->post('kode_program_studi')) {
            $kode_prodi = $this->input->post('kode_program_studi') ? $this->input->post('kode_program_studi') : $this->session->userdata('mak_prodi');
            $this->session->set_userdata(array('mak_prodi' => $kode_prodi));
        }
        $kode_prod = $this->session->userdata('mak_prodi');

        $uri_segment = 6;
        $offset = ($this->uri->segment($uri_segment) == FALSE) ? 0 : $this->uri->segment($uri_segment);

        $data_count = $this->matakuliahservice->countMatakuliahByProdi($kode_prod);

        $config = array(
            'base_url' => site_url('admin/jurusan/kurikulum/matakuliah/all'),
            'total_rows' => $data_count,
            'per_page' => $this->limit,
            'uri_segment' => $uri_segment,
            'full_tag_open' => '<div class="btn-group">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a href="#!" class="btn btn-sm flat btn-default disabled">',
            'cur_tag_close' => '</a>',
            'attributes' => array('class' => 'btn flat btn-sm btn-default'),
        );
        $this->pagination->initialize($config);

        $data = array(
            'judul' => 'Jurusan',
            'sub_judul' => 'Matakuliah',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Matakuliah</li>',
            'data' => $this->matakuliahservice->getMatakuliahByProdi($kode_prod, $this->limit, $offset),
            'kompetensi' => $this->matakuliahservice->getKompetensi(),
            'jurusan' => $this->matakuliahservice->getJurusan(),
            'halaman' => $this->pagination->create_links(),
            'jumlah_data' => $data_count,
        );

        $this->load->view('admin/jurusan/kurikulum/matakuliah/V_Matakuliah', $data);
    }

    public function cari($offset = 0)
    {
        $kode_prod = $this->session->userdata('mak_prodi');
        if (!$this->session->userdata('keyword_sess') || $this->input->post('keyword')) {
            $key = $this->input->post('keyword') ? $this->input->post('keyword') : $this->session->userdata('keyword_sess');
            $this->session->set_userdata(array('keyword_sess' => $key));
        }
        $keyword = $this->session->userdata('keyword_sess');

        $uri_segment = 6;
        $offset = ($this->uri->segment($uri_segment) == FALSE) ? 0 : $this->uri->segment($uri_segment);

        $data_count = $this->matakuliahservice->countMatakuliahCari($keyword, $kode_prod);

        $config = array(
            'base_url' => site_url('admin/jurusan/kurikulum/matakuliah/cari'),
            'total_rows' => $data_count,
            'per_page' => $this->limit,
            'uri_segment' => $uri_segment,
            'full_tag_open' => '<div class="btn-group">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a href="#!" class="btn btn-sm flat btn-default disabled">',
            'cur_tag_close' => '</a>',
            'attributes' => array('class' => 'btn flat btn-sm btn-default'),
        );
        $this->pagination->initialize($config);

        $data = array(
            'judul' => 'Jurusan',
            'sub_judul' => 'Matakuliah',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Matakuliah</li>',
            'data' => $this->matakuliahservice->getMatakuliahCari($keyword, $kode_prod, $this->limit, $offset),
            'kompetensi' => $this->matakuliahservice->getKompetensi(),
            'jurusan' => $this->matakuliahservice->getJurusan(),
            'halaman' => $this->pagination->create_links(),
            'jumlah_data' => $data_count,
        );

        $this->load->view('admin/jurusan/kurikulum/matakuliah/V_Matakuliah', $data);
    }

    public function tambah_matakuliah()
    {
        $res = $this->matakuliahservice->simpanMatakuliah($this->input->post());
        echo json_encode($res);
    }

    public function edit()
    {
        $id = $this->input->post('id_matakuliah');
        $data = $this->matakuliahservice->getMatakuliahByKode($id);
        echo json_encode($data);
    }

    public function ubah_matakuliah()
    {
        $res = $this->matakuliahservice->ubahMatakuliah($this->input->post());
        echo json_encode($res);
    }

    public function hapus($id)
    {
        $res = $this->matakuliahservice->hapusMatakuliah($id);
        echo json_encode($res);
    }
}
