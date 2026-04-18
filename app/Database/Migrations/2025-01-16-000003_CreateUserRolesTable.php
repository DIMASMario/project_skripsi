<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRolesTable extends Migration
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
            'role_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'permissions' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('role_name');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('user_roles');

        // Insert default roles
        $data = [
            [
                'role_name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => json_encode([
                    'users' => ['create', 'read', 'update', 'delete'],
                    'content' => ['create', 'read', 'update', 'delete'],
                    'system' => ['config', 'backup', 'logs', 'security'],
                    'reports' => ['view', 'export'],
                ]),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_name' => 'operator',
                'display_name' => 'Operator',
                'description' => 'Content management and basic operations',
                'permissions' => json_encode([
                    'content' => ['create', 'read', 'update'],
                    'letters' => ['read', 'process'],
                ]),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_name' => 'warga',
                'display_name' => 'Warga',
                'description' => 'Regular citizen with basic access',
                'permissions' => json_encode([
                    'profile' => ['read', 'update'],
                    'letters' => ['create', 'read'],
                    'news' => ['read'],
                ]),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('user_roles')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('user_roles');
    }
}