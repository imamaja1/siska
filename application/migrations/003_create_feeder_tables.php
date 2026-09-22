<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_feeder_tables extends CI_Migration {

    public function up()
    {
        if (!$this->db->table_exists('feeder_credentials')) {
            $this->db->query("
                CREATE TABLE `feeder_credentials` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `key_name` varchar(100) NOT NULL COMMENT 'feeder_url / feeder_port / feeder_username / feeder_password / feeder_endpoint / feeder_token',
                  `key_value` text NOT NULL COMMENT 'Nilai terenkripsi (AES-256-CBC)',
                  `description` varchar(255) DEFAULT NULL,
                  `created_by` bigint unsigned DEFAULT NULL,
                  `updated_by` bigint unsigned DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        if (!$this->db->table_exists('feeder_sync_logs')) {
            $this->db->query("
                CREATE TABLE `feeder_sync_logs` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `tipe` varchar(50) NOT NULL COMMENT 'mahasiswa/kelas/matakuliah',
                  `tipe_sync` varchar(50) DEFAULT NULL COMMENT 'mahasiswa_per_khs/mahasiswa_semester/kelas_per_mahasiswa/dll',
                  `tahun_akademik` varchar(20) DEFAULT NULL,
                  `semester` varchar(5) DEFAULT NULL,
                  `kode_matakuliah` varchar(50) DEFAULT NULL,
                  `kelas` varchar(10) DEFAULT NULL,
                  `referensi` varchar(100) NOT NULL COMMENT 'NIM / ID Kelas / ID Matakuliah',
                  `jumlah_data_feeder` int NOT NULL DEFAULT '0',
                  `jumlah_data_siska` int NOT NULL DEFAULT '0',
                  `jumlah_sync` int NOT NULL DEFAULT '0',
                  `jumlah_gagal` int NOT NULL DEFAULT '0',
                  `status` enum('success','failed','partial') NOT NULL DEFAULT 'success',
                  `synced_by` bigint unsigned NOT NULL,
                  `log_detail` longtext,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `feeder_sync_logs_tipe_sync_index` (`tipe_sync`),
                  KEY `feeder_sync_logs_tahun_akademik_semester_index` (`tahun_akademik`,`semester`),
                  KEY `feeder_sync_logs_kode_matakuliah_index` (`kode_matakuliah`),
                  CONSTRAINT `feeder_sync_logs_chk_1` CHECK (json_valid(`log_detail`))
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }

    public function down()
    {
        // Tidak menjatuhkan tabel agar data konfigurasi tetap aman.
    }
}
