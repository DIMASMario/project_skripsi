<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_desa', 'kepala_desa', 'alamat', 'deskripsi', 'foto', 
        'jumlah_penduduk', 'potensi'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nama_desa' => 'required|min_length[3]|max_length[100]',
        'kepala_desa' => 'required|min_length[3]|max_length[100]',
        'alamat' => 'required',
        'jumlah_penduduk' => 'numeric'
    ];

    public function getDesaWithStats()
    {
        return $this->select('desa.*, COUNT(users.id) as jumlah_warga_terdaftar')
                   ->join('users', 'users.alamat LIKE CONCAT("%", desa.nama_desa, "%") AND users.role = "warga" AND users.status = "disetujui"', 'left')
                   ->groupBy('desa.id')
                   ->findAll();
    }

    public function getTotalPenduduk()
    {
        $result = $this->selectSum('jumlah_penduduk')->first();
        return $result['jumlah_penduduk'] ?? 0;
    }

    public function getDesaPopuler($limit = 5)
    {
        return $this->orderBy('jumlah_penduduk', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Ambil informasi desa utama untuk profil
     * 
     * @return array|null Data desa utama atau null jika tidak ada
     */
    public function getDesaInfo()
    {
        try {
            // Coba ambil desa dengan nama "Blanakan" terlebih dahulu (desa utama)
            $result = $this->where('nama_desa', 'Blanakan')->first();
            
            // Jika tidak ada, ambil desa pertama yang tersedia
            if (!$result) {
                $result = $this->first();
            }
            
            // Jika masih tidak ada, return null untuk memicu fallback data
            return $result;
            
        } catch (\Exception $e) {
            // Log error dan return null
            log_message('error', 'Error in getDesaInfo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ambil desa berdasarkan nama
     */
    public function getDesaByNama($nama)
    {
        return $this->where('nama_desa', $nama)->first();
    }

    /**
     * Ambil statistik desa
     */
    public function getStatistikDesa()
    {
        $totalDesa = $this->countAllResults(false);
        $totalPenduduk = $this->getTotalPenduduk();
        $rataRataPenduduk = $totalDesa > 0 ? round($totalPenduduk / $totalDesa) : 0;

        return [
            'total_desa' => $totalDesa,
            'total_penduduk' => $totalPenduduk,
            'rata_rata_penduduk' => $rataRataPenduduk
        ];
    }
}