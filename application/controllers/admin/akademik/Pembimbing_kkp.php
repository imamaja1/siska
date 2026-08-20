<?php

class Pembimbing_kkp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_dosen',
        ));

        $this->load->service('PembimbingKkpService');

        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }
        $class = $this->router->fetch_class();
        $id_user = $this->session->userdata('id');
        if (!rbac_cek($class, $id_user)) {
            redirect(site_url('denied'));
        }
    }

    public function index()
    {
        $bimbingan = $this->pembimbingkkpservice->getBimbinganList();

        $data['content'] = 'admin/akademik/pembimbing_kkp/V_index';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Pembimbing KKP';
        $data['judul_sub_judul'] = '';
        $data['data'] = $bimbingan;

        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah()
    {
        $tahun_akademik = tahun_akademik();
        $sub = $this->pembimbingkkpservice->getExistingPembimbing();
        if (count($sub) > 0)
        {
            foreach ($sub as $row)
            {
                $exis[] = $row->nim;
            }
        }else{
            $exis = ['1'];
        }
//        $kode_mak_kkp = array('DSPB470401','MDBB340015','TDBB340127','TDBB350020','TSBB360039','TSBB370068');
        $kode_mak_kkp = get_kode_matakuliah_kkp();
        $kkp = $this->pembimbingkkpservice->getMahasiswaKkp($kode_mak_kkp, $exis, $tahun_akademik->kode_tahun_akademik);
//        $dosen = $this->db->select('kode_dosen,nama_dosen')->from('dosen')->get()->result();

        $data['data'] = $kkp;
        $data['dosen'] = $this->m_dosen->get();
        $this->load->view('admin/akademik/pembimbing_kkp/Modal_tambah_bimbingan', $data);
    }

    public function add()
    {
        $data = array(
            'nim' => $this->input->post('nim'),
            'kode_dosen' => $this->input->post('kode_dosen'),
            'lokasi_kkp' => $this->input->post('lokasi_kkp'),
            'bidang_kkp' => $this->input->post('bidang_kkp'),
            'tgl_pelaksanaan' => $this->input->post('tgl_pelaksanaan'),
            'batas_pelaksanaan' => $this->input->post('batas_pelaksanaan'),
            'batas_laporan' => $this->input->post('batas_laporan'),
            'kode_tahun_akademik' => tahun_akademik()->kode_tahun_akademik,
        );
        $simpan = $this->pembimbingkkpservice->simpanPembimbing($data);
        if ($simpan)
        {
            $this->session->set_flashdata('info',
                '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                Data berhasil disimpan.
              </div>');
        }else{
            $this->session->set_flashdata('info',
                '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Gagal!</h4>
                Data gagal disimpan.
              </div>');
        }

        redirect(site_url('admin/akademik/pembimbing_kkp'));
    }

    public function view($kode_dosen)
    {
        $dosen = $this->m_dosen->get_nama($kode_dosen);
        $bimbingan = $this->pembimbingkkpservice->getBimbinganByDosen($kode_dosen);
        $data['content'] = 'admin/akademik/pembimbing_kkp/V_view';
        $data['judul'] = 'Akademik';
        $data['sub_judul'] = 'Pembimbing KKP';
        $data['judul_sub_judul'] = '';
        $data['data'] = $bimbingan;
        $data['dosen'] = $dosen;
        $data['kode_dosen'] = $kode_dosen;
        $data['all_dosen'] = $this->m_dosen->get();

        $this->load->view('admin/template/V_main', $data);
    }

    public function edit($id, $kode_dosen)
    {
        $data['data'] = $this->pembimbingkkpservice->getPembimbingById($id);
        $data['id'] = $id;
        $data['kode_dosen'] = $kode_dosen;
        $this->load->view('admin/akademik/pembimbing_kkp/Modal_edit_bimbingan', $data);
    }

    public function update($id,$kode_dosen)
    {
        $data = array(
            'lokasi_kkp' => $this->input->post('lokasi_kkp'),
            'bidang_kkp' => $this->input->post('bidang_kkp'),
            'tgl_pelaksanaan' => $this->input->post('tgl_pelaksanaan'),
            'batas_pelaksanaan' => $this->input->post('batas_pelaksanaan'),
            'batas_laporan' => $this->input->post('batas_laporan'),
        );
        $ubah = $this->pembimbingkkpservice->updatePembimbing($id, $data);
        if ($ubah)
        {
            $this->session->set_flashdata('info',
                '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                Data berhasil di Ubah.
              </div>');
        }else{
            $this->session->set_flashdata('info',
                '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Gagal!</h4>
                Data gagal di Ubah.
              </div>');
        }

        redirect(site_url('admin/akademik/pembimbing_kkp/view/'.$kode_dosen));
    }

    public function hapus($id)
    {
        $delete = $this->pembimbingkkpservice->hapusPembimbing($id);
        if ($delete)
        {
            $this->session->set_flashdata('info',
                '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                Data berhasil dihapus.
              </div>');
        }else{
            $this->session->set_flashdata('info',
                '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Gagal!</h4>
                Data gagal dihapus.
              </div>');
        }

        redirect(site_url('admin/akademik/pembimbing_kkp'));
    }

    public function pindah()
    {
        $id = $this->input->post('id');
        $update = $this->pembimbingkkpservice->pindahPembimbing($id, $this->input->post('kode_dosen'));
        if ($update)
        {
            $this->session->set_flashdata('info',
                '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                Mahasiswa berhasil di pindah.
              </div>');
        }else{
            $this->session->set_flashdata('info',
                '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Gagal!</h4>
                Mahasiswa gagal di pindah.
              </div>');
        }

        redirect(site_url('admin/akademik/pembimbing_kkp'));
    }

    public function cari()
    {
        $keyword = $this->input->post('keyword');
        $bimbingan = $this->pembimbingkkpservice->cariBimbingan($keyword);
        $data['data'] =  $bimbingan;

        $this->load->view('admin/akademik/pembimbing_kkp/V_hasil_cari', $data);
    }

    public function data($id_pembimbing_kkp)
    {
        $data = $this->pembimbingkkpservice->getDataPenilaian($id_pembimbing_kkp);
        return $data;
    }

    public function penilaian_pembimbing($id_pembimbing_kkp)
    {
        $data['data'] = $this->data($id_pembimbing_kkp);
        $data['file_name'] = 'Nilai Pembimbing KKP';
        $this->load->view('admin/akademik/pembimbing_kkp/V_cetak_nilai_pembimbing', $data);
    }

    public function nilai_gabungan($id_pembimbing_kkp)
    {
        $data['data'] = $this->data($id_pembimbing_kkp);
        $data['file_name'] = 'Nilai Gabungan KKP';
        $this->load->view('admin/akademik/pembimbing_kkp/V_cetak_nilai_gabungan', $data);
    }

    public function rekap_kkp()
    {
        $tahun_akademik = tahun_akademik();
        $data['tahun_akademik'] = $tahun_akademik;
        $data['data'] = $this->pembimbingkkpservice->getRekapKkp($tahun_akademik->kode_tahun_akademik);

        $data['file_name'] = 'Rekap KKP';
        $this->load->view('admin/akademik/pembimbing_kkp/V_cetak_rekap_kkp', $data);
    }
}