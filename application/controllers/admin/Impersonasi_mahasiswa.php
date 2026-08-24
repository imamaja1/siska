<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Impersonasi_mahasiswa extends CI_Controller
{
    protected $limit = 20;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'akademik/Mahasiswa_model',
            'jurusan/m_tahun_akademik',
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

    public function index()
    {
        $keyword = trim($this->input->get('q'));
        $prodi_filter = $this->input->get('prodi');
        $angkatan_filter = $this->input->get('angkatan');
        $offset = (int) $this->input->get('hal') ?: 0;

        $data['list_prodi'] = $this->nama_jurusan_model->get();
        $data['list_angkatan'] = $this->db->query("select distinct(substr(nim,1,2)) as angkatan from mahasiswa order by angkatan desc")->result();
        $data['prodi_filter'] = $prodi_filter;
        $data['angkatan_filter'] = $angkatan_filter;

        $apply_filter = function ($db) use ($prodi_filter, $angkatan_filter, $keyword) {
            $db->from('mahasiswa as mah');
            $db->join('program_studi as ps', 'mah.program_studi_kode = ps.kode_program_studi', 'left');
            if ($prodi_filter) {
                $db->where('mah.program_studi_kode', $prodi_filter);
            }
            if ($angkatan_filter) {
                $db->where('substr(mah.nim,1,2)', $angkatan_filter);
            }
            if ($keyword !== '') {
                $db->group_start()
                   ->like('mah.nim', $keyword)
                   ->or_like('mah.nama_mahasiswa', $keyword)
                   ->group_end();
            }
        };

        $this->db->select('count(*) as total', false);
        $apply_filter($this->db);
        $total = $this->db->get()->row()->total;

        $this->db->select('mah.nim, mah.nama_mahasiswa, ps.nama_program_studi');
        $apply_filter($this->db);
        $this->db->order_by('mah.nim', 'desc');

        if ($total > $this->limit) {
            $this->load->library('pagination');
            $this->pagination->initialize([
                'base_url'             => site_url('admin/impersonasi_mahasiswa/index'),
                'total_rows'           => $total,
                'per_page'             => $this->limit,
                'page_query_string'    => TRUE,
                'query_string_segment' => 'hal',
                'full_tag_open'        => '<div class="btn-group">',
                'full_tag_close'       => '</div>',
                'cur_tag_open'         => '<a href="#!" class="btn btn-xs btn-flat btn-default disabled">',
                'cur_tag_close'        => '</a>',
                'attributes'           => ['class' => 'btn btn-flat btn-xs btn-default'],
            ]);
        }
        $data['pagination'] = ($total > $this->limit) ? $this->pagination->create_links() : '';
        $data['mahasiswa'] = $this->db->limit($this->limit, $offset)->get()->result();

        $this->load->view('admin/template/V_main', [
            'content'   => 'admin/impersonasi/V_mahasiswa',
            'judul'     => 'Impersonasi',
            'sub_judul' => 'Mahasiswa',
            'title_h1'  => '<li>Impersonasi</li>',
            'title_h2'  => '<li>Mahasiswa</li>',
        ] + $data);
    }

    public function menyamar($nim)
    {
        $mahasiswa = $this->Mahasiswa_model->get_mahasiswa_by_nim($nim);
        if (!$mahasiswa) {
            show_404();
        }

        $kode_program_studi = $mahasiswa->program_studi_kode;
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);

        $this->session->set_userdata('is_impersonating', true);
        $this->session->set_userdata('original_admin', [
            'nama_pengguna' => $this->session->userdata('nama_pengguna'),
            'nama_login'    => $this->session->userdata('nama_login'),
            'kode_pengguna' => $this->session->userdata('kode_pengguna'),
            'id_role'       => $this->session->userdata('id_role'),
            'id'            => $this->session->userdata('id'),
        ]);

        $this->session->set_userdata([
            'nim'                 => $mahasiswa->nim,
            'nama_mahasiswa'      => $mahasiswa->nama_mahasiswa,
            'kode_program_studi'  => $kode_program_studi,
            'kode_nama_kurikulum' => $kode_nama_kurikulum,
            'status'              => 'login_mahasiswa',
            'foto'                => $mahasiswa->foto,
            'jenis_kelamin'       => $mahasiswa->jenis_kelamin,
        ]);

        redirect('mahasiswa/home');
    }
}
