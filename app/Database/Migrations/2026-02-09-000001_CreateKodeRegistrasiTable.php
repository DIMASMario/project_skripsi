<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKodeRegistrasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kode_registrasi' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
                'comment' => 'Format: BLK-RT03RW02-0007',
            ],
            'rt' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
            ],
            'rw' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
            ],
            'nomor_urut' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Nomor urut warga pada RT/RW tersebut',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['belum_digunakan', 'sudah_digunakan', 'kadaluarsa'],
                'default' => 'belum_digunakan',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID user yang menggunakan kode ini',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Catatan tambahan dari admin',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID admin yang membuat kode',
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Waktu kode digunakan untuk registrasi',
            ],
            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Waktu kadaluarsa kode (opsional)',
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
        $this->forge->addKey('kode_registrasi');
        $this->forge->addKey('status');
        $this->forge->addKey('rt');
        $this->forge->addKey('rw');
        
        $this->forge->createTable('kode_registrasi', true);
    }

    public function down()
    {
        $this->forge->dropTable('kode_registrasi', true);
    }
}
