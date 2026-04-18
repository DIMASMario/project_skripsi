<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\DesaModel;
use App\Models\PengumumanModel;
use App\Models\SettingsModel;
use App\Models\StatistikDesaModel;
use App\Models\UserModel;

class Home extends BaseController
{
    protected $beritaModel;
    protected $desaModel;
    protected $pengumumanModel;
    protected $settingsModel;
    protected $statistikDesaModel;
    protected $userModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
        $this->desaModel = new DesaModel();
        $this->pengumumanModel = new PengumumanModel();
        $this->settingsModel = new SettingsModel();
        $this->statistikDesaModel = new StatistikDesaModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cache key untuk homepage data
        $cache = \Config\Services::cache();
        $cacheKey = 'homepage_data_v4_carousel';
        
        // Try to get cached data first (disabled for development)
        $data = null; // $cache->get($cacheKey);
        
        if (!$data) {
            // Generate data sesuai dengan tampilan frontend baru
            $data = [
                'title' => 'Website Pelayanan Digital Desa Blanakan',
                'meta_description' => 'Portal digital Desa Blanakan - Layanan online, informasi terkini, dan transparansi pemerintahan desa.',
                
                // Hero Section Data - Multiple Slides
                'hero_slides' => [
                    [
                        'title' => 'Selamat Datang di Portal Digital Desa Blanakan',
                        'subtitle' => 'Mewujudkan Desa Maju dan Sejahtera melalui teknologi, transparansi, dan partisipasi warga.',
                        'background_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCT8ZFX6TuGYeZUl9JdP3G-DjhyDFb7MxWGQ-CPLZykN_eXDr43pcFvj-wOgLcJc2H1G_PDhMnCYYdUFVOn6tPDDo6kOwbyJg9NQwLB1ic_kCmzWdtVsJa8lT8CWiwUbCuLUDTvKZvF-mTUn2CsUy0ccps-jcKfVlh1pWR6O0cRmeFUYsZaz2onir2SzMZCiM458HpOrLcBHadeeiR-T-tmrCjTGct9x7CWqtJRdyfzYcbise6ujnsyXVq5jx0x1e3kDgIrTgyphkiT',
                        'cta_text' => 'Jelajahi Desa',
                        'cta_url' => base_url('/profil-desa')
                    ],
                    [
                        'title' => 'Layanan Digital Terdepan',
                        'subtitle' => 'Nikmati kemudahan layanan administrasi desa secara online. Cepat, mudah, dan terpercaya.',
                        'background_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                        'cta_text' => 'Layanan Online',
                        'cta_url' => base_url('/layanan-online')
                    ],
                    [
                        'title' => 'Budaya & Wisata Blanakan',
                        'subtitle' => 'Jelajahi keindahan alam, budaya lokal, dan destinasi wisata unggulan Desa Blanakan.',
                        'background_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWD5NamN36L9ycz4aUvcYOtMt_mWW1TYUz7PTEkepInjh4AFAMWqjI7PEA5fkyRhOa6763CGO2vmnqv9IyRH6um4kwaPDyB9-iKeVegzclswgEUesGGF4mikwDZuDXD8TVNvlGwcI1RlY-dxrggJ9j-Ykmkb9er_10uevO9yuTUSKfh1eqn2J8KAk0hpoJcrXZadDYa-NS-HBbRrjTSKGdbkB2PFF-i9GChJxyBGSEe2qOYvbKyc2rC3rbJJ1LCYLnv8l0QuuAtNLk',
                        'cta_text' => 'Jelajahi Wisata',
                        'cta_url' => base_url('/budaya-wisata')
                    ]
                ],
                // Compatibility - Default hero untuk backward compatibility
                'hero' => [
                    'title' => 'Selamat Datang di Portal Digital Desa Blanakan',
                    'subtitle' => 'Mewujudkan Desa Maju dan Sejahtera melalui teknologi, transparansi, dan partisipasi warga.',
                    'background_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCT8ZFX6TuGYeZUl9JdP3G-DjhyDFb7MxWGQ-CPLZykN_eXDr43pcFvj-wOgLcJc2H1G_PDhMnCYYdUFVOn6tPDDo6kOwbyJg9NQwLB1ic_kCmzWdtVsJa8lT8CWiwUbCuLUDTvKZvF-mTUn2CsUy0ccps-jcKfVlh1pWR6O0cRmeFUYsZaz2onir2SzMZCiM458HpOrLcBHadeeiR-T-tmrCjTGct9x7CWqtJRdyfzYcbise6ujnsyXVq5jx0x1e3kDgIrTgyphkiT',
                    'cta_text' => 'Jelajahi Desa',
                    'cta_url' => base_url('/profil-desa')
                ],
                
                // Berita Terbaru (3 artikel)
                'berita_terbaru' => $this->beritaModel->getBeritaTerbaru(3),
                
                // Pengumuman Aktif untuk Beranda
                'pengumuman_beranda' => $this->pengumumanModel
                    ->where('status', 'aktif')
                    ->where('tampil_di_beranda', 1)
                    ->groupStart()
                        ->where('tanggal_selesai >=', date('Y-m-d'))
                        ->orWhere('tanggal_selesai', null)
                    ->groupEnd()
                    ->orderBy('prioritas', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll(3),
                
                // Statistik Desa dalam Angka - Dynamic from Database
                'statistik_desa' => $this->statistikDesaModel->getStatistikForHomepage(),
                
                // Layanan Digital untuk Warga
                'layanan_digital' => [
                    [
                        'title' => 'Surat Pengantar',
                        'description' => 'Ajukan surat pengantar untuk berbagai keperluan administrasi Anda.',
                        'icon' => 'description',
                        'url' => base_url('/layanan-online'),
                        'color' => 'accent'
                    ],
                    [
                        'title' => 'Lapor Warga',
                        'description' => 'Sampaikan aspirasi, keluhan, atau laporan Anda langsung kepada pemerintah desa.',
                        'icon' => 'report',
                        'url' => base_url('/kontak'),
                        'color' => 'accent'
                    ],
                    [
                        'title' => 'Informasi Bansos',
                        'description' => 'Cek status dan informasi terbaru mengenai bantuan sosial dari pemerintah.',
                        'icon' => 'volunteer_activism',
                        'url' => base_url('/berita?kategori=bantuan-sosial'),
                        'color' => 'accent'
                    ]
                ]
            ];
            
            // Cache untuk 30 menit
            $cache->save($cacheKey, $data, 1800);
        }

        return view('frontend/home_new', $data);
    }

    public function profil()
    {
        // Caching untuk performa yang lebih baik
        $cacheKey = 'profil_desa_data_v2';
        
        if (cache($cacheKey)) {
            $profilData = cache($cacheKey);
        } else {
            $profilData = $this->getProfilDesaData();
            cache()->save($cacheKey, $profilData, 1800); // Cache selama 30 menit
        }
        
        $data = [
            'title' => 'Profil Desa Blanakan - Website Desa Blanakan',
            'profil_data' => $profilData
        ];

        return view('frontend/profil', $data);
    }
    
    private function getProfilDesaData()
    {
        return [
            'nama_desa' => 'Blanakan',
            
            // Data Sejarah Desa
            'sejarah' => [
                'deskripsi' => 'Desa Blanakan memiliki sejarah panjang yang berakar dari tradisi pesisir dan agraris. Berawal dari sebuah perkampungan nelayan kecil, desa ini berkembang pesat seiring dengan pembukaan lahan pertanian yang subur. Nama "Blanakan" diyakini berasal dari kata lokal yang berarti muara pertemuan air tawar dan air laut, mencerminkan letak geografisnya yang unik. Dari masa ke masa, Desa Blanakan terus bertransformasi menjadi pusat kegiatan ekonomi dan sosial yang dinamis bagi warganya, sambil tetap menjaga kearifan lokal yang diwariskan turun-temurun.',
                'timeline' => [
                    [
                        'tahun' => '1965',
                        'judul' => 'Pembentukan Desa',
                        'deskripsi' => 'Desa Tanjungbaru resmi dibentuk melalui pemekaran dari desa induk dengan SK Gubernur'
                    ],
                    [
                        'tahun' => '1975',
                        'judul' => 'Pembangunan Infrastruktur',
                        'deskripsi' => 'Pembangunan jalan utama, jembatan, dan fasilitas umum pertama'
                    ],
                    [
                        'tahun' => '1985',
                        'judul' => 'Pengembangan Sektor Perikanan',
                        'deskripsi' => 'Pembentukan kelompok nelayan dan pengembangan industri perikanan'
                    ],
                    [
                        'tahun' => '2000',
                        'judul' => 'Era Modernisasi',
                        'deskripsi' => 'Pembangunan fasilitas pendidikan, kesehatan, dan teknologi informasi'
                    ],
                    [
                        'tahun' => '2020-sekarang',
                        'judul' => 'Digitalisasi Pelayanan',
                        'deskripsi' => 'Implementasi sistem pelayanan publik digital dan smart village'
                    ]
                ]
            ],
            
            // Visi Desa
            'visi' => 'Terwujudnya Desa Blanakan yang Maju, Mandiri, Sejahtera, dan Berbudaya Berlandaskan Iman dan Taqwa.',
            
            // Misi Desa
            'misi' => [
                'Meningkatkan kualitas sumber daya manusia melalui pendidikan dan kesehatan.',
                'Mengembangkan potensi ekonomi desa berbasis agraris dan kelautan.',
                'Meningkatkan kualitas infrastruktur dan pelayanan publik yang prima.',
                'Menciptakan tata kelola pemerintahan desa yang bersih, transparan, dan akuntabel.',
                'Melestarikan dan mengembangkan nilai-nilai budaya dan kearifan lokal.'
            ],
            
            // Struktur Organisasi
            'struktur' => [
                'kepala_desa' => [
                    'nama' => 'Hj. Siti Aisyah, S.Pd.',
                    'periode' => 'Periode 2024-2029'
                ],
                'perangkat' => [
                    [
                        'nama' => 'Ahmad Budi Santoso, S.Kom.',
                        'jabatan' => 'Sekretaris Desa'
                    ],
                    [
                        'nama' => 'Joko Susilo',
                        'jabatan' => 'Kaur Keuangan'
                    ],
                    [
                        'nama' => 'Bambang Irawan',
                        'jabatan' => 'Kaur Umum & Perencanaan'
                    ],
                    [
                        'nama' => 'Dewi Lestari, S.IP.',
                        'jabatan' => 'Kasi Pemerintahan'
                    ],
                    [
                        'nama' => 'Rina Anggraini',
                        'jabatan' => 'Kasi Kesejahteraan'
                    ]
                ]
            ],
            
            // Data Perangkat Desa Detail
            'perangkat_desa' => [
                [
                    'nama' => 'Bapak H. Ahmad Fauzi, S.Sos',
                    'jabatan' => 'Kepala Desa',
                    'pendidikan' => 'S1 Ilmu Sosial dan Politik',
                    'pengalaman' => 'Mantan Ketua BPD, aktif di organisasi masyarakat'
                ],
                [
                    'nama' => 'Bapak Suherman, S.AP',
                    'jabatan' => 'Sekretaris Desa',
                    'pendidikan' => 'S1 Administrasi Publik',
                    'pengalaman' => 'PNS dengan pengalaman 15 tahun di pemerintahan'
                ],
                [
                    'nama' => 'Ibu Siti Aisyah, S.E',
                    'jabatan' => 'Kaur Keuangan',
                    'pendidikan' => 'S1 Ekonomi',
                    'pengalaman' => 'Mantan staf bank, ahli dalam pengelolaan keuangan'
                ],
                [
                    'nama' => 'Bapak Rahmat Hidayat',
                    'jabatan' => 'Kaur Tata Usaha & Umum',
                    'pendidikan' => 'SMA/Sederajat',
                    'pengalaman' => 'Berpengalaman 10 tahun dalam administrasi desa'
                ],
                [
                    'nama' => 'Bapak Dedi Supriadi',
                    'jabatan' => 'Kasi Pemerintahan',
                    'pendidikan' => 'D3 Pemerintahan',
                    'pengalaman' => 'Ahli dalam bidang administrasi kependudukan'
                ],
                [
                    'nama' => 'Ibu Nunung Nurjanah',
                    'jabatan' => 'Kasi Kesejahteraan',
                    'pendidikan' => 'S1 Kesehatan Masyarakat',
                    'pengalaman' => 'Bidan desa, aktif dalam program kesejahteraan'
                ],
                [
                    'nama' => 'Bapak Agus Salim',
                    'jabatan' => 'Kasi Pelayanan',
                    'pendidikan' => 'SMA/Sederajat',
                    'pengalaman' => 'Berpengalaman dalam pelayanan publik'
                ],
                [
                    'nama' => 'Bapak Maman Suryaman',
                    'jabatan' => 'Kepala Dusun 1',
                    'pendidikan' => 'SMA/Sederajat',
                    'pengalaman' => 'Tokoh masyarakat, ketua kelompok nelayan'
                ],
                [
                    'nama' => 'Bapak Cecep Hermawan',
                    'jabatan' => 'Kepala Dusun 2',
                    'pendidikan' => 'SMA/Sederajat',
                    'pengalaman' => 'Pengurus koperasi, aktif dalam pemberdayaan ekonomi'
                ],
                [
                    'nama' => 'Ibu Iyah Rohayah',
                    'jabatan' => 'Kepala Dusun 3',
                    'pendidikan' => 'SMA/Sederajat',
                    'pengalaman' => 'Ketua PKK desa, pemberdayaan perempuan'
                ]
            ]
        ];
    }

    public function dataDesa()
    {
        $data = [
            'title' => 'Statistik Desa Blanakan - Website Desa Blanakan',
            'meta_description' => 'Data visual interaktif mengenai demografi, ekonomi, dan sarana prasarana Desa Blanakan',
            
            // Demographics Data
            'demographics' => [
                'total_population' => 8540,
                'total_families' => 2750,
                'population_density' => 562,
                'area' => 15.2,
                'male_count' => 4320,
                'female_count' => 4220,
                'male_percentage' => 50.6,
                'female_percentage' => 49.4,
                'age_groups' => [
                    [
                        'range' => '0-4 tahun',
                        'count' => 512,
                        'percentage' => 6.0
                    ],
                    [
                        'range' => '5-14 tahun',
                        'count' => 1367,
                        'percentage' => 16.0
                    ],
                    [
                        'range' => '15-24 tahun',
                        'count' => 1196,
                        'percentage' => 14.0
                    ],
                    [
                        'range' => '25-39 tahun',
                        'count' => 2220,
                        'percentage' => 26.0
                    ],
                    [
                        'range' => '40-59 tahun',
                        'count' => 2562,
                        'percentage' => 30.0
                    ],
                    [
                        'range' => '60+ tahun',
                        'count' => 683,
                        'percentage' => 8.0
                    ]
                ]
            ],
            
            // Economics Data
            'economics' => [
                'occupations' => [
                    [
                        'name' => 'Petani',
                        'count' => 1850,
                        'percentage' => 35.2
                    ],
                    [
                        'name' => 'Nelayan',
                        'count' => 920,
                        'percentage' => 17.5
                    ],
                    [
                        'name' => 'Pedagang',
                        'count' => 680,
                        'percentage' => 12.9
                    ],
                    [
                        'name' => 'Buruh',
                        'count' => 590,
                        'percentage' => 11.2
                    ],
                    [
                        'name' => 'PNS/TNI/Polri',
                        'count' => 420,
                        'percentage' => 8.0
                    ],
                    [
                        'name' => 'Wiraswasta',
                        'count' => 380,
                        'percentage' => 7.2
                    ],
                    [
                        'name' => 'Lainnya',
                        'count' => 410,
                        'percentage' => 8.0
                    ]
                ],
                'education' => [
                    [
                        'level' => 'Tidak Sekolah',
                        'count' => 342,
                        'percentage' => 4.0
                    ],
                    [
                        'level' => 'SD/MI',
                        'count' => 2562,
                        'percentage' => 30.0
                    ],
                    [
                        'level' => 'SMP/MTs',
                        'count' => 2220,
                        'percentage' => 26.0
                    ],
                    [
                        'level' => 'SMA/SMK',
                        'count' => 2391,
                        'percentage' => 28.0
                    ],
                    [
                        'level' => 'Diploma',
                        'count' => 512,
                        'percentage' => 6.0
                    ],
                    [
                        'level' => 'Sarjana+',
                        'count' => 513,
                        'percentage' => 6.0
                    ]
                ]
            ],
            
            // Infrastructure Data
            'infrastructure' => [
                'facilities' => [
                    [
                        'name' => 'Pendidikan',
                        'icon' => 'school',
                        'count' => 8
                    ],
                    [
                        'name' => 'Kesehatan',
                        'icon' => 'health_and_safety',
                        'count' => 5
                    ],
                    [
                        'name' => 'Tempat Ibadah',
                        'icon' => 'mosque',
                        'count' => 12
                    ],
                    [
                        'name' => 'Pasar',
                        'icon' => 'storefront',
                        'count' => 2
                    ],
                    [
                        'name' => 'Balai Desa',
                        'icon' => 'location_city',
                        'count' => 1
                    ],
                    [
                        'name' => 'Posyandu',
                        'icon' => 'medical_services',
                        'count' => 6
                    ]
                ],
                'rt_count' => 28,
                'rw_count' => 8,
                'dusun_count' => 4
            ]
        ];

        return view('frontend/data_desa_new', $data);
    }

    public function budayaWisata()
    {
        $data = [
            'title' => 'Budaya & Pariwisata Desa Blanakan - Website Desa Blanakan',
            'meta_description' => 'Jelajahi keunikan perpaduan alam, budaya, dan kearifan lokal Desa Blanakan',
            
            // Hero Section Data
            'hero' => [
                'title' => 'Pesona Budaya & Wisata Desa Blanakan',
                'subtitle' => 'Jelajahi keunikan perpaduan alam, budaya, dan kearifan lokal yang ditawarkan desa kami.',
                'cta_text' => 'Jelajahi Sekarang',
                'background_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmR9S6ytJhCDIlz_TWXQgOBG2Y1LqqWmlTAV5XOQbTw9ppjfQTJgVLc5atxWngwEaNWVEMNMgVkSV4osqYRqXFMkTVsmvoU1Z5hOs7wvZv8S_tjO6TwKgL9saY5mbhpk6vsH_NZIfCWUTWvyuuiMDQeNmRlUPF_6M1hJXP4yj2nmH6_RPkHwkbeUzlJ3T0Yb5bJsCwhAxYh-yjILVA7qLPvDsPHrCBsRKCZRepF5Gorl7IOoa_xtz3y7TPrBdbV7NFSde4EMcCuA_H'
            ],
            
            // Gallery Items
            'gallery_items' => [
                [
                    'category' => 'wisata',
                    'category_label' => 'Wisata Alam',
                    'title' => 'Pantai Blanakan',
                    'description' => 'Nikmati keindahan pantai utara dengan pasir dan ombak yang menenangkan.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCcSOcMZYG-3G39FkaQp1PjZ7_wU8v5kkoOJ41MgCUTovson_SnChxAS3yN6xpMEAYkTTuhyaersiIO5hbMAAMjDS22CRuzbXdV5vyXHj45K0c_W6nTYXC2skD58XtyLpxN1XrCM74GvuNz4Ps6stQ-d_GmZ_KN8QxTBOXAQyXbtTyUj8pHCMyM65XjcK8wLJeECT8MboS6MSsDnERt0wOofV88ijc9OSX7m6T8LU11xjUmAA9higdCdG85pMC85cO6b0LgjAA200o3',
                    'slug' => 'pantai-blanakan'
                ],
                [
                    'category' => 'budaya',
                    'category_label' => 'Budaya',
                    'title' => 'Tradisi Sedekah Laut',
                    'description' => 'Upacara adat tahunan sebagai wujud syukur para nelayan kepada laut.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA2NcMiWZhdJFLhE6cOcEBQloUTB6Eu_Y-4U_FdOvVTfXDuyuxq66voSNcrFaFwtRROyICu9_1Xwi53Y5n_8lJIjfhDNEn7co4mi7dlG_aUBH9NZK8zTrlsMmHSx3nA4-x09NiNssBfehJaNmZIGQ61sV8Dq0TqzNx8GLvMS-fC1EGEV8_8-uzuWemtCl5UEcmIcV14wCiK266NSaCE_k6Ja-8dIdBu1A5zfAj0XZYJUbVfmoUdxu8SoW6ZKAXAEArMM0cOMzXcAzmN'
                ],
                [
                    'category' => 'kuliner',
                    'category_label' => 'Kuliner',
                    'title' => 'Produk Olahan Laut',
                    'description' => 'Berbagai macam olahan hasil laut segar, dari ikan asin hingga kerupuk.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCDpAVaILEsUDF3ED2rKNPMDP_NUSTg4WJL3A-LVuSSlktnJlnO2Wmd-JB0Ire7GPYuYt6wBAMreWBMKDEvE-JBIbS-9srV_T9yekuBMgAR-4NjKlz7lWfMuR8coZ7SWPq3Z2p6w0bR0uWwIP9J-iITdVn-jRJpBUFfey3dXL9EKxKyQ9tUabDd2UElRDm17DtyY78x7Xl66-qqVUVVcm7ffB6qVvir2spSGzfg_V1NmDX0hCAtmEPBSNQY2nAjyotQDxNGTkj-O_CC'
                ],
                [
                    'category' => 'wisata',
                    'category_label' => 'Wisata Alam',
                    'title' => 'Konservasi Hutan Mangrove',
                    'description' => 'Jelajahi ekosistem mangrove yang penting bagi kelestarian lingkungan pesisir.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD5f_gCRTdQLXbbFrOjwNcdDa0QaTtSXQz0LkubsXGKOEVU0eQBZCqSGC0lz4CaPnuQfhYG1HeVv2MDkp7r0rlBGTy1kJG7nWm-OvFO4I2IoLhwhccMTF5e1Feoce1ppfZeVdR8IIiWrANHzDGJmgm3pthQWxMQK8Zd_FPjFQgnT1H6pN8_8VJkfNWg4QqQwct-WLTl27_0FzoA4OLY-ywYbgzxbehCBcBXS5x_bNhSnnfTBrC_zCYTvkxGdRbnHAVAuRFmkbm54awm',
                    'slug' => 'hutan-mangrove'
                ],
                [
                    'category' => 'budaya',
                    'category_label' => 'Budaya',
                    'title' => 'Kesenian Tari Lokal',
                    'description' => 'Saksikan pertunjukan tari tradisional yang menceritakan kisah-kisah lokal.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA3jBRXhCWeqS7-_QAr_61TLvX7BURiPEGCqZgR7iaprvuxJNGnUXUSD7xU44IWiaZ5CdDski6z4yCdKLavsmhmwS3zUiIUmsJaTrfYDTlY3N_QFQMQsDiSisCXXPTVaQD5I7u5wqdpeemaLOaAjXkxa2AXLqrOABORAQlRpg4MPs5HvHwAHikCuwL0jVOIGKfbl41hco0TkUegAzZ86TepATsArf_4DkkCEONYchiSQ6PApIVHNCQaJumKhQd4TV7XXGTauoQxPrJo'
                ],
                [
                    'category' => 'kerajinan',
                    'category_label' => 'Kerajinan',
                    'title' => 'Kerajinan Tangan',
                    'description' => 'Beli oleh-oleh kerajinan unik yang dibuat oleh pengrajin lokal.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBY3Lgo36frLjJB0OSyvCtO-A3TA7WkYPQh_ksGFdOjBXFPC5xPQGNJbcaQYcuXa1qPQ5lNlrVyf1VRGlS-eAzsJw0BgXhu4t5ZpWaaWBc3eOKHBXFIFhd5NOLLEcYa5y36va9-hveLNB7JZ6u7tuzU0fxe7efMAqMZWhazo2f_yfOfimB2X_3J1d-zNZMEWjCVhx-c77oB4zfHAm14haUuZAZ3n7AaWNQ0HuSXJ-1CCFpCpd6ojkz9x9b7k16Y_cM3JPsDocdjGbOX'
                ],
                [
                    'category' => 'wisata',
                    'category_label' => 'Wisata',
                    'title' => 'Dermaga Nelayan Lokal',
                    'description' => 'Lihat aktivitas para nelayan dan beli ikan segar langsung dari sumbernya.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBXxgeqUNKwdsG8hNjoJOLWIEbgjMRvBSEJm8M8LR7nJmOAUm5U9OUG7mAHDLOsvvQl-6RpoRRAR2bpdTIecrgPfoCROngodGiaty6hTdbwfUdSK3slnQl5ARXH1zOgRyq2GLsyJReqNP-xlU7-Lwe0xXC7aG4eoZQKFm-1TUHQXvwbx_APXCmKHrN0oxpXqZgWjqkYf1PyV7HCcnCCTQJ66oj-3scTBRjttir48YSXYw_HFEXTt-UaH4LrQnExcg1eAdHVMwwbZEs7'
                ],
                [
                    'category' => 'kuliner',
                    'category_label' => 'Kuliner',
                    'title' => 'Warung Kuliner Lokal',
                    'description' => 'Cicipi masakan khas Blanakan yang lezat dan otentik di warung-warung lokal.',
                    'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6nDc-ngbGaX1cLFkJnsNj6l9wF-F5uxvIOG913Vg3G1O3nfipEpIwAdK4sHB-F_9MKfo5IXNY7KBtjCGEHDc3Ra_ZvNOaW1HIOn7_gfdLesk5qNGxxdhGlM-57ZJ9tLuzayTAjd5ZMChSEk3GYpjpn8pDpA0suqE3335PfFHDk4pln_BH1d6AEH6ME27c4QTW4y86A5t7eQv-3_FsS37TXSUDHpHQA0_D3NNqB3yhQ0tFUBHkc_k55UTXcsHCNA8DjSRR2JcFEHfj'
                ],
                [
                    'category' => 'budaya',
                    'category_label' => 'Budaya',
                    'title' => 'Festival Budaya Tahunan',
                    'description' => 'Rayakan keberagaman budaya dalam festival tahunan desa.',
                    'image' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'
                ],
                [
                    'category' => 'wisata',
                    'category_label' => 'Wisata Alam',
                    'title' => 'Perkebunan Kelapa',
                    'description' => 'Kunjungi perkebunan kelapa yang menjadi ciri khas desa pesisir.',
                    'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'
                ],
                [
                    'category' => 'kerajinan',
                    'category_label' => 'Kerajinan',
                    'title' => 'Anyaman Bambu Tradisional',
                    'description' => 'Pelajari teknik anyaman bambu yang diwariskan turun temurun.',
                    'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'
                ],
                [
                    'category' => 'kuliner',
                    'category_label' => 'Kuliner',
                    'title' => 'Kue Tradisional Desa',
                    'description' => 'Rasakan kelezatan kue tradisional yang dibuat dengan resep turun temurun.',
                    'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'
                ]
            ]
        ];

        return view('frontend/budaya_wisata_new', $data);
    }

