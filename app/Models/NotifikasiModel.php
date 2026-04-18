<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'surat_id', 'tipe', 'pesan', 'status', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Tambah notifikasi baru
     */
    public function tambahNotifikasi($data)
    {
        return $this->insert([
            'user_id' => $data['user_id'] ?? null,
            'surat_id' => $data['surat_id'] ?? null,
            'tipe' => $data['tipe'],
            'pesan' => $data['pesan'],
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ambil notifikasi untuk admin
     */
    public function getNotifikasiAdmin($limit = 10)
    {
        return $this->select('notifikasi.*, users.nama_lengkap as nama_user, surat.jenis_surat')
                    ->join('users', 'users.id = notifikasi.user_id', 'left')
                    ->join('surat', 'surat.id = notifikasi.surat_id', 'left')
                    ->where('notifikasi.tipe', 'admin')
                    ->orderBy('notifikasi.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Hitung notifikasi belum dibaca untuk admin
     */
    public function countNotifikasiAdminBelumBaca()
    {
        return $this->where([
            'tipe' => 'admin',
            'status' => 'belum_dibaca'
        ])->countAllResults();
    }

    /**
     * Ambil notifikasi untuk warga
     */
    public function getNotifikasiWarga($userId, $limit = 10)
    {
        return $this->select('notifikasi.*, surat.jenis_surat')
                    ->join('surat', 'surat.id = notifikasi.surat_id', 'left')
                    ->where([
                        'notifikasi.user_id' => $userId,
                        'notifikasi.tipe' => 'user'
                    ])
                    ->orderBy('notifikasi.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Hitung notifikasi belum dibaca untuk warga
     */
    public function countNotifikasiWargaBelumBaca($userId)
    {
        return $this->where([
            'user_id' => $userId,
            'tipe' => 'user',
            'status' => 'belum_dibaca'
        ])->countAllResults();
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['status' => 'sudah_dibaca']);
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca
     */
    public function markAllAsRead($userId, $tipe)
    {
        if ($userId === null && $tipe === 'admin') {
            // Untuk admin, tandai semua notifikasi admin
            return $this->where([
                'tipe' => 'admin',
                'status' => 'belum_dibaca'
            ])->set(['status' => 'sudah_dibaca'])
              ->update();
        }
        
        return $this->where([
            'user_id' => $userId,
            'tipe' => $tipe,
            'status' => 'belum_dibaca'
        ])->set(['status' => 'sudah_dibaca'])
          ->update();
    }
    
    /**
     * Hapus notifikasi yang sudah dibaca lebih dari 24 jam
     */
    public function deleteOldReadNotifications()
    {
        $twentyFourHoursAgo = date('Y-m-d H:i:s', strtotime('-24 hours'));
        
        return $this->where('status', 'sudah_dibaca')
                    ->where('updated_at <', $twentyFourHoursAgo)
                    ->delete();
    }

    /**
     * Notifikasi surat baru untuk admin
     */
    public function notifikasiSuratBaru($suratId, $userId, $namaUser, $jenisSurat)
    {
        return $this->tambahNotifikasi([
            'user_id' => null, // Untuk admin, user_id = null
            'surat_id' => $suratId,
            'tipe' => 'admin',
            'pesan' => "Surat baru {$jenisSurat} dari {$namaUser} menunggu persetujuan."
        ]);
    }

    /**
     * Notifikasi status surat untuk warga
     */
    public function notifikasiStatusSurat($suratId, $userId, $status, $jenisSurat)
    {
        switch ($status) {
            case 'diproses':
                $pesan = "Surat {$jenisSurat} Anda sedang diproses oleh admin.";
                break;
            case 'selesai':
            case 'disetujui':
                $pesan = "Surat {$jenisSurat} Anda telah diselesaikan dan siap diambil.";
                break;
            case 'ditolak':
                $pesan = "Surat {$jenisSurat} Anda telah ditolak. Silakan hubungi admin untuk info lebih lanjut.";
                break;
            default:
                $pesan = "Status surat {$jenisSurat} Anda telah diperbarui.";
        }

        return $this->tambahNotifikasi([
            'user_id' => $userId,
            'surat_id' => $suratId,
            'tipe' => 'user',
            'pesan' => $pesan
        ]);
    }

    /**
     * Create admin notification (untuk registrasi, dll)
     * @param int|null $userId User ID (null untuk broadcast ke semua admin)
     * @param string $tipe Tipe notifikasi ('admin' atau 'user')
     * @param string $pesan Pesan notifikasi
     * @return bool
     */
    public function createAdminNotification($userId, $tipe, $pesan)
    {
        return $this->tambahNotifikasi([
            'user_id' => $userId,
            'surat_id' => null,
            'tipe' => $tipe,
            'pesan' => $pesan
        ]);
    }
}