<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laravel Crypt compatibility layer.
 *
 * Mendekripsi payload yang dihasilkan Illuminate\Encryption\Encrypter
 * (dipakai aplikasi Filament/Laravel), yaitu base64 dari JSON:
 *   { "iv": "<base64>", "value": "<base64>", "mac": "<hex>" }        (AES-256-CBC)
 *   { "iv": "<base64>", "value": "<base64>", "tag": "<base64>", ... } (AES-256-GCM)
 *
 * APP_KEY Laravel memakai format "base64:<base64(32 byte key)>".
 */
class Laravel_crypt {

    protected $key;
    protected $cipher;

    public function __construct($params = [])
    {
        if (is_array($params)) {
            $key          = isset($params['key']) ? $params['key'] : '';
            $this->cipher = isset($params['cipher']) ? strtolower($params['cipher']) : 'aes-256-cbc';
        } else {
            $key          = (string) $params;
            $this->cipher = 'aes-256-cbc';
        }

        $this->key = $this->parseKey($key);
    }

    public function setCipher($cipher)
    {
        $this->cipher = strtolower((string) $cipher);
    }

    public function validKey()
    {
        if (stripos($this->cipher, '128') !== FALSE) {
            return strlen($this->key) === 16;
        }
        if (stripos($this->cipher, '256') !== FALSE) {
            return strlen($this->key) === 32;
        }
        return strlen($this->key) > 0;
    }

    /**
     * @param string|array $payload Nilai dari kolom key_value.
     * @param bool         $unserialize Coba unserialize hasil dekripsi.
     * @return mixed NULL jika gagal.
     */
    public function decrypt($payload, $unserialize = TRUE)
    {
        if ($payload === NULL || $payload === '') {
            return NULL;
        }

        $data = is_array($payload) ? $payload : json_decode(base64_decode($payload), TRUE);
        if (!is_array($data) || !isset($data['iv'], $data['value'])) {
            return NULL;
        }

        $iv    = base64_decode($data['iv'], TRUE);
        $value = $data['value'];
        if ($iv === FALSE || $value === NULL) {
            return NULL;
        }

        $tag = NULL;
        if (isset($data['tag']) && $data['tag'] !== NULL && $data['tag'] !== '') {
            $tag = base64_decode($data['tag'], TRUE);
        }

        if (stripos($this->cipher, 'gcm') !== FALSE) {
            if ($tag === NULL || $tag === FALSE) {
                return NULL;
            }
            $decrypted = openssl_decrypt($value, $this->cipher, $this->key, 0, $iv, $tag);
        } else {
            if (isset($data['mac']) && $data['mac'] !== NULL && $data['mac'] !== '') {
                $calc = hash_hmac('sha256', $data['iv'] . $value, $this->key);
                if (!hash_equals($calc, (string) $data['mac'])) {
                    return NULL;
                }
            }
            $decrypted = openssl_decrypt($value, $this->cipher, $this->key, 0, $iv);
        }

        if ($decrypted === FALSE) {
            return NULL;
        }

        if ($unserialize) {
            $result = $this->tryUnserialize($decrypted);
            if ($result !== NULL) {
                return $result;
            }
        }

        return $decrypted;
    }

    protected function parseKey($key)
    {
        $key = trim((string) $key);
        if (strncmp($key, 'base64:', 7) === 0) {
            $decoded = base64_decode(substr($key, 7), TRUE);
            if ($decoded !== FALSE) {
                return $decoded;
            }
        }
        return $key;
    }

    protected function tryUnserialize($value)
    {
        if (!is_string($value)) {
            return NULL;
        }

        $error = NULL;
        set_error_handler(function () use (&$error) {
            $error = TRUE;
            return TRUE;
        });
        $result = unserialize($value);
        restore_error_handler();

        if ($error || ($result === FALSE && $value !== 'b:0;')) {
            return NULL;
        }

        return $result;
    }
}
