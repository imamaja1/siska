<?php

class Double extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->service('DoubleService');
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

    public function index($ta = null)
    {
        $kta = ($ta == null ? 22: $ta);
        $data['data'] = $this->doubleservice->getDoubleKrs($kta);
        return $this->load->view('admin/double/double', $data);
    }

    public function detail($nim, $semester)
    {
        $data['data'] = $this->doubleservice->getKrsDetailByNimSemester($nim, $semester);

        $data['krs'] =  $this->doubleservice->getKrsByNim($nim);

        return $this->load->view('admin/double/double_detail', $data);
    }

    function update_nilai($kode_khs_detail)
    {
        $input_name = $this->input->post('input_name');
        $nilai = $this->input->post('nilai');
        $fields = array(
            'nilai_harian' => 'nilai_harian',
            'nilai_uts' => 'nilai_uts',
            'nilai_uas' => 'nilai_uas',
        );

        $field = isset($fields[$input_name]) ? $fields[$input_name] : 'nilai_akhir';
        $ubah = $this->doubleservice->updateKhsDetail($kode_khs_detail, $field, empty($nilai) ? null : $nilai);

        if ($ubah)
        {
            echo 'true';
        }else{
            echo 'false';
        }
    }

    public function hapus_krs($kode_krs)
    {
        $this->doubleservice->deleteKrs($kode_krs);
        return redirect($_SERVER['HTTP_REFERER']);
    }
}