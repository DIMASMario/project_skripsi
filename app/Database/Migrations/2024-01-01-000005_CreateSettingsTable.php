<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
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
            'key_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'value' => [
                'type' => 'TEXT',
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
        $this->forge->createTable('settings');

        // Insert default settings
        $data = [
            [
                'key_name' => 'nama_website',
                'value' => 'Website Resmi Desa Blanakan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key_name' => 'alamat_kantor',
                'value' => 'Jl. Raya Blanakan No. 123, Blanakan, Subang, Jawa Barat',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key_name' => 'telepon',
                'value' => '0260-123456',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key_name' => 'email',
                'value' => 'info@desablanakan.go.id',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key_name' => 'deskripsi_desa',
                'value' => 'Desa Blanakan adalah desa pesisir yang terletak di Kabupaten Subang, Jawa Barat. Desa ini terkenal dengan tradisi Ruwat Laut dan Penangkaran Buaya.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}