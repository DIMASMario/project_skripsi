<?php

namespace App\Models;

use CodeIgniter\Model;

class KodeRegistrasiModel extends Model
{
    protected $table = 'kode_registrasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_registrasi',
        'rt',
        'rw',
        'nomor_urut',
        'status',
        'user_id',
        'keterangan',
        'created_by',
        'used_at',
        'expired_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'kode_registrasi' => 'required|is_unique[kode_registrasi.kode_registrasi]',
        'rt' => 'required|max_length[3]',
        'rw' => 'required|max_length[3]',
        'nomor_urut' => 'required|integer',
        'status' => 'in_list[belum_digunakan,sudah_digunakan,kadaluarsa]'
    ];
    
    protected $validationMessages = [
        'kode_registrasi' => [
            'required' => 'Kode registrasi harus diisi',
            'is_unique' => 'Kode registrasi sudah ada'
        ]
    ];
    
    /**
     * Generate kode registrasi dengan format BLK-RT03RW02-0007
     * 
     * @param string $rt RT (contoh: "03")
     * @param string $rw RW (contoh: "02")
     * @param int $nomorUrut Nomor urut warga
     * @return string Kode registrasi
     */
    public function generateKode($rt, $rw, $nomorUrut)
    {
        // Format RT dan RW dengan leading zero
        $rtFormatted = str_pad($rt, 2, '0', STR_PAD_LEFT);
        $rwFormatted = str_pad($rw, 2, '0', STR_PAD_LEFT);
        $urutFormatted = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
        
        return "BLK-RT{$rtFormatted}RW{$rwFormatted}-{$urutFormatted}";
    }
    
    /**
     * Buat kode registrasi baru
     * 
     * @param string $rt
     * @param string $rw
     * @param int $jumlah Jumlah kode yang akan dibuat
     * @param int $createdBy ID admin yang membuat
     * @param string|null $keterangan
     * @return array Array of created codes
     */
    public function buatKodeBatch($rt, $rw, $jumlah = 1, $createdBy = null, $keterangan = null)
    {
        $createdCodes = [];
        
        // Ambil nomor urut terakhir untuk RT/RW ini
        $lastCode = $this->where('rt', $rt)
                         ->where('rw', $rw)
                         ->orderBy('nomor_urut', 'DESC')
                         ->first();
        
        $startUrut = $lastCode ? $lastCode['nomor_urut'] + 1 : 1;
        
        for ($i = 0; $i < $jumlah; $i++) {
            $nomorUrut = $startUrut + $i;
            $kode = $this->generateKode($rt, $rw, $nomorUrut);
            
            $data = [
                'kode_registrasi' => $kode,
                'rt' => $rt,
                'rw' => $rw,
                'nomor_urut' => $nomorUrut,
                'status' => 'belum_digunakan',
                'created_by' => $createdBy,
                'keterangan' => $keterangan
            ];
            
            if ($this->insert($data)) {
                $createdCodes[] = $kode;
            }
        }
        
        return $createdCodes;
    }
    
    /**
     * Validasi kode registrasi
     * 
     * @param string $kode Kode registrasi
     * @param string $rt RT yang diinput user
     * @param string $rw RW yang diinput user
     * @return array ['valid' => bool, 'message' => string, 'data' => array|null]
     */
    public function validateKode($kode, $rt, $rw)
    {
        $kodeData = $this->where('kode_registrasi', $kode)->first();
        
        if (!$kodeData) {
            return [
                'valid' => false,
                'message' => 'Kode registrasi tidak ditemukan. Silakan periksa kembali atau hubungi admin desa.',
                'data' => null
            ];
        }
        
        // Check if already used
        if ($kodeData['status'] === 'sudah_digunakan') {
            return [
                'valid' => false,
                'message' => 'Kode registrasi sudah pernah digunakan.',
                'data' => null
            ];
        }
        
        // Check if expired
        if ($kodeData['status'] === 'kadaluarsa') {
            return [
                'valid' => false,
                'message' => 'Kode registrasi sudah kadaluarsa.',
                'data' => null
            ];
        }
        
        // Check expiry date if set
        if ($kodeData['expired_at']) {
            $expiredAt = new \DateTime($kodeData['expired_at']);
            $now = new \DateTime();
            
            if ($now > $expiredAt) {
                // Mark as expired
                $this->update($kodeData['id'], ['status' => 'kadaluarsa']);
                
                return [
                    'valid' => false,
                    'message' => 'Kode registrasi sudah kadaluarsa.',
                    'data' => null
                ];
            }
        }
        
        // Validate RT/RW match
        $rtPadded = str_pad($rt, 2, '0', STR_PAD_LEFT);
        $rwPadded = str_pad($rw, 2, '0', STR_PAD_LEFT);
        $kodeDataRtPadded = str_pad($kodeData['rt'], 2, '0', STR_PAD_LEFT);
        $kodeDataRwPadded = str_pad($kodeData['rw'], 2, '0', STR_PAD_LEFT);
        
        if ($rtPadded !== $kodeDataRtPadded || $rwPadded !== $kodeDataRwPadded) {
            return [
                'valid' => false,
                'message' => "Kode registrasi tidak sesuai dengan RT/RW yang Anda masukkan. Kode ini untuk RT {$kodeData['rt']} RW {$kodeData['rw']}.",
                'data' => null
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Kode registrasi valid.',
            'data' => $kodeData
        ];
    }
    
    /**
     * Tandai kode sebagai sudah digunakan
     * 
     * @param int $kodeId ID kode registrasi
     * @param int $userId ID user yang menggunakan kode
     * @return bool
     */
    public function markAsUsed($kodeId, $userId)
    {
        return $this->update($kodeId, [
            'status' => 'sudah_digunakan',
            'user_id' => $userId,
            'used_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get statistik kode registrasi
     * 
     * @return array
     */
    public function getStatistik()
    {
        return [
            'total' => $this->countAll(),
            'belum_digunakan' => $this->where('status', 'belum_digunakan')->countAllResults(),
            'sudah_digunakan' => $this->where('status', 'sudah_digunakan')->countAllResults(),
            'kadaluarsa' => $this->where('status', 'kadaluarsa')->countAllResults()
        ];
    }
    
    /**
     * Get kode registrasi dengan filter
     * 
     * @param array $filters
     * @return array
     */
    public function getWithFilters($filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['rt'])) {
            $builder->where('rt', $filters['rt']);
        }
        
        if (!empty($filters['rw'])) {
            $builder->where('rw', $filters['rw']);
        }
        
        if (!empty($filters['search'])) {
            $builder->like('kode_registrasi', $filters['search']);
        }
        
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
    
    /**
     * Hapus kode yang kadaluarsa otomatis
     * 
     * @return int Jumlah kode yang diupdate
     */
    public function expireOldCodes()
    {
        $builder = $this->builder();
        
        return $builder->where('expired_at <', date('Y-m-d H:i:s'))
                       ->where('status', 'belum_digunakan')
                       ->update(['status' => 'kadaluarsa']);
    }
}
