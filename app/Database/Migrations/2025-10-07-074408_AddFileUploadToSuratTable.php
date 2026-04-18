<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFileUploadToSuratTable extends Migration
{
    public function up()
    {
        $fields = [
            'file_ktp' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'file_surat'
            ],
            'file_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'file_ktp'
            ],
            'file_pengantar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'file_kk'
            ]
        ];
        
        $this->forge->addColumn('surat', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('surat', ['file_ktp', 'file_kk', 'file_pengantar']);
    }
}
