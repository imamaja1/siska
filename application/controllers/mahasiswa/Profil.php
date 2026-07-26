<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/Mahasiswa_model',
            'jurusan/Perwalian_model',
            'jurusan/m_dosen',
        ));
        $this->load->library('form_validation');
        $this->load->service('MahasiswaService');
        if ($this->session->userdata('status') !== 'login_mahasiswa') {
            redirect('mahasiswa/Login_mahasiswa');
        }
    }

    public function index() {
        $dosen_wali = $this->Perwalian_model->get_perwalian_by_nim($this->session->userdata('nim'));
        if (!empty($dosen_wali->kode_dosen_perwakilan))
        {
            $dosen_perwakilan = $this->m_dosen->get_dosen_by_kode($dosen_wali->kode_dosen_perwakilan);
            $data['dosen_perwakilan'] = $dosen_perwakilan->nama_dosen."&nbsp;(<i class='fa fa-phone'></i>&nbsp;".$dosen_perwakilan->no_telp.")";
        }
        $data['conten'] = "mahasiswa/V_profil";
        $data['judul'] = 'Profil Mahasiswa';
        //$data['dosen_wali'] = count($dosen_wali) > 0 ? $dosen_wali->nama_dosen."&nbsp;(<i class='fa fa-phone'></i>&nbsp;".$dosen_wali->no_telp.")" : '-';
        $data['dosen_wali'] = !empty($dosen_wali) ? $dosen_wali->nama_dosen : '-';
        $data['nama_mahasiswa'] = '<p style="font-size: 20px;">Profil <b>' . $this->session->userdata('nama_mahasiswa') . '</b></p>';
        $data['data'] = $this->Mahasiswa_model->get($this->session->userdata('nim'));

        $this->load->view('mahasiswa/template/V_main', $data);
    }

    function ubah_data_mahasiswa($nim) {
        $data['conten'] = "mahasiswa/V_mahasiswa_update";
        $data['judul'] = 'Ubah Profil Mahasiswa';

        $data['data_mahasiswa'] = $this->Mahasiswa_model->get($nim);
        $data['provinsi'] = $this->Mahasiswa_model->get_provinsi();

        $this->load->view('mahasiswa/template/V_main', $data);
    }

    public function simpan_update() {
        $nim = $this->input->post('nim');
        $this->form_validation->set_rules('nim', 'nim', 'required', array('required' => 'Field NIM Mahasiswa harus diisi'));
        $this->form_validation->set_rules('npm', 'npm', 'required', array('required' => 'Field NPM Mahasiswa harus diisi'));
        $this->form_validation->set_rules('no_pendaftaran', 'no_pendaftaran', 'required', array('required' => 'Field Nomor Pendaftaran Mahasiswa harus diisi'));
        $this->form_validation->set_rules('no_pendaftaran_ulang', 'no_pendaftaran_ulang', 'required', array('required' => 'Field Nomor Pendaftaran Ulang Mahasiswa harus diisi'));
        $this->form_validation->set_rules('nama_mahasiswa', 'nama_mahasiswa', 'required', array('required' => 'Field Nama Mahasiswa harus diisi'));
        $this->form_validation->set_rules('tempat_lahir', 'tempat_lahir', 'required', array('required' => 'Field Tempat Lahir harus diisi'));
        $this->form_validation->set_rules('tanggal_lahir', 'tanggal_lahir', 'required', array('required' => 'Field Tanggal lahir harus diisi'));
        $this->form_validation->set_rules('alamat_lengkap', 'alamat_lengkap', 'required', array('required' => 'Field Alamat Lengkap harus diisi'));
        $this->form_validation->set_rules('kota', 'kota', 'required', array('required' => 'Field Kota harus diisi'));
        $this->form_validation->set_rules('propinsi', 'propinsi', 'required', array('required' => 'Field Propinsi harus dipilih'));
        $this->form_validation->set_rules('telepon', 'telepon', 'required', array('required' => 'Field No. Telepon harus diisi'));
        $this->form_validation->set_rules('jenis_kelamin', 'jenis_kelamin', 'required', array('required' => 'Field Jenis Kelamin harus dipilih'));
        $this->form_validation->set_rules('agama', 'agama', 'required', array('required' => 'Field Agama harus dipilih'));
        $this->form_validation->set_rules('golongan_darah', 'golongan_darah', 'required', array('required' => 'Field Golongan Darah harus dipilih'));
        $this->form_validation->set_rules('kewarganegaraan', 'kewarganegaraan', 'required', array('required' => 'Field Kewarganegaraan harus dipilih'));

        $this->form_validation->set_rules('nama_ayah', 'nama_ayah', 'required', array('required' => 'Field Nama Ayah Kelamin harus diisi'));
        $this->form_validation->set_rules('pekerjaan_ayah', 'pekerjaan_ayah', 'required', array('required' => 'Field Pekerjaan Ayah harus dipilih'));
        $this->form_validation->set_rules('nama_ibu', 'nama_ibu', 'required', array('required' => 'Field Nama Ibu harus diisi'));
        $this->form_validation->set_rules('agama_ayah', 'agama_ayah', 'required', array('required' => 'Field Agama Ayah harus dipilih'));
        $this->form_validation->set_rules('agama_ibu', 'agama_ibu', 'required', array('required' => 'Field Agama Ibu harus dipilih'));
        $this->form_validation->set_rules('pekerjaan_ibu', 'pekerjaan_ibu', 'required', array('required' => 'Field Pekerjaan Ibu harus dipilih'));

        $this->form_validation->set_rules('alamat_orangtua', 'alamat_orangtua', 'required', array('required' => 'Field Alamat Orang Tua harus diisi'));
        $this->form_validation->set_rules('kota_orangtua', 'kota_orangtua', 'required', array('required' => 'Field Kota Orang Tua harus diisi'));
        $this->form_validation->set_rules('propinsi_orangtua', 'propinsi_orangtua', 'required', array('required' => 'Field Propinsi Orang Tua harus dipilih'));
        $this->form_validation->set_rules('nik', 'nik', 'required|min_length[16]|numeric|max_length[16]', array('numeric'=> 'Field NIK harus angka','required' => 'Field NIK harus dipilih','min_length'=> 'Field NIK haurs 16 digit','max_length'=> 'Field NIK haurs 16 digit' ));
        $this->form_validation->set_rules('email', 'email', 'required', array('required' => 'Field Email Harus di isi'));

        if ($this->form_validation->run() == false) {
            $data['conten'] = "mahasiswa/V_mahasiswa_update";
            $data['judul'] = 'Ubah Profil Mahasiswa';

            $data['data_mahasiswa'] = $this->Mahasiswa_model->get($nim);
            $data['provinsi'] = $this->Mahasiswa_model->get_provinsi();

            $this->load->view('mahasiswa/template/V_main', $data);
        } else {

            $data = array(
                'npm' => $this->input->post('npm'),
                'nomor_pendaftaran' => $this->input->post('no_pendaftaran'),
                'nomor_pendaftaran_ulang' => $this->input->post('no_pendaftaran_ulang'),
                'nama_mahasiswa' => $this->input->post('nama_mahasiswa'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat_lengkap'),
                'kota' => $this->input->post('kota'),
                'propinsi' => $this->input->post('propinsi'),
                'telepon' => $this->input->post('telepon'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'agama' => $this->input->post('agama'),
                'golongan_darah' => $this->input->post('golongan_darah'),
                'kewarganegaraan' => $this->input->post('kewarganegaraan'),
                'nama_instansi' => $this->input->post('nama_instansi'),
                'email' => $this->input->post('email'),
                'nik' => $this->input->post('nik'),
                'nama_ayah' => $this->input->post('nama_ayah'),
                'agama_ayah' => $this->input->post('agama_ayah'),
                'pekerjaan_ayah' => $this->input->post('pekerjaan_ayah'),
                'nama_ibu' => $this->input->post('nama_ibu'),
                'agama_ibu' => $this->input->post('agama_ibu'),
                'pekerjaan_ibu' => $this->input->post('pekerjaan_ibu'),
                'alamat_orangtua' => $this->input->post('alamat_orangtua'),
                'kota_orangtua' => $this->input->post('kota_orangtua'),
                'propinsi_orangtua' => $this->input->post('propinsi_orangtua'),
                'telepon_orangtua' => $this->input->post('telepon_orangtua'),
            );

            if ($this->Mahasiswa_model->update($nim, $data)) {

                $this->session->set_flashdata(
                        'info', '<script>swal("Sukses!","Ubah data mahasiswa berhasil.","success")</script>');
                redirect('mahasiswa/profil');
            }
        }
    }

    public function upload_foto() {
        $nim = $this->session->userdata('nim');
        $result = $this->mahasiswaservice->uploadImage($nim, $nim, './assets/foto/');

        if ($result['status']) {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Sukses!","Upload foto berhasil.","success")</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<script>swal("Gagal!","' . $result['msg'] . '","error")</script>'
            );
        }
        redirect('mahasiswa/profil');
    }

}
