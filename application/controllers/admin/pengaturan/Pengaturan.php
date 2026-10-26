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

    public function konversi()
    {
        $this->load->model(array(
            'akademik/Krs_model',
            'jurusan/m_tahun_akademik',
        ));

        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');

        $data_konversi = null;
        if ($this->input->post('proses') !== null) {
            $data_konversi = $this->Krs_model->get_konversi_list($kode_tahun_akademik);
        }

        $this->load->view('admin/template/V_main', [
            'content'    => 'admin/pengaturan/V_konversi',
            'judul'      => 'Pengaturan',
            'sub_judul'  => 'Konversi',
            'title_h1'   => '<i class="fa fa-exchange"></i> <li>Pengaturan</li>',
            'title_h2'   => '<li>Konversi</li>',
            'tahun_akademik_list' => $this->m_tahun_akademik->get(),
            'data'       => $data_konversi,
            'filter'     => ['kode_tahun_akademik' => $kode_tahun_akademik],
        ]);
    }

    public function status_mahasiswa()
    {
        $nim = trim($this->input->post('nim') ?? $this->input->get('nim') ?? '');
        $data_mhs = null;

        if ($this->input->post('simpan') !== null) {
            $status_pendaftaran = $this->input->post('status_pendaftaran');
            if (in_array($status_pendaftaran, ['B', 'T', 'L'])) {
                $this->db->where('nim', $nim)->update('mahasiswa', [
                    'status_pendaftaran' => $status_pendaftaran
                ]);
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Status pendaftaran berhasil disimpan.</h6></div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Status pendaftaran tidak valid.</h6></div>');
            }
        }

        if ($nim !== '') {
            $data_mhs = $this->db->select('m.nim, m.nama_mahasiswa, ps.nama_program_studi, m.status_pendaftaran')
                ->from('mahasiswa m')
                ->join('program_studi ps', 'm.program_studi_kode = ps.kode_program_studi', 'left')
                ->where('m.nim', $nim)
                ->get()
                ->row();

            if (!$data_mhs && $this->input->post('simpan') === null) {
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-warning"><h6>Mahasiswa dengan NIM ' . e($nim) . ' tidak ditemukan.</h6></div>');
            }
        }

        $this->load->view('admin/template/V_main', [
            'content'   => 'admin/pengaturan/V_status_mahasiswa',
            'judul'     => 'Pengaturan',
            'sub_judul' => 'Status Mahasiswa',
            'title_h1'  => '<i class="fa fa-user"></i> <li>Pengaturan</li>',
            'title_h2'  => '<li>Status Mahasiswa</li>',
            'nim'       => $nim,
            'data'      => $data_mhs,
        ]);
    }

    public function hapus_krs()
    {
        $nim = trim($this->input->post('nim') ?? $this->input->get('nim') ?? '');
        $data_mhs = null;
        $data_krs = [];

        if ($nim !== '') {
            $data_mhs = $this->db->select('m.nim, m.nama_mahasiswa, ps.nama_program_studi, m.status_pendaftaran')
                ->from('mahasiswa m')
                ->join('program_studi ps', 'm.program_studi_kode = ps.kode_program_studi', 'left')
                ->where('m.nim', $nim)
                ->get()
                ->row();

            if (!$data_mhs) {
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-warning"><h6>Mahasiswa dengan NIM ' . e($nim) . ' tidak ditemukan.</h6></div>');
            } else {
                $data_krs = $this->db->select('k.kode_krs, k.nim, k.semester, k.kode_tahun_akademik, ta.tahun_akademik, ta.semester as ta_semester, COUNT(kd.kode_krs_detail) as total_mk, COALESCE(SUM(m.sks_teori + m.sks_praktek + m.sks_praktikum), 0) as total_sks')
                    ->from('krs k')
                    ->join('tahun_akademik ta', 'ta.kode_tahun_akademik = k.kode_tahun_akademik', 'left')
                    ->join('krs_detail kd', 'kd.kode_krs = k.kode_krs', 'left')
                    ->join('matakuliah m', 'm.id_matakuliah = kd.id_matakuliah', 'left')
                    ->where('k.nim', $nim)
                    ->group_by(['k.kode_krs', 'k.nim', 'k.semester', 'k.kode_tahun_akademik', 'ta.tahun_akademik', 'ta.semester'])
                    ->order_by('k.kode_tahun_akademik', 'DESC')
                    ->get()
                    ->result();
            }
        }

        $this->load->view('admin/template/V_main', [
            'content'   => 'admin/pengaturan/V_hapus_krs',
            'judul'     => 'Pengaturan',
            'sub_judul' => 'Penghapusan KRS',
            'title_h1'  => '<i class="fa fa-trash"></i> <li>Pengaturan</li>',
            'title_h2'  => '<li>Penghapusan KRS</li>',
            'nim'       => $nim,
            'data_mhs'  => $data_mhs,
            'data_krs'  => $data_krs,
        ]);
    }

    public function detail_krs_ajax($kode_krs)
    {
        $details = $this->db->select('kd.kode_krs_detail, m.kode_matakuliah, m.nama_matakuliah, (m.sks_teori + m.sks_praktek + m.sks_praktikum) as sks, kd.status as status_mk, khd.nilai_akhir')
            ->from('krs_detail kd')
            ->join('matakuliah m', 'm.id_matakuliah = kd.id_matakuliah', 'inner')
            ->join('khs_detail khd', 'khd.kode_krs_detail = kd.kode_krs_detail', 'left')
            ->where('kd.kode_krs', $kode_krs)
            ->get()
            ->result();

        echo json_encode(['status' => true, 'data' => $details]);
    }

    public function proses_hapus_krs()
    {
        $kode_krs = $this->input->post('kode_krs');
        $nim = trim($this->input->post('nim') ?? '');

        if (!$kode_krs) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Data KRS tidak valid.</h6></div>');
            redirect(site_url('admin/pengaturan/pengaturan/hapus_krs' . ($nim ? '?nim=' . urlencode($nim) : '')));
            return;
        }

        $krs = $this->db->select('k.kode_krs, k.nim, ta.tahun_akademik, ta.semester as ta_semester')
            ->from('krs k')
            ->join('tahun_akademik ta', 'ta.kode_tahun_akademik = k.kode_tahun_akademik', 'left')
            ->where('k.kode_krs', $kode_krs)
            ->get()->row();

        if (!$krs) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Data KRS tidak ditemukan.</h6></div>');
            redirect(site_url('admin/pengaturan/pengaturan/hapus_krs' . ($nim ? '?nim=' . urlencode($nim) : '')));
            return;
        }

        $nim = $krs->nim;
        $ta_label = ($krs->tahun_akademik ?? '-') . ' (' . ($krs->ta_semester == 0 ? 'Genap' : 'Ganjil') . ')';

        $this->db->trans_start();

        if (function_exists('log_aktivitas_nilai')) {
            $krs_details = $this->db->select('kode_krs_detail')->where('kode_krs', $kode_krs)->get('krs_detail')->result();
            foreach ($krs_details as $kd) {
                $row = $this->db->where('kode_krs_detail', $kd->kode_krs_detail)->get('khs_detail')->row();
                if ($row) {
                    $json_nilai = json_encode([
                        'nilai_harian' => $row->nilai_harian,
                        'nilai_uts'    => $row->nilai_uts,
                        'nilai_uas'    => $row->nilai_uas,
                        'nilai_akhir'  => $row->nilai_akhir,
                    ]);
                    log_aktivitas_nilai('delete', 'nilai_harian,nilai_uts,nilai_uas,nilai_akhir', $json_nilai, null, 'pengaturan_hapus_krs', null, $kd->kode_krs_detail);
                }
            }
            log_aktivitas_nilai('delete', 'kode_krs', (string) $kode_krs, null, 'pengaturan_hapus_krs', null, null, $kode_krs);
        }

        $this->db->where('kode_krs', $kode_krs)->delete('krs');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal menghapus KRS ' . e($ta_label) . '.</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>KRS ' . e($ta_label) . ' untuk NIM ' . e($nim) . ' berhasil dihapus.</h6></div>');
        }

        redirect(site_url('admin/pengaturan/pengaturan/hapus_krs?nim=' . urlencode($nim)));
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

    public function pembuatan_nim_baru()
    {
        $this->load->service('MahasiswaService', 'mahasiswaservice');

        $prodi = $this->mahasiswaservice->getProgramStudiLengkap();
        $provinsi = $this->mahasiswaservice->getProvinsiLengkap();

        $current_year = (int) date('Y');
        $angkatan_list = [];
        for ($y = $current_year + 1; $y >= $current_year - 4; $y--) {
            $angkatan_list[] = [
                'val'   => substr((string) $y, -2),
                'label' => $y . ' (Angkatan ' . substr((string) $y, -2) . ')'
            ];
        }

        $data = [
            'content'          => 'admin/pengaturan/V_pembuatan_nim_baru',
            'judul'            => 'Pengaturan',
            'judul_sub_judul'  => 'Mahasiswa Baru',
            'sub_judul'        => 'NIM Baru',
            'title_h1'         => '<i class="fa fa-user-plus"></i> <li>Pengaturan</li>',
            'title_h2'         => '<li>Mahasiswa Baru</li>',
            'title_h3'         => '<li>NIM Baru</li>',
            'prodi'            => $prodi,
            'provinsi'         => $provinsi,
            'angkatan_list'    => $angkatan_list,
            'default_angkatan' => substr((string) $current_year, -2)
        ];

        $this->load->view('admin/template/V_main', $data);
    }

    public function pembuatan_nim_transfer()
    {
        $this->load->service('MahasiswaService', 'mahasiswaservice');

        $prodi = $this->mahasiswaservice->getProgramStudiLengkap();
        $provinsi = $this->mahasiswaservice->getProvinsiLengkap();

        $current_year = (int) date('Y');
        $angkatan_list = [];
        for ($y = $current_year + 1; $y >= $current_year - 4; $y--) {
            $angkatan_list[] = [
                'val'   => substr((string) $y, -2),
                'label' => $y . ' (Angkatan ' . substr((string) $y, -2) . ')'
            ];
        }

        $data = [
            'content'          => 'admin/pengaturan/V_pembuatan_nim_transfer',
            'judul'            => 'Pengaturan',
            'judul_sub_judul'  => 'Mahasiswa Baru',
            'sub_judul'        => 'NIM Transfer',
            'title_h1'         => '<i class="fa fa-exchange"></i> <li>Pengaturan</li>',
            'title_h2'         => '<li>Mahasiswa Baru</li>',
            'title_h3'         => '<li>NIM Transfer</li>',
            'prodi'            => $prodi,
            'provinsi'         => $provinsi,
            'angkatan_list'    => $angkatan_list,
            'default_angkatan' => substr((string) $current_year, -2)
        ];

        $this->load->view('admin/template/V_main', $data);
    }

    public function generate_nim_ajax()
    {
        $prodi_kode = (int) $this->input->post('program_studi_kode');
        $angkatan   = trim($this->input->post('angkatan') ?? '');

        if (!$prodi_kode || empty($angkatan)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Prodi dan Angkatan wajib dipilih/diisi.']));
        }

        $angkatan_2digit = substr($angkatan, -2);

        // Ambil pola middle 5 digit dari mahasiswa 11 digit yang sudah ada pada prodi ini
        $sample = $this->db->select('nim')
            ->where('program_studi_kode', $prodi_kode)
            ->where('LENGTH(nim)', 11)
            ->order_by('nim', 'DESC')
            ->limit(1)
            ->get('mahasiswa')
            ->row();

        if ($sample) {
            $middle = substr($sample->nim, 2, 5);
        } else {
            $prodi = $this->db->get_where('program_studi', ['kode_program_studi' => $prodi_kode])->row();
            if ($prodi) {
                $jenjang_code = '1';
                if ($prodi->id_jenjang == 2) {
                    $jenjang_code = '0';
                } elseif ($prodi->id_jenjang == 3) {
                    $jenjang_code = '2';
                }
                $middle = sprintf('%02d%02d%s', (int) $prodi->kode_fakultas, (int) $prodi->kode_prodi_univ, $jenjang_code);
            } else {
                $middle = '00000';
            }
        }

        $prefix = $angkatan_2digit . $middle;

        // Cari NIM tertinggi pada prodi dan prefix ini
        $latest = $this->db->select('nim')
            ->where('program_studi_kode', $prodi_kode)
            ->where("nim LIKE '{$prefix}%'")
            ->where('LENGTH(nim)', 11)
            ->order_by('nim', 'DESC')
            ->limit(1)
            ->get('mahasiswa')
            ->row();

        if ($latest) {
            $seq = intval(substr($latest->nim, 7, 4)) + 1;
        } else {
            $seq = 1;
        }

        $new_nim = $prefix . sprintf('%04d', $seq);
        while ($this->db->where('nim', $new_nim)->count_all_results('mahasiswa') > 0) {
            $seq++;
            $new_nim = $prefix . sprintf('%04d', $seq);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => true,
                'nim'     => $new_nim,
                'prefix'  => $prefix,
                'seq'     => sprintf('%04d', $seq),
            ]));
    }

    public function cari_mahasiswa_transfer_ajax()
    {
        $nim = trim($this->input->post('nim') ?? '');

        if (empty($nim)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'NIM mahasiswa asal harus diisi.']));
        }

        $mhs = $this->db->select('m.*, ps.nama_program_studi as nama_prodi_asal')
            ->from('mahasiswa m')
            ->join('program_studi ps', 'm.program_studi_kode = ps.kode_program_studi', 'left')
            ->where('m.nim', $nim)
            ->get()
            ->row();

        if (!$mhs) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Mahasiswa dengan NIM ' . $nim . ' tidak ditemukan di database Bumigora.']));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data'   => [
                    'nim_asal'           => $mhs->nim,
                    'nama_mahasiswa'     => $mhs->nama_mahasiswa,
                    'nama_prodi_asal'    => $mhs->nama_prodi_asal ?? '-',
                    'nik'                => $mhs->nik,
                    'npm'                => $mhs->npm,
                    'tempat_lahir'       => $mhs->tempat_lahir,
                    'tanggal_lahir'      => $mhs->tanggal_lahir,
                    'alamat_lengkap'     => $mhs->alamat,
                    'kota'               => $mhs->kota,
                    'propinsi'           => $mhs->propinsi,
                    'telepon'            => $mhs->telepon,
                    'jenis_kelamin'      => $mhs->jenis_kelamin,
                    'agama'              => $mhs->agama,
                    'golongan_darah'     => $mhs->golongan_darah,
                    'kewarganegaraan'    => $mhs->kewarganegaraan,
                    'nama_instansi'      => $mhs->nama_instansi,
                    'nama_ayah'          => $mhs->nama_ayah,
                    'agama_ayah'         => $mhs->agama_ayah,
                    'pekerjaan_ayah'     => $mhs->pekerjaan_ayah,
                    'nama_ibu'           => $mhs->nama_ibu,
                    'agama_ibu'          => $mhs->agama_ibu,
                    'pekerjaan_ibu'      => $mhs->pekerjaan_ibu,
                    'alamat_orangtua'    => $mhs->alamat_orangtua,
                    'kota_orangtua'      => $mhs->kota_orangtua,
                    'propinsi_orangtua'  => $mhs->propinsi_orangtua,
                    'telepon_orangtua'   => $mhs->telepon_orangtua,
                ]
            ]));
    }

    public function simpan_nim_baru()
    {
        $this->load->library('form_validation');
        $this->load->service('MahasiswaService', 'mahasiswaservice');

        $this->form_validation->set_rules('program_studi_kode', 'Program Studi', 'required');
        $this->form_validation->set_rules('nim', 'NIM', 'required|numeric');
        $this->form_validation->set_rules('nama_mahasiswa', 'Nama Mahasiswa', 'required');
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('status', 'Status Kuliah', 'required');

        $is_transfer = ($this->input->post('is_transfer') == '1');
        $redirect_url = $is_transfer ? 'admin/pengaturan/pembuatan_nim_transfer' : 'admin/pengaturan/pembuatan_nim_baru';

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . validation_errors(' ', ' ') . '</h6></div>');
            redirect($redirect_url);
            return;
        }

        $post = $this->input->post();

        if ($is_transfer) {
            $post['status_pendaftaran'] = 'T';
        } else {
            // Murni baru: hanya boleh opsi selain T, atau valid enum B/L
            if (!in_array($post['status_pendaftaran'] ?? '', ['B', 'L'])) {
                $post['status_pendaftaran'] = 'B';
            }
        }

        // Cek duplikasi NIM
        if ($this->db->where('nim', $post['nim'])->count_all_results('mahasiswa') > 0) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>NIM ' . e($post['nim']) . ' sudah digunakan. Silakan generate NIM baru.</h6></div>');
            redirect($redirect_url);
            return;
        }

        // Default password jika kosong atau kurang dari 8 karakter
        if (empty($post['password'])) {
            $post['password'] = !empty($post['tanggal_lahir']) ? date('dmY', strtotime($post['tanggal_lahir'])) : '12345678';
        }
        if (strlen($post['password']) < 8) {
            $post['password'] = str_pad($post['password'], 8, '0', STR_PAD_RIGHT);
        }

        // Set default value jika field opsional tidak diisi
        $post['npm']                     = $post['npm'] ?? '';
        $post['no_pendaftaran']          = !empty($post['no_pendaftaran']) ? $post['no_pendaftaran'] : 'REG' . substr($post['nim'], 0, 2) . substr($post['nim'], -4);
        $post['no_pendaftaran_ulang']    = !empty($post['no_pendaftaran_ulang']) ? $post['no_pendaftaran_ulang'] : $post['no_pendaftaran'];
        $post['tempat_lahir']            = $post['tempat_lahir'] ?? '';
        $post['tanggal_lahir']           = !empty($post['tanggal_lahir']) ? $post['tanggal_lahir'] : date('Y-m-d');
        $post['alamat_lengkap']          = $post['alamat_lengkap'] ?? '';
        $post['kota']                    = $post['kota'] ?? '';
        $post['propinsi']                = $post['propinsi'] ?? '';
        $post['telepon']                 = $post['telepon'] ?? '';
        $post['jenis_kelamin']           = $post['jenis_kelamin'] ?? 'L';
        $post['agama']                   = $post['agama'] ?? 'Islam';
        $post['golongan_darah']          = $post['golongan_darah'] ?? '-';
        $post['kewarganegaraan']         = $post['kewarganegaraan'] ?? 'WNI';
        $post['nama_instansi']           = $post['nama_instansi'] ?? '';
        $post['nama_ayah']               = $post['nama_ayah'] ?? '';
        $post['agama_ayah']              = $post['agama_ayah'] ?? 'Islam';
        $post['pekerjaan_ayah']          = $post['pekerjaan_ayah'] ?? '-';
        $post['nama_ibu']                = $post['nama_ibu'] ?? '';
        $post['agama_ibu']               = $post['agama_ibu'] ?? 'Islam';
        $post['pekerjaan_ibu']           = $post['pekerjaan_ibu'] ?? '-';
        $post['alamat_orangtua']         = $post['alamat_orangtua'] ?? '';
        $post['kota_orangtua']           = $post['kota_orangtua'] ?? '';
        $post['propinsi_orangtua']       = $post['propinsi_orangtua'] ?? '';
        $post['telepon_orangtua']        = $post['telepon_orangtua'] ?? '';

        $res = $this->mahasiswaservice->simpanMahasiswa($post);

        if ($res['status']) {
            // Catat ke tabel riwayat_pembuatan_nim
            $admin_username = $this->session->userdata('nama_login') ?? $this->session->userdata('username') ?? 'admin';
            $this->db->insert('riwayat_pembuatan_nim', [
                'nim'                => $post['nim'],
                'nama_mahasiswa'     => $post['nama_mahasiswa'],
                'program_studi_kode' => (int) $post['program_studi_kode'],
                'tahun_angkatan'     => substr($post['nim'], 0, 2),
                'jenis'              => $is_transfer ? 'transfer' : 'baru',
                'nim_asal'           => $is_transfer ? ($post['nim_asal'] ?? null) : null,
                'status_pendaftaran' => $post['status_pendaftaran'],
                'created_by'         => $admin_username,
                'created_at'         => date('Y-m-d H:i:s')
            ]);

            $msg = 'Berhasil membuat mahasiswa baru dengan NIM: <strong>' . e($post['nim']) . '</strong> - ' . e($post['nama_mahasiswa']);
            if ($is_transfer) {
                $msg .= ' (Status: Transfer Mahasiswa Bumigora)';
            }
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>' . $msg . '</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . e($res['msg']) . '</h6></div>');
        }

        redirect($redirect_url);
    }

    public function riwayat_pembuatan_nim()
    {
        $this->load->service('MahasiswaService', 'mahasiswaservice');

        $prodi_filter = (int) ($this->input->get('prodi') ?? 0);
        $jenis_filter = trim($this->input->get('jenis') ?? '');

        $builder = $this->db->select('r.*, ps.nama_program_studi')
            ->from('riwayat_pembuatan_nim r')
            ->join('program_studi ps', 'r.program_studi_kode = ps.kode_program_studi', 'left');

        if ($prodi_filter > 0) {
            $builder->where('r.program_studi_kode', $prodi_filter);
        }
        if (in_array($jenis_filter, ['baru', 'transfer'])) {
            $builder->where('r.jenis', $jenis_filter);
        }

        $riwayat = $builder->order_by('r.id', 'DESC')->get()->result();

        $data = [
            'content'         => 'admin/pengaturan/V_riwayat_pembuatan_nim',
            'judul'           => 'Pengaturan',
            'judul_sub_judul' => 'Mahasiswa Baru',
            'sub_judul'       => 'Riwayat',
            'title_h1'        => '<i class="fa fa-history"></i> <li>Pengaturan</li>',
            'title_h2'        => '<li>Mahasiswa Baru</li>',
            'title_h3'        => '<li>Riwayat</li>',
            'riwayat'         => $riwayat,
            'prodi'           => $this->mahasiswaservice->getProgramStudiLengkap(),
            'filter_prodi'    => $prodi_filter,
            'filter_jenis'    => $jenis_filter
        ];

        $this->load->view('admin/template/V_main', $data);
    }
}
