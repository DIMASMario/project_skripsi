<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_lengkap', 'nik', 'username', 'no_ktp', 'no_kk', 'tempat_lahir', 'tanggal_lahir', 
        'jenis_kelamin', 'agama', 'alamat', 'rt', 'rw', 'kelurahan_desa', 
        'kecamatan', 'kota_kabupaten', 'provinsi', 'kode_pos', 
        'email', 'no_hp', 'password', 'role', 
        'status', 'foto_ktp', 'foto_kk', 'foto_selfie', 'foto_profil',
        'foto_verifikasi_status', 'kode_registrasi_id', 'login_identifier'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Validasi untuk registrasi warga baru
     * - Wajib: Nomor KTP, Wajib upload foto verifikasi
     * - Opsional: Nomor KK, Email, Nomor HP
     */
    protected $validationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'no_ktp' => 'required|exact_length[16]|is_unique[users.no_ktp]',
        'no_kk' => 'permit_empty|exact_length[16]',
        'alamat' => 'required',
        'rt' => 'required|max_length[3]',
        'rw' => 'required|max_length[3]',
        'email' => 'permit_empty|valid_email|is_unique[users.email]',
        'no_hp' => 'permit_empty|min_length[10]|max_length[15]|is_unique[users.no_hp]',
        'password' => 'required|min_length[6]',
        'role' => 'permit_empty|in_list[admin,warga]'
    ];

    protected $validationMessages = [
        'no_ktp' => [
            'required' => 'Nomor KTP wajib diisi',
            'exact_length' => 'Nomor KTP harus 16 digit',
            'is_unique' => 'Nomor KTP sudah terdaftar'
        ],
        'email' => [
            'is_unique' => 'Email sudah terdaftar'
        ],
        'no_hp' => [
            'is_unique' => 'Nomor HP sudah terdaftar'
        ]
    ];

    protected $beforeInsert = ['hashPassword', 'setLoginIdentifier'];
    protected $beforeUpdate = ['hashPassword', 'updateLoginIdentifier'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    /**
     * Set login_identifier saat insert (email atau no_hp untuk login cepat)
     */
    protected function setLoginIdentifier(array $data)
    {
        // Prioritas: email jika ada, jika tidak pakai no_hp
        if (!empty($data['data']['email'])) {
            $data['data']['login_identifier'] = $data['data']['email'];
        } elseif (!empty($data['data']['no_hp'])) {
            $data['data']['login_identifier'] = $data['data']['no_hp'];
        }
        return $data;
    }
    
    /**
     * Update login_identifier saat update
     */
    protected function updateLoginIdentifier(array $data)
    {
        // Update login_identifier jika email atau no_hp berubah
        if (isset($data['data']['email']) || isset($data['data']['no_hp'])) {
            if (!empty($data['data']['email'])) {
                $data['data']['login_identifier'] = $data['data']['email'];
            } elseif (!empty($data['data']['no_hp'])) {
                $data['data']['login_identifier'] = $data['data']['no_hp'];
            }
        }
        return $data;
    }

    /**
     * Get user by email atau nomor HP (untuk login)
     */
    public function getUserByEmailOrPhone($identifier)
    {
        return $this->where('login_identifier', $identifier)
                   ->orWhere('email', $identifier)
                   ->orWhere('no_hp', $identifier)
                   ->first();
    }

    public function getWargaMenunggu()
    {
        return $this->where('role', 'warga')
                   ->where('status', 'menunggu')
                   ->findAll();
    }

    public function getTotalWarga()
    {
        return $this->where('role', 'warga')
                   ->where('status', 'disetujui')
                   ->countAllResults();
    }

    /**
     * Get statistik user untuk dashboard
     */
    public function getStatistikUser()
    {
        return [
            'total' => $this->where('role', 'warga')->countAllResults(),
            'menunggu' => $this->where('role', 'warga')->where('status', 'menunggu')->countAllResults(),
            'disetujui' => $this->where('role', 'warga')->where('status', 'disetujui')->countAllResults(),
            'ditolak' => $this->where('role', 'warga')->where('status', 'ditolak')->countAllResults(),
            'suspend' => $this->where('role', 'warga')->where('status', 'suspend')->countAllResults(),
        ];
    }

    /**
     * Get warga approved untuk halaman profil warga
     */
    public function getWargaApproved($limit = null, $search = null)
    {
        $builder = $this->where('role', 'warga')
                       ->where('status', 'disetujui');
        
        if ($search) {
            $builder->groupStart()
                   ->like('nama_lengkap', $search)
                   ->orLike('nik', $search)
                   ->orLike('email', $search)
                   ->groupEnd();
        }
        
        $builder->orderBy('nama_lengkap', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Get warga count by status
     */
    public function getCountByStatus($status)
    {
        return $this->where('role', 'warga')
                   ->where('status', $status)
                   ->countAllResults();
    }

    /**
     * Search warga
     */
    public function searchWarga($keyword)
    {
        return $this->where('role', 'warga')
                   ->where('status', 'disetujui')
                   ->groupStart()
                   ->like('nama_lengkap', $keyword)
                   ->orLike('nik', $keyword)
                   ->orLike('email', $keyword)
                   ->orLike('alamat', $keyword)
                   ->groupEnd()
                   ->orderBy('nama_lengkap', 'ASC')
                   ->findAll();
    }

    /**
     * Get warga paginated
     */
    public function getWargaPaginated($perPage = 20, $page = 1, $search = null)
    {
        $builder = $this->where('role', 'warga')
                       ->where('status', 'disetujui');
        
        if ($search) {
            $builder->groupStart()
                   ->like('nama_lengkap', $search)
                   ->orLike('nik', $search)
                   ->orLike('email', $search)
                   ->groupEnd();
        }
        
        return $builder->orderBy('nama_lengkap', 'ASC')
                      ->paginate($perPage, 'default', $page);
    }
}