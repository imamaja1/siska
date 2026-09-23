<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_kelas extends CI_Controller {

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
        $data['sub_judul'] = 'Nilai SISKA vs Feeder - Kelas';
        $data['content'] = 'admin/audit/V_feeder_kelas';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['prodi'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function hasil()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_program_studi = $this->input->post('kode_program_studi');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $kode_matakuliah = $this->input->post('kode_matakuliah');
        $nama_kelas_id = $this->input->post('nama_kelas_id');

        $data = $this->auditfeederservice->hasilKelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah, $kode_matakuliah, $nama_kelas_id);
        if (isset($data['error'])) {
            echo '<div class="callout callout-danger flat"><p>' . html_escape($data['error']) . '</p></div>';
            return;
        }

        $this->load->view('admin/audit/V_feeder_hasil', $data);
    }

    public function get_matakuliah($kode_tahun_akademik, $kode_program_studi)
    {
        $rows = $this->Audit_feeder_model->getMatakuliahByProdiTa($kode_tahun_akademik, $kode_program_studi);
        echo json_encode($rows);
    }

    public function get_kelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah)
    {
        $rows = $this->Audit_feeder_model->getKelasByMatakuliah($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        echo json_encode($rows);
    }
}
