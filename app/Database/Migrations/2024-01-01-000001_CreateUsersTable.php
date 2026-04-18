<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
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
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'unique'     => true,
            ],
            'no_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'warga'],
                'default'    => 'warga',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'disetujui', 'ditolak'],
                'default'    => 'menunggu',
            ],
            'foto_ktp' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'foto_kk' => [
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
        $this->forge->createTable('users');

        // Insert default admin user
        $data = [
            'nama_lengkap' => 'Super Admin',
            'nik' => '1234567890123456',
            'no_kk' => '1234567890123456',
            'alamat' => 'Kantor Desa Blanakan',
            'email' => 'admin@desa.com',
            'no_hp' => '081234567890',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'disetujui',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}