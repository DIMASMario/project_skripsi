<?php

namespace App\Controllers;

use App\Models\DesaModel;
use App\Models\UserModel;
use App\Models\StatistikDesaModel;

class DataDesa extends BaseController
{
    protected $desaModel;
    protected $userModel;
    protected $statistikModel;

    public function __construct()
    {
        $this->desaModel = new DesaModel();
        $this->userModel = new UserModel();
        $this->statistikModel = new StatistikDesaModel();
    }

    /**
     * Halaman Data Desa dengan statistik dinamis dari database
     */
    public function index()
    {
        // Ambil data statistik dari database statistik_desa
        $demografi = $this->statistikModel->getDemografiUtama();
        $totalPenduduk = $this->statistikModel->getTotalPenduduk();
        
        // Jika tidak ada data di statistik_desa, gunakan fallback dari users table
        if ($totalPenduduk == 0) {
            $totalPenduduk = $this->userModel->where('role', 'warga')
                                            ->where('status', 'disetujui')
                                            ->countAllResults();
            
            $totalKeluarga = $this->userModel->where('role', 'warga')
                                            ->where('status', 'disetujui')
                                            ->distinct()
                                            ->select('no_kk')
                                            ->countAllResults();
            
            $lakiLaki = $this->userModel->where('role', 'warga')
                                       ->where('status', 'disetujui')
                                       ->where('jenis_kelamin', 'L')
                                       ->countAllResults();
            
            $perempuan = $this->userModel->where('role', 'warga')
                                        ->where('status', 'disetujui')
                                        ->where('jenis_kelamin', 'P')
                                        ->countAllResults();
        } else {
            // Gunakan data dari statistik_desa
            $totalKeluarga = $demografi['jumlah_kk'] ?? 0;
            $lakiLaki = $demografi['laki_laki'] ?? 0;
            $perempuan = $demografi['perempuan'] ?? 0;
        }

        // Ambil data dari model statistik
        $kelompokUmurData = $this->statistikModel->getKelompokUmurChart();
        $pendidikanData = $this->statistikModel->getPendidikanChart();
        $pekerjaanData = $this->statistikModel->getPekerjaanChart();
        $fasilitasData = $this->statistikModel->getFasilitas();
        $wilayahData = $this->statistikModel->getDataWilayah();

        // Fallback jika tidak ada data
        if (empty($kelompokUmurData)) {
            $kelompokUmurData = $this->getKelompokUmur();
        }
        if (empty($pendidikanData)) {
            $pendidikanData = $this->getFallbackPendidikanStats($totalPenduduk);
        }
        if (empty($pekerjaanData)) {
            $pekerjaanData = $this->getFallbackPekerjaanStats($totalPenduduk);
        }
        if (empty($fasilitasData)) {
            $fasilitasData = $this->getDefaultFasilitas();
        }
        if (empty($wilayahData)) {
            $wilayahData = $this->getDefaultWilayah();
        }

        // Susun data demographics untuk view dengan data dinamis
        $totalPop = $totalPenduduk;
        $maleCount = $lakiLaki;
        $femaleCount = $perempuan;
        
        // Hitung persentase gender
        $malePercentage = $totalPop > 0 ? round(($maleCount / $totalPop) * 100, 1) : 50;
        $femalePercentage = $totalPop > 0 ? round(($femaleCount / $totalPop) * 100, 1) : 50;
        
        // Konversi kelompok umur untuk view - gunakan data dari statistik
        $ageGroups = [];
        if (is_array($kelompokUmurData)) {
            foreach ($kelompokUmurData as $item) {
                $count = $item['jumlah'] ?? 0;
                $percentage = $totalPop > 0 ? round(($count / $totalPop) * 100, 1) : 0;
                $ageGroups[] = [
                    'range' => $item['nama'] ?? $item['kelompok_umur'] ?? 'Unknown',
                    'count' => number_format($count),
                    'percentage' => $percentage
                ];
            }
        }
        
        $demographics = [
            'total_population' => $totalPop,
            'total_families' => $totalKeluarga ?: 2750,
            'male_population' => $maleCount,
            'female_population' => $femaleCount,
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'male_percentage' => $malePercentage,
            'female_percentage' => $femalePercentage,
            'population_density' => str_replace(' Jiwa/km²', '', $wilayahData['kepadatan_penduduk'] ?? '562'),
            'area' => '15.2',
            'age_groups' => $ageGroups
        ];

        // Susun data economics untuk view - gunakan data dinamis dari statistik
        $occupations = [];
        if (is_array($pekerjaanData)) {
            foreach ($pekerjaanData as $pekerjaan) {
                $count = $pekerjaan['jumlah'] ?? 0;
                $percentage = $totalPop > 0 ? round(($count / $totalPop) * 100, 1) : 0;
                $occupations[] = [
                    'name' => $pekerjaan['nama'] ?? $pekerjaan['pekerjaan'] ?? 'Unknown',
                    'count' => number_format($count),
                    'percentage' => $percentage
                ];
            }
        }
        
        $education = [];
        if (is_array($pendidikanData)) {
            foreach ($pendidikanData as $pendidikan) {
                $count = $pendidikan['jumlah'] ?? 0;
                $percentage = $totalPop > 0 ? round(($count / $totalPop) * 100, 1) : 0;
                $education[] = [
                    'level' => $pendidikan['nama'] ?? $pendidikan['jenjang'] ?? 'Unknown',
                    'name' => $pendidikan['nama'] ?? $pendidikan['jenjang'] ?? 'Unknown',
                    'count' => number_format($count),
                    'percentage' => $percentage
                ];
            }
        }
        
        $economics = [
            'occupations' => $occupations,
            'education' => $education
        ];

        // Susun data infrastructure untuk view - gunakan data dinamis
        $facilities = [];
        if (is_array($fasilitasData)) {
            foreach ($fasilitasData as $facility) {
                // Handle both data structures: from database (name/count) and default (nama/jumlah)
                $facilityName = $facility['nama'] ?? $facility['name'] ?? 'Unknown';
                $facilityCount = intval($facility['jumlah'] ?? $facility['count'] ?? 0);
                
                $facilities[] = [
                    'name' => $facilityName,
                    'count' => $facilityCount,
                    'icon' => $this->getFacilityIcon($facilityName)
                ];
            }
        }
        
        $infrastructure = [
            'facilities' => $facilities,
            'rt_count' => $wilayahData['jumlah_rt'] ?? 28,
            'rw_count' => $wilayahData['jumlah_rw'] ?? 8,
            'dusun_count' => $wilayahData['jumlah_dusun'] ?? 4
        ];

        $data = [
            'title' => 'Data Desa - Website Desa Blanakan',
            'meta_description' => 'Data statistik dan demografi Desa Blanakan terkini',
            'demographics' => $demographics,
            'economics' => $economics,
            'infrastructure' => $infrastructure,
            'total_penduduk' => $totalPenduduk,
            'total_keluarga' => $totalKeluarga,
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'kepadatan_penduduk' => $wilayahData['kepadatan_penduduk'] ?? '562 Jiwa/km²',
            'kelompok_umur' => $kelompokUmurData,
            'pendidikan_stats' => $pendidikanData,
            'pekerjaan_stats' => $pekerjaanData,
            'fasilitas' => $fasilitasData,
            'education' => $education, // Backward compatibility
            'stats_extra' => $wilayahData
        ];

        return view('frontend/data_desa_new', $data);
    }

    /**
     * Generate kelompok umur berdasarkan tanggal lahir
     */
    private function getKelompokUmur()
    {
        $users = $this->userModel->select('tanggal_lahir')
                                ->where('role', 'warga')
                                ->where('status', 'disetujui')
                                ->where('tanggal_lahir !=', '0000-00-00')
                                ->where('tanggal_lahir IS NOT NULL')
                                ->findAll();

        $kelompok = [
            '0-4' => 0,
            '5-14' => 0, 
            '15-24' => 0,
            '25-39' => 0,
            '40-59' => 0,
            '60+' => 0
        ];

        foreach ($users as $user) {
            if ($user['tanggal_lahir']) {
                $umur = date_diff(date_create($user['tanggal_lahir']), date_create('today'))->y;
                
                if ($umur <= 4) $kelompok['0-4']++;
                elseif ($umur <= 14) $kelompok['5-14']++;
                elseif ($umur <= 24) $kelompok['15-24']++;
                elseif ($umur <= 39) $kelompok['25-39']++;
                elseif ($umur <= 59) $kelompok['40-59']++;
                else $kelompok['60+']++;
            }
        }

        // Fallback data jika tidak ada data
        if (array_sum($kelompok) == 0) {
            $kelompok = [
                '0-4' => 512,
                '5-14' => 1367,
                '15-24' => 1196,
                '25-39' => 2220,
                '40-59' => 2562,
                '60+' => 683
            ];
        }

        return $kelompok;
    }

    /**
     * Fallback data untuk statistik pendidikan
     */
    private function getFallbackPendidikanStats($totalPenduduk)
    {
        // Distribusi persentase pendidikan berdasarkan data umum Indonesia
        $distribusi = [
            'SD/Sederajat' => 30,
            'SMP/Sederajat' => 25,
            'SMA/Sederajat' => 30,
            'Diploma' => 8,
            'Sarjana' => 6,
            'Pascasarjana' => 1
        ];

        $stats = [];
        foreach ($distribusi as $pendidikan => $persentase) {
            $jumlah = round(($persentase / 100) * $totalPenduduk);
            $stats[] = [
                'pendidikan_terakhir' => $pendidikan,
                'jumlah' => $jumlah
            ];
        }

        return $stats;
    }

    /**
     * Fallback data untuk statistik pekerjaan
     */
    private function getFallbackPekerjaanStats($totalPenduduk)
    {
        // Distribusi pekerjaan berdasarkan daerah pesisir/pertanian
        $distribusi = [
            'Petani' => 25,
            'Nelayan' => 20,
            'Pedagang' => 15,
            'Buruh' => 12,
            'Wiraswasta' => 10,
            'PNS/ASN' => 8,
            'Swasta' => 10
        ];

        $stats = [];
        foreach ($distribusi as $pekerjaan => $persentase) {
            $jumlah = round(($persentase / 100) * $totalPenduduk);
            $stats[] = [
                'pekerjaan' => $pekerjaan,
                'jumlah' => $jumlah
            ];
        }

        // Urutkan berdasarkan jumlah terbesar
        usort($stats, function($a, $b) {
            return $b['jumlah'] - $a['jumlah'];
        });

        return array_slice($stats, 0, 7); // Batasi 7 teratas
    }

    /**
     * Get icon for facility based on name
     */
    private function getFacilityIcon($facilityName)
    {
        $iconMap = [
            'Pendidikan' => 'school',
            'Sekolah' => 'school', 
            'SD' => 'school',
            'SMP' => 'school',
            'SMA' => 'school',
            'Kesehatan' => 'local_hospital',
            'Puskesmas' => 'local_hospital',
            'Klinik' => 'local_hospital',
            'Tempat Ibadah' => 'mosque',
            'Masjid' => 'mosque',
            'Mushola' => 'mosque',
            'Gereja' => 'church',
            'Pasar' => 'storefront',
            'Toko' => 'store',
            'Warung' => 'restaurant',
            'Balai Desa' => 'apartment',
            'Kantor' => 'business',
            'Posyandu' => 'medical_services',
            'Olahraga' => 'sports_soccer',
            'Lapangan' => 'sports_soccer',
            'Bank' => 'account_balance',
            'ATM' => 'atm'
        ];

        foreach ($iconMap as $keyword => $icon) {
            if (stripos($facilityName, $keyword) !== false) {
                return $icon;
            }
        }
        
        return 'place'; // Default icon
    }

    /**
     * Get default facilities data
     */
    private function getDefaultFasilitas()
    {
        return [
            ['nama' => 'Pendidikan', 'jumlah' => 8],
            ['nama' => 'Kesehatan', 'jumlah' => 5],
            ['nama' => 'Tempat Ibadah', 'jumlah' => 12],
            ['nama' => 'Pasar', 'jumlah' => 2],
            ['nama' => 'Balai Desa', 'jumlah' => 1],
            ['nama' => 'Posyandu', 'jumlah' => 6]
        ];
    }

    /**
     * Get default wilayah data
     */
    private function getDefaultWilayah()
    {
        return [
            'luas_wilayah' => '15.2',
            'jumlah_rt' => 28,
            'jumlah_rw' => 8,
            'jumlah_dusun' => 4,
            'kepadatan_penduduk' => '562 Jiwa/km²'
        ];
    }
}