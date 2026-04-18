<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSKDFieldsToSurat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('surat', [
            'status_kawin' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'alamat',
            ],
            'deskripsi_skd' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status_kawin',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('surat', ['status_kawin', 'deskripsi_skd']);
    }
}
