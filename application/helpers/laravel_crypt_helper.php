<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('laravel_decrypt')) {
    /**
     * Mendekripsi data yang di-encrypt oleh Laravel Crypt (Encrypter).
     *
     * @param string      $payloadBase64 String dari kolom feeder_credentials.key_value
     * @param string|null $appKey        APP_KEY Laravel (jika null, ambil dari config)
     * @return string|false Plain text hasil dekripsi atau false jika gagal
     */
    function laravel_decrypt($payloadBase64, $appKey = null)
    {
        if (empty($payloadBase64)) {
            return false;
        }

        if ($appKey === null) {
            $CI =& get_instance();
            $appKey = $CI->config->item('laravel_app_key');

            if (empty($appKey)) {
                $appKey = 'base64:' . base64_encode((string) $CI->config->item('encryption_key'));
            }
        }

        if (strpos($appKey, 'base64:') === 0) {
            $key = base64_decode(substr($appKey, 7));
        } else {
            $key = $appKey;
        }

        $payload = json_decode(base64_decode($payloadBase64), true);

        if (!is_array($payload) || !isset($payload['iv'], $payload['value'], $payload['mac'])) {
            return false;
        }

        $iv = base64_decode($payload['iv']);

        $calculatedMac = hash_hmac('sha256', $payload['iv'] . $payload['value'], $key);
        if (!hash_equals($calculatedMac, $payload['mac'])) {
            return false;
        }

        $decrypted = openssl_decrypt(
            $payload['value'],
            'AES-256-CBC',
            $key,
            0,
            $iv
        );

        return $decrypted;
    }
}
