<?php

namespace App\Controllers;

class Galeri extends BaseController
{
    /**
     * Halaman Galeri dengan data dinamis dari database
     */
    public function index()
    {
        $galeriModel = new \App\Models\GaleriModel();
        
        // Ambil data galeri dari database
        $galeriData = [];
        $galeriFromDB = $galeriModel->orderBy('created_at', 'DESC')->findAll();
        
        foreach ($galeriFromDB as $item) {
            $galeriData[] = [
                'id' => $item['id'],
                'name' => $item['judul'],
                'nama' => $item['judul'],
                'title' => $item['judul'],
                'file' => $item['gambar'],
                'url' => base_url('uploads/galeri/' . $item['gambar']),
                'image' => base_url('uploads/galeri/' . $item['gambar']),
                'image_url' => base_url('uploads/galeri/' . $item['gambar']),
                'description' => $item['deskripsi'] ?? 'Galeri: ' . $item['judul'],
                'date' => date('Y-m-d', strtotime($item['created_at'])),
                'category' => strtolower(str_replace(' ', '-', $item['album'])),
                'category_label' => $item['album'],
                'kategori' => $item['album']
            ];
        }

        // Fallback data jika database kosong
        if (empty($galeriData)) {
            $galeriData = $this->getFallbackGaleriData();
        }

        // Kategorikan galeri
        $kategoriGaleri = [];
        foreach ($galeriData as $item) {
            $kategoriGaleri[$item['kategori']][] = $item;
        }

        $data = [
            'title' => 'Galeri - Website Desa Blanakan',
            'meta_description' => 'Dokumentasi kegiatan, acara, dan momen bersejarah dalam perjalanan pembangunan Desa Blanakan',
            'galeri_data' => $galeriData,
            'gallery_items' => $galeriData,
            'kategori_galeri' => $kategoriGaleri,
            'total_gambar' => count($galeriData)
        ];

        return view('frontend/galeri_new', $data);
    }

    /**
     * Tentukan kategori berdasarkan nama file
     */
    private function getKategoriFromFilename($filename)
    {
        $filename = strtolower($filename);
        
        if (strpos($filename, 'kegiatan') !== false || strpos($filename, 'activity') !== false) {
            return 'Kegiatan';
        } elseif (strpos($filename, 'acara') !== false || strpos($filename, 'event') !== false) {
            return 'Acara';
        } elseif (strpos($filename, 'infrastruktur') !== false || strpos($filename, 'pembangunan') !== false) {
            return 'Infrastruktur';
        } elseif (strpos($filename, 'wisata') !== false || strpos($filename, 'tourism') !== false) {
            return 'Wisata';
        } else {
            return 'Umum';
        }
    }

    /**
     * Data galeri fallback jika folder kosong
     */
    private function getFallbackGaleriData()
    {
        $defaultData = [
            [
                'nama' => 'Kegiatan Gotong Royong',
                'file' => 'kegiatan_gotong_royong.jpg',
                'url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                'kategori' => 'Kegiatan'
            ],
            [
                'nama' => 'Rapat Koordinasi Desa',
                'file' => 'rapat_koordinasi.jpg',
                'url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                'kategori' => 'Acara'
            ],
            [
                'nama' => 'Pembangunan Infrastruktur',
                'file' => 'infrastruktur_pembangunan.jpg',
                'url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                'kategori' => 'Infrastruktur'
            ],
            [
                'nama' => 'Pantai Wisata Blanakan',
                'file' => 'wisata_pantai.jpg',
                'url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                'kategori' => 'Wisata'
            ],
            [
                'nama' => 'Jembatan Desa',
                'file' => 'jembatan_desa.jpg', 
                'url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                'kategori' => 'Infrastruktur'
            ],
            [
                'nama' => 'Galactic Nebula',
                'file' => 'galactic_nebula.jpg',
                'url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJI1LCYLnv8l0QuuAtNLk',
                'kategori' => 'Umum'
            ]
        ];
        
        // Transform data untuk view compatibility
        $transformedData = [];
        foreach ($defaultData as $item) {
            $transformedData[] = [
                'name' => $item['nama'],
                'nama' => $item['nama'],
                'title' => $item['nama'],
                'file' => $item['file'],
                'url' => $item['url'],
                'image' => $item['url'],
                'image_url' => $item['url'],
                'description' => 'Galeri: ' . $item['nama'],
                'date' => date('Y-m-d'),
                'category' => strtolower(str_replace(' ', '-', $item['kategori'])),
                'category_label' => $item['kategori'],
                'kategori' => $item['kategori']
            ];
        }
        
        return $transformedData;
    }
}