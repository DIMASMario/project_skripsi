<?php

namespace App\Libraries;

use Config\Email;
use CodeIgniter\Email\Email as EmailLib;

class EmailService
{
    protected $email;
    protected $config;

    public function __construct()
    {
        $this->config = config('Email');
        $this->email = new EmailLib($this->config);
    }

    /**
     * Send email notifikasi surat selesai
     */
    public function sendSuratSelesaiNotification($recipientEmail, $recipientName, $suratData)
    {
        try {
            // Reset email
            $this->email->clear();

            // Set email parameters
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Surat ' . $suratData['jenis_surat_text'] . ' Anda Telah Selesai');

            // Prepare email body
            $emailBody = $this->renderSuratSelesaiTemplate([
                'nama_warga' => $recipientName,
                'jenis_surat' => $suratData['jenis_surat_text'],
                'no_surat' => $suratData['id'],
                'tanggal_selesai' => date('d M Y', strtotime($suratData['updated_at'])),
                'download_link' => base_url('layanan-online/download/' . $suratData['id']),
                'dashboard_link' => base_url('dashboard/detail-surat/' . $suratData['id'])
            ]);

            $this->email->setMessage($emailBody);

            // Send email
            if ($this->email->send()) {
                log_message('info', 'Email notifikasi surat selesai berhasil dikirim ke: ' . $recipientEmail);
                return true;
            } else {
                log_message('error', 'Gagal mengirim email ke ' . $recipientEmail . ': ' . $this->email->printDebugger());
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception saat mengirim email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email verifikasi akun warga
     */
    public function sendVerifikasiAkunEmail($recipientEmail, $recipientName, $approvalLink)
    {
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Akun Anda Telah Diverifikasi - Desa Blanakan');

            $emailBody = $this->renderVerifikasiAkunTemplate([
                'nama_warga' => $recipientName,
                'login_link' => base_url('auth/login'),
                'dashboard_link' => base_url('dashboard')
            ]);

            $this->email->setMessage($emailBody);

            if ($this->email->send()) {
                log_message('info', 'Email verifikasi akun berhasil dikirim ke: ' . $recipientEmail);
                return true;
            } else {
                log_message('error', 'Gagal mengirim email verifikasi ke ' . $recipientEmail);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception saat mengirim email verifikasi: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email penolakan akun
     */
    public function sendPenolakan($recipientEmail, $recipientName, $alasan)
    {
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Akun Anda Ditolak - Desa Blanakan');

            $emailBody = $this->renderPenolakanTemplate([
                'nama_warga' => $recipientName,
                'alasan' => $alasan ?? 'Data tidak lengkap atau tidak sesuai dengan persyaratan',
                'contact_link' => base_url('kontak')
            ]);

            $this->email->setMessage($emailBody);

            if ($this->email->send()) {
                log_message('info', 'Email penolakan berhasil dikirim ke: ' . $recipientEmail);
                return true;
            } else {
                log_message('error', 'Gagal mengirim email penolakan ke ' . $recipientEmail);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception saat mengirim email penolakan: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Render template email surat selesai
     */
    private function renderSuratSelesaiTemplate($data)
    {
        return view('emails/surat_selesai', $data);
    }

    /**
     * Render template email verifikasi akun
     */
    private function renderVerifikasiAkunTemplate($data)
    {
        return view('emails/verifikasi_akun', $data);
    }

    /**
     * Render template email penolakan
     */
    private function renderPenolakanTemplate($data)
    {
        return view('emails/penolakan_akun', $data);
    }

    /**
     * Get email config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Test email connection
     */
    public function testConnection()
    {
        try {
            $this->email->clear();
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $emailTest = session()->get('user_id') ? 'test@example.com' : $this->config->fromEmail;
            $this->email->setTo($emailTest);
            $this->email->setSubject('Test Email Connection');
            $this->email->setMessage('This is a test email to verify connection settings.');

            if ($this->email->send()) {
                return ['status' => 'success', 'message' => 'Email connection test successful'];
            } else {
                return ['status' => 'error', 'message' => $this->email->printDebugger()];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
