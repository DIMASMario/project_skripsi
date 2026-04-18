<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

        /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['text', 'url', 'form', 'html', 'notification'];

    /**
     * Common properties for all controllers
     */
    protected $settingsModel;
    protected $currentUser;
    protected $siteSettings;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->settingsModel = new \App\Models\SettingsModel();
        
        // Load current user data if logged in
        if (session()->get('logged_in')) {
            $userModel = new \App\Models\UserModel();
            $this->currentUser = $userModel->find(session()->get('user_id'));
        }
        
        // Load site settings untuk global access (with graceful fallback if settings table missing)
        try {
            $this->siteSettings = $this->settingsModel->getAllSettings();
        } catch (\Exception $e) {
            // If settings table doesn't exist yet, use empty array as fallback
            log_message('warning', 'Settings table not found: ' . $e->getMessage());
            $this->siteSettings = [];
        }
        
        // Make settings available in all views
        service('renderer')->setData([
            'siteSettings' => $this->siteSettings,
            'currentUser' => $this->currentUser
        ]);
        
        // Track visitor (setiap halaman diakses)
        $this->trackVisitor();
        
        // Auto-cleanup notifikasi lama (1x per hari)
        $this->cleanupOldNotifications();
    }
    
    /**
     * Track visitor untuk statistik
     */
    private function trackVisitor()
    {
        // Jangan track jika CLI request atau AJAX request
        if (is_cli() || $this->request->isAJAX()) {
            return;
        }
        
        // Cek apakah sudah track dalam session ini (untuk page yg sama)
        $currentUri = (string) $this->request->getUri();
        $lastTrackedUri = session()->get('last_tracked_uri');
        $lastTrackedTime = session()->get('last_tracked_time');
        
        // Jika URI sama dan baru track < 60 detik, skip
        if ($lastTrackedUri === $currentUri && $lastTrackedTime && (time() - $lastTrackedTime) < 60) {
            return;
        }
        
        try {
            $visitorModel = new \App\Models\VisitorLogModel();
            
            $data = [
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'page_visited' => $this->request->getUri()->getPath() ?: 'home',
                'session_id' => session()->session_id,
                'visited_at' => date('Y-m-d H:i:s')
            ];
            
            $visitorModel->insert($data);
            
            // Update session tracker
            session()->set('last_tracked_uri', $currentUri);
            session()->set('last_tracked_time', time());
            
        } catch (\Exception $e) {
            // Silent fail - jangan ganggu user experience
            log_message('error', 'Failed to track visitor: ' . $e->getMessage());
        }
    }
    
    /**
     * Cleanup notifikasi yang sudah dibaca > 24 jam
     * Hanya berjalan 1x per hari
     */
    private function cleanupOldNotifications()
    {
        $lastCleanup = cache('last_notif_cleanup');
        $now = time();
        
        // Jika belum pernah cleanup atau sudah > 24 jam
        if (!$lastCleanup || ($now - $lastCleanup) > 86400) {
            $notifModel = new \App\Models\NotifikasiModel();
            $notifModel->deleteOldReadNotifications();
            cache()->save('last_notif_cleanup', $now, 86400);
        }
    }

    /**
     * Security helper untuk validasi file upload
     */
    protected function validateImageUpload($file, $maxSize = 5242880, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
    {
        if (!$file || !$file->isValid()) {
            return ['success' => false, 'message' => 'File tidak valid'];
        }
        
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return ['success' => false, 'message' => 'File harus berupa gambar (JPEG, PNG, GIF, WebP)'];
        }
        
        if ($file->getSize() > $maxSize) {
            $maxMB = round($maxSize / 1024 / 1024, 1);
            return ['success' => false, 'message' => "Ukuran file maksimal {$maxMB}MB"];
        }
        
        return ['success' => true];
    }

    /**
     * Helper untuk generate safe filename
     */
    protected function generateSafeFilename($prefix = 'file', $extension = 'jpg')
    {
        return $prefix . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Helper untuk create upload directory
     */
    protected function ensureUploadDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        return is_dir($path);
    }
}
