<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Status_perkuliahan extends CI_Controller
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
        $this->load->helper(array('cookie'));
        $this->load->service('KeuanganService');
    }

    public function index()
    {
        if (is_null(get_cookie('kode_tahun_akademik'))){
            setcookie('kode_tahun_akademik',tahun_akademik()->kode_tahun_akademik,time()+(60*15),'/');
            setcookie('tahun_akademik',tahun_akademik()->tahun_akademik,time()+(60*15),'/');
            setcookie('semester',tahun_akademik()->semester,time()+(60*15),'/');
        }
        $data['content'] = 'admin/keuangan/V_status_perkuliahan';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Status Perkuliahan";
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['tahun_angkatan'] = $this->m_tahun_akademik->tahun_angkatan();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();
        $data['nim_mahasiswa'] = $this->Mahasiswa_model->get_nim();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter()
    {
        # code...
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $tahun_angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');

        $data_sess = array(
            'tahun_akademik_sess' => $kode_tahun_akademik,
            'tahun_angkatan_sess' => $tahun_angkatan,
            'kode_prodi_sess' => $kode_program_studi,
        );
        $this->session->set_userdata($data_sess);

        redirect(site_url('admin/keuangan/Status_perkuliahan/tampil_filter'));
    }

    public function filter1()
    {
        # code...
        $tahun_angkatan = $this->input->post('angkatan');
        $kode_program_studi = $this->input->post('prodi');

        redirect(site_url('admin/keuangan/Status_perkuliahan/tampil_filter1/' . $tahun_angkatan . '/' . $kode_program_studi));
    }

    public function tampil_filter()
    {
        $kode_tahun_akademik = $this->session->userdata('tahun_akademik_sess');
        $tahun_angkatan = $this->session->userdata('tahun_angkatan_sess');
        $kode_prodi = $this->session->userdata('kode_prodi_sess');

        $data['content'] = 'admin/keuangan/V_mahasiswa_status';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Status Perkuliahan";
        $data['data'] = $this->Status_perkuliahan_model->filter($kode_tahun_akademik, $tahun_angkatan, $kode_prodi);

        $this->load->view('admin/template/V_main', $data);
    }

    public function tampil_filter1($tahun_angkatan, $kode_program_studi)
    {
        $data['content'] = 'admin/keuangan/V_tambah_status';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Status Perkuliahan";
        $tahun_akademik = get_cookie('kode_tahun_akademik');
        $data['data'] = $this->Status_perkuliahan_model->filter1($tahun_angkatan, $kode_program_studi, $tahun_akademik);

        $param = array(
            'tahun_angkatan' => $tahun_angkatan,
            'kode_program_studi' => $kode_program_studi
        );

        $this->session->set_userdata($param);

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah_status_semua()
    {
        # code...
        $param = $this->input->post('cekbox');
        $status = $this->input->post('status');

        $semester = get_cookie('semester');
        $tahun_akademik = substr(get_cookie('tahun_akademik'),2,2);
        $kode_tahun_akademik = get_cookie('kode_tahun_akademik');

        $tahun_angkatan = $this->session->userdata('tahun_angkatan');
        $kode_program_studi = $this->session->userdata('kode_program_studi');

        if ($semester == 0) {
            $sem = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $sem = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }

        foreach ($param as $value) {
            $data = array(
                'kode_tahun_akademik' => $kode_tahun_akademik,
                'status_perkuliahan' => $status,
                'semester' => $sem,
                'nim' => $value,
            );
            if ($status == 'L') {
                $this->keuanganservice->updateStatusMahasiswa($value, 'N');
            }
            $data_konsultasi_perwalian = array(
                'nim' => $value,
                'kode_tahun_akademik' => $kode_tahun_akademik,
            );
            $this->Status_perkuliahan_model->simpan($data, $value);
            $this->Perwalian_model->simpan_konsultasi_perwalian($data_konsultasi_perwalian);
        }

        redirect(site_url('admin/keuangan/Status_perkuliahan/tampil_filter1/' . $tahun_angkatan . '/' . $kode_program_studi));
    }

    public function simpan_status()
    {
        # code...
        $nim = $this->input->post('nim');
        $status = $this->input->post('status');

        $tahun_angkatan = substr($nim, 0, 2);

        $semester = get_cookie('semester');
        $tahun_akademik = substr(get_cookie('tahun_akademik'),2,2);
        $kode_tahun_akademik = get_cookie('kode_tahun_akademik');

        if ($semester == 0) {
            # code...
            $sem = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $sem = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }
        $data = array(
            'nim' => $nim,
            'status_perkuliahan' => $status,
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'semester' => $sem,
        );
        if ($status == 'L') {
            $this->keuanganservice->updateStatusMahasiswa($nim, 'N');
        }
        $data_konsultasi_perwalian = array(
            'nim' => $nim,
            'kode_tahun_akademik' => $kode_tahun_akademik,
        );
        $this->Perwalian_model->simpan_konsultasi_perwalian($data_konsultasi_perwalian);
        if ($this->Status_perkuliahan_model->simpan($data)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function cek_exis()
    {
        $nim = $this->input->post('nim');
        $tahun = $this->m_tahun_akademik->get_aktif();
        $kode_tahun_akademik = get_cookie('kode_tahun_akademik');
        $mah = $this->keuanganservice->getMahasiswaByNim($nim);

        $cek = $this->Status_perkuliahan_model->cek_exis($nim, $kode_tahun_akademik);
        if ($cek > 0) {
            $res['status'] = 1;
        } else {
            $res['status'] = 0;
            $res['data'] = $mah;
        }

        echo json_encode($res);
    }

    public function autocomplate()
    {
        $keyword = $this->input->post('keyword');
        $result = $this->Status_perkuliahan_model->autocomplate($keyword);
        if (!empty($result)) {
            echo '<ul id="nim-list" class="list-group">';
            foreach ($result as $nim) {
                echo '<li onClick="selectNim(' . $nim->nim . ')" class="list-group-item">' . $nim->nim . '</li>';
            }
            echo '</ul>';
        } else {
            echo "Data tidak ditemukan";
        }
    }

    public function edit($kode_status_perkuliahan)
    {
        $data['id'] = $kode_status_perkuliahan;
        $data['data'] = $this->keuanganservice->getStatusPerkuliahanByKode($kode_status_perkuliahan);
        $this->load->view('admin/keuangan/Popover_edit', $data);
    }

    public function update($id)
    {
        $angkatan = $this->session->userdata('tahun_angkatan_sess');
        $kode_tahun_akademik = $this->session->userdata('tahun_akademik_sess');
        $kode_prodi = $this->session->userdata('kode_prodi_sess');
        $sp = $this->keuanganservice->getNimFromStatusPerkuliahan($id);
        $data = $this->input->post();
        $status = $this->input->post('status_perkuliahan');
        if ($status == 'L') {
            $this->keuanganservice->updateStatusMahasiswa($sp->nim, 'N');
        } else {
            $this->keuanganservice->updateStatusMahasiswa($sp->nim, 'A');
        }
        $this->keuanganservice->updateStatusPerkuliahan($id, $data);
        redirect(site_url('admin/keuangan/status_perkuliahan/tampil_filter/' . $kode_tahun_akademik . '/' . $angkatan . '/' . $kode_prodi));

    }

    public function rekap()
    {
        $data['content'] = 'admin/keuangan/V_rekap';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Rekap Status Perkuliahan";
        $data['judul_sub_judul'] = '';
        $data['tahun_akademik'] = $this->m_tahun_akademik->get();
        $data['nama_jurusan'] = $this->Nama_jurusan_model->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter_rekap()
    {
        $kode_tahun_akademik = $this->input->post('tahun_akademik');
        $kode_program_studi = $this->input->post('prodi');

        $kode = $this->Nama_jurusan_model->get_kode($kode_program_studi);
        $kode_jurusan = $this->Kode_jurusan_model->get_kode($kode->id_jurusan)->kode_jurusan;
        $kode_jenjang = $this->Jenjang_model->get_kode($kode->id_jenjang)->kode_jenjang;

        $data_sess = array(
            'tahun_akademik_sess' => $kode_tahun_akademik,
            'kode_jenjang_sess' => $kode_jenjang,
            'kode_jurusan_sess' => $kode_jurusan,
            'kode_prodi_sess' => $kode_program_studi,
        );
        $this->session->set_userdata($data_sess);

        redirect(site_url('admin/keuangan/status_perkuliahan/data_rekap'));
    }

    public function data()
    {
        $kode_tahun_akademik = $this->session->userdata('tahun_akademik_sess');
        $kode_jurusan = $this->session->userdata('kode_jurusan_sess');
        $kode_jenjang = $this->session->userdata('kode_jenjang_sess');
        $kode_prodi = $this->session->userdata('kode_prodi_sess');
        $tahun_angkatan = $this->m_tahun_akademik->tahun_angkatan();

        $status = array('A', 'C', 'T', 'B', 'P', 'L');
        $i = 0;
        foreach ($tahun_angkatan as $row) {
            $angkatan = substr($row->tahun_akademik, 2, 2);
            $tahun_angkatan = substr($row->tahun_akademik, 0, 4);
            $nama_kurikulum = $this->keuanganservice->getNamaKurikulumByAngkatan($angkatan, $kode_prodi);
            $tugas_akhir = $this->keuanganservice->getTugasAkhirId($nama_kurikulum->kode_nama_kurikulum);
            if ($angkatan < 19) {
                $mhs_tugas_akhir = $this->keuanganservice->getMhsTugasAkhirOld($kode_tahun_akademik, $tugas_akhir, $kode_jurusan, $kode_jenjang, $angkatan);
            } else {
                $kode = $this->keuanganservice->getProgramStudiByKode($kode_prodi);
                $mhs_tugas_akhir = $this->keuanganservice->getMhsTugasAkhirNew($kode_tahun_akademik, $tugas_akhir, $kode->kode_fakultas, $kode->kode_prodi_univ, $angkatan);
            }

            $res[$i]['mhs_tugas_akhir'] = count($mhs_tugas_akhir);
            $res[$i]['tugas_akhir'] = $tugas_akhir;
            $res[$i]['kode_nama_kuirkulum'] = $nama_kurikulum->kode_nama_kurikulum;
            $res[$i]['angkatan'] = $tahun_angkatan;
            if ($angkatan < 19) {
                foreach ($status as $key => $val) {
                    $sp = $this->keuanganservice->getStatusPerkuliahanCountOld($kode_tahun_akademik, $angkatan, $kode_jurusan, $kode_jenjang, $val);
                    $res[$i][$val] = count($sp) > 0 ? $sp->jumlah : 0;
                }
            } else {
                $kode = $this->keuanganservice->getProgramStudiByKode($kode_prodi);
                foreach ($status as $key => $val) {
                    $sp = $this->keuanganservice->getStatusPerkuliahanCountNew($kode_tahun_akademik, $angkatan, $kode->kode_fakultas, $kode->kode_prodi_univ, $val);
                    $res[$i][$val] = count($sp) > 0 ? $sp->jumlah : 0;
                }
            }

            $i++;
        }

        return $res;
    }

    public function data_rekap()
    {
        $kode_tahun_akademik = $this->session->userdata('tahun_akademik_sess');
        $kode_prodi = $this->session->userdata('kode_prodi_sess');
        $data['content'] = 'admin/keuangan/V_data_rekap';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Data Rekap";
        $data['judul_sub_judul'] = '';
        $data['data'] = $this->data();
        $data['tahun_akademik'] = $this->m_tahun_akademik->get_all_byid($kode_tahun_akademik);
        $data['prodi'] = $this->Nama_jurusan_model->get_all_byid($kode_prodi);

        $this->load->view('admin/template/V_main', $data);
    }

    public function cetak()
    {
        $kode_prodi = $this->session->userdata('kode_prodi_sess');
        $prodi = $this->Nama_jurusan_model->get_all_byid($kode_prodi);
        $data = $this->data();

        $table = '<table border="1">';
        $table .= '<tr>';
        $table .= '<th>TAHUN ANGKATAN</th>';
        $table .= '<th>AKTIF</th>';
        $table .= '<th>PINDAH</th>';
        $table .= '<th>TANPA KETERANGAN</th>';
        $table .= '<th>CUTI</th>';
        $table .= '<th>TUGAS AKHIR</th>';
        $table .= '</tr>';
        foreach ($data as $row) :
            $table .= '<tr>';
            $table .= '<td>' . $row['angkatan'] . '</td>';
            $table .= '<td>' . $row['A'] . '</td>';
            $table .= '<td>' . $row['P'] . '</td>';
            $table .= '<td>' . $row['T'] . '</td>';
            $table .= '<td>' . $row['C'] . '</td>';
            $table .= '<td>' . $row['mhs_tugas_akhir'] . '</td>';
            $table .= '</tr>';
        endforeach;
        $table .= '</table>';

        $data['table'] = $table;
        $data['file_name'] = $prodi->singkatan_program_studi . '-' . $prodi->nama_program_studi;

        $this->load->view('admin/laporan/rekap_ipk/V_spreadsheet_view', $data);
    }

    public function rekap_sks()
    {
        $data['content'] = 'admin/keuangan/V_rekap_sks';
        $data['judul'] = "Keuangan";
        $data['sub_judul'] = "Rekap SKS Mahasiswa";

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter_rekap_sks(){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kkp_skripsi = get_kode_matakuliah_skripsi();

        $data['data'] = $this->keuanganservice->getRekapSksData($kode_tahun_akademik, $kkp_skripsi);
        return $this->load->view('admin/keuangan/V_hasil_rekap_sks', $data);

    }

  public function cetak_filter_rekap_sks()
    {
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kkp_skripsi = get_kode_matakuliah_skripsi();

        $data['data'] = $this->keuanganservice->getRekapSksData($kode_tahun_akademik, $kkp_skripsi);
        $data['filename'] = 'Rekap SKS Mahasiswa ' . tahun_akademik()->tahun_akademik . ' ' . (tahun_akademik()->semester == 0 ? 'Genap' : 'Ganjil') . ' - ' . date('d-m-Y');
        return $this->load->view('admin/keuangan/V_cetak_hasil_rekap_sks', $data);

    }

    public function filter_rekap_sks_skripsi(){
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kkp_skripsi = get_kode_matakuliah_skripsi();

        $data['data'] = $this->keuanganservice->getRekapSksSkripsiData($kode_tahun_akademik, $kkp_skripsi);
        return $this->load->view('admin/keuangan/V_hasil_rekap_sks_skripsi', $data);
    }

   public function cetak_filter_rekap_sks_skripsi()
    {
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $kkp_skripsi = get_kode_matakuliah_skripsi();

        $data['data'] = $this->keuanganservice->getRekapSksSkripsiData($kode_tahun_akademik, $kkp_skripsi);
        $data['filename'] = 'Rekap Pembayaran Skripsi Mahasiswa ' . tahun_akademik()->tahun_akademik . ' ' . (tahun_akademik()->semester == 0 ? 'Genap' : 'Ganjil') . ' - ' . date('d-m-Y');
        return $this->load->view('admin/keuangan/V_cetak_hasil_rekap_sks_skripsi', $data);
    }

    public function bayar_sks($kode_status_perkuliahan){
        $sp = $this->keuanganservice->getStatusPerkuliahanByKode($kode_status_perkuliahan);
        if ($sp->pembayaran_sks == '0'){
            $val = '1';
        }else{
            $val = '0';
        }
        $ubah = $this->keuanganservice->updatePembayaranStatus($kode_status_perkuliahan, array('pembayaran_sks'=> $val));
        if ($ubah){
            $res['status'] = 'true';
            $res['val'] = $val;
        }else{
            $res['status'] = 'false';
        }

        echo json_encode($res);
    }

    public function bayar_lab($kode_status_perkuliahan){
        $sp = $this->keuanganservice->getStatusPerkuliahanByKode($kode_status_perkuliahan);
        if ($sp->pembayaran_lab == '0'){
            $val = '1';
        }else{
            $val = '0';
        }
        $ubah = $this->keuanganservice->updatePembayaranStatus($kode_status_perkuliahan, array('pembayaran_lab'=> $val));
        if ($ubah){
            $res['status'] = 'true';
            $res['val'] = $val;
        }else{
            $res['status'] = 'false';
        }

        echo json_encode($res);
    }

    public function bayar(){
        $key = $this->input->post('key');
        $value = $this->input->post('value');
        $kode_status_perkuliahan = $this->input->post('kode_status_perkuliahan');
        $ubah = $this->keuanganservice->updatePembayaranStatus($kode_status_perkuliahan, array("$key" => "$value"));
        if ($ubah){
            $res['status'] = '1';
            $res['id'] = $kode_status_perkuliahan;
            $res['status_pembayaran'] = $value;
            $res['pembayaran'] = $key;
        }else{
            $res['status'] = '0';
            $res['id'] = $kode_status_perkuliahan;
            $res['status_pembayaran'] = $value;
            $res['pembayaran'] = $key;
        }

        echo json_encode($res);
    }

    public function ganti_tahun_akademik(){
        $kode_tahun_akademik = $this->input->post('kode_tahun_akademik');
        $ta = $this->keuanganservice->getTahunAkademikByKode($kode_tahun_akademik);
        setcookie('kode_tahun_akademik',$ta->kode_tahun_akademik,time()+(60*15),'/');
        setcookie('tahun_akademik',$ta->tahun_akademik,time()+(60*15),'/');
        setcookie('semester',$ta->semester,time()+(60*15),'/');

        return redirect($_SERVER['HTTP_REFERER']);
    }

}
