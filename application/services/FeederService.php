<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FeederService extends MY_Service {

    private $fields = [
        'feeder_url'      => 'URL API Feeder',
        'feeder_port'     => 'Port API Feeder',
        'feeder_username' => 'Username API Feeder',
        'feeder_password' => 'Password API Feeder',
        'feeder_endpoint' => 'Endpoint web service Feeder',
    ];

    private $sensitive = ['feeder_password'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Feeder_model');
        $this->load->library('encryption');
        $this->encryption->initialize([
            'cipher' => 'aes-256',
            'mode'   => 'cbc',
        ]);

        $this->config->load('feeder', TRUE);
        $app_key = trim((string) $this->config->item('feeder_app_key', 'feeder'));
        if ($app_key === '') {
            $app_key = 'base64:' . base64_encode((string) $this->config->item('encryption_key'));
        }
        $this->load->library('laravel_crypt', [
            'key'    => $app_key,
            'cipher' => (string) $this->config->item('feeder_cipher', 'feeder'),
        ]);
    }

    public function getConfig()
    {
        $unified = $this->getConfigUnified();
        if ($unified !== NULL) {
            return $unified;
        }

        return $this->getConfigLegacy();
    }

    /**
     * Baca konfigurasi tunggal dari baris `_feeder_config` yang ditulis
     * aplikasi Filament (payload terenkripsi format Laravel).
     *
     * @return array|NULL NULL jika baris tidak ada atau gagal didekripsi.
     */
    private function getConfigUnified()
    {
        $row = $this->feeder_model->get_credential('_feeder_config');
        if (!$row || trim((string) $row->key_value) === '') {
            return NULL;
        }

        $data = $this->laravel_crypt->decrypt($row->key_value);
        if ($data === NULL) {
            return NULL;
        }

        if (is_string($data)) {
            $decoded = json_decode($data, TRUE);
            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        if (!is_array($data)) {
            return NULL;
        }

        return $this->normalizeConfig($data);
    }

    /**
     * Fallback: konfigurasi lama yang tersimpan pada baris terpisah.
     */
    private function getConfigLegacy()
    {
        $config = [];
        foreach (array_keys($this->fields) as $key) {
            $row = $this->feeder_model->get_credential($key);
            $config[$key] = $row ? $this->decrypt($row->key_value) : '';
        }
        if ($config['feeder_endpoint'] === '') {
            $config['feeder_endpoint'] = '/ws/live2.php';
        }
        return $config;
    }

    /**
     * Normalisasi struktur JSON `_feeder_config` agar toleran terhadap
     * penamaan key yang berbeda dari aplikasi Filament.
     */
    private function normalizeConfig(array $data)
    {
        $pick = function ($keys) use ($data) {
            foreach ($keys as $k) {
                if (array_key_exists($k, $data) && $data[$k] !== NULL) {
                    return $data[$k];
                }
            }
            return '';
        };

        $config = [
            'feeder_url'      => (string) $pick(['feeder_url', 'url', 'host', 'base_url', 'alamat']),
            'feeder_port'     => (string) $pick(['feeder_port', 'port']),
            'feeder_username' => (string) $pick(['feeder_username', 'username', 'user']),
            'feeder_password' => (string) $pick(['feeder_password', 'password', 'pass']),
            'feeder_endpoint' => (string) $pick(['feeder_endpoint', 'endpoint', 'path']),
        ];

        if ($config['feeder_endpoint'] === '') {
            $config['feeder_endpoint'] = '/ws/live2.php';
        }

        return $config;
    }

    public function saveConfig($data, $user_id)
    {
        foreach ($this->fields as $key => $description) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $value = (string) $data[$key];

            if ($key === 'feeder_password' && $value === '') {
                continue;
            }

            if ($key === 'feeder_endpoint' && $value === '') {
                $value = '/ws/live2.php';
            }

            $encrypted = $this->encrypt($value);
            if ($encrypted === FALSE) {
                return FALSE;
            }

            $this->feeder_model->save_credential($key, $encrypted, $description, $user_id);
        }

        return TRUE;
    }

    public function testConnection()
    {
        $row = $this->feeder_model->get_credential('_feeder_config');
        if ($row && trim((string) $row->key_value) !== '' && $this->getConfigUnified() === NULL) {
            return ['status' => FALSE, 'message' => 'Konfigurasi `_feeder_config` tidak dapat didekripsi. Periksa APP_KEY Filament pada application/config/feeder.php.'];
        }

        $config = $this->getConfig();

        if (trim($config['feeder_url']) === '') {
            return ['status' => FALSE, 'message' => 'URL API Feeder belum diisi.'];
        }
        if (trim($config['feeder_username']) === '') {
            return ['status' => FALSE, 'message' => 'Username Feeder belum diisi.'];
        }
        if ($config['feeder_password'] === '') {
            return ['status' => FALSE, 'message' => 'Password Feeder belum diisi.'];
        }

        $endpoint = $this->buildEndpoint($config);
        $payload  = json_encode([
            'act'      => 'GetToken',
            'username' => $config['feeder_username'],
            'password' => $config['feeder_password'],
        ]);

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $http     = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === FALSE) {
            return ['status' => FALSE, 'message' => 'Gagal terhubung ke Feeder: ' . $error];
        }

        $result = json_decode($response, TRUE);
        if (!is_array($result)) {
            return ['status' => FALSE, 'message' => 'Respons Feeder tidak valid (HTTP ' . $http . ').'];
        }

        if (isset($result['error_code']) && (string) $result['error_code'] !== '0') {
            $desc = $result['error_desc'] ?? 'Kesalahan tidak diketahui';
            return ['status' => FALSE, 'message' => 'Feeder menolak koneksi: ' . $desc];
        }

        $token = $result['data']['token'] ?? ($result['token'] ?? NULL);
        if (empty($token)) {
            return ['status' => FALSE, 'message' => 'Koneksi berhasil namun token tidak diterima.'];
        }

        $user_id = (int) $this->session->userdata('id');
        $this->feeder_model->save_credential('feeder_token', $this->encrypt($token), 'Token hasil GetToken', $user_id);

        return [
            'status'  => TRUE,
            'message' => 'Koneksi ke Feeder berhasil. Token diperoleh dan disimpan.',
        ];
    }

    public function getToken($force = FALSE)
    {
        if (!$force) {
            $row = $this->feeder_model->get_credential('feeder_token');
            if ($row) {
                $token = $this->decrypt($row->key_value);
                if ($token !== '') {
                    return $token;
                }
            }
        }

        $config = $this->getConfig();
        if (trim($config['feeder_url']) === '' || trim($config['feeder_username']) === '' || $config['feeder_password'] === '') {
            return '';
        }

        $payload = json_encode([
            'act'      => 'GetToken',
            'username' => $config['feeder_username'],
            'password' => $config['feeder_password'],
        ]);

        $response = $this->httpPost($this->buildEndpoint($config), $payload);
        if ($response === FALSE) {
            return '';
        }

        $result = json_decode($response, TRUE);
        if (!is_array($result)) {
            return '';
        }
        if (isset($result['error_code']) && (string) $result['error_code'] !== '0') {
            return '';
        }

        $token = $result['data']['token'] ?? ($result['token'] ?? '');
        if ($token === '') {
            return '';
        }

        $user_id = (int) $this->session->userdata('id');
        $this->feeder_model->save_credential('feeder_token', $this->encrypt($token), 'Token hasil GetToken', $user_id);

        return $token;
    }

    public function request($act, $params = [], $retry = TRUE)
    {
        $config = $this->getConfig();
        if (trim($config['feeder_url']) === '') {
            return ['error' => 'URL API Feeder belum dikonfigurasi.'];
        }

        $token = $this->getToken();
        if ($token === '') {
            return ['error' => 'Gagal memperoleh token Feeder. Periksa konfigurasi username/password.'];
        }

        $payload = array_merge(['token' => $token, 'act' => $act], $params);

        $response = $this->httpPost($this->buildEndpoint($config), json_encode($payload));
        if ($response === FALSE) {
            return ['error' => 'Gagal terhubung ke Feeder.'];
        }

        $result = json_decode($response, TRUE);
        if (!is_array($result)) {
            return ['error' => 'Respons Feeder tidak valid.'];
        }

        $code = isset($result['error_code']) ? (string) $result['error_code'] : '0';
        if ($code !== '0') {
            $desc = (string) ($result['error_desc'] ?? '');
            $is_token_error = $retry && (
                stripos($desc, 'token') !== FALSE ||
                stripos($desc, 'sesi') !== FALSE ||
                stripos($desc, 'login') !== FALSE
            );
            if ($is_token_error) {
                $this->getToken(TRUE);
                return $this->request($act, $params, FALSE);
            }
            return ['error' => $desc !== '' ? $desc : 'Feeder mengembalikan error_code ' . $code];
        }

        return ['data' => $result['data'] ?? $result];
    }

    public function getNilaiFeederMap($id_semester, $extraFilter = [])
    {
        $filter = $this->buildFilter($id_semester, $extraFilter);

        $map      = [];
        $keysSeen = [];
        $total    = 0;
        $offset   = 0;
        $limit    = 500;
        $guard    = 0;

        while (TRUE) {
            $guard++;
            if ($guard > 200) {
                break;
            }

            $res = $this->request('GetDetailNilaiPerkuliahanKelas', [
                'filter' => $filter,
                'order'  => '',
                'limit'  => (string) $limit,
                'offset' => (string) $offset,
            ]);

            if (isset($res['error'])) {
                return [
                    'map'   => [],
                    'keys'  => [],
                    'total' => 0,
                    'error' => $res['error'],
                ];
            }

            $rows = $res['data'] ?? [];
            if (!is_array($rows) || empty($rows)) {
                break;
            }

            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                if (empty($keysSeen)) {
                    $keysSeen = array_keys($row);
                }

                $nim  = $this->pick($row, ['nim', 'NIM']);
                $kode = $this->pick($row, ['kode_mata_kuliah', 'kode_matakuliah', 'kode_mk']);
                if ($nim === NULL || $kode === NULL) {
                    continue;
                }

                $key = strtoupper(trim((string) $nim)) . '|' . strtoupper(trim((string) $kode));
                $map[$key] = [
                    'nim'   => trim((string) $nim),
                    'nama'  => (string) $this->pick($row, ['nama_mahasiswa', 'nama']),
                    'kode'  => trim((string) $kode),
                    'angka' => $this->pick($row, ['nilai_angka', 'nilai', 'NA']),
                    'huruf' => $this->pick($row, ['nilai_huruf', 'nilai_huruf_mutu', 'huruf']),
                ];
                $total++;
            }

            if (count($rows) < $limit) {
                break;
            }
            $offset += $limit;
        }

        return [
            'map'   => $map,
            'keys'  => $keysSeen,
            'total' => $total,
            'error' => '',
        ];
    }

    public function getNilaiMahasiswa($nim)
    {
        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['rows' => [], 'total' => 0, 'error' => 'NIM kosong.'];
        }

        $filter = "nim='" . str_replace("'", "\\'", $nim) . "'";

        $rows_out = [];
        $offset = 0;
        $limit = 500;
        $guard = 0;

        while (TRUE) {
            $guard++;
            if ($guard > 100) {
                break;
            }

            $res = $this->request('GetDetailNilaiPerkuliahanKelas', [
                'filter' => $filter,
                'order'  => '',
                'limit'  => (string) $limit,
                'offset' => (string) $offset,
            ]);

            if (isset($res['error'])) {
                return ['rows' => [], 'total' => 0, 'error' => $res['error']];
            }

            $rows = $res['data'] ?? [];
            if (!is_array($rows) || empty($rows)) {
                break;
            }

            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $rows_out[] = [
                    'id_semester'        => (string) $this->pick($row, ['id_semester', 'id_smt']),
                    'nama_semester'      => (string) $this->pick($row, ['nama_semester', 'nm_smt']),
                    'kode_mata_kuliah'   => (string) $this->pick($row, ['kode_mata_kuliah', 'kode_matakuliah']),
                    'nama_mata_kuliah'   => (string) $this->pick($row, ['nama_mata_kuliah', 'nama_matakuliah']),
                    'sks'                => $this->pick($row, ['sks_mata_kuliah', 'sks']),
                    'nilai_angka'        => $this->pick($row, ['nilai_angka', 'nilai']),
                    'nilai_huruf'        => $this->pick($row, ['nilai_huruf', 'nilai_huruf_mutu', 'huruf']),
                    'nilai_indeks'       => $this->pick($row, ['nilai_indeks', 'indeks']),
                    'nim'                => (string) $this->pick($row, ['nim', 'NIM']),
                    'nama_mahasiswa'     => (string) $this->pick($row, ['nama_mahasiswa', 'nama']),
                    'nama_program_studi' => (string) $this->pick($row, ['nama_program_studi', 'jurusan']),
                    'angkatan'           => (string) $this->pick($row, ['angkatan']),
                ];
            }

            if (count($rows) < $limit) {
                break;
            }
            $offset += $limit;
        }

        return ['rows' => $rows_out, 'total' => count($rows_out), 'error' => ''];
    }

    public function idSemester($tahun_akademik, $semester)
    {
        $year = (int) substr(trim((string) $tahun_akademik), 0, 4);
        if ($year <= 0) {
            return '';
        }
        $suffix = ((string) $semester === '1') ? '1' : '2';
        return $year . $suffix;
    }

    private function buildFilter($id_semester, $extraFilter = [])
    {
        $parts = ["id_semester='" . str_replace("'", "\\'", (string) $id_semester) . "'"];
        foreach ($extraFilter as $field => $value) {
            $parts[] = $field . "='" . str_replace("'", "\\'", (string) $value) . "'";
        }
        return implode(' and ', $parts);
    }

    private function pick($row, $keys)
    {
        foreach ($keys as $k) {
            if (array_key_exists($k, $row) && $row[$k] !== NULL && $row[$k] !== '') {
                return $row[$k];
            }
        }
        return NULL;
    }

    private function httpPost($endpoint, $payload)
    {
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function buildEndpoint($config)
    {
        $url = trim($config['feeder_url']);
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'http://' . $url;
        }
        $url = rtrim($url, '/');

        $port = trim($config['feeder_port']);
        if ($port !== '' && !preg_match('~:\d+$~', $url)) {
            $url .= ':' . $port;
        }

        $path = trim($config['feeder_endpoint']);
        if ($path === '') {
            $path = '/ws/live2.php';
        }
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $url . $path;
    }

    private function encrypt($value)
    {
        return $this->encryption->encrypt($value);
    }

    private function decrypt($value)
    {
        if ($value === NULL || $value === '') {
            return '';
        }
        $plain = $this->encryption->decrypt($value);
        return ($plain === FALSE) ? '' : $plain;
    }
}
