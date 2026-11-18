<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_mahasiswa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->service('AuditFeederService');
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'Audit_feeder_model',
        ));

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
        $data['sub_judul'] = 'Feeder Mahasiswa';
        $data['content'] = 'admin/audit/V_feeder_mahasiswa_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('kode_program_studi');

        $data = $this->auditfeederservice->bandingkanMahasiswa($angkatan, $kode_program_studi);
        if (isset($data['error'])) {
            echo '<div class="col-md-12"><div class="callout callout-danger flat"><p><i class="fa fa-exclamation-triangle"></i> ' . html_escape($data['error']) . '</p></div></div>';
            return;
        }

        $this->load->view('admin/audit/V_feeder_mahasiswa_hasil', $data);
    }

    public function detail_nim()
    {
        $nim = $this->input->post('nim');
        $from_sync = (bool) $this->input->post('from_sync');

        $data = $this->auditfeederservice->auditBiodataNim($nim, $from_sync);
        if (isset($data['error'])) {
            echo '<div class="callout callout-danger flat"><p><i class="fa fa-exclamation-triangle"></i> ' . html_escape($data['error']) . '</p></div>';
            return;
        }

        $this->load->view('admin/audit/V_feeder_mahasiswa_detail', $data);
    }

    public function sinkron_feeder_ke_siska()
    {
        $nim = $this->input->post('nim');
        $field_key = $this->input->post('field_key') ?: 'all';

        $res = $this->auditfeederservice->sinkronBiodataFeederKeSiska($nim, $field_key);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($res));
    }
}
