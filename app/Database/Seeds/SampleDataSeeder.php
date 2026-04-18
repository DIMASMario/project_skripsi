<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Insert sample warga (admin sudah ada dari SQL dump)
        $warga_data = [
            [
                'nama_lengkap' => 'Budi Santoso',
                'nik' => '3213010101900002',
                'no_kk' => '3213010101900002',
                'alamat' => 'Jl. Raya Blanakan No. 123, Desa Tanjungbaru',
                'email' => 'budi@gmail.com',
                'no_hp' => '081234567891',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'warga',
                'status' => 'disetujui',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_lengkap' => 'Siti Nurhaliza',
                'nik' => '3213010101900003',
                'no_kk' => '3213010101900003',
                'alamat' => 'Jl. Pantai Timur No. 45, Desa Tanjungbaru',
                'email' => 'siti@gmail.com',
                'no_hp' => '081234567892',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'warga',
                'status' => 'disetujui',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('users')->insertBatch($warga_data);

        // Insert sample berita
        $berita_data = [
            [
                'judul' => 'Perayaan Ruwat Laut Blanakan 2024',
                'slug' => 'perayaan-ruwat-laut-blanakan-2024',
                'konten' => 'Desa Blanakan akan menggelar perayaan Ruwat Laut tahunan pada bulan Agustus 2024. Acara ini merupakan tradisi turun temurun masyarakat pesisir Blanakan untuk meminta keselamatan dan kemakmuran dari laut.',
                'gambar' => 'ruwat-laut-2024.jpg',
                'kategori' => 'Budaya',
                'status' => 'publish',
                'author_id' => 1,
                'views' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'judul' => 'Pembangunan Jembatan Penghubung Desa',
                'slug' => 'pembangunan-jembatan-penghubung-desa',
                'konten' => 'Pembangunan jembatan penghubung akan dimulai di Desa Blanakan. Proyek ini bertujuan untuk mempermudah akses transportasi warga.',
                'gambar' => 'jembatan-2024.jpg',
                'kategori' => 'Pembangunan',
                'status' => 'publish',
                'author_id' => 1,
                'views' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('berita')->insertBatch($berita_data);

        // Insert desa data (menambah ke data yang sudah ada)
        $desa_data = [
            [
                'nama_desa' => 'Tanjungbaru',
                'kepala_desa' => 'H. Ahmad Sutrisno',
                'alamat' => 'Jl. Raya Tanjungbaru No. 1, Blanakan, Subang',
                'deskripsi' => 'Desa pesisir dengan potensi perikanan dan wisata bahari',
                'jumlah_penduduk' => 5420,
                'potensi' => 'Perikanan, wisata bahari, budaya Ruwat Laut',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('desa')->insertBatch($desa_data);

        // Insert sample surat
        $surat_data = [
            [
                'user_id' => 2, // Budi Santoso
                'jenis_surat' => 'domisili',
                'keperluan' => 'Untuk melengkapi persyaratan pembuatan KTP baru setelah pindah domisili',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 3, // Siti Nurhaliza
                'jenis_surat' => 'tidak_mampu',
                'keperluan' => 'Untuk mengajukan beasiswa pendidikan anak di sekolah dasar',
                'status' => 'approved',
                'catatan_admin' => 'Surat telah disetujui dan bisa diambil di kantor kecamatan',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ]
        ];
        $this->db->table('surat')->insertBatch($surat_data);

        // Insert settings
        $settings_data = [
            [
                'key' => 'site_name',
                'value' => 'Website Resmi Desa Blanakan',
                'description' => 'Nama website',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'site_description',
                'value' => 'Portal informasi dan layanan administrasi Desa Blanakan, Kabupaten Subang, Jawa Barat',
                'description' => 'Deskripsi website',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'camat_name',
                'value' => 'Drs. H. Maman Sulaeman, M.Si',
                'description' => 'Nama Camat',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'office_address',
                'value' => 'Jl. Raya Blanakan No. 123, Blanakan, Subang, Jawa Barat 41251',
                'description' => 'Alamat kantor kecamatan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'office_phone',
                'value' => '(0260) 123-456',
                'description' => 'Telepon kantor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'office_email',
                'value' => 'kecamatan.blanakan@subangkab.go.id',
                'description' => 'Email kantor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('settings')->insertBatch($settings_data);

        echo "Sample data berhasil ditambahkan!\n";
        echo "Login Admin: email=admin@desa.com, password=password\n";
        echo "Login User: email=budi@gmail.com, password=user123\n";
    }
}