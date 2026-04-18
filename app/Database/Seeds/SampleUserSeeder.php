<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleUserSeeder extends Seeder
{
    public function run()
    {
        // Insert sample warga dengan NIK yang unik
        $warga_data = [
            [
                'nama_lengkap' => 'Budi Santoso',
                'nik' => '3213010101900005',
                'no_kk' => '3213010101900005',
                'alamat' => 'Jl. Raya Blanakan No. 123, Desa Tanjungbaru',
                'email' => 'budi@example.com',
                'no_hp' => '081234567891',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'warga',
                'status' => 'disetujui',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_lengkap' => 'Siti Nurhaliza',
                'nik' => '3213010101900006',
                'no_kk' => '3213010101900006',
                'alamat' => 'Jl. Pantai Timur No. 45, Desa Tanjungbaru',
                'email' => 'siti@example.com',
                'no_hp' => '081234567892',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'warga',
                'status' => 'disetujui',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($warga_data as $data) {
            // Check if user already exists
            $existing = $this->db->table('users')->where('email', $data['email'])->get()->getRow();
            if (!$existing) {
                $this->db->table('users')->insert($data);
            }
        }

        // Insert sample berita
        $berita_data = [
            [
                'judul' => 'Perayaan Ruwat Laut Blanakan 2025',
                'slug' => 'perayaan-ruwat-laut-blanakan-2025',
                'konten' => 'Desa Blanakan akan menggelar perayaan Ruwat Laut tahunan pada bulan Agustus 2025. Acara ini merupakan tradisi turun temurun masyarakat pesisir Blanakan untuk meminta keselamatan dan kemakmuran dari laut.',
                'gambar' => 'ruwat-laut-2025.jpg',
                'kategori' => 'Budaya',
                'status' => 'publish',
                'author_id' => 1,
                'views' => 150,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'judul' => 'Pembangunan Jembatan Penghubung Desa',
                'slug' => 'pembangunan-jembatan-penghubung-desa-2025',
                'konten' => 'Pembangunan jembatan penghubung akan dimulai di Desa Blanakan. Proyek ini bertujuan untuk mempermudah akses transportasi warga.',
                'gambar' => 'jembatan-2025.jpg',
                'kategori' => 'Pembangunan',
                'status' => 'publish',
                'author_id' => 1,
                'views' => 89,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($berita_data as $data) {
            $existing = $this->db->table('berita')->where('slug', $data['slug'])->get()->getRow();
            if (!$existing) {
                $this->db->table('berita')->insert($data);
            }
        }

        // Insert sample surat (menggunakan user_id dari users yang baru dibuat)
        $users = $this->db->table('users')->where('role', 'warga')->get()->getResult();
        if (count($users) >= 2) {
            $surat_data = [
                [
                    'user_id' => $users[0]->id,
                    'jenis_surat' => 'domisili',
                    'nama_lengkap' => $users[0]->nama_lengkap,
                    'nik' => $users[0]->nik,
                    'alamat' => $users[0]->alamat,
                    'keperluan' => 'Untuk melengkapi persyaratan pembuatan KTP baru setelah pindah domisili',
                    'status' => 'menunggu',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'user_id' => $users[1]->id,
                    'jenis_surat' => 'tidak_mampu',
                    'nama_lengkap' => $users[1]->nama_lengkap,
                    'nik' => $users[1]->nik,
                    'alamat' => $users[1]->alamat,
                    'keperluan' => 'Untuk mengajukan beasiswa pendidikan anak di sekolah dasar',
                    'status' => 'disetujui',
                    'pesan_admin' => 'Surat telah disetujui dan bisa diambil di kantor kecamatan',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
                ]
            ];
            
            foreach ($surat_data as $data) {
                $existing = $this->db->table('surat')->where('user_id', $data['user_id'])->where('jenis_surat', $data['jenis_surat'])->get()->getRow();
                if (!$existing) {
                    $this->db->table('surat')->insert($data);
                }
            }
        }

        echo "Sample data berhasil ditambahkan!\n";
        echo "Login Admin: email=admin@desa.com, password=password\n";
        echo "Login User: email=budi@example.com, password=password\n";
    }
}