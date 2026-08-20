<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kuisioner extends CI_Controller
{
    private $nim;
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'kuisioner/kelas_model',
            'kuisioner/mengajar_model',
            'kuisioner/kuisioner_model',
            'jurusan/m_tahun_akademik',
        ));
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index()
    {
//        print_r($this->kuisioner_model->get_soal_layanan());
//        die();
        $this->nim = $this->session->userdata('nim');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data['judul'] = 'Kuisioner';
        $data['conten'] = 'mahasiswa/V_kuisioner';
        $data['data'] = $this->kuisioner_model->get_matakuliah_kuisioner($this->nim, $kode_tahun_akademik);
        $data['soal_layanan'] = $this->kuisioner_model->get_soal_layanan();
        $data['axis'] = $this->kuisioner_model->layanan_axis($this->nim);
        //echo json_encode($data['axis']);
        $data['status_kuisioner'] = $this->kuisioner_model->get_setting();
        $this->load->view('mahasiswa/template/V_main', $data);
    }

    public function isi_kuisioner($kelas_mahasiswa_id)
    {
        $data['kelas_mahasiswa_id'] = $kelas_mahasiswa_id;
        $data['soal'] = $this->kuisioner_model->get_soal($kelas_mahasiswa_id);
        $data['matakuliah'] = $this->kuisioner_model->get_matakuliah($kelas_mahasiswa_id);
        $data['dosen'] = $this->kuisioner_model->get_dosen_mengajar($kelas_mahasiswa_id);
        if (!$data['matakuliah']) {
            $this->session->set_flashdata('info', '<div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Error!</h4>
                <p>Data kelas tidak ditemukan.</p>
              </div>');
            redirect(site_url('mahasiswa/kuisioner'));
        }
        $this->load->view('mahasiswa/V_isi_kuisioner', $data);
    }

    public function simpan()
    {
        $hasil = $this->input->post('hasil');
        $kelas_mahasiswa_id = $this->input->post('kelas_mahsiswa_id');
        $kritik = $this->input->post('kritik');
        $saran = $this->input->post('saran');

        if (!empty($hasil) && is_array($hasil)) {
            foreach ($hasil as $key => $value)
            {
                $data_array = array(
                    'kelas_mahasiswa_id' => $kelas_mahasiswa_id,
                    'kritik' => $kritik,
                    'saran' => $saran,
                    'hasil' => $value,
                    'soal_kuisioner_id' => $key,
                );

                $this->kuisioner_model->simpan($data_array);
            }
            $this->session->set_flashdata('info', '<div class="callout callout-success">
                <h4><i class="fa fa-check"></i> Sukses!</h4>
                <p>Kuisioner berhasil disimpan. Terimakasih atas partisipasi Anda.</p>
              </div>');
        } else {
            $this->session->set_flashdata('info', '<div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Gagal!</h4>
                <p>Tidak ada jawaban kuisioner yang dikirim.</p>
              </div>');
        }

        redirect(site_url('mahasiswa/kuisioner'));
    }

    public function simpan_layanan()
    {
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $hasil = $this->input->post('hasil');
        $nim = $this->session->userdata('nim');
        $masukan = $this->input->post('masukan');

        if (!empty($hasil) && is_array($hasil)) {
            foreach ($hasil as $key => $value)
            {
                $data_array = array(
                    'masukan' => $masukan,
                    'hasil' => $value,
                    'nim' => $nim,
                    'kode_tahun_akademik' => $kode_tahun_akademik,
                    'id_soal_pelayanan' => $key,
                );

                $this->kuisioner_model->simpan_layanan($data_array);
            }
            $this->session->set_flashdata('info', '<div class="callout callout-success">
                <h4><i class="fa fa-check"></i> Sukses!</h4>
                <p>Kuisioner layanan berhasil disimpan. Terimakasih atas partisipasi Anda.</p>
              </div>');
        } else {
            $this->session->set_flashdata('info', '<div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Gagal!</h4>
                <p>Tidak ada jawaban kuisioner yang dikirim.</p>
              </div>');
        }

        redirect(site_url('mahasiswa/kuisioner'));
    }
}