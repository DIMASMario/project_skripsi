<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Hapus data user lama
        $this->db->table('users')->truncate();

        // Data user baru dengan password yang benar
        $data = [
            [
                'nama_lengkap' => 'Administrator',
                'nik' => '1234567890123456',
                'no_kk' => '1234567890123456',
                'alamat' => 'Kantor Desa Blanakan',
                'email' => 'admin@desa.com',
                'no_hp' => '081234567890',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'disetujui',
                'foto_ktp' => null,
                'foto_kk' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_lengkap' => 'Admin Desa',
                'nik' => '1234567890123457',
                'no_kk' => '1234567890123457',
                'alamat' => 'Kantor Desa Blanakan',
                'email' => 'admin.desa@desa.com',
                'no_hp' => '081234567891',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'disetujui',
                'foto_ktp' => null,
                'foto_kk' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_lengkap' => 'Budi Santoso',
                'nik' => '3217050112850001',
                'no_kk' => '3217050112850001',
                'alamat' => 'Jl. Raya Blanakan No. 45, Blanakan, Subang',
                'email' => 'budi@example.com',
                'no_hp' => '081234567892',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'warga',
                'status' => 'disetujui',
                'foto_ktp' => null,
                'foto_kk' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_lengkap' => 'Siti Nurhaliza',
                'nik' => '3217050212860002',
                'no_kk' => '3217050212860002',
                'alamat' => 'Jl. Merdeka No. 12, Blanakan, Subang',
                'email' => 'siti@example.com',
                'no_hp' => '081234567893',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'warga',
                'status' => 'disetujui',
                'foto_ktp' => null,
                'foto_kk' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('users')->insertBatch($data);
    }
}