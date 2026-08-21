<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{
    protected $config_file;

    public function __construct()
    {
        parent::__construct();
        $this->config_file = APPPATH . 'config/config.php';

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
        $this->load->view('admin/template/V_main', [
            'content'    => 'admin/pengaturan/V_smtp',
            'judul'      => 'Pengaturan',
            'sub_judul'  => 'Konfigurasi SMTP Email',
            'title_h1'   => 'Pengaturan SMTP Email',
            'smtp'       => $this->get_smtp_config(),
        ]);
    }

    public function simpan()
    {
        $this->form_validation->set_rules('smtp_host', 'SMTP Host', 'trim|required');
        $this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|required|numeric');
        $this->form_validation->set_rules('smtp_user', 'SMTP User', 'trim|required|valid_email');
        $this->form_validation->set_rules('smtp_timeout', 'SMTP Timeout', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . validation_errors() . '</h6></div>');
            redirect(site_url('admin/pengaturan/pengaturan'), 'refresh');
        }

        $data = [
            'smtp_host'    => $this->input->post('smtp_host'),
            'smtp_port'    => $this->input->post('smtp_port'),
            'smtp_user'    => $this->input->post('smtp_user'),
            'smtp_timeout' => $this->input->post('smtp_timeout'),
        ];

        $pass = $this->input->post('smtp_pass');
        if ($pass !== '') {
            $data['smtp_pass'] = $pass;
        } else {
            $current = $this->get_smtp_config();
            $data['smtp_pass'] = $current['smtp_pass'];
        }

        if ($this->write_smtp_config($data)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Konfigurasi SMTP berhasil disimpan.</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal menyimpan konfigurasi SMTP. Periksa permission file config.php.</h6></div>');
        }

        redirect(site_url('admin/pengaturan/pengaturan'), 'refresh');
    }

    public function test_email()
    {
        $smtp = $this->get_smtp_config();

        $to = $this->input->post('test_email_to');
        if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Alamat email tujuan test tidak valid.</h6></div>');
            redirect(site_url('admin/pengaturan/pengaturan'), 'refresh');
        }

        $this->load->library('email');
        $config = [
            'charset'      => 'utf-8',
            'useragent'    => 'Codeigniter',
            'protocol'     => 'smtp',
            'mailtype'     => 'html',
            'smtp_host'    => $smtp['smtp_host'],
            'smtp_port'    => $smtp['smtp_port'],
            'smtp_timeout' => $smtp['smtp_timeout'],
            'smtp_user'    => $smtp['smtp_user'],
            'smtp_pass'    => $smtp['smtp_pass'],
            'crlf'         => "\r\n",
            'newline'      => "\r\n",
        ];

        $this->email->initialize($config);
        $this->email->from($smtp['smtp_user'], 'SISKA UBG');
        $this->email->to($to);
        $this->email->subject('Test Email Konfigurasi SMTP');
        $this->email->message('<p>Test email berhasil dikirim. Konfigurasi SMTP Anda berfungsi dengan baik.</p>');

        if ($this->email->send()) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Test email berhasil dikirim.</h6></div>');
        } else {
            log_message('error', 'SMTP test email gagal: ' . $this->email->print_debugger());
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal mengirim test email. Periksa konfigurasi SMTP.</h6></div>');
        }

        redirect(site_url('admin/pengaturan/pengaturan'), 'refresh');
    }

    private function get_smtp_config()
    {
        $content = file_get_contents($this->config_file);
        $keys = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_timeout'];
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->extract_value($content, $key);
        }
        return $result;
    }

    private function extract_value($content, $key)
    {
        $pattern = "/\\\$config\['{$key}'\]\s*=\s*'([^']*)'/";
        if (preg_match($pattern, $content, $m)) {
            return $m[1];
        }
        return '';
    }

    private function write_smtp_config($data)
    {
        $content = file_get_contents($this->config_file);
        if ($content === false) {
            return false;
        }

        $replace = [];
        foreach ($data as $key => $value) {
            $pattern = "/\\\$config\['{$key}'\]([ \t]*)=\s*'[^']*'/";
            $replacement = "\$config['{$key}']$1= '" . str_replace("'", "\\'", $value) . "'";
            $replace[$key] = [$pattern, $replacement];
        }

        $new_content = $content;
        foreach ($replace as $key => $pair) {
            $new_content = preg_replace($pair[0], $pair[1], $new_content, 1);
        }

        if ($new_content === $content) {
            return false;
        }

        return file_put_contents($this->config_file, $new_content, LOCK_EX) !== false;
    }
}
