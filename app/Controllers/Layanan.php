<?php

namespace App\Controllers;

use App\Models\SuratModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;

class Layanan extends BaseController
{
    protected $suratModel;
    protected $notifikasiModel;
    protected $userModel;

    public function __construct()
    {
        $this->suratModel = new SuratModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Layanan Online - Desa Tanjung Baru',
            'recent_applications' => []
        ];

        // If user is logged in, get recent applications
        if (session()->get('logged_in')) {
            $data['recent_applications'] = $this->suratModel
                ->where('user_id', session()->get('user_id'))
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->findAll();
        }

        return view('frontend/layanan_online_new', $data);
    }

    public function ajukan()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/layanan-online')->with('error', 'Silakan login terlebih dahulu untuk menggunakan layanan ini.');
        }

        $jenis = $this->request->getGet('jenis');
        $jenisValid = ['domisili', 'sktm', 'kelahiran', 'kematian', 'pindah_nama', 'usaha', 'garapan', 'taksiran_harga', 'skd'];
        
        if (!$jenis || !in_array($jenis, $jenisValid)) {
            return redirect()->to('/layanan-online')->with('error', 'Jenis surat yang dipilih tidak valid.');
        }

        // Define surat details
        $suratDetails = [
            'domisili' => [
                'display' => 'Surat Domisili',
                'description' => 'Surat keterangan domisili untuk berbagai keperluan administrasi',
                'icon' => 'home'
            ],
            'sktm' => [
                'display' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'description' => 'Surat keterangan tidak mampu untuk bantuan sosial dan pendidikan',
                'icon' => 'volunteer_activism'
            ],
            'kelahiran' => [
                'display' => 'Surat Kelahiran',
                'description' => 'Surat keterangan kelahiran dari kantor desa',
                'icon' => 'child_care'
            ],
            'kematian' => [
                'display' => 'Surat Kematian',
                'description' => 'Surat keterangan kematian untuk keperluan administrasi',
                'icon' => 'sentiment_very_dissatisfied'
            ],
            'pindah_nama' => [
                'display' => 'Surat Pindah Nama',
                'description' => 'Surat keterangan perubahan/pindah nama penduduk',
                'icon' => 'person_remove'
            ],
            'usaha' => [
                'display' => 'Surat Keterangan Usaha',
                'description' => 'Surat keterangan usaha untuk perizinan dan administrasi bisnis',
                'icon' => 'business'
            ],
            'garapan' => [
                'display' => 'Surat Garapan',
                'description' => 'Surat keterangan jaminan/garapan tanah atau harta benda',
                'icon' => 'gavel'
            ],
            'taksiran_harga' => [
                'display' => 'Surat Taksiran Harga Tanah',
                'description' => 'Surat perkiraan harga tanah/bangunan dari kantor desa',
                'icon' => 'local_florist'
            ],
            'skd' => [
                'display' => 'Surat Keterangan Desa (SKD)',
                'description' => 'Surat keterangan penduduk dari kantor desa untuk berbagai keperluan',
                'icon' => 'card_membership'
            ]
        ];

        $data = [
            'title' => 'Formulir Pengajuan Surat - Desa Tanjung Baru',
            'jenis_surat' => $jenis,
            'jenis_surat_display' => $suratDetails[$jenis]['display'],
            'surat_description' => $suratDetails[$jenis]['description'],
            'surat_icon' => $suratDetails[$jenis]['icon']
        ];

        return view('frontend/layanan_form_new', $data);
    }

    public function ajukanSurat($jenis = null)
    {
        // Redirect to new ajukan method for backward compatibility
        return redirect()->to('/layanan-online/ajukan?jenis=' . $jenis);
    }

    public function submit()
    {
        log_message('info', '=== SUBMIT FUNCTION CALLED ===');
        log_message('info', 'Request method: ' . $this->request->getMethod());
        log_message('info', 'User logged_in: ' . (session()->get('logged_in') ? 'YES' : 'NO'));
        log_message('info', 'User role: ' . session()->get('role'));
        log_message('info', 'User ID: ' . session()->get('user_id'));
        log_message('info', 'Request URI: ' . $this->request->getUri());
        
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            log_message('warning', 'Submit called but user not logged in, redirecting to layanan-online');
            session()->setFlashdata('error', 'Silakan login terlebih dahulu untuk mengajukan surat.');
            return redirect()->to('/layanan-online');
        }

        // FIX: getMethod() returns uppercase 'POST', not lowercase 'post'
        if ($this->request->getMethod() === 'POST') {
            // Log all POST data for debugging (hide sensitive data)
            $postData = $this->request->getPost();
            $postDataLog = $postData;
            if (isset($postDataLog['nik'])) {
                $postDataLog['nik'] = substr($postDataLog['nik'], 0, 4) . '****'; // Mask NIK
            }
            log_message('info', 'Surat submission attempt. POST data: ' . json_encode($postDataLog));
            
            // Get jenis_surat from POST data
            $jenis = $this->request->getPost('jenis_surat');
            
            if (!$jenis) {
                log_message('warning', 'Jenis surat tidak ditemukan di POST data');
                session()->setFlashdata('error', 'Jenis surat tidak valid. Silakan pilih jenis surat terlebih dahulu.');
                return redirect()->to('/layanan-online');
            }
            
            // Map form values to database enum values
            $jenisMapping = [
                'domisili' => 'domisili',
                'sktm' => 'tidak_mampu',
                'kelahiran' => 'kelahiran',
                'kematian' => 'kematian',
                'pindah_nama' => 'pindah_nama',
                'usaha' => 'usaha',
                'garapan' => 'garapan',
                'taksiran_harga' => 'taksiran_harga',
                'skd' => 'desa'
            ];
            
            // Convert jenis to database value
            $jenisSuratDb = $jenisMapping[$jenis] ?? null;
            if (!$jenisSuratDb) {
                log_message('warning', 'Jenis surat tidak valid: ' . $jenis);
                session()->setFlashdata('error', 'Jenis surat tidak valid: ' . $jenis);
                return redirect()->to('/layanan-online');
            }
            
            log_message('info', 'Jenis surat mapped: ' . $jenis . ' -> ' . $jenisSuratDb);
            log_message('info', 'User ID from session: ' . session()->get('user_id'));
            
            // Simplified validation - only check required text fields
            $rules = [
                'nama_lengkap' => 'required|min_length[3]',
                'nik' => 'required|exact_length[16]',
                'no_kk' => 'permit_empty|exact_length[16]',
                'alamat' => 'required',
                'telepon' => 'required|min_length[10]',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required|valid_date',
                'jenis_kelamin' => 'required|in_list[Laki-laki,Perempuan]',
                'keperluan' => 'required|min_length[10]'
            ];
            
            // Skip file validation for now - files are optional
            $fileKtp = $this->request->getFile('file_ktp');
            $fileKk = $this->request->getFile('file_kk');
            $filePengantar = $this->request->getFile('file_pengantar');

            
            if ($this->validate($rules)) {
                log_message('info', 'Validation passed for surat submission');
                
                // Skip file upload processing for now - focus on getting basic submission working
                // Files will be handled in future update
                
                $userId = session()->get('user_id');
                if (!$userId) {
                    log_message('error', 'User ID not found in session during surat submission');
                    session()->setFlashdata('error', 'Sesi login tidak valid. Silakan login kembali.');
                    return redirect()->to('/auth/login');
                }
                
                // Prepare keperluan text - gabungkan semua field form jadi format terstruktur
                $keperluan = $this->request->getPost('keperluan');
                $statusKawin = $this->request->getPost('status_kawin');
                $deskripsiSkd = $this->request->getPost('deskripsi_skd');
                $tempat_lahir = $this->request->getPost('tempat_lahir');
                $tanggal_lahir = $this->request->getPost('tanggal_lahir');
                $jenis_kelamin = $this->request->getPost('jenis_kelamin');
                $no_kk = $this->request->getPost('no_kk');
                $telepon = $this->request->getPost('telepon');
                
                // Build detailed info text with all form fields
                $detailInfo = [
                    "=== DATA FORM PENGAJUAN ===",
                    "Jenis Kelamin: " . $jenis_kelamin,
                    "Tempat Lahir: " . $tempat_lahir,
                    "Tanggal Lahir: " . (!empty($tanggal_lahir) ? date('d M Y', strtotime($tanggal_lahir)) : '-'),
                    "Nomor KK: " . (!empty($no_kk) ? $no_kk : '-'),
                    "Telepon: " . $telepon
                ];
                
                if ($jenis === 'skd') {
                    $detailInfo[] = "Status Perkawinan: " . $statusKawin;
                    if (!empty($deskripsiSkd)) {
                        $detailInfo[] = "Detail SKD: " . $deskripsiSkd;
                    }
                }
                
                $detailInfo[] = "\n=== KEPERLUAN ===";
                $detailInfo[] = $keperluan;
                
                $keperluan = implode("\n", $detailInfo);
                
                $suratData = [
                    'user_id' => $userId,
                    'jenis_surat' => $jenisSuratDb,  // Use database enum value
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'nik' => $this->request->getPost('nik'),
                    'alamat' => $this->request->getPost('alamat'),
                    'keperluan' => $keperluan,
                    'status' => 'menunggu'
                ];
                
                // Mask NIK for logging
                $logData = $suratData;
                $logData['nik'] = substr($logData['nik'], 0, 4) . '****';
                log_message('info', 'Attempting to insert surat data: ' . json_encode($logData));

                try {
                    $suratId = $this->suratModel->insert($suratData);
                    
                    if ($suratId) {
                        log_message('info', 'Surat inserted successfully  with ID: ' . $suratId);
                        
                        // Try to create notification for admin
                        try {
                            $userData = $this->userModel->find($userId);
                            $jenisSuratText = $this->suratModel->getJenisSuratText($jenisSuratDb);
                            
                            if (!empty($userData)) {
                                $this->notifikasiModel->notifikasiSuratBaru(
                                    $suratId,
                                    $userId,
                                    $userData['nama_lengkap'] ?? 'User',
                                    $jenisSuratText
                                );
                                log_message('info', 'Notification created for admin about surat ID: ' . $suratId);
                            }
                        } catch (\Exception $notifError) {
                            // Don't fail the whole submission if notification fails
                            log_message('error', 'Failed to create notification: ' . $notifError->getMessage());
                        }
                        
                        log_message('info', 'Surat submission completed successfully. Redirecting to dashboard.');
                        session()->setFlashdata('success', 'Permohonan surat berhasil diajukan! Silakan tunggu verifikasi dari admin.');
                        session()->setFlashdata('show_success_modal', true);
                        return redirect()->to('/dashboard');
                    } else {
                        // Log validation errors if insert failed
                        $errors = $this->suratModel->errors();
                        log_message('error', 'Surat insert returned false. Model validation errors: ' . json_encode($errors));
                        
                        $errorMsg = 'Gagal menyimpan permohonan surat.';
                        if (is_array($errors) && !empty($errors)) {
                            $errorMsg .= ' ' . implode(', ', $errors);
                        }
                        
                        session()->setFlashdata('error', $errorMsg);
                        session()->setFlashdata('errors', $errors);
                        return redirect()->back()->withInput();
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Exception during surat insert: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    
                    $errorMsg = 'Terjadi kesalahan sistem saat mengajukan surat.';
                    if (ENVIRONMENT === 'development') {
                        $errorMsg .= ' Detail: ' . $e->getMessage();
                    }
                    
                    session()->setFlashdata('error', $errorMsg);
                    return redirect()->back()->withInput();
                }
            } else {
                // Validation failed - redirect back with errors
                $errors = $this->validator->getErrors();
                log_message('warning', 'Surat submission validation failed');
                log_message('warning', 'Validation errors: ' . json_encode($errors));
                
                session()->setFlashdata('error', 'Terdapat kesalahan pada pengisian form. Silakan periksa kembali.');
                session()->setFlashdata('errors', $errors);
                return redirect()->back()->withInput();
            }
        }

        // If not POST request, redirect to layanan-online page
        return redirect()->to('/layanan-online');
    }

    /**
     * Check status surat untuk AJAX calls
     */
    public function checkStatus($id)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        $surat = $this->suratModel->where('user_id', $userId)->find($id);

        if (!$surat) {
            return $this->response->setJSON(['error' => 'Surat tidak ditemukan']);
        }

        return $this->response->setJSON([
            'id' => $surat['id'],
            'status' => $surat['status'],
            'updated_at' => $surat['updated_at'],
            'catatan_admin' => $surat['pesan_admin'] ?? null,
            'jenis_surat' => $surat['jenis_surat']
        ]);
    }

    /**
     * Download surat yang sudah selesai
     */
    public function download($id)
    {
        if (!session()->get('logged_in')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Akses ditolak');
        }

        $userId = session()->get('user_id');
        $surat = $this->suratModel->where('user_id', $userId)->find($id);

        if (!$surat || !in_array($surat['status'], ['selesai', 'disetujui', 'approved'], true) || !$surat['file_surat']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/surat/' . $surat['file_surat'];
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }

        return $this->response->download($filePath, null);
    }
}