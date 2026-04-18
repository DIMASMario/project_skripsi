<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatistikDesaTable extends Migration
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
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'suffix' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'display_order' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 1,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->forge->createTable('statistik_desa');
        
        // Insert default data
        $data = [
            [
                'label' => 'Jumlah Penduduk',
                'icon' => 'groups',
                'number' => '3450',
                'suffix' => '',
                'display_order' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'label' => 'Luas Wilayah',
                'icon' => 'public',
                'number' => '125',
                'suffix' => ' km²',
                'display_order' => 2,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'label' => 'UMKM Terdaftar',
                'icon' => 'storefront',
                'number' => '87',
                'suffix' => '',
                'display_order' => 3,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'label' => 'Fasilitas Pendidikan',
                'icon' => 'school',
                'number' => '5',
                'suffix' => '',
                'display_order' => 4,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->db->table('statistik_desa')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('statistik_desa');
    }
}