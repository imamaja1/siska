<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Botdosen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('DosenService');
    }

    public function index() {
        if (!$this->session->userdata('alamat_email')) {
            redirect('login/dosen');
        }
        $kode_dosen = $this->session->userdata('kode_dosen');
        $data = array(
            'content' => 'dosen/V_bot_dosen',
            'judul' => 'Notifikasi Telegram',
            'a_botdosen' => 'active',
            'title_h1' => '<li>Konsultasi Perwalian</li>',
            'kode_dosen' => $kode_dosen,
            'nama_dosen' => $this->session->userdata('nama_dosen'),
            'chat_id' => $this->dosenservice->getChatIdDosenByKode($kode_dosen)
        );

        $this->load->view('dosen/template/V_main', $data);
    }

    public function getchatid() {
        $alamat_email = $this->session->userdata('alamat_email');
        $sumber = file_get_contents('https://api.telegram.org/bot5764172748:AAH2CxbnErR25yJebFdGaxFzrpIaiKh2OgA/getUpdates');
        $konten = json_decode($sumber, true);
        if (!is_array($konten) || !isset($konten['result'])) {
            echo json_encode(['status' => false, 'msg' => 'Gagal mengambil data Telegram']);
            return;
        }
        for ($a = 0; $a < count($konten['result']); $a++) {

            $chat_id = $konten['result'][$a]['message']['chat']['id'];
            $pesan = $konten['result'][$a]['message']['text'];
            if ($pesan == $alamat_email) {
                $cid = $chat_id;
            }
        }
        $this->dosenservice->updateChatIdDosen($cid, $alamat_email);
        redirect('home/dosen');
    }

    public function kirimpesan() {
        $kode_dosen = $this->session->userdata('kode_dosen');
        $kode_chat = $this->dosenservice->getChatIdDosenByKode($kode_dosen);

        $message_text = "*[SISKA UNIVERSITAS BUMIGORA]*";
        kirim_ke_telegram($kode_chat['chatid'], $message_text);

        $kirim_pesan = $this->session->set_flashdata('infoxy', '<script>swal("Sukses!","Pesan sudah terkirim, mohon periksa aplikasi telegram anda, pada akun Universitas Bumigora","success")</script>');
        redirect('home/dosen');
    }

}
