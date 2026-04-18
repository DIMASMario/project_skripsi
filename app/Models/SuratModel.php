<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratModel extends Model
{
    protected $table = 'surat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'jenis_surat', 'nama_lengkap', 'nik', 'no_kk', 'alamat', 
        'telepon', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
        'keperluan', 'status', 'pesan_admin', 'file_surat', 'status_perkawinan'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Jenis surat yang tersedia:
     * - domisili: Surat Domisili
     * - tidak_mampu: SKTM (Surat Keterangan Tidak Mampu)
     * - kelahiran: Surat Kelahiran
     * - kematian: Surat Kematian
     * - pindah_nama: Surat Keterangan Pindah Nama
     * - usaha: SKU (Surat Keterangan Usaha)
     * - garapan: SKG (Surat Keterangan Garapan)
     * - taksiran_harga_tanah: Surat Taksiran Harga Tanah
     * - desa: SKD (Surat Keterangan Desa)
     */
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'jenis_surat' => 'required|in_list[domisili,tidak_mampu,kelahiran,kematian,pindah_nama,usaha,garapan,taksiran_harga_tanah,desa]',
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'nik' => 'required|exact_length[16]',
        'no_kk' => 'permit_empty|exact_length[16]',
        'alamat' => 'required',
        'keperluan' => 'required|min_length[10]',
        'status_perkawinan' => 'permit_empty|in_list[janda_hidup,janda_mati,duda_hidup,duda_mati,menikah,belum_menikah,cerai_hidup,cerai_mati]'
    ];

    protected $validationMessages = [
        'nik' => [
            'exact_length' => 'NIK harus 16 digit'
        ],
        'no_kk' => [
            'exact_length' => 'Nomor KK harus 16 digit'
        ],
        'jenis_surat' => [
            'in_list' => 'Jenis surat tidak valid'
        ]
    ];

    public function getSuratByUser($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getSuratWithUser()
    {
        return $this->select('surat.*, users.nama_lengkap as pemohon, users.email')
                   ->join('users', 'users.id = surat.user_id')
                   ->orderBy('surat.created_at', 'DESC')
                   ->findAll();
    }

    public function getSuratMenunggu()
    {
        return $this->select('surat.*, users.nama_lengkap as pemohon, users.email')
                   ->join('users', 'users.id = surat.user_id')
                   ->where('surat.status', 'menunggu')
                   ->orderBy('surat.created_at', 'ASC')
                   ->findAll();
    }

    public function getTotalSuratByStatus($status = null)
    {
        try {
            if ($status) {
                return $this->where('surat.status', $status)->countAllResults();
            }
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'getTotalSuratByStatus Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getJenisSuratText($jenis)
    {
        $jenis_surat = [
            'domisili' => 'Surat Keterangan Domisili',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu (SKTM)',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kematian' => 'Surat Keterangan Kematian',
            'pindah_nama' => 'Surat Keterangan Pindah Nama',
            'usaha' => 'Surat Keterangan Usaha (SKU)',
            'garapan' => 'Surat Keterangan Garapan (SKG)',
            'taksiran_harga_tanah' => 'Surat Taksiran Harga Tanah',
            'desa' => 'Surat Keterangan Desa (SKD)'
        ];

        return $jenis_surat[$jenis] ?? $jenis;
    }

    /**
     * Get list jenis surat untuk dropdown/select
     */
    public function getListJenisSurat()
    {
        return [
            'domisili' => 'Surat Keterangan Domisili',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu (SKTM)',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kematian' => 'Surat Keterangan Kematian',
            'pindah_nama' => 'Surat Keterangan Pindah Nama',
            'usaha' => 'Surat Keterangan Usaha (SKU)',
            'garapan' => 'Surat Keterangan Garapan (SKG)',
            'taksiran_harga_tanah' => 'Surat Taksiran Harga Tanah',
            'desa' => 'Surat Keterangan Desa (SKD)'
        ];
    }

    /**
     * Get list status perkawinan untuk SKD
     */
    public function getListStatusPerkawinan()
    {
        return [
            'janda_hidup' => 'Janda hidup',
            'janda_mati' => 'Janda mati',
            'duda_hidup' => 'Duda hidup',
            'duda_mati' => 'Duda mati',
            'menikah' => 'Menikah',
            'belum_menikah' => 'Belum menikah',
            'cerai_hidup' => 'Cerai hidup',
            'cerai_mati' => 'Cerai mati'
        ];
    }

    public function getLaporanBulanan($tahun, $bulan)
    {
        return $this->select('jenis_surat, status, COUNT(*) as jumlah')
                   ->where('YEAR(created_at)', $tahun)
                   ->where('MONTH(created_at)', $bulan)
                   ->groupBy(['jenis_surat', 'status'])
                   ->findAll();
    }

    /**
     * Get surat by user ID dengan eager loading untuk performance
     */
    public function getSuratByUserId($userId, $status = null)
    {
        $builder = $this->select('surat.*, users.nama_lengkap as pemohon')
                       ->join('users', 'users.id = surat.user_id')
                       ->where('surat.user_id', $userId);
        
        if ($status) {
            $builder->where('surat.status', $status);
        }
        
        return $builder->orderBy('surat.created_at', 'DESC')->findAll();
    }

    /**
     * Get statistik surat untuk dashboard dengan optimized query
     */
    public function getStatistikSurat()
    {
        return $this->select('status, COUNT(*) as jumlah')
                   ->groupBy('status')
                   ->findAll();
    }

    /**
     * Get surat menunggu dengan user info untuk admin dashboard
     */
    public function getSuratMenungguWithUser($limit = 5)
    {
        return $this->select('surat.*, users.nama_lengkap, users.email, users.no_hp')
                   ->join('users', 'users.id = surat.user_id')
                   ->where('surat.status', 'menunggu')
                   ->orderBy('surat.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get all surat counts by status
     */
    public function getCountByStatus($status = null)
    {
        if ($status) {
            return $this->where('surat.status', $status)->countAllResults();
        }
        return $this->countAllResults();
    }

    /**
     * Get surat statistics grouped by status
     */
    public function getSuratByStatusGroups()
    {
        return $this->select('status, COUNT(*) as total')
                   ->groupBy('status')
                   ->findAll();
    }

    /**
     * Get surat with user info paginated
     */
    public function getSuratWithUserPaginated($perPage = 20, $page = 1, $status = null, $search = null)
    {
        try {
            $builder = $this->select('surat.*, users.nama_lengkap, users.email, users.no_hp')
                           ->join('users', 'users.id = surat.user_id', 'left');
            
            // Only filter by status if explicitly provided and not 'semua'
            if ($status && $status !== 'semua') {
                $builder->where('surat.status', $status);
            }
            
            if ($search) {
                $builder->groupStart()
                       ->like('users.nama_lengkap', $search)
                       ->orLike('surat.jenis_surat', $search)
                       ->orLike('surat.nik', $search)
                       ->groupEnd();
            }
            
            // Log the query for debugging
            $query = $builder->getCompiledSelect();
            log_message('info', 'getSuratWithUserPaginated query: ' . $query);
            
            $result = $builder->orderBy('surat.created_at', 'DESC')
                              ->paginate($perPage, 'default', $page);
            
            log_message('info', 'getSuratWithUserPaginated result count: ' . (is_array($result) ? count($result) : 0));
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'getSuratWithUserPaginated Error: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            // Return empty array on error
            return [];
        }
    }

    /**
     * Get total surat for statistics
     */
    public function getTotalSuratCount()
    {
        return $this->countAllResults();
    }
}