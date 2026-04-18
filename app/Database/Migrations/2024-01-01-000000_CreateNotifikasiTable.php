<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotifikasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
            ],
            'surat_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'warga'],
                'default'    => 'warga',
            ],
            'pesan' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['belum_dibaca', 'sudah_dibaca'],
                'default'    => 'belum_dibaca',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notifikasi');
    }

    public function down()
    {
        $this->forge->dropTable('notifikasi');
    }
}