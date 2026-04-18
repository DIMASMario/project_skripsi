<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table = 'kontak';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nama',
        'email', 
        'telepon',
        'subjek',
        'pesan',
        'tanggal_kirim',
        'status',
        'ip_address',
        'balasan',
        'tanggal_balas',
        'admin_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email',
        'subjek' => 'required|min_length[5]|max_length[200]',
        'pesan' => 'required|min_length[10]|max_length[1000]'
    ];
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
     * Ambil informasi kontak desa
     */
    public function getKontakInfo()
    {
        // Cek apakah tabel desa ada dan memiliki data
        $db = \Config\Database::connect();
        
        if ($db->tableExists('desa')) {
            $query = $db->query("SELECT * FROM desa LIMIT 1");
            return $query->getRowArray();
        }
        
        return null;
    }

    /**
     * Simpan pesan kontak baru
     */
    public function simpanPesan($data)
    {
        return $this->insert($data);
    }

    /**
     * Ambil semua pesan kontak
     */
    public function getAllPesan($limit = null, $offset = 0)
    {
        $builder = $this->builder();
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->orderBy('tanggal_kirim', 'DESC')->get()->getResultArray();
    }

    /**
     * Ambil pesan berdasarkan status
     */
    public function getPesanByStatus($status)
    {
        return $this->where('status', $status)
                   ->orderBy('tanggal_kirim', 'DESC')
                   ->findAll();
    }

    public function getPesanBelumDibaca()
    {
        return $this->where('status', 'baru')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getTotalPesanBelumDibaca()
    {
        return $this->where('status', 'baru')
                   ->countAllResults();
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['status' => 'diproses']);
    }

    /**
     * Update status pesan
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Balas pesan kontak
     */
    public function balasPesan($id, $balasan, $adminId)
    {
        $data = [
            'balasan' => $balasan,
            'tanggal_balas' => date('Y-m-d H:i:s'),
            'admin_id' => $adminId,
            'status' => 'dibalas'
        ];
        
        return $this->update($id, $data);
    }

    /**
     * Statistik pesan kontak
     */
    public function getStatistik()
    {
        $db = \Config\Database::connect();
        
        // Pastikan tabel ada
        if (!$db->tableExists($this->table)) {
            return [
                'total_pesan' => 0,
                'pesan_baru' => 0,
                'pesan_diproses' => 0,
                'pesan_dibalas' => 0,
                'bulan_ini' => 0
            ];
        }

        $builder = $this->builder();
        
        $stats = [];
        $stats['total_pesan'] = $builder->countAllResults(false);
        $stats['pesan_baru'] = $builder->where('status', 'baru')->countAllResults(false);
        $stats['pesan_diproses'] = $builder->where('status', 'diproses')->countAllResults(false);
        $stats['pesan_dibalas'] = $builder->where('status', 'dibalas')->countAllResults(false);
        
        // Reset builder
        $builder = $this->builder();
        $stats['bulan_ini'] = $builder->where('MONTH(tanggal_kirim)', date('n'))
                                    ->where('YEAR(tanggal_kirim)', date('Y'))
                                    ->countAllResults();
        
        return $stats;
    }
}