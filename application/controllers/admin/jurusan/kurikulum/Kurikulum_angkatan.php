<?php

class Kurikulum_angkatan extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->service('KurikulumService');
        
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
        $data = array(
            'content' => 'admin/jurusan/kurikulum/kurikulum_angkatan/V_index',
            'judul' => 'Jurusan',
            'sub_judul' => 'Kurikulum Angkatan',
            'judul_sub_judul' => 'Kurikulum',
            'title_h1' => '<i class="fa fa-map"></i> <li>Jurusan</li>',
            'title_h2' => '<li>Kurikulum</li>',
            'title_h3' => '<li>Kurikulum Angkatan</li>',
            'nama_kurikulum' => $this->kurikulumservice->getNamaKurikulumLengkap()
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function all()
    {
        $data['data'] = $this->kurikulumservice->getKurikulumAngkatanLengkap();
        $this->load->view('admin/jurusan/kurikulum/kurikulum_angkatan/V_all', $data);
    }

    public function add()
    {
        $res = $this->kurikulumservice->simpanKurikulumAngkatan($this->input->post());
        echo json_encode($res);
    }

    public function edit($id)
    {
        $data['nama_kurikulum'] = $this->kurikulumservice->getNamaKurikulumLengkap();
        $data['data'] = $this->kurikulumservice->getKurikulumAngkatanById($id);
        $data['id'] = $id;
        $this->load->view('admin/jurusan/kurikulum/kurikulum_angkatan/Modal_edit', $data);
    }

    public function update($id)
    {
        $res = $this->kurikulumservice->ubahKurikulumAngkatan($id, $this->input->post());
        echo json_encode($res);
    }

    public function hapus($id)
    {
        $res = $this->kurikulumservice->hapusKurikulumAngkatan($id);
        echo json_encode($res);
    }
}