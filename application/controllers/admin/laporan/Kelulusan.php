<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelulusan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'laporan/laporan_model',
        ));

        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            if (!rbac_cek($class, $id_user) && !rbac_cek('Aktif_perkuliahan', $id_user)) {
                redirect(site_url('denied'));
            }
        }
    }

    public function index()
    {
        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Laporan Mahasiswa Kelulusan';
        $data['content'] = 'admin/laporan/kelulusan/V_index';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter()
    {
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $kode_program_studi = $this->input->post('prodi');
        $angkatan = $this->input->post('angkatan');

        $data_session = array(
            'sess_lulus_ta' => $kode_tahun_akademik,
            'sess_lulus_prodi' => $kode_program_studi,
            'sess_lulus_angkatan' => $angkatan,
        );

        $this->session->set_userdata($data_session);

        redirect(site_url('admin/laporan/kelulusan/data_kelulusan'));
    }

    public function data_kelulusan()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_lulus_ta');
        $kode_program_studi = $this->session->userdata('sess_lulus_prodi');
        $angkatan = $this->session->userdata('sess_lulus_angkatan');

        $prodi = null;
        if (!empty($kode_program_studi) && $kode_program_studi !== 'all') {
            $prodi = $this->Nama_jurusan_model->get_all_byid($kode_program_studi);
        }

        $ta_info = null;
        if (!empty($kode_tahun_akademik) && $kode_tahun_akademik !== 'all') {
            $ta_info = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);
        }

        $data['judul'] = 'Laporan';
        $data['sub_judul'] = 'Data Mahasiswa Kelulusan';
        $data['content'] = 'admin/laporan/kelulusan/V_data';
        $data['data'] = $this->laporan_model->mahasiswa_kelulusan($kode_tahun_akademik, $kode_program_studi, $angkatan);
        $data['prodi'] = $prodi;
        $data['ta_info'] = $ta_info;
        $data['kode_ta_aktif'] = $kode_tahun_akademik;
        $data['daftar_tahun_akademik'] = $this->m_tahun_akademik->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function cari_mahasiswa_ajax()
    {
        $keyword = trim($this->input->get_post('keyword', true));
        if (empty($keyword)) {
            $this->output->set_content_type('application/json')->set_output(json_encode([]));
            return;
        }

        $result = $this->laporan_model->cari_mahasiswa($keyword, 10);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function simpan_lulus()
    {
        $nim = trim($this->input->post('nim', true));
        $kode_tahun_akademik = trim($this->input->post('tahun_akademik', true));
        $is_ajax = $this->input->is_ajax_request();

        if (empty($nim) || empty($kode_tahun_akademik) || $kode_tahun_akademik === 'all') {
            $msg = 'NIM dan Periode Kelulusan wajib dipilih!';
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'error', 'message' => $msg]));
            }
            $this->session->set_flashdata('info', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-ban"></i> ' . $msg . '</div>');
            redirect(site_url('admin/laporan/kelulusan/data_kelulusan'));
            return;
        }

        $mhs = $this->laporan_model->get_mahasiswa_by_nim($nim);
        if (!$mhs) {
            $msg = 'Mahasiswa dengan NIM ' . htmlspecialchars($nim) . ' tidak ditemukan!';
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'error', 'message' => $msg]));
            }
            $this->session->set_flashdata('info', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-ban"></i> ' . $msg . '</div>');
            redirect(site_url('admin/laporan/kelulusan/data_kelulusan'));
            return;
        }

        $this->laporan_model->tambah_kelulusan($nim, $kode_tahun_akademik);

        $mhs_updated = $this->laporan_model->get_mahasiswa_by_nim($nim);
        $semester = ($mhs_updated->semester_lulus == '0') ? 'Genap' : 'Ganjil';
        $ta_label = (!empty($mhs_updated->tahun_lulus)) ? $mhs_updated->tahun_lulus . ' - ' . $semester : '-';
        $angkatan_label = (!empty($mhs_updated->nim)) ? '20' . substr($mhs_updated->nim, 0, 2) : '-';

        $msg = 'Mahasiswa <strong>' . htmlspecialchars($mhs->nim) . ' - ' . htmlspecialchars($mhs->nama_mahasiswa) . '</strong> berhasil ditambahkan ke daftar kelulusan.';

        if ($is_ajax) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => $msg,
                    'data' => [
                        'nim' => $mhs_updated->nim,
                        'nama_mahasiswa' => $mhs_updated->nama_mahasiswa,
                        'nama_program_studi' => $mhs_updated->nama_program_studi ?: '-',
                        'angkatan' => $angkatan_label,
                        'periode_lulus' => $ta_label,
                        'url_batal' => site_url('admin/laporan/kelulusan/batal_lulus/' . $mhs_updated->nim)
                    ]
                ]));
        }

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> ' . $msg . '</div>');
        redirect(site_url('admin/laporan/kelulusan/data_kelulusan'));
    }

    public function batal_lulus($nim = null)
    {
        $nim = trim($nim ?: $this->input->post('nim', true));
        $is_ajax = $this->input->is_ajax_request();

        if (empty($nim)) {
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'error', 'message' => 'NIM tidak valid']));
            }
            redirect(site_url('admin/laporan/kelulusan/data_kelulusan'));
            return;
        }

        $mhs = $this->laporan_model->get_mahasiswa_by_nim($nim);
        if ($mhs) {
            $this->laporan_model->batal_kelulusan($nim);
            $msg = 'Status kelulusan mahasiswa <strong>' . htmlspecialchars($mhs->nim) . ' - ' . htmlspecialchars($mhs->nama_mahasiswa) . '</strong> berhasil dibatalkan.';
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'success', 'message' => $msg, 'nim' => $nim]));
            }
            $this->session->set_flashdata('info', '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-info"></i> ' . $msg . '</div>');
        }

        redirect(site_url('admin/laporan/kelulusan/data_kelulusan'));
    }

    public function cetak_excel()
    {
        $kode_tahun_akademik = $this->session->userdata('sess_lulus_ta');
        $kode_program_studi = $this->session->userdata('sess_lulus_prodi');
        $angkatan = $this->session->userdata('sess_lulus_angkatan');

        $data = $this->laporan_model->mahasiswa_kelulusan($kode_tahun_akademik, $kode_program_studi, $angkatan);

        $table = '<table border="1">';
        $table .= '<thead><tr>';
        $table .= '<th>NO.</th>';
        $table .= '<th>NIM</th>';
        $table .= '<th>NAMA MAHASISWA</th>';
        $table .= '<th>PROGRAM STUDI</th>';
        $table .= '<th>PERIODE KELULUSAN</th>';
        $table .= '</tr></thead><tbody>';

        $i = 1;
        foreach ($data as $row) {
            $semester = ($row->semester_lulus == '0') ? 'Genap' : 'Ganjil';
            $ta_label = (!empty($row->tahun_lulus)) ? $row->tahun_lulus . ' ' . $semester : '-';

            $table .= '<tr>';
            $table .= '<td align="center">' . $i++ . '.</td>';
            $table .= '<td style="mso-number-format:\'\@\';">' . e($row->nim) . '</td>';
            $table .= '<td>' . e($row->nama_mahasiswa) . '</td>';
            $table .= '<td>' . e($row->nama_program_studi) . '</td>';
            $table .= '<td align="center">' . e($ta_label) . '</td>';
            $table .= '</tr>';
        }
        $table .= '</tbody></table>';

        $file_name = 'Laporan_Mahasiswa_Kelulusan_' . date('YmdHis');
        $data_view['table'] = $table;
        $data_view['file_name'] = $file_name;

        $this->load->view('admin/laporan/aktif_perkuliahan/V_spreadsheet_view', $data_view);
    }
}
