<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => null, // Untuk admin
                'surat_id' => 1,
                'tipe' => 'admin',
                'pesan' => 'Surat baru Surat Domisili dari John Doe menunggu persetujuan.',
                'status' => 'belum_dibaca',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ],
            [
                'user_id' => null, // Untuk admin
                'surat_id' => 2,
                'tipe' => 'admin',
                'pesan' => 'Surat baru Surat Tidak Mampu dari Jane Smith menunggu persetujuan.',
                'status' => 'belum_dibaca',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
            [
                'user_id' => 1, // Untuk warga dengan user_id 1
                'surat_id' => 1,
                'tipe' => 'warga',
                'pesan' => 'Surat Domisili Anda telah disetujui dan siap diambil.',
                'status' => 'belum_dibaca',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-15 minutes'))
            ],
            [
                'user_id' => 2, // Untuk warga dengan user_id 2
                'surat_id' => 3,
                'tipe' => 'warga',
                'pesan' => 'Surat Tidak Mampu Anda telah ditolak. Silakan hubungi admin untuk info lebih lanjut.',
                'status' => 'sudah_dibaca',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ]
        ];

        $this->db->table('notifikasi')->insertBatch($data);
    }
}