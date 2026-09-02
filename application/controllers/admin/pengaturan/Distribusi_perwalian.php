<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_perwalian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/Perwalian_model',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/M_tahun_akademik',
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
        $data = array(
            'content' => 'admin/pengaturan/distribusi_perwalian/V_index',
            'judul' => 'Pengaturan',
            'sub_judul' => 'Distribusi Perwalian',
            'program_studi' => $this->Nama_jurusan_model->get(),
            'tahun_akademik' => $this->M_tahun_akademik->get_semester(),
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function proses()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');

        if (empty($kode_program_studi)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Silakan pilih program studi terlebih dahulu.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        $tahun_akademik = $this->M_tahun_akademik->get_semester();
        if (!$tahun_akademik) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Tahun akademik aktif tidak ditemukan.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
        $angkatan = $tahun_akademik->tahun_akademik; // 2 digit, misal '25'

        // Dosen aktif per prodi
        $dosen = $this->Perwalian_model->get_dosen_aktif_by_homebase($kode_program_studi);

        if (empty($dosen)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Tidak ada dosen aktif pada program studi tersebut.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        // Mahasiswa angkatan aktif yang belum punya dosen wali, urut NIM
        $mahasiswa = $this->Perwalian_model->get_mahasiswa_belum_ada_dosen_wali($angkatan, $kode_program_studi);

        if (!$mahasiswa || count($mahasiswa) == 0) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-info"><h6>Tidak ada mahasiswa yang belum memiliki dosen wali.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        // Urutkan berdasarkan NIM ascending
        usort($mahasiswa, function ($a, $b) {
            return strcmp($a->nim, $b->nim);
        });

        $jumlah_dosen = count($dosen);
        $jumlah_mhs = count($mahasiswa);
        $jatah = (int) floor($jumlah_mhs / $jumlah_dosen);
        $sisa = $jumlah_mhs % $jumlah_dosen;

        $insert_count = 0;
        $idx = 0;

        foreach ($dosen as $d) {
            $jumlah_untuk_dosen = $jatah + ($sisa > 0 ? 1 : 0);
            if ($sisa > 0) $sisa--;

            for ($i = 0; $i < $jumlah_untuk_dosen; $i++) {
                if ($idx >= $jumlah_mhs) break;
                $data_perwalian = array(
                    'nim' => $mahasiswa[$idx]->nim,
                    'kode_dosen' => $d->kode_dosen,
                    'kode_tahun_akademik' => $kode_tahun_akademik,
                );
                if ($this->Perwalian_model->simpan($data_perwalian)) {
                    $insert_count++;
                }
                $idx++;
            }
        }

        $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Distribusi berhasil: ' . $insert_count . ' mahasiswa dibagikan kepada ' . $jumlah_dosen . ' dosen.</h6></div>');
        redirect(site_url('admin/pengaturan/distribusi_perwalian'));
    }

    public function preview()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');
        $tahun_akademik = $this->M_tahun_akademik->get_semester();
        $angkatan = $tahun_akademik->tahun_akademik;

        $dosen = $this->Perwalian_model->get_dosen_aktif_by_homebase($kode_program_studi);
        $mahasiswa = $this->Perwalian_model->get_mahasiswa_belum_ada_dosen_wali($angkatan, $kode_program_studi);

        $jumlah_dosen = count($dosen);
        $jumlah_mhs = $mahasiswa ? count($mahasiswa) : 0;

        $html = '<table class="table table-bordered table-striped">';
        $html .= '<thead><tr><th>Nama Dosen</th><th class="text-center">Jumlah Mahasiswa</th></tr></thead><tbody>';

        if ($jumlah_dosen > 0 && $jumlah_mhs > 0) {
            $jatah = (int) floor($jumlah_mhs / $jumlah_dosen);
            $sisa = $jumlah_mhs % $jumlah_dosen;
            foreach ($dosen as $d) {
                $jml = $jatah + ($sisa > 0 ? 1 : 0);
                if ($sisa > 0) $sisa--;
                $html .= '<tr><td>' . e($d->nama_dosen) . '</td><td class="text-center">' . $jml . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2" class="text-center">Tidak ada data.</td></tr>';
        }

        $html .= '</tbody></table>';

        echo json_encode(array(
            'status' => true,
            'jumlah_dosen' => $jumlah_dosen,
            'jumlah_mahasiswa' => $jumlah_mhs,
            'html' => $html,
        ));
    }
}
