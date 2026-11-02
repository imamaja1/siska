<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_mahasiswa_semester_masuk extends CI_Migration {

    private $seed = array(
        '24010820008', '24010820009', '24010820010', '24010820011',
        '24010820012', '24010820013', '24010820014', '24010820015',
        '24010820016', '24010820017', '24040220004', '24040220005',
        '25010820010', '25010820011', '25010820012', '25010820013',
        '25010820014', '25040220006', '25040220007',
    );

    public function up()
    {
        if (!$this->db->table_exists('mahasiswa_semester_masuk')) {
            $this->db->query("
                CREATE TABLE `mahasiswa_semester_masuk` (
                  `nim` char(11) NOT NULL,
                  `semester_masuk` enum('GANJIL','GENAP') NOT NULL DEFAULT 'GANJIL',
                  `keterangan` varchar(255) DEFAULT NULL,
                  `diubah_oleh` varchar(100) DEFAULT NULL,
                  `created_at` datetime DEFAULT NULL,
                  `updated_at` datetime DEFAULT NULL,
                  PRIMARY KEY (`nim`),
                  KEY `idx_semester_masuk` (`semester_masuk`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        foreach ($this->seed as $nim) {
            $this->db->query("
                INSERT IGNORE INTO `mahasiswa_semester_masuk`
                    (`nim`, `semester_masuk`, `keterangan`, `diubah_oleh`, `created_at`, `updated_at`)
                VALUES (?, 'GENAP', 'Data awal migrasi', 'migration', NOW(), NOW())
            ", array($nim));
        }
    }

    public function down()
    {
        // Tidak menjatuhkan tabel agar data konfigurasi tetap aman.
    }
}
