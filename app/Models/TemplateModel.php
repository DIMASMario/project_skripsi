<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateModel extends Model
{
    protected $table = 'template_surat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nama_template', 'jenis_surat', 'konten_template', 'variabel_template', 
        'keterangan', 'status', 'created_by', 'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_template' => 'required|max_length[100]',
        'jenis_surat' => 'required|max_length[50]',
        'konten_template' => 'required',
        'status' => 'in_list[aktif,nonaktif]'
    ];

    protected $validationMessages = [
        'nama_template' => [
            'required' => 'Nama template wajib diisi',
            'max_length' => 'Nama template maksimal 100 karakter'
        ],
        'jenis_surat' => [
            'required' => 'Jenis surat wajib diisi'
        ],
        'konten_template' => [
            'required' => 'Konten template wajib diisi'
        ]
    ];

    public function getTemplateByJenis($jenis)
    {
        return $this->where('jenis_surat', $jenis)
                    ->where('status', 'aktif')
                    ->first();
    }

    public function getActiveTemplates()
    {
        return $this->where('status', 'aktif')
                    ->orderBy('nama_template', 'ASC')
                    ->findAll();
    }
}