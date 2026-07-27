<?php
class Kpat extends CI_Controller
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

    public function get_matakuliah() {
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_array($this->session->userdata('kode_dosen'));
        $kode_program_studi = array_column($prodi, 'kode_program_studi');
        $data = $this->kaprodiservice->get_matakuliah_kpat_by_prodi($kode_tahun_akademik, $kode_program_studi);
        if($data){
            foreach ($data as $row) {
                echo "<option value='" . $row->id_matakuliah . "'>".$row->kode_matakuliah ." - ". $row->nama_matakuliah . "</option>";
            }
        }else{
            echo "<option value='' onlyread>Tidak Ada Matakuliah</option>";
        }  
    }
    public function index(){
        $prodi = $this->kaprodiservice->get_kaprodi_prodi_array($this->session->userdata('kode_dosen'));
        $kode_program_studi = array_column($prodi, 'kode_program_studi');
        $data['matakuliah'] = $this->kaprodiservice->get_matakuliah_by_prodi($kode_program_studi);
        $data['content'] = 'dosen/kaprodi/Kpat/v_index';
        $data['judul'] = 'KPAT Mahsiswa ';
        $data['sub_judul'] = 'Data Mahasiswa';
        $data['sub_judul'] = 'Halaman Mahasiswa';
        $data['semester'] = $this->m_tahun_akademik->get_semester();        
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['kode_tahun_akademik'] = $data['semester']->kode_tahun_akademik;
        $this->load->view('dosen/template/V_main', $data);
    }
    public function get_mahasiswa($ta = null, $angkatan = null, $kode_matakuliah = null) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_semester()->kode_tahun_akademik;
        }
        $data['data'] = $this->kaprodiservice->get_mahasiswa_kpat($ta, $kode_matakuliah, $angkatan);
        $this->load->view('dosen/kaprodi/Kpat/v_data_mhs',$data);
    }
}
