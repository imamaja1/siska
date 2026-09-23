<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Konfigurasi Feeder (integrasi aplikasi Filament)
| -------------------------------------------------------------------------
| APP_KEY aplikasi Filament/Laravel, format "base64:...".
|
| Nilai baris `_feeder_config` pada tabel feeder_credentials dienkripsi
| memakai Encrypter Laravel, sehingga SISKA membutuhkan APP_KEY yang sama
| untuk mendekripsinya.
|
| Jika dibiarkan kosong, SISKA akan memakai encryption_key CodeIgniter
| (application/config/config.php) sebagai kunci. Agar cocok dengan Filament,
| set APP_KEY Filament menjadi:
|   base64:<base64_encode(encryption_key)>
| atau isi nilai di bawah ini dengan APP_KEY Filament yang sebenarnya.
|
| Catatan: file ini HANYA dipakai untuk membaca `_feeder_config` (read-only).
*/
$config['feeder_app_key'] = '';

/*
| Cipher yang dipakai Encrypter Laravel. Default Laravel: aes-256-cbc.
| Ubah ke aes-256-gcm jika aplikasi Filament memakai cipher tersebut.
*/
$config['feeder_cipher'] = 'aes-256-cbc';
