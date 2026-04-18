<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class ErrorHandler extends Controller
{
    /**
     * Show 404 error page
     */
    public function show404()
    {
        $this->response->setStatusCode(404);
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Halaman tidak ditemukan'
            ]);
        }
        
        return view('errors/html/error_404', [
            'title' => 'Halaman Tidak Ditemukan',
            'message' => 'Halaman yang Anda cari tidak ditemukan.'
        ]);
    }
    
    /**
     * Show 403 error page
     */
    public function show403()
    {
        $this->response->setStatusCode(403);
        
        return view('errors/html/error_403', [
            'title' => 'Akses Ditolak',
            'message' => 'Anda tidak memiliki akses untuk mengunjungi halaman ini.'
        ]);
    }
    
    /**
     * Show 500 error page
     */
    public function show500()
    {
        $this->response->setStatusCode(500);
        
        return view('errors/html/error_500', [
            'title' => 'Server Error',
            'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
        ]);
    }
    
    /**
     * Show maintenance page
     */
    public function showMaintenance()
    {
        $this->response->setStatusCode(503);
        
        return view('errors/html/maintenance', [
            'title' => 'Sedang Maintenance',
            'message' => 'Situs sedang dalam tahap perbaikan. Silakan kunjungi kembali nanti.'
        ]);
    }
    
    /**
     * Show CSRF error page
     */
    public function showCSRFError()
    {
        $this->response->setStatusCode(403);
        
        return view('errors/html/error_403', [
            'title' => 'CSRF Error',
            'message' => 'Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.'
        ]);
    }
    
    /**
     * Show generic error page
     */
    public function showGenericError($code = 500, $message = null)
    {
        $this->response->setStatusCode($code);
        
        $errorMessages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable'
        ];
        
        $defaultMessage = $errorMessages[$code] ?? 'Terjadi kesalahan';
        
        return view('errors/html/error_generic', [
            'title' => "Error $code",
            'message' => $message ?? $defaultMessage,
            'code' => $code
        ]);
    }
}