<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tiket extends CI_Controller
{
    private $user_type;
    private $user_id;
    private $user_nama;
    private $user_email;
    private $template_view;

    public function __construct()
    {
        parent::__construct();
        $this->detect_user();
        $this->load->model('dukungan/Tiket_model', 'tiket_m');
    }

    private function detect_user()
    {
        // 1. Admin / Staff (cek jika login di panel admin)
        if ($this->session->userdata('nama_login') && !$this->session->userdata('is_impersonating')) {
            $this->user_type = 'admin';
            $this->user_id = $this->session->userdata('kode_pengguna') ?: ($this->session->userdata('id') ?: '1');
            $nama = $this->session->userdata('nama_pengguna') ?: 'Pengguna Sistem';
            $role_id = $this->session->userdata('id_role');
            if ($role_id) {
                $r = $this->db->select('nama_role')->where('id_role', $role_id)->get('role')->row();
                if ($r && stripos($nama, $r->nama_role) === false) {
                    $nama .= ' (' . $r->nama_role . ')';
                }
            }
            $this->user_nama = $nama;
            $this->user_email = $this->session->userdata('email') ?: '';
            $this->template_view = 'admin/template/V_main';
            return;
        }

        // 2. Dosen
        if ($this->session->userdata('alamat_email') || $this->session->userdata('kode_dosen')) {
            $this->user_type = 'dosen';
            $this->user_id = $this->session->userdata('kode_dosen') ?: $this->session->userdata('id');
            $this->user_nama = $this->session->userdata('nama_dosen') ?: 'Dosen';
            $this->user_email = $this->session->userdata('alamat_email') ?: '';
            $this->template_view = 'dosen/template/V_main';
            return;
        }

        // 3. Mahasiswa
        if ($this->session->userdata('nim')) {
            $this->user_type = 'mahasiswa';
            $this->user_id = $this->session->userdata('nim');
            $this->user_nama = $this->session->userdata('nama_mahasiswa') ?: 'Mahasiswa';
            $this->user_email = $this->session->userdata('email') ?: '';
            $this->template_view = 'mahasiswa/template/V_main';
            return;
        }

        // Jika belum login
        redirect('login/admin');
    }

    public function index()
    {
        redirect('dukungan/tiket/data');
    }

    public function data()
    {
        $status = $this->input->get('status', true);
        $kategori_id = $this->input->get('kategori', true);

        $filter = [
            'status' => $status,
            'kategori_id' => $kategori_id,
            'user_type' => $this->user_type,
            'user_id' => $this->user_id
        ];

        $data['judul'] = 'Dukungan';
        $data['sub_judul'] = 'Tiket Komplain & Bantuan Saya';
        $data['content'] = 'dukungan/tiket/V_user_index';

        $data['stats'] = $this->tiket_m->get_statistik_user($this->user_type, $this->user_id);
        $data['kategori_list'] = $this->tiket_m->get_kategori(true);
        $data['tiket_list'] = $this->tiket_m->get_tiket_list($filter, 50);
        $data['filter'] = $filter;
        $data['menu_dukungan'] = 'tiket';

        $this->load->view($this->template_view, $data);
    }

    public function buat()
    {
        $data['judul'] = 'Dukungan';
        $data['sub_judul'] = 'Buat Tiket Baru';
        $data['content'] = 'dukungan/tiket/V_user_buat';
        $data['kategori_list'] = $this->tiket_m->get_kategori(true);
        $data['menu_dukungan'] = 'buat';

        $this->load->view($this->template_view, $data);
    }

    public function get_form_fields_ajax($kategori_id = null)
    {
        if (empty($kategori_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }

        $kat = $this->tiket_m->get_kategori_by_id($kategori_id);
        if (!$kat || empty($kat->form_fields)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }

        $fields = json_decode($kat->form_fields, true) ?: [];
        return $this->output->set_content_type('application/json')->set_output(json_encode([
            'tipe_alur' => $kat->tipe_alur,
            'deskripsi' => $kat->deskripsi,
            'fields' => $fields
        ]));
    }

    public function simpan()
    {
        $kategori_id = $this->input->post('kategori_id', true);
        $judul = trim($this->input->post('judul', true));
        $urgensi = $this->input->post('urgensi', true) ?: 'sedang';
        $deskripsi = trim($this->input->post('deskripsi', true));

        if (empty($kategori_id) || empty($judul) || empty($deskripsi)) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger"><i class="fa fa-ban"></i> Kategori, Judul, dan Deskripsi kendala wajib diisi!</div>');
            redirect('dukungan/tiket/buat');
            return;
        }

        $kat = $this->tiket_m->get_kategori_by_id($kategori_id);
        if (!$kat) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger"><i class="fa fa-ban"></i> Kategori tidak valid!</div>');
            redirect('dukungan/tiket/buat');
            return;
        }

        // Custom fields dinamis
        $custom_fields_data = [];
        $form_config = json_decode($kat->form_fields, true) ?: [];
        foreach ($form_config as $f) {
            $val = $this->input->post('custom_' . $f['name'], true);
            $custom_fields_data[$f['name']] = [
                'label' => $f['label'],
                'value' => $val
            ];
        }

        // Upload lampiran jika ada
        $lampiran_file = null;
        if (!empty($_FILES['lampiran']['name'])) {
            $config['upload_path'] = './assets/uploads/tiket/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|zip';
            $config['max_size'] = 5120; // 5MB
            $config['file_name'] = 'TIKET_' . time() . '_' . rand(100, 999);

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('lampiran')) {
                $upload_data = $this->upload->data();
                $lampiran_file = $upload_data['file_name'];
            }
        }

        // Generate nomor tiket
        $nomor_tiket = $this->tiket_m->generate_nomor_tiket($kat->kode_prefix);
        $status_awal = ($kat->tipe_alur === 'feature') ? 'SUBMITTED' : 'OPEN';

        $data_tiket = [
            'nomor_tiket' => $nomor_tiket,
            'kategori_id' => $kategori_id,
            'judul' => $judul,
            'urgensi' => $urgensi,
            'status' => $status_awal,
            'user_type' => $this->user_type,
            'user_id' => $this->user_id,
            'user_nama' => $this->user_nama,
            'user_email' => $this->user_email,
            'deskripsi' => $deskripsi,
            'lampiran' => $lampiran_file,
            'custom_fields_data' => json_encode($custom_fields_data)
        ];

        $tiket_id = $this->tiket_m->buat_tiket($data_tiket, 'Tiket berhasil dibuat oleh pelapor');

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Tiket <strong>#' . $nomor_tiket . '</strong> berhasil diajukan! Tim Super Admin akan segera menindaklanjuti.</div>');
        redirect('dukungan/tiket/detail/' . $tiket_id);
    }

    public function detail($id = null)
    {
        if (empty($id)) {
            redirect('dukungan/tiket/data');
        }

        $tiket = $this->tiket_m->get_tiket_by_id($id);
        // Validasi kepemilikan tiket (pelapor hanya bisa melihat tiket miliknya sendiri)
        if (!$tiket || ($tiket->user_type !== $this->user_type || $tiket->user_id != $this->user_id)) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger"><i class="fa fa-ban"></i> Anda tidak memiliki akses ke tiket ini.</div>');
            redirect('dukungan/tiket/data');
            return;
        }

        $data['judul'] = 'Dukungan';
        $data['sub_judul'] = 'Detail Tiket #' . $tiket->nomor_tiket;
        $data['content'] = 'dukungan/tiket/V_user_detail';

        $data['tiket'] = $tiket;
        // Pelapor HANYA bisa membaca pesan publik (is_internal = 0)
        $data['pesan_list'] = $this->tiket_m->get_pesan($tiket->id, false);
        $data['riwayat_status'] = $this->tiket_m->get_riwayat_status($tiket->id);
        $data['menu_dukungan'] = 'tiket';

        $this->load->view($this->template_view, $data);
    }

    public function kirim_pesan()
    {
        $tiket_id = $this->input->post('tiket_id', true);
        $pesan = trim($this->input->post('pesan', true));

        $tiket = $this->tiket_m->get_tiket_by_id($tiket_id);
        if (!$tiket || ($tiket->user_type !== $this->user_type || $tiket->user_id != $this->user_id)) {
            redirect('dukungan/tiket/data');
            return;
        }

        if (empty($pesan)) {
            redirect('dukungan/tiket/detail/' . $tiket_id);
            return;
        }

        $lampiran_file = null;
        if (!empty($_FILES['lampiran']['name'])) {
            $config['upload_path'] = './assets/uploads/tiket/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|zip';
            $config['max_size'] = 5120;
            $config['file_name'] = 'TIKET_REPLY_' . time() . '_' . rand(100, 999);

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('lampiran')) {
                $upload_data = $this->upload->data();
                $lampiran_file = $upload_data['file_name'];
            }
        }

        $data_pesan = [
            'tiket_id' => $tiket_id,
            'pengirim_type' => $this->user_type,
            'pengirim_id' => $this->user_id,
            'pengirim_nama' => $this->user_nama,
            'pesan' => $pesan,
            'lampiran' => $lampiran_file,
            'is_internal' => 0
        ];

        $this->tiket_m->tambah_pesan($data_pesan);

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Pesan balasan berhasil dikirim.</div>');
        redirect('dukungan/tiket/detail/' . $tiket_id);
    }

    public function konfirmasi_selesai($tiket_id = null)
    {
        if (empty($tiket_id)) {
            redirect('dukungan/tiket/data');
        }

        $tiket = $this->tiket_m->get_tiket_by_id($tiket_id);
        if ($tiket && $tiket->user_type === $this->user_type && $tiket->user_id == $this->user_id) {
            $this->tiket_m->ubah_status($tiket_id, 'CLOSED', $this->user_id, $this->user_nama, 'Dikonfirmasi selesai oleh pelapor');
            $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Terima kasih atas konfirmasi Anda. Tiket resmi ditutup.</div>');
        }

        redirect('dukungan/tiket/detail/' . $tiket_id);
    }
}
