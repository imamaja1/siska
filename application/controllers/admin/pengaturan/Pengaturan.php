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

    public function krs_kpat()
    {
        $this->load->model(array(
            'akademik/Krs_kpat_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
        ));

        $tahun_akademik = $this->input->post('tahun_akademik');
        $semester = $this->input->post('semester');
        $kode_program_studi = $this->input->post('prodi');

        $kode_tahun_akademik = null;
        $data_krs = null;

        if ($this->input->post('proses') !== null) {
            if ($tahun_akademik !== null && $tahun_akademik !== '' && $semester !== null && $semester !== '') {
                $kode_tahun_akademik = $this->m_tahun_akademik->get_kode_by_tahun_semester($tahun_akademik, $semester);
            }

            if ($kode_tahun_akademik) {
                $data_krs = $this->Krs_kpat_model->filter($kode_tahun_akademik, null, $kode_program_studi);
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-warning"><h6>Tahun Akademik dan Semester yang dipilih tidak ditemukan.</h6></div>');
            }
        }

        $this->load->view('admin/template/V_main', [
            'content'    => 'admin/pengaturan/V_krs_kpat',
            'judul'      => 'Pengaturan',
            'sub_judul'  => 'KRS KPAT',
            'title_h1'   => '<i class="fa fa-list-alt"></i> <li>Pengaturan</li>',
            'title_h2'   => '<li>KRS KPAT</li>',
            'tahun'      => $this->m_tahun_akademik->get_tahun(),
            'tahun_akademik_list' => $this->m_tahun_akademik->get(),
            'nama_jurusan' => $this->Nama_jurusan_model->get(),
            'data'       => $data_krs,
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'filter'     => [
                'tahun_akademik' => $tahun_akademik,
                'semester'       => $semester,
                'prodi'          => $kode_program_studi,
            ],
        ]);
    }

    public function pindah_tahun_akademik()
    {
        $this->load->model(array(
            'akademik/Krs_kpat_model',
            'jurusan/m_tahun_akademik',
        ));

        $kode_krs = $this->input->post('kode_krs');
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');

        if (!$kode_krs || !$kode_tahun_akademik) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        $krs = $this->db->select('kode_krs, nim, kode_tahun_akademik')
            ->where('kode_krs', $kode_krs)
            ->get('krs')->row_object();

        if (!$krs) {
            echo json_encode(['status' => false, 'message' => 'Data KRS tidak ditemukan.']);
            return;
        }

        if ($krs->kode_tahun_akademik == $kode_tahun_akademik) {
            echo json_encode(['status' => false, 'message' => 'Tahun akademik tujuan sama dengan tahun akademik saat ini.']);
            return;
        }

        $tahun = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);
        if (!$tahun) {
            echo json_encode(['status' => false, 'message' => 'Tahun akademik tujuan tidak ditemukan.']);
            return;
        }

        $existing = $this->Krs_kpat_model->get_kode_krs_kpat($krs->nim, $kode_tahun_akademik);
        if ($existing && $existing != $kode_krs) {
            echo json_encode(['status' => false, 'message' => 'Mahasiswa sudah memiliki KRS KPAT pada tahun akademik tujuan.']);
            return;
        }

        $tahun_angkatan = substr($krs->nim, 0, 2);
        if ($tahun->semester == 0) {
            $semester = ($tahun->tahun - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun->tahun - $tahun_angkatan) * 2 + 1;
        }

        $this->db->where('kode_krs', $kode_krs)->update('krs', [
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'semester' => $semester,
        ]);

        echo json_encode(['status' => true, 'message' => 'KRS berhasil dipindahkan ke tahun akademik ' . $tahun->tahun_akademik . '.']);
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
