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

    private function processPageData($data_mahasiswa, $mode = 'sync')
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

            if ($mode === 'insert') {
                // Mode Import: hanya menambah mahasiswa baru, tidak update data lama
                if ($exists) {
                    $skip_count++;
                    $aksi = 'skip';
                } else {
                    $this->api_token_model->insert_mahasiswa($field_data);
                    $insert_count++;
                    $aksi = 'insert';
                }
            } else {
                // Mode Sync: update semua sekaligus tambah data
                if ($exists) {
                    $this->api_token_model->update_mahasiswa($nim, $field_data);
                    $update_count++;
                    $aksi = 'update';
                } else {
                    $this->api_token_model->insert_mahasiswa($field_data);
                    $insert_count++;
                    $aksi = 'insert';
                }
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

    public function syncStart($id, $mode = 'sync')
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
        $result = $this->processPageData($decoded['data'] ?? [], $mode);

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

    public function syncPage($id, $page, $mode = 'sync')
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
        $result = $this->processPageData($decoded['data'] ?? [], $mode);

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

    // ─── Cek Perbedaan Data (Check Diff) ─────────────────────────────

    public function checkDiffFromPMB($token_id)
    {
        $token = $this->api_token_model->get_token($token_id);
        if (!$token) {
            return ['status' => false, 'message' => 'Token tidak ditemukan.'];
        }
        if (!$token->is_active) {
            return ['status' => false, 'message' => 'Token tidak aktif. Aktifkan token terlebih dahulu.'];
        }

        $total_pages = 1;
        $daftar_berbeda = [];
        $baru = [];
        $sama = 0;
        $skip = 0;

        // ── Tahap 1: Pengambilan data dari PMB (semua halaman) ──
        $semua_data = [];
        for ($page = 1; $page <= $total_pages; $page++) {
            $url = $this->buildPageUrl($token->api_url, $page);
            $response = $this->fetchApi($url, $token->bearer_token);
            if (!$response['success']) {
                return ['status' => false, 'message' => $response['error'], 'total_pages' => $total_pages];
            }

            $decoded = $response['decoded'];
            if ($page === 1) {
                $total_pages = $this->resolveTotalPages($decoded);
            }

            $data_mahasiswa = $decoded['data'] ?? [];
            if (empty($data_mahasiswa)) {
                break;
            }

            foreach ($data_mahasiswa as $mhs) {
                $semua_data[] = $mhs;
            }
        }

        // ── Tahap 2: Bandingkan data PMB dengan data SISKA ──
        foreach ($semua_data as $mhs) {
            $nim = trim((string) ($mhs['nim'] ?? ''));
            if (empty($nim)) {
                $skip++;
                continue;
            }

            $nama_pmb = trim((string) ($mhs['nama_mahasiswa'] ?? ''));
            $lokal = $this->api_token_model->get_mahasiswa_by_nim($nim);

            if (!$lokal) {
                $baru[] = ['nim' => $nim, 'nama' => $nama_pmb];
            } elseif (trim((string) $lokal->nama_mahasiswa) !== $nama_pmb) {
                $field_data = $this->mapPMBToSISKA($mhs);
                $field_data['nim'] = $nim;
                unset($field_data['sandi']); // jangan kirim sandi ke browser
                $daftar_berbeda[] = [
                    'nim'        => $nim,
                    'nama_pmb'   => $nama_pmb,
                    'nama_lokal' => (string) $lokal->nama_mahasiswa,
                    'data'       => $field_data,
                ];
            } else {
                $sama++;
            }
        }

        // ── Tahap 3: Cek program studi baru dari PMB ──
        $prodi_baru = [];
        $prodi_pmb_map = [];
        foreach ($this->getProdiFromPMB($token->api_url, $token->bearer_token) as $p) {
            $prodi_pmb_map[(int) ($p['id'] ?? 0)] = $p;
        }
        // Pastikan kode prodi yang muncul di data mahasiswa ikut dicek
        foreach ($semua_data as $mhs) {
            $kode = (int) ($mhs['program_studi_kode'] ?? 0);
            if ($kode > 0 && !isset($prodi_pmb_map[$kode])) {
                $prodi_pmb_map[$kode] = [
                    'id' => $kode,
                    'nama_prodi' => '',
                    'jenjang' => '',
                    'nama_fakultas' => '',
                    'kode_fakultas' => '',
                    'kode_ps_nim' => '',
                ];
            }
        }
        ksort($prodi_pmb_map);
        foreach ($prodi_pmb_map as $kode => $p) {
            if ($kode <= 0 || $this->api_token_model->cek_prodi_exists($kode)) {
                continue;
            }
            $prodi_baru[] = [
                'kode'         => $kode,
                'nama_prodi'   => $p['nama_prodi'] ?? '',
                'jenjang'      => $p['jenjang'] ?? '',
                'nama_fakultas' => $p['nama_fakultas'] ?? '',
            ];
        }

        $count_diff = count($daftar_berbeda);
        $count_baru = count($baru);
        $count_prodi = count($prodi_baru);

        $msg = "Cek data selesai ({$total_pages} halaman): {$count_diff} berbeda, {$count_baru} baru, {$sama} sama, {$skip} dilewati, {$count_prodi} program studi baru.";
        $this->logSync($token_id, $token->api_url, 'GET', 200, $msg, $count_diff, 'success', null, array_merge(
            array_map(function ($d) { return ['nim' => $d['nim'], 'nama' => $d['nama_pmb'], 'aksi' => 'berbeda']; }, $daftar_berbeda),
            array_map(function ($d) { return ['nim' => $d['nim'], 'nama' => $d['nama'], 'aksi' => 'baru']; }, $baru)
        ));

        return [
            'status' => true,
            'message' => $msg,
            'total_pages' => $total_pages,
            'berbeda' => $daftar_berbeda,
            'baru' => $baru,
            'sama' => $sama,
            'skip' => $skip,
            'prodi_baru' => $prodi_baru,
        ];
    }

    // ─── Tambah Program Studi dari PMB ──────────────────────────────

    private function getProdiFromPMB($api_url, $bearer_token)
    {
        $base_url = preg_replace('#/siska/mahasiswa$#', '', rtrim($api_url, '?&'));
        if ($base_url === rtrim($api_url, '?&')) {
            return [];
        }

        $url = $base_url . '/siska/program-studi';
        $response = $this->fetchApi($url, $bearer_token);
        if (!$response['success']) {
            return [];
        }

        return $response['decoded']['data'] ?? [];
    }

    public function tambahProdiFromPMB($token_id, $kode)
    {
        $token = $this->api_token_model->get_token($token_id);
        if (!$token) {
            return ['status' => false, 'message' => 'Token tidak ditemukan.'];
        }
        if (!$token->is_active) {
            return ['status' => false, 'message' => 'Token tidak aktif. Aktifkan token terlebih dahulu.'];
        }

        $kode = (int) $kode;
        if ($kode <= 0) {
            return ['status' => false, 'message' => 'Kode program studi tidak valid.'];
        }
        if ($this->api_token_model->cek_prodi_exists($kode)) {
            return ['status' => false, 'message' => "Program studi kode {$kode} sudah ada di SISKA."];
        }

        $prodi = null;
        foreach ($this->getProdiFromPMB($token->api_url, $token->bearer_token) as $p) {
            if ((int) ($p['id'] ?? 0) === $kode) {
                $prodi = $p;
                break;
            }
        }
        if (!$prodi) {
            return ['status' => false, 'message' => "Program studi kode {$kode} tidak ditemukan di PMB."];
        }

        $nama_pmb = trim((string) ($prodi['nama_prodi'] ?? ''));
        $nama = $nama_pmb !== '' ? ucwords(strtolower($nama_pmb)) : 'Program Studi ' . $kode;

        $jenjang_str = trim((string) ($prodi['jenjang'] ?? ''));
        $jenjang_map = ['S1' => 1, 'D3' => 2, 'S2' => 3];
        $id_jenjang = $jenjang_map[strtoupper($jenjang_str)] ?? null;

        $kode_fakultas = null;
        $fakultas = $this->api_token_model->get_fakultas_by_nama($prodi['nama_fakultas'] ?? '');
        if ($fakultas) {
            $kode_fakultas = $fakultas->kode_fakultas;
        }

        $data = [
            'kode_program_studi' => $kode,
            'nama_program_studi' => $nama,
            'singkatan_program_studi' => $this->buatSingkatanProdi($nama_pmb, $jenjang_str),
            'kode_fakultas' => $kode_fakultas ?: '',
            'kode_prodi_univ' => substr(trim((string) ($prodi['kode_ps_nim'] ?? '')), 0, 4) ?: '',
            'kompetensi' => 'N',
            'id_jenjang' => $id_jenjang,
            'id_jurusan' => null,
            'kode_pengguna' => 0,
        ];

        if ($this->api_token_model->insert_program_studi($data)) {
            $msg = "Program studi {$nama} (kode {$kode}) berhasil ditambahkan.";
            $this->logSync($token_id, $token->api_url, 'GET', 200, $msg, 1, 'success', null, [[
                'nim' => $kode,
                'nama' => $nama,
                'aksi' => 'tambah_prodi',
            ]]);
            return ['status' => true, 'message' => $msg];
        }

        return ['status' => false, 'message' => 'Gagal menambahkan program studi.'];
    }

    private function buatSingkatanProdi($nama, $jenjang_str)
    {
        $jenjang = strtoupper(trim($jenjang_str));
        $abbr = (strpos($jenjang, 'S1') === 0) ? 'S1'
            : (strpos($jenjang, 'D3') === 0 ? 'D3'
            : (strpos($jenjang, 'S2') === 0 ? 'S2'
            : 'Prof'));

        $words = preg_split('/\s+/', trim($nama));
        $rest = [];
        foreach ($words as $w) {
            $w = strtoupper(trim($w));
            if ($w === '' || $w === $jenjang || $w === 'PROGRAM') {
                continue;
            }
            $rest[] = $w;
        }

        if (empty($rest)) {
            return ucwords(strtolower(trim($nama)));
        }

        if (count($rest) === 1) {
            $word = $rest[0];
            $part = (strlen($word) <= 10) ? $word : substr($word, 0, 6);
        } else {
            $initials = '';
            foreach ($rest as $w) {
                $initials .= $w[0];
            }
            $part = $initials;
        }

        return $abbr . ' ' . ucwords(strtolower($part));
    }

    // ─── Update Satu Mahasiswa (dari data hasil cek, tanpa fetch ulang PMB) ──

    public function updateOneFromData($token_id, $nim, $data)
    {
        $token = $this->api_token_model->get_token($token_id);
        if (!$token) {
            return ['status' => false, 'message' => 'Token tidak ditemukan.'];
        }
        if (!$token->is_active) {
            return ['status' => false, 'message' => 'Token tidak aktif. Aktifkan token terlebih dahulu.'];
        }

        $nim = trim((string) $nim);
        if ($nim === '') {
            return ['status' => false, 'message' => 'NIM tidak valid.'];
        }

        if (!is_array($data) || empty($data)) {
            return ['status' => false, 'message' => 'Data tidak valid. Jalankan Cek kembali.'];
        }

        // Whitelist field sesuai mapPMBToSISKA agar data dari client aman
        $allowed = array_keys($this->mapPMBToSISKA([]));
        $clean = [];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $clean[$k] = $data[$k];
            }
        }
        $clean['nim'] = $nim;

        if (empty($clean['nama_mahasiswa'])) {
            return ['status' => false, 'message' => 'Nama mahasiswa tidak ditemukan pada data.'];
        }

        // Hitung ulang sandi dari tanggal lahir (tidak diambil dari browser)
        if (!empty($clean['tanggal_lahir'])) {
            $clean['sandi'] = md5(date('dmY', strtotime($clean['tanggal_lahir'])));
        }

        if ($this->api_token_model->update_mahasiswa($nim, $clean)) {
            $msg = "Data mahasiswa {$nim} ({$clean['nama_mahasiswa']}) berhasil diperbarui.";
            $this->logSync($token_id, $token->api_url, 'GET', 200, $msg, 1, 'success', null, [[
                'nim' => $nim,
                'nama' => $clean['nama_mahasiswa'],
                'aksi' => 'update',
            ]]);
            return ['status' => true, 'message' => $msg, 'nim' => $nim];
        }

        return ['status' => false, 'message' => 'Gagal memperbarui data mahasiswa.'];
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
