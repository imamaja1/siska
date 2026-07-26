<?php

class Pembayaran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
                'jurusan/m_tahun_akademik',
                'jurusan/program_studi/Nama_jurusan_model',
                'jurusan/program_studi/Jenjang_model',
                'jurusan/program_studi/Kode_jurusan_model',
                'jurusan/Perwalian_model',
                'keuangan/Status_perkuliahan_model',
                'akademik/Mahasiswa_model',
        ));
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
        $this->load->service('KeuanganService');
    }

    public function index()
    {
        $data['content'] = 'admin/keuangan/rekap_pembayaran/V_index';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Rekap Pembyaran";
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
//        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['program_studi'] = $this->Nama_jurusan_model->get();
//        $data['nim_mahasiswa'] = $this->Mahasiswa_model->get_nim();

        $this->load->view('admin/template/V_main', $data);
    }

    public function rekap(){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kkp_skripsi = get_kode_matakuliah_skripsi();
        $kode_program_studi = $this->input->post('kode_program_studi');
        $data['kode_program_studi'] = $kode_program_studi;
        $data['data'] = $this->keuanganservice->getRekapPembayaran($kode_tahun_akademik, $kode_program_studi);
        return $this->load->view('admin/keuangan/rekap_pembayaran/V_hasil_rekap_sks', $data);
    }

    public function excel($kode_program_studi){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $program_studi = $this->keuanganservice->getProgramStudiRow($kode_program_studi);
        $data['program_studi'] = $program_studi;
        $data['filename'] = "Rekap Pembayaran Prodi ".$program_studi->nama_program_studi;
        $data['data'] = $this->keuanganservice->getRekapPembayaran($kode_tahun_akademik, $kode_program_studi);
        return $this->load->view('admin/keuangan/rekap_pembayaran/Excel', $data);
    }

    public function add(){
        $data['content'] = 'admin/keuangan/pembayaran/V_add';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Add Pembayaran";
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['jenis_pembayaran'] = $this->keuanganservice->getJenisPembayaranAktif();
        $data['mahasiswa'] = $this->keuanganservice->getMahasiswaStatusA();
        $data['rekening'] = $this->keuanganservice->getRekening();

        $this->load->view('admin/template/V_main', $data);
    }

    public function store(){
        $input = $this->input->post();
        $jenis_bayar = $this->input->post('jenis_pembayaran_id');
        if (($jenis_bayar !== '4') && ($jenis_bayar !== '21') && ($jenis_bayar !== '22')){
            unset($input['jml_sks']);
        }
        $input['nominal_pembayaran'] = str_replace('.','',$this->input->post('nominal_pembayaran'));
        $this->keuanganservice->insertPembayaran($input);
    }

    public function last_pembayaran(){
        $pembayaran = $this->keuanganservice->getLastPembayaran();
        $data['pembayaran'] = $pembayaran;
        $this->load->view('admin/keuangan/pembayaran/last_pembayaran', $data);
    }

    public function delete($id){
        $this->keuanganservice->deletePembayaran($id);
    }

    public function index_pembayaran(){
        $data['content'] = 'admin/keuangan/pembayaran/V_index';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Add Pembayaran";
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['jenis_pembayaran'] = $this->keuanganservice->getJenisPembayaranAktif();
        $data['mahasiswa'] = $this->keuanganservice->getMahasiswaStatusA();
        $data['rekening'] = $this->keuanganservice->getRekening();

        $this->load->view('admin/template/V_main', $data);
    }

    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $result = $this->keuanganservice->autocompleteMahasiswa($keyword);
        if ($keyword !== "") {
            if (!empty($result)) {
                echo '<ul id="nim-list" class="list-group">';
                foreach ($result as $nim) {
                    $nama = "'$nim->nama_mahasiswa'";

                    echo '<li onClick="selectNim('. $nim->nim . ',' .$nama. ')" class="list-group-item">' . $nim->nim . '-'.$nim->nama_mahasiswa.'</li>';
                }
                echo '</ul>';
            } else {
                echo "Data tidak ditemukan";
            }
        }
    }

    public function history_pembayaran(){
        $nim = $this->input->post('nim');
        $pembayaran = $this->keuanganservice->getHistoryPembayaran($nim);
        $data = array(
                'data' => $pembayaran
        );
        $this->load->view('admin/keuangan/pembayaran/V_history', $data);
    }
}
