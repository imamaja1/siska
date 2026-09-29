<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pure_feeder extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->service('AuditFeederService');

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
        $data['sub_judul'] = 'Pure Feeder';
        $data['content'] = 'admin/audit/V_pure_feeder_index';

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $nim = $this->input->post('nim');

        $data = $this->auditfeederservice->rawFeederData($nim);
        $data['nim'] = $nim;
        if (isset($data['error'])) {
            echo '<div class="col-md-12"><div class="callout callout-danger flat"><p>' . html_escape($data['error']) . '</p></div></div>';
            return;
        }

        $this->load->view('admin/audit/V_pure_feeder_hasil', $data);
    }
}
