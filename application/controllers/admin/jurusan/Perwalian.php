<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Perwalian extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model(array(
            'jurusan/m_dosen',
            'jurusan/program_studi/Nama_jurusan_model',
            'jurusan/m_tahun_akademik',
            'jurusan/Perwalian_model',
            'akademik/Mahasiswa_model',
        ));

        $this->load->service('PerwalianService');

        $this->add_perwalian();
        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }else{
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    public function coba() {
        $homebase = $this->Perwalian_model->get_homebase();
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
        $ta = substr($tahun_akademik->ta, 2, 2);
        $cek_perwalian_baru = $this->Perwalian_model->cek_mahasiswa_baru($ta);
        $i = 0;
        foreach ($homebase as $row) {
            $data['jumlah_dosen'][$i] = $row->jumlah_dosen;
            $data['homebase'][$i] = $row->homebase;
//            $data_dosen = $this->Perwalian_model->get_dosen_by_homebase($row->homebase);
//            $data['data_mahasiswa'][$i] = $this->Perwalian_model->get_mahasiswa_by_homebase($row->homebase, $ta, $limit, $offset);
            $cek_mahasiswa_baru = $this->Mahasiswa_model->cek_mahasiswa_baru($row->homebase, $ta);
            $data['jumlah_mahasiswa'][$i] = count($cek_mahasiswa_baru);
            $i++;
        }
        echo '<pre>';
        print_r($data);
    }

    public function add_perwalian() {
        $homebase = $this->Perwalian_model->get_homebase();
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
        $ta = substr($tahun_akademik->ta, 2, 2);
        $cek_perwalian_baru = $this->Perwalian_model->cek_mahasiswa_baru($ta);
        $i = 0;
        foreach ($homebase as $row) {
            $jumlah_dosen = $row->jumlah_dosen;
            $data_dosen = $this->Perwalian_model->get_dosen_by_homebase($row->homebase);
//            $data['data_mahasiswa'][$i] = $this->Perwalian_model->get_mahasiswa_by_homebase($row->homebase, $ta, $limit, $offset);
            $cek_mahasiswa_baru = $this->Mahasiswa_model->cek_mahasiswa_baru($row->homebase, $ta);
            $jumlah_mahasiswa = count($cek_mahasiswa_baru);
//            print_r($cek_mahasiswa_baru);
//            die();
            $i++;
//            Percobaa
            if ((count($cek_mahasiswa_baru) > 0) && ($tahun_akademik->semester == 1) && (count($cek_perwalian_baru) == 0)) {
                if ($jumlah_mahasiswa >= $jumlah_dosen) {
                    $sisa_bagi = $jumlah_mahasiswa % $jumlah_dosen;
                    $dibagi = $jumlah_mahasiswa - $sisa_bagi;
                    $jatah = $dibagi / $jumlah_dosen;
                    $offset = 0;
                    $limit = $jatah;
                    $j = 0;
                    foreach ($data_dosen as $row) {
                        $kode_dosen = $row->kode_dosen;
                        $data_mahasiswa_jatah = $this->Perwalian_model->get_mahasiswa_by_homebase($row->homebase, $ta, $limit, $offset);
                        $i = 0;
                        foreach ($data_mahasiswa_jatah as $item) {
                            $data_perwalian = array(
                                'kode_dosen' => $kode_dosen,
                                'nim' => $item->nim,
                                'kode_tahun_akademik' => $kode_tahun_akademik,
                            );
                            $this->Perwalian_model->simpan($data_perwalian);
                            $i++;
                        }
                        $offset = $offset + $limit;
                        $j++;
                    }
                    $offset2 = $offset;
                    $limit2 = 1;
                    $i2 = 0;
                    foreach ($data_dosen as $row) {
                        $kode_dosen = $row->kode_dosen;
                        $data_mahasiswa_jatah = $this->Perwalian_model->get_mahasiswa_by_homebase($row->homebase, $ta, $limit2, $offset2);
                        $i = 0;
                        foreach ($data_mahasiswa_jatah as $item) {
                            $data_perwalian = array(
                                'kode_dosen' => $kode_dosen,
                                'nim' => $item->nim,
                                'kode_tahun_akademik' => $kode_tahun_akademik,
                            );
                            $this->Perwalian_model->simpan($data_perwalian);
                            $i++;
                        }
                        $offset2 = $offset2 + $limit2;
                        $j++;
                    }
                } else {
                    $limit = 1;
                    $offset = 0;
                    $i = 0;
                    $j = 0;
                    foreach ($data_dosen as $row) {
                        $kode_dosen = $row->kode_dosen;
                        $data_mahasiswa_jatah = $this->Perwalian_model->get_mahasiswa_by_homebase($row->homebase, $ta, $limit, $offset);
                        $i = 0;
                        foreach ($data_mahasiswa_jatah as $item) {
                            $data_perwalian = array(
                                'kode_dosen' => $kode_dosen,
                                'nim' => $item->nim,
                                'kode_tahun_akademik' => $kode_tahun_akademik,
                            );
                            $this->Perwalian_model->simpan($data_perwalian);
                            $i++;
                        }
                        $offset = $offset + $limit;
                        $j++;
                    }
                }
            }
        }
    }

    public function index() {
        $data = array(
            'content' => 'admin/jurusan/perwalian/V_Perwalian',
            'judul' => 'Jurusan',
            'sub_judul' => 'Perwalian',
            'judul_sub_judul' => '',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Perwalian</li>',
            'title_h3' => '',
            'dosen' => $this->m_dosen->get(),
            'nama_jurusan' => $this->Nama_jurusan_model->get(),
            'nama_dosen' => $this->m_dosen->get(),
            'tahun_angkatan' => $this->m_tahun_akademik->tahun_angkatan(),
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $kode_program_studi = $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('tahun_angkatan');
        $kode_dosen = $this->input->post('kode_dosen');
//        $prodi = $this->Nama_jurusan_model->get_kode_jurusan_and_jenjang($kode_program_studi);
//        $kode_jurusan = $prodi->kode_jurusan;
//        $kode_jenjang = $prodi->kode_jenjang;

        $filter_session = array(
            'perwalian_kode_dosen' => $kode_dosen,
            'perwalian_angkatan' => $angkatan,
            'perwalian_kode_program_studi' => $kode_program_studi,
//            'perwalian_kode_jurusan' => $kode_jurusan,
//            'perwalian_kode_jenjang' => $kode_jenjang,
        );

        $this->session->set_userdata($filter_session);
        redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
    }

    public function filter_perubahan_dosen_wali() {
        $kode_program_studi = $this->input->post('kode_program_studi');
        $angkatan = $this->input->post('tahun_angkatan');
    }

    public function get_perwalian() {
        $angkatan = $this->session->userdata('perwalian_angkatan');
        $kode_dosen = $this->session->userdata('perwalian_kode_dosen');
        $kode_program_studi = $this->session->userdata('perwalian_kode_program_studi');
//        $kode_jurusan = $this->session->userdata('perwalian_kode_jurusan');
//        $kode_jenjang = $this->session->userdata('perwalian_kode_jenjang');

//        $data['content'] = 'admin/jurusan/perwalian/V_data_perwalian';
//        $data['judul'] = 'Perwalian';
//        $data['sub_judul'] = 'Data Perwalian Mahasiswa';
//        $data['dosen_pengganti'] = $this->m_dosen->get_dosen_pengganti($kode_dosen);
//        $data['nama_dosen'] = $this->m_dosen->get_nama($kode_dosen);
//        $data['data'] = $this->Perwalian_model->filter($angkatan, $kode_dosen, $kode_jurusan, $kode_jenjang);


        $data_dosen_perwalian = $this->perwalianservice->getDosenPerwalian();
        $data = array(
//            'content' => 'admin/jurusan/perwalian/V_data_perwalian',
//            'judul' => 'Jurusan',
            'sub_judul' => 'Data Perwalian Mahasiswa',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Perwalian</li>',
            'title_h3' => '<li>Data Perwalian Mahasiswa</li>',
            'dosen_pengganti' => $this->m_dosen->get_dosen_pengganti($kode_dosen),
            'nama_dosen' => $this->m_dosen->get_nama($kode_dosen),
            'dosen_perwalian' => $data_dosen_perwalian,
            'data' => $this->Perwalian_model->filter($angkatan, $kode_dosen, $kode_program_studi),
        );


        $this->load->view('admin/jurusan/perwalian/V_data_perwalian', $data);
    }

    public function simpan_dosen_pengganti() {
        $param = $this->input->post('param');
        $kode_dosen_perwakilan = $this->input->post('kode_dosen');

        $data = array(
            'kode_dosen' => $kode_dosen_perwakilan,
        );


        if ($this->Perwalian_model->ubah($data, $param)) {
            $this->session->set_flashdata('info', '<script>swal("Berhasil", "Data Berhasil di Ubah", "success");</script>'
            );

            redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
        } else {
            $this->session->set_flashdata('info', '<script>swal("gagal", "Data gagal di ubah", "error");</script>'
            );

            redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
        }
    }

    public function simpan_ubah_perwalian($param, $jenis) {
        $kode_dosen = $this->input->post('kode_dosen');
        $kode_dosen_perwakilan = $this->input->post('kode_dosen_perwakilan');

        if ((isset($kode_dosen)) && (isset($kode_dosen_perwakilan))) {
            $data = array(
                'kode_dosen' => $kode_dosen,
                'kode_dosen_perwakilan' => $kode_dosen_perwakilan,
            );
        } elseif (isset($kode_dosen)) {
            $data = array(
                'kode_dosen' => $kode_dosen,
            );
        } else {
            $data = array(
                'kode_dosen_perwakilan' => $kode_dosen_perwakilan,
            );
        }

        $this->Perwalian_model->ubah($data, $param);
//        if ($this->Perwalian_model->ubah($data, $param)) {
//            $this->session->set_flashdata('info', '<script>swal("Berhasil", "Data Berhasil di Ubah", "success");</script>'
//            );
//
//            redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
//        } else {
//            $this->session->set_flashdata('info', '<script>swal("gagal", "Data gagal di ubah", "error");</script>'
//            );
        if ($jenis == 'filter'){
            redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
        }else{
            $datas = $this->perwalianservice->getPerwalianDetail($param);
            $data['kode_perwalian'] = $datas->kode_perwalian;
            $data['nim'] = $datas->nim;
            $data['nama_mahasiswa'] = $datas->nama_mahasiswa;
            $data['nama_dosen'] = $datas->nama_dosen;
            $data['nama_dosen_perwakilan'] = $this->m_dosen->get_nama($datas->kode_dosen_perwakilan);

            echo json_encode($data);
        }
//        }
    }

    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $result = $this->Perwalian_model->autocomplate($keyword);
//        echo json_encode($result);
        if ($keyword !== "") {
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
    }

    public function autocomplatedosen() {
        $keyword = $this->input->post('keyword');
//        $result = $this->Perwalian_model->autocomplate($keyword);
        $result = $this->perwalianservice->searchDosen($keyword);
//        echo json_encode($result);
        if ($keyword !== "") {
            if (!empty($result)) {
                echo '<ul id="nim-list" class="list-group">';
                foreach ($result as $row) {
                    $nama = "'$row->nama_dosen'";

                    echo '<li onClick="selectDosen(' . $row->kode_dosen . ','.$nama.')" class="list-group-item">' . $row->nama_dosen . '</li>';
                }
                echo '</ul>';
            } else {
                echo "Data tidak ditemukan";
            }
        }
    }

    public function edit_dosen_wali_perdosen($kode_dosen){
        $data_perwalian = $this->perwalianservice->getPerwalianByDosen($kode_dosen);
        $data_dosen_perwalian = $this->perwalianservice->getDosenPerwalian();

        $data = array(
//            'content' => 'admin/jurusan/perwalian/V_data_perwalian',
//            'judul' => 'Jurusan',
                'sub_judul' => 'Data Perwalian Mahasiswa',
                'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
                'title_h2' => '<li>Perwalian</li>',
                'title_h3' => '<li>Data Perwalian Mahasiswa</li>',
                'dosen_pengganti' => $this->m_dosen->get_dosen_pengganti($kode_dosen),
                'nama_dosen' => $this->m_dosen->get_nama($kode_dosen),
                'dosen_perwalian' => $data_dosen_perwalian,
                'data' => $data_perwalian,
        );
        $this->load->view('admin/jurusan/perwalian/V_perwalian_perdosen', $data);
    }

    public function edit_dosen_wali() {
        $nim = $this->input->post('nim');
        $datas = $this->Perwalian_model->get_perwalian_by_nim($nim);
        $data['kode_perwalian'] = $datas->kode_perwalian;
        $data['nim'] = $datas->nim;
        $data['nama_mahasiswa'] = $datas->nama_mahasiswa;
        $data['nama_dosen'] = $datas->nama_dosen;
        $data['nama_dosen_perwakilan'] = $this->m_dosen->get_nama($datas->kode_dosen_perwakilan);

        $this->load->view('admin/jurusan/perwalian/V_edit_dosen_wali', $data);
//        echo json_encode($data);
    }

    public function hapus_perwakilan($kode_perwalian){
        $hapus = $this->perwalianservice->hapusPerwakilan($kode_perwalian);
        if ($hapus){
            $res['status'] = true;
        }else{
            $res['status'] = false;
        }

        echo json_encode($res);
    }

    public function mahasiswa_tidak_punya_dosen_wali($homebase) {
        $tahun_akademik = $this->m_tahun_akademik->get_semester();
        $tahun_angkatan = substr($tahun_akademik->ta, 2, 2);
        $dosen = $this->m_dosen->get_dosen_and_homebase();
        $mahasiswa = $this->Perwalian_model->get_mahasiswa_belum_ada_dosen_wali($tahun_angkatan, $homebase);
        if ($mahasiswa == false) {
            echo '<div class="callout callout-info">
                    <h4>Info!</h4>
                    Tidak ada data mahasiswa ditemukan
                  </div>';
        } else {
            $table = '<table class="table demo-table">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th width="3%" id="th">Cek</th>';
            $table .= '<th id="th">NIM</th>';
            $table .= '<th id="th">Nama Mahasiswa</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            $table .= '<tr>';
            foreach ($mahasiswa as $row) :
                $table .= '<td style="text-align: center;">';
                $table .= '<input type="checkbox" name="nim[]" value="' . $row->nim . '">';
                $table .= '</td>';
                $table .= '<td align="center">' . $row->nim . '</td>';
                $table .= '<td >' . $row->nama_mahasiswa . '</td>';
                $table .= '</tr>';
            endforeach;
            $table .= '</tbody>';
            $table .= '</table>';
            $table .= '<div  class="form-group">';
            $table .= '<div  class="col-sm-6"  style="padding: 0;">';
            $table .= '<select required name="kode_dosen" class="form-control select2">';
            $table .= '<option value="" selected disabled>Dosen Wali</option>';
            foreach ($dosen as $row) :
                $table .= '<option value="' . $row->kode_dosen . '">' . $row->nama_dosen . ' (' . $row->singkatan_program_studi . ')</option>';
            endforeach;
            $table .= '</select>';
            $table .= '</div>';
            $table .= '</div>';

            echo $table;
        }
    }

    public function simpan_perwalian() {
        $kode_dosen = $this->input->post('kode_dosen');
        $nim_mahasiswa = $this->input->post('nim');
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        if (isset($nim_mahasiswa)) {
            foreach ($nim_mahasiswa as $row => $value) {
                $data_perwalian = array(
                    'kode_dosen' => $kode_dosen,
                    'nim' => $value,
                    'kode_tahun_akademik' => $kode_tahun_akademik,
                );
                $this->Perwalian_model->simpan($data_perwalian);
            }
            $this->session->set_flashdata('info', '<script>swal("Suceess","Data berhasil di simpan","success");</script>');

            redirect(site_url('admin/jurusan/perwalian'));
        } else {
            $this->session->set_flashdata('info', '<script>swal("","Tidak bisa melakukan penyimpanan karena tidak ada data mahsiswa terpilih","error");</script>');
            redirect(site_url('admin/jurusan/perwalian'));
        }
    }

    public function quick_view()
    {
        $data['judul'] = 'Jurusan';
        $data['sub_judul'] = 'Quick View';
        $data['content'] = 'admin/jurusan/perwalian/V_quick_view';
        $data['title_h1'] = '<i class="fa fa-map"></i> <li>Jurusan</li>';
        $data['title_h2'] = '<li>Perwalian</li>';
        $data['data'] = $this->Perwalian_model->dosen_wali();

        $this->load->view('admin/template/V_main', $data);
    }

    public function pindah_perwalian()
    {
        $kode_perwalian = $this->input->post('kode_perwalian');
        $kode_dosen = $this->input->post('kode_dosen');
        $jenis_ubah = $this->input->post('jenis_ubah');
        if (isset($kode_perwalian))
        {
            if ($jenis_ubah == 1)
            {
                foreach ($kode_perwalian as $key => $val)
                {
                    $this->perwalianservice->pindahPerwalianDosen($val, $kode_dosen);
                }
                $this->session->set_flashdata('info', '<script>swal("Suceess","Data berhasil di ubah","success");</script>');

                redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
            }else {
                foreach ($kode_perwalian as $key => $val)
                {
                    $this->perwalianservice->pindahPerwalianPerwakilan($val, $kode_dosen);
                }

                $this->session->set_flashdata('info', '<script>swal("Suceess","Data berhasil di ubah","success");</script>');

                redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
            }
        }else{
            $this->session->set_flashdata('info', '<script>swal("Gagal","Silahkan pilih data mahasiswa","error");</script>');

            redirect(site_url('admin/jurusan/perwalian/get_perwalian'));
        }

    }

    public function edit_perwalian($kode_perwalian, $filter){
        $data['kode_perwalian'] = $kode_perwalian;
        $data['filter'] = $filter;
        $data['dosen'] = $this->m_dosen->get();
        $data['data'] = $this->perwalianservice->getPerwalianById($kode_perwalian);

        $this->load->view('admin/jurusan/perwalian/Modal_edit', $data);
    }

    public function rekap_dosen_wali()
    {
        $data['filename'] = 'Rekap Prewalian Mahasiswa';
        $data['data'] = $this->Perwalian_model->rekap_dosen_wali();

        $this->load->view('admin/jurusan/perwalian/Excel', $data);
    }

}