    public function wisataDetail($slug = null)
    {
        if (!$slug) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Wisata tidak ditemukan');
        }

        // Data sample untuk detail wisata
        $wisataData = [
            'pantai-blanakan' => [
                'title' => 'Pantai Blanakan',
                'subtitle' => 'Nikmati keindahan pesisir utara dengan kuliner laut yang khas.',
                'tags' => ['Wisata Alam', 'Kuliner'],
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCo7nTN6_7sfs2vARVMnIo2WKzHxdSKA0QBqxh5vmn95gph2oXvWgYJylY2QHt6NM0BYnaPn1OVDNFIX2mfFPU5v4TIMDBjIscTKlcf6a1vfcAY2FY9R5UtCtbec90KG0LudjpXSMEjjL-X8CnJPXqS7qrfOKJxZiprp_lHG7nV-JMTj4VAXx461BJJJReS2DdZdtSCDI8O-Pi_z0JvsSC1C8u9EKJX5lGSzAmwsUMBM_8hIrbO4g_cEjwsrJF6pJaKOOHWOHqDfrEO',
                'description' => [
                    'Pantai Blanakan merupakan salah satu destinasi wisata andalan di pesisir utara Jawa Barat. Terkenal dengan pemandangan hutan mangrove yang asri serta aktivitas nelayan yang khas, pantai ini menawarkan pengalaman yang unik bagi para pengunjung.',
                    'Selain keindahan alamnya, Pantai Blanakan juga menjadi surga bagi para pecinta kuliner laut. Berbagai hidangan laut segar, mulai dari ikan bakar, cumi, udang, hingga kepiting, dapat dinikmati langsung di warung-warung makan yang berjejer di sepanjang pantai. Keunikan lain dari tempat ini adalah adanya penangkaran buaya dan satwa lain yang menjadi daya tarik edukatif bagi keluarga.'
                ],
                'gallery' => [
                    ['url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDe746syHLBQ7dafUHSsynaKXqagcjmSgcL6LR1fGQVZXASW8KeWOFTbsL9wLm2HV1U1bs14mDZJcN17ytCLYMlDWkUampYBWxodN8bxjSePjtXQdRVHRRewAybe77Jp80N9ItdBipEMO3Rek6k9QiVKhl7PQu9prA_ID-Np8HP4s7hLbXKvKEA3j0-d87QRukJQIYmK5dSKHfCq2TpxSYG0YxoNwAYaE8m1gdnY88VAoM0LW4pWM65PHIUSOPekTdv-kDSo7GCCjdy', 'alt' => 'Hidangan ikan bakar segar dengan sayuran'],
                    ['url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuClthuM-8NQQ52UdVIyCQ7nGGcpMSnZG0_szHTP7YinQ4URlojPMV9qRz21Tqg-TUuz0kf2kS4DJAJQ2PFfaYnBv8XBkT_kvJIdtxQOOz7-F6kV5j_3aq69ZxInz40PIGMbEmGXEGnw10IxsgbSPL9pePt8k-O9zDgaWcP4_D0ngoWKJH5dqBj9IjTAeMHfBlkU9PKKi7POdXFQpa5Tuexk-UdXp9UHnoUc_Ea2J1B1xqPAJWxaABTs4ooxSDgkdmSjMHp-_p2yeJdz', 'alt' => 'Pemandangan hutan mangrove dari jembatan kayu'],
                    ['url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuANIS2p43Z8Kkb4fcumasq4EXh22PascKec8WPuYjk431zR2m9bdVhPdzofY-FTPhZ-v_4hLFviyamxCd8RPvYFOKHO_SpvgWMiERauHP0HDlyFObsGYB9kN2RD4VrNDEFfeFH1RfmAPZNK-iCGDiS0DOJocNONRWaNhg7Mc3nZ-gcFMFy6q7xNJEboXnVG_omnpXlHvuhA1unQi4wpLFqjvMX9PbC-OxlFxbLMe5Lcf9tFZ2LBEFMzqrgVc2sIgYPHLM12haSxverS', 'alt' => 'Perahu nelayan kembali ke pantai saat senja'],
                    ['url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBA7bDdvOFyDdKLmF_FUT-MiSeFhaoDz8ZM-rEi7TtEGfCvNo4aBo2rXE0TjSKzydiM-IEp_NfD8DchLVL0SxjH9mSd8-gMlWrIORj5_eaTxEM2QWiPEs6Nx8ep3wbLp6x5VOEtrkAHc9YWL2dFRd3_GMh4z0fEznl74AtQRIEh5T5Jg22Etfjad48EjMmvgdy4fxlE1bmJwKVkTLim1JTi68P33w0Wa4v127YRTrEx0hLMDO7kdnePVmEIxP6JGcODFJK4HyXFotIA', 'alt' => 'Close up kepiting masak di piring'],
                    ['url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA7F1L6GYK4s0m0xu6TDYAVtwfHRfOLCcXT-sQQztc_aByqmoSHT31rd5D_NmX_07meF27Ty3pDFFLOG-AFLW3VzOQQA4THDAOfWwT21idVMrJFxvlvxKpbT2eZSykLwhgaOqGYGtLL-DYsQj69VJLWs9i9G7Erx0R9hWA1I80-lyoJc3ACB9AusWq6A3MfwfP41QP5XNDMNogRIwAu3KNSJyaPfAMWhDfx_R5iYI2xpS3lZdScgb6FfrGTN20pt1hVc4QAgO59f5vH', 'alt' => 'Keluarga berjalan di pantai berpasir'],
                    ['url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80', 'alt' => 'Sunset di pantai Blanakan'],
                    ['url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80', 'alt' => 'Warung makan seafood']
                ],
                'activities' => [
                    ['icon' => 'restaurant', 'name' => 'Kuliner Seafood', 'description' => 'Menikmati hidangan laut segar'],
                    ['icon' => 'camera_alt', 'name' => 'Fotografi', 'description' => 'Spot foto pemandangan pantai'],
                    ['icon' => 'directions_boat', 'name' => 'Naik Perahu', 'description' => 'Berkeliling area mangrove'],
                    ['icon' => 'pets', 'name' => 'Melihat Satwa', 'description' => 'Mengunjungi penangkaran buaya']
                ],
                'info' => [
                    'hours' => 'Setiap Hari, 08:00 - 18:00 WIB',
                    'price' => 'Rp 10.000 / orang',
                    'contact' => '0812-3456-7890',
                    'facilities' => 'Area Parkir, Toilet, Mushola, Warung Makan'
                ],
                'location' => [
                    'embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.253081156886!2d107.84200684789512!3d-6.222379768565552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6935398a6350f5%3A0x6b158025251a37c4!2sBlanakan%2C%20Subang%20Regency%2C%20West%20Java!5e0!3m2!1sen!2sid!4f13.1!5m2!1sen!2sid',
                    'google_maps_url' => 'https://maps.google.com/?q=Blanakan,+Subang,+West+Java'
                ],
                'related_places' => [
                    ['slug' => 'hutan-mangrove', 'name' => 'Hutan Mangrove', 'category' => 'Wisata Alam', 'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80'],
                    ['slug' => 'dermaga-nelayan', 'name' => 'Dermaga Nelayan', 'category' => 'Wisata', 'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80']
                ]
            ],
            // Tambahan data wisata lain bisa ditambahkan di sini
            'hutan-mangrove' => [
                'title' => 'Konservasi Hutan Mangrove',
                'subtitle' => 'Jelajahi ekosistem mangrove yang penting bagi kelestarian lingkungan pesisir.',
                'tags' => ['Wisata Alam', 'Edukasi'],
                'main_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD5f_gCRTdQLXbbFrOjwNcdDa0QaTtSXQz0LkubsXGKOEVU0eQBZCqSGC0lz4CaPnuQfhYG1HeVv2MDkp7r0rlBGTy1kJG7nWm-OvFO4I2IoLhwhccMTF5e1Feoce1ppfZeVdR8IIiWrANHzDGJmgm3pthQWxMQK8Zd_FPjFQgnT1H6pN8_8VJkfNWg4QqQwct-WLTl27_0FzoA4OLY-ywYbgzxbehCBcBXS5x_bNhSnnfTBrC_zCYTvkxGdRbnHAVAuRFmkbm54awm',
                'description' => [
                    'Kawasan konservasi hutan mangrove Blanakan merupakan salah satu ekosistem pesisir yang paling penting di Jawa Barat. Hutan mangrove ini tidak hanya berperan sebagai pelindung pantai dari abrasi, tetapi juga menjadi habitat bagi berbagai jenis flora dan fauna endemik.',
                    'Pengunjung dapat menikmati wisata edukasi sambil berjalan di jembatan kayu yang menembus hutan mangrove. Aktivitas bird watching, fotografi alam, dan edukasi lingkungan menjadi daya tarik utama tempat ini.'
                ],
                'gallery' => [
                    ['url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD5f_gCRTdQLXbbFrOjwNcdDa0QaTtSXQz0LkubsXGKOEVU0eQBZCqSGC0lz4CaPnuQfhYG1HeVv2MDkp7r0rlBGTy1kJG7nWm-OvFO4I2IoLhwhccMTF5e1Feoce1ppfZeVdR8IIiWrANHzDGJmgm3pthQWxMQK8Zd_FPjFQgnT1H6pN8_8VJkfNWg4QqQwct-WLTl27_0FzoA4OLY-ywYbgzxbehCBcBXS5x_bNhSnnfTBrC_zCYTvkxGdRbnHAVAuRFmkbm54awm', 'alt' => 'Hutan mangrove hijau dengan akar yang terlihat di air']
                ],
                'activities' => [
                    ['icon' => 'nature_people', 'name' => 'Edukasi Lingkungan', 'description' => 'Belajar tentang ekosistem mangrove'],
                    ['icon' => 'visibility', 'name' => 'Bird Watching', 'description' => 'Mengamati burung-burung di habitat aslinya'],
                    ['icon' => 'camera_alt', 'name' => 'Fotografi Alam', 'description' => 'Mengabadikan keindahan hutan mangrove']
                ],
                'info' => [
                    'hours' => 'Setiap Hari, 07:00 - 17:00 WIB',
                    'price' => 'Rp 5.000 / orang',
                    'contact' => '0812-3456-7891',
                    'facilities' => 'Jembatan Kayu, Area Parkir, Toilet, Pos Jaga'
                ],
                'location' => [
                    'embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.253081156886!2d107.84200684789512!3d-6.222379768565552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6935398a6350f5%3A0x6b158025251a37c4!2sBlanakan%2C%20Subang%20Regency%2C%20West%20Java!5e0!3m2!1sen!2sid!4f13.1!5m2!1sen!2sid',
                    'google_maps_url' => 'https://maps.google.com/?q=Blanakan,+Subang,+West+Java'
                ],
                'related_places' => [
                    ['slug' => 'pantai-blanakan', 'name' => 'Pantai Blanakan', 'category' => 'Wisata Alam', 'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80']
                ]
            ]
        ];

        if (!isset($wisataData[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Wisata tidak ditemukan');
        }

        $data = [
            'title' => $wisataData[$slug]['title'] . ' - Website Desa Blanakan',
            'meta_description' => $wisataData[$slug]['subtitle'],
            'detail' => $wisataData[$slug]
        ];

        return view('frontend/wisata_detail', $data);
    }

    public function kontak()
    {
        $data = [
            'title' => 'Kontak Kami - Website Desa Tanjung Baru',
            'meta_description' => 'Hubungi Pemerintah Desa Tanjung Baru untuk layanan, informasi, dan komunikasi dengan warga.'
        ];

        return view('frontend/kontak_new', $data);
    }

    public function kirimKontak()
    {
        // Validate form data
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'subjek' => 'required|min_length[5]|max_length[200]',
            'pesan' => 'required|min_length[10]|max_length[1000]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        // Get form data
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'subjek' => $this->request->getPost('subjek'),
            'pesan' => $this->request->getPost('pesan'),
            'tanggal_kirim' => date('Y-m-d H:i:s')
        ];

        // In a real application, you would:
        // 1. Save to database
        // 2. Send email notification to admin
        // 3. Send confirmation email to sender
        
        // For now, we'll just simulate success
        log_message('info', 'Pesan kontak diterima dari: ' . $data['email'] . ' - ' . $data['subjek']);

        return redirect()->to('/kontak')->with('success', 'Pesan Anda telah berhasil dikirim. Terima kasih, kami akan segera merespons.');
    }

    public function berita()
    {
        log_message('info', '=== BERITA PAGE ACCESSED ===');
        
        $kategori = $this->request->getGet('kategori');
        $search = $this->request->getGet('search');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 12;
        
        log_message('info', 'Kategori filter: ' . ($kategori ?? 'none'));
        log_message('info', 'Search query: ' . ($search ?? 'none'));
        
        // Ambil data berita yang sudah dipublish dari database
        $beritaBuilder = $this->beritaModel->where('status', 'publish');
        
        // Filter by search
        if ($search) {
            $beritaBuilder->groupStart()
                ->like('judul', $search)
                ->orLike('konten', $search)
                ->groupEnd();
        }
        
        // Filter by kategori (hanya untuk berita, bukan pengumuman)
        if ($kategori && strtolower($kategori) !== 'pengumuman') {
            $beritaBuilder->where('kategori', $kategori);
        }
        
        $beritaFromDB = $beritaBuilder->orderBy('created_at', 'DESC')->findAll();
        
        log_message('info', 'Berita from DB: ' . count($beritaFromDB));
        
        // Ambil data pengumuman aktif dari database menggunakan method model
        $pengumumanFromDB = [];
        if (!$kategori || strtolower($kategori) === 'pengumuman') {
            // Gunakan method getActivePengumuman dari model
            $pengumumanFromDB = $this->pengumumanModel->getActivePengumuman();
            
            log_message('info', 'Pengumuman query executed. Found: ' . count($pengumumanFromDB));
            
            // DEBUG: Output langsung
            echo "<!-- DEBUG: Pengumuman count from DB: " . count($pengumumanFromDB) . " -->\n";
            if (count($pengumumanFromDB) > 0) {
                echo "<!-- DEBUG: First pengumuman: " . json_encode($pengumumanFromDB[0]) . " -->\n";
            }
            echo "<!-- DEBUG: Today date: " . date('Y-m-d') . " -->\n";
        } else {
            log_message('info', 'Pengumuman skipped due to kategori filter: ' . $kategori);
        }
        
        // Debug logging
        log_message('info', 'Berita Page - Total Berita: ' . count($beritaFromDB));
        log_message('info', 'Berita Page - Total Pengumuman: ' . count($pengumumanFromDB));
        log_message('info', 'Berita Page - Today: ' . date('Y-m-d'));
        if (count($pengumumanFromDB) > 0) {
            log_message('info', 'Berita Page - First Pengumuman: ' . json_encode($pengumumanFromDB[0]));
        }
        
        // Transform berita ke format unified
        $allItems = [];
        foreach ($beritaFromDB as $berita) {
            $allItems[] = [
                'id' => $berita['id'],
                'judul' => $berita['judul'],
                'slug' => $berita['slug'],
                'konten' => $berita['konten'],
                'kategori' => $berita['kategori'],
                'gambar' => $berita['gambar'],
                'tanggal_publikasi' => $berita['created_at'],
                'penulis' => 'Admin Desa',
                'views' => $berita['views'] ?? 0,
                'type' => 'berita',
                'created_timestamp' => strtotime($berita['created_at'])
            ];
        }
        
        // Transform pengumuman ke format unified
        foreach ($pengumumanFromDB as $pengumuman) {
            $allItems[] = [
                'id' => 'pengumuman-' . $pengumuman['id'],
                'judul' => $pengumuman['judul'],
                'slug' => 'pengumuman-' . $pengumuman['id'],
                'konten' => $pengumuman['isi'],
                'kategori' => 'Pengumuman',
                'gambar' => null, // Pengumuman tidak punya gambar
                'tanggal_publikasi' => $pengumuman['created_at'],
                'penulis' => 'Admin Desa',
                'views' => 0,
                'type' => 'pengumuman',
                'prioritas' => $pengumuman['prioritas'] ?? 'sedang',
                'tipe' => $pengumuman['tipe'] ?? 'info',
                'created_timestamp' => strtotime($pengumuman['created_at'])
            ];
        }
        
        // Log setelah transform
        log_message('info', 'Total items after transform: ' . count($allItems));
        log_message('info', 'Items breakdown: Berita=' . count($beritaFromDB) . ', Pengumuman=' . count($pengumumanFromDB));
        
        // DEBUG: Output langsung
        echo "<!-- DEBUG: Total items after transform: " . count($allItems) . " -->\n";
        echo "<!-- DEBUG: Breakdown - Berita: " . count($beritaFromDB) . ", Pengumuman: " . count($pengumumanFromDB) . " -->\n";
        foreach ($allItems as $idx => $item) {
            echo "<!-- DEBUG Item $idx: type=" . $item['type'] . ", judul=" . $item['judul'] . " -->\n";
        }
        
        // Sort by tanggal terbaru
        usort($allItems, function($a, $b) {
            return $b['created_timestamp'] - $a['created_timestamp'];
        });
        
        // Pagination
        $totalItems = count($allItems);
        $offset = ($page - 1) * $perPage;
        $beritaData = array_slice($allItems, $offset, $perPage);
        
        log_message('info', 'After pagination - beritaData count: ' . count($beritaData));
        
        // Get unique kategori from database
        $kategoriFromDB = $this->beritaModel->select('kategori')
            ->where('status', 'publish')
            ->distinct()
            ->findAll();
        $kategoriList = array_column($kategoriFromDB, 'kategori');
        // Tambah kategori "Pengumuman"
        if (!in_array('Pengumuman', $kategoriList)) {
            $kategoriList[] = 'Pengumuman';
        }
        
        $data = [
            'title' => 'Berita & Pengumuman - Website Desa Blanakan',
            'meta_description' => 'Informasi terkini seputar kegiatan, berita, dan pengumuman resmi dari Desa Blanakan',
            'berita' => $beritaData,
            'kategori_aktif' => $kategori,
            'search_query' => $search,
            'kategori_list' => $kategoriList,
            // Simulasi pager object
            'pager' => (object) [
                'getPageCount' => function() use ($totalItems, $perPage) { 
                    return ceil($totalItems / $perPage); 
                },
                'hasPrevious' => function() use ($page) { 
                    return $page > 1; 
                },
                'hasNext' => function() use ($page, $totalItems, $perPage) { 
                    return $page < ceil($totalItems / $perPage); 
                },
                'getPrevious' => function() use ($page, $kategori, $search) {
                    $url = base_url('berita?page=' . ($page - 1));
                    if ($kategori) $url .= '&kategori=' . $kategori;
                    if ($search) $url .= '&search=' . urlencode($search);
                    return $url;
                },
                'getNext' => function() use ($page, $kategori, $search) {
                    $url = base_url('berita?page=' . ($page + 1));
                    if ($kategori) $url .= '&kategori=' . $kategori;
                    if ($search) $url .= '&search=' . urlencode($search);
                    return $url;
                },
                'links' => function() use ($page, $totalItems, $perPage, $kategori, $search) {
                    $links = [];
                    $totalPages = ceil($totalItems / $perPage);
                    for ($i = 1; $i <= min($totalPages, 5); $i++) {
                        $url = base_url('berita?page=' . $i);
                        if ($kategori) $url .= '&kategori=' . $kategori;
                        if ($search) $url .= '&search=' . urlencode($search);
                        $links[] = [
                            'uri' => $url,
                            'title' => $i,
                            'active' => $i == $page
                        ];
                    }
                    return $links;
                }
            ]
        ];

        return view('frontend/berita_new', $data);
    }

