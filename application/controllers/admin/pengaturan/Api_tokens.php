<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_tokens extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->service('ApiService');

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

    public function index()
    {
        $tokens = $this->apiservice->getAllTokens();
        $logs = $this->apiservice->getLogs(NULL, 9, 0);

        $data = [
            'content'       => 'admin/pengaturan/api_tokens/V_index',
            'judul'         => 'Pengaturan',
            'sub_judul'     => 'API Integrasi',
            'title_h1'      => '<li>Pengaturan</li>',
            'title_h2'      => '<li>API Integrasi</li>',
            'tokens'        => $tokens,
            'logs'          => $logs,
        ];
        $this->load->view('admin/template/V_main', $data);
    }

    public function tambah()
    {
        $data = [
            'content'       => 'admin/pengaturan/api_tokens/VTambah',
            'judul'         => 'Pengaturan',
            'sub_judul'     => 'Tambah Token API',
            'title_h1'      => '<li>Pengaturan</li>',
            'title_h2'      => '<li>API Integrasi</li>',
            'title_h3'      => '<li>Tambah Token</li>',
            'generated_token' => $this->apiservice->generateToken(),
        ];
        $this->load->view('admin/template/V_main', $data);
    }

    public function simpan()
    {
        $this->form_validation->set_rules('nama_aplikasi', 'Nama Aplikasi', 'required|trim');
        $this->form_validation->set_rules('api_url', 'URL Endpoint', 'required|trim|valid_url');
        $this->form_validation->set_rules('bearer_token', 'Bearer Token', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . validation_errors() . '</h6></div>');
            redirect(site_url('admin/pengaturan/api_tokens/tambah'));
        }

        $data = [
            'nama_aplikasi' => $this->input->post('nama_aplikasi'),
            'api_url'       => $this->input->post('api_url'),
            'bearer_token'  => $this->input->post('bearer_token'),
            'is_active'     => $this->input->post('is_active') ? 1 : 0,
        ];

        if ($this->apiservice->saveToken($data)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Token berhasil disimpan.</h6></div>');
            redirect(site_url('admin/pengaturan/api_tokens'));
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal menyimpan token.</h6></div>');
            redirect(site_url('admin/pengaturan/api_tokens/tambah'));
        }
    }

    public function edit($id)
    {
        $token = $this->apiservice->getTokenById($id);
        if (!$token) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Token tidak ditemukan.</h6></div>');
            redirect(site_url('admin/pengaturan/api_tokens'));
        }

        $data = [
            'content'       => 'admin/pengaturan/api_tokens/VEdit',
            'judul'         => 'Pengaturan',
            'sub_judul'     => 'Edit Token API',
            'title_h1'      => '<li>Pengaturan</li>',
            'title_h2'      => '<li>API Integrasi</li>',
            'title_h3'      => '<li>Edit Token</li>',
            'token'         => $token,
        ];
        $this->load->view('admin/template/V_main', $data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $this->form_validation->set_rules('nama_aplikasi', 'Nama Aplikasi', 'required|trim');
        $this->form_validation->set_rules('api_url', 'URL Endpoint', 'required|trim|valid_url');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . validation_errors() . '</h6></div>');
            redirect(site_url('admin/pengaturan/api_tokens/edit/' . $id));
        }

        $data = [
            'nama_aplikasi' => $this->input->post('nama_aplikasi'),
            'api_url'       => $this->input->post('api_url'),
            'is_active'     => $this->input->post('is_active') ? 1 : 0,
        ];

        $bearer = $this->input->post('bearer_token');
        if (!empty($bearer)) {
            $data['bearer_token'] = $bearer;
        }

        if ($this->apiservice->updateToken($id, $data)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Token berhasil diupdate.</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal update token.</h6></div>');
        }
        redirect(site_url('admin/pengaturan/api_tokens'));
    }

    public function hapus($id)
    {
        if ($this->apiservice->deleteToken($id)) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Token berhasil dihapus.</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal hapus token.</h6></div>');
        }
        redirect(site_url('admin/pengaturan/api_tokens'));
    }

    public function toggle_status($id)
    {
        if ($this->apiservice->toggleStatus($id)) {
            $token = $this->apiservice->getTokenById($id);
            $status = $token->is_active ? 'aktif' : 'nonaktif';
            echo json_encode(['status' => true, 'message' => 'Token berhasil diubah menjadi ' . $status]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal mengubah status token.']);
        }
    }

    public function generate_token()
    {
        $token = $this->apiservice->generateToken();
        echo json_encode(['status' => true, 'token' => $token]);
    }

    public function sinkronisasi($id)
    {
        $result = $this->apiservice->syncFromPMB($id);

        if ($result['status']) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>' . $result['message'] . '</h6></div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . $result['message'] . '</h6></div>');
        }
        redirect(site_url('admin/pengaturan/api_tokens'));
    }

    public function sync_process($id)
    {
        $result = $this->apiservice->syncFromPMB($id);
        echo json_encode($result);
    }

    public function sync_start($id)
    {
        $result = $this->apiservice->syncStart($id);
        echo json_encode($result);
    }

    public function sync_page($id, $page)
    {
        $result = $this->apiservice->syncPage($id, $page);
        echo json_encode($result);
    }

    public function sync_finish($id)
    {
        $total_pages  = (int) $this->input->post('total_pages');
        $total_insert = (int) $this->input->post('total_insert');
        $total_update = (int) $this->input->post('total_update');
        $total_skip   = (int) $this->input->post('total_skip');

        $detail_data = null;
        $detail_json = $this->input->post('detail_data');
        if (!empty($detail_json)) {
            $detail_data = json_decode($detail_json, true);
        }

        $result = $this->apiservice->syncFinish($id, $total_pages, $total_insert, $total_update, $total_skip, $detail_data);
        echo json_encode($result);
    }

    public function get_logs($token_id = NULL)
    {
        $offset = $this->input->get('offset') ?? 0;
        $logs = $this->apiservice->getLogs($token_id, 20, $offset);
        $total = $this->apiservice->getLogsCount($token_id);

        $html = '';
        if (empty($logs)) {
            $html = '<tr><td colspan="7" class="text-center">Tidak ada log.</td></tr>';
        } else {
            $no = $offset + 1;
            foreach ($logs as $log) {
                $status_badge = $log->status_sync === 'success'
                    ? '<span class="label label-success">Berhasil</span>'
                    : '<span class="label label-danger">Gagal</span>';
                $html .= '<tr>';
                $html .= '<td>' . $no . '</td>';
                $html .= '<td>' . date('d-m-Y H:i', strtotime($log->created_at)) . '</td>';
                $html .= '<td>' . e($log->nama_aplikasi ?? '-') . '</td>';
                $html .= '<td>' . $status_badge . '</td>';
                $html .= '<td>' . $log->total_data . ' data</td>';
                $html .= '<td>' . ($log->response_code ?? '-') . '</td>';
                $html .= '<td>' . e($log->ip_address ?? '-') . '</td>';
                $html .= '</tr>';
                $no++;
            }
        }

        echo json_encode(['status' => true, 'html' => $html, 'total' => $total]);
    }

    public function get_log_detail($log_id)
    {
        $this->load->model('Api_token_model');
        $log = $this->Api_token_model->get_log_detail($log_id);
        if (!$log) {
            echo json_encode(['status' => false, 'message' => 'Log tidak ditemukan.']);
            return;
        }

        $detail = [];
        if (!empty($log->detail_data)) {
            $detail = json_decode($log->detail_data, true);
        }

        echo json_encode([
            'status'  => true,
            'waktu'   => date('d-m-Y H:i', strtotime($log->created_at)),
            'total'   => $log->total_data,
            'status_sync' => $log->status_sync,
            'detail'  => $detail,
        ]);
    }
}
