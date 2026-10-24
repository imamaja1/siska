<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tiket extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        }

        // Hanya Super Admin (role 1) yang berhak mengelola Meja Kerja Tiket & Kategori
        if ((int) $this->session->userdata('id_role') !== 1) {
            redirect('dukungan/tiket/data');
        }

        $this->load->model('dukungan/Tiket_model', 'tiket_m');
    }

    public function index()
    {
        redirect('admin/dukungan/tiket/data');
    }

    public function data()
    {
        $status = $this->input->get('status', true);
        $kategori_id = $this->input->get('kategori', true);
        $urgensi = $this->input->get('urgensi', true);
        $user_type = $this->input->get('user_type', true);
        $keyword = $this->input->get('keyword', true);

        $filter = [
            'status' => $status,
            'kategori_id' => $kategori_id,
            'urgensi' => $urgensi,
            'user_type' => $user_type,
            'keyword' => $keyword
        ];

        $data['judul'] = 'Dukungan';
        $data['sub_judul'] = 'Meja Kerja Tiket Komplain & Bantuan';
        $data['content'] = 'admin/dukungan/tiket/V_index';

        $data['stats'] = $this->tiket_m->get_statistik_admin();
        $data['kategori_list'] = $this->tiket_m->get_kategori(false);
        $data['tiket_list'] = $this->tiket_m->get_tiket_list($filter, 100);
        $data['filter'] = $filter;
        $data['menu_dukungan'] = 'tiket';

        $this->load->view('admin/template/V_main', $data);
    }

    public function detail($id = null)
    {
        if (empty($id)) {
            redirect('admin/dukungan/tiket/data');
        }

        $tiket = $this->tiket_m->get_tiket_by_id($id);
        if (!$tiket) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger"><i class="fa fa-ban"></i> Tiket tidak ditemukan.</div>');
            redirect('admin/dukungan/tiket/data');
        }

        $data['judul'] = 'Dukungan';
        $data['sub_judul'] = 'Detail Tiket #' . $tiket->nomor_tiket;
        $data['content'] = 'admin/dukungan/tiket/V_detail';

        $data['tiket'] = $tiket;
        $data['pesan_list'] = $this->tiket_m->get_pesan($tiket->id, true);
        $data['riwayat_status'] = $this->tiket_m->get_riwayat_status($tiket->id);
        $data['menu_dukungan'] = 'tiket';

        $this->load->view('admin/template/V_main', $data);
    }

    public function ubah_status()
    {
        $tiket_id = $this->input->post('tiket_id', true);
        $status_baru = $this->input->post('status_baru', true);
        $keterangan = $this->input->post('keterangan', true);
        $alasan_penolakan = $this->input->post('alasan_penolakan', true);

        $user_id = $this->session->userdata('id') ?: '1';
        $user_nama = $this->session->userdata('nama_pengguna') ?: 'Super Admin';

        if (!empty($alasan_penolakan) && $status_baru === 'DECLINED') {
            $this->db->where('id', $tiket_id)->update('tiket', ['alasan_penolakan' => $alasan_penolakan]);
            $keterangan = $alasan_penolakan;
        }

        $res = $this->tiket_m->ubah_status($tiket_id, $status_baru, $user_id, $user_nama, $keterangan);

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $res ? 'success' : 'error']));
        }

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Status tiket berhasil diubah ke <strong>' . htmlspecialchars($status_baru) . '</strong>.</div>');
        redirect('admin/dukungan/tiket/detail/' . $tiket_id);
    }

    public function kirim_pesan()
    {
        $tiket_id = $this->input->post('tiket_id', true);
        $pesan = trim($this->input->post('pesan', true));
        $is_internal = $this->input->post('is_internal') ? 1 : 0;

        if (empty($pesan)) {
            redirect('admin/dukungan/tiket/detail/' . $tiket_id);
            return;
        }

        $lampiran_file = null;
        if (!empty($_FILES['lampiran']['name'])) {
            $config['upload_path'] = './assets/uploads/tiket/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|zip';
            $config['max_size'] = 5120; // 5MB
            $config['file_name'] = 'TIKET_MSG_' . time() . '_' . rand(100, 999);

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('lampiran')) {
                $upload_data = $this->upload->data();
                $lampiran_file = $upload_data['file_name'];
            }
        }

        $user_id = $this->session->userdata('id') ?: '1';
        $user_nama = $this->session->userdata('nama_pengguna') ?: 'Super Admin';

        $data_pesan = [
            'tiket_id' => $tiket_id,
            'pengirim_type' => 'admin',
            'pengirim_id' => $user_id,
            'pengirim_nama' => $user_nama,
            'pesan' => $pesan,
            'lampiran' => $lampiran_file,
            'is_internal' => $is_internal
        ];

        $this->tiket_m->tambah_pesan($data_pesan);

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Balasan berhasil terkirim.</div>');
        redirect('admin/dukungan/tiket/detail/' . $tiket_id);
    }

    public function simpan_catatan_internal()
    {
        $tiket_id = $this->input->post('tiket_id', true);
        $catatan = $this->input->post('catatan_internal', true);

        $this->tiket_m->simpan_catatan_internal($tiket_id, $catatan);

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Catatan internal berhasil disimpan.</div>');
        redirect('admin/dukungan/tiket/detail/' . $tiket_id);
    }

    // ==========================================
    // MANAJEMEN KATEGORI DINAMIS
    // ==========================================

    public function kategori()
    {
        $data['judul'] = 'Dukungan';
        $data['sub_judul'] = 'Manajemen Kategori & Form Tiket';
        $data['content'] = 'admin/dukungan/kategori/V_index';
        $data['kategori_list'] = $this->tiket_m->get_kategori(false);
        $data['menu_dukungan'] = 'kategori';

        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan_kategori()
    {
        $id = $this->input->post('id', true);
        $nama_kategori = trim($this->input->post('nama_kategori', true));
        $kode_prefix = strtoupper(trim($this->input->post('kode_prefix', true)));
        $tipe_alur = $this->input->post('tipe_alur', true);
        $deskripsi = trim($this->input->post('deskripsi', true));

        // Form fields dinamis (Array of fields)
        $field_names = $this->input->post('field_name', true) ?: [];
        $field_labels = $this->input->post('field_label', true) ?: [];
        $field_types = $this->input->post('field_type', true) ?: [];
        $field_requireds = $this->input->post('field_required', true) ?: [];
        $field_placeholders = $this->input->post('field_placeholder', true) ?: [];

        $form_fields = [];
        for ($i = 0; $i < count($field_names); $i++) {
            if (!empty($field_names[$i])) {
                $form_fields[] = [
                    'name' => preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($field_names[$i])),
                    'label' => $field_labels[$i] ?: $field_names[$i],
                    'type' => $field_types[$i] ?: 'text',
                    'required' => !empty($field_requireds[$i]),
                    'placeholder' => $field_placeholders[$i] ?: ''
                ];
            }
        }

        $data_kategori = [
            'nama_kategori' => $nama_kategori,
            'kode_prefix' => $kode_prefix,
            'tipe_alur' => $tipe_alur,
            'deskripsi' => $deskripsi,
            'form_fields' => json_encode($form_fields)
        ];

        $this->tiket_m->simpan_kategori($data_kategori, $id ?: null);

        $this->session->set_flashdata('info', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-check"></i> Kategori tiket berhasil disimpan.</div>');
        redirect('admin/dukungan/tiket/kategori');
    }

    public function toggle_kategori($id = null)
    {
        if ($id) {
            $this->tiket_m->toggle_kategori($id);
            $this->session->set_flashdata('info', '<div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon fa fa-info"></i> Status keaktifan kategori diperbarui.</div>');
        }
        redirect('admin/dukungan/tiket/kategori');
    }
}
