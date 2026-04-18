<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixUsersTableColumnConflicts extends Migration
{
    public function up()
    {
        // Recreate settings table if missing
        try {
            $settingsExists = $this->db->tableExists('settings');
            if (!$settingsExists) {
                $this->db->query("
                    CREATE TABLE `settings` (
                      `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                      `setting_key` varchar(100) NOT NULL,
                      `setting_value` longtext,
                      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `setting_key` (`setting_key`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
                ");
            }
        } catch (\Exception $e) {
            // Settings table might already exist
            log_message('debug', 'Settings table: ' . $e->getMessage());
        }

        // Fix users table columns - use try-catch to avoid errors if already modified
        try {
            $this->db->query("ALTER TABLE `users` MODIFY `no_kk` VARCHAR(16) NULL");
        } catch (\Exception $e) {}

        try {
            $this->db->query("ALTER TABLE `users` MODIFY `nik` VARCHAR(16) NULL");
        } catch (\Exception $e) {}

        try {
            $this->db->query("ALTER TABLE `users` MODIFY `username` VARCHAR(50) NULL");
        } catch (\Exception $e) {}

        // Tambah kolom no_ktp jika belum ada
        if (!$this->db->fieldExists('no_ktp', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `no_ktp` VARCHAR(16) UNIQUE COMMENT 'Nomor KTP'");
            } catch (\Exception $e) {
                log_message('debug', 'no_ktp column: ' . $e->getMessage());
            }
        }

        // Tambah kolom kode_registrasi_id jika belum ada
        if (!$this->db->fieldExists('kode_registrasi_id', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `kode_registrasi_id` INT UNSIGNED NULL AFTER `id` COMMENT 'FK ke tabel kode_registrasi'");
            } catch (\Exception $e) {
                log_message('debug', 'kode_registrasi_id column: ' . $e->getMessage());
            }
        }

        // Tambah kolom login_identifier jika belum ada
        if (!$this->db->fieldExists('login_identifier', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `login_identifier` VARCHAR(100) NULL AFTER `email` COMMENT 'Email atau nomor HP untuk login'");
            } catch (\Exception $e) {
                log_message('debug', 'login_identifier column: ' . $e->getMessage());
            }
        }

        // Tambah kolom foto_verifikasi_status jika belum ada
        if (!$this->db->fieldExists('foto_verifikasi_status', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `foto_verifikasi_status` ENUM('pending', 'verified', 'rejected') DEFAULT 'pending' COMMENT 'Status verifikasi foto'");
            } catch (\Exception $e) {
                log_message('debug', 'foto_verifikasi_status column: ' . $e->getMessage());
            }
        }

        // Add indexes
        try {
            $this->db->query("CREATE INDEX `idx_login_identifier` ON `users`(`login_identifier`)");
        } catch (\Exception $e) {}

        try {
            $this->db->query("CREATE INDEX `idx_email_nohp` ON `users`(`email`, `no_hp`)");
        } catch (\Exception $e) {}
    }

    public function down()
    {
        // Revert kolom ke NOT NULL
        try {
            $this->db->query("ALTER TABLE `users` MODIFY `no_kk` VARCHAR(16) NOT NULL");
        } catch (\Exception $e) {}

        try {
            $this->db->query("ALTER TABLE `users` MODIFY `nik` VARCHAR(16) NOT NULL");
        } catch (\Exception $e) {}

        try {
            $this->db->query("ALTER TABLE `users` MODIFY `username` VARCHAR(50) NOT NULL");
        } catch (\Exception $e) {}

        // Drop added columns
        if ($this->db->fieldExists('no_ktp', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` DROP COLUMN `no_ktp`");
            } catch (\Exception $e) {}
        }

        if ($this->db->fieldExists('kode_registrasi_id', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` DROP COLUMN `kode_registrasi_id`");
            } catch (\Exception $e) {}
        }

        if ($this->db->fieldExists('login_identifier', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` DROP COLUMN `login_identifier`");
            } catch (\Exception $e) {}
        }

        if ($this->db->fieldExists('foto_verifikasi_status', 'users')) {
            try {
                $this->db->query("ALTER TABLE `users` DROP COLUMN `foto_verifikasi_status`");
            } catch (\Exception $e) {}
        }

        // Drop settings table if needed
        try {
            $this->db->query("DROP TABLE IF EXISTS `settings`");
        } catch (\Exception $e) {}
    }
}
