<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller
{

    var $limit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
                'akademik/krs_model',
                'kuisioner/kelas_kpat_model',
                'kuisioner/mengajar_model_kpat',
                'jurusan/m_tahun_akademik',
                'jurusan/program_studi/nama_jurusan_model',
                'jurusan/kurikulum/m_matakuliah',
                'akademik/mahasiswa_model',
        ));

        $this->load->service('KPATService');

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
  	public function add_kelas() {
      	$tahun_akademik = $this->input->post('ta');
        $nama_kelas_id = $this->input->post('nama_kelas_id');
        //$tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kode_program_studi = $this->session->userdata('kode_program_studi_sess');
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $data_kelas = array(
            'nama_kelas_id' => $nama_kelas_id,
            'semester' => substr(get_matakuliah($id_matakuliah)->kode_matakuliah, 5, 1),
            'id_matakuliah' => $id_matakuliah,
            'kode_tahun_akademik' => $tahun_akademik,
            'kode_program_studi' => $kode_program_studi,
        );
		 $tambah = $this->kelas_kpat_model->simpan_kelas($data_kelas);
        if ($tambah) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }
        echo json_encode($res);
        //echo json_encode($data_kelas);
    }
  	public function tidak_kelas($matakuliah_id, $kode_tahun_akademik) {
        $sub = $this->kpatservice->getKelasMahasiswaDetail($matakuliah_id, $kode_tahun_akademik);
        $kode_krs_detail = [];
        foreach ($sub as $key => $row) {
            $kode_krs_detail[$key] = $row->kode_krs_detail;
        }
        $data['nama_kelas'] = $this->kelas_kpat_model->get_kelas_exist($matakuliah_id, $kode_tahun_akademik);
        $query_builder = $this->kpatservice->getSemuaMahasiswaKpat($kode_tahun_akademik, $matakuliah_id);

        if (count($kode_krs_detail) > 0) {
            $data['semua_mhs'] = $query_builder->where_not_in('kd.kode_krs_detail', $kode_krs_detail)->get()->result();
        } else {
            $data['semua_mhs'] = $query_builder->get()->result();
        }
        $this->load->view('admin/akademik/kpat/kelas/partial/V_mahasiswa_no_kelas', $data);
        
    }
  	public function generate_kelas($kode_program_studi, $id_matakuliah, $kode_tahun_akademik) {
       	$matakuliah_kelas = $this->kelas_kpat_model->get_matakuliah_kelas($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $nama_kelas = $this->kelas_kpat_model->get_nama_kelas();
        $data_mahasiswa = $this->nama_jurusan_model->get_mahasiswa_kelas_by_kode_prodi($kode_program_studi, $id_matakuliah);
        if (count($matakuliah_kelas) > 0) {
            $jumlah_mahasiswa = $this->nama_jurusan_model->get_jumlah_mahasiswa_by_kode_prodi_kpat($kode_program_studi, $id_matakuliah);
            $data_mahasiswa = $this->nama_jurusan_model->get_mahasiswa_by_kode_prodi_kpat($kode_program_studi, $id_matakuliah);
            $data_kelas = array(
                'nama_kelas_id' => $nama_kelas[0]['nama_kelas_id'],
                'semester' => substr(get_matakuliah($id_matakuliah)->kode_matakuliah, 5, 1),
                'id_matakuliah' => $id_matakuliah,
                'kode_tahun_akademik' => $kode_tahun_akademik,
                'kode_program_studi' => $kode_program_studi,
            );
            $id_kelas = $this->kelas_kpat_model->simpan_kelas($data_kelas);
            foreach ($data_mahasiswa as $key) {
                $data_array = array(
                    'kelas_id' => $id_kelas,
                    'kode_krs_detail' => $key->kode_krs_detail,
                );
                $this->kelas_kpat_model->add_kelas_mahasiswa($data_array);
            }
         } else {
             $data = 'Data tidak ditemukan';
         }
    }

    public function index()
    {
        $data['content'] = 'admin/akademik/kpat/kelas/V_index';
        $data['judul'] = "Akademik";
        $data['sub_judul'] = "KRS KPAT";
        $data['judul_sub_judul'] = "KPAT";
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['jurusan'] = $this->nama_jurusan_model->get();
        $this->load->view('admin/template/V_main', $data);
    }
    public function kelas($ta, $prodi)
    {
        $query = $this->kpatservice->getMatakuliahKpat($ta, $prodi);
        $data['data'] = $query;
        $this->load->view('admin/akademik/kpat/kelas/partial/V_matakuliah', $data);
    }
    
    public function get_nama_kelas($kode_program_studi, $id_matakuliah, $kode_tahun_akademik) {
        $kode_tahun_akademik = $kode_tahun_akademik;
        $data_sess = array(
            'kode_program_studi_sess' => $kode_program_studi,
            'id_matakuliah_sess' => $id_matakuliah,
            'ta_sess' => $kode_tahun_akademik
        );
        $this->session->set_userdata($data_sess);
      	$data['ta'] = $kode_tahun_akademik;
       	$data['kelas'] = $this->kpatservice->getNamaKelas();
        $data['nama_matakuliah'] = $this->m_matakuliah->get_nama_matakuliah($id_matakuliah);
        $data['kode_matakuliah'] = get_matakuliah($id_matakuliah)->kode_matakuliah;
        $data['nama_kelas'] = $this->kelas_kpat_model->get_kelas_combobox($kode_tahun_akademik, $kode_program_studi, $id_matakuliah);
        $this->load->view('admin/akademik/kpat/kelas/partial/V_nama_kelas', $data);
    }
    
    public function hapus_kelas($kelas_id) {
        $hapus = $this->kpatservice->hapusKelasKpat($kelas_id);

        if ($hapus) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
        }
        echo json_encode($res);
    }
    public function data_mahasiswa($kelas_id, $kode_tahun_akademik) {
        $id_matakuliah = $this->session->userdata('id_matakuliah_sess');
        $this->session->set_userdata(array('kelas_id' => $kelas_id));
        $pengajar = $this->mengajar_model_kpat->get_pengajar($kelas_id);
        if (count($pengajar) > 0) {
            $data['pengajar'] = "";
            foreach ($pengajar as $row) {
                $data['pengajar'] = $data['pengajar'] . '' . $row->nama_dosen . ', ';
            }
        } else {
            $data['pengajar'] = "Belum ada pengajar";
        }
        $data['nama_kelas'] = $this->kelas_kpat_model->get_kelas_exist($id_matakuliah, $kode_tahun_akademik);
        $data['matakuliah'] = $this->kelas_kpat_model->get_matakuliah_by_kelas_id($kelas_id);
        $data['data'] = $this->kelas_kpat_model->get_mahasiswa_kelas($kelas_id);
        $data['kelas_id'] = $kelas_id;
        $data['dosen'] = $this->kpatservice->getAllDosen();

        $this->load->view('admin/akademik/kpat/kelas/partial/V_mahasiswa_kelas', $data);
    }
    public function simpan_pengampu($kelas_id) {
        //    $kelas_id = $this->input->post('kelas_id');
        $kode_dosen = $this->input->post('kode_dosen');

        foreach ($kode_dosen as $key => $value) {
            $data_mengajar = array(
                'kode_dosen' => $value,
                'kelas_id' => $kelas_id
            );

            $this->mengajar_model_kpat->simpan($data_mengajar);
        }
    }
    public function pengampu($kelas_id) {
        $pengajar = $this->mengajar_model_kpat->get_pengajar($kelas_id);
        $data['pengampu'] = $pengajar;
        return $this->load->view('admin/akademik/kpat/kelas/partial/V_pengampu', $data);
    }
    public function hapus_pengampu($id) {
        $hapus = $this->mengajar_model_kpat->hapus($id);

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
    public function pindah_kelas() {
        $kelas_mahsiswa_id = $this->input->post('kelas_mahasiswa_id');
        $kelas_id = $this->input->post('kelas_id');
        foreach ($kelas_mahsiswa_id as $key => $value) {
            $data_update = array(
                'kelas_id' => $kelas_id,
            );
            $pindah = $this->kelas_kpat_model->pindah_kelas($data_update, $value);
        }
        $this->session->set_flashdata('info',
                '<script>swal("Success", "Data berhasil diubah", "success");</script>'
        );
    }
    public function tambah_mahasiswa() {
        $nim = $this->input->post('nim');
        $kode_krs_detail = $this->input->post('kode_krs_detail');
        $kelas_id = $this->session->userdata('kelas_id');
        $cek_exis = $this->kelas_kpat_model->cek_exis($kode_krs_detail);
        if (count($cek_exis) > 0) {
            $res['status'] = 0;
            $res['message'] = "Data atas nama <strong>" . $cek_exis->nama_mahasiswa . "</strong> sudah ada di <strong>kelas " . $cek_exis->nama_kelas . "</strong>";
        } else {
            $data_array = array(
                'kelas_id' => $kelas_id,
                'kode_krs_detail' => $kode_krs_detail,
            );
            $tambah = $this->kelas_kpat_model->add_kelas_mahasiswa($data_array);
            if ($tambah) {
                $res['status'] = 1;
                $res['message'] = "Data berhasil ditambahkan";
            }
        }
        echo json_encode($res);
    }
    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $id_matakuliah = $this->input->post('id_matakuliah');
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $result = $this->kelas_kpat_model->autocomplate($keyword, $id_matakuliah, $kode_tahun_akademik);
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
        $hapus = $this->kelas_kpat_model->hapus($kelas_mahasiswa_id);

        if ($hapus) {
            $res['status'] = 1;
            $res['message'] = "Data berhasil dihapus";
        } else {
            $res['status'] = 0;
            $res['message'] = "Data gagal ditambahkan";
        }

        echo json_encode($res);
    }
    
    public function add_mahasiswa() {
        $kode_krs_detail = $this->input->post('kode_krs_detail');
        $kelas_id = $this->input->post('kelas_id');
        foreach ($kode_krs_detail as $key => $value) {
            $data_insert = array(
                'kelas_id' => $kelas_id,
                'kode_krs_detail' => $value,
            );
            $this->kpatservice->tambahMahasiswaKpat($data_insert);
        }
    }
}

?>