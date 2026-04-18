<?php

/**
 * Security Helper Functions
 * 
 * Helper functions untuk meningkatkan keamanan aplikasi
 * Meliputi: file upload validation, rate limiting, safe file operations
 */

if (!function_exists('validateUploadedFile')) {
    /**
     * Validasi file upload dengan comprehensive checks
     * 
     * @param mixed $file File dari $this->request->getFile()
     * @param array|null $allowedTypes Array of allowed MIME types
     * @param int $maxSize Maximum file size in bytes (default 5MB)
     * @return array ['valid' => bool, 'error' => string|null]
     */
    function validateUploadedFile($file, $allowedTypes = null, $maxSize = 5242880)
    {
        // Check if file exists and is valid
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return ['valid' => false, 'error' => 'File tidak valid atau sudah dipindahkan'];
        }

        // Default allowed types untuk image
        if ($allowedTypes === null) {
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        }

        // Check MIME type
        $fileMime = $file->getMimeType();
        if (!in_array($fileMime, $allowedTypes)) {
            $allowedList = implode(', ', array_map(function($mime) {
                return str_replace('image/', '', $mime);
            }, $allowedTypes));
            return [
                'valid' => false, 
                'error' => 'Tipe file tidak diizinkan. Hanya: ' . strtoupper($allowedList)
            ];
        }

        // Check file size
        if ($file->getSize() > $maxSize) {
            $maxSizeMB = round($maxSize / 1024 / 1024, 2);
            return [
                'valid' => false, 
                'error' => 'Ukuran file maksimal ' . $maxSizeMB . 'MB'
            ];
        }

        // Check extension matches MIME type (prevent double extension attack)
        $extension = strtolower($file->getExtension());
        
        // Map MIME types to valid extensions
        $mimeToExt = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/jpg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'image/webp' => ['webp'],
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        ];

        $validExtensions = $mimeToExt[$fileMime] ?? [];
        
        if (!empty($validExtensions) && !in_array($extension, $validExtensions)) {
            return [
                'valid' => false, 
                'error' => 'Extension file tidak sesuai dengan tipe file. Expected: ' . implode(', ', $validExtensions)
            ];
        }

        // Additional check: For images, verify it's really an image using getimagesize
        if (strpos($fileMime, 'image/') === 0) {
            $imageInfo = @getimagesize($file->getTempName());
            if ($imageInfo === false) {
                return [
                    'valid' => false, 
                    'error' => 'File bukan gambar yang valid. File mungkin corrupt atau berbahaya.'
                ];
            }
            
            // Check image dimensions if needed (optional)
            // $maxWidth = 4000;
            // $maxHeight = 4000;
            // if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
            //     return [
            //         'valid' => false,
            //         'error' => 'Dimensi gambar terlalu besar. Maksimal ' . $maxWidth . 'x' . $maxHeight . 'px'
            //     ];
            // }
        }

        return ['valid' => true, 'error' => null];
    }
}

if (!function_exists('generateSafeFilename')) {
    /**
     * Generate filename yang aman untuk upload
     * 
     * @param string $originalName Original filename
     * @param string $prefix Prefix untuk filename (optional)
     * @return string Safe filename
     */
    function generateSafeFilename($originalName, $prefix = '')
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $extension = strtolower(preg_replace('/[^a-z0-9]/i', '', $extension));
        
        // Whitelist extension yang diizinkan
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
        
        if (!in_array($extension, $allowedExt)) {
            $extension = 'bin'; // Default extension untuk file unknown
        }
        
        // Generate unique filename dengan timestamp dan random string
        $prefix = preg_replace('/[^a-z0-9_-]/i', '', $prefix);
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        
        return ($prefix ? $prefix . '_' : '') . $timestamp . '_' . $random . '.' . $extension;
    }
}

if (!function_exists('safeUnlink')) {
    /**
     * Hapus file dengan aman (mencegah directory traversal)
     * 
     * @param string $filePath Path file yang akan dihapus
     * @param string $allowedDir Directory yang diizinkan (default: uploads/)
     * @return bool True jika berhasil, false jika gagal
     */
    function safeUnlink($filePath, $allowedDir = null)
    {
        if ($allowedDir === null) {
            $allowedDir = FCPATH . 'uploads/';
        }
        
        // Normalize paths untuk mencegah directory traversal
        $filePath = realpath($filePath);
        $allowedDir = realpath($allowedDir);
        
        // Check if realpath failed (file/dir not exists)
        if ($filePath === false || $allowedDir === false) {
            log_message('warning', 'SafeUnlink: Invalid path - File: ' . $filePath . ', Allowed: ' . $allowedDir);
            return false;
        }
        
        // Check if file is within allowed directory
        if (strpos($filePath, $allowedDir) !== 0) {
            log_message('error', 'SafeUnlink: Attempted to delete file outside allowed directory: ' . $filePath);
            return false;
        }
        
        // Check if it's actually a file (not directory)
        if (!is_file($filePath)) {
            log_message('warning', 'SafeUnlink: Path is not a file: ' . $filePath);
            return false;
        }
        
        // Try to delete
        if (@unlink($filePath)) {
            log_message('info', 'SafeUnlink: Successfully deleted file: ' . $filePath);
            return true;
        } else {
            log_message('error', 'SafeUnlink: Failed to delete file: ' . $filePath);
            return false;
        }
    }
}

if (!function_exists('checkRateLimit')) {
    /**
     * Check rate limiting untuk mencegah brute force
     * 
     * @param string $key Unique key untuk rate limit (e.g., 'login_' . IP)
     * @param int $maxAttempts Maximum attempts allowed
     * @param int $decayMinutes Time window in minutes
     * @return bool True jika masih dalam limit, false jika exceeded
     */
    function checkRateLimit($key, $maxAttempts = 5, $decayMinutes = 15)
    {
        $cache = \Config\Services::cache();
        
        // Sanitize cache key - remove reserved characters {}()/\@:
        $key = preg_replace('/[{}()\\/\\\\@:]/', '_', $key);
        
        // Get current attempts
        $attempts = $cache->get($key);
        
        if ($attempts === null) {
            // First attempt
            $cache->save($key, 1, $decayMinutes * 60);
            return true;
        }
        
        // Check if exceeded
        if ($attempts >= $maxAttempts) {
            log_message('warning', 'Rate limit exceeded for key: ' . $key);
            return false;
        }
        
        // Increment attempts
        $cache->save($key, $attempts + 1, $decayMinutes * 60);
        return true;
    }
}

if (!function_exists('getRateLimitRemaining')) {
    /**
     * Get remaining attempts untuk rate limit
     * 
     * @param string $key Unique key untuk rate limit
     * @param int $maxAttempts Maximum attempts allowed
     * @return int Remaining attempts
     */
    function getRateLimitRemaining($key, $maxAttempts = 5)
    {
        $cache = \Config\Services::cache();
        
        // Sanitize cache key - remove reserved characters {}()/\@:
        $key = preg_replace('/[{}()\/\\\\@:]/', '_', $key);
        
        $attempts = $cache->get($key) ?? 0;
        return max(0, $maxAttempts - $attempts);
    }
}

if (!function_exists('resetRateLimit')) {
    /**
     * Reset rate limit counter (e.g., setelah login berhasil)
     * 
     * @param string $key Unique key untuk rate limit
     * @return bool
     */
    function resetRateLimit($key)
    {
        $cache = \Config\Services::cache();
        
        // Sanitize cache key - remove reserved characters {}()/\@:
        $key = preg_replace('/[{}()\/\\\\@:]/', '_', $key);
        
        return $cache->delete($key);
    }
}

if (!function_exists('sanitizeFilename')) {
    /**
     * Sanitize filename untuk mencegah injection
     * 
     * @param string $filename Original filename
     * @return string Sanitized filename
     */
    function sanitizeFilename($filename)
    {
        // Remove directory traversal
        $filename = basename($filename);
        
        // Remove special characters, keep only alphanumeric, dash, underscore, dot
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple dots (prevent double extension)
        $filename = preg_replace('/\.+/', '.', $filename);
        
        // Limit length
        if (strlen($filename) > 200) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = substr($filename, 0, 200 - strlen($ext) - 1);
            $filename = $name . '.' . $ext;
        }
        
        return $filename;
    }
}

if (!function_exists('isImageSafe')) {
    /**
     * Advanced image validation untuk detect malicious files
     * 
     * @param string $filePath Path to file
     * @return bool True if safe, false if suspicious
     */
    function isImageSafe($filePath)
    {
        if (!file_exists($filePath)) {
            return false;
        }
        
        // Check with getimagesize
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }
        
        // Check for PHP code in image (basic check)
        $content = file_get_contents($filePath, false, null, 0, 1024); // Read first 1KB
        
        // Look for PHP tags
        if (preg_match('/<\?php|<\?=|<script/i', $content)) {
            log_message('critical', 'Malicious code detected in uploaded image: ' . $filePath);
            return false;
        }
        
        return true;
    }
}

if (!function_exists('logSecurityEvent')) {
    /**
     * Log security-related events
     * 
     * @param string $event Event name
     * @param string $description Event description
     * @param array $context Additional context
     * @return void
     */
    function logSecurityEvent($event, $description, $context = [])
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $userId = session()->get('user_id') ?? 'guest';
        
        $logData = [
            'event' => $event,
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'user_id' => $userId,
            'timestamp' => date('Y-m-d H:i:s'),
            'context' => $context
        ];
        
        log_message('info', 'SECURITY_EVENT: ' . json_encode($logData));
        
        // TODO: Save to database table 'security_logs' if exists
        // $db = \Config\Database::connect();
        // $db->table('security_logs')->insert($logData);
    }
}
