<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSuratTableNewJenis extends Migration
{
    public function up()
    {
        // Modifikasi kolom jenis_surat untuk menambah tipe surat baru
        // Jenis surat yang tersedia:
        // - domisili: Surat Domisili
        // - tidak_mampu: SKTM (Surat Keterangan Tidak Mampu)
        // - kelahiran: Surat Kelahiran
        // - kematian: Surat Kematian
        // - pindah_nama: Surat Keterangan Pindah Nama
        // - usaha: SKU (Surat Keterangan Usaha)
        // - garapan: SKG (Surat Keterangan Garapan)
        // - taksiran_harga_tanah: Surat Taksiran Harga Tanah
        // - desa: SKD (Surat Keterangan Desa)
        
        $this->forge->modifyColumn('surat', [
            'jenis_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['domisili', 'tidak_mampu', 'kelahiran', 'kematian', 'pindah_nama', 'usaha', 'garapan', 'taksiran_harga_tanah', 'desa'],
            ],
        ]);

        // Tambah kolom untuk status perkawinan (khusus untuk SKD)
        if (!$this->db->fieldExists('status_perkawinan', 'surat')) {
            $this->forge->addColumn('surat', [
                'status_perkawinan' => [
                    'type'       => 'ENUM',
                    'constraint' => ['janda_hidup', 'janda_mati', 'duda_hidup', 'duda_mati', 'menikah', 'belum_menikah', 'cerai_hidup', 'cerai_mati'],
                    'null'       => true,
                    'comment'    => 'Hanya diisi untuk SKD (Surat Keterangan Desa)'
                ],
            ]);
        }

        // Tambah kolom untuk nomor KK (opsional)
        if (!$this->db->fieldExists('no_kk', 'surat')) {
            $this->forge->addColumn('surat', [
                'no_kk' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 16,
                    'null'       => true,
                    'comment'    => 'Nomor Kartu Keluarga (opsional)'
                ],
            ]);
        }

        // Update status enum untuk menambah status 'selesai'
        $this->forge->modifyColumn('surat', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'diproses', 'selesai', 'ditolak'],
                'default'    => 'menunggu',
            ],
        ]);
    }

    public function down()
    {
        // Revert ke jenis_surat lama
        $this->forge->modifyColumn('surat', [
            'jenis_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['domisili', 'tidak_mampu', 'pengantar_nikah', 'usaha', 'pengantar_skck'],
            ],
        ]);

        // Hapus kolom baru
        if ($this->db->fieldExists('status_perkawinan', 'surat')) {
            $this->forge->dropColumn('surat', 'status_perkawinan');
        }

        if ($this->db->fieldExists('no_kk', 'surat')) {
            $this->forge->dropColumn('surat', 'no_kk');
        }

        // Revert status
        $this->forge->modifyColumn('surat', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'disetujui', 'ditolak'],
                'default'    => 'menunggu',
            ],
        ]);
    }
}
