<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteConfigTable extends Migration
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
            'config_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'config_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'config_group' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'general',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_type' => [
                'type'       => 'ENUM',
                'constraint' => ['string', 'number', 'boolean', 'json', 'array'],
                'default'    => 'string',
            ],
            'is_public' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
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
        $this->forge->addKey('config_group');
        $this->forge->createTable('site_config');

        // Insert default configurations
        $data = [
            [
                'config_key' => 'site_name',
                'config_value' => 'Website Resmi Desa Blanakan',
                'config_group' => 'general',
                'description' => 'Nama website',
                'data_type' => 'string',
                'is_public' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'config_key' => 'site_description',
                'config_value' => 'Portal digital Desa Blanakan - Layanan online, informasi terkini, dan transparansi pemerintahan desa.',
                'config_group' => 'general',
                'description' => 'Deskripsi website',
                'data_type' => 'string',
                'is_public' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'config_key' => 'site_logo',
                'config_value' => 'images/carousel/logo.png',
                'config_group' => 'general',
                'description' => 'Logo website',
                'data_type' => 'string',
                'is_public' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'config_key' => 'maintenance_mode',
                'config_value' => 'false',
                'config_group' => 'system',
                'description' => 'Mode maintenance website',
                'data_type' => 'boolean',
                'is_public' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'config_key' => 'email_notifications',
                'config_value' => 'true',
                'config_group' => 'notifications',
                'description' => 'Enable email notifications',
                'data_type' => 'boolean',
                'is_public' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'config_key' => 'max_file_size',
                'config_value' => '5120',
                'config_group' => 'uploads',
                'description' => 'Maximum file size for uploads (KB)',
                'data_type' => 'number',
                'is_public' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('site_config')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('site_config');
    }
}