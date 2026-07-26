<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mengajar extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
           'kuisioner/kelas_model',
           'kuisioner/mengajar_model',
           'jurusan/m_tahun_akademik',
           'jurusan/m_dosen',
        ));
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

    public function index()
    {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data['judul'] = 'Kuisioner';
        $data['sub_judul'] = '| Dosen Pengajar';
        $data['content'] = 'admin/kelas/V_mengajar';
        $data['matakuliah'] = $this->kelas_model->get_matakuliah($kode_tahun_akademik);

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter()
    {
        $id_matakuliah = $this->input->post('id_matakuliah');
        $kelas_id = $this->input->post('kelas_id');

        $data_sess = array(
            'id_matakuliah_sess' => $id_matakuliah,
            'kelas_id_sess' => $kelas_id,
        );
        $this->session->set_userdata($data_sess);
    }

    public function data_pengajar()
    {
        $kelas_id = $this->session->userdata('kelas_id_sess');
        $data['data'] = $this->mengajar_model->get_pengajar($kelas_id);

        $this->load->view('admin/kelas/V_pengajar', $data);
    }

    public function get_kelas($id_matakuliah)
    {
        $kode_tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $datas = $this->kelas_model->get_kelas_by_kode_makul($id_matakuliah, $kode_tahun_akademik);
        foreach ($datas as $data)
        {
            echo "<option value='".$data->kelas_id."' >".$data->nama_kelas."</option><br>";
        }
    }

    public function autocomplate() {
        $keyword = $this->input->post('keyword');
        $result = $this->m_dosen->autocomplate($keyword);
        if (!empty($result)) {
            echo '<ul id="nim-list" class="list-group">';
            foreach ($result as $row) {
                $nama_dosen = str_ireplace("'",'`',$row->nama_dosen);
                echo '<li onClick="selectNim(' . $row->kode_dosen . ',\''.$nama_dosen.'\')" class="list-group-item">' . $row->nama_dosen . '</li>';
            }
            echo '</ul>';
        } else {
            echo "Data tidak ditemukan";
        }
    }

    public function autocomplate_dinamic() {
        $keyword = $this->input->post('keyword');
        $result = $this->m_dosen->autocomplate($keyword);
        if (!empty($result)) {
            echo '<ul id="nim-list" class="list-group">';
            foreach ($result as $row) {
                $nama_dosen = str_ireplace("'",'`',$row->nama_dosen);
                echo '<li onClick="pilihNim(' . $row->kode_dosen . ',\''.$nama_dosen.'\')" class="list-group-item">' . $row->nama_dosen . '</li>';
            }
            echo '</ul>';
        } else {
            echo "Data tidak ditemukan";
        }
    }

    public function simpan_mengajar()
    {
        $kelas_id = $this->input->post('kelas_id');
        $kode_dosen = $this->input->post('kode_dosen');

        foreach ($kode_dosen as $key => $value)
        {
            $data_mengajar = array(
                'kode_dosen' => $value,
                'kelas_id' => $kelas_id
            );

            $this->mengajar_model->simpan($data_mengajar);
        }

        $this->session->set_flashdata('info',
            '<script>swal("Success", "Data berhasil disimpan", "success");</script>');
        redirect(site_url('admin/kuisioner/mengajar'));
    }

    public function hapus($id)
    {
        $hapus = $this->mengajar_model->hapus($id);

        if ($hapus)
        {
            $this->session->set_flashdata('info',
                '<script>swal("Success", "Data berhasil dihapus", "success");</script>');
            redirect(site_url('admin/kuisioner/mengajar'));
        }else{
            $this->session->set_flashdata('info',
                '<script>swal("Gagal", "Data gagal dihapus", "error");</script>');
            redirect(site_url('admin/kuisioner/mengajar'));
        }

    }
}