<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table = 'pengumuman';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'judul', 'isi', 'tipe', 'prioritas', 'tanggal_mulai', 'tanggal_selesai',
        'target_audience', 'status', 'tampil_di_beranda', 'created_by', 'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'judul' => 'required|max_length[200]',
        'isi' => 'required',
        'tipe' => 'in_list[info,peringatan,urgent,biasa]',
        'prioritas' => 'in_list[tinggi,sedang,rendah]',
        'status' => 'in_list[aktif,nonaktif,draft]'
    ];

    protected $validationMessages = [
        'judul' => [
            'required' => 'Judul pengumuman wajib diisi',
            'max_length' => 'Judul maksimal 200 karakter'
        ],
        'isi' => [
            'required' => 'Isi pengumuman wajib diisi'
        ]
    ];

    public function getActivePengumuman()
    {
        return $this->where('status', 'aktif')
                    ->where('tanggal_mulai <=', date('Y-m-d'))
                    ->where('(tanggal_selesai >= "'.date('Y-m-d').'" OR tanggal_selesai IS NULL)')
                    ->orderBy('prioritas', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getPengumumanBeranda()
    {
        return $this->where('status', 'aktif')
                    ->where('tampil_di_beranda', 1)
                    ->where('tanggal_mulai <=', date('Y-m-d'))
                    ->where('(tanggal_selesai >= "'.date('Y-m-d').'" OR tanggal_selesai IS NULL)')
                    ->orderBy('prioritas', 'DESC')
                    ->limit(3)
                    ->findAll();
    }

    public function getStatistikPengumuman()
    {
        $stats = [];
        
        $stats['total'] = $this->countAllResults();
        $stats['aktif'] = $this->where('status', 'aktif')->countAllResults(false);
        $stats['draft'] = $this->where('status', 'draft')->countAllResults(false);
        $stats['beranda'] = $this->where('tampil_di_beranda', 1)
                                 ->where('status', 'aktif')
                                 ->countAllResults(false);
        
        return $stats;
    }
}