<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriModel extends Model
{
    protected $table = 'galeri';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul', 'deskripsi', 'gambar', 'album', 'created_at'
    ];
    protected $useTimestamps = false;

    protected $validationRules = [
        'judul' => 'required|min_length[3]|max_length[100]',
        'gambar' => 'required',
        'album' => 'required|max_length[50]'
    ];

    public function getGaleriByAlbum($album)
    {
        return $this->where('album', $album)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getAlbumList()
    {
        return $this->select('album, COUNT(*) as jumlah_foto')
                   ->groupBy('album')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getFotoTerbaru($limit = 6)
    {
        return $this->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}