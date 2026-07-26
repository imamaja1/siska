<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/krs_model',
            'kuisioner/kelas_model',
            'kuisioner/mengajar_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_matakuliah',
        ));
        $this->load->service('KuisionerService');
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

    public function index() {
//        $data_mahasiswa = $this->krs_model->get_mahasiswa_by_mk();

        $data['content'] = 'admin/kelas/V_kelas';
        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = 'Halaman Kelas';
        $data['nama_jurusan'] = $this->nama_jurusan_model->get();
        $data['semester'] = $this->m_tahun_akademik->get_semester();
        $data['setting'] = $this->kuisionerservice->getSettingKuisioner(2);
        $data['tahun_akademik'] = $this->kuisionerservice->getTahunAkademikOrdered();
        $data['kode_tahun_akademik'] = tahun_akademik()->kode_tahun_akademik;

        $this->load->view('admin/template/V_main', $data);
    }

    public function dropdown_makul($kode_program_studi) {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $query = $this->kuisionerservice->getMatakuliahByProgramStudi($kode_program_studi, $tahun_akademik);
        $data['datas'] = $query;
        $this->load->view('admin/kelas/dropdown', $data);
    }

    public function filter() {
        $kode_program_studi = $this->input->post('kode_program_studi');
//        $semester = $this->input->post('semester');
//        $kode_matakuliah = $this->input->post('kode_matakuliah');
        $id_matakuliah = $this->input->post('id_matakuliah');

        $data_sess = array(
            'kode_program_studi_sess' => $kode_program_studi,
            'id_matakuliah_sess' => $id_matakuliah,
        );

        $this->session->set_userdata($data_sess);

        redirect(site_url('admin/kuisioner/kelas/hasil_filter'));
    }

    public function hasil_filter() {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_program_studi = $this->session->userdata('kode_program_studi_sess');
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = '| Status Kelas';
        $data['content'] = 'admin/kelas/V_status_kelas';
        $data['nama_matakuliah'] = $this->m_matakuliah->get_nama_matakuliah($id_matakuliah);
        $data['kode_matakuliah'] = get_matakuliah($id_matakuliah)->kode_matakuliah;
        $data['nama_kelas'] = $this->kelas_model->get_kelas_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $data['matakuliah'] = $this->kelas_model->get_matakuliah_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $data['exis_kelas'] = count($this->kelas_model->cek_exis_kelas_mahasiswa($kode_tahun_akademik, $kode_program_studi, $id_matakuliah));
        $this->load->view('admin/template/V_main', $data);
    }

    public function generate_kelas($kode_program_studi, $id_matakuliah, $kode_tahun_akademik) {
//    $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
//    $kode_tahun_akademik = $kode_tahun_akademik;
//        $kode_program_studi = $this->session->userdata('kode_program_studi_sess');
//        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $matakuliah_kelas = $this->kelas_model->get_matakuliah_kelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $nama_kelas = $this->kelas_model->get_nama_kelas();

        if (count($matakuliah_kelas) > 0) {
            $j = 0;
//            foreach ($matakuliah_kelas as $makul)
//            {
            $jumlah_mahasiswa = $this->nama_jurusan_model->get_jumlah_mahasiswa_by_kode_prodi($kode_program_studi, $id_matakuliah);

            if ($jumlah_mahasiswa <= 50) {
                $data_mahasiswa = $this->nama_jurusan_model->get_mahasiswa_by_kode_prodi($kode_program_studi, $id_matakuliah);
//                    print_r($data_mahasiswa);
//                    die();
                $i = 0;
                $data_kelas = array(
                    'nama_kelas_id' => $nama_kelas[0]['nama_kelas_id'],
                    'semester' => substr(get_matakuliah($id_matakuliah)->kode_matakuliah, 5, 1),
                    'id_matakuliah' => $id_matakuliah,
                    'kode_tahun_akademik' => $kode_tahun_akademik,
                    'kode_program_studi' => $kode_program_studi,
                );

                $id_kelas = $this->kelas_model->simpan_kelas($data_kelas);

                foreach ($data_mahasiswa as $key) {
                    $data_array = array(
                        'kelas_id' => $id_kelas,
                        'kode_krs_detail' => $key->kode_krs_detail,
                    );
                    $this->kelas_model->add_kelas_mahasiswa($data_array);
                }
            } else {
                $offset = 0;
                $limit = 50;
                $mod = $jumlah_mahasiswa % $limit;
                $mod > 0 ? $n = ($jumlah_mahasiswa - ($jumlah_mahasiswa % $limit)) / $limit + 1 : $n = ($jumlah_mahasiswa - ($jumlah_mahasiswa % $limit)) / $limit;

                $m = 0;
                for ($i = 1; $i <= $n; $i++) {
                    $data_kelas = array(
                        'nama_kelas_id' => $nama_kelas[$m]['nama_kelas_id'],
                        'semester' => substr(get_matakuliah($id_matakuliah)->kode_matakuliah, 5, 1),
                        'id_matakuliah' => $id_matakuliah,
                        'kode_tahun_akademik' => $kode_tahun_akademik,
                        'kode_program_studi' => $kode_program_studi,
                    );

                    $id_kelas = $this->kelas_model->simpan_kelas($data_kelas);
                    $data_mahasiswa = $this->nama_jurusan_model->get_mahasiswa_by_kode_prodi($kode_program_studi, $id_matakuliah, $offset, $limit);
                    foreach ($data_mahasiswa as $row) {
                        $data_array = array(
                            'kelas_id' => $id_kelas,
                            'kode_krs_detail' => $row->kode_krs_detail,
                        );
                        $this->kelas_model->add_kelas_mahasiswa($data_array);
                    }
                    $m++;
                    $offset = $offset + $limit;
                }
            }
            $j++;
//            }
        } else {
            $data = 'Data tidak ditemukan';
        }
        redirect(site_url('admin/kuisioner/kelas/hasil_filter'));
    }

    public function data_mahasiswa($kelas_id, $kode_tahun_akademik) {
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $this->session->set_userdata(array('kelas_id' => $kelas_id));
        $pengajar = $this->mengajar_model->get_pengajar($kelas_id);
        if (count($pengajar) > 0) {
            $data['pengajar'] = "";
            foreach ($pengajar as $row) {
                $data['pengajar'] = $data['pengajar'] . '' . $row->nama_dosen . ', ';
            }
        } else {
            $data['pengajar'] = "Belum ada pengajar";
        }
        $data['nama_kelas'] = $this->kelas_model->get_kelas_exist($id_matakuliah, $kode_tahun_akademik);
        $data['matakuliah'] = $this->kelas_model->get_matakuliah_by_kelas_id($kelas_id);
        $data['data'] = $this->kelas_model->get_mahasiswa_kelas($kelas_id);
        $data['kelas_id'] = $kelas_id;
        $data['dosen'] = $this->kuisionerservice->getAllDosen();

        $this->load->view('admin/kelas/V_mahasiswa_kelas', $data);
    }

    public function cetak_kelas($kelas_id) {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $nama_kelas_id = $this->session->userdata('nama_kelas_id_sess');
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $matakuliah = get_matakuliah($id_matakuliah);
        $data['nama_kelas'] = $this->kelas_model->get_nama_kelas_by_kelas_id($kelas_id);
        $data['file_name'] = $matakuliah->nama_matakuliah . '-' . $data['nama_kelas']->nama_kelas;
        $data['nama_matakuliah'] = $matakuliah->nama_matakuliah;
        $data['kode_matakuliah'] = get_matakuliah($id_matakuliah)->kode_matakuliah;
        $data['tahun_akademik'] = $tahun_akademik;
        $data['prodi'] = $this->mengajar_model->get_program_studi_by_kode_mk($id_matakuliah);
        $data['data'] = $this->kelas_model->get_mahasiswa_kelas($kelas_id);
        $data['pengajar'] = $this->mengajar_model->get_pengajar($kelas_id);

        $this->load->view('admin/kelas/V_cetak_kelas', $data);
    }

    public function tambah_mahasiswa() {
        $nim = $this->input->post('nim');
        $kode_krs_detail = $this->input->post('kode_krs_detail');
        $kelas_id = $this->session->userdata('kelas_id');
        $cek_exis = $this->kelas_model->cek_exis($kode_krs_detail);
        if (count($cek_exis) > 0) {
            $res['status'] = 0;
            $res['message'] = "Data atas nama <strong>" . $cek_exis->nama_mahasiswa . "</strong> sudah ada di <strong>kelas " . $cek_exis->nama_kelas . "</strong>";
        } else {
            $data_array = array(
                'kelas_id' => $kelas_id,
                'kode_krs_detail' => $kode_krs_detail,
            );
            $tambah = $this->kelas_model->add_kelas_mahasiswa($data_array);
            if ($tambah) {
                $res['status'] = 1;
                $res['message'] = "Data berhasil ditambahkan";
            }
        }
        echo json_encode($res);
    }

    public function pindah_kelas() {
        $kelas_mahsiswa_id = $this->input->post('kelas_mahasiswa_id');
        $kelas_id = $this->input->post('kelas_id');
        foreach ($kelas_mahsiswa_id as $key => $value) {
            $data_update = array(
                'kelas_id' => $kelas_id,
            );
            $pindah = $this->kelas_model->pindah_kelas($data_update, $value);
        }
//    $this->session->set_flashdata('info',
//            '<script>swal("Success", "Data berhasil diubah", "success");</script>'
//    );
//
//    redirect(site_url('admin/kuisioner/kelas/hasil_filter'));
    }

    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $result = $this->kelas_model->autocomplate($keyword, $id_matakuliah, $kode_tahun_akademik);
        if ($keyword != '') {
            if (!empty($result)) {
                echo '<ul id="nim-list" class="list-group">';
                foreach ($result as $nim) {
                    echo '<li onClick="selectNim(' . $nim->nim . ',' . $nim->kode_krs_detail . ')" class="list-group-item">' . $nim->nim . ' - ' . $nim->nama_mahasiswa . '</li>';
                }
                echo '</ul>';
            } else {
                echo "Data tidak ditemukan";
            }
        }
    }

    public function hapus($kelas_mahasiswa_id) {
        $hapus = $this->kelas_model->hapus($kelas_mahasiswa_id);

        if ($hapus) {
            $res['status'] = 1;
            $res['message'] = "Data berhasil dihapus";
        } else {
            $res['status'] = 0;
            $res['message'] = "Data gagal ditambahkan";
        }

        echo json_encode($res);
    }

    public function add_kelas() {
        $nama_kelas_id = $this->input->post('nama_kelas_id');
        $tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kode_program_studi = $this->session->userdata('kode_program_studi_sess');
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $data_kelas = array(
            'nama_kelas_id' => $nama_kelas_id,
            'semester' => substr(get_matakuliah($id_matakuliah)->kode_matakuliah, 5, 1),
            'id_matakuliah' => $id_matakuliah,
            'kode_tahun_akademik' => $tahun_akademik,
            'kode_program_studi' => $kode_program_studi,
        );

        $tambah = $this->kelas_model->simpan_kelas($data_kelas);

        if ($tambah) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }

        echo json_encode($res);
    }

    public function hapus_kelas($kelas_id) {
        $hapus = $this->kuisionerservice->deleteKelas($kelas_id);

        if ($hapus) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }

        echo json_encode($res);
    }

    public function aktif() {
        $ubah = $this->kuisionerservice->updateSettingKuisioner(2, array('aktif_kuisioner' => 'A'));
        redirect(site_url('admin/kuisioner/kelas'));
    }

    public function nonaktif() {
        $ubah = $this->kuisionerservice->updateSettingKuisioner(2, array('aktif_kuisioner' => 'N'));
        redirect(site_url('admin/kuisioner/kelas'));
    }

    public function get_matakuliah($kode_program_studi, $kode_tahun_akademik) {
        $tahun_akademik = $kode_tahun_akademik;
        $query = $this->kuisionerservice->getMatakuliahWithKelas($kode_program_studi, $tahun_akademik);
        $data['data'] = $query;
        $this->load->view('admin/kelas/partial/V_matakuliah', $data);
    }

    public function get_nama_kelas($kode_program_studi, $id_matakuliah, $kode_tahun_akademik) {
//    $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_tahun_akademik = $kode_tahun_akademik;
        $data_sess = array(
            'kode_program_studi_sess' => $kode_program_studi,
            'id_matakuliah_sess' => $id_matakuliah,
            'ta_sess' => $kode_tahun_akademik
        );
        $this->session->set_userdata($data_sess);
        $data['kelas'] = $this->kuisionerservice->getAllNamaKelas();
        $data['nama_matakuliah'] = $this->m_matakuliah->get_nama_matakuliah($id_matakuliah);
        $data['kode_matakuliah'] = get_matakuliah($id_matakuliah)->kode_matakuliah;
        $data['nama_kelas'] = $this->kelas_model->get_kelas_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $data['matakuliah'] = $this->kelas_model->get_matakuliah_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $this->load->view('admin/kelas/partial/V_nama_kelas', $data);
//      $this->load->view('admin/template/V_main', $data);
    }

    public function pengampu($kelas_id) {
        $pengajar = $this->mengajar_model->get_pengajar($kelas_id);
        $data['pengampu'] = $pengajar;
        return $this->load->view('admin/kelas/partial/V_pengampu', $data);
    }

    public function simpan_pengampu($kelas_id) {
//    $kelas_id = $this->input->post('kelas_id');
        $kode_dosen = $this->input->post('kode_dosen');

        foreach ($kode_dosen as $key => $value) {
            $data_mengajar = array(
                'kode_dosen' => $value,
                'kelas_id' => $kelas_id
            );

            $this->mengajar_model->simpan($data_mengajar);
        }
    }

    public function hapus_pengampu($id) {
        $hapus = $this->mengajar_model->hapus($id);

        if ($hapus) {
            $this->session->set_flashdata('info',
                '<script>swal("Success", "Data berhasil dihapus", "success");</script>');
            redirect(site_url('admin/kuisioner/mengajar'));
        } else {
            $this->session->set_flashdata('info',
                '<script>swal("Gagal", "Data gagal dihapus", "error");</script>');
            redirect(site_url('admin/kuisioner/mengajar'));
        }
    }

    public function tidak_kelas($matakuliah_id, $kode_tahun_akademik) {
//      $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
//      $kode_tahun_akademik = $kode_tahun_akademik;
        $sub = $this->kuisionerservice->getKrsDetailByMatakuliah($kode_tahun_akademik, $matakuliah_id);
        $kode_krs_detail = [];
        foreach ($sub as $row) {
            $kode_krs_detail[] = $row->kode_krs_detail;
        }
        $data['nama_kelas'] = $this->kelas_model->get_kelas_exist($matakuliah_id, $kode_tahun_akademik);
        $data['semua_mhs'] = $this->kuisionerservice->getMahasiswaWithoutKelas($kode_tahun_akademik, $matakuliah_id, $kode_krs_detail);
        $this->load->view('admin/kelas/partial/V_mahasiswa_no_kelas', $data);
    }

    public function add_mahasiswa() {
        $kode_krs_detail = $this->input->post('kode_krs_detail');
        $kelas_id = $this->input->post('kelas_id');
        foreach ($kode_krs_detail as $key => $value) {
            $data_insert = array(
                'kelas_id' => $kelas_id,
                'kode_krs_detail' => $value,
            );
            $this->kuisionerservice->insertKelasMahasiswa($data_insert);
        }
    }

    public function aktivasi_nilai() {
        $data = array(
            'content' => 'admin/kelas/aktivasi_nilai/V_index',
            'judul' => 'Kuisioner',
            'sub_judul' => 'Aktivasi Nilai',
            'tahun_akademik' => $this->kuisionerservice->getTahunAkademikOrdered(),
        );
        $this->load->view('admin/template/V_main', $data);
    }

    public function tanggal_aktivasi($tahun_akademik) {
        $aktivasi = $this->kuisionerservice->getAktivasi($tahun_akademik);
        $data = array('aktivasi' => $aktivasi,
            'kode_tahun_akademik' => $tahun_akademik,
        );
        if ($aktivasi) {
            $this->load->view('admin/kelas/aktivasi_nilai/V_aktivasi', $data);
        } else {
            $this->load->view('admin/kelas/aktivasi_nilai/V_anaktivasi');
        }
    }

    public function generit_aktivasi($tahun_akademik) {
        $waktu = date('Y-m-d 00:00:00');
        $input = array(
            'kode_tahun_akademik' => $tahun_akademik,
            'tgl_awal_uts' => $waktu,
            'tgl_akhir_uts' => $waktu,
            'tgl_awal_uas' => $waktu,
            'tgl_akhir_uas' => $waktu,
            'param_uts' => "0",
            'param_uas' => "0",
        );
        $this->kuisionerservice->insertAktivasi($input);
        $aktivasi = $this->kuisionerservice->getAktivasi($tahun_akademik);
        $data = array('aktivasi' => $aktivasi,
            'kode_tahun_akademik' => $tahun_akademik
        );
        $this->load->view('admin/kelas/aktivasi_nilai/V_aktivasi', $data);
    }

    public function update_aktivasi_uts($tahun_akademik) {

        $input = array(
            'kode_tahun_akademik' => $tahun_akademik,
            'tgl_awal_uts' => $this->input->post('tgl_awal_uts'),
            'tgl_akhir_uts' => $this->input->post('tgl_akhir_uts'),
            'param_uts' => "1",
        );

        $this->kuisionerservice->updateAktivasi($tahun_akademik, $input);

        $tau = $this->kuisionerservice->getAktivasiArray($tahun_akademik);

        if ($tau['tgl_akhir_uts'] >= date('Y-m-d H:s:i', strtotime($this->input->post('tgl_akhir_uts')))) {
            $data_update = array(
                'param_uts' => "",
            );
            
            $where_in = array('F','R');
            $this->kuisionerservice->updateKelasByWhereIn('status_nilai_uts', $where_in, $tahun_akademik, $data_update);
        }
    }

    public function update_aktivasi_uas($tahun_akademik) {

        $input = array(
            'kode_tahun_akademik' => $tahun_akademik,
            'tgl_awal_uas' => $this->input->post('tgl_awal_uas'),
            'tgl_akhir_uas' => $this->input->post('tgl_akhir_uas'),
            'param_uas' => "1",
        );

        $this->kuisionerservice->updateAktivasi($tahun_akademik, $input);

        $tau = $this->kuisionerservice->getAktivasiArray($tahun_akademik);

        if ($tau['tgl_akhir_uas'] >= date('Y-m-d H:s:i', strtotime($this->input->post('tgl_akhir_uas')))) {
            $data_update = array(
                'param_uas' => "",
            );
            
            $where_in = array('F','R');
            $this->kuisionerservice->updateKelasByWhereIn('status_nilai_uas', $where_in, $tahun_akademik, $data_update);
        }

    }



}
