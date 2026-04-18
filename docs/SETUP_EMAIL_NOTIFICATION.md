# 📧 Panduan Setup Email Notification - Sistem Pengajuan Surat

## Daftar Isi
1. [Konfigurasi SMTP Gmail](#konfigurasi-smtp-gmail)
2. [Konfigurasi Email CodeIgniter](#konfigurasi-email-codeigniter)
3. [Cara Kerja Email Service](#cara-kerja-email-service)
4. [Testing Email](#testing-email)
5. [Troubleshooting](#troubleshooting)

---

## 1. Konfigurasi SMTP Gmail

### Langkah 1: Enable 2-Step Verification di Akun Google
1. Buka https://myaccount.google.com
2. Pilih **Security** di sebelah kiri
3. Scroll ke bawah dan aktifkan **2-Step Verification**
4. Ikuti proses verifikasi

### Langkah 2: Generate Google App Password
1. Buka https://myaccount.google.com/apppasswords
2. Pilih **Mail** dan **Windows**
3. Google akan generate 16 karakter password
4. Copy password tersebut (tanpa spasi)

**CATATAN:** Password ini BERBEDA dengan password Gmail normal Anda!

---

## 2. Konfigurasi Email CodeIgniter

### File: `app/Config/Email.php`

Buka file konfigurasi email dan update dengan informasi SMTP Gmail Anda:

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    // Email admin desa
    public string $fromEmail  = 'your-email@gmail.com';
    public string $fromName   = 'Desa Blanakan - Sistem Digital';
    public string $recipients = '';

    // Protocol SMTP untuk Gmail
    public string $protocol = 'smtp';

    // SMTP Configuration
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = 'your-email@gmail.com';
    public string $SMTPPass = 'your-app-password'; // 16 chars dari step sebelumnya
    public int $SMTPPort = 587;
    public int $SMTPTimeout = 30;
    public bool $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls'; // PENTING: untuk Gmail gunakan 'tls'

    // Email Format
    public string $mailType = 'html'; // Gunakan HTML untuk email template yang cantik
    public string $charset = 'UTF-8';
    public bool $wordWrap = true;
    public int $wrapChars = 76;

    public bool $validate = false;
    public string $userAgent = 'Desa Blanakan Email System';
}
```

### Contoh Konfigurasi untuk Provider Lain:

**Mailtrap (Testing):**
```php
public string $SMTPHost = 'smtp.mailtrap.io';
public string $SMTPUser = 'your-mailtrap-username';
public string $SMTPPass = 'your-mailtrap-password';
public int $SMTPPort = 465;
public string $SMTPCrypto = ''; // Kosong untuk Mailtrap
```

**SendGrid:**
```php
public string $SMTPHost = 'smtp.sendgrid.net';
public string $SMTPUser = 'apikey';
public string $SMTPPass = 'SG.xxxxxxxxxxxxxx'; // API Key SendGrid
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

---

## 3. Cara Kerja Email Service

### File: `app/Libraries/EmailService.php`

Librari ini menyediakan method-method untuk mengirim email:

#### Method 1: Send Surat Selesai Notification
```php
$emailService = new EmailService();

$emailService->sendSuratSelesaiNotification(
    $recipientEmail,      // Email penerima
    $recipientName,       // Nama penerima
    $suratData           // Array dengan id, jenis_surat_text, updated_at
);
```

#### Method 2: Send Account Verification
```php
$emailService->sendVerifikasiAkunEmail(
    $recipientEmail,
    $recipientName,
    $approvalLink
);
```

#### Method 3: Send Account Rejection
```php
$emailService->sendPenolakan(
    $recipientEmail,
    $recipientName,
    $alasan  // Alasan penolakan
);
```

### Email Templates: `app/Views/emails/`

**surat_selesai.php** - Template email ketika surat selesai diproses
- Menampilkan informasi surat (jenis, nomor, tanggal)
- Tombol download surat PDF
- Link ke dashboard warga

---

## 4. Testing Email

### Cara 1: Test via Code
Buat file test di `app/Controllers/TestEmail.php`:

```php
<?php

namespace App\Controllers;

use App\Libraries\EmailService;

class TestEmail extends BaseController
{
    public function sendTest()
    {
        $emailService = new EmailService();
        
        $result = $emailService->sendSuratSelesaiNotification(
            'test@gmail.com',
            'Test User',
            [
                'id' => 123,
                'jenis_surat_text' => 'Surat Domisili',
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        
        if ($result) {
            echo 'Email berhasil dikirim!';
        } else {
            echo 'Email gagal dikirim!';
        }
    }
}
```

Akses: `http://localhost/test-email/sendTest`

### Cara 2: Test Connection
```php
$emailService = new EmailService();
$result = $emailService->testConnection();
echo json_encode($result);
```

### Cara 3: Check Logs
```
writable/logs/log-*.log
```

Cari entry berisi "Email" untuk melihat status pengiriman email.

---

## 5. Troubleshooting

### Error: "SMTP connect() failed"
**Penyebab:** Konfigurasi SMTP salah atau host tidak dapat diakses

**Solusi:**
- Pastikan `SMTPHost` = 'smtp.gmail.com'
- Pastikan `SMTPPort` = 587 (bukan 465)
- Pastikan `SMTPCrypto` = 'tls'
- Check firewall/ISP tidak memblokir port 587

### Error: "SMTP Error: Could not authenticate"
**Penyebab:** Username/password salah

**Solusi:**
- Pastikan `SMTPUser` = full Gmail address (dengan @gmail.com)
- Pastikan password adalah **App Password**, BUKAN password Gmail biasa
- Pastikan tidak ada spasi di awal/akhir password dan user
- Regenerate app password jika masih gagal

### Error: "Failed to send email - no recipients"
**Penyebab:** Email penerima tidak valid atau kosong

**Solusi:**
- Pastikan user memiliki email (field email di database tidak kosong)
- Pastikan format email valid (abc@gmail.com)

### Email Dikirim tapi Masuk Folder Spam
**Penyebab:** Gmail menganggap email sebagai spam

**Solusi:**
- Pastikan `fromName` bukan sesuatu yang mencurigakan
- Pastikan tidak ada link mencurigakan di email
- Tambahkan DKIM/SPF record ke DNS (untuk production)
- Format HTML template dengan baik

### Error: "Allowed memory exhausted"
**Penyebab:** Email library menggunakan terlalu banyak memory

**Solusi:**
- Increase PHP memory limit di `php.ini` atau `.htaccess`:
  ```
  memory_limit = 256M
  ```
- Update `smtp.php` config untuk tidak keep alive:
  ```php
  public bool $SMTPKeepAlive = false;
  ```

---

## Integrasi di Admin Panel

### Saat Admin Upload Surat PDF

File: `app/Controllers/Admin.php` - Method `uploadFileSurat()`

```php
// Email service otomatis dikirim ketika surat di-upload
$this->emailService->sendSuratSelesaiNotification(
    $user['email'],
    $user['nama_lengkap'],
    [
        'id' => $id,
        'jenis_surat_text' => $jenisSuratText,
        'updated_at' => date('Y-m-d H:i:s')
    ]
);
```

**Workflow:**
1. Admin login ke sistem
2. Admin buka halaman "Manajemen Surat"
3. Admin klik detail surat yang ingin diselesaikan
4. Admin upload file PDF surat
5. Sistem otomatis:
   - Update status menjadi "selesai"
   - Buat notifikasi di dashboard warga
   - Kirim email ke Gmail warga ✅

---

## Checklist Setup Email

- [ ] Aktifkan 2-Step Verification di akun Google
- [ ] Generate App Password di Google Account
- [ ] Update `app/Config/Email.php` dengan:
  - [ ] Email address
  - [ ] App Password
  - [ ] SMTPHost = smtp.gmail.com
  - [ ] SMTPPort = 587
  - [ ] SMTPCrypto = tls
- [ ] Test email connection
- [ ] Upload file test dan cek apakah email dikirim
- [ ] Check email di Gmail inbox/spam folder

---

## Email Signature untuk Surat

Untuk menambahkan tanda tangan pada email, edit file:
`app/Views/emails/surat_selesai.php`

Tambahkan di bagian footer:
```html
<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ccc;">
    <p><strong>Kepala Desa Blanakan</strong></p>
    <p>Nama Kepala Desa</p>
    <p>NIP: XXXXXXXXXXXX</p>
</div>
```

---

## Production Considerations

Untuk production environment:

1. **Gunakan dedicated email account** (bukan akun personal)
2. **Setup SPF/DKIM records** di DNS untuk deliverability lebih baik
3. **Monitor email logs** di `writable/logs/`
4. **Implement retry mechanism** untuk email yang gagal
5. **Setup bounce handling** untuk email yang tidak valid
6. **Rate limiting** untuk mencegah spam

---

**Dokumentasi dibuat:** 17 Maret 2026  
**Terakhir diupdate:** 17 Maret 2026  
**Status:** ✅ Siap untuk Production
