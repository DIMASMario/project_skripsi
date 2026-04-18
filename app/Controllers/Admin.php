<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SuratModel;
use App\Models\BeritaModel;
use App\Models\KontakModel;
use App\Models\DesaModel;
use App\Models\NotifikasiModel;
use App\Models\VisitorLogModel;
use App\Models\PengumumanModel;
use App\Models\GaleriModel;
use App\Libraries\EmailService;

class Admin extends BaseController
{
    protected $userModel;
    protected $suratModel;
    protected $beritaModel;
    protected $kontakModel;
    protected $desaModel;
    protected $notifikasiModel;
    protected $visitorLogModel;
    protected $pengumumanModel;
    protected $galeriModel;
    protected $emailService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->suratModel = new SuratModel();
        $this->beritaModel = new BeritaModel();
        $this->kontakModel = new KontakModel();
        $this->desaModel = new DesaModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->visitorLogModel = new VisitorLogModel();
        $this->pengumumanModel = new PengumumanModel();
        $this->galeriModel = new GaleriModel();
        $this->emailService = new EmailService();
        
        // Check if user is admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan');
        }
    }

    public function index()
    {
        // Get statistics dari database (REAL DATA, bukan statis)
        $userStats = $this->userModel->getStatistikUser();
        $suratStats = $this->suratModel->getStatistikSurat();
        $beritaStats = $this->beritaModel->getStatistikBerita();
        $visitorHariIni = $this->visitorLogModel->getTotalVisitorHariIni();
        
        // Get active/online users (dalam 15 menit terakhir)
        $usersOnlineNow = $this->visitorLogModel->getCountActiveUsers(15);
        
        // Debug logging
        log_message('info', 'Dashboard Stats - Berita: ' . json_encode($beritaStats));
        log_message('info', 'Dashboard Stats - Active Users Now: ' . $usersOnlineNow . ', Total Visitor Today: ' . $visitorHariIni);
        
        // Convert surat stats untuk easy access
        $suratByStatus = [];
        foreach ($suratStats as $stat) {
            $suratByStatus[$stat['status']] = $stat['jumlah'];
        }
        
        // Get recent data
        $suratTerbaru = $this->suratModel->getSuratMenungguWithUser(5);
        $userBaru = $this->userModel->where('role', 'warga')
                                     ->where('status', 'menunggu')
                                     ->orderBy('created_at', 'DESC')
                                     ->limit(5)
                                     ->findAll();
        $notifikasiCount = $this->notifikasiModel->countNotifikasiAdminBelumBaca();

        // Get visitor trend data (last 90 days to show historical data)
        $visitorTrend = $this->visitorLogModel->getDailyStatistics(90);

        // Prepare data untuk view (dengan fallback untuk safety)
        $data = [
            'title' => 'Admin Dashboard',
            'breadcrumb' => 'Dashboard',
            'current_page' => 'dashboard',
            
            // User Statistics
            'stats' => [
                'total_users' => $userStats['total'] ?? 0,
                'users_pending' => $userStats['menunggu'] ?? 0,
                'users_approved' => $userStats['disetujui'] ?? 0,
                'users_rejected' => $userStats['ditolak'] ?? 0,
                'users_suspended' => $userStats['suspend'] ?? 0,
                
                // Surat Statistics
                'total_surat' => array_sum(array_column($suratStats, 'jumlah')),
                'surat_pending' => $suratByStatus['menunggu'] ?? 0,
                'surat_approved' => $suratByStatus['disetujui'] ?? 0,
                'surat_rejected' => $suratByStatus['ditolak'] ?? 0,
                'surat_processed' => $suratByStatus['diproses'] ?? 0,
                
                // Berita Statistics
                'total_berita' => $beritaStats['total'] ?? 0,
                'berita_published' => $beritaStats['publish'] ?? 0,
                'berita_draft' => $beritaStats['draft'] ?? 0,
                
                // Visitor Statistics
                'visitor_today' => $visitorHariIni,
                'online_users_now' => $usersOnlineNow,

                // For letters chart (fix structure)
                'letters' => [
                    'pending' => $suratByStatus['menunggu'] ?? 0,
                    'diproses' => $suratByStatus['diproses'] ?? 0,
                    'selesai' => $suratByStatus['selesai'] ?? 0,
                    'ditolak' => $suratByStatus['ditolak'] ?? 0,
                ]
            ],
            
            // Recent Data
            'surat_terbaru' => $suratTerbaru,
            'users_baru' => $userBaru,
            'notifikasi_count' => $notifikasiCount,
            'recent_letters' => $suratTerbaru,
            
            // For charts
            'berita_by_kategori' => $beritaStats['by_kategori'] ?? [],
            'visitor_trends' => $visitorTrend
        ];
        
        log_message('info', 'Dashboard Data Stats: ' . json_encode($data['stats']));

        return view('admin/dashboard_enhanced', $data);
    }

    public function users()
    {
        $status = $this->request->getGet('status');
        $users = $this->userModel->findAll();
        
        // Filter berdasarkan status jika ada
        if ($status) {
            switch ($status) {
                case 'pending':
                    $users = array_filter($users, fn($u) => ($u['status'] ?? 'menunggu') === 'menunggu');
                    break;
                case 'suspended':
                    $users = array_filter($users, fn($u) => ($u['status'] ?? '') === 'suspended');
                    break;
                case 'approved':
                    $users = array_filter($users, fn($u) => ($u['status'] ?? '') === 'disetujui');
                    break;
            }
        }

        $data = [
            'title' => 'Manajemen Pengguna - Admin Panel',
            'users' => array_values($users), // Re-index array after filter
            'current_status' => $status
        ];

        return view('admin/users', $data);
    }

    public function verifikasiUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        $action = $this->request->getPost('action');
        
        if ($action === 'setujui') {
            $this->userModel->update($id, ['status' => 'disetujui']);
            
            // Buat notifikasi untuk warga bahwa akun sudah disetujui
            $this->notifikasiModel->tambahNotifikasi([
                'user_id' => $user['id'],
                'surat_id' => null,
                'tipe' => 'warga',
                'pesan' => 'Selamat! Akun Anda telah diverifikasi dan disetujui. Anda sekarang dapat menggunakan layanan online.'
            ]);
            
            // Send email notification
            if (!empty($user['email'])) {
                send_account_approved_email($user);
            }
            
            log_message('info', 'User approved: ' . $user['nama_lengkap']);
            
            session()->setFlashdata('success', 'Pengguna berhasil disetujui dan email konfirmasi telah dikirim');
        } elseif ($action === 'tolak') {
            // Get rejection reason from POST data
            $rejectionReason = $this->request->getPost('rejection_reason') ?: 'Data yang diberikan tidak lengkap atau tidak sesuai dengan persyaratan yang berlaku.';
            
            $this->userModel->update($id, ['status' => 'ditolak']);
            
            // Buat notifikasi untuk warga bahwa akun ditolak
            $this->notifikasiModel->tambahNotifikasi([
                'user_id' => $user['id'],
                'surat_id' => null,
                'tipe' => 'warga',
                'pesan' => 'Maaf, pengajuan akun Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.'
            ]);
            
            // Send email notification
            if (!empty($user['email'])) {
                send_account_rejected_email($user, $rejectionReason);
            }
            
            log_message('info', 'User rejected: ' . $user['nama_lengkap'] . ' - Reason: ' . $rejectionReason);
            
            session()->setFlashdata('success', 'Pengguna berhasil ditolak dan email pemberitahuan telah dikirim');
        }

        return redirect()->to('/admin/users');
    }

    public function surat()
    {
        $data = [
            'title' => 'Manajemen Surat - Admin Panel',
            'surat' => $this->suratModel->getSuratWithUser()  // FIX: ubah 'surat_list' → 'surat' untuk match dengan view
        ];

        return view('admin/surat', $data);
    }

    public function detailSurat($id)
    {
        $surat = $this->suratModel->find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Surat - Admin Panel',
            'surat' => $surat
        ];

        return view('admin/surat_detail', $data);
    }

    public function uploadFileSurat($id)
    {
        $surat = $this->suratModel->find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        // Validasi file upload
        $validationRule = [
            'file_surat' => [
                'label' => 'File Surat',
                'rules' => 'uploaded[file_surat]|max_size[file_surat,5120]|ext_in[file_surat,pdf]',
                'errors' => [
                    'uploaded' => 'File surat harus diupload',
                    'max_size' => 'Ukuran file maksimal 5MB',
                    'ext_in' => 'File harus berformat PDF'
                ]
            ]
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_surat');
        
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        // Generate unique filename - sesuai nama surat resmi
        $namaJenisSurat = $this->suratModel->getJenisSuratText($surat['jenis_surat']);
        
        // Jika ada status perkawinan (untuk SKD), tambahkan ke nama file
        if (!empty($surat['status_perkawinan'])) {
            $statusPerkawinanList = $this->suratModel->getListStatusPerkawinan();
            $namaStatusPerkawinan = $statusPerkawinanList[$surat['status_perkawinan']] ?? $surat['status_perkawinan'];
            $newName = $namaJenisSurat . ' - ' . $namaStatusPerkawinan . '.pdf';
        } else {
            $newName = $namaJenisSurat . '.pdf';
        }
        
        // Buat safe filename
        $newName = preg_replace('/[^a-zA-Z0-9\s\-()]/', '', $newName);
        
        // Upload file ke folder uploads/surat_selesai/
        try {
            $file->move(FCPATH . 'uploads/surat_selesai', $newName);
        } catch (\Exception $e) {
            log_message('error', 'Failed to upload file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupload file. Silakan coba lagi.');
        }

        // Update database
        $updateData = [
            'status' => 'selesai',
            'file_surat' => $newName,
            'pesan_admin' => $this->request->getPost('pesan_admin'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->suratModel->update($id, $updateData);

        // Buat notifikasi untuk warga
        $jenisSuratText = $this->suratModel->getJenisSuratText($surat['jenis_surat']);
        $this->notifikasiModel->insert([
            'user_id' => $surat['user_id'],
            'surat_id' => $id,
            'jenis' => 'surat',
            'judul' => 'Surat Selesai Diproses',
            'isi' => 'Surat ' . $jenisSuratText . ' Anda telah selesai diproses dan siap diunduh.',
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Send email notification (if email exists)
        $user = $this->userModel->find($surat['user_id']);
        $emailSent = false;
        if ($user && !empty($user['email'])) {
            try {
                $suratData = [
                    'id' => $id,
                    'jenis_surat_text' => $jenisSuratText,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $emailSent = $this->emailService->sendSuratSelesaiNotification(
                    $user['email'],
                    $user['nama_lengkap'],
                    $suratData
                );
                
                if ($emailSent) {
                    log_message('info', 'Email notifikasi surat selesai berhasil dikirim ke: ' . $user['email']);
                } else {
                    log_message('warning', 'Email notifikasi gagal dikirim ke: ' . $user['email']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception saat mengirim email notifikasi: ' . $e->getMessage());
            }
        }

        $successMessage = 'File surat berhasil diupload. Surat telah diselesaikan dan notifikasi telah dikirim ke warga.';
        if ($user && empty($user['email'])) {
            $successMessage .= ' (Email warga tidak tersedia untuk notifikasi)';
        }

        return redirect()->to('admin/surat')
            ->with('success', $successMessage);
    }

    public function prosesSurat($id)
    {
        $surat = $this->suratModel->find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        // Jika GET request, tampilkan form konfirmasi
        if ($this->request->getMethod() === 'GET') {
            // Langsung update status ke diproses tanpa form
            $this->suratModel->update($id, [
                'status' => 'diproses'
            ]);
            
            $jenisSuratText = $this->suratModel->getJenisSuratText($surat['jenis_surat']);
            
            // Buat notifikasi untuk warga
            $this->notifikasiModel->notifikasiStatusSurat(
                $id,
                $surat['user_id'],
                'diproses',
                $jenisSuratText
            );
            
            return redirect()->to('admin/surat')->with('success', 'Surat berhasil diproses');
        }

        // Jika POST request, process form
        $action = $this->request->getPost('action');
        $pesan = $this->request->getPost('pesan_admin');
        $jenisSuratText = $this->suratModel->getJenisSuratText($surat['jenis_surat']);

        if ($action === 'proses') {
            $this->suratModel->update($id, [
                'status' => 'diproses',
                'pesan_admin' => $pesan
            ]);
            
            // Buat notifikasi untuk warga
            $this->notifikasiModel->notifikasiStatusSurat(
                $id,
                $surat['user_id'],
                'diproses',
                $jenisSuratText
            );
            
            session()->setFlashdata('success', 'Surat berhasil diproses');
        } elseif ($action === 'selesai') {
            // Generate PDF surat here (simplified)
            $namaJenisSurat = $this->suratModel->getJenisSuratText($surat['jenis_surat']);
            
            // Jika ada status perkawinan (untuk SKD), tambahkan ke nama file
            if (!empty($surat['status_perkawinan'])) {
                $statusPerkawinanList = $this->suratModel->getListStatusPerkawinan();
                $namaStatusPerkawinan = $statusPerkawinanList[$surat['status_perkawinan']] ?? $surat['status_perkawinan'];
                $fileName = $namaJenisSurat . ' - ' . $namaStatusPerkawinan . '.pdf';
            } else {
                $fileName = $namaJenisSurat . '.pdf';
            }
            
            // Buat safe filename
            $fileName = preg_replace('/[^a-zA-Z0-9\s\-()]/', '', $fileName);
            
            $updateData = [
                'status' => 'selesai',
                'pesan_admin' => $pesan,
                'file_surat' => $fileName
            ];

            $this->suratModel->update($id, $updateData);
            
            // Buat notifikasi untuk warga
            $this->notifikasiModel->notifikasiStatusSurat(
                $id,
                $surat['user_id'],
                'selesai',
                $jenisSuratText
            );
            
            // Send email notification
            $user = $this->userModel->find($surat['user_id']);
            if ($user && !empty($user['email'])) {
                send_surat_completed_email($user, $surat);
            }
            
            session()->setFlashdata('success', 'Surat berhasil diselesaikan dan email notifikasi telah dikirim');
        } elseif ($action === 'tolak') {
            $this->suratModel->update($id, [
                'status' => 'ditolak',
                'pesan_admin' => $pesan
            ]);
            
            // Buat notifikasi untuk warga
            $this->notifikasiModel->notifikasiStatusSurat(
                $id,
                $surat['user_id'],
                'ditolak',
                $jenisSuratText
            );
            
            // Send email notification
            $user = $this->userModel->find($surat['user_id']);
            if ($user && !empty($user['email'])) {
                $suratUpdated = $this->suratModel->find($id); // Get updated data with pesan_admin
                send_surat_rejected_email($user, $suratUpdated);
            }
            
            session()->setFlashdata('success', 'Surat berhasil ditolak dan email pemberitahuan telah dikirim');
        }

        return redirect()->to('/admin/surat');
    }

    public function berita()
    {
        $data = [
            'title' => 'Manajemen Berita - Admin Panel',
            'berita' => $this->beritaModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/berita', $data);
    }

    public function tambahBerita()
    {
        log_message('info', 'Tambah Berita: Method called, HTTP Method = ' . $this->request->getMethod());
        
        $data = [
            'title' => 'Tambah Berita - Admin Panel'
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            log_message('info', 'Tambah Berita: Form submitted');
            
            // Cek session user
            $userId = session()->get('user_id');
            if (!$userId) {
                log_message('error', 'Tambah Berita: User ID not found in session');
                session()->setFlashdata('error', 'Session berakhir. Silakan login kembali.');
                return redirect()->to('/auth/admin-login');
            }
            
            log_message('info', 'Tambah Berita: User ID = ' . $userId);
            
            $rules = [
                'judul' => 'required|min_length[5]|max_length[255]',
                'konten' => 'required|min_length[50]',
                'kategori' => 'required',
                'status' => 'required|in_list[draft,publish]'
            ];

            if ($this->validate($rules)) {
                log_message('info', 'Tambah Berita: Validation passed');
                
                $slug = $this->beritaModel->createSlug($this->request->getPost('judul'));
                
                $beritaData = [
                    'judul' => $this->request->getPost('judul'),
                    'slug' => $slug,
                    'konten' => $this->request->getPost('konten'),
                    'kategori' => $this->request->getPost('kategori'),
                    'status' => $this->request->getPost('status'),
                    'author_id' => $userId,
                    'views' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                log_message('info', 'Tambah Berita: Data prepared - Judul: ' . $beritaData['judul'] . ', Status: ' . $beritaData['status']);

                // Handle image upload dengan security validation
                $gambar = $this->request->getFile('gambar');
                if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
                    log_message('info', 'Tambah Berita: Processing image upload');
                    
                    // Validate file type dan size
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    
                    if (!in_array($gambar->getMimeType(), $allowedTypes)) {
                        log_message('error', 'Tambah Berita: Invalid file type - ' . $gambar->getMimeType());
                        session()->setFlashdata('error', 'File harus berupa gambar (JPEG, PNG, GIF, WebP)');
                        return redirect()->back()->withInput();
                    }
                    
                    if ($gambar->getSize() > $maxSize) {
                        log_message('error', 'Tambah Berita: File too large - ' . $gambar->getSize() . ' bytes');
                        session()->setFlashdata('error', 'Ukuran file maksimal 5MB');
                        return redirect()->back()->withInput();
                    }
                    
                    // Generate safe filename
                    $extension = $gambar->getExtension();
                    $gambarName = 'berita_' . time() . '_' . uniqid() . '.' . $extension;
                    
                    // Create upload directory if not exists (use public folder)
                    $uploadPath = FCPATH . 'uploads/berita/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                        log_message('info', 'Tambah Berita: Created upload directory');
                    }
                    
                    if ($gambar->move($uploadPath, $gambarName)) {
                        $beritaData['gambar'] = $gambarName;
                        log_message('info', 'Tambah Berita: Image uploaded - ' . $gambarName);
                    } else {
                        log_message('error', 'Tambah Berita: Failed to move uploaded file');
                        session()->setFlashdata('error', 'Gagal mengupload gambar');
                        return redirect()->back()->withInput();
                    }
                }

                try {
                    log_message('info', 'Tambah Berita: Attempting to insert into database');
                    
                    if ($this->beritaModel->insert($beritaData)) {
                        $insertId = $this->beritaModel->getInsertID();
                        log_message('info', 'Tambah Berita: SUCCESS! Insert ID = ' . $insertId);
                        session()->setFlashdata('success', 'Berita berhasil ditambahkan');
                        return redirect()->to('/admin/berita');
                    } else {
                        $errors = $this->beritaModel->errors();
                        log_message('error', 'Tambah Berita: Insert failed - ' . json_encode($errors));
                        session()->setFlashdata('error', 'Gagal menyimpan berita: ' . implode(', ', $errors));
                        return redirect()->back()->withInput();
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Tambah Berita: Exception - ' . $e->getMessage());
                    session()->setFlashdata('error', 'Error: ' . $e->getMessage());
                    return redirect()->back()->withInput();
                }
            } else {
                $validationErrors = $this->validator->getErrors();
                log_message('error', 'Tambah Berita: Validation failed - ' . json_encode($validationErrors));
                $data['validation'] = $this->validator;
                session()->setFlashdata('errors', $validationErrors);
                return redirect()->back()->withInput();
            }
        }

        return view('admin/tambah_berita', $data);
    }

    public function editBerita($id)
    {
        log_message('info', '=== EDIT BERITA START === ID: ' . $id);
        
        $berita = $this->beritaModel->find($id);
        
        if (!$berita) {
            log_message('error', 'Edit Berita: Berita not found with ID: ' . $id);
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita tidak ditemukan');
        }

        log_message('info', 'Edit Berita: Berita found - ' . $berita['judul']);
        
        $data = [
            'title' => 'Edit Berita - Admin Panel',
            'berita' => $berita
        ];

        if ($this->request->getMethod() === 'POST') {
            log_message('info', 'Edit Berita: POST request received');
            log_message('info', 'Edit Berita: POST data = ' . json_encode($this->request->getPost()));
            
            $rules = [
                'judul' => 'required|min_length[5]',
                'konten' => 'required|min_length[50]',
                'kategori' => 'required'
            ];

            if ($this->validate($rules)) {
                log_message('info', 'Edit Berita: Validation passed');
                
                // Get action from button (draft or publish)
                $action = $this->request->getPost('action');
                $status = $action === 'publish' ? 'publish' : 'draft';
                
                log_message('info', 'Edit Berita: Action = ' . $action . ', Status = ' . $status);
                
                // Generate slug from judul
                helper('text');
                $slug = url_title($this->request->getPost('judul'), '-', true);
                
                $beritaData = [
                    'judul' => $this->request->getPost('judul'),
                    'slug' => $slug,
                    'konten' => $this->request->getPost('konten'),
                    'kategori' => $this->request->getPost('kategori'),
                    'status' => $status
                ];

                // Handle image upload
                $gambar = $this->request->getFile('gambar');
                if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
                    // Validate file type and size
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $maxSize = 2048; // 2MB in KB
                    
                    if (!in_array($gambar->getMimeType(), $allowedTypes)) {
                        session()->setFlashdata('error', 'Format file tidak valid. Gunakan JPG, PNG, atau GIF.');
                        return redirect()->back()->withInput();
                    }
                    
                    if ($gambar->getSizeByUnit('kb') > $maxSize) {
                        session()->setFlashdata('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                        return redirect()->back()->withInput();
                    }
                    
                    // Generate safe filename
                    $gambarName = 'berita_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $gambar->getExtension();
                    
                    // Upload path
                    $uploadPath = FCPATH . 'uploads/berita/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // Move file
                    try {
                        if ($gambar->move($uploadPath, $gambarName)) {
                            $beritaData['gambar'] = $gambarName;
                            
                            // Hapus file lama jika ada
                            if (!empty($berita['gambar']) && file_exists($uploadPath . $berita['gambar'])) {
                                @unlink($uploadPath . $berita['gambar']);
                            }
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'Failed to upload berita image: ' . $e->getMessage());
                        session()->setFlashdata('error', 'Gagal mengupload gambar.');
                        return redirect()->back()->withInput();
                    }
                }

                try {
                    log_message('info', 'Edit Berita: Attempting to update database with data: ' . json_encode($beritaData));
                    
                    if ($this->beritaModel->update($id, $beritaData)) {
                        log_message('info', 'Edit Berita: Update successful');
                        $message = $status === 'publish' ? 'Berita berhasil diperbarui dan dipublish' : 'Berita berhasil disimpan sebagai draft';
                        session()->setFlashdata('success', $message);
                        log_message('info', 'Edit Berita: Redirecting to admin/berita');
                        return redirect()->to('admin/berita');
                    } else {
                        log_message('error', 'Edit Berita: Update returned false');
                        session()->setFlashdata('error', 'Gagal memperbarui berita.');
                        return redirect()->back()->withInput();
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error updating berita: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui berita.');
                    return redirect()->back()->withInput();
                }
            } else {
                log_message('info', 'Edit Berita: Validation failed');
                log_message('info', 'Edit Berita: Errors = ' . json_encode($this->validator->getErrors()));
                session()->setFlashdata('error', 'Mohon periksa kembali form Anda.');
                $data['errors'] = $this->validator->getErrors();
            }
        }

        log_message('info', 'Edit Berita: Rendering view');
        return view('admin/edit_berita', $data);
    }

    public function hapusBerita($id)
    {
        if ($this->beritaModel->delete($id)) {
            session()->setFlashdata('success', 'Berita berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus berita');
        }

        return redirect()->to('/admin/berita');
    }
    
    public function galeri()
    {
        $galeriModel = new \App\Models\GaleriModel();
        $galeri = $galeriModel->orderBy('created_at', 'DESC')->findAll();
        
        // Hitung jumlah album unik
        $albumsUnique = [];
        foreach ($galeri as $item) {
            if (!in_array($item['album'], $albumsUnique)) {
                $albumsUnique[] = $item['album'];
            }
        }
        
        $data = [
            'title' => 'Manajemen Galeri - Admin Panel',
            'galeri' => $galeri,
            'total_foto' => count($galeri),
            'total_albums' => count($albumsUnique)
        ];

        return view('admin/galeri', $data);
    }
    
    public function tambahGaleri()
    {
        $data = [
            'title' => 'Tambah Galeri - Admin Panel'
        ];

        if ($this->request->getMethod() === 'POST') {
            $galeriModel = new \App\Models\GaleriModel();
            
            // Validasi
            $validation = \Config\Services::validation();
            $validation->setRules([
                'judul' => 'required|min_length[3]|max_length[100]',
                'album' => 'required|max_length[50]',
                'gambar' => [
                    'rules' => 'uploaded[gambar]|max_size[gambar,5120]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]',
                    'errors' => [
                        'uploaded' => 'Foto harus diupload',
                        'max_size' => 'Ukuran foto maksimal 5MB',
                        'is_image' => 'File harus berupa gambar',
                        'mime_in' => 'Format foto harus JPG, JPEG, PNG, atau GIF'
                    ]
                ]
            ]);
            
            if (!$validation->withRequest($this->request)->run()) {
                session()->setFlashdata('errors', $validation->getErrors());
                return redirect()->back()->withInput();
            }
            
            // Upload gambar
            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = 'galeri_' . time() . '_' . uniqid() . '.' . $file->getExtension();
                
                // Upload ke folder public/uploads/galeri
                $uploadPath = FCPATH . 'uploads/galeri';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Validasi file dengan security helper
                $validation = validateUploadedFile(
                    $file,
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    10 * 1024 * 1024 // 10MB max untuk galeri
                );
                
                if (!$validation['valid']) {
                    session()->setFlashdata('error', $validation['error']);
                    return redirect()->back()->withInput();
                }
                
                // Generate safe filename
                $newName = generateSafeFilename($file->getName(), 'galeri');
                
                if ($file->move($uploadPath, $newName)) {
                    // Additional security check
                    if (!isImageSafe($uploadPath . '/' . $newName)) {
                        @unlink($uploadPath . '/' . $newName);
                        session()->setFlashdata('error', 'File yang diupload terdeteksi berbahaya');
                        return redirect()->back()->withInput();
                    }
                    
                    // Simpan ke database
                    $dataGaleri = [
                        'judul' => $this->request->getPost('judul'),
                        'deskripsi' => $this->request->getPost('deskripsi'),
                        'gambar' => $newName,
                        'album' => $this->request->getPost('album'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($galeriModel->insert($dataGaleri)) {
                        logSecurityEvent('file_upload', 'Galeri image uploaded', [
                            'filename' => $newName,
                            'album' => $this->request->getPost('album')
                        ]);
                        session()->setFlashdata('success', 'Foto berhasil ditambahkan ke galeri!');
                        return redirect()->to('/admin/galeri');
                    } else {
                        // Hapus file jika gagal simpan ke DB dengan safe unlink
                        safeUnlink($uploadPath . '/' . $newName, $uploadPath);
                        session()->setFlashdata('error', 'Gagal menyimpan data ke database');
                    }
                } else {
                    session()->setFlashdata('error', 'Gagal mengupload file');
                }
            } else {
                session()->setFlashdata('error', 'File tidak valid atau sudah dipindahkan');
            }
            
            return redirect()->back()->withInput();
        }

        return view('admin/tambah_galeri', $data);
    }
    
    public function hapusGaleri($id)
    {
        $galeriModel = new \App\Models\GaleriModel();
        $galeri = $galeriModel->find($id);
        
        if ($galeri) {
            // Hapus file gambar dengan aman
            $uploadPath = FCPATH . 'uploads/galeri/';
            $filePath = $uploadPath . $galeri['gambar'];
            
            if (safeUnlink($filePath, $uploadPath)) {
                logSecurityEvent('file_delete', 'Galeri file deleted', [
                    'galeri_id' => $id,
                    'file_path' => $galeri['gambar']
                ]);
            }
            
            // Hapus dari database
            if ($galeriModel->delete($id)) {
                session()->setFlashdata('success', 'Foto berhasil dihapus dari galeri');
            } else {
                session()->setFlashdata('error', 'Gagal menghapus foto');
            }
        } else {
            session()->setFlashdata('error', 'Foto tidak ditemukan');
        }
        
        return redirect()->to('/admin/galeri');
    }
    
    public function settings()
    {
        $data = [
            'title' => 'Pengaturan - Admin Panel',
            'settings' => [
                'nama_desa' => 'Desa Blanakan',
                'alamat_desa' => 'Desa Blanakan, Kabupaten Subang',
                'telepon' => '021-xxx-xxxx',
                'email' => 'admin@desablanakan.id'
            ]  // Nanti bisa ditambahkan SettingsModel
        ];

        if ($this->request->getMethod() === 'POST') {
            // Logic untuk update settings nanti
            session()->setFlashdata('success', 'Pengaturan berhasil disimpan');
            return redirect()->to('/admin/settings');
        }

        return view('admin/settings', $data);
    }

    /**
     * Halaman notifikasi admin
     */
    public function notifikasi()
    {
        $notifikasi = $this->notifikasiModel->getNotifikasiAdmin(50); // Get 50 latest
        $count = $this->notifikasiModel->countNotifikasiAdminBelumBaca();
        
        $data = [
            'title' => 'Notifikasi - Admin Panel',
            'notifikasi' => $notifikasi,
            'count_unread' => $count
        ];
        
        return view('admin/notifikasi', $data);
    }

    /**
     * API endpoint untuk mengambil notifikasi admin (untuk AJAX)
     */
    public function getNotifikasi()
    {
        // Only allow AJAX requests
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/notifikasi');
        }
        
        $notifikasi = $this->notifikasiModel->getNotifikasiAdmin(10);
        $count = $this->notifikasiModel->countNotifikasiAdminBelumBaca();
        
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
     * Tandai semua notifikasi admin sebagai sudah dibaca
     */
    public function markAllNotifikasiRead()
    {
        $this->notifikasiModel->markAllAsRead(null, 'admin');
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Semua notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    // ===== USER MANAGEMENT METHODS =====

    /**
     * Suspend user
     */
    public function suspendUser($id)
    {
        $this->userModel->update($id, ['status' => 'suspend']);
        
        // Add notification
        $this->notifikasiModel->insert([
            'user_id' => $id,
            'jenis' => 'akun',
            'judul' => 'Akun Anda Disuspend',
            'isi' => 'Akun Anda telah disuspend oleh administrator. Silakan hubungi admin untuk informasi lebih lanjut.',
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'User berhasil disuspend');
    }

    /**
     * Activate user
     */
    public function activateUser($id)
    {
        $this->userModel->update($id, ['status' => 'disetujui']);
        
        // Add notification
        $this->notifikasiModel->insert([
            'user_id' => $id,
            'jenis' => 'akun',
            'judul' => 'Akun Anda Diaktifkan',
            'isi' => 'Akun Anda telah diaktifkan kembali oleh administrator.',
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'User berhasil diaktifkan');
    }

    /**
     * User Activity Logs
     */
    public function userActivity()
    {
        $data = [
            'title' => 'Log Aktivitas User',
            'breadcrumb' => 'Manajemen Warga > Log Aktivitas',
            'current_page' => 'user_activity',
            'activities' => $this->visitorLogModel->getUserActivities()
        ];

        return view('admin/user_activity', $data);
    }

    // ===== SURAT MANAGEMENT METHODS =====

    /**
     * Tolak surat
     */
    public function tolakSurat($id)
    {
        // Get alasan from POST or GET parameter
        $alasan = $this->request->getPost('alasan_penolakan') ?? $this->request->getGet('alasan') ?? 'Tidak memenuhi syarat';
        
        $this->suratModel->update($id, [
            'status' => 'ditolak',
            'alasan_penolakan' => $alasan,
            'diproses_oleh' => session()->get('user_id'),
            'diproses_at' => date('Y-m-d H:i:s')
        ]);

        // Get surat data for notification
        $surat = $this->suratModel->find($id);
        
        // Add notification to user
        $this->notifikasiModel->insert([
            'user_id' => $surat['user_id'],
            'jenis' => 'surat',
            'judul' => 'Surat Ditolak',
            'isi' => 'Pengajuan surat ' . $surat['jenis_surat'] . ' Anda ditolak. Alasan: ' . $alasan,
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Surat berhasil ditolak');
    }

    /**
     * Selesaikan surat
     */
    public function selesaiSurat($id)
    {
        $this->suratModel->update($id, [
            'status' => 'selesai',
            'diselesaikan_oleh' => session()->get('user_id'),
            'diselesaikan_at' => date('Y-m-d H:i:s')
        ]);

        // Get surat data for notification
        $surat = $this->suratModel->find($id);
        
        // Add notification to user
        $this->notifikasiModel->insert([
            'user_id' => $surat['user_id'],
            'jenis' => 'surat',
            'judul' => 'Surat Selesai',
            'isi' => 'Surat ' . $surat['jenis_surat'] . ' Anda telah selesai diproses dan siap diambil.',
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Surat berhasil diselesaikan');
    }

    public function deleteSurat($id)
    {
        $surat = $this->suratModel->find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        try {
            // Hapus file surat jika ada
            if (!empty($surat['file_surat'])) {
                $filePath = FCPATH . 'uploads/surat_selesai/' . $surat['file_surat'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                    log_message('info', 'File surat dihapus: ' . $surat['file_surat']);
                }
            }

            // Hapus notifikasi terkait surat ini
            $this->notifikasiModel->where('surat_id', $id)->delete();
            
            // Hapus surat dari database
            $this->suratModel->delete($id);
            
            log_message('info', 'Surat ID ' . $id . ' berhasil dihapus oleh admin ID ' . session()->get('user_id'));
            
            return redirect()->to('admin/surat')->with('success', 'Surat berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Error menghapus surat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus surat. Silakan coba lagi.');
        }
    }

    public function cetakSurat($id)
    {
        $surat = $this->suratModel->getSuratWithUser($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        // Data untuk view
        $data = [
            'title' => 'Cetak Surat',
            'surat' => $surat
        ];

        return view('admin/surat_print', $data);
    }

    /**
     * Template Surat Management
     */
    public function suratTemplate()
    {
        $templateModel = new \App\Models\TemplateModel();
        
        $data = [
            'title' => 'Template Surat',
            'breadcrumb' => 'Manajemen Surat > Template',
            'current_page' => 'surat_template',
            'templates' => $templateModel->findAll()
        ];

        return view('admin/surat_template', $data);
    }

    /**
     * Preview Template Surat
     */
    public function previewTemplate($id)
    {
        $templateModel = new \App\Models\TemplateModel();
        $template = $templateModel->find($id);
        
        if (!$template) {
            return redirect()->to('/admin/surat-template')->with('error', 'Template tidak ditemukan');
        }

        $data = [
            'title' => 'Preview Template',
            'breadcrumb' => 'Manajemen Surat > Template > Preview',
            'current_page' => 'surat_template',
            'template' => $template
        ];

        return view('admin/surat_template_preview', $data);
    }

    /**
     * Tambah Template Surat
     */
    public function tambahTemplate()
    {
        $templateModel = new \App\Models\TemplateModel();

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama_template' => 'required|min_length[3]',
                'jenis_surat' => 'required',
                'file_template' => [
                    'rules' => 'uploaded[file_template]|max_size[file_template,5120]|ext_in[file_template,doc,docx]',
                    'errors' => [
                        'uploaded' => 'File template harus diupload',
                        'max_size' => 'Ukuran file maksimal 5MB',
                        'ext_in' => 'File harus berformat .doc atau .docx'
                    ]
                ],
                'status' => 'required|in_list[aktif,nonaktif]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Handle file upload
            $file = $this->request->getFile('file_template');
            $fileName = null;
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(WRITEPATH . '../public/uploads/templates', $fileName);
            }

            $data = [
                'nama_template' => $this->request->getPost('nama_template'),
                'jenis_surat' => $this->request->getPost('jenis_surat'),
                'file_template' => $fileName,
                'keterangan' => $this->request->getPost('keterangan'),
                'status' => $this->request->getPost('status'),
                'created_by' => session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($templateModel->insert($data)) {
                return redirect()->to('/admin/surat-template')->with('success', 'Template berhasil ditambahkan');
            } else {
                // Delete uploaded file if database insert fails
                if ($fileName && file_exists(WRITEPATH . '../public/uploads/templates/' . $fileName)) {
                    unlink(WRITEPATH . '../public/uploads/templates/' . $fileName);
                }
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan template');
            }
        }

        $data = [
            'title' => 'Tambah Template',
            'breadcrumb' => 'Manajemen Surat > Template > Tambah',
            'current_page' => 'surat_template'
        ];

        return view('admin/surat_template_form', $data);
    }

    /**
     * Edit Template Surat
     */
    public function editTemplate($id)
    {
        $templateModel = new \App\Models\TemplateModel();
        $template = $templateModel->find($id);
        
        if (!$template) {
            return redirect()->to('/admin/surat-template')->with('error', 'Template tidak ditemukan');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama_template' => 'required|min_length[3]',
                'jenis_surat' => 'required',
                'status' => 'required|in_list[aktif,nonaktif]'
            ];

            // File upload is optional on edit
            $file = $this->request->getFile('file_template');
            if ($file && $file->isValid()) {
                $rules['file_template'] = [
                    'rules' => 'max_size[file_template,5120]|ext_in[file_template,doc,docx]',
                    'errors' => [
                        'max_size' => 'Ukuran file maksimal 5MB',
                        'ext_in' => 'File harus berformat .doc atau .docx'
                    ]
                ];
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'nama_template' => $this->request->getPost('nama_template'),
                'jenis_surat' => $this->request->getPost('jenis_surat'),
                'keterangan' => $this->request->getPost('keterangan'),
                'status' => $this->request->getPost('status'),
                'updated_by' => session()->get('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Handle file upload if new file provided
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Delete old file
                if (!empty($template['file_template'])) {
                    $oldFile = WRITEPATH . '../public/uploads/templates/' . $template['file_template'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                
                // Upload new file
                $fileName = $file->getRandomName();
                $file->move(WRITEPATH . '../public/uploads/templates', $fileName);
                $data['file_template'] = $fileName;
            }

            if ($templateModel->update($id, $data)) {
                return redirect()->to('/admin/surat-template')->with('success', 'Template berhasil diperbarui');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui template');
            }
        }

        $data = [
            'title' => 'Edit Template',
            'breadcrumb' => 'Manajemen Surat > Template > Edit',
            'current_page' => 'surat_template',
            'template' => $template
        ];

        return view('admin/surat_template_form', $data);
    }

    /**
     * Hapus Template Surat
     */
    public function hapusTemplate($id)
    {
        $templateModel = new \App\Models\TemplateModel();
        $template = $templateModel->find($id);
        
        if (!$template) {
            return redirect()->to('/admin/surat-template')->with('error', 'Template tidak ditemukan');
        }
        
        // Delete file if exists
        if (!empty($template['file_template'])) {
            $filePath = WRITEPATH . '../public/uploads/templates/' . $template['file_template'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        if ($templateModel->delete($id)) {
            return redirect()->to('/admin/surat-template')->with('success', 'Template berhasil dihapus');
        } else {
            return redirect()->to('/admin/surat-template')->with('error', 'Gagal menghapus template');
        }
    }

    /**
     * Arsip Surat
     */
    public function suratArsip()
    {
        $arsipSurat = $this->suratModel->where('surat.status', 'selesai')
                                       ->orWhere('surat.status', 'ditolak')
                                       ->orderBy('updated_at', 'DESC')
                                       ->paginate(20);
        
        $data = [
            'title' => 'Arsip Surat',
            'breadcrumb' => 'Manajemen Surat > Arsip',
            'current_page' => 'surat_arsip',
            'arsip_surat' => $arsipSurat,
            'pager' => $this->suratModel->pager
        ];

        return view('admin/surat_arsip', $data);
    }

    // ===== CONTENT MANAGEMENT METHODS =====

    /**
     * Publish/Unpublish Berita
     */
    public function publishBerita($id)
    {
        $berita = $this->beritaModel->find($id);
        $newStatus = ($berita['status'] === 'publish') ? 'draft' : 'publish';
        
        $this->beritaModel->update($id, ['status' => $newStatus]);
        
        $message = ($newStatus === 'publish') ? 'Berita berhasil dipublikasi' : 'Berita berhasil dijadikan draft';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Pengumuman Management
     */
    public function pengumuman()
    {
        $pengumumanModel = new \App\Models\PengumumanModel();
        
        $data = [
            'title' => 'Manajemen Pengumuman',
            'breadcrumb' => 'Konten Website > Pengumuman',
            'current_page' => 'pengumuman',
            'pengumuman' => $pengumumanModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/pengumuman', $data);
    }

    public function tambahPengumuman()
    {
        $pengumumanModel = new \App\Models\PengumumanModel();
        
        // Cek method POST case-insensitive
        if (strtolower($this->request->getMethod()) === 'post') {
            log_message('info', '=== POST PENGUMUMAN - Processing ===');
            log_message('info', 'POST data: ' . json_encode($this->request->getPost()));
            
            $rules = [
                'judul' => 'required|max_length[200]',
                'isi' => 'required',
                'tipe' => 'required|in_list[info,peringatan,urgent,biasa]',
                'prioritas' => 'required|in_list[tinggi,sedang,rendah]',
                'tanggal_mulai' => 'required|valid_date',
                'status' => 'required|in_list[aktif,nonaktif,draft]'
            ];

            if (!$this->validate($rules)) {
                log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'tipe' => $this->request->getPost('tipe'),
                'prioritas' => $this->request->getPost('prioritas'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
                'target_audience' => $this->request->getPost('target_audience') ?: 'semua',
                'status' => $this->request->getPost('status'),
                'tampil_di_beranda' => $this->request->getPost('tampil_di_beranda') ? 1 : 0,
                'created_by' => session()->get('user_id')
            ];

            log_message('info', 'Data to insert: ' . json_encode($data));

            if ($pengumumanModel->insert($data)) {
                log_message('info', 'Pengumuman berhasil ditambahkan');
                return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil ditambahkan');
            } else {
                log_message('error', 'Gagal insert pengumuman: ' . json_encode($pengumumanModel->errors()));
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengumuman');
            }
        }

        $data = [
            'title' => 'Tambah Pengumuman',
            'breadcrumb' => 'Konten Website > Pengumuman > Tambah',
            'current_page' => 'pengumuman'
        ];

        return view('admin/pengumuman_form', $data);
    }

    public function editPengumuman($id)
    {
        $pengumumanModel = new \App\Models\PengumumanModel();
        $pengumuman = $pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
        }

        // Cek method POST case-insensitive
        if (strtolower($this->request->getMethod()) === 'post') {
            log_message('info', '=== UPDATE PENGUMUMAN - Processing ===');
            log_message('info', 'Pengumuman ID: ' . $id);
            
            $rules = [
                'judul' => 'required|max_length[200]',
                'isi' => 'required',
                'tipe' => 'required|in_list[info,peringatan,urgent,biasa]',
                'prioritas' => 'required|in_list[tinggi,sedang,rendah]',
                'tanggal_mulai' => 'required|valid_date',
                'status' => 'required|in_list[aktif,nonaktif,draft]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'tipe' => $this->request->getPost('tipe'),
                'prioritas' => $this->request->getPost('prioritas'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
                'target_audience' => $this->request->getPost('target_audience'),
                'status' => $this->request->getPost('status'),
                'tampil_di_beranda' => $this->request->getPost('tampil_di_beranda') ? 1 : 0,
                'updated_by' => session()->get('user_id')
            ];

            if ($pengumumanModel->update($id, $data)) {
                return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil diperbarui');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengumuman');
            }
        }

        $data = [
            'title' => 'Edit Pengumuman',
            'breadcrumb' => 'Konten Website > Pengumuman > Edit',
            'current_page' => 'pengumuman',
            'pengumuman' => $pengumuman
        ];

        return view('admin/pengumuman_form', $data);
    }

    public function hapusPengumuman($id)
    {
        $pengumumanModel = new \App\Models\PengumumanModel();
        $pengumuman = $pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
        }

        if ($pengumumanModel->delete($id)) {
            return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil dihapus');
        } else {
            return redirect()->to('/admin/pengumuman')->with('error', 'Gagal menghapus pengumuman');
        }
    }

    // ===== CONFIGURATION METHODS =====

    /**
     * Email Configuration
     */
    public function configEmail()
    {
        if ($this->request->getMethod() === 'POST') {
            // Save email configuration
            $configData = [
                'smtp_host' => $this->request->getPost('smtp_host'),
                'smtp_port' => $this->request->getPost('smtp_port'),
                'smtp_user' => $this->request->getPost('smtp_user'),
                'smtp_pass' => $this->request->getPost('smtp_pass'),
                'smtp_encryption' => $this->request->getPost('smtp_encryption'),
            ];
            
            // Save to config file or database
            // Implementation depends on your preference
            
            return redirect()->back()->with('success', 'Konfigurasi email berhasil disimpan');
        }

        // Load existing config (you can load from database or config file)
        $config = [
            'smtp_host' => env('email.SMTPHost', 'smtp.gmail.com'),
            'smtp_port' => env('email.SMTPPort', '587'),
            'smtp_user' => env('email.SMTPUser', ''),
            'smtp_pass' => env('email.SMTPPass', ''),
            'smtp_encryption' => env('email.SMTPCrypto', 'tls'),
            'smtp_from_name' => env('email.fromName', 'Website Desa Blanakan'),
            'email_header' => '',
            'email_footer' => '© 2025 Desa Blanakan. All rights reserved.'
        ];

        $data = [
            'title' => 'Konfigurasi Email',
            'breadcrumb' => 'Konfigurasi > Email',
            'current_page' => 'config_email',
            'config' => $config
        ];

        return view('admin/config_email', $data);
    }

    /**
     * Payment Configuration
     */
    public function configPayment()
    {
        if ($this->request->getMethod() === 'POST') {
            // Save payment configuration
            $configData = [
                'payment_mode' => $this->request->getPost('payment_mode'),
                'payment_gateway' => $this->request->getPost('payment_gateway'),
                'merchant_id' => $this->request->getPost('merchant_id'),
                'merchant_key' => $this->request->getPost('merchant_key'),
                'api_key' => $this->request->getPost('api_key'),
                'callback_url' => $this->request->getPost('callback_url'),
            ];
            
            // Save to config file or database
            // Implementation depends on your preference
            
            return redirect()->back()->with('success', 'Konfigurasi payment berhasil disimpan');
        }

        // Load existing config
        $config = [
            'payment_mode' => 'sandbox',
            'payment_gateway' => 'midtrans',
            'merchant_id' => '',
            'merchant_key' => '',
            'api_key' => '',
            'callback_url' => base_url('payment/callback'),
            'enable_payment' => false,
            'payment_fee' => 0,
        ];

        $data = [
            'title' => 'Konfigurasi Payment',
            'breadcrumb' => 'Konfigurasi > Payment',
            'current_page' => 'config_payment',
            'config' => $config
        ];

        return view('admin/config_payment', $data);
    }

    /**
     * Security Settings
     */
    public function keamanan()
    {
        $data = [
            'title' => 'Setting Keamanan',
            'breadcrumb' => 'Konfigurasi > Keamanan',
            'current_page' => 'keamanan'
        ];

        return view('admin/keamanan', $data);
    }



    /**
     * Admin Profile Management
     */
    public function profil()
    {
        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'nama' => 'required|min_length[3]',
                'email' => 'required|valid_email',
                'password' => 'permit_empty|min_length[6]'
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $updateData = [
                    'nama' => $this->request->getPost('nama'),
                    'email' => $this->request->getPost('email')
                ];

                // Update password if provided
                if ($this->request->getPost('password')) {
                    $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }

                $this->userModel->update(session()->get('user_id'), $updateData);
                
                // Update session data
                session()->set([
                    'nama' => $updateData['nama'],
                    'email' => $updateData['email']
                ]);

                return redirect()->back()->with('success', 'Profil berhasil diperbarui');
            } else {
                return redirect()->back()->with('errors', $validation->getErrors());
            }
        }

        $data = [
            'title' => 'Edit Profil Admin',
            'breadcrumb' => 'Admin Panel > Profil',
            'current_page' => 'admin_profil',
            'admin' => $this->userModel->find(session()->get('user_id'))
        ];

        return view('admin/profil', $data);
    }

    /**
     * Role Management
     */
    public function roleManagement()
    {
        $admins = $this->userModel->where('role !=', 'warga')
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll();

        $data = [
            'title' => 'Manajemen Role Admin',
            'breadcrumb' => 'Admin Panel > Role Management',
            'current_page' => 'role_management',
            'admins' => $admins
        ];

        return view('admin/role_management', $data);
    }

    /**
     * Assign Role
     */
    public function assignRole($id)
    {
        $role = $this->request->getPost('role');
        
        if ($role === 'admin') {
            $this->userModel->update($id, ['role' => $role]);
            return redirect()->back()->with('success', 'Role berhasil diubah');
        }
        
        return redirect()->back()->with('error', 'Role tidak valid');
    }

    /**
     * Halaman konfigurasi admin
     */
    public function config()
    {
        $configModel = new \App\Models\ConfigModel();
        $data = [
            'title' => 'Konfigurasi - Admin Panel',
            'config' => $configModel->getAllConfig()
        ];

        return view('admin/config', $data);
    }

    /**
     * Update konfigurasi
     */
    public function updateConfig()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $configModel = new \App\Models\ConfigModel();
        $configs = $this->request->getPost();
        
        unset($configs['_token']); // Remove CSRF token if exists
        
        if ($configModel->updateMultipleConfig($configs)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Konfigurasi berhasil diperbarui'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui konfigurasi'
            ]);
        }
    }

    /**
     * Test email configuration
     */
    public function testEmail()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $email = \Config\Services::email();
        
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => $this->request->getPost('smtp_host'),
            'SMTPPort' => $this->request->getPost('smtp_port'),
            'SMTPUser' => $this->request->getPost('smtp_username'),
            'SMTPPass' => $this->request->getPost('smtp_password'),
            'SMTPCrypto' => $this->request->getPost('smtp_encryption'),
            'mailType' => 'html'
        ];

        $email->initialize($config);
        
        $email->setFrom(session()->get('email'), 'Website Desa');
        $email->setTo(session()->get('email'));
        $email->setSubject('Test Email Configuration');
        $email->setMessage('Email konfigurasi berhasil diatur dengan benar.');

        if ($email->send()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Email test berhasil dikirim'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengirim email test: ' . $email->printDebugger()
            ]);
        }
    }

    /**
     * Halaman backup admin
     */
    public function backup()
    {
        $backups = $this->getBackupList();
        
        $data = [
            'title' => 'Backup & Restore - Admin Panel',
            'backups' => $backups
        ];

        return view('admin/backup', $data);
    }

    /**
     * Create backup
     */
    public function createBackup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $data = $this->request->getJSON(true);
        $type = $data['type'] ?? 'full';
        
        $backupDir = WRITEPATH . 'backups/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "backup_{$type}_{$timestamp}";
        
        try {
            switch ($type) {
                case 'database':
                    $this->createDatabaseBackup($backupDir . $filename . '.sql');
                    break;
                case 'files':
                    $this->createFilesBackup($backupDir . $filename . '.zip');
                    break;
                case 'full':
                    $this->createDatabaseBackup($backupDir . $filename . '_db.sql');
                    $this->createFilesBackup($backupDir . $filename . '_files.zip');
                    break;
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Backup berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename = null)
    {
        if (!$filename) {
            return redirect()->to('/admin/backup')->with('error', 'File tidak ditemukan');
        }

        $backupDir = WRITEPATH . 'backups/';
        $filepath = $backupDir . $filename;

        if (!file_exists($filepath)) {
            return redirect()->to('/admin/backup')->with('error', 'File tidak ditemukan');
        }

        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * Delete backup file
     */
    public function deleteBackup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $data = $this->request->getJSON(true);
        $filename = $data['filename'] ?? null;

        if (!$filename) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama file tidak valid'
            ]);
        }

        $backupDir = WRITEPATH . 'backups/';
        $filepath = $backupDir . $filename;

        if (!file_exists($filepath)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File tidak ditemukan'
            ]);
        }

        try {
            unlink($filepath);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Backup berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Restore backup
     */
    public function restoreBackup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $data = $this->request->getJSON(true);
        $filename = $data['filename'] ?? null;

        if (!$filename) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama file tidak valid'
            ]);
        }

        $backupDir = WRITEPATH . 'backups/';
        $filepath = $backupDir . $filename;

        if (!file_exists($filepath)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File backup tidak ditemukan'
            ]);
        }

        try {
            // Only restore database backups (.sql files)
            if (pathinfo($filename, PATHINFO_EXTENSION) === 'sql') {
                $sql = file_get_contents($filepath);
                $db = \Config\Database::connect();
                
                // Disable foreign key checks
                $db->query('SET FOREIGN_KEY_CHECKS = 0');
                
                // Remove comments and split by semicolon
                $sql = preg_replace('/--.*$/m', '', $sql); // Remove single line comments
                $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments
                
                // Split queries and execute
                $queries = explode(';', $sql);
                $executed = 0;
                
                foreach ($queries as $query) {
                    $query = trim($query);
                    if (!empty($query) && strlen($query) > 5) { // Skip very short queries
                        try {
                            $db->query($query);
                            $executed++;
                        } catch (\Exception $e) {
                            // Log but continue with other queries
                            log_message('error', 'Restore query error: ' . $e->getMessage());
                        }
                    }
                }
                
                // Re-enable foreign key checks
                $db->query('SET FOREIGN_KEY_CHECKS = 1');

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => "Database berhasil di-restore ({$executed} queries dijalankan)"
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Hanya file database (.sql) yang dapat di-restore'
                ]);
            }
        } catch (\Exception $e) {
            // Make sure to re-enable foreign key checks
            try {
                $db = \Config\Database::connect();
                $db->query('SET FOREIGN_KEY_CHECKS = 1');
            } catch (\Exception $ex) {
                // Ignore
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal melakukan restore: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload backup file
     */
    public function uploadBackup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $file = $this->request->getFile('backup_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File tidak valid'
            ]);
        }

        $allowedExtensions = ['sql', 'zip'];
        $extension = $file->getExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Format file tidak didukung. Gunakan .sql atau .zip'
            ]);
        }

        try {
            $backupDir = WRITEPATH . 'backups/';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $newName = 'backup_uploaded_' . date('Y-m-d_H-i-s') . '.' . $extension;
            $file->move($backupDir, $newName);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Backup berhasil diupload'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengupload backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check last backup
     */
    public function checkLastBackup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $backups = $this->getBackupList();
        
        return $this->response->setJSON([
            'status' => 'success',
            'last_backup' => !empty($backups) ? $backups[0] : null,
            'total_backups' => count($backups)
        ]);
    }

    /**
     * System Logs Management
     */
    public function systemLogs()
    {
        $logsPath = WRITEPATH . 'logs/';
        $logFiles = [];
        
        if (is_dir($logsPath)) {
            $files = scandir($logsPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $filepath = $logsPath . $file;
                    $logFiles[] = [
                        'name' => $file,
                        'size' => filesize($filepath),
                        'modified' => date('Y-m-d H:i:s', filemtime($filepath)),
                        'readable' => is_readable($filepath)
                    ];
                }
            }
            
            // Sort by modified date descending
            usort($logFiles, function($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });
        }
        
        $data = [
            'title' => 'System Logs - Admin Panel',
            'logFiles' => $logFiles
        ];
        
        return view('admin/logs', $data);
    }

    /**
     * View log file content
     */
    public function viewLog($filename = null)
    {
        if (!$filename) {
            return redirect()->to('/admin/logs')->with('error', 'File tidak ditemukan');
        }
        
        $logsPath = WRITEPATH . 'logs/';
        $filepath = $logsPath . $filename;
        
        if (!file_exists($filepath) || pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->to('/admin/logs')->with('error', 'File log tidak ditemukan');
        }
        
        // Read last 1000 lines
        $lines = [];
        $file = new \SplFileObject($filepath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        $startLine = max(0, $lastLine - 1000);
        
        $file->seek($startLine);
        while (!$file->eof()) {
            $lines[] = $file->current();
            $file->next();
        }
        
        $content = implode('', $lines);
        
        $data = [
            'title' => 'View Log: ' . $filename . ' - Admin Panel',
            'filename' => $filename,
            'content' => $content,
            'totalLines' => $lastLine + 1
        ];
        
        return view('admin/log_viewer', $data);
    }

    /**
     * Clear old log files
     */
    public function clearLogs()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }
        
        $data = $this->request->getJSON(true);
        $days = $data['days'] ?? 30;
        
        $logsPath = WRITEPATH . 'logs/';
        $deleted = 0;
        $cutoffDate = strtotime("-{$days} days");
        
        try {
            if (is_dir($logsPath)) {
                $files = scandir($logsPath);
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                        $filepath = $logsPath . $file;
                        if (filemtime($filepath) < $cutoffDate) {
                            unlink($filepath);
                            $deleted++;
                        }
                    }
                }
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "{$deleted} file log berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus log: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cache Management
     */
    public function cacheManagement()
    {
        $cache = \Config\Services::cache();
        $cachePath = WRITEPATH . 'cache/';
        
        // Get cache info
        $cacheInfo = $this->getCacheInfo($cachePath);
        
        $data = [
            'title' => 'Cache Management - Admin Panel',
            'cacheInfo' => $cacheInfo
        ];
        
        return view('admin/cache', $data);
    }

    /**
     * Clear all cache
     */
    public function clearCache()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        try {
            $cache = \Config\Services::cache();
            $cache->clean();
            
            // Also clear file cache
            $cachePath = WRITEPATH . 'cache/';
            $this->deleteCacheFiles($cachePath);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Cache berhasil dibersihkan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Clear specific cache type
     */
    public function clearSpecificCache()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $data = $this->request->getJSON(true);
        $type = $data['type'] ?? null;

        if (!$type) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tipe cache tidak valid'
            ]);
        }

        try {
            $cachePath = WRITEPATH . 'cache/';
            
            switch ($type) {
                case 'data':
                    $this->deleteCacheFiles($cachePath, ['index.html']);
                    break;
                case 'debugbar':
                    $debugbarPath = WRITEPATH . 'debugbar/';
                    $this->deleteCacheFiles($debugbarPath);
                    break;
                case 'session':
                    $sessionPath = WRITEPATH . 'session/';
                    $this->deleteCacheFiles($sessionPath, ['index.html']);
                    break;
                default:
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Tipe cache tidak dikenali'
                    ]);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => ucfirst($type) . ' cache berhasil dibersihkan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get cache information
     */
    private function getCacheInfo($path)
    {
        $info = [
            'total_files' => 0,
            'total_size' => 0,
            'data_cache' => ['files' => 0, 'size' => 0],
            'debugbar' => ['files' => 0, 'size' => 0],
            'session' => ['files' => 0, 'size' => 0]
        ];
        
        // Data cache
        if (is_dir($path)) {
            $files = glob($path . '*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.html') {
                    $info['data_cache']['files']++;
                    $size = filesize($file);
                    $info['data_cache']['size'] += $size;
                    $info['total_size'] += $size;
                    $info['total_files']++;
                }
            }
        }
        
        // Debugbar
        $debugbarPath = WRITEPATH . 'debugbar/';
        if (is_dir($debugbarPath)) {
            $files = glob($debugbarPath . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $info['debugbar']['files']++;
                    $size = filesize($file);
                    $info['debugbar']['size'] += $size;
                    $info['total_size'] += $size;
                    $info['total_files']++;
                }
            }
        }
        
        // Session
        $sessionPath = WRITEPATH . 'session/';
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.html') {
                    $info['session']['files']++;
                    $size = filesize($file);
                    $info['session']['size'] += $size;
                    $info['total_size'] += $size;
                    $info['total_files']++;
                }
            }
        }
        
        return $info;
    }

    /**
     * Delete cache files recursively
     */
    private function deleteCacheFiles($path, $exclude = ['index.html', '.htaccess'])
    {
        if (!is_dir($path)) {
            return;
        }
        
        $files = glob($path . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                $basename = basename($file);
                if (!in_array($basename, $exclude)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Halaman role management
     */
    public function roles()
    {
        // Mock data untuk roles dan permissions
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Administrator sistem dengan akses penuh',
                'permissions' => ['admin.read', 'admin.write', 'user.read', 'user.write', 'surat.read', 'surat.write'],
                'user_count' => 2
            ],
            [
                'id' => 2,
                'name' => 'Warga',
                'description' => 'Pengguna warga desa',
                'permissions' => ['profile.read', 'profile.update', 'surat.create'],
                'user_count' => 50
            ]
        ];

        $permissions = [
            ['id' => 1, 'name' => 'admin.read', 'description' => 'Akses dashboard admin', 'usage_count' => 1],
            ['id' => 2, 'name' => 'admin.write', 'description' => 'Kelola data admin', 'usage_count' => 1],
            ['id' => 3, 'name' => 'user.read', 'description' => 'Lihat data user', 'usage_count' => 1],
            ['id' => 4, 'name' => 'user.write', 'description' => 'Kelola data user', 'usage_count' => 1],
            ['id' => 5, 'name' => 'surat.read', 'description' => 'Lihat surat', 'usage_count' => 1],
            ['id' => 6, 'name' => 'surat.write', 'description' => 'Kelola surat', 'usage_count' => 1],
            ['id' => 7, 'name' => 'surat.create', 'description' => 'Ajukan surat', 'usage_count' => 1],
            ['id' => 8, 'name' => 'profile.read', 'description' => 'Lihat profil', 'usage_count' => 1],
            ['id' => 9, 'name' => 'profile.update', 'description' => 'Update profil', 'usage_count' => 1]
        ];

        $users = $this->userModel->select('users.id, users.username, users.email, users.role, users.status, users.created_at')
                                ->where('role !=', 'warga')
                                ->limit(20)
                                ->findAll();
        
        // Format user data
        foreach ($users as &$user) {
            $user['name'] = $user['username'];
            $user['role_name'] = ucfirst($user['role']);
            $user['is_active'] = ($user['status'] == 'approved');
            $user['last_login'] = null; // Set null karena kolom tidak ada di database
        }

        $data = [
            'title' => 'Role & Permission - Admin Panel',
            'roles' => $roles,
            'permissions' => $permissions,
            'users' => $users
        ];

        return view('admin/roles', $data);
    }

    /**
     * Halaman keamanan sistem
     */
    public function security()
    {
        // Mock data untuk security
        $settings = [
            'two_factor_auth' => false,
            'login_attempt_limit' => true,
            'max_login_attempts' => 5,
            'session_timeout' => 60,
            'ip_whitelist_enabled' => false,
            'allowed_ips' => [],
            'force_https' => false
        ];

        $blocked_ips = [];
        $security_logs = [];

        $data = [
            'title' => 'Keamanan Sistem - Admin Panel',
            'settings' => $settings,
            'blocked_ips' => $blocked_ips,
            'security_logs' => $security_logs,
            'security_score' => 85,
            'failed_logins_today' => 3,
            'threats_blocked' => 1
        ];

        return view('admin/security', $data);
    }

    /**
     * Halaman login attempts
     */
    public function loginAttempts()
    {
        // Sample data untuk login attempts
        $login_attempts = [
            [
                'id' => 1,
                'username' => 'admin',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'id' => 2,
                'username' => 'user123',
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'failed',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
            [
                'id' => 3,
                'username' => 'hacker',
                'ip_address' => '103.45.67.89',
                'user_agent' => 'curl/7.68.0',
                'status' => 'blocked',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ]
        ];

        $data = [
            'title' => 'Login Attempts - Admin Panel',
            'login_attempts' => $login_attempts,
            'total_attempts' => 127,
            'failed_attempts' => 23,
            'success_attempts' => 98,
            'blocked_ips' => 6
        ];

        return view('admin/login_attempts', $data);
    }

    /**
     * Halaman security logs
     */
    public function securityLogs()
    {
        // Sample data untuk security logs
        $security_logs = [
            [
                'id' => 1,
                'level' => 'info',
                'event_type' => 'login',
                'username' => 'admin',
                'ip_address' => '192.168.1.100',
                'description' => 'User admin berhasil login ke sistem',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'id' => 2,
                'level' => 'warning',
                'event_type' => 'access_denied',
                'username' => 'user123',
                'ip_address' => '192.168.1.105',
                'description' => 'Percobaan akses ke halaman yang tidak diizinkan',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
            [
                'id' => 3,
                'level' => 'critical',
                'event_type' => 'permission_change',
                'username' => 'admin',
                'ip_address' => '192.168.1.100',
                'description' => 'Perubahan permission role administrator',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ]
        ];

        $data = [
            'title' => 'Security Logs - Admin Panel',
            'security_logs' => $security_logs,
            'total_logs' => 456,
            'critical_logs' => 12,
            'warning_logs' => 34,
            'info_logs' => 410
        ];

        return view('admin/security_logs', $data);
    }

    /**
     * Export security logs ke CSV
     */
    public function exportSecurityLogs()
    {
        $format = $this->request->getGet('format') ?? 'csv';
        
        // Generate more sample data for export
        $security_logs = $this->generateSecurityLogsData();
        
        if ($format === 'csv') {
            return $this->exportSecurityLogsCSV($security_logs);
        } elseif ($format === 'json') {
            return $this->response->setJSON($security_logs);
        }
        
        return redirect()->back()->with('error', 'Format tidak didukung');
    }

    /**
     * Generate sample security logs data
     */
    private function generateSecurityLogsData()
    {
        $levels = ['info', 'warning', 'critical', 'debug'];
        $event_types = ['login', 'logout', 'access_denied', 'permission_change', 'data_access', 'config_change'];
        $usernames = ['admin', 'user123', 'operator', 'staff01', 'manager'];
        $ips = ['192.168.1.100', '192.168.1.105', '192.168.1.110', '103.45.67.89', '10.0.0.1'];
        
        $logs = [];
        $descriptions = [
            'login' => 'User %s berhasil login ke sistem',
            'logout' => 'User %s logout dari sistem',
            'access_denied' => 'Percobaan akses ke halaman yang tidak diizinkan oleh %s',
            'permission_change' => 'Perubahan permission role oleh %s',
            'data_access' => 'User %s mengakses data sensitif',
            'config_change' => 'User %s mengubah konfigurasi sistem'
        ];
        
        // Generate 100 sample logs
        for ($i = 1; $i <= 100; $i++) {
            $level = $levels[array_rand($levels)];
            $event_type = $event_types[array_rand($event_types)];
            $username = $usernames[array_rand($usernames)];
            $ip = $ips[array_rand($ips)];
            $description = sprintf($descriptions[$event_type], $username);
            
            $logs[] = [
                'id' => $i,
                'level' => $level,
                'event_type' => $event_type,
                'username' => $username,
                'ip_address' => $ip,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s', strtotime("-" . rand(1, 720) . " hours"))
            ];
        }
        
        // Sort by created_at descending
        usort($logs, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $logs;
    }

    /**
     * Export security logs to CSV format
     */
    private function exportSecurityLogsCSV($logs)
    {
        $filename = 'security_logs_' . date('Y-m-d_His') . '.csv';
        
        // Set headers untuk download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM untuk Excel UTF-8 support
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header CSV
        fputcsv($output, ['ID', 'Level', 'Event Type', 'Username', 'IP Address', 'Description', 'Created At']);
        
        // Data rows
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                strtoupper($log['level']),
                $log['event_type'],
                $log['username'],
                $log['ip_address'],
                $log['description'],
                $log['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export login attempts ke CSV
     */
    public function exportLoginAttempts()
    {
        $format = $this->request->getGet('format') ?? 'csv';
        
        // Generate sample data for export
        $login_attempts = $this->generateLoginAttemptsData();
        
        if ($format === 'csv') {
            return $this->exportLoginAttemptsCSV($login_attempts);
        } elseif ($format === 'json') {
            return $this->response->setJSON($login_attempts);
        }
        
        return redirect()->back()->with('error', 'Format tidak didukung');
    }

    /**
     * Generate sample login attempts data
     */
    private function generateLoginAttemptsData()
    {
        $statuses = ['success', 'failed', 'blocked'];
        $usernames = ['admin', 'user123', 'operator', 'staff01', 'hacker', 'test_user'];
        $ips = ['192.168.1.100', '192.168.1.105', '192.168.1.110', '103.45.67.89', '10.0.0.1', '45.76.123.45'];
        $user_agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36',
            'curl/7.68.0',
            'PostmanRuntime/7.28.4'
        ];
        
        $attempts = [];
        
        // Generate 150 sample attempts
        for ($i = 1; $i <= 150; $i++) {
            $status = $statuses[array_rand($statuses)];
            $username = $usernames[array_rand($usernames)];
            $ip = $ips[array_rand($ips)];
            $user_agent = $user_agents[array_rand($user_agents)];
            
            $attempts[] = [
                'id' => $i,
                'username' => $username,
                'ip_address' => $ip,
                'user_agent' => $user_agent,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s', strtotime("-" . rand(1, 720) . " hours"))
            ];
        }
        
        // Sort by created_at descending
        usort($attempts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $attempts;
    }

    /**
     * Export login attempts to CSV format
     */
    private function exportLoginAttemptsCSV($attempts)
    {
        $filename = 'login_attempts_' . date('Y-m-d_His') . '.csv';
        
        // Set headers untuk download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM untuk Excel UTF-8 support
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header CSV
        fputcsv($output, ['ID', 'Username', 'IP Address', 'User Agent', 'Status', 'Created At']);
        
        // Data rows
        foreach ($attempts as $attempt) {
            fputcsv($output, [
                $attempt['id'],
                $attempt['username'],
                $attempt['ip_address'],
                $attempt['user_agent'],
                strtoupper($attempt['status']),
                $attempt['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Get backup list
     */
    private function getBackupList()
    {
        $backupDir = WRITEPATH . 'backups/';
        if (!is_dir($backupDir)) {
            return [];
        }
        
        $files = scandir($backupDir);
        $backups = [];
        
        foreach ($files as $file) {
            if (in_array($file, ['.', '..']) || !is_file($backupDir . $file)) {
                continue;
            }
            
            $backups[] = [
                'filename' => $file,
                'type' => $this->getBackupType($file),
                'size' => filesize($backupDir . $file),
                'created_at' => filemtime($backupDir . $file)
            ];
        }
        
        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });
        
        return $backups;
    }

    /**
     * Get backup type from filename
     */
    private function getBackupType($filename)
    {
        if (strpos($filename, '_db.sql') !== false) {
            return 'database';
        } elseif (strpos($filename, '_files.zip') !== false) {
            return 'files';
        } elseif (strpos($filename, '.sql') !== false) {
            return 'database';
        } elseif (strpos($filename, '.zip') !== false) {
            return 'files';
        }
        return 'unknown';
    }

    /**
     * Create database backup
     */
    private function createDatabaseBackup($filename)
    {
        $db = \Config\Database::connect();
        
        // Get all tables
        $tables = $db->listTables();
        $sql = '';
        
        foreach ($tables as $table) {
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $createTable = $db->query("SHOW CREATE TABLE `$table`")->getRow();
            $sql .= $createTable->{'Create Table'} . ";\n\n";
            
            // Get data
            $rows = $db->query("SELECT * FROM `$table`")->getResult();
            foreach ($rows as $row) {
                $values = array_map(function($value) use ($db) {
                    return $value === null ? 'NULL' : "'" . $db->escapeString($value) . "'";
                }, (array)$row);
                $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n";
        }
        
        file_put_contents($filename, $sql);
    }

    /**
     * Create files backup
     */
    private function createFilesBackup($filename)
    {
        $zip = new \ZipArchive();
        if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create zip file');
        }
        
        $uploadsPath = FCPATH . 'uploads/';
        if (is_dir($uploadsPath)) {
            $this->addDirectoryToZip($zip, $uploadsPath, 'uploads/');
        }
        
        $zip->close();
    }

    /**
     * Add directory to zip recursively
     */
    private function addDirectoryToZip($zip, $dir, $zipPath = '')
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            
            $fullPath = $dir . $file;
            $zipFilePath = $zipPath . $file;
            
            if (is_dir($fullPath)) {
                $zip->addEmptyDir($zipFilePath);
                $this->addDirectoryToZip($zip, $fullPath . '/', $zipFilePath . '/');
            } else {
                $zip->addFile($fullPath, $zipFilePath);
            }
        }
    }

    /**
     * Edit User - Show form
     */
    public function editUser($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/users')->with('error', 'User ID tidak valid');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit User',
            'breadcrumb' => 'Admin / Manajemen User / Edit User',
            'user' => $user
        ];

        return view('admin/edit_user', $data);
    }

    /**
     * Update User Data
     */
    public function updateUser($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/users')->with('error', 'User ID tidak valid');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'no_hp' => 'required|regex_match[/^[0-9+\-\s()]+$/]',
            'alamat' => 'required|min_length[10]',
            'rt_rw' => 'permit_empty|max_length[10]',
            'status' => 'required|in_list[menunggu,disetujui,ditolak,suspended]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
            'rt_rw' => $this->request->getPost('rt_rw'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update optional fields if provided
        $optionalFields = ['nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'desa'];
        foreach ($optionalFields as $field) {
            $value = $this->request->getPost($field);
            if (!empty($value)) {
                $updateData[$field] = $value;
            }
        }

        try {
            $this->userModel->update($id, $updateData);
            
            // Log activity
            log_message('info', "Admin " . session()->get('nama') . " updated user ID: $id");
            
            return redirect()->to('/admin/users')->with('success', 'Data user berhasil diperbarui');
        } catch (\Exception $e) {
            log_message('error', 'Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data user');
        }
    }

    /**
     * Reset User Password
     */
    public function resetPassword($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID tidak valid']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        try {
            // Generate new password (default: 123456)
            $newPassword = '123456';
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $this->userModel->update($id, [
                'password' => $hashedPassword,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Create notification for user
            $this->notifikasiModel->insert([
                'user_id' => $id,
                'tipe' => 'warga',
                'pesan' => 'Password Anda telah direset oleh admin. Password baru Anda adalah: ' . $newPassword . '. Silakan login dan ubah password Anda.',
                'status' => 'belum_dibaca',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Log activity
            log_message('info', "Admin " . session()->get('nama') . " reset password for user ID: $id");

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Password berhasil direset menjadi: ' . $newPassword
                ]);
            }

            return redirect()->to('/admin/users')->with('success', "Password user {$user['nama_lengkap']} berhasil direset menjadi: $newPassword");
        } catch (\Exception $e) {
            log_message('error', 'Error resetting password: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat reset password']);
            }

            return redirect()->to('/admin/users')->with('error', 'Terjadi kesalahan saat reset password');
        }
    }

    /**
     * Delete User
     */
    public function deleteUser($id = null)
    {
        // Always return JSON for consistent API
        $this->response->setContentType('application/json');
        
        if (!$id) {
            log_message('error', 'Delete user called without ID');
            return $this->response->setJSON(['success' => false, 'message' => 'User ID tidak valid']);
        }

        log_message('info', "Attempting to delete user ID: $id");

        $user = $this->userModel->find($id);
        if (!$user) {
            log_message('error', "User not found for ID: $id");
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        log_message('info', "Found user: {$user['nama_lengkap']} (Role: {$user['role']})");

        // Prevent deleting super admin or current logged in user
        if ($id == session()->get('user_id')) {
            log_message('warning', "Attempted to delete protected user: {$user['nama_lengkap']}");
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Tidak dapat menghapus super admin atau akun Anda sendiri'
            ]);
        }

        // Debug: Check if user exists in surat table
        log_message('info', "Checking surat records for user ID: $id");
        $suratRecords = $this->suratModel->where('user_id', $id)->findAll();
        log_message('info', "Surat records found: " . json_encode($suratRecords));

        try {
            // Check if user has any surat/documents - prevent deletion if exists
            $suratCount = $this->suratModel->where('user_id', $id)->countAllResults();
            log_message('info', "User has $suratCount surat records");
            
            // Add admin override option (uncomment next line to allow force delete)
            $forceDelete = $this->request->getJSON(true)['force'] ?? false;
            
            if ($suratCount > 0 && !$forceDelete) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => "Tidak dapat menghapus user yang memiliki riwayat surat/dokumen ($suratCount dokumen). Gunakan 'Suspend' atau hubungi super admin untuk force delete.",
                    'can_force_delete' => true,
                    'surat_count' => $suratCount
                ]);
            }
            
            // If force delete, also remove surat records (WARNING: Data loss!)
            if ($forceDelete && $suratCount > 0) {
                log_message('warning', "FORCE DELETE: Removing $suratCount surat records for user $id by admin " . session()->get('nama'));
                $this->suratModel->where('user_id', $id)->delete();
            }

            // Delete user notifications first
            $notifDeleted = $this->notifikasiModel->where('user_id', $id)->delete();
            log_message('info', "Deleted user notifications: $notifDeleted");

            // Debug: Check current user before delete
            $userBeforeDelete = $this->userModel->find($id);
            log_message('info', "User before delete: " . json_encode($userBeforeDelete));

            // Try to delete user with error capture
            try {
                $userDeleted = $this->userModel->delete($id);
                log_message('info', "User deletion result: " . ($userDeleted ? 'success' : 'failed'));
                
                // Verify deletion
                $userAfterDelete = $this->userModel->find($id);
                log_message('info', "User after delete check: " . ($userAfterDelete ? 'still exists' : 'deleted successfully'));
                
                if ($userAfterDelete) {
                    throw new \Exception('User still exists in database after delete attempt');
                }
                
                if (!$userDeleted) {
                    throw new \Exception('Failed to delete user from database - delete() returned false');
                }
            } catch (\Exception $deleteError) {
                log_message('error', 'Database delete error: ' . $deleteError->getMessage());
                throw $deleteError;
            }

            // Log activity
            log_message('info', "Admin " . session()->get('nama') . " successfully deleted user: {$user['nama_lengkap']} (ID: $id)");

            return $this->response->setJSON([
                'success' => true, 
                'message' => "User {$user['nama_lengkap']} berhasil dihapus"
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting user ID ' . $id . ': ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk Delete Users
     */
    public function bulkDeleteUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userIds = $this->request->getJSON(true)['userIds'] ?? [];
        
        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada user yang dipilih']);
        }

        try {
            $deletedCount = 0;
            $errors = [];
            $currentUserId = session()->get('user_id');

            foreach ($userIds as $id) {
                $user = $this->userModel->find($id);
                
                if (!$user) {
                    $errors[] = "User ID $id tidak ditemukan";
                    continue;
                }

                // Prevent deleting super admin or current user
                if ($id == $currentUserId) {
                    $errors[] = "Tidak dapat menghapus super admin atau akun Anda sendiri ({$user['nama_lengkap']})";
                    continue;
                }

                // Check if user has documents
                $suratCount = $this->suratModel->where('user_id', $id)->countAllResults();
                $forceDelete = $this->request->getJSON(true)['force'] ?? false;
                
                if ($suratCount > 0 && !$forceDelete) {
                    $errors[] = "User {$user['nama_lengkap']} memiliki riwayat surat ($suratCount dokumen), tidak dapat dihapus";
                    continue;
                }
                
                // If force delete, remove surat records first
                if ($forceDelete && $suratCount > 0) {
                    log_message('warning', "BULK FORCE DELETE: Removing $suratCount surat records for user {$user['nama_lengkap']} by admin " . session()->get('nama'));
                    $this->suratModel->where('user_id', $id)->delete();
                }

                // Delete user notifications first
                $this->notifikasiModel->where('user_id', $id)->delete();
                
                // Delete user
                $this->userModel->delete($id);
                $deletedCount++;

                // Log activity
                log_message('info', "Admin " . session()->get('nama') . " bulk deleted user: {$user['nama_lengkap']} (ID: $id)");
            }

            $message = "$deletedCount user berhasil dihapus";
            if (!empty($errors)) {
                $message .= ". Namun ada " . count($errors) . " user yang gagal dihapus.";
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in bulk delete users: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus users']);
        }
    }

    /**
     * Bulk Change User Status
     */
    public function bulkChangeStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $data = $this->request->getJSON(true);
        $userIds = $data['userIds'] ?? [];
        $newStatus = $data['status'] ?? '';

        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada user yang dipilih']);
        }

        if (!in_array($newStatus, ['menunggu', 'disetujui', 'ditolak', 'suspended'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status tidak valid']);
        }

        try {
            $updatedCount = 0;
            $errors = [];

            foreach ($userIds as $id) {
                $user = $this->userModel->find($id);
                
                if (!$user) {
                    $errors[] = "User ID $id tidak ditemukan";
                    continue;
                }

                // Don't change status of super admin
                // Admin can delete any user except themselves
                if (false) {
                    $errors[] = "Tidak dapat mengubah status super admin ({$user['nama_lengkap']})";
                    continue;
                }

                // Update status
                $this->userModel->update($id, [
                    'status' => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Create notification for user
                $statusText = [
                    'disetujui' => 'disetujui',
                    'ditolak' => 'ditolak',
                    'suspended' => 'disuspend',
                    'menunggu' => 'diubah ke status menunggu'
                ];

                $this->notifikasiModel->insert([
                    'user_id' => $id,
                    'tipe' => 'warga',
                    'pesan' => "Status akun Anda telah {$statusText[$newStatus]} oleh admin.",
                    'status' => 'belum_dibaca',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $updatedCount++;

                // Log activity
                log_message('info', "Admin " . session()->get('nama') . " bulk changed status of user {$user['nama_lengkap']} to $newStatus");
            }

            $statusLabel = [
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
                'suspended' => 'Suspended',
                'menunggu' => 'Menunggu'
            ];

            $message = "$updatedCount user berhasil diubah statusnya ke {$statusLabel[$newStatus]}";
            if (!empty($errors)) {
                $message .= ". Namun ada " . count($errors) . " user yang gagal diubah.";
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'updated_count' => $updatedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in bulk change status: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat mengubah status users']);
        }
    }

    /**
     * Bulk Export Users Data
     */
    public function bulkExportUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userIds = $this->request->getJSON(true)['userIds'] ?? [];
        
        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada user yang dipilih']);
        }

        try {
            $users = $this->userModel->whereIn('id', $userIds)->findAll();
            
            if (empty($users)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Data user tidak ditemukan']);
            }

            // Create CSV content
            $filename = 'export_users_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = WRITEPATH . 'uploads/' . $filename;

            $file = fopen($filepath, 'w');
            
            // CSV Header
            fputcsv($file, [
                'ID', 'Nama Lengkap', 'NIK', 'Email', 'No HP', 'Alamat', 
                'RT/RW', 'Desa', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 
                'Status', 'Role', 'Tanggal Daftar'
            ]);

            // CSV Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user['id'],
                    $user['nama_lengkap'] ?? $user['nama'] ?? '',
                    $user['nik'] ?? '',
                    $user['email'] ?? '',
                    $user['no_hp'] ?? '',
                    $user['alamat'] ?? '',
                    $user['rt_rw'] ?? '',
                    $user['desa'] ?? '',
                    $user['tempat_lahir'] ?? '',
                    $user['tanggal_lahir'] ?? '',
                    $user['jenis_kelamin'] ?? '',
                    $user['status'] ?? '',
                    $user['role'] ?? 'warga',
                    $user['created_at'] ?? ''
                ]);
            }

            fclose($file);

            // Log activity
            log_message('info', "Admin " . session()->get('nama') . " exported " . count($users) . " users data");

            return $this->response->setJSON([
                'success' => true,
                'message' => count($users) . ' data user berhasil di-export',
                'download_url' => base_url('admin/users/download-export/' . $filename),
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in bulk export users: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat export data users']);
        }
    }

    /**
     * Download Export File
     */
    public function downloadExport($filename = null)
    {
        if (!$filename) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }

        $filepath = WRITEPATH . 'uploads/' . $filename;
        
        if (!file_exists($filepath)) {
            return redirect()->to('/admin/users')->with('error', 'File export tidak ditemukan');
        }

        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * System Information
     * Menampilkan informasi sistem server dan aplikasi
     */
    public function systemInfo()
    {
        $data = [
            'title' => 'Informasi Sistem',
            'system_info' => [
                'php_version' => phpversion(),
                'ci_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
                'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
                'server_port' => $_SERVER['SERVER_PORT'] ?? 'Unknown',
                'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
                'base_url' => base_url(),
                'app_timezone' => date_default_timezone_get(),
                'current_time' => date('Y-m-d H:i:s'),
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'display_errors' => ini_get('display_errors') ? 'On' : 'Off',
                'error_reporting' => error_reporting(),
                'session_save_path' => session_save_path(),
            ],
            'database_info' => $this->getDatabaseInfo(),
            'disk_info' => $this->getDiskInfo(),
            'extensions' => get_loaded_extensions(),
        ];

        return view('admin/system-info', $data);
    }

    /**
     * Get Database Information
     */
    private function getDatabaseInfo()
    {
        try {
            $db = \Config\Database::connect();
            
            return [
                'driver' => $db->DBDriver,
                'database' => $db->getDatabase(),
                'hostname' => $db->hostname,
                'port' => $db->port ?? '3306',
                'username' => $db->username,
                'charset' => $db->charset ?? 'utf8',
                'version' => $db->getVersion(),
                'platform' => $db->getPlatform(),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Unable to connect to database: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get Disk Information
     */
    private function getDiskInfo()
    {
        $totalSpace = disk_total_space(ROOTPATH);
        $freeSpace = disk_free_space(ROOTPATH);
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total_space' => $this->formatBytes($totalSpace),
            'used_space' => $this->formatBytes($usedSpace),
            'free_space' => $this->formatBytes($freeSpace),
            'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2),
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}