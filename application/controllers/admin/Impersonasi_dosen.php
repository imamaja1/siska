<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Impersonasi_dosen extends CI_Controller
{
    protected $limit = 20;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_dosen',
            'jurusan/program_studi/nama_jurusan_model',
        ));
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
    }

    public function index($offset = 0)
    {
        $keyword = $this->input->get('q');
        $prodi_filter = $this->input->get('prodi');
        $uri_segment = 4;
        $offset = $this->uri->segment($uri_segment) ?: 0;

        $data['list_prodi'] = $this->nama_jurusan_model->get();
        $data['prodi_filter'] = $prodi_filter;

        if ($keyword) {
            $data['dosen'] = $this->m_dosen->get_pagination_search($keyword);
        } else {
            $this->db->select('count(*) as total', false);
            $this->db->from('dosen');
            if ($prodi_filter) {
                $this->db->where('homebase', $prodi_filter);
            }
            $total = $this->db->get()->row()->total;

            $this->db->select('kode_dosen, nama_dosen');
            $this->db->from('dosen');
            $this->db->order_by('kode_dosen', 'asc');
            if ($prodi_filter) {
                $this->db->where('homebase', $prodi_filter);
            }

            $url_params = http_build_query(array_filter(['prodi' => $prodi_filter]));
            $base_url = site_url('admin/impersonasi_dosen/index');
            if ($url_params) {
                $base_url .= '?' . $url_params;
            }

            if ($total > $this->limit) {
                $this->load->library('pagination');
                $this->pagination->initialize([
                    'base_url'       => $base_url,
                    'total_rows'     => $total,
                    'per_page'       => $this->limit,
                    'uri_segment'    => $uri_segment,
                    'full_tag_open'  => '<div class="btn-group">',
                    'full_tag_close' => '</div>',
                    'cur_tag_open'   => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
                    'cur_tag_close'  => '</a>',
                    'attributes'     => ['class' => 'btn btn-flat btn-xs btn-default'],
                ]);
                $data['pagination'] = $this->pagination->create_links();
            }
            $data['dosen'] = $this->db->limit($this->limit, $offset)->get()->result();
        }

        $this->load->view('admin/template/V_main', [
            'content'   => 'admin/impersonasi/V_dosen',
            'judul'     => 'Impersonasi',
            'sub_judul' => 'Dosen',
            'title_h1'  => '<li>Impersonasi</li>',
            'title_h2'  => '<li>Dosen</li>',
        ] + $data);
    }

    public function menyamar($kode_dosen)
    {
        $dosen = $this->m_dosen->get_dosen_by_kode($kode_dosen);
        if (!$dosen) {
            show_404();
        }

        $this->session->set_userdata('is_impersonating', true);
        $this->session->set_userdata('original_admin', [
            'nama_pengguna' => $this->session->userdata('nama_pengguna'),
            'nama_login'    => $this->session->userdata('nama_login'),
            'kode_pengguna' => $this->session->userdata('kode_pengguna'),
            'id_role'       => $this->session->userdata('id_role'),
            'id'            => $this->session->userdata('id'),
        ]);

        $this->session->set_userdata([
            'kode_dosen'   => $dosen->kode_dosen,
            'nama_dosen'   => $dosen->nama_dosen,
            'alamat_email' => $dosen->alamat_email,
        ]);

        redirect('home/dosen');
    }

    public function kembali()
    {
        $original = $this->session->userdata('original_admin');
        if (!$original) {
            redirect('home/admin');
        }

        $this->session->unset_userdata('is_impersonating');
        $this->session->unset_userdata('original_admin');

        $this->session->set_userdata($original);

        redirect('home/admin');
    }
}
