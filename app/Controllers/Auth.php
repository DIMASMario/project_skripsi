<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\NotifikasiModel;
use App\Models\KodeRegistrasiModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $notifikasiModel;
    protected $kodeRegistrasiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->kodeRegistrasiModel = new KodeRegistrasiModel();
    }

    /**
     * Login dengan Email atau Nomor HP (TANPA NIK!)
     */
    public function login()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            return redirect()->to($role === 'admin' ? '/admin' : '/dashboard');
        }

        $data = ['title' => 'Login - Website Desa Blanakan'];

        if ($this->request->getMethod() === 'POST') {
            // Rate limiting - encode IP to avoid cache key issues with IPv6
            $rateLimitKey = 'login_attempts_' . md5($this->request->getIPAddress());
            
            if (!$this->checkRateLimit($rateLimitKey, 5, 15)) {
                session()->setFlashdata('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam 15 menit.');
                return redirect()->back();
            }

            $rules = [
                'identifier' => 'required',
                'password' => 'required'
            ];

            if ($this->validate($rules)) {
                $identifier = $this->request->getPost('identifier');
                $password = $this->request->getPost('password');

                // Get user by email or phone
                $user = $this->userModel->getUserByEmailOrPhone($identifier);

                if ($user && password_verify($password, $user['password'])) {
                    // Check user status
                    if ($user['status'] === 'menunggu') {
                        session()->setFlashdata('warning', 'Akun Anda masih menunggu verifikasi dari admin.');
                        return redirect()->back();
                    }

                    if ($user['status'] === 'ditolak') {
                        session()->setFlashdata('error', 'Akun Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.');
                        return redirect()->back();
                    }

                    // Reset rate limit on successful login
                    $this->resetRateLimit($rateLimitKey);

                    // Regenerate session ID for security (prevent session fixation)
                    session()->regenerate(true);
                    
                    // Set session
                    session()->set([
                        'user_id' => $user['id'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'status' => $user['status'],
                        'logged_in' => true
                    ]);

                    log_message('info', "User login: {$user['nama_lengkap']} ({$user['email']})");

                    // Redirect based on role
                    $redirectTo = $user['role'] === 'admin' ? '/admin' : '/dashboard';
                    return redirect()->to($redirectTo);
                } else {
                    // Increment rate limit only on FAILED login
                    $this->incrementRateLimit($rateLimitKey, 15);
                    
                    log_message('warning', "Login gagal untuk identifier: {$identifier} dari IP: " . $this->request->getIPAddress());
                    session()->setFlashdata('error', 'Email/No HP atau password salah.');
                    return redirect()->back()->withInput();
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('auth/login', $data);
    }

    /**
     * Registrasi dengan Kode Registrasi (TANPA NIK!)
     */
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = ['title' => 'Registrasi Warga - Website Desa Blanakan'];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama_lengkap' => 'required|min_length[3]|max_length[100]',
                'alamat' => 'required|min_length[10]',
                'rt' => 'required|numeric|max_length[3]',
                'rw' => 'required|numeric|max_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'no_hp' => 'required|min_length[10]|max_length[15]|numeric|is_unique[users.no_hp]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]'
            ];

            $messages = [
                'nama_lengkap' => [
                    'required' => 'Nama lengkap harus diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter'
                ],
                'alamat' => [
                    'required' => 'Alamat harus diisi',
                    'min_length' => 'Alamat minimal 10 karakter'
                ],
                'rt' => [
                    'required' => 'RT harus diisi',
                    'numeric' => 'RT harus berupa angka'
                ],
                'rw' => [
                    'required' => 'RW harus diisi',
                    'numeric' => 'RW harus berupa angka'
                ],
                'email' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ],
                'no_hp' => [
                    'required' => 'Nomor HP harus diisi',
                    'min_length' => 'Nomor HP minimal 10 digit',
                    'numeric' => 'Nomor HP hanya boleh angka',
                    'is_unique' => 'Nomor HP sudah terdaftar'
                ],
                'password' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ],
                'password_confirm' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ];

            if ($this->validate($rules, $messages)) {
                $rt = $this->request->getPost('rt');
                $rw = $this->request->getPost('rw');

                // Prepare user data
                $userData = [
                    'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
                    'alamat' => trim($this->request->getPost('alamat')),
                    'rt' => $rt,
                    'rw' => $rw,
                    'email' => trim($this->request->getPost('email')),
                    'no_hp' => $this->request->getPost('no_hp'),
                    'password' => $this->request->getPost('password'),
                    'role' => 'warga',
                    'status' => 'menunggu',
                    'kelurahan_desa' => 'Blanakan',
                    'kecamatan' => 'Blanakan',
                    'kota_kabupaten' => 'Subang',
                    'provinsi' => 'Jawa Barat'
                ];

                try {
                    // Insert user
                    if ($this->userModel->insert($userData)) {
                        // Create notification for admin
                        $this->notifikasiModel->createAdminNotification(
                            null,
                            'admin',
                            "Warga baru {$userData['nama_lengkap']} (RT {$rt} RW {$rw}) telah mendaftar dan menunggu verifikasi."
                        );

                        log_message('info', "New user registered: {$userData['nama_lengkap']}");

                        session()->setFlashdata('success', 'Registrasi berhasil! Anda akan menerima email konfirmasi setelah akun diverifikasi oleh admin desa.');
                        return redirect()->to('/auth/login');
                    } else {
                        $errors = $this->userModel->errors();
                        $errorMsg = is_array($errors) ? implode(', ', $errors) : 'Gagal menyimpan data';
                        session()->setFlashdata('error', 'Registrasi gagal: ' . $errorMsg);
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Registration error: ' . $e->getMessage());
                    session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            } else {
                $data['validation'] = $this->validator;
                session()->setFlashdata('error', 'Mohon periksa kembali input Anda');
            }
        }

        return view('auth/register', $data);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $userName = session()->get('nama_lengkap');
        
        log_message('info', "User logout: {$userName}");
        
        // Remove all session data first
        session()->remove(['user_id', 'nama_lengkap', 'email', 'role', 'status', 'logged_in', 'isLoggedIn']);
        
        // Destroy session completely
        session()->destroy();
        
        // Start a fresh session for flashdata
        // (session() will auto-start a new session)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session()->setFlashdata('success', 'Anda telah berhasil logout.');
        
        // DO NOT manually delete CSRF or session cookies
        // CI4 handles cookie lifecycle automatically
        // Manually deleting them causes token mismatch on next login
        
        return redirect()->to('/');
    }

    /**
     * Simple rate limit check (only counts FAILED attempts)
     */
    private function checkRateLimit($key, $maxAttempts, $decayMinutes)
    {
        $cache = \Config\Services::cache();
        $attempts = $cache->get($key) ?: 0;

        if ($attempts >= $maxAttempts) {
            return false;
        }

        // Don't increment here - only increment on FAILED login
        // See incrementRateLimit() and resetRateLimit()
        return true;
    }

    /**
     * Increment rate limit counter (call on failed login)
     */
    private function incrementRateLimit($key, $decayMinutes)
    {
        $cache = \Config\Services::cache();
        $attempts = $cache->get($key) ?: 0;
        $cache->save($key, $attempts + 1, $decayMinutes * 60);
    }

    /**
     * Reset rate limit counter (call on successful login)
     */
    private function resetRateLimit($key)
    {
        $cache = \Config\Services::cache();
        $cache->delete($key);
    }

    /**
     * Admin Login (separate from warga)
     */
    public function adminLogin()
    {
        if (session()->get('logged_in') && session()->get('role') === 'admin') {
            return redirect()->to('/admin');
        }

        $data = ['title' => 'Admin Login - Website Desa Blanakan'];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'identifier' => 'required',
                'password' => 'required'
            ];

            if ($this->validate($rules)) {
                $identifier = $this->request->getPost('identifier');
                $password = $this->request->getPost('password');

                // Get user
                $user = $this->userModel->getUserByEmailOrPhone($identifier);
                
                if (!$user) {
                    session()->setFlashdata('error', 'Email/No HP tidak ditemukan.');
                    return redirect()->back()->withInput();
                }

                if ($user['role'] !== 'admin') {
                    session()->setFlashdata('error', 'Anda bukan admin. Silakan login di halaman warga.');
                    return redirect()->back()->withInput();
                }

                if (password_verify($password, $user['password'])) {
                    // Reset rate limit on successful login
                    $this->resetRateLimit('login_attempts_' . md5($this->request->getIPAddress()));
                    
                    // Regenerate session ID for security
                    session()->regenerate(true);
                    
                    session()->set([
                        'user_id' => $user['id'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ]);

                    log_message('info', "Admin login: {$user['nama_lengkap']} ({$user['email']})");
                    
                    return redirect()->to('/admin');
                } else {
                    $this->incrementRateLimit('login_attempts_' . md5($this->request->getIPAddress()), 15);
                    log_message('warning', "Admin login gagal untuk: {$identifier} dari IP: " . $this->request->getIPAddress());
                    session()->setFlashdata('error', 'Password salah.');
                    return redirect()->back()->withInput();
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('auth/admin_login', $data);
    }

    // Legacy methods - redirected to new system
    public function registerStep1()
    {
        return redirect()->to('/auth/register');
    }

    public function registerStep2()
    {
        return redirect()->to('/auth/register');
    }

    public function registerStep3()
    {
        return redirect()->to('/auth/register');
    }

    public function registerReview()
    {
        return redirect()->to('/auth/register');
    }

    public function registerSuccess()
    {
        return redirect()->to('/auth/login');
    }

    public function loginSelection()
    {
        return redirect()->to('/auth/login');
    }
}
