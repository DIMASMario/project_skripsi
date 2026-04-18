<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StatistikDesaModel;

class StatistikController extends BaseController
{
    protected $statistikModel;

    public function __construct()
    {
        $this->statistikModel = new StatistikDesaModel();
    }

    /**
     * Halaman utama statistik admin
     */
    public function index()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/admin-login');
        }

        $data = [
            'title' => 'Kelola Data Statistik Desa',
            'statistik' => $this->statistikModel->getStatistikDashboard(),
            'kategori_list' => $this->statistikModel->getKategoriList()
        ];

        return view('admin/statistik/index', $data);
    }

    /**
     * Halaman edit statistik berdasarkan kategori
     */
    public function edit($kategori)
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Edit Statistik ' . ucfirst($kategori),
            'kategori' => $kategori,
            'statistik_list' => $this->statistikModel->getByKategori($kategori)
        ];

        return view('admin/statistik/edit', $data);
    }

    /**
     * Update data statistik
     */
    public function update()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $kategori = $this->request->getPost('kategori');
        $data_statistik = $this->request->getPost('statistik');

        if (!$kategori || !$data_statistik) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Data tidak lengkap'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            foreach ($data_statistik as $id => $nilai) {
                $this->statistikModel->update($id, ['nilai' => (int)$nilai]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal memperbarui data'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data statistik berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Tambah data statistik baru
     */
    public function create()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }

        $kategori = $this->request->getGet('kategori');

        $data = [
            'title' => 'Tambah Data Statistik',
            'kategori' => $kategori
        ];

        return view('admin/statistik/create', $data);
    }

    /**
     * Simpan data statistik baru
     */
    public function store()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $rules = [
            'kategori' => 'required',
            'nama_statistik' => 'required',
            'nilai' => 'required|integer|greater_than_equal_to[0]',
            'satuan' => 'permit_empty',
            'deskripsi' => 'permit_empty',
            'icon' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Tentukan urutan otomatis
            $maxUrutan = $this->statistikModel->where('kategori', $this->request->getPost('kategori'))
                                            ->selectMax('urutan')
                                            ->first();
            
            $urutan = ($maxUrutan['urutan'] ?? 0) + 1;

            $data = [
                'kategori' => $this->request->getPost('kategori'),
                'nama_statistik' => $this->request->getPost('nama_statistik'),
                'nilai' => $this->request->getPost('nilai'),
                'satuan' => $this->request->getPost('satuan') ?: 'jiwa',
                'deskripsi' => $this->request->getPost('deskripsi'),
                'icon' => $this->request->getPost('icon') ?: 'bar_chart',
                'urutan' => $urutan,
                'status' => 'aktif'
            ];

            $result = $this->statistikModel->insert($data);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data statistik berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan data'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hapus data statistik
     */
    public function delete($id)
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $result = $this->statistikModel->delete($id);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data statistik berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak ditemukan atau gagal dihapus'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Halaman statistik user/penduduk
     */
    public function user()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/admin-login');
        }
        
        log_message('info', 'StatistikController::user() accessed by user: ' . session()->get('username'));
        
        // Debug session data
        $sessionData = [
            'logged_in' => session()->get('logged_in'),
            'role' => session()->get('role'),
            'nama_lengkap' => session()->get('nama_lengkap'),
            'email' => session()->get('email')
        ];
        log_message('info', 'Session data: ' . json_encode($sessionData));

        // Load model untuk data user
        try {
            $userModel = new \App\Models\UserModel();
            
            $data = [
                'title' => 'Statistik User & Penduduk',
                'user_stats' => [
                    'total_user' => $userModel->countAll(),
                    'user_aktif' => $userModel->where('status', 'aktif')->countAllResults(),
                    'user_pending' => $userModel->where('status', 'pending')->countAllResults(),
                    'user_nonaktif' => $userModel->where('status', 'nonaktif')->countAllResults(),
                ],
                'demografi' => $this->statistikModel->getDemografiUtama(),
                'recent_users' => $userModel->orderBy('created_at', 'DESC')->limit(10)->findAll()
            ];
        } catch (\Exception $e) {
            // Fallback data jika ada error
            $data = [
                'title' => 'Statistik User & Penduduk',
                'user_stats' => [
                    'total_user' => 0,
                    'user_aktif' => 0,
                    'user_pending' => 0,
                    'user_nonaktif' => 0,
                ],
                'demografi' => ['total_penduduk' => 0, 'laki_laki' => 0, 'perempuan' => 0, 'jumlah_kk' => 0],
                'recent_users' => []
            ];
            log_message('error', 'Error in StatistikController::user() - ' . $e->getMessage());
        }

        return view('admin/statistik/user', $data);
    }

    /**
     * Halaman statistik surat
     */
    public function surat()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/admin-login');
        }

        // Load model untuk data surat
        try {
            $suratModel = new \App\Models\SuratModel();
            
            $data = [
                'title' => 'Statistik Surat & Layanan',
                'surat_stats' => [
                    'total_surat' => $suratModel->countAll(),
                    'surat_pending' => $suratModel->where('status', 'pending')->countAllResults(),
                    'surat_proses' => $suratModel->where('status', 'diproses')->countAllResults(),
                    'surat_selesai' => $suratModel->where('status', 'selesai')->countAllResults(),
                    'surat_ditolak' => $suratModel->where('status', 'ditolak')->countAllResults(),
                ],
                'recent_surat' => $suratModel->orderBy('created_at', 'DESC')->limit(10)->findAll()
            ];
        } catch (\Exception $e) {
            // Fallback data jika ada error
            $data = [
                'title' => 'Statistik Surat & Layanan',
                'surat_stats' => [
                    'total_surat' => 0,
                    'surat_pending' => 0,
                    'surat_proses' => 0,
                    'surat_selesai' => 0,
                    'surat_ditolak' => 0,
                ],
                'recent_surat' => []
            ];
            log_message('error', 'Error in StatistikController::surat() - ' . $e->getMessage());
        }

        return view('admin/statistik/surat', $data);
    }

    /**
     * Halaman statistik traffic website
     */
    public function traffic()
    {
        // Cek autentikasi admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/admin-login');
        }

        try {
            $db = \Config\Database::connect();
            
            // Cek apakah tabel visitor_logs ada
            if (!$db->tableExists('visitor_logs')) {
                // Jika tabel belum ada, tampilkan data kosong
                $data = [
                    'title' => 'Statistik Traffic Website',
                    'traffic_stats' => [
                        'page_views' => 0,
                        'unique_visitors' => 0,
                        'bounce_rate' => '0%',
                        'avg_session' => '0 menit',
                    ],
                    'popular_pages' => []
                ];
                return view('admin/statistik/traffic', $data);
            }
            
            // Hitung total page views dari visitor_logs
            $pageViews = $db->table('visitor_logs')->countAllResults();
            
            // Hitung unique visitors (berdasarkan IP address unik)
            $uniqueVisitors = $db->table('visitor_logs')
                ->distinct()
                ->select('ip_address')
                ->countAllResults();
            
            // Hitung bounce rate (pengunjung yang hanya melihat 1 halaman)
            $totalSessions = 0;
            $bounceSessions = 0;
            
            try {
                $result = $db->query("SELECT COUNT(DISTINCT ip_address, DATE(visited_at)) as total FROM visitor_logs")->getRow();
                $totalSessions = $result ? $result->total : 0;
                
                $result2 = $db->query("
                    SELECT COUNT(*) as bounced 
                    FROM (
                        SELECT ip_address, DATE(visited_at) as visit_date, COUNT(*) as page_count
                        FROM visitor_logs 
                        GROUP BY ip_address, DATE(visited_at)
                        HAVING page_count = 1
                    ) as sessions
                ")->getRow();
                $bounceSessions = $result2 ? $result2->bounced : 0;
            } catch (\Exception $e) {
                log_message('error', 'Error calculating bounce rate: ' . $e->getMessage());
            }
            
            $bounceRate = $totalSessions > 0 ? round(($bounceSessions / $totalSessions) * 100) : 0;
            
            // Hitung rata-rata durasi sesi (dalam menit)
            $avgSession = 0;
            try {
                $result3 = $db->query("
                    SELECT AVG(duration) as avg_duration
                    FROM (
                        SELECT 
                            ip_address,
                            DATE(visited_at) as visit_date,
                            TIMESTAMPDIFF(MINUTE, MIN(visited_at), MAX(visited_at)) as duration
                        FROM visitor_logs
                        GROUP BY ip_address, DATE(visited_at)
                        HAVING COUNT(*) > 1
                    ) as sessions
                ")->getRow();
                $avgSession = $result3 && $result3->avg_duration ? $result3->avg_duration : 0;
            } catch (\Exception $e) {
                log_message('error', 'Error calculating avg session: ' . $e->getMessage());
            }
            
            // Halaman terpopuler (perbaiki column name menjadi page_visited)
            $popularPages = [];
            try {
                $popularPages = $db->table('visitor_logs')
                    ->select('page_visited, COUNT(*) as views')
                    ->groupBy('page_visited')
                    ->orderBy('views', 'DESC')
                    ->limit(10)
                    ->get()
                    ->getResultArray();
            } catch (\Exception $e) {
                log_message('error', 'Error getting popular pages: ' . $e->getMessage());
            }

            $data = [
                'title' => 'Statistik Traffic Website',
                'traffic_stats' => [
                    'page_views' => $pageViews,
                    'unique_visitors' => $uniqueVisitors,
                    'bounce_rate' => $bounceRate . '%',
                    'avg_session' => round($avgSession, 1) . ' menit',
                ],
                'popular_pages' => $popularPages
            ];

            return view('admin/statistik/traffic', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Traffic stats error: ' . $e->getMessage());
            
            // Tampilkan data kosong jika error
            $data = [
                'title' => 'Statistik Traffic Website',
                'traffic_stats' => [
                    'page_views' => 0,
                    'unique_visitors' => 0,
                    'bounce_rate' => '0%',
                    'avg_session' => '0 menit',
                ],
                'popular_pages' => [],
                'error_message' => 'Terjadi kesalahan saat mengambil data statistik. Silakan hubungi administrator.'
            ];
            
            return view('admin/statistik/traffic', $data);
        }
    }
    
    /**
     * API untuk mendapatkan ringkasan statistik
     */
    public function api_summary()
    {
        try {
            $demografi = $this->statistikModel->getDemografiUtama();
            $total_fasilitas = count($this->statistikModel->getFasilitas());
            
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'total_penduduk' => $demografi['total_penduduk'] ?? 0,
                    'total_kk' => $demografi['jumlah_kk'] ?? 0,
                    'laki_laki' => $demografi['laki_laki'] ?? 0,
                    'perempuan' => $demografi['perempuan'] ?? 0,
                    'total_fasilitas' => $total_fasilitas
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}