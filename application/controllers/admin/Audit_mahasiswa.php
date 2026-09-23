<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_mahasiswa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->service('AuditFeederService');
        $this->load->model('jurusan/m_tahun_akademik');

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

    public function index()
    {
        $data['judul'] = 'Audit Nilai';
        $data['sub_judul'] = 'Nilai SISKA vs Feeder - Mahasiswa';
        $data['content'] = 'admin/audit/V_feeder_mahasiswa';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $nim = $this->input->post('nim');

        $data = $this->auditfeederservice->hasilMahasiswa($nim, $kode_tahun_akademik);
        if (isset($data['error'])) {
            echo '<div class="callout callout-danger flat"><p>' . html_escape($data['error']) . '</p></div>';
            return;
        }

        $this->load->view('admin/audit/V_feeder_hasil', $data);
    }
}
