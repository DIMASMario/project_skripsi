<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyConstraints extends Migration
{
    public function up()
    {
        // Add foreign key untuk berita.author_id -> users.id
        $this->forge->addForeignKey('author_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->db->query('ALTER TABLE berita ADD CONSTRAINT fk_berita_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');

        // Add foreign key untuk surat.user_id -> users.id  
        $this->db->query('ALTER TABLE surat ADD CONSTRAINT fk_surat_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add foreign key untuk notifikasi.user_id -> users.id
        $this->db->query('ALTER TABLE notifikasi ADD CONSTRAINT fk_notifikasi_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add foreign key untuk notifikasi.surat_id -> surat.id
        $this->db->query('ALTER TABLE notifikasi ADD CONSTRAINT fk_notifikasi_surat FOREIGN KEY (surat_id) REFERENCES surat(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add foreign key untuk surat_berkas.surat_id -> surat.id
        $this->db->query('ALTER TABLE surat_berkas ADD CONSTRAINT fk_surat_berkas_surat FOREIGN KEY (surat_id) REFERENCES surat(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add indexes untuk performance optimization
        $this->db->query('CREATE INDEX idx_users_role_status ON users(role, status)');
        $this->db->query('CREATE INDEX idx_surat_status_created ON surat(status, created_at)');
        $this->db->query('CREATE INDEX idx_notifikasi_user_tipe_status ON notifikasi(user_id, tipe, status)');
        $this->db->query('CREATE INDEX idx_berita_status_kategori ON berita(status, kategori)');
        $this->db->query('CREATE INDEX idx_berita_created ON berita(created_at DESC)');
    }

    public function down()
    {
        // Drop foreign keys
        $this->db->query('ALTER TABLE berita DROP FOREIGN KEY fk_berita_author');
        $this->db->query('ALTER TABLE surat DROP FOREIGN KEY fk_surat_user');
        $this->db->query('ALTER TABLE notifikasi DROP FOREIGN KEY fk_notifikasi_user');
        $this->db->query('ALTER TABLE notifikasi DROP FOREIGN KEY fk_notifikasi_surat');
        $this->db->query('ALTER TABLE surat_berkas DROP FOREIGN KEY fk_surat_berkas_surat');

        // Drop indexes
        $this->db->query('DROP INDEX idx_users_role_status ON users');
        $this->db->query('DROP INDEX idx_surat_status_created ON surat');
        $this->db->query('DROP INDEX idx_notifikasi_user_tipe_status ON notifikasi');
        $this->db->query('DROP INDEX idx_berita_status_kategori ON berita');
        $this->db->query('DROP INDEX idx_berita_created ON berita');
    }
}