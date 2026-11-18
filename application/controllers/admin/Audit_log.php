<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_log extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            $id_role = (int) $this->session->userdata('id_role');
            if ($id_role !== 1) {
                $has_audit_access = rbac_cek('Audit', $id_user) || rbac_cek('Audit_mahasiswa', $id_user) || rbac_cek('Audit_log', $id_user);
                if (!$has_audit_access) {
                    redirect(site_url('denied'));
                }
            }
        }
        $this->load->model('Audit_log_model');
        $this->load->library('pagination');
    }

    public function index($offset = 0) {
        $filters = [
            'submodul'    => $this->input->get('submodul'),
            'aksi'        => $this->input->get('aksi'),
            'identitas'   => trim((string) $this->input->get('identitas')),
            'tgl_mulai'   => $this->input->get('tgl_mulai'),
            'tgl_selesai' => $this->input->get('tgl_selesai'),
        ];

        $limit = 25;
        $offset = (int) $offset;

        $total_rows = $this->Audit_log_model->count_logs($filters);
        $logs = $this->Audit_log_model->get_logs($filters, $limit, $offset);

        // Pagination config
        $config['base_url'] = site_url('admin/audit/log');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['reuse_query_string'] = TRUE;

        // Bootstrap 3 styling for pagination
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '&laquo; Pertama';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Terakhir &raquo;';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lsaquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['judul'] = 'Audit Nilai';
        $data['sub_judul'] = 'Log Aktivitas Audit';
        $data['judul_sub_judul'] = 'Riwayat Perubahan Modul Audit';
        $data['content'] = 'admin/audit/V_log_index';
        $data['logs'] = $logs;
        $data['total_rows'] = $total_rows;
        $data['offset'] = $offset;
        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('admin/template/V_main', $data);
    }

    public function detail($id) {
        $log = $this->Audit_log_model->get_by_id($id);
        if (!$log) {
            echo '<div class="alert alert-danger">Log aktivitas tidak ditemukan.</div>';
            return;
        }

        $data['log'] = $log;
        $data['data_sebelum'] = json_decode($log->data_sebelum, true) ?: [];
        $data['data_sesudah'] = json_decode($log->data_sesudah, true) ?: [];

        $this->load->view('admin/audit/V_log_detail', $data);
    }
}
