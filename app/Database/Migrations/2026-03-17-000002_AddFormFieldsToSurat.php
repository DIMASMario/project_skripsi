<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFormFieldsToSurat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('surat', [
            'no_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'null'       => true,
                'after'      => 'nik',
            ],
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'no_kk',
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'telepon',
            ],
            'tanggal_lahir' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'tempat_lahir',
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['Laki-laki', 'Perempuan'],
                'null'       => true,
                'after'      => 'tanggal_lahir',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('surat', ['no_kk', 'telepon', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin']);
    }
}
