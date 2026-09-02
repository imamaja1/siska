<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ApiService extends MY_Service {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_token_model');
    }

    public function getAllTokens()
    {
        return $this->api_token_model->get_all_tokens();
    }

    public function getActiveTokens()
    {
        return $this->api_token_model->get_active_tokens();
    }

    public function getTokenById($id)
    {
        return $this->api_token_model->get_token($id);
    }

    public function saveToken($data)
    {
        return $this->api_token_model->insert_token($data);
    }

    public function updateToken($id, $data)
    {
        return $this->api_token_model->update_token($id, $data);
    }

    public function deleteToken($id)
    {
        return $this->api_token_model->delete_token($id);
    }

    public function toggleStatus($id)
    {
        return $this->api_token_model->toggle_status($id);
    }

    public function generateToken($length = 64)
    {
        return bin2hex(random_bytes($length / 2));
    }

    public function syncFromPMB($token_id)
    {
        $token = $this->api_token_model->get_token($token_id);

        if (!$token) {
            return [
                'status' => false,
                'message' => 'Token tidak ditemukan.',
                'total_data' => 0,
            ];
        }

        if (!$token->is_active) {
            return [
                'status' => false,
                'message' => 'Token tidak aktif. Aktifkan token terlebih dahulu.',
                'total_data' => 0,
            ];
        }

        $base_url = rtrim($token->api_url, '?&');
        $insert_count = 0;
        $update_count = 0;
        $skip_count = 0;
        $total_pages = 1;
        $last_http_code = 200;
        $detail_logs = []; // simpan detail NIM + Nama + Aksi

        // Loop semua halaman: page=1 sampai page=total_pages
        for ($page = 1; $page <= $total_pages; $page++) {
            $separator = (strpos($base_url, '?') !== false) ? '&' : '?';
            $url = $base_url . $separator . 'per_page=100&page=' . $page;

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $token->bearer_token,
                    'Accept: application/json',
                ],
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            $last_http_code = $http_code;

            if ($error) {
                $this->logSync($token_id, $url, 'GET', $http_code, null, 0, 'failed', 'Error pada halaman ' . $page . ': ' . $error);
                return [
                    'status' => false,
                    'message' => 'Gagal koneksi di halaman ' . $page . ': ' . $error,
                    'total_data' => $insert_count + $update_count,
                ];
            }

            $decoded = json_decode($response, true);

            if (!$decoded) {
                $this->logSync($token_id, $url, 'GET', $http_code, 'Response bukan JSON valid', 0, 'failed', $response);
                return [
                    'status' => false,
                    'message' => 'Response bukan JSON valid di halaman ' . $page . '.',
                    'total_data' => $insert_count + $update_count,
                ];
            }

            if (!isset($decoded['success']) || !$decoded['success']) {
                $msg = $decoded['message'] ?? 'Unknown error';
                $this->logSync($token_id, $url, 'GET', $http_code, $msg, 0, 'failed', $response);
                return [
                    'status' => false,
                    'message' => 'Response gagal di halaman ' . $page . ': ' . $msg,
                    'total_data' => $insert_count + $update_count,
                ];
            }

            // Ambil info pagination dari response pertama
            if ($page === 1) {
                $meta = $decoded['meta'] ?? [];
                if (isset($meta['total_pages'])) {
                    $total_pages = (int) $meta['total_pages'];
                } elseif (isset($decoded['total_pages'])) {
                    $total_pages = (int) $decoded['total_pages'];
                } elseif (isset($meta['total']) && isset($meta['per_page'])) {
                    $total = (int) $meta['total'];
                    $per_page = (int) $meta['per_page'];
                    $total_pages = ($per_page > 0) ? (int) ceil($total / $per_page) : 1;
                } else {
                    $total_pages = 1;
                }
                log_message('info', '[API Sync] Total pages: ' . $total_pages);
            }

            $data_mahasiswa = $decoded['data'] ?? [];

            if (empty($data_mahasiswa)) {
                break;
            }

            foreach ($data_mahasiswa as $mhs) {
                $nim = trim((string) ($mhs['nim'] ?? ''));
                if (empty($nim)) {
                    $skip_count++;
                    continue;
                }

                $nama = $mhs['nama_mahasiswa'] ?? '';
                $field_data = $this->mapPMBToSISKA($mhs);
                $field_data['nim'] = $nim; // pastikan NIM string

                $exists = $this->api_token_model->cek_nim_exists($nim);

                if ($exists) {
                    $this->api_token_model->update_mahasiswa($nim, $field_data);
                    $update_count++;
                    $aksi = 'update';
                } else {
                    $this->api_token_model->insert_mahasiswa($field_data);
                    $insert_count++;
                    $aksi = 'insert';
                }

                $detail_logs[] = [
                    'nim'   => $nim,
                    'nama'  => $nama,
                    'aksi'  => $aksi,
                ];
            }
        }

        $total = $insert_count + $update_count;
        $msg = "Sync berhasil ({$total_pages} halaman): {$insert_count} baru, {$update_count} diperbarui, {$skip_count} dilewati.";
        $this->logSync($token_id, $token->api_url, 'GET', $last_http_code, $msg, $total, 'success', null, $detail_logs);

        return [
            'status' => true,
            'message' => $msg,
            'total_data' => $total,
            'total_pages' => $total_pages,
            'insert' => $insert_count,
            'update' => $update_count,
            'skip' => $skip_count,
        ];
    }

    private function mapPMBToSISKA($mhs)
    {
        $data = [
            'nim'                   => $mhs['nim'] ?? '',
            'nik'                   => $mhs['nik'] ?? '',
            'npm'                   => $mhs['npm'] ?? '',
            'nomor_pendaftaran'     => $mhs['nomor_pendaftaran'] ?? '',
            'nomor_pendaftaran_ulang' => $mhs['nomor_pendaftaran_ulang'] ?? '',
            'program_studi_kode'    => $mhs['program_studi_kode'] ?? '',
            'nama_mahasiswa'        => $mhs['nama_mahasiswa'] ?? '',
            'tempat_lahir'          => $mhs['tempat_lahir'] ?? '',
            'tanggal_lahir'         => $mhs['tanggal_lahir'] ?? '',
            'alamat'                => $mhs['alamat'] ?? '',
            'kota'                  => $mhs['kota'] ?? '',
            'propinsi'              => $mhs['propinsi'] ?? '',
            'telepon'               => $mhs['telepon'] ?? '',
            'jenis_kelamin'         => $mhs['jenis_kelamin'] ?? '',
            'agama'                 => $mhs['agama'] ?? '',
            'golongan_darah'        => $mhs['golongan_darah'] ?? '-',
            'kewarganegaraan'       => $mhs['kewarganegaraan'] ?? 'WNI',
            'nama_instansi'         => $mhs['nama_instansi'] ?? null,
            'email'                 => $mhs['email'] ?? '',
            'nama_ayah'             => $mhs['nama_ayah'] ?? '',
            'agama_ayah'            => $mhs['agama_ayah'] ?? '',
            'pekerjaan_ayah'        => $mhs['pekerjaan_ayah'] ?? '',
            'nama_ibu'              => $mhs['nama_ibu'] ?? '',
            'agama_ibu'             => $mhs['agama_ibu'] ?? '',
            'pekerjaan_ibu'         => $mhs['pekerjaan_ibu'] ?? '',
            'alamat_orangtua'       => $mhs['alamat_orangtua'] ?? '',
            'kota_orangtua'         => $mhs['kota_orangtua'] ?? '',
            'propinsi_orangtua'     => $mhs['propinsi_orangtua'] ?? '',
            'telepon_orangtua'      => $mhs['telepon_orangtua'] ?? '',
            'foto'                  => $mhs['foto'] ?? '',
            'sandi'                 => '',
            'status'                => $mhs['status'] ?? 'A',
            'status_pendaftaran'    => $mhs['status_pendaftaran'] ?? 'B',
        ];

        // Sandi = md5 tanggal lahir format dmY (format lama CI)
        // contoh: tanggal_lahir 2007-08-27 => password 27082007 => md5('27082007')
        if (!empty($mhs['tanggal_lahir'])) {
            $data['sandi'] = md5(date('dmY', strtotime($mhs['tanggal_lahir'])));
        }

        return $data;
    }

    // ─── Per-Page Sync Methods ───────────────────────────────────────

    private function buildPageUrl($api_url, $page)
    {
        $base_url = rtrim($api_url, '?&');
        $separator = (strpos($base_url, '?') !== false) ? '&' : '?';
        return $base_url . $separator . 'per_page=100&page=' . $page;
    }

    private function fetchApi($url, $bearer_token)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $bearer_token,
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'error' => 'Koneksi gagal: ' . $error, 'http_code' => $http_code];
        }

        $decoded = json_decode($response, true);
        if (!$decoded) {
            return ['success' => false, 'error' => 'Response bukan JSON valid', 'http_code' => $http_code];
        }

        if (!isset($decoded['success']) || !$decoded['success']) {
            return ['success' => false, 'error' => $decoded['message'] ?? 'Response gagal', 'http_code' => $http_code];
        }

        return ['success' => true, 'decoded' => $decoded, 'http_code' => $http_code];
    }

    private function resolveTotalPages($decoded)
    {
        $meta = $decoded['meta'] ?? [];

        if (isset($meta['total_pages'])) {
            return (int) $meta['total_pages'];
        }
        if (isset($meta['total']) && isset($meta['per_page'])) {
            $total = (int) $meta['total'];
            $per_page = (int) $meta['per_page'];
            return ($per_page > 0) ? (int) ceil($total / $per_page) : 1;
        }

        // Fallback: format lama (field di top-level)
        if (isset($decoded['total_pages'])) {
            return (int) $decoded['total_pages'];
        }
        if (isset($decoded['total']) && isset($decoded['per_page'])) {
            $total = (int) $decoded['total'];
            $per_page = (int) $decoded['per_page'];
            return ($per_page > 0) ? (int) ceil($total / $per_page) : 1;
        }
        return 1;
    }

    private function processPageData($data_mahasiswa)
    {
        $insert_count = 0;
        $update_count = 0;
        $skip_count = 0;
        $detail_logs = [];

        foreach ($data_mahasiswa as $mhs) {
            $nim = trim((string) ($mhs['nim'] ?? ''));
            if (empty($nim)) {
                $skip_count++;
                continue;
            }

            $nama = $mhs['nama_mahasiswa'] ?? '';
            $field_data = $this->mapPMBToSISKA($mhs);
            $field_data['nim'] = $nim;

            $exists = $this->api_token_model->cek_nim_exists($nim);

            if ($exists) {
                $this->api_token_model->update_mahasiswa($nim, $field_data);
                $update_count++;
                $aksi = 'update';
            } else {
                $this->api_token_model->insert_mahasiswa($field_data);
                $insert_count++;
                $aksi = 'insert';
            }

            $detail_logs[] = [
                'nim'   => $nim,
                'nama'  => $nama,
                'aksi'  => $aksi,
            ];
        }

        return [
            'insert' => $insert_count,
            'update' => $update_count,
            'skip' => $skip_count,
            'detail_logs' => $detail_logs,
        ];
    }

    public function syncStart($id)
    {
        $token = $this->api_token_model->get_token($id);
        if (!$token) {
            return ['status' => false, 'message' => 'Token tidak ditemukan.'];
        }
        if (!$token->is_active) {
            return ['status' => false, 'message' => 'Token tidak aktif. Aktifkan token terlebih dahulu.'];
        }

        $url = $this->buildPageUrl($token->api_url, 1);
        $response = $this->fetchApi($url, $token->bearer_token);
        if (!$response['success']) {
            return ['status' => false, 'message' => $response['error']];
        }

        $decoded = $response['decoded'];
        $total_pages = $this->resolveTotalPages($decoded);
        $result = $this->processPageData($decoded['data'] ?? []);

        return [
            'status' => true,
            'token_id' => $id,
            'total_pages' => $total_pages,
            'page' => 1,
            'insert' => $result['insert'],
            'update' => $result['update'],
            'skip' => $result['skip'],
            'detail_logs' => $result['detail_logs'],
        ];
    }

    public function syncPage($id, $page)
    {
        $token = $this->api_token_model->get_token($id);
        if (!$token) {
            return ['status' => false, 'message' => 'Token tidak ditemukan.'];
        }

        $url = $this->buildPageUrl($token->api_url, $page);
        $response = $this->fetchApi($url, $token->bearer_token);
        if (!$response['success']) {
            return ['status' => false, 'message' => $response['error']];
        }

        $decoded = $response['decoded'];
        $result = $this->processPageData($decoded['data'] ?? []);

        return [
            'status' => true,
            'page' => $page,
            'insert' => $result['insert'],
            'update' => $result['update'],
            'skip' => $result['skip'],
            'detail_logs' => $result['detail_logs'],
        ];
    }

    public function syncFinish($id, $total_pages, $total_insert, $total_update, $total_skip, $detail_data = null)
    {
        $token = $this->api_token_model->get_token($id);
        if (!$token) {
            return ['status' => false, 'message' => 'Token tidak ditemukan.'];
        }

        $total = $total_insert + $total_update;
        $msg = "Sync berhasil ({$total_pages} halaman): {$total_insert} baru, {$total_update} diperbarui, {$total_skip} dilewati.";
        $this->logSync($id, $token->api_url, 'GET', 200, $msg, $total, 'success', null, $detail_data);

        return ['status' => true, 'message' => $msg];
    }

    private function logSync($token_id, $endpoint, $method, $response_code, $response_message, $total_data, $status_sync, $error_message, $detail_data = null)
    {
        $this->api_token_model->insert_log([
            'token_id'          => $token_id,
            'endpoint'          => $endpoint,
            'method'            => $method,
            'response_code'     => $response_code,
            'response_message'  => $response_message,
            'total_data'        => $total_data,
            'status_sync'       => $status_sync,
            'error_message'     => $error_message,
            'detail_data'       => $detail_data,
            'ip_address'        => $this->input->ip_address(),
        ]);
    }

    public function getLogs($token_id = NULL, $limit = 20, $offset = 0)
    {
        return $this->api_token_model->get_logs($token_id, $limit, $offset);
    }

    public function getLogsCount($token_id = NULL)
    {
        return $this->api_token_model->get_logs_count($token_id);
    }
}
