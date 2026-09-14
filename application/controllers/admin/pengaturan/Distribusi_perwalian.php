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
            'tahun_akademik_list' => $this->M_tahun_akademik->get(),
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
            echo json_encode(array('status' => false, 'message' => 'Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.'));
            return;
        }

        if (!in_array($tipe, array('belum', 'sudah'), true) || empty($kode_dosen)) {
            echo json_encode(array('status' => false, 'message' => 'Pilih dosen wali tujuan terlebih dahulu.'));
            return;
        }

        if (empty($nims) || !is_array($nims)) {
            echo json_encode(array('status' => false, 'message' => 'Tidak ada mahasiswa yang dicentang.'));
            return;
        }

        $tahun_akademik = $this->M_tahun_akademik->get_semester();
        if (!$tahun_akademik) {
            echo json_encode(array('status' => false, 'message' => 'Tahun akademik aktif tidak ditemukan.'));
            return;
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

        echo json_encode(array(
            'status' => $sukses > 0,
            'message' => $pesan,
        ));
    }

    public function duplikat_data()
    {
        $groups = $this->Perwalian_model->get_duplikat_perwalian();
        $detail = $this->Perwalian_model->get_detail_duplikat();

        $map = array();
        foreach ($detail as $row) {
            $map[$row->nim][$row->kode_perwalian] = $row->nama_dosen;
        }

        $html = '<div class="table-responsive" style="max-height: 400px; overflow: auto;">';
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead><tr>';
        $html .= '<th class="text-center">No.</th>';
        $html .= '<th>NIM</th>';
        $html .= '<th>Nama Mahasiswa</th>';
        $html .= '<th>Tahun Akademik</th>';
        $html .= '<th>Dosen Wali Lama</th>';
        $html .= '<th>Dosen Wali Terbaru</th>';
        $html .= '<th class="text-center">Aksi</th>';
        $html .= '</tr></thead><tbody>';

        if (!empty($groups)) {
            $i = 1;
            foreach ($groups as $g) {
                $nama_lama = isset($map[$g->nim][$g->kode_perwalian_lama]) ? $map[$g->nim][$g->kode_perwalian_lama] : '-';
                $nama_terbaru = isset($map[$g->nim][$g->kode_perwalian_terbaru]) ? $map[$g->nim][$g->kode_perwalian_terbaru] : '-';
                $ta = $g->tahun_akademik ? $g->tahun_akademik . ' - ' . ($g->semester == '1' ? 'Ganjil' : 'Genap') : 'Semua';
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $i++ . '</td>';
                $html .= '<td>' . e($g->nim) . '</td>';
                $html .= '<td>' . e($g->nama_mahasiswa) . '</td>';
                $html .= '<td>' . e($ta) . '</td>';
                $html .= '<td>' . e($nama_lama) . ' <small class="text-muted">(#'.(int)$g->kode_perwalian_lama.')</small></td>';
                $html .= '<td>' . e($nama_terbaru) . ' <small class="text-muted">(#'.(int)$g->kode_perwalian_terbaru.')</small></td>';
                $html .= '<td class="text-center">';
                $html .= '<button type="button" class="btn btn-warning btn-xs flat btn-resolusi-duplikat" '
                    . 'data-nim="' . e($g->nim) . '" '
                    . 'data-kode_tahun_akademik="' . e($g->kode_tahun_akademik) . '" '
                    . 'data-kode_terbaru="' . (int)$g->kode_perwalian_terbaru . '" '
                    . 'data-nama="' . e($g->nama_mahasiswa) . '" '
                    . 'data-terbaru="' . e($nama_terbaru) . '">'
                    . '<i class="fa fa-check"></i> Gunakan Dosen Wali Terbaru</button>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="7" class="text-center">Tidak ada duplikat dosen wali.</td></tr>';
        }

        $html .= '</tbody></table></div>';

        echo json_encode(array(
            'status' => true,
            'jumlah' => count($groups),
            'html' => $html,
        ));
    }

    public function duplikat_resolusi()
    {
        $nim = $this->input->post('nim');
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_terbaru = (int) $this->input->post('kode_terbaru');

        if (empty($nim) || empty($kode_terbaru)) {
            echo json_encode(array('status' => false, 'message' => 'Data tidak lengkap.'));
            return;
        }

        $kode_tahun_akademik = ($kode_tahun_akademik === '' || $kode_tahun_akademik === null) ? null : $kode_tahun_akademik;

        $deleted = $this->Perwalian_model->resolusi_duplikat($nim, $kode_tahun_akademik, $kode_terbaru);

        if ($deleted === false) {
            echo json_encode(array('status' => false, 'message' => 'Gagal menyelesaikan duplikat.'));
            return;
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Selesai: ' . $deleted . ' record dosen wali lama dihapus untuk NIM ' . $nim . '. Dosen wali terbaru sekarang yang digunakan.',
        ));
    }

    public function sync_data()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_tahun_akademik = ($kode_tahun_akademik === '' || $kode_tahun_akademik === null) ? null : $kode_tahun_akademik;
        $angkatan = $this->input->post('angkatan');
        $angkatan = ($angkatan === '' || $angkatan === null) ? null : $angkatan;

        $rows = $this->Perwalian_model->get_konsultasi_kode_dosen_null($kode_tahun_akademik, $angkatan);
        $missing = $this->Perwalian_model->get_perwalian_tanpa_konsultasi($kode_tahun_akademik, $angkatan);

        $html = '<div class="table-responsive" style="max-height: 300px; overflow: auto;">';
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead><tr>';
        $html .= '<th class="text-center">No.</th>';
        $html .= '<th>NIM</th>';
        $html .= '<th>Nama Mahasiswa</th>';
        $html .= '<th>Tahun Akademik</th>';
        $html .= '<th>Dosen Wali (perwalian)</th>';
        $html .= '<th class="text-center">Aksi</th>';
        $html .= '</tr></thead><tbody>';

        if (!empty($rows)) {
            $i = 1;
            foreach ($rows as $r) {
                $ta = $r->tahun_akademik ? $r->tahun_akademik . ' - ' . ($r->semester == '1' ? 'Ganjil' : 'Genap') : '-';
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $i++ . '</td>';
                $html .= '<td>' . e($r->nim) . '</td>';
                $html .= '<td>' . e($r->nama_mahasiswa) . '</td>';
                $html .= '<td>' . e($ta) . '</td>';
                $html .= '<td>' . e($r->nama_dosen) . ' <small class="text-muted">(#' . (int)$r->kode_dosen . ')</small></td>';
                $html .= '<td class="text-center">';
                $html .= '<button type="button" class="btn btn-primary btn-xs flat btn-sync-konsultasi" '
                    . 'data-kode_konsultasi_perwalian="' . (int)$r->kode_konsultasi_perwalian . '" '
                    . 'data-nim="' . e($r->nim) . '" '
                    . 'data-dosen="' . e($r->nama_dosen) . '">'
                    . '<i class="fa fa-refresh"></i> Sync</button>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="6" class="text-center">Tidak ada record konsultasi dengan dosen wali NULL/mismatch.</td></tr>';
        }

        $html .= '</tbody></table></div>';

        $html_missing = '<div class="table-responsive" style="max-height: 300px; overflow: auto;">';
        $html_missing .= '<table class="table table-bordered table-striped">';
        $html_missing .= '<thead><tr>';
        $html_missing .= '<th class="text-center">No.</th>';
        $html_missing .= '<th>NIM</th>';
        $html_missing .= '<th>Nama Mahasiswa</th>';
        $html_missing .= '<th>Dosen Wali (perwalian)</th>';
        $html_missing .= '<th class="text-center">Aksi</th>';
        $html_missing .= '</tr></thead><tbody>';

        if (!empty($missing)) {
            $i = 1;
            foreach ($missing as $r) {
                $html_missing .= '<tr>';
                $html_missing .= '<td class="text-center">' . $i++ . '</td>';
                $html_missing .= '<td>' . e($r->nim) . '</td>';
                $html_missing .= '<td>' . e($r->nama_mahasiswa) . '</td>';
                $html_missing .= '<td>' . e($r->nama_dosen) . ' <small class="text-muted">(#' . (int)$r->kode_dosen . ')</small></td>';
                $html_missing .= '<td class="text-center">';
                $html_missing .= '<button type="button" class="btn btn-success btn-xs flat btn-buat-konsultasi" '
                    . 'data-nim="' . e($r->nim) . '" '
                    . 'data-kode_dosen="' . (int)$r->kode_dosen . '" '
                    . 'data-dosen="' . e($r->nama_dosen) . '">'
                    . '<i class="fa fa-plus"></i> Buat</button>';
                $html_missing .= '</td>';
                $html_missing .= '</tr>';
            }
        } else {
            $html_missing .= '<tr><td colspan="5" class="text-center">Tidak ada perwalian yang belum punya record konsultasi.</td></tr>';
        }

        $html_missing .= '</tbody></table></div>';

        echo json_encode(array(
            'status' => true,
            'jumlah' => count($rows),
            'html' => $html,
            'jumlah_missing' => count($missing),
            'html_missing' => $html_missing,
        ));
    }

    public function sync_proses()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $kode_konsultasi_perwalian = $this->input->post('kode_konsultasi_perwalian');
        $angkatan = $this->input->post('angkatan');

        $kode_tahun_akademik = ($kode_tahun_akademik === '' || $kode_tahun_akademik === null) ? null : $kode_tahun_akademik;
        $kode_konsultasi_perwalian = ($kode_konsultasi_perwalian === '' || $kode_konsultasi_perwalian === null) ? null : (int)$kode_konsultasi_perwalian;
        $angkatan = ($angkatan === '' || $angkatan === null) ? null : $angkatan;

        $updated = $this->Perwalian_model->sync_konsultasi_kode_dosen($kode_tahun_akademik, $kode_konsultasi_perwalian, $angkatan);

        if ($updated === false) {
            echo json_encode(array('status' => false, 'message' => 'Gagal melakukan sinkronisasi.'));
            return;
        }

        if ($kode_konsultasi_perwalian !== null) {
            $message = 'Selesai: record konsultasi diperbarui dengan dosen wali dari perwalian.';
        } elseif ($angkatan !== null) {
            $message = 'Selesai: ' . $updated . ' record konsultasi_perwalian (angkatan ' . $angkatan . ') disinkronkan dengan dosen wali dari perwalian.';
        } else {
            $message = 'Selesai: ' . $updated . ' record konsultasi_perwalian disinkronkan dengan dosen wali dari perwalian.';
        }

        echo json_encode(array('status' => true, 'message' => $message, 'updated' => $updated));
    }

    public function buat_konsultasi_proses()
    {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $angkatan = $this->input->post('angkatan');
        $nim = $this->input->post('nim');

        if (empty($kode_tahun_akademik)) {
            echo json_encode(array('status' => false, 'message' => 'Tahun akademik wajib dipilih.'));
            return;
        }
        $angkatan = ($angkatan === '' || $angkatan === null) ? null : $angkatan;

        if (!empty($nim)) {
            $sql = "INSERT INTO konsultasi_perwalian (kode_tahun_akademik, nim, kode_dosen, status_cetak)
                    SELECT ?, p.nim, p.kode_dosen, 'N'
                    FROM perwalian p
                    WHERE p.nim = ? AND NOT EXISTS (
                        SELECT 1 FROM konsultasi_perwalian kp
                        WHERE kp.nim = p.nim AND kp.kode_tahun_akademik = ?)";
            $created = $this->db->query($sql, array($kode_tahun_akademik, $nim, $kode_tahun_akademik));
            $affected = $created ? $this->db->affected_rows() : 0;
            $message = $affected > 0
                ? 'Record konsultasi untuk NIM ' . $nim . ' berhasil dibuat.'
                : 'NIM ' . $nim . ' sudah memiliki record konsultasi untuk tahun akademik tersebut.';
            echo json_encode(array('status' => true, 'message' => $message, 'updated' => $affected));
            return;
        }

        $created = $this->Perwalian_model->buat_konsultasi_perwalian_hilang($kode_tahun_akademik, $angkatan);

        if ($created === false) {
            echo json_encode(array('status' => false, 'message' => 'Gagal membuat record konsultasi.'));
            return;
        }

        $message = $angkatan !== null
            ? 'Selesai: ' . $created . ' record konsultasi_perwalian dibuat untuk angkatan ' . $angkatan . '.'
            : 'Selesai: ' . $created . ' record konsultasi_perwalian dibuat.';

        echo json_encode(array('status' => true, 'message' => $message, 'updated' => $created));
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
