<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $table = 'provinsi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode', 'nama'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Get all provinces
     */
    public function getProvinsi()
    {
        return $this->findAll();
    }

    /**
     * Get kabupaten/kota by province ID
     */
    public function getKabupatenKota($provinsiId)
    {
        return $this->db->table('kabupaten_kota')
            ->where('provinsi_id', $provinsiId)
            ->get()
            ->getResultArray();
    }

    /**
     * Get kecamatan by kabupaten/kota ID  
     */
    public function getKecamatan($kabupatenKotaId)
    {
        return $this->db->table('kecamatan')
            ->where('kabupaten_kota_id', $kabupatenKotaId)
            ->get()
            ->getResultArray();
    }

    /**
     * Get kelurahan/desa by kecamatan ID
     */
    public function getKelurahanDesa($kecamatanId)
    {
        return $this->db->table('kelurahan_desa')
            ->where('kecamatan_id', $kecamatanId)
            ->get()
            ->getResultArray();
    }

    /**
     * Get full address hierarchy
     */
    public function getFullAddress($kelurahanDesaId)
    {
        return $this->db->query("
            SELECT 
                kd.nama as kelurahan_desa,
                k.nama as kecamatan,
                kk.nama as kabupaten_kota,
                p.nama as provinsi
            FROM kelurahan_desa kd
            JOIN kecamatan k ON kd.kecamatan_id = k.id
            JOIN kabupaten_kota kk ON k.kabupaten_kota_id = kk.id  
            JOIN provinsi p ON kk.provinsi_id = p.id
            WHERE kd.id = ?
        ", [$kelurahanDesaId])->getRowArray();
    }

    /**
     * Search wilayah by name
     */
    public function searchWilayah($keyword, $type = 'all')
    {
        $keyword = '%' . $keyword . '%';
        
        switch ($type) {
            case 'provinsi':
                return $this->like('nama', $keyword)->findAll();
                
            case 'kabupaten_kota':
                return $this->db->table('kabupaten_kota')
                    ->like('nama', $keyword)
                    ->get()
                    ->getResultArray();
                    
            case 'kecamatan':
                return $this->db->table('kecamatan')
                    ->like('nama', $keyword)
                    ->get()
                    ->getResultArray();
                    
            case 'kelurahan_desa':
                return $this->db->table('kelurahan_desa')
                    ->like('nama', $keyword)
                    ->get()
                    ->getResultArray();
                    
            default:
                // Search all
                $results = [];
                
                $results['provinsi'] = $this->like('nama', $keyword)->findAll();
                
                $results['kabupaten_kota'] = $this->db->table('kabupaten_kota')
                    ->like('nama', $keyword)
                    ->get()
                    ->getResultArray();
                    
                $results['kecamatan'] = $this->db->table('kecamatan')
                    ->like('nama', $keyword)
                    ->get()
                    ->getResultArray();
                    
                $results['kelurahan_desa'] = $this->db->table('kelurahan_desa')
                    ->like('nama', $keyword)
                    ->get()
                    ->getResultArray();
                
                return $results;
        }
    }
}