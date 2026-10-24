<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_petikan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->service('AuditFeederService');
        $this->load->model('Audit_feeder_model');

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
        $data['sub_judul'] = 'Feeder Petikan Nilai';
        $data['content'] = 'admin/audit/V_feeder_petikan_index';

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $nim = $this->input->post('nim');

        $data = $this->auditfeederservice->petikanFeeder($nim);
        $data['nim'] = $nim;
        if (isset($data['error'])) {
            echo '<div class="col-md-12"><div class="callout callout-danger flat"><p>' . html_escape($data['error']) . '</p></div></div>';
            return;
        }

        $this->load->view('admin/audit/V_feeder_petikan', $data);
    }

    public function cek_null_siska()
    {
        $nim                 = $this->input->post('nim');
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_matakuliah     = $this->input->post('kode_matakuliah');

        $data = $this->Audit_feeder_model->getKhsDanDummyByNimMatakuliah($nim, $kode_tahun_akademik, $kode_matakuliah);

        echo json_encode([
            'khs'         => $data['khs'],
            'dummy'       => $data['dummy'],
            'dummy_nilai' => $data['dummy_nilai'],
            'hanya_khs'   => !empty($data['hanya_khs']),
        ]);
    }
}
