<?php

class Bimbingan_kkp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    public function index()
    {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $bimbingan = $this->dosenservice->getBimbinganKkp($kode_dosen);

        $data['content'] = 'dosen/bimbingan_kkp/V_index';
        $data['judul'] = 'Bimbingan KKP';
        $data['a_kkp'] = 'active';
        $data['data'] = $bimbingan;

        $this->load->view('dosen/template/V_main',$data);
    }

    public function get_content($id)
    {
        $cek = $this->dosenservice->getNilaiKkp($id);
        if (!empty($cek))
        {
            $this->load->view('dosen/bimbingan_kkp/V_form_update_nilai',array('id'=>$id, 'data' => $cek));
        }else{
            $this->load->view('dosen/bimbingan_kkp/V_form_add_nilai',array('id'=>$id));
        }
    }

    public function add_nilai()
    {
        $bab1 = $this->input->post('bab_1');
        $bab2 = $this->input->post('bab_2');
        $bab3 = $this->input->post('bab_3');
        $bab4 = $this->input->post('bab_4');
        $bab5 = $this->input->post('bab_5');
        $laporan = ($bab1 * 0.15)+($bab2 * 0.15)+($bab3 * 0.25) + ($bab4 * 0.3) + ($bab5 * 0.15);
        $kinerja = $this->input->post('kinerja');
        $data = $this->input->post();
        $data['laporan'] = $laporan;
        $data['nilai_akhir'] = ($laporan * 0.4) + ($kinerja * 0.6);

        $simpan = $this->dosenservice->insertNilaiKkp($data);
        if ($simpan)
        {
            $this->session->set_flashdata('info',
                '<script>swal("Behasil", "Data berhasil disimpan","success");</script>');
        }else{
            $this->session->set_flashdata('info',
                '<script>swal("Gagal", "Data gagal disimpan","error");</script>');
        }
        redirect(site_url('dosen/bimbingan_kkp'));

    }

    public function update_nilai($id)
    {
        $bab1 = $this->input->post('bab_1');
        $bab2 = $this->input->post('bab_2');
        $bab3 = $this->input->post('bab_3');
        $bab4 = $this->input->post('bab_4');
        $bab5 = $this->input->post('bab_5');
        $laporan = ($bab1 * 0.15)+($bab2 * 0.15)+($bab3 * 0.25) + ($bab4 * 0.3) + ($bab5 * 0.15);
        $kinerja = $this->input->post('kinerja');
        $data = $this->input->post();
        $data['laporan'] = $laporan;
        $data['nilai_akhir'] = ($laporan * 0.4) + ($kinerja * 0.6);

        $update = $this->dosenservice->updateNilaiKkp($id, $data);
        if ($update)
        {
            $this->session->set_flashdata('info',
                '<script>swal("Behasil", "Data berhasil diupdate","success");</script>');
        }else{
            $this->session->set_flashdata('info',
                '<script>swal("Gagal", "Data gagal diupdate","error");</script>');
        }
        redirect(site_url('dosen/bimbingan_kkp'));

    }

}