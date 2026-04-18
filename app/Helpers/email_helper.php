<?php

/**
 * Email Helper Functions
 * Simplified email sending for Desa Blanakan system
 */

if (!function_exists('send_email')) {
    /**
     * Send email using configured SMTP
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message Email message (HTML supported)
     * @param bool $isHtml Whether message is HTML (default: true)
     * @return bool True on success, false on failure
     */
    function send_email(string $to, string $subject, string $message, bool $isHtml = true): bool
    {
        $email = \Config\Services::email();
        
        try {
            $email->setTo($to);
            $email->setSubject($subject);
            
            if ($isHtml) {
                $email->setMailType('html');
            }
            
            $email->setMessage($message);
            
            if ($email->send()) {
                log_message('info', "Email sent successfully to: {$to}, subject: {$subject}");
                return true;
            } else {
                log_message('error', "Failed to send email to: {$to}. Error: " . $email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "Email exception: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('send_account_approved_email')) {
    /**
     * Send email when user account is approved
     * 
     * @param array $user User data array
     * @return bool
     */
    function send_account_approved_email(array $user): bool
    {
        if (empty($user['email'])) {
            return false;
        }

        $subject = '✅ Akun Desa Blanakan Telah Aktif';
        
        $message = email_template_account_approved($user);
        
        return send_email($user['email'], $subject, $message);
    }
}

if (!function_exists('send_account_rejected_email')) {
    /**
     * Send email when user account is rejected
     * 
     * @param array $user User data array
     * @param string $reason Rejection reason
     * @return bool
     */
    function send_account_rejected_email(array $user, string $reason = ''): bool
    {
        if (empty($user['email'])) {
            return false;
        }

        $subject = '❌ Pengajuan Akun Desa Blanakan';
        
        $message = email_template_account_rejected($user, $reason);
        
        return send_email($user['email'], $subject, $message);
    }
}

if (!function_exists('send_surat_completed_email')) {
    /**
     * Send email when surat is completed
     * 
     * @param array $user User data array
     * @param array $surat Surat data array
     * @return bool
     */
    function send_surat_completed_email(array $user, array $surat): bool
    {
        if (empty($user['email'])) {
            return false;
        }

        $subject = '📄 Surat Anda Telah Selesai';
        
        $message = email_template_surat_completed($user, $surat);
        
        return send_email($user['email'], $subject, $message);
    }
}

if (!function_exists('send_surat_rejected_email')) {
    /**
     * Send email when surat is rejected
     * 
     * @param array $user User data array
     * @param array $surat Surat data array
     * @return bool
     */
    function send_surat_rejected_email(array $user, array $surat): bool
    {
        if (empty($user['email'])) {
            return false;
        }

        $subject = '❌ Pengajuan Surat Perlu Perbaikan';
        
        $message = email_template_surat_rejected($user, $surat);
        
        return send_email($user['email'], $subject, $message);
    }
}

// ====================
// EMAIL TEMPLATES
// ====================

if (!function_exists('email_template_account_approved')) {
    function email_template_account_approved(array $user): string
    {
        $baseUrl = base_url();
        $loginUrl = base_url('auth/login');
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #0c5c8c 0%, #0a4a6e 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .button { display: inline-block; padding: 12px 30px; background: #0c5c8c; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .info-box { background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat!</h1>
            <p>Akun Anda Telah Disetujui</p>
        </div>
        <div class="content">
            <p>Halo <strong>{$user['nama_lengkap']}</strong>,</p>
            
            <p>Selamat! Akun Anda di <strong>Website Desa Blanakan</strong> telah diverifikasi dan disetujui oleh admin desa.</p>
            
            <div class="info-box">
                <strong>📧 Email Login:</strong> {$user['email']}<br>
                <strong>🔐 Password:</strong> [Password yang Anda buat saat registrasi]
            </div>
            
            <p>Anda sekarang dapat menggunakan berbagai layanan online kami:</p>
            <ul>
                <li>✅ Pengajuan surat online</li>
                <li>✅ Cek status surat</li>
                <li>✅ Informasi & pengumuman desa</li>
                <li>✅ Download dokumen</li>
            </ul>
            
            <center>
                <a href="{$loginUrl}" class="button">Login Sekarang</a>
            </center>
            
            <p style="margin-top: 30px; font-size: 13px; color: #666;">
                Jika Anda mengalami kesulitan login, silakan hubungi admin desa atau datang langsung ke kantor desa.
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.<br>
            Website Desa Blanakan © 2026</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}

if (!function_exists('email_template_account_rejected')) {
    function email_template_account_rejected(array $user, string $reason): string
    {
        $baseUrl = base_url();
        $reason = $reason ?: 'Data yang Anda masukkan tidak sesuai atau tidak dapat diverifikasi.';
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .warning-box { background: #fff3cd; border-left: 4px solid #ff9800; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Pemberitahuan</h1>
            <p>Pengajuan Akun</p>
        </div>
        <div class="content">
            <p>Halo <strong>{$user['nama_lengkap']}</strong>,</p>
            
            <p>Mohon maaf, pengajuan akun Anda di <strong>Website Desa Blanakan</strong> belum dapat disetujui.</p>
            
            <div class="warning-box">
                <strong>Alasan:</strong><br>
                {$reason}
            </div>
            
            <p><strong>Apa yang harus dilakukan?</strong></p>
            <ul>
                <li>Hubungi RT/RW setempat untuk konfirmasi data</li>
                <li>Datang ke kantor desa untuk verifikasi langsung</li>
                <li>Telepon: 0260-123456</li>
            </ul>
            
            <p style="margin-top: 30px; font-size: 13px; color: #666;">
                Terima kasih atas pengertiannya.
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.<br>
            Website Desa Blanakan © 2026</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}

if (!function_exists('email_template_surat_completed')) {
    function email_template_surat_completed(array $user, array $surat): string
    {
        $dashboardUrl = base_url('dashboard');
        $jenisSurat = ucwords(str_replace('_', ' ', $surat['jenis_surat']));
        $tanggal = date('d/m/Y', strtotime($surat['created_at']));
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .button { display: inline-block; padding: 12px 30px; background: #2e7d32; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .surat-info { background: #e8f5e9; padding: 15px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Surat Selesai!</h1>
            <p>Dokumen Anda Siap</p>
        </div>
        <div class="content">
            <p>Halo <strong>{$user['nama_lengkap']}</strong>,</p>
            
            <p>Kabar baik! Pengajuan surat Anda telah selesai diproses oleh admin desa.</p>
            
            <div class="surat-info">
                <strong>📋 Jenis Surat:</strong> {$jenisSurat}<br>
                <strong>📅 Tanggal Pengajuan:</strong> {$tanggal}<br>
                <strong>✅ Status:</strong> SELESAI
            </div>
            
            <p><strong>Langkah selanjutnya:</strong></p>
            <ul>
                <li>📥 Download surat di dashboard Anda, ATAU</li>
                <li>🏢 Ambil surat fisik di kantor desa</li>
            </ul>
            
            <center>
                <a href="{$dashboardUrl}" class="button">Lihat Dashboard</a>
            </center>
            
            <p style="margin-top: 30px; font-size: 13px; color: #666;">
                <strong>Jam operasional kantor desa:</strong><br>
                Senin - Jumat: 08.00 - 15.00 WIB<br>
                Sabtu: 08.00 - 12.00 WIB
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.<br>
            Website Desa Blanakan © 2026</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}

if (!function_exists('email_template_surat_rejected')) {
    function email_template_surat_rejected(array $user, array $surat): string
    {
        $dashboardUrl = base_url('dashboard');
        $jenisSurat = ucwords(str_replace('_', ' ', $surat['jenis_surat']));
        $alasan = $surat['pesan_admin'] ?: 'Dokumen pendukung belum lengkap atau data tidak sesuai.';
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; }
        .button { display: inline-block; padding: 12px 30px; background: #f57c00; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .warning-box { background: #fff3cd; border-left: 4px solid #ff9800; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Perlu Perbaikan</h1>
            <p>Pengajuan Surat</p>
        </div>
        <div class="content">
            <p>Halo <strong>{$user['nama_lengkap']}</strong>,</p>
            
            <p>Mohon maaf, pengajuan surat Anda memerlukan perbaikan atau kelengkapan data.</p>
            
            <div class="warning-box">
                <strong>📋 Jenis Surat:</strong> {$jenisSurat}<br><br>
                <strong>Catatan dari Admin:</strong><br>
                {$alasan}
            </div>
            
            <p><strong>Apa yang harus dilakukan?</strong></p>
            <ul>
                <li>Lengkapi atau perbaiki data yang diminta</li>
                <li>Ajukan surat ulang melalui dashboard</li>
                <li>Atau datang langsung ke kantor desa untuk konsultasi</li>
            </ul>
            
            <center>
                <a href="{$dashboardUrl}" class="button">Buka Dashboard</a>
            </center>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.<br>
            Website Desa Blanakan © 2026</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
