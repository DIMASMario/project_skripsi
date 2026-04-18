<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\DesaModel;

class BudayaWisata extends BaseController
{
    protected $beritaModel;
    protected $desaModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
        $this->desaModel = new DesaModel();
    }

    /**
     * Halaman Budaya & Wisata dengan data dinamis
     */
    public function index()
    {
        // Ambil berita kategori budaya dan wisata
        $beritaBudaya = $this->beritaModel->where('kategori', 'Budaya')
                                         ->where('status', 'publish')
                                         ->orderBy('created_at', 'DESC')
                                         ->limit(6)
                                         ->findAll();

        $beritaWisata = $this->beritaModel->where('kategori', 'Wisata')
                                         ->where('status', 'publish')
                                         ->orderBy('created_at', 'DESC')
                                         ->limit(6)
                                         ->findAll();

        // Data wisata (bisa dari tabel terpisah atau hardcoded untuk sementara)
        $wisataData = [
            [
                'id' => 1,
                'kategori' => 'WISATA ALAM',
                'nama' => 'Pantai Blanakan',
                'deskripsi' => 'Nikmati keindahan pantai utara dengan pasir dan ombak yang menenangkan.',
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
            ],
            [
                'id' => 2,
                'kategori' => 'BUDAYA',
                'nama' => 'Tradisi Sedekah Laut',
                'deskripsi' => 'Upacara adat tahunan sebagai wujud syukur para nelayan kepada laut.',
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
            ],
            [
                'id' => 3,
                'kategori' => 'KULINER',
                'nama' => 'Produk Olahan Laut',
                'deskripsi' => 'Berbagai macam olahan hasil laut segar, dari ikan asin hingga kerupuk.',
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
            ],
            [
                'id' => 4,
                'kategori' => 'WISATA ALAM',
                'nama' => 'Konservasi Hutan Mangrove',
                'deskripsi' => 'Jelajahi ekosistem mangrove yang penting bagi kelestarian lingkungan pesisir.',
                'gambar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
            ]
        ];

        // Convert wisata_data untuk gallery_items format
        $galleryItems = [];
        foreach ($wisataData as $wisata) {
            $galleryItems[] = [
                'id' => $wisata['id'],
                'name' => $wisata['nama'],
                'title' => $wisata['nama'],
                'description' => $wisata['deskripsi'],
                'category' => strtolower(str_replace(' ', '-', $wisata['kategori'])),
                'category_label' => $wisata['kategori'],
                'image' => $wisata['gambar'],
                'image_url' => $wisata['gambar'],
                'date' => date('Y-m-d')
            ];
        }

        $data = [
            'title' => 'Budaya & Pariwisata - Website Desa Blanakan',
            'meta_description' => 'Jelajahi kekayaan budaya dan destinasi wisata Desa Blanakan yang menakjubkan',
            'berita_budaya' => $beritaBudaya,
            'berita_wisata' => $beritaWisata,
            'wisata_data' => $wisataData,
            'gallery_items' => $galleryItems,
            'hero' => [
                'title' => 'Budaya & Pariwisata Desa Blanakan',
                'subtitle' => 'Temukan pesona tradisi dan keindahan alam yang mempesona di desa kami.',
                'background' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
            ]
        ];

        return view('frontend/budaya_wisata_new', $data);
    }

    /**
     * Detail wisata berdasarkan ID
     */
    public function detail($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Untuk sementara menggunakan data hardcoded
        // Nanti bisa diganti dengan data dari database
        $wisataDetail = [
            1 => [
                'nama' => 'Pantai Blanakan',
                'kategori' => 'Wisata Alam',
                'deskripsi' => 'Pantai Blanakan menawarkan keindahan pantai utara dengan pasir putih dan ombak yang tenang. Cocok untuk bersantai bersama keluarga sambil menikmati sunset yang menawan.',
                'fasilitas' => ['Area Parkir', 'Warung Makan', 'Gazebo', 'Toilet Umum'],
                'lokasi' => 'Desa Blanakan, Kecamatan Blanakan',
                'jam_buka' => '24 Jam',
                'harga_tiket' => 'Gratis',
                'kontak' => '0813-xxxx-xxxx',
                'gambar' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk'
                ]
            ]
        ];

        $wisata = $wisataDetail[$id] ?? null;

        if (!$wisata) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $wisata['nama'] . ' - Wisata Desa Blanakan',
            'wisata' => $wisata,
            'wisata_lain' => array_filter($wisataDetail, fn($key) => $key != $id, ARRAY_FILTER_USE_KEY)
        ];

        return view('frontend/wisata_detail', $data);
    }
}