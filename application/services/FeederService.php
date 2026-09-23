<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FeederService extends MY_Service {

    private $fields = [
        'feeder_url'      => 'URL API Feeder',
        'feeder_port'     => 'Port API Feeder',
        'feeder_username' => 'Username API Feeder',
        'feeder_password' => 'Password API Feeder',
        'feeder_endpoint' => 'Endpoint web service Feeder',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Feeder_model');
        $this->load->helper('laravel_crypt');
    }

    public function getConfig()
    {
        $config = [];
        foreach (array_keys($this->fields) as $key) {
            $row = $this->feeder_model->get_credential($key);
            $config[$key] = $this->decryptValue($row ? $row->key_value : '');
        }

        if ($config['feeder_endpoint'] === '') {
            $config['feeder_endpoint'] = '/ws/live2.php';
        }

        return $config;
    }

    private function decryptValue($payload)
    {
        if ($payload === NULL || trim((string) $payload) === '') {
            return '';
        }

        $plain = laravel_decrypt($payload);
        if ($plain === FALSE) {
            return '';
        }

        $decoded = $this->decodePayload($plain);

        if (is_array($decoded)) {
            $scalar = $this->firstScalar($decoded);
            return $scalar === NULL ? '' : (string) $scalar;
        }

        return is_scalar($decoded) ? (string) $decoded : '';
    }

    private function decodePayload($plain)
    {
        if (!is_string($plain)) {
            return $plain;
        }

        $decoded = json_decode($plain, TRUE);
        if (is_array($decoded) || (is_scalar($decoded) && $decoded !== NULL)) {
            return $decoded;
        }

        if (preg_match('/^(a:\d+:|s:\d+:|i:\d+;|b:[01];|N;|d:)/', $plain)) {
            $error = NULL;
            set_error_handler(function () use (&$error) {
                $error = TRUE;
                return TRUE;
            });
            $unserialized = unserialize($plain);
            restore_error_handler();

            if (!$error && $unserialized !== FALSE) {
                if (is_string($unserialized)) {
                    $inner = json_decode($unserialized, TRUE);
                    if (is_array($inner) || (is_scalar($inner) && $inner !== NULL)) {
                        return $inner;
                    }
                }
                return $unserialized;
            }
        }

        return $plain;
    }

    private function firstScalar(array $data)
    {
        foreach (['value', 'url', 'host', 'base_url', 'port', 'username', 'user', 'password', 'pass', 'endpoint', 'path', 'data'] as $k) {
            if (array_key_exists($k, $data) && is_scalar($data[$k])) {
                return $data[$k];
            }
        }
        return NULL;
    }

    private function hasCredentialRows()
    {
        foreach (array_keys($this->fields) as $key) {
            $row = $this->feeder_model->get_credential($key);
            if ($row && trim((string) $row->key_value) !== '') {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function testConnection()
    {
        $config = $this->getConfig();

        if (trim($config['feeder_url']) === '' && $this->hasCredentialRows()) {
            return ['status' => FALSE, 'message' => 'Konfigurasi Feeder tidak dapat didekripsi. Pastikan APP_KEY Filament benar pada $config[\'laravel_app_key\'] di application/config/config.php.'];
        }

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

        return [
            'status'  => TRUE,
            'message' => 'Koneksi ke Feeder berhasil.',
        ];
    }

    public function getToken($force = FALSE)
    {
        if (!$force) {
            $row = $this->feeder_model->get_credential('feeder_token');
            if ($row) {
                $token = $this->parseToken($row->key_value);
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

        return (string) ($result['data']['token'] ?? ($result['token'] ?? ''));
    }

    private function parseToken($payload)
    {
        if ($payload === NULL || trim((string) $payload) === '') {
            return '';
        }

        $plain = laravel_decrypt($payload);
        if ($plain === FALSE) {
            return '';
        }

        $data = $this->decodePayload($plain);

        if (is_array($data)) {
            $token   = $this->pick($data, ['token', 'access_token', 'bearer_token']);
            $expired = $this->pick($data, ['expired_at', 'expires_at', 'expiry', 'exp', 'expired']);

            if ($token === NULL || $this->isExpired($expired)) {
                return '';
            }

            return trim((string) $token);
        }

        return trim((string) $plain);
    }

    private function isExpired($expired)
    {
        if ($expired === NULL || $expired === '') {
            return FALSE;
        }

        if (is_numeric($expired)) {
            $ts = (int) $expired;
            if ($ts > 9999999999) {
                $ts = (int) ($ts / 1000);
            }
            return $ts <= time();
        }

        $ts = strtotime((string) $expired);
        return $ts !== FALSE && $ts <= time();
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
}
