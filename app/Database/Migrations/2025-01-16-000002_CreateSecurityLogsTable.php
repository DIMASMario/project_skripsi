<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSecurityLogsTable extends Migration
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
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['success', 'failed', 'blocked'],
                'default'    => 'success',
            ],
            'risk_level' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default'    => 'low',
            ],
            'additional_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('ip_address');
        $this->forge->addKey('action');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('security_logs');
    }

    public function down()
    {
        $this->forge->dropTable('security_logs');
    }
}