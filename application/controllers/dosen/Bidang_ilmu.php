<?php

class Bidang_ilmu extends CI_Controller {

    public function __construct() {
        parent::__construct();
//        $this->load->model(array(
//            'jurusan/bidang_ilmu',
//            'jurusan/bidang_ilmu_detail',
//        ));
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $this->load->service('DosenService');
    }

    public function index() {

        $kode_dosen = $this->session->userdata('kode_dosen');
        $homebase = $this->dosenservice->getDosenHomebase($kode_dosen);
        $data_bidang_ilmu = $this->dosenservice->getBidangIlmuByProdi($homebase['homebase']);

        $data['content'] = 'dosen/V_bidang_ilmu';
        $data['judul'] = 'Bidang Ilmu Dosen';
        $data['a_bidang_ilmu'] = 'active';
        $data['data_bidang_ilmu'] = $data_bidang_ilmu;
        $this->load->view('dosen/template/V_main', $data);
    }

    public function add_bidang_ilmu($id_bidang) {

        $kode_dosen = $this->session->userdata('kode_dosen');

        $data = array(
            'id_bidang_ilmu' => $id_bidang,
            'kode_dosen' => $kode_dosen,
        );

        $id_bidang_ilmu = $this->dosenservice->checkBidangIlmuDetail($kode_dosen, $id_bidang);

        if ($id_bidang_ilmu->num_rows() == 1) {
            $message = "false";
        } else {
            $message = "true";
            $this->dosenservice->insertBidangIlmuDetail($data);
        }

        echo json_encode($message);
    }

    public function show_bidang_ilmu_anda() {

        $kode_dosen = $this->session->userdata('kode_dosen');

        $data = $this->dosenservice->getBidangIlmuDetailDosen($kode_dosen);

        echo json_encode($data);
    }

    public function hapus_bidang_ilmu_detail($id_bidang_ilmu_detail) {
        $this->dosenservice->deleteBidangIlmuDetail($id_bidang_ilmu_detail);
    }

     public function info_bidang_ilmu_for_kaprodi() {

        $kode_dosen = $this->session->userdata('kode_dosen');

        $data['content'] = 'dosen/kaprodi/V_bidang_ilmu';
        $data['judul'] = 'Data Bidang Ilmu Dosen';
        $data['sub_judul'] = 'Untuk Kaprodi';
        $data['a_bidang_ilmu_kaprodi'] = 'active';

        $query = $this->dosenservice->getKaprodiByDosen($kode_dosen);

        $program_studi = $query->row_array();

        if ($query->num_rows() >= 1) {
            $data['data_bidang_ilmu'] = $this->dosenservice->getBidangIlmuByProdiDistinct($program_studi['kode_program_studi']);
            $data['data_bidang_ilmu2'] = $this->dosenservice->getBidangIlmuDetailByProdi($program_studi['kode_program_studi']);
            $data['dosen'] = $this->dosenservice->getDosenByHomebase($program_studi['kode_program_studi']);
            
            $data['total_dosen'] = count($data['dosen']);

            $data['dosen_sudah_ngisi'] = $this->dosenservice->getDosenSudahBidang($program_studi['kode_program_studi']);
            $data['dosen_belum_ngisi'] = $this->dosenservice->getDosenBelumBidang($program_studi['kode_program_studi']);

            $data['sudah_ngisi'] = count($data['dosen_sudah_ngisi']);
            $data['belum_ngisi'] = count($data['dosen_belum_ngisi']);
        } else {
            
        }

        $this->load->view('dosen/template/V_main', $data);
    }

    public function info_bidang_ilmu_for_dekan() {
        $kode_dosen = $this->session->userdata('kode_dosen');

        $data['content'] = 'dosen/dekan/V_bidang_ilmu';
        $data['judul'] = 'Data Bidang Ilmu Dosen';
        $data['sub_judul'] = 'Untuk Dekan';
        $data['a_bidang_ilmu_dekan'] = 'active';

        $query = $this->dosenservice->getFakultasByDekan($kode_dosen);

        if ($query->num_rows() >= 1) {

            $data['data_bidang_ilmu'] = $this->dosenservice->getBidangIlmuByProdiSub($kode_dosen);
            $data['data_bidang_ilmu2'] = $this->dosenservice->getBidangIlmuDetailByProdiSub($kode_dosen);
             $data['dosen'] = $this->dosenservice->getDosenByHomebaseSub($kode_dosen);
            
            $data['total_dosen'] = count($data['dosen']);


             $data['dosen_sudah_ngisi'] = $this->dosenservice->getDosenSudahBidangSub($kode_dosen);
            $data['dosen_belum_ngisi'] = $this->dosenservice->getDosenBelumBidangSub($kode_dosen);

                    
            $data['sudah_ngisi'] = count($data['dosen_sudah_ngisi']);
            $data['belum_ngisi'] = count($data['dosen_belum_ngisi']);
        } else {
            
        }

        $this->load->view('dosen/template/V_main', $data);
    }

   

    public function jumlah_dosen($id) {
        $query = $this->dosenservice->getJumlahDosenBidang($id);
        echo json_encode($query);
    }

}
