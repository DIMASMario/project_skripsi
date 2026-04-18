<?php

namespace App\Controllers;

use App\Models\SuratModel;
use App\Models\UserModel;
use App\Models\NotifikasiModel;
use App\Libraries\EmailService;

class AdminSurat extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $notifikasiModel;
    protected $emailService;

    public function __construct()
    {
        $this->suratModel = new SuratModel();
        $this->userModel = new UserModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->emailService = new EmailService();
        
        // Check if user is admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan');
        }
    }

    /**
     * Dashboard pengelolaan surat
     */
    public function index()
    {
        try {
            // Default: tampilkan semua surat jika tidak ada filter status
            $status = $this->request->getGet('status') ?? null;
            $search = $this->request->getGet('search');
            
            // Get surat dengan filter
            $page = $this->request->getGet('page') ?? 1;
            
            log_message('info', 'AdminSurat index called - Status: ' . $status . ', Page: ' . $page);
            
            $suratWithUser = $this->suratModel->getSuratWithUserPaginated(20, $page, $status, $search);
            
            log_message('info', 'Surat data fetched: ' . count($suratWithUser) . ' results');
            
            // Get pagination object untuk view
            $pager = $this->suratModel->pager;
            
            // Statistik surat
            $stats = [
                'menunggu' => $this->suratModel->getTotalSuratByStatus('menunggu'),
                'diproses' => $this->suratModel->getTotalSuratByStatus('diproses'),
                'disetujui' => $this->suratModel->getTotalSuratByStatus('disetujui'),
                'selesai' => $this->suratModel->getTotalSuratByStatus('selesai'),
                'ditolak' => $this->suratModel->getTotalSuratByStatus('ditolak'),
                'total' => $this->suratModel->countAllResults()
            ];
            
            log_message('info', 'Stats: ' . json_encode($stats));

            $data = [
                'title' => 'Manajemen Surat - Admin Panel',
                'breadcrumb' => 'Manajemen Surat',
                'current_page' => 'manajemen-surat',
                'surat' => $suratWithUser,
                'stats' => $stats,
                'status' => $status,
                'search' => $search,
                'pager' => $pager,
                'jenis_surat_list' => $this->suratModel->getListJenisSurat()
            ];

            return view('admin/manajemen_surat', $data);
        } catch (\Exception $e) {
            log_message('error', 'AdminSurat::index Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Return redirect with error message instead of error view
            return redirect()->to(base_url('admin'))->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail surat untuk review dan proses
     */
    public function detail($suratId)
    {
        $surat = $this->suratModel->find($suratId);
        
        if (!$surat) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Surat tidak ditemukan');
        }

        // Get user yang mengajukan
        $user = $this->userModel->find($surat['user_id']);
        
        if (!$user) {
            $user = []; // Default empty array if user not found
        }
        
        $jenisSuratText = '';
        $statusPerkawinanList = [];
        
        try {
            $jenisSuratText = $this->suratModel->getJenisSuratText($surat['jenis_surat'] ?? '');
            $statusPerkawinanList = $this->suratModel->getListStatusPerkawinan();
        } catch (\Exception $e) {
            log_message('error', 'Error in detail surat: ' . $e->getMessage());
        }
        
        $data = [
            'title' => 'Detail Pengajuan Surat - Admin Panel',
            'surat' => $surat,
            'user' => $user,
            'jenis_surat_text' => $jenisSuratText,
            'status_perkawinan_list' => $statusPerkawinanList
        ];

        return view('admin/detail_surat', $data);
    }

    /**
     * Ubah status surat (diproses / selesai / ditolak)
     */
    public function ubahStatus($suratId)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $surat = $this->suratModel->find($suratId);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        $statusBaru = $this->request->getPost('status');
        $pesan = $this->request->getPost('pesan_admin') ?? null;
        
        // Validasi status
        $statusValid = ['menunggu', 'diproses', 'selesai', 'ditolak'];
        if (!in_array($statusBaru, $statusValid)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Update surat
        $updateData = [
            'status' => $statusBaru,
            'pesan_admin' => $pesan
        ];
        
        try {
            $this->suratModel->update($suratId, $updateData);
            
            // Buat notifikasi untuk warga
            $this->_buatNotifikasiWarga($surat, $statusBaru, $pesan);
            
            // Jika status selesai dan ada file surat
            if ($statusBaru === 'selesai' && !empty($surat['file_surat'])) {
                // Kirim email notifikasi
                $this->_kirimEmailNotifikasi($surat);
            }
            
            return redirect()->to(base_url('admin/surat'))->with('success', 'Status surat berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Upload file surat yang sudah selesai
     */
    public function uploadFile($suratId)
    {
        // Log immediately on entry
        log_message('info', '===== uploadFile called with ID: ' . $suratId . ' =====');
        log_message('info', 'Request method: ' . $this->request->getMethod());
        log_message('info', 'POST data keys: ' . json_encode(array_keys($this->request->getPost() ?? [])));
        log_message('info', 'FILES keys: ' . json_encode(array_keys($_FILES ?? [])));
        
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'Not a POST request, returning');
            return redirect()->back();
        }

        $surat = $this->suratModel->find($suratId);
        
        if (!$surat) {
            log_message('error', 'Surat tidak ditemukan ID: ' . $suratId);
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        // Validasi file - check all possible ways to get the file
        $file = $this->request->getFile('file_surat');
        
        log_message('info', 'File object type: ' . gettype($file));
        log_message('info', 'File object: ' . json_encode($file ? ['name' => $file->getName(), 'size' => $file->getSize(), 'error' => $file->getError()] : 'NULL'));
        
        if (!$file) {
            log_message('error', 'File tidak ditemukan dalam request - getFile returned NULL');
            log_message('error', 'Raw $_FILES: ' . json_encode($_FILES ?? []));
            return redirect()->back()->with('error', 'Silakan pilih file untuk diupload');
        }
        
        log_message('info', 'File received: ' . $file->getName() . ', Size: ' . $file->getSize() . ', MIME: ' . $file->getMimeType());
        
        if (!$file->isValid()) {
            log_message('error', 'File tidak valid: ' . $file->getErrorString());
            return redirect()->back()->with('error', 'File tidak valid. Error: ' . $file->getErrorString());
        }

        // Check tipe file (hanya PDF)
        if ($file->getMimeType() !== 'application/pdf') {
            log_message('error', 'MIME tidak sesuai: ' . $file->getMimeType());
            return redirect()->back()->with('error', 'Hanya file PDF yang diperbolehkan. File Anda adalah: ' . $file->getMimeType());
        }

        // Check ukuran file (max 5MB)
        if ($file->getSize() > 5242880) {
            log_message('error', 'File terlalu besar: ' . $file->getSize() . ' bytes');
            return redirect()->back()->with('error', 'Ukuran file terlalu besar (max 5MB). Ukuran file Anda: ' . round($file->getSize() / 1024 / 1024, 2) . ' MB');
        }

        try {
            // Pastikan folder upload ada
            $uploadDir = WRITEPATH . 'uploads/surat_selesai';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
                log_message('info', 'Folder created: ' . $uploadDir);
            }
            
            // Generate nama file yang readable sesuai nama surat asli
            $namaJenisSurat = $this->suratModel->getJenisSuratText($surat['jenis_surat']);
            
            // Jika ada status perkawinan (untuk SKD), tambahkan ke nama file
            if (!empty($surat['status_perkawinan'])) {
                $statusPerkawinanList = $this->suratModel->getListStatusPerkawinan();
                $namaStatusPerkawinan = $statusPerkawinanList[$surat['status_perkawinan']] ?? $surat['status_perkawinan'];
                $fileName = $namaJenisSurat . ' - ' . $namaStatusPerkawinan . '.pdf';
            } else {
                $fileName = $namaJenisSurat . '.pdf';
            }
            
            // Buat safe filename (replace special chars)
            $fileName = preg_replace('/[^a-zA-Z0-9\s\-()]/', '', $fileName);
            
            // Move file ke folder uploads
            if ($file->move($uploadDir, $fileName)) {
                log_message('info', 'File berhasil dipindahkan ke: ' . $uploadDir . '/' . $fileName);
                
                // Update database dengan nama file dan status selesai
                $updated = $this->suratModel->update($suratId, [
                    'file_surat' => $fileName,
                    'status' => 'selesai'
                ]);
                
                log_message('info', 'Database updated: ' . ($updated ? 'success' : 'failed'));
                
                // Buat notifikasi untuk warga
                $this->_buatNotifikasiWarga($surat, 'selesai', 'Surat Anda sudah selesai dan siap diunduh');
                
                // Kirim email notifikasi
                $this->_kirimEmailNotifikasi($surat);
                
                log_message('info', 'Upload completed successfully');
                return redirect()->to(base_url('admin-surat'))
                    ->with('success', 'File surat berhasil diupload dan status diubah menjadi selesai. Klik tab "Selesai" untuk melihat daftar surat yang sudah diupload.');
            } else {
                log_message('error', 'Gagal memindahkan file');
                return redirect()->back()->with('error', 'Gagal memindahkan file ke server');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception saat upload: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pengajuan surat
     */
    public function tolak($suratId)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $surat = $this->suratModel->find($suratId);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        $alasan = $this->request->getPost('alasan');
        
        if (empty($alasan)) {
            return redirect()->back()->with('error', 'Alasan penolakan harus diisi');
        }

        try {
            $this->suratModel->update($suratId, [
                'status' => 'ditolak',
                'pesan_admin' => $alasan
            ]);
            
            // Buat notifikasi untuk warga
            $this->notifikasiModel->insert([
                'user_id' => $surat['user_id'],
                'tipe' => 'surat_ditolak',
                'judul' => 'Pengajuan Surat Ditolak',
                'pesan' => 'Pengajuan ' . $this->suratModel->getJenisSuratText($surat['jenis_surat']) . ' Anda telah ditolak. Alasan: ' . $alasan,
                'status' => 'unread'
            ]);
            
            return redirect()->back()->with('success', 'Pengajuan surat berhasil ditolak');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Private method untuk membuat notifikasi warga
     */
    private function _buatNotifikasiWarga($surat, $status, $pesan = null)
    {
        $notifikasiData = [
            'user_id' => $surat['user_id'],
            'status' => 'unread'
        ];

        switch ($status) {
            case 'diproses':
                $notifikasiData['tipe'] = 'surat_diproses';
                $notifikasiData['judul'] = 'Surat Sedang Diproses';
                $notifikasiData['pesan'] = 'Pengajuan ' . $this->suratModel->getJenisSuratText($surat['jenis_surat']) . ' Anda sedang diproses oleh admin.';
                break;
            
            case 'selesai':
                $notifikasiData['tipe'] = 'surat_selesai';
                $notifikasiData['judul'] = 'Surat Sudah Selesai';
                $notifikasiData['pesan'] = 'Pengajuan ' . $this->suratModel->getJenisSuratText($surat['jenis_surat']) . ' Anda sudah selesai dan siap diunduh di dashboard Anda.';
                break;
            
            case 'ditolak':
                $notifikasiData['tipe'] = 'surat_ditolak';
                $notifikasiData['judul'] = 'Pengajuan Surat Ditolak';
                $notifikasiData['pesan'] = 'Pengajuan ' . $this->suratModel->getJenisSuratText($surat['jenis_surat']) . ' Anda telah ditolak.';
                if ($pesan) {
                    $notifikasiData['pesan'] .= ' Alasan: ' . $pesan;
                }
                break;
        }

        if ($notifikasiData) {
            $this->notifikasiModel->insert($notifikasiData);
        }
    }

    /**
     * Private method untuk mengirim email notifikasi
     */
    private function _kirimEmailNotifikasi($surat)
    {
        // Get user data
        $user = $this->userModel->find($surat['user_id']);
        
        if (!$user || empty($user['email'])) {
            log_message('warning', 'Email notifikasi tidak dikirim - user/email tidak ditemukan untuk surat ID: ' . $surat['id']);
            return;
        }

        try {
            // Gunakan EmailService untuk mengirim email
            $suratData = [
                'id' => $surat['id'],
                'jenis_surat_text' => $this->suratModel->getJenisSuratText($surat['jenis_surat']),
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

    /**
     * Laporan surat bulanan
     */
    public function laporan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');
        
        // Get laporan data
        $laporan = $this->suratModel->getLaporanBulanan($tahun, $bulan);
        
        // Format data
        $laporanFormatted = [];
        $totalSurat = 0;
        
        foreach ($laporan as $item) {
            $jenisSuratText = $this->suratModel->getJenisSuratText($item['jenis_surat']);
            if (!isset($laporanFormatted[$jenisSuratText])) {
                $laporanFormatted[$jenisSuratText] = [
                    'menunggu' => 0,
                    'diproses' => 0,
                    'selesai' => 0,
                    'ditolak' => 0
                ];
            }
            $laporanFormatted[$jenisSuratText][$item['status']] = $item['jumlah'];
            $totalSurat += $item['jumlah'];
        }

        $data = [
            'title' => 'Laporan Surat - Admin Panel',
            'tahun' => $tahun,
            'bulan' => $bulan,
            'bulan_text' => $this->_getNamaBulan($bulan),
            'laporan' => $laporanFormatted,
            'total_surat' => $totalSurat
        ];

        return view('admin/laporan_surat', $data);
    }

    /**
     * Private method untuk mendapatkan nama bulan
     */
    private function _getNamaBulan($bulan)
    {
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        
        return $namaBulan[$bulan] ?? '-';
    }
}
