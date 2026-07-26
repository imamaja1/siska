<?php

class Kelas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/konsultasi_perwalian_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/program_studi/Kode_jurusan_model',
            'jurusan/M_tahun_akademik',
            'laporan/laporan_model',
            'akademik/krs_model',
            'kuisioner/kelas_model',
            'kuisioner/mengajar_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/kurikulum/m_matakuliah',
        ));
        $this->load->service('KaprodiService');
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }

        if (!isKaprodi($this->session->userdata('kode_dosen'))) {
            redirect('denied');
        }
        $this->load->library('pagination');
    }

    public function index()
    {
        $kode_tahun_akademik = $this->input->get('kode_tahun_akademik');
        if ($kode_tahun_akademik) {
            $data['tahun_now'] = $kode_tahun_akademik;
        } else {
            $data['tahun_now'] = $this->m_tahun_akademik->get_aktif();
        }
        $data['content'] = 'dosen/kaprodi/kelas/index';
        $data['judul'] = 'Mahsiswa KRS';
        $data['sub_judul'] = 'Data Mahasiswa';
        $data['sub_judul'] = 'Halaman Mahasiswa';
        $data['kode_tahun_akademik'] = tahun_akademik()->kode_tahun_akademik;
        $data['semester'] = $this->m_tahun_akademik->get_semester();
        $data['tahun_akademik'] = $this->kaprodiservice->order_by_get('tahun_akademik', 'kode_tahun_akademik DESC');
        $data['prodi'] = $this->kaprodiservice->get_kaprodi_prodi_row_kode($this->session->userdata('kode_dosen'))->kode_program_studi;
        $data['kelas'] = $this->kaprodiservice->get_kelas_by_prodi_ta($data['prodi'], $data['tahun_now']);
        
        $this->load->view('dosen/template/V_main', $data);
    }

    public function get_nama_kelas($kode_program_studi, $id_matakuliah, $kode_tahun_akademik) {
        $kode_tahun_akademik = $kode_tahun_akademik;
        $data_sess = array(
            'kode_program_studi_sess' => $kode_program_studi,
            'id_matakuliah_sess' => $id_matakuliah,
            'ta_sess' => $kode_tahun_akademik
        );
        $this->session->set_userdata($data_sess);
        $data['tahun'] = $kode_tahun_akademik;
        $data['kelas'] = $this->kaprodiservice->get_all_nama_kelas();
        $data['nama_matakuliah'] = $this->m_matakuliah->get_nama_matakuliah($id_matakuliah);
        $data['kode_matakuliah'] = get_matakuliah($id_matakuliah)->kode_matakuliah;
        $data['nama_kelas'] = $this->kelas_model->get_kelas_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $data['matakuliah'] = $this->kelas_model->get_matakuliah_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $this->load->view('dosen/kaprodi/kelas/nama_kelas', $data);
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
        $data['dosen'] = $this->kaprodiservice->get_all_dosen();

        $this->load->view('dosen/kaprodi/kelas/mahasiswa', $data);
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
    public function pengampu($kelas_id) {
        $pengajar = $this->mengajar_model->get_pengajar($kelas_id);
        $data['pengampu'] = $pengajar;
        return $this->load->view('dosen/kaprodi/kelas/pengampu', $data);
    }
    public function get_mahasiswa($ta = null, $angkatan = false, $status_krs = false) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_program_studi = $this->kaprodiservice->get_kaprodi_prodi_row_kode($kode_dosen)->kode_program_studi;
        $data['data'] = $this->kaprodiservice->get_mahasiswa_krsan($ta, $kode_program_studi, $angkatan, $status_krs);
        
        $this->load->view('dosen/kaprodi/krsan/v_data_mhs',$data);
    }
}
