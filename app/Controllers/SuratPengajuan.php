<?php

namespace App\Controllers;

use App\Models\SuratModel;
use App\Models\UserModel;
use App\Models\NotifikasiModel;

class SuratPengajuan extends BaseController
{
    protected $suratModel;
    protected $userModel;
    protected $notifikasiModel;

    public function __construct()
    {
        $this->suratModel = new SuratModel();
        $this->userModel = new UserModel();
        $this->notifikasiModel = new NotifikasiModel();
        
        // Check jika user sudah login dan berstatus warga
        if (!session()->get('logged_in')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Silakan login terlebih dahulu');
        }
        
        if (session()->get('role') !== 'warga') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Hanya warga yang dapat mengajukan surat');
        }
    }

    /**
     * Halaman dashboard pengajuan surat warga
     * Menampilkan list surat yang sudah diajukan dengan status
     */
    public function index()
    {
        $userId = session()->get('user_id');
        
        // Get surat yang diajukan oleh warga ini
        $surat = $this->suratModel->getSuratByUser($userId);
        
        // Group by status untuk tampilan yang lebih rapi
        $suratByStatus = [
            'menunggu' => [],
            'diproses' => [],
            'selesai' => [],
            'ditolak' => []
        ];
        
        foreach ($surat as $s) {
            $status = $s['status'] ?? 'menunggu';
            if (isset($suratByStatus[$status])) {
                $suratByStatus[$status][] = $s;
            }
        }
        
        // Count statistik
        $stats = [
            'total' => count($surat),
            'menunggu' => count($suratByStatus['menunggu']),
            'diproses' => count($suratByStatus['diproses']),
            'selesai' => count($suratByStatus['selesai']),
            'ditolak' => count($suratByStatus['ditolak'])
        ];
        
        $data = [
            'title' => 'Pengajuan Surat Saya - Dashboard Warga',
            'surat' => $surat,
            'surat_by_status' => $suratByStatus,
            'stats' => $stats,
            'jenis_surat_list' => $this->suratModel->getListJenisSurat()
        ];
        
        return view('admin/warga_surat_list', $data);
    }

    /**
     * Form pengajuan surat baru
     */
    public function form($jenis = null)
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }
        
        // Daftar jenis surat yang tersedia
        $jenisSuratList = $this->suratModel->getListJenisSurat();
        
        // Validasi jenis surat jika dikirim
        if ($jenis && !array_key_exists($jenis, $jenisSuratList)) {
            $jenis = null;
        }
        
        $data = [
            'title' => 'Formulir Pengajuan Surat',
            'user' => $user,
            'jenis_surat_selected' => $jenis,
            'jenis_surat_list' => $jenisSuratList,
            'status_perkawinan_list' => $this->suratModel->getListStatusPerkawinan()
        ];
        
        return view('warga/form_pengajuan_surat', $data);
    }

    /**
     * Proses pengajuan surat (AJAX/POST handler)
     */
    public function proses()
    {
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Metode request tidak valid'
            ]);
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Validasi input
        $rules = [
            'jenis_surat' => 'required|in_list[domisili,tidak_mampu,kelahiran,kematian,pindah_nama,usaha,garapan,taksiran_harga_tanah,desa]',
            'nik' => 'required|exact_length[16]',
            'no_kk' => 'permit_empty|exact_length[16]',
            'keperluan' => 'required|min_length[10]'
        ];
        
        // Jika memilih SKD, status_perkawinan wajib
        if ($this->request->getPost('jenis_surat') === 'desa') {
            $rules['status_perkawinan'] = 'required';
        }
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Siapkan data
        $postData = [
            'user_id' => $userId,
            'jenis_surat' => $this->request->getPost('jenis_surat'),
            'nama_lengkap' => $user['nama_lengkap'],
            'nik' => $this->request->getPost('nik'),
            'no_kk' => $this->request->getPost('no_kk') ?? null,
            'alamat' => $user['alamat'],
            'keperluan' => $this->request->getPost('keperluan'),
            'status' => 'menunggu',
            'status_perkawinan' => $this->request->getPost('status_perkawinan') ?? null
        ];

        try {
            // Simpan pengajuan surat
            $suratId = $this->suratModel->insert($postData);
            
            if (!$suratId) {
                throw new \Exception('Gagal menyimpan pengajuan surat');
            }

            // Buat notifikasi untuk warga
            $this->notifikasiModel->insert([
                'user_id' => $userId,
                'tipe' => 'surat_terima',
                'judul' => 'Pengajuan Surat Diterima',
                'pesan' => 'Pengajuan ' . $this->suratModel->getJenisSuratText($postData['jenis_surat']) . ' Anda telah diterima. Admin akan memrosesnya dalam 1-2 hari kerja.',
                'status' => 'unread'
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pengajuan surat berhasil dikirim. Silakan tunggu proses dari admin.',
                'surat_id' => $suratId
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Lihat detail surat yang sudah diajukan
     */
    public function detail($suratId)
    {
        $userId = session()->get('user_id');
        
        // Get surat dan pastikan milik user ini
        $surat = $this->suratModel->find($suratId);
        
        if (!$surat || $surat['user_id'] != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Surat tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pengajuan Surat',
            'surat' => $surat,
            'jenis_surat_text' => $this->suratModel->getJenisSuratText($surat['jenis_surat'])
        ];

        return view('warga/detail_surat', $data);
    }

    /**
     * Download surat yang sudah selesai (PDF)
     */
    public function download($suratId)
    {
        $userId = session()->get('user_id');
        
        // Get surat
        $surat = $this->suratModel->find($suratId);
        
        if (!$surat || $surat['user_id'] != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Surat tidak ditemukan atau bukan milik Anda');
        }

        // Check status - hanya bisa didownload jika selesai
        if ($surat['status'] !== 'selesai') {
            return redirect()->back()->with('error', 'Surat belum siap diunduh. Status: ' . $surat['status']);
        }

        // Check file name
        if (empty($surat['file_surat'])) {
            return redirect()->back()->with('error', 'File surat belum tersedia');
        }

        // Check multiple possible locations for the file
        $possiblePaths = [
            WRITEPATH . 'uploads/surat_selesai/' . $surat['file_surat'],
            FCPATH . 'uploads/surat_selesai/' . $surat['file_surat'],
            WRITEPATH . 'uploads/' . $surat['file_surat'],
            FCPATH . 'uploads/' . $surat['file_surat']
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            log_message('error', 'File tidak ditemukan untuk surat ID: ' . $suratId . ' (file_surat: ' . $surat['file_surat'] . ')');
            return redirect()->back()->with('error', 'File surat tidak ditemukan di server');
        }

        // Log download
        log_message('info', 'Download surat: User ID: ' . $userId . ', Surat ID: ' . $suratId . ', File: ' . $filePath);

        // Download file dengan nama yang lebih deskriptif
        $jenisSuratText = $this->suratModel->getJenisSuratText($surat['jenis_surat'] ?? 'surat');
        $fileName = $surat['id'] . '_' . str_replace(' ', '_', $jenisSuratText) . '.pdf';

        return $this->response->download($filePath, $fileName);
    }

    /**
     * API: Get list surat berdasarkan filter (untuk AJAX)
     */
    public function listSurat()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $userId = session()->get('user_id');
        $status = $this->request->getGet('status');
        
        $surat = $this->suratModel->getSuratByUser($userId);
        
        if ($status) {
            $surat = array_filter($surat, fn($s) => $s['status'] === $status);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => array_values($surat),
            'count' => count($surat)
        ]);
    }
}
