<?php

namespace App\Controllers;

use App\Models\SuratModel;
use App\Models\UserModel;
use App\Models\NotifikasiModel;

class Dashboard extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $notifikasiModel;
    protected array $statusMeta = [
        'menunggu' => ['label' => 'Menunggu Verifikasi', 'badgeClass' => 'bg-warning text-dark'],
        'diproses' => ['label' => 'Sedang Diproses', 'badgeClass' => 'bg-info text-dark'],
        'selesai' => ['label' => 'Selesai', 'badgeClass' => 'bg-success'],
        'ditolak' => ['label' => 'Ditolak', 'badgeClass' => 'bg-danger']
    ];

    public function __construct()
    {
        $this->suratModel = new SuratModel();
        $this->userModel = new UserModel();
        $this->notifikasiModel = new NotifikasiModel();
        
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan');
        }
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $surat = $this->suratModel->getSuratByUser($userId);
        $user = $this->userModel->find($userId);
        $stats = $this->summarizeSurat($surat);

        if ($user) {
            session()->set([
                'status' => $user['status'],
                'nama_lengkap' => $user['nama_lengkap'],
            ]);
        }

        $data = [
            'title' => 'Dashboard - Website Desa Blanakan',
            'user' => $user,
            'recent_surat' => array_slice($surat, 0, 5),
            'total_surat' => $stats['total'],
            'surat_pending' => $stats['menunggu'] + $stats['diproses'],
            'surat_menunggu' => $stats['menunggu'],
            'surat_processing' => $stats['diproses'],
            'surat_approved' => $stats['selesai'] ?? $stats['disetujui'] ?? 0,
            'surat_rejected' => $stats['ditolak'],
            'status_meta' => $this->statusMeta,
            'user_status' => $user['status'] ?? session()->get('status'),
            'surat_stats' => $stats,
            'notif_count' => 0, // TODO: Implement notification count
        ];

        return view('dashboard/index_standalone', $data);
    }

    public function riwayatSurat()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Get search and filter parameters
        $search = $this->request->getGet('search');
        $statusFilter = $this->request->getGet('status');
        
        // Build query with filters
        $builder = $this->suratModel->where('user_id', $userId);
        
        // Apply search filter
        if (!empty($search)) {
            $builder->like('jenis_surat', $search);
        }
        
        // Apply status filter
        if (!empty($statusFilter)) {
            $builder->where('status', $statusFilter);
        }
        
        // Order by created_at desc and paginate
        $applications = $builder->orderBy('created_at', 'DESC')
                              ->paginate(10, 'applications');
        
        $pager = $this->suratModel->pager;

        if ($user) {
            session()->set('status', $user['status']);
        }

        $data = [
            'title' => 'Riwayat Pengajuan Surat - Pelayanan Digital Desa Blanakan',
            'user' => $user,
            'applications' => $applications,
            'pager' => $pager,
            'search' => $search,
            'statusFilter' => $statusFilter
        ];

        return view('dashboard/riwayat_surat_standalone', $data);
    }

    public function detailSurat($id)
    {
        $userId = session()->get('user_id');
        
        // Debug: Check if user is logged in
        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $surat = $this->suratModel->find($id);
        $user = $this->userModel->find($userId);
        
        // Debug: Check surat data
        if (!$surat) {
            session()->setFlashdata('error', 'Data surat dengan ID ' . $id . ' tidak ditemukan');
            return redirect()->to('/dashboard/riwayat-surat');
        }
        
        // Check if surat belongs to current user
        if ($surat['user_id'] != $userId) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke surat ini');
            return redirect()->to('/dashboard/riwayat-surat');
        }
        
        $data = [
            'title' => 'Detail Pengajuan Surat - Pelayanan Digital Desa Blanakan',
            'surat' => $surat,
            'user' => $user
        ];
        
        return view('dashboard/detail_surat_standalone', $data);
    }

    public function cancelSurat($id)
    {
        $userId = session()->get('user_id');
        $surat = $this->suratModel->find($id);

        if (!$surat || $surat['user_id'] != $userId || !in_array($surat['status'], ['pending', 'menunggu', 'diproses'])) {
            return redirect()->back()->with('error', 'Surat tidak dapat dibatalkan');
        }

        if ($this->suratModel->delete($id)) {
            return redirect()->to('/dashboard/riwayat-surat')->with('success', 'Pengajuan surat berhasil dibatalkan');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan surat');
        }
    }

    public function downloadSurat($id)
    {
        $userId = session()->get('user_id');
        $surat = $this->suratModel->find($id);
        
        // Check if surat exists, belongs to user, and is completed
        if (!$surat || $surat['user_id'] != $userId || $surat['status'] !== 'selesai') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }
        
        // Check if file exists
        if (empty($surat['file_surat'])) {
            return redirect()->back()->with('error', 'File surat belum tersedia');
        }
        
        // Check both WRITEPATH and public folder
        $filePath = WRITEPATH . 'uploads/surat_selesai/' . $surat['file_surat'];
        
        if (!file_exists($filePath)) {
            // Try public folder as fallback
            $filePath = FCPATH . 'uploads/surat_selesai/' . $surat['file_surat'];
        }
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }
        
        // Download file
        return $this->response->download($filePath, null);
    }

    public function surat()
    {
        $userId = session()->get('user_id');
        $surat = $this->suratModel->getSuratByUser($userId);

        $data = [
            'title' => 'Riwayat Surat - Website Desa Blanakan',
            'surat_list' => $surat
        ];

        return view('dashboard/surat', $data);
    }

    /**
     * API endpoint untuk mengambil notifikasi warga
     */
    public function getNotifikasi()
    {
        $userId = session()->get('user_id');
        $notifikasi = $this->notifikasiModel->getNotifikasiWarga($userId, 10);
        $count = $this->notifikasiModel->countNotifikasiWargaBelumBaca($userId);
        
        return $this->response->setJSON([
            'notifikasi' => $notifikasi,
            'count' => $count
        ]);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markNotifikasiRead($id)
    {
        $this->notifikasiModel->markAsRead($id);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    /**
     * Tandai semua notifikasi warga sebagai sudah dibaca
     */
    public function markAllNotifikasiRead()
    {
        $userId = session()->get('user_id');
        $this->notifikasiModel->markAllAsRead($userId, 'warga');
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Semua notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    public function refreshSummary()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401, 'Unauthenticated');
        }

        $userId = session()->get('user_id');
        $surat = $this->suratModel->getSuratByUser($userId);
        $stats = $this->summarizeSurat($surat);
        $user = $this->userModel->find($userId);

        if ($user) {
            session()->set('status', $user['status']);
        }

        $latestSurat = array_slice($surat, 0, 5);

        return $this->response->setJSON([
            'userStatus' => $user['status'] ?? session()->get('status'),
            'stats' => $stats,
            'suratMenunggu' => $stats['menunggu'],
            'suratDiproses' => $stats['diproses'],
            'suratSelesai' => $stats['selesai'],
            'suratDitolak' => $stats['ditolak'],
            'suratTerbaru' => $latestSurat,
            'statusMeta' => $this->statusMeta,
            'syncedAt' => date(DATE_ATOM),
        ]);
    }

    public function profil()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'Data pengguna tidak ditemukan');
        }

        // Handle form submissions
        if ($this->request->getMethod() === 'POST') {
            $action = $this->request->getPost('action');
            
            if ($action === 'update_profile') {
                return $this->updateProfile();
            } elseif ($action === 'change_password') {
                return $this->changePassword();
            }
        }

        // Get total surat count
        $totalSurat = $this->suratModel->where('user_id', $userId)->countAllResults();

        $data = [
            'title' => 'Profil Saya - Website Desa Blanakan',
            'user' => $user,
            'totalSurat' => $totalSurat
        ];

        return view('dashboard/profil_standalone', $data);
    }

    private function updateProfile()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'tempat_lahir' => 'permit_empty|max_length[100]',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'jenis_kelamin' => 'permit_empty|in_list[L,P]',
            'agama' => 'permit_empty|max_length[20]',
            'alamat' => 'permit_empty|max_length[500]',
            'no_hp' => 'permit_empty|regex_match[/^[0-9]{10,15}$/]',
            'email' => 'permit_empty|valid_email',
            'foto_profil' => 'permit_empty|uploaded[foto_profil]|max_size[foto_profil,2048]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]'
        ];

        $messages = [
            'no_hp' => [
                'regex_match' => 'Nomor HP harus berupa angka 10-15 digit'
            ],
            'email' => [
                'valid_email' => 'Format email tidak valid'
            ],
            'alamat' => [
                'max_length' => 'Alamat maksimal 500 karakter'
            ],
            'foto_profil' => [
                'uploaded' => 'File foto harus diupload',
                'max_size' => 'Ukuran foto maksimal 2MB',
                'is_image' => 'File harus berupa gambar',
                'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
            ],
            'jenis_kelamin' => [
                'in_list' => 'Jenis kelamin tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada data yang dimasukkan');
        }

        $updateData = [
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'agama' => $this->request->getPost('agama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_hp' => $this->request->getPost('no_hp'),
            'email' => $this->request->getPost('email'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle file upload
        $foto = $this->request->getFile('foto_profil');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Create upload directory if not exists
            $uploadPath = FCPATH . 'uploads/profiles';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = 'profile_' . $userId . '_' . time() . '.' . $foto->getExtension();
            
            try {
                // Move file to upload directory
                $foto->move($uploadPath, $newName);
                $updateData['foto_profil'] = 'uploads/profiles/' . $newName;
                
                // Delete old photo if exists
                $user = $this->userModel->find($userId);
                if (!empty($user['foto_profil']) && file_exists(FCPATH . $user['foto_profil'])) {
                    @unlink(FCPATH . $user['foto_profil']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to upload profile photo: ' . $e->getMessage());
            }
        }

        // Remove empty values
        $updateData = array_filter($updateData, function($value, $key) {
            // Keep foto_profil even if empty to allow removal
            if ($key === 'foto_profil') {
                return true;
            }
            return !empty(trim($value)) || $value === null;
        }, ARRAY_FILTER_USE_BOTH);

        try {
            $this->userModel->update($userId, $updateData);
            
            // Update session data
            $user = $this->userModel->find($userId);
            session()->set([
                'nama_lengkap' => $user['nama_lengkap'],
                'email' => $user['email']
            ]);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            log_message('error', 'Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil');
        }
    }

    private function changePassword()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        $messages = [
            'old_password' => [
                'required' => 'Kata sandi lama harus diisi'
            ],
            'new_password' => [
                'required' => 'Kata sandi baru harus diisi',
                'min_length' => 'Kata sandi baru minimal 8 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi kata sandi harus diisi',
                'matches' => 'Konfirmasi kata sandi tidak cocok'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            $errorMessage = implode('. ', $errors);
            return redirect()->back()->with('error', $errorMessage);
        }

        $user = $this->userModel->find($userId);
        
        // Verify old password
        if (!password_verify($this->request->getPost('old_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Kata sandi lama tidak benar');
        }

        try {
            $this->userModel->update($userId, [
                'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Kata sandi berhasil diubah');
        } catch (\Exception $e) {
            log_message('error', 'Error changing password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah kata sandi');
        }
    }

    private function summarizeSurat(array $suratList): array
    {
        $template = [
            'total' => 0,
            'menunggu' => 0,
            'diproses' => 0,
            'selesai' => 0,
            'ditolak' => 0,
        ];

        $legacyMap = [
            'pending' => 'menunggu',
            'approved' => 'selesai',
            'rejected' => 'ditolak',
            'disetujui' => 'selesai',
        ];

        foreach ($suratList as $surat) {
            $template['total']++;
            $status = $surat['status'] ?? null;

            if (isset($legacyMap[$status])) {
                $status = $legacyMap[$status];
            }

            if (isset($template[$status])) {
                $template[$status]++;
            }
        }

        return $template;
    }
}