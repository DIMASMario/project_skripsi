<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'page_visited' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'home',
            ],
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => true,
            ],
            'visited_at' => [
                'type' => 'DATETIME',
                'default' => new \DateTime(),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('ip_address');
        $this->forge->addKey('visited_at');
        $this->forge->createTable('visitor_logs');
    }

    public function down()
    {
        $this->forge->dropTable('visitor_logs');
    }
}