    public function detailBerita($slug)
    {
        // Sample data untuk detail berita
        $sampleBerita = [
            'peringatan-hut-ri-ke-79-desa-blanakan' => [
                'id' => 1,
                'judul' => 'Peringatan HUT RI ke-79 di Desa Blanakan Berlangsung Meriah',
                'slug' => 'peringatan-hut-ri-ke-79-desa-blanakan',
                'konten' => '<p>Pemerintah Desa Blanakan dengan bangga mengumumkan kesuksesan penyelenggaraan peringatan Hari Kemerdekaan Republik Indonesia yang ke-79. Rangkaian acara yang berlangsung dari tanggal 16-17 Agustus 2024 ini diikuti dengan antusias oleh seluruh lapisan masyarakat desa.</p>

<p>Kepala Desa Blanakan, Bapak H. Sutrisno, menyatakan rasa syukurnya atas partisipasi aktif warga dalam memeriahkan peringatan kemerdekaan tahun ini. "Ini adalah bukti nyata bahwa semangat gotong royong dan nasionalisme masih mengakar kuat di hati masyarakat Desa Blanakan," ujarnya dalam sambutan pembuka acara.</p>

<h2>Rangkaian Acara yang Meriah</h2>

<p>Peringatan HUT RI ke-79 di Desa Blanakan dimulai dengan upacara bendera yang diselenggarakan di halaman Balai Desa pada pukul 08.00 WIB. Upacara dihadiri oleh seluruh perangkat desa, kepala dusun, ketua RT/RW, serta perwakilan dari berbagai organisasi kemasyarakatan.</p>

<ul>
<li><strong>Lomba Tradisional:</strong> Balap karung, panjat pinang, lomba makan kerupuk, dan tarik tambang</li>
<li><strong>Lomba Kreatif:</strong> Menghias sepeda, fashion show kostum tradisional, dan lomba mural</li>
<li><strong>Pertunjukan Seni:</strong> Pentas seni budaya lokal dan modern dari berbagai kelompok masyarakat</li>
<li><strong>Bazar Kuliner:</strong> Pameran dan penjualan makanan khas daerah</li>
</ul>

<blockquote>
<p>"Acara ini tidak hanya memeriahkan kemerdekaan, tetapi juga mempererat tali silaturahmi antarwarga. Kami sangat bangga dengan partisipasi dan kreativitas masyarakat," kata Ibu Siti, salah seorang panitia acara.</p>
</blockquote>

<p>Puncak acara ditutup dengan malam puncak syukuran yang dihadiri oleh ribuan warga. Acara ini dimeriahkan dengan penampilan grup musik lokal dan pembagian doorprize untuk para peserta lomba.</p>

<h2>Dampak Positif bagi Masyarakat</h2>

<p>Selain sebagai ajang memeriahkan kemerdekaan, kegiatan ini juga memberikan dampak ekonomi positif bagi masyarakat. Para pedagang lokal merasakan peningkatan omzet penjualan, sementara para peserta lomba mendapatkan hadiah menarik yang telah disiapkan panitia.</p>

<p>Ke depannya, pemerintah desa berkomitmen untuk terus mengembangkan tradisi peringatan kemerdekaan yang lebih kreatif dan inklusif, agar semangat nasionalisme terus terjaga dan tumbuh di kalangan generasi muda.</p>',
                'kategori' => 'budaya',
                'gambar' => 'hut-ri.jpg',
                'tanggal_publikasi' => '2024-08-17',
                'penulis' => 'Admin Desa',
                'views' => 245
            ],
            'jadwal-pelayanan-kantor-desa-libur-kemerdekaan' => [
                'id' => 2,
                'judul' => 'Jadwal Pelayanan Kantor Desa Selama Libur Hari Kemerdekaan',
                'slug' => 'jadwal-pelayanan-kantor-desa-libur-kemerdekaan',
                'konten' => '<p>Dalam rangka memperingati Hari Kemerdekaan Republik Indonesia yang ke-79, Pemerintah Desa Blanakan menyampaikan informasi penting mengenai penyesuaian jadwal pelayanan kantor desa.</p>

<h2>Jadwal Libur Resmi</h2>
<p>Berdasarkan Surat Keputusan yang berlaku, kantor desa akan menerapkan jadwal libur sebagai berikut:</p>

<ul>
<li><strong>16 Agustus 2024 (Jumat):</strong> Libur nasional</li>
<li><strong>17 Agustus 2024 (Sabtu):</strong> Hari Kemerdekaan RI</li>
<li><strong>18 Agustus 2024 (Minggu):</strong> Libur minggu</li>
</ul>

<h2>Layanan Darurat</h2>
<p>Untuk keperluan darurat dan mendesak, masyarakat dapat menghubungi:</p>
<ul>
<li>Kepala Desa: 0812-xxxx-xxxx</li>
<li>Sekretaris Desa: 0813-xxxx-xxxx</li>
<li>Petugas Piket: 0814-xxxx-xxxx</li>
</ul>

<p>Pelayanan administratif akan kembali normal pada <strong>Senin, 19 Agustus 2024</strong> pukul 08.00 WIB dengan jam operasional seperti biasa.</p>',
                'kategori' => 'pengumuman',
                'gambar' => 'jadwal-pelayanan.jpg',
                'tanggal_publikasi' => '2024-08-15',
                'penulis' => 'Sekretaris Desa',
                'views' => 189
            ]
        ];

        // Sample berita terkait
        $beritaTerkait = [
            [
                'id' => 3,
                'judul' => 'Program Bantuan Pupuk untuk Petani Desa Blanakan',
                'slug' => 'program-bantuan-pupuk-petani-desa-blanakan',
                'kategori' => 'ekonomi',
                'gambar' => 'bantuan-pupuk.jpg',
                'tanggal_publikasi' => '2024-08-08'
            ],
            [
                'id' => 4,
                'judul' => 'Pembangunan Infrastruktur Jalan Desa Tahap II Dimulai',
                'slug' => 'pembangunan-infrastruktur-jalan-desa-tahap-ii',
                'kategori' => 'pembangunan',
                'gambar' => 'pembangunan-jalan.jpg',
                'tanggal_publikasi' => '2024-08-05'
            ],
            [
                'id' => 5,
                'judul' => 'Jadwal Posyandu Bulan September 2024',
                'slug' => 'jadwal-posyandu-september-2024',
                'kategori' => 'kesehatan',
                'gambar' => 'posyandu.jpg',
                'tanggal_publikasi' => '2024-08-01'
            ]
        ];

        if (!isset($sampleBerita[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita tidak ditemukan');
        }

        $berita = $sampleBerita[$slug];

        $data = [
            'title' => $berita['judul'] . ' - Website Desa Blanakan',
            'meta_description' => character_limiter(strip_tags($berita['konten']), 160),
            'berita' => $berita,
            'beritaTerkait' => $beritaTerkait,
            'breadcrumb' => [
                ['title' => 'Beranda', 'url' => base_url()],
                ['title' => 'Berita', 'url' => base_url('berita')],
                ['title' => $berita['judul'], 'url' => '']
            ]
        ];

        return view('frontend/detail_berita_new', $data);
    }

    public function galeri()
    {
        // Cache untuk performa yang lebih baik
        $cache = \Config\Services::cache();
        $cacheKey = 'galeri_data_v2';
        
        $galeriData = $cache->get($cacheKey);
        
        if (!$galeriData) {
            $galeriData = [
                'gallery_items' => [
                    // Kategori: Kegiatan Desa
                    [
                        'id' => 1,
                        'category' => 'kegiatan',
                        'category_label' => 'Kegiatan',
                        'title' => 'Gotong Royong Pembersihan Desa',
                        'description' => 'Kegiatan gotong royong membersihkan fasilitas umum desa yang dilakukan setiap bulan',
                        'image_url' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-01-15',
                        'photographer' => 'Tim Dokumentasi Desa'
                    ],
                    [
                        'id' => 2,
                        'category' => 'kegiatan',
                        'category_label' => 'Kegiatan',
                        'title' => 'Rapat Koordinasi RT/RW',
                        'description' => 'Rapat bulanan untuk koordinasi program pembangunan dan kemasyarakatan',
                        'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-01-10',
                        'photographer' => 'Sekretariat Desa'
                    ],
                    [
                        'id' => 3,
                        'category' => 'kegiatan',
                        'category_label' => 'Kegiatan',
                        'title' => 'Posyandu Balita dan Lansia',
                        'description' => 'Kegiatan rutin posyandu untuk kesehatan balita dan lansia',
                        'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-01-08',
                        'photographer' => 'Kader Posyandu'
                    ],
                    [
                        'id' => 4,
                        'category' => 'kegiatan',
                        'category_label' => 'Kegiatan',
                        'title' => 'Penyuluhan Pertanian Organik',
                        'description' => 'Sosialisasi teknik pertanian organik kepada petani desa',
                        'image_url' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-01-05',
                        'photographer' => 'Dinas Pertanian'
                    ],
                    
                    // Kategori: Acara Desa
                    [
                        'id' => 5,
                        'category' => 'acara',
                        'category_label' => 'Acara',
                        'title' => 'Peringatan HUT RI ke-79',
                        'description' => 'Upacara bendera dan aneka lomba memeriahkan HUT Kemerdekaan RI',
                        'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-08-17',
                        'photographer' => 'Karang Taruna Desa'
                    ],
                    [
                        'id' => 6,
                        'category' => 'acara',
                        'category_label' => 'Acara',
                        'title' => 'Festival Budaya Tahunan',
                        'description' => 'Pertunjukan seni budaya dan pameran produk lokal desa',
                        'image_url' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-07-20',
                        'photographer' => 'Sanggar Seni Desa'
                    ],
                    [
                        'id' => 7,
                        'category' => 'acara',
                        'category_label' => 'Acara',
                        'title' => 'Bazar Ramadhan',
                        'description' => 'Pasar murah dan bazar kuliner menyambut bulan suci Ramadhan',
                        'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-03-15',
                        'photographer' => 'UMKM Desa'
                    ],
                    [
                        'id' => 8,
                        'category' => 'acara',
                        'category_label' => 'Acara',
                        'title' => 'Pekan Olahraga Desa',
                        'description' => 'Turnamen olahraga antar RT dengan berbagai cabang lomba',
                        'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-06-10',
                        'photographer' => 'Komunitas Olahraga'
                    ],
                    
                    // Kategori: Infrastruktur
                    [
                        'id' => 9,
                        'category' => 'infrastruktur',
                        'category_label' => 'Infrastruktur',
                        'title' => 'Pembangunan Jalan Desa',
                        'description' => 'Proses pembangunan jalan beton untuk meningkatkan aksesibilitas',
                        'image_url' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-02-01',
                        'photographer' => 'Tim Pembangunan'
                    ],
                    [
                        'id' => 10,
                        'category' => 'infrastruktur',
                        'category_label' => 'Infrastruktur',
                        'title' => 'Renovasi Balai Desa',
                        'description' => 'Pembaruan dan modernisasi fasilitas balai desa untuk pelayanan publik',
                        'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-01-20',
                        'photographer' => 'Kontraktor Desa'
                    ],
                    [
                        'id' => 11,
                        'category' => 'infrastruktur',
                        'category_label' => 'Infrastruktur',
                        'title' => 'Instalasi Lampu Jalan',
                        'description' => 'Pemasangan lampu penerangan jalan untuk keamanan dan kenyamanan warga',
                        'image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2024-01-12',
                        'photographer' => 'Dinas Listrik'
                    ],
                    [
                        'id' => 12,
                        'category' => 'infrastruktur',
                        'category_label' => 'Infrastruktur',
                        'title' => 'Pembangunan Jembatan',
                        'description' => 'Konstruksi jembatan penghubung antar dusun untuk mobilitas warga',
                        'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'date' => '2023-12-15',
                        'photographer' => 'Tim Konstruksi'
                    ],
                    
                    // Kategori: Wisata
                    [
                        'id' => 13,
                        'category' => 'wisata',
                        'category_label' => 'Wisata',
                        'title' => 'Pantai Blanakan',
                        'description' => 'Keindahan pantai dengan hutan mangrove dan kuliner seafood khas',
                        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCo7nTN6_7sfs2vARVMnIo2WKzHxdSKA0QBqxh5vmn95gph2oXvWgYJylY2QHt6NM0BYnaPn1OVDNFIX2mfFPU5v4TIMDBjIscTKlcf6a1vfcAY2FY9R5UtCtbec90KG0LudjpXSMEjjL-X8CnJPXqS7qrfOKJxZiprp_lHG7nV-JMTj4VAXx461BJJJReS2DdZdtSCDI8O-Pi_z0JvsSC1C8u9EKJX5lGSzAmwsUMBM_8hIrbO4g_cEjwsrJF6pJaKOOHWOHqDfrEO',
                        'date' => '2024-01-25',
                        'photographer' => 'Pokdarwis Desa'
                    ],
                    [
                        'id' => 14,
                        'category' => 'wisata',
                        'category_label' => 'Wisata',
                        'title' => 'Hutan Mangrove',
                        'description' => 'Konservasi alam dengan jembatan kayu untuk edukasi lingkungan',
                        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD5f_gCRTdQLXbbFrOjwNcdDa0QaTtSXQz0LkubsXGKOEVU0eQBZCqSGC0lz4CaPnuQfhYG1HeVv2MDkp7r0rlBGTy1kJG7nWm-OvFO4I2IoLhwhccMTF5e1Feoce1ppfZeVdR8IIiWrANHzDGJmgm3pthQWxMQK8Zd_FPjFQgnT1H6pN8_8VJkfNWg4QqQwct-WLTl27_0FzoA4OLY-ywYbgzxbehCBcBXS5x_bNhSnnfTBrC_zCYTvkxGdRbnHAVAuRFmkbm54awm',
                        'date' => '2024-01-22',
                        'photographer' => 'Komunitas Pecinta Alam'
                    ],
                    [
                        'id' => 15,
                        'category' => 'wisata',
                        'category_label' => 'Wisata',
                        'title' => 'Dermaga Nelayan',
                        'description' => 'Aktivitas nelayan dan penjualan ikan segar langsung dari laut',
                        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBXxgeqUNKwdsG8hNjoJOLWIEbgjMRvBSEJm8M8LR7nJmOAUm5U9OUG7mAHDLOsvvQl-6RpoRRAR2bpdTIecrgPfoCROngodGiaty6hTdbwfUdSK3slnQl5ARXH1zOgRyq2GLsyJReqNP-xlU7-Lwe0xXC7aG4eoZQKFm-1TUHQXvwbx_APXCmKHrN0oxpXqZgWjqkYf1PyV7HCcnCCTQJ66oj-3scTBRjttir48YSXYw_HFEXTt-UaH4LrQnExcg1eAdHVMwwbZEs7',
                        'date' => '2024-01-18',
                        'photographer' => 'Kelompok Nelayan'
                    ],
                    [
                        'id' => 16,
                        'category' => 'wisata',
                        'category_label' => 'Wisata',
                        'title' => 'Warung Kuliner Seafood',
                        'description' => 'Hidangan laut segar dengan cita rasa khas pesisir Blanakan',
                        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6nDc-ngbGaX1cLFkJnsNj6l9wF-F5uxvIOG913Vg3G1O3nfipEpIwAdK4sHB-F_9MKfo5IXNY7KBtjCGEHDc3Ra_ZvNOaW1HIOn7_gfdLesk5qNGxxdhGlM-57ZJ9tLuzayTAjd5ZMChSEk3GYpjpn8pDpA0suqE3335PfFHDk4pln_BH1d6AEH6ME27c4QTW4y86A5t7eQv-3_FsS37TXSUDHpHQA0_D3NNqB3yhQ0tFUBHkc_k55UTXcsHCNA8DjSRR2JcFEHfj',
                        'date' => '2024-01-14',
                        'photographer' => 'Food Blogger Lokal'
                    ]
                ]
            ];
            
            // Cache untuk 1 jam
            $cache->save($cacheKey, $galeriData, 3600);
        }
        
        $data = [
            'title' => 'Galeri Foto Desa - Website Desa Blanakan',
            'meta_description' => 'Dokumentasi visual kegiatan, acara, pembangunan, dan wisata Desa Blanakan',
            'gallery_items' => $galeriData['gallery_items']
        ];

        return view('frontend/galeri_new', $data);
    }
}
