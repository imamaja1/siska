<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_kurikulum extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->service('KurikulumService');
        
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
        $data = array(
            'content' => 'admin/jurusan/kurikulum/Data_kurikulum/V_index',
            'judul' => 'Jurusan',
            'sub_judul' => 'Data Kurikulum',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Data Kurikulum</li>',
            'nama_kurikulum' => $this->kurikulumservice->getNamaKurikulumLengkap()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function filter() {
        $kode_nama_kurukulum = $this->input->post('nama_kurikulum');
        redirect('admin/jurusan/kurikulum/data_kurikulum/tampil_filter/' . $kode_nama_kurukulum);
    }

    public function tampil_filter($kode_nama_kurikulum) {
        $kode_program_studi = $this->kurikulumservice->getKodeProdiFromKurikulum($kode_nama_kurikulum);
        
        $data = array(
            'content' => 'admin/jurusan/kurikulum/Data_kurikulum/V_Data_kurikulum',
            'judul' => 'Jurusan',
            'sub_judul' => 'Data Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Data Kurikulum</li>',
            'kode_nama_kurikulum' => $kode_nama_kurikulum,
            'nama_kurikulum' => $this->kurikulumservice->getNamaKurikulumById($kode_nama_kurikulum),
            'data_matakuliah' => $this->kurikulumservice->getMatakuliahByProdi($kode_program_studi),
            'data' => $this->kurikulumservice->getDataKurikulum($kode_nama_kurikulum),
        );
      
        $pilihan = $this->kurikulumservice->getKompetensiPilihan($kode_program_studi);
        if (!empty($pilihan['mk_pilihan'])) {
            $data['mk_pilihan'] = $pilihan['mk_pilihan'];
            $data['nama_pilihan'] = $pilihan['nama_pilihan'];
        }

        $this->load->view('admin/template/V_main', $data);
    }

    public function excel($kode_nama_kurikulum) {
        $nama_kurikulum = $this->kurikulumservice->getNamaKurikulumById($kode_nama_kurikulum);
        $kode_program_studi = $this->kurikulumservice->getKodeProdiFromKurikulum($kode_nama_kurikulum);

        $data = array(
            'file_name' => $nama_kurikulum->nama_kurikulum."-".$nama_kurikulum->singkatan_program_studi,
            'nama_kurikulum' => $nama_kurikulum,
            'data_matakuliah' => $this->kurikulumservice->getMatakuliahByProdi($kode_program_studi),
            'data' => $this->kurikulumservice->getDataKurikulum($kode_nama_kurikulum),
        );

        $pilihan = $this->kurikulumservice->getKompetensiPilihan($kode_program_studi);
        if (!empty($pilihan['mk_pilihan'])) {
            $data['mk_pilihan'] = $pilihan['mk_pilihan'];
            $data['nama_pilihan'] = $pilihan['nama_pilihan'];
        }
      
        $this->load->view('admin/jurusan/kurikulum/Data_kurikulum/Excel', $data);
    }

    public function simpan_data_kurikulum() {
        $kode_nama_kurikulum = $this->kurikulumservice->simpanDataKurikulumMultiple($this->input->post());
        redirect("admin/jurusan/kurikulum/data_kurikulum/tampil_filter/" . $kode_nama_kurikulum);
    }

    public function isi_data_kurikulum($kode_nama_kurikulum) {
        $kode_program_studi = $this->kurikulumservice->getKodeProdiFromKurikulum($kode_nama_kurikulum);
        
        $data['content'] = "admin/jurusan/kurikulum/Data_kurikulum/V_Isi_data_kurikulum";
        $data['judul'] = "Jurusan";
        $data['sub_judul'] = "Isi Data Kurikulum";
        $data['data_matakuliah'] = $this->kurikulumservice->getMatakuliahByProdi($kode_program_studi);

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah() {
        if ($this->kurikulumservice->simpanDataKurikulum($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data berhasil disimpan", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data gagal disimpan", "error");</script>'
            );
        }
        redirect(site_url('admin/jurusan/kurikulum/data_kurikulum'));
    }

    public function ubah() {
        if ($this->kurikulumservice->ubahDataKurikulum($this->input->post())) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data berhasil diubah", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data gagal diubah", "error");</script>'
            );
        }
        redirect(site_url('admin/jurusan/kurikulum/data_kurikulum'));
    }

    public function hapus($id, $kode_nama_kurikulum) {
        if ($this->kurikulumservice->hapusDataKurikulum($id)) {
            $this->session->set_flashdata(
                'info', '<script>swal("Success!", "Data berhasil dihapus", "success");</script>'
            );
        } else {
            $this->session->set_flashdata(
                'info', '<script>swal("Gagal!", "Data gagal dihapus", "error");</script>'
            );
        }
        redirect(site_url('admin/jurusan/kurikulum/data_kurikulum/tampil_filter/' . $kode_nama_kurikulum));
    }
}
