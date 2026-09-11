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
            'angkatan_list' => $this->Perwalian_model->get_angkatan_list(),
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

        // Dosen dengan status login aktif per prodi
        $dosen = $this->Perwalian_model->get_dosen_aktif_by_homebase($kode_program_studi);

        if (empty($dosen)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Tidak ada dosen dengan status login aktif pada program studi tersebut.</h6></div>');
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

    public function view_data()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('angkatan');

        if (empty($kode_program_studi) || !preg_match('/^\d{2}$/', $angkatan)) {
            echo json_encode(array('status' => false, 'message' => 'Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.'));
            return;
        }

        $rows = $this->Perwalian_model->get_data_perwalian($kode_program_studi, $angkatan);

        $html = '<div class="table-responsive" style="max-height: 350px; overflow: auto;">';
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead><tr><th>No.</th><th>NIM</th><th>Nama Mahasiswa</th><th>Dosen Wali</th><th>Tahun Akademik</th></tr></thead><tbody>';

        if (!empty($rows)) {
            $i = 1;
            foreach ($rows as $r) {
                $ta = $r->tahun_akademik ? $r->tahun_akademik . ' - ' . ($r->semester == '1' ? 'Ganjil' : 'Genap') : '-';
                $html .= '<tr><td>' . $i++ . '</td><td>' . e($r->nim) . '</td><td>' . e($r->nama_mahasiswa) . '</td><td>' . e($r->nama_dosen) . '</td><td>' . e($ta) . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" class="text-center">Tidak ada data perwalian.</td></tr>';
        }

        $html .= '</tbody></table></div>';

        echo json_encode(array(
            'status' => true,
            'jumlah' => count($rows),
            'html' => $html,
        ));
    }

    public function preview_hapus()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('angkatan');

        if (empty($kode_program_studi) || !preg_match('/^\d{2}$/', $angkatan)) {
            echo json_encode(array('status' => false, 'message' => 'Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.'));
            return;
        }

        $nims = $this->Perwalian_model->get_nim_perwalian($kode_program_studi, $angkatan);
        $jumlah_perwalian = count($nims);

        $jumlah_konsultasi = 0;
        if ($jumlah_perwalian > 0) {
            $nim_list = array_column($nims, 'nim');
            $jumlah_konsultasi = $this->db->where_in('nim', $nim_list)
                ->count_all_results('konsultasi_perwalian');
        }

        echo json_encode(array(
            'status' => true,
            'jumlah_perwalian' => $jumlah_perwalian,
            'jumlah_konsultasi' => $jumlah_konsultasi,
        ));
    }

    public function manual_data()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('angkatan');

        if (empty($kode_program_studi) || !preg_match('/^\d{2}$/', $angkatan)) {
            echo json_encode(array('status' => false, 'message' => 'Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.'));
            return;
        }

        $dosen = $this->Perwalian_model->get_dosen_by_homebase($kode_program_studi);
        $belum = $this->Perwalian_model->get_mahasiswa_belum_ada_dosen_wali($angkatan, $kode_program_studi);
        $sudah = $this->Perwalian_model->get_mahasiswa_sudah_ada_dosen_wali($angkatan, $kode_program_studi);

        $dosen_options = '<option value="" selected disabled>Pilih Dosen Wali</option>';
        if (!empty($dosen)) {
            foreach ($dosen as $d) {
                $dosen_options .= '<option value="' . e($d->kode_dosen) . '">' . e($d->nama_dosen) . '</option>';
            }
        }

        $html_belum = '<div class="table-responsive" style="max-height: 350px; overflow: auto;">';
        $html_belum .= '<table class="table table-bordered table-striped">';
        $html_belum .= '<thead><tr><th class="text-center">No.</th><th>NIM</th><th>Nama Mahasiswa</th><th class="text-center"><input type="checkbox" id="check-all-belum"></th></tr></thead><tbody>';
        if ($belum && count($belum) > 0) {
            $i = 1;
            foreach ($belum as $r) {
                $html_belum .= '<tr><td class="text-center">' . $i++ . '</td><td>' . e($r->nim) . '</td><td>' . e($r->nama_mahasiswa) . '</td><td class="text-center"><input type="checkbox" class="check-belum" name="nim_belum[]" value="' . e($r->nim) . '"></td></tr>';
            }
        } else {
            $html_belum .= '<tr><td colspan="4" class="text-center">Tidak ada mahasiswa yang belum memiliki dosen wali.</td></tr>';
        }
        $html_belum .= '</tbody></table></div>';

        $html_sudah = '<div class="table-responsive" style="max-height: 350px; overflow: auto;">';
        $html_sudah .= '<table class="table table-bordered table-striped">';
        $html_sudah .= '<thead><tr><th class="text-center">No.</th><th>NIM</th><th>Nama Mahasiswa</th><th>Dosen Wali Sekarang</th><th class="text-center"><input type="checkbox" id="check-all-sudah"></th></tr></thead><tbody>';
        if ($sudah && count($sudah) > 0) {
            $i = 1;
            foreach ($sudah as $r) {
                $html_sudah .= '<tr><td class="text-center">' . $i++ . '</td><td>' . e($r->nim) . '</td><td>' . e($r->nama_mahasiswa) . '</td><td>' . e($r->nama_dosen) . '</td><td class="text-center"><input type="checkbox" class="check-sudah" name="nim_sudah[]" value="' . e($r->nim) . '"></td></tr>';
            }
        } else {
            $html_sudah .= '<tr><td colspan="5" class="text-center">Tidak ada mahasiswa yang memiliki dosen wali.</td></tr>';
        }
        $html_sudah .= '</tbody></table></div>';

        echo json_encode(array(
            'status' => true,
            'jumlah_dosen' => count($dosen),
            'jumlah_belum' => $belum ? count($belum) : 0,
            'jumlah_sudah' => $sudah ? count($sudah) : 0,
            'dosen_options' => $dosen_options,
            'table_belum' => $html_belum,
            'table_sudah' => $html_sudah,
        ));
    }

    public function manual_proses()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('angkatan');
        $tipe = $this->input->post('tipe');
        $kode_dosen = (int) $this->input->post('kode_dosen');
        $nims = $this->input->post('nim_belum') ?: $this->input->post('nim_sudah');

        if (empty($kode_program_studi) || !preg_match('/^\d{2}$/', $angkatan)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        if (!in_array($tipe, array('belum', 'sudah'), true) || empty($kode_dosen)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Pilih dosen wali tujuan terlebih dahulu.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        if (empty($nims) || !is_array($nims)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-warning"><h6>Tidak ada mahasiswa yang dicentang.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        $tahun_akademik = $this->M_tahun_akademik->get_semester();
        if (!$tahun_akademik) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Tahun akademik aktif tidak ditemukan.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }
        $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;

        $sukses = 0;
        $dilewati = 0;

        if ($tipe === 'belum') {
            foreach ($nims as $nim) {
                if ($this->Perwalian_model->cek_perwalian_exists($nim)) {
                    $dilewati++;
                    continue;
                }
                if ($this->Perwalian_model->simpan(array(
                    'nim' => $nim,
                    'kode_dosen' => $kode_dosen,
                    'kode_tahun_akademik' => $kode_tahun_akademik,
                ))) {
                    $sukses++;
                }
            }
            $pesan = $sukses . ' mahasiswa berhasil diberikan dosen wali.';
        } else {
            foreach ($nims as $nim) {
                if ($this->Perwalian_model->ubah_dosen_wali($nim, $kode_dosen)) {
                    $sukses++;
                }
            }
            $pesan = $sukses . ' mahasiswa berhasil dipindahkan ke dosen wali baru.';
        }

        if ($dilewati > 0) {
            $pesan .= ' ' . $dilewati . ' mahasiswa dilewati karena sudah memiliki dosen wali.';
        }

        $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>' . $pesan . '</h6></div>');
        redirect(site_url('admin/pengaturan/distribusi_perwalian'));
    }

    public function hapus()
    {
        $kode_program_studi = (int) $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('angkatan');

        if (empty($kode_program_studi) || !preg_match('/^\d{2}$/', $angkatan)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.</h6></div>');
            redirect(site_url('admin/pengaturan/distribusi_perwalian'));
        }

        $res = $this->Perwalian_model->hapus_perwalian($kode_program_studi, $angkatan);

        if ($res['status']) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Perwalian dihapus: ' . $res['hapus_perwalian'] . ' data perwalian dan ' . $res['hapus_konsultasi'] . ' data konsultasi.</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal menghapus perwalian.</h6></div>');
        }

        redirect(site_url('admin/pengaturan/distribusi_perwalian'));
    }
}
