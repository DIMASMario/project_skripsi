<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGaleriTable extends Migration
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
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'album' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('galeri');
    }

    public function down()
    {
        $this->forge->dropTable('galeri');
    }
}