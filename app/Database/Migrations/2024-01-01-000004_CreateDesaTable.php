<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesaTable extends Migration
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
            'nama_desa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'kepala_desa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'jumlah_penduduk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'potensi' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('desa');

        // Insert sample data
        $data = [
            [
                'nama_desa' => 'Cilamaya Hilir',
                'kepala_desa' => 'Bapak Suherman',
                'alamat' => 'Desa Cilamaya Hilir, Blanakan, Subang',
                'deskripsi' => 'Desa pesisir dengan potensi perikanan',
                'jumlah_penduduk' => 7000,
                'potensi' => 'Perikanan laut, tambak udang, wisata pantai',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_desa' => 'Cilamaya Girang',
                'kepala_desa' => 'Bapak Suryadi',
                'alamat' => 'Desa Cilamaya Girang, Blanakan, Subang',
                'deskripsi' => 'Desa dengan potensi pertanian dan perikanan',
                'jumlah_penduduk' => 6500,
                'potensi' => 'Pertanian padi, perikanan darat',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_desa' => 'Rawameneng',
                'kepala_desa' => 'Bapak Ahmad',
                'alamat' => 'Desa Rawameneng, Blanakan, Subang',
                'deskripsi' => 'Desa dengan potensi pertanian',
                'jumlah_penduduk' => 5800,
                'potensi' => 'Pertanian padi, sayuran',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('desa')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('desa');
    }
}