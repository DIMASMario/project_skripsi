<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul', 'slug', 'konten', 'gambar', 'kategori', 'status', 'author_id', 'views'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'judul' => 'required|min_length[5]|max_length[255]',
        'konten' => 'required|min_length[50]',
        'kategori' => 'required',
        'status' => 'required|in_list[draft,publish]'
    ];

    protected $validationMessages = [
        'judul' => [
            'required' => 'Judul berita harus diisi',
            'min_length' => 'Judul minimal 5 karakter',
            'max_length' => 'Judul maksimal 255 karakter'
        ],
        'konten' => [
            'required' => 'Konten berita harus diisi',
            'min_length' => 'Konten minimal 50 karakter'
        ],
        'kategori' => [
            'required' => 'Kategori harus dipilih'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus draft atau publish'
        ]
    ];

    public function getBeritaPublish($limit = null, $kategori = null)
    {
        $builder = $this->select('berita.*, users.nama_lengkap as author')
                       ->join('users', 'users.id = berita.author_id')
                       ->where('berita.status', 'publish');
        
        if ($kategori) {
            $builder->where('berita.kategori', $kategori);
        }
        
        $builder->orderBy('berita.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    public function getBeritaBySlug($slug)
    {
        return $this->select('berita.*, users.nama_lengkap as author')
                   ->join('users', 'users.id = berita.author_id')
                   ->where('berita.slug', $slug)
                   ->where('berita.status', 'publish')
                   ->first();
    }

    public function createSlug($judul)
    {
        $slug = url_title(strtolower($judul), '-', true);
        
        // Check if slug exists
        $count = $this->where('slug', $slug)->countAllResults();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }

    public function getBeritaTerkait($kategori, $excludeId, $limit = 5)
    {
        return $this->select('berita.*, users.nama_lengkap as author')
                   ->join('users', 'users.id = berita.author_id')
                   ->where('berita.status', 'publish')
                   ->where('berita.kategori', $kategori)
                   ->where('berita.id !=', $excludeId)
                   ->orderBy('berita.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get berita dengan pagination dan caching untuk performance
     */
    public function getBeritaPaginated($page = 1, $perPage = 10, $kategori = null)
    {
        $builder = $this->select('berita.*, users.nama_lengkap as author')
                       ->join('users', 'users.id = berita.author_id')
                       ->where('berita.status', 'publish');
        
        if ($kategori) {
            $builder->where('berita.kategori', $kategori);
        }
        
        return $builder->orderBy('berita.created_at', 'DESC')
                      ->paginate($perPage, 'default', $page);
    }

    /**
     * Get statistik berita untuk dashboard
     */
    public function getStatistikBerita()
    {
        return [
            'total' => $this->countAll(),
            'publish' => $this->where('status', 'publish')->countAllResults(),
            'draft' => $this->where('status', 'draft')->countAllResults(),
            'by_kategori' => $this->select('kategori, COUNT(*) as jumlah')
                                 ->where('status', 'publish')
                                 ->groupBy('kategori')
                                 ->findAll()
        ];
    }

    /**
     * Increment views counter dengan optimized update
     */
    public function incrementViews($id)
    {
        return $this->db->query('UPDATE berita SET views = views + 1 WHERE id = ?', [$id]);
    }

    /**
     * Get berita populer berdasarkan views
     */
    public function getBeritaPopuler($limit = 5)
    {
        return $this->select('berita.*, users.nama_lengkap as author')
                   ->join('users', 'users.id = berita.author_id')
                   ->where('berita.status', 'publish')
                   ->orderBy('berita.views', 'DESC')
                   ->orderBy('berita.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get berita terbaru untuk homepage dengan data lengkap
     */
    public function getBeritaTerbaru($limit = 3)
    {
        $berita = $this->select('berita.*, users.nama_lengkap as author')
                      ->join('users', 'users.id = berita.author_id', 'left')
                      ->where('berita.status', 'publish')
                      ->orderBy('berita.created_at', 'DESC')
                      ->limit($limit)
                      ->findAll();

        // Jika tidak ada berita di database, return sample data
        if (empty($berita)) {
            return $this->getSampleBerita($limit);
        }

        // Format data untuk tampilan frontend
        foreach ($berita as &$item) {
            $item = $this->formatBeritaItem($item);
        }

        return $berita;
    }

    /**
     * Get total berita count
     */
    public function getTotalBeritaCount()
    {
        return $this->countAllResults();
    }

    /**
     * Get berita published count
     */
    public function getTotalBeritaPublished()
    {
        return $this->where('status', 'publish')->countAllResults();
    }

    /**
     * Get berita by kategori
     */
    public function getBeritaByKategori($kategori, $limit = null)
    {
        $builder = $this->select('berita.*, users.nama_lengkap as author')
                       ->join('users', 'users.id = berita.author_id')
                       ->where('berita.status', 'publish')
                       ->where('berita.kategori', $kategori);
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->orderBy('berita.created_at', 'DESC')->findAll();
    }

    /**
     * Get berita with pagination and search
     */
    public function getBeritaWithSearch($perPage = 20, $page = 1, $search = null, $kategori = null)
    {
        $builder = $this->select('berita.*, users.nama_lengkap as author')
                       ->join('users', 'users.id = berita.author_id')
                       ->where('berita.status', 'publish');
        
        if ($search) {
            $builder->groupStart()
                   ->like('berita.judul', $search)
                   ->orLike('berita.konten', $search)
                   ->groupEnd();
        }
        
        if ($kategori) {
            $builder->where('berita.kategori', $kategori);
        }
        
        return $builder->orderBy('berita.created_at', 'DESC')
                      ->paginate($perPage, 'default', $page);
    }

    /**
     * Get berita admin paginated
     */
    public function getBeritaAdminPaginated($perPage = 20, $page = 1, $status = null, $search = null)
    {
        $builder = $this->select('berita.*, users.nama_lengkap as author')
                       ->join('users', 'users.id = berita.author_id');
        
        if ($status) {
            $builder->where('berita.status', $status);
        }
        
        if ($search) {
            $builder->like('berita.judul', $search);
        }
        
        return $builder->orderBy('berita.created_at', 'DESC')
                      ->paginate($perPage, 'default', $page);
    }

    /**
     * Sample data berita untuk demo
     */
    private function getSampleBerita($limit = 3)
    {
        $sampleBerita = [
            [
                'id' => 1,
                'judul' => 'Penyaluran Bantuan Langsung Tunai (BLT) Tahap III',
                'slug' => 'penyaluran-blt-tahap-iii',
                'konten' => 'Pemerintah desa telah menyalurkan bantuan kepada 250 keluarga penerima manfaat untuk membantu meringankan beban ekonomi warga di masa pandemi. Program ini merupakan bagian dari upaya pemerintah dalam mendukung kesejahteraan masyarakat desa.',
                'kategori' => 'bantuan-sosial',
                'author' => 'Admin Desa',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'views' => 245
            ],
            [
                'id' => 2,
                'judul' => 'Peresmian Jalan Usaha Tani di Dusun Sukamaju',
                'slug' => 'peresmian-jalan-usaha-tani',
                'konten' => 'Jalan baru ini diharapkan dapat mempermudah akses petani ke lahan pertanian mereka serta meningkatkan produktivitas hasil tani. Pembangunan jalan sepanjang 2 km ini menggunakan dana desa dengan total anggaran 500 juta rupiah.',
                'kategori' => 'pembangunan',
                'author' => 'Admin Desa',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'views' => 189
            ],
            [
                'id' => 3,
                'judul' => 'Pelatihan Digital Marketing untuk UMKM Desa',
                'slug' => 'pelatihan-digital-marketing-umkm',
                'konten' => 'UMKM desa kini dibekali kemampuan pemasaran online untuk meningkatkan penjualan produk lokal. Pelatihan ini meliputi penggunaan media sosial, marketplace, dan strategi promosi digital yang efektif.',
                'kategori' => 'kegiatan',
                'author' => 'Admin Desa',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'views' => 156
            ]
        ];

        $result = [];
        for ($i = 0; $i < min($limit, count($sampleBerita)); $i++) {
            $result[] = $this->formatBeritaItem($sampleBerita[$i]);
        }

        return $result;
    }

    /**
     * Format berita item untuk tampilan
     */
    private function formatBeritaItem($item)
    {
        // Format tanggal
        $item['formatted_date'] = date('d M Y', strtotime($item['created_at']));
        $item['formatted_date_full'] = date('d F Y', strtotime($item['created_at']));
        
        // Generate excerpt dari konten
        $item['excerpt'] = $this->generateExcerpt($item['konten'], 150);
        
        // URL gambar dengan fallback
        if (!empty($item['gambar']) && file_exists(FCPATH . 'uploads/berita/' . $item['gambar'])) {
            $item['image_url'] = base_url('uploads/berita/' . $item['gambar']);
        } else {
            $item['image_url'] = $this->getDefaultNewsImage();
        }
        
        // Generate URL detail berita
        $item['detail_url'] = base_url('berita/' . $item['slug']);
        
        // Format kategori untuk display
        $item['kategori_display'] = ucwords(str_replace(['_', '-'], ' ', $item['kategori']));
        
        // Author fallback
        if (empty($item['author'])) {
            $item['author'] = 'Admin Desa';
        }

        return $item;
    }

    /**
     * Generate excerpt dari konten HTML
     */
    private function generateExcerpt($content, $length = 150)
    {
        // Strip HTML tags
        $text = strip_tags($content);
        
        // Truncate text
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length);
            $text = substr($text, 0, strrpos($text, ' ')) . '...';
        }
        
        return $text;
    }

    /**
     * Get default image jika berita tidak punya gambar
     */
    private function getDefaultNewsImage()
    {
        // Daftar default images yang bisa digunakan
        $defaultImages = [
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDIbkO__vZ3X957UTifyUEHBw6GLAr7jdxIE6ce5vLujdM2UEOEEOBXyyb4NVt-rexx4jD0p1cLP-ZVATPbBcEIjPGKADNX3kYvGikBzqukwRytXJDdzSCsTicIRpZ_ZIPvJVUKqvpTC7lipvpKcUz5BObqzbDfA5d0fMOE9sfpyPlCuHGorCl8frNs37AL5Q6ZFQJFavylFrVna_7-Enm86WnafVwV9yKPGwkwvtS_S4OsArQpK4eg4H-j9mhpJ46S7_KAT_e_-7Bz',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDMTBFL-PjEjI0GQdXcpg6KyhsTA5fhNNcmXNPjt0mpZkrG1vBcWB3BEIm28Sef3G7MgqdmUNCN8wxA3VMdawxYeBxBnK88newlQf3-zUe1DnzDcz8EnJ_gMnRafxDeJEUQutIH6bpNWBGuzt9WHvQPWaBthw_DWfD_tS_vB6Clq0FMP33Ly3uF4iJmQY2UnGQ1rBg5q_r49p9qLTsI-wfWgq8skWqgoKCdVat9dMWy6_T1CF9TBKVkxT2FUUGA8dLOKEYjbwc1W-pv',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
        ];
        
        return $defaultImages[array_rand($defaultImages)];
    }
}