<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Feeder extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->service('FeederService');

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
        $config = $this->feederservice->getConfig();

        $data = [
            'content'   => 'admin/pengaturan/feeder/V_index',
            'judul'     => 'Pengaturan',
            'sub_judul' => 'Konfigurasi Feeder',
            'title_h1'  => '<li>Pengaturan</li>',
            'title_h2'  => '<li>Konfigurasi Feeder</li>',
            'feeder'    => $config,
        ];
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan()
    {
        $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-info"><h6>Konfigurasi Feeder dikelola melalui aplikasi Filament. Perubahan pada halaman ini tidak disimpan.</h6></div>');
        redirect(site_url('admin/pengaturan/feeder'), 'refresh');
    }

    public function test_koneksi()
    {
        $result = $this->feederservice->testConnection();
        echo json_encode($result);
    }
}
