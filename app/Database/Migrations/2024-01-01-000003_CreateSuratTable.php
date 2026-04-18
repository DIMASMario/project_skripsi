<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuratTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jenis_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['domisili', 'tidak_mampu', 'pengantar_nikah', 'usaha', 'pengantar_skck'],
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'keperluan' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'disetujui', 'ditolak'],
                'default'    => 'menunggu',
            ],
            'pesan_admin' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'file_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('surat');
    }

    public function down()
    {
        $this->forge->dropTable('surat');
    }
}