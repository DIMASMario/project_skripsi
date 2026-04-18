# 📧 SETUP NOTIFIKASI EMAIL - Website Desa Blanakan

**Tanggal:** 15 Februari 2026  
**Status:** Ready to Deploy

---

## 🎯 PERUBAHAN YANG DILAKUKAN

### 1. ✅ Sistem Registrasi Disederhanakan
- **SEBELUM:** Warga butuh kode registrasi dari admin
- **SEKARANG:** Warga langsung isi form → Submit → Tunggu approval
- **Email wajib diisi** untuk menerima notifikasi

### 2. 📧 Notifikasi Email Otomatis

Email akan **otomatis terkirim** dalam kondisi berikut:

| **Event** | **Kapan Kirim** |
|-----------|-----------------|
| ✅ **Akun Disetujui** | Saat admin klik "Setujui" di dashboard |
| ❌ **Akun Ditolak** | Saat admin klik "Tolak" di dashboard |
| 📄 **Surat Selesai** | Saat admin ubah status → "Selesai" |
| ⚠️ **Surat Ditolak** | Saat admin ubah status → "Ditolak" |

---

## 🔧 CARA SETUP GMAIL SMTP

### Step 1: Generate Gmail App Password

1. **Login ke Gmail** yang akan dipakai untuk kirim email
2. Buka: https://myaccount.google.com/security
3. **Enable 2-Step Verification** (wajib!)
4. Search **"App passwords"** di halaman security
5. Klik **"Generate app password"**
6. Pilih:
   - App: **Mail**
   - Device: **Windows Computer**
7. **Copy 16 karakter password** yang muncul (contoh: `abcd efgh ijkl mnop`)

### Step 2: Edit File Config Email

Buka file: `app/Config/Email.php`

**Ganti 2 baris ini:**

```php
public string $SMTPUser = 'your-email@gmail.com'; // ← GANTI INI
public string $SMTPPass = 'your-app-password';    // ← GANTI INI
```

**Contoh setelah diganti:**

```php
public string $SMTPUser = 'desablanakan@gmail.com';
public string $SMTPPass = 'abcd efgh ijkl mnop'; // App password dari step 1
```

**PENTING:**
- ✅ Pakai **App Password** (16 karakter)
- ❌ **JANGAN** pakai password Gmail biasa!

---

## 🧪 CARA TESTING

### Test 1: Registrasi & Approval

```
1. Buka: http://localhost:8080/auth/register
2. Isi form registrasi:
   - Nama: Test User
   - Email: emailanda@gmail.com (HARUS ADA!)
   - HP: 08123456789
   - Password: test123
3. Submit
4. Login sebagai admin
5. Buka: /admin/users
6. Klik "Setujui" pada Test User
7. CHECK EMAIL: Harus menerima email "✅ Akun Desa Blanakan Telah Aktif"
```

### Test 2: Surat Selesai

```
1. Login sebagai warga (yang sudah approved)
2. Ajukan surat (misalnya: Surat Domisili)
3. Login sebagai admin
4. Buka: /admin/surat
5. Update status → "Selesai"
6. CHECK EMAIL: Warga harus menerima email "📄 Surat Anda Telah Selesai"
```

---

## 📋 FILE YANG DIUBAH

### 1. **app/Controllers/Auth.php**
- ✅ Hapus validasi `kode_registrasi`
- ✅ Email sekarang WAJIB (required)
- ✅ Registrasi langsung ke database

### 2. **app/Config/Email.php**
- ✅ Setup Gmail SMTP
- ✅ SMTPHost: smtp.gmail.com
- ✅ SMTPPort: 587 (TLS)
- ⚠️ **Masih perlu setup:** SMTPUser & SMTPPass

### 3. **app/Helpers/email_helper.php** (BARU)
- ✅ Function `send_email()` - Kirim email umum
- ✅ Function `send_account_approved_email()` - Email akun disetujui
- ✅ Function `send_account_rejected_email()` - Email akun ditolak
- ✅ Function `send_surat_completed_email()` - Email surat selesai
- ✅ Function `send_surat_rejected_email()` - Email surat ditolak
- ✅ Template HTML yang bagus untuk semua jenis email

### 4. **app/Config/Autoload.php**
- ✅ Autoload helper 'email' → Bisa langsung pakai

### 5. **app/Controllers/Admin.php**
- ✅ Di `verifikasiUser()`: Kirim email saat approve/reject
- ✅ Di `prosesSurat()`: Kirim email saat selesai/tolak

### 6. **app/Views/auth/register.php**
- ✅ Hapus field "Kode Registrasi"
- ✅ Email sekarang wajib diisi
- ✅ Info: "Email digunakan untuk notifikasi"

---

## 🚦 STATUS IMPLEMENTASI

| **Komponen** | **Status** | **Catatan** |
|--------------|------------|-------------|
| Remove kode registrasi | ✅ Complete | Registrasi bebas tanpa kode |
| Email helper functions | ✅ Complete | 5 functions ready |
| Email templates (HTML) | ✅ Complete | 4 templates (approve/reject user & surat) |
| Integrate di user approval | ✅ Complete | Auto-send saat approve/reject |
| Integrate di surat status | ✅ Complete | Auto-send saat selesai/ditolak |
| Update registration view | ✅ Complete | Kode field dihapus |
| Gmail SMTP config | ⚠️ **NEEDS SETUP** | Perlu App Password Gmail |

---

## ⚙️ KONFIGURASI GMAIL (Setup Sekali Aja)

### Email Settings:

```php
// app/Config/Email.php

$fromEmail = 'noreply@desablanakan.go.id'  // Pengirim email
$fromName  = 'Desa Blanakan - Sistem Digital'

$SMTPHost  = 'smtp.gmail.com'               // Sudah benar
$SMTPPort  = 587                             // Sudah benar  
$SMTPCrypto = 'tls'                          // Sudah benar
$SMTPTimeout = 30                            // Sudah benar

// YANG PERLU DIISI:
$SMTPUser = 'your-email@gmail.com'          // ← GANTI!
$SMTPPass = 'your-app-password'             // ← GANTI!
```

---

## 💡 TIPS & TROUBLESHOOTING

### ❌ Email Tidak Terkirim?

**1. Check Log File:**
```powershell
Get-Content "writable\logs\log-$(Get-Date -Format 'yyyy-MM-dd').log" | Select-String "email"
```

**2. Cek Gmail App Password:**
- Pastikan 2-Step Verification aktif
- Password harus 16 karakter (ada spasi)
- Jangan pakai password Gmail biasa

**3. Test Connection:**
Buat file `public/test_email.php`:

```php
<?php
require '../vendor/autoload.php';

$email = \Config\Services::email();
$email->setTo('emailanda@gmail.com');
$email->setSubject('Test Email');
$email->setMessage('Ini test email dari sistem desa');

if ($email->send()) {
    echo "✅ Email berhasil terkirim!";
} else {
    echo "❌ Gagal: " . $email->printDebugger();
}
```

Akses: `http://localhost:8080/test_email.php`

### ⚠️ Email Masuk Spam?

Normal untuk pertama kali. Warga harus:
1. Check folder Spam
2. Tandai "Not Spam"
3. Email berikutnya masuk Inbox

### 📊 Limit Gmail:

- ✅ **500 email/hari** (gratis)
- ✅ Cukup untuk desa (rata-rata < 50 email/hari)
- ✅ Kalau lebih, bisa pakai 2+ akun Gmail

---

## 🔐 KEAMANAN

### Jangan Commit Credentials!

Tambahkan di `.gitignore`:

```
app/Config/Email.php
.env
```

Atau pakai **Environment Variables** di `.env`:

```env
email.SMTPUser = desablanakan@gmail.com
email.SMTPPass = abcd efgh ijkl mnop
```

---

## 📞 BANTUAN

Email tidak jalan setelah setup? Contact developer atau:

1. **Check Email.php** → Pastikan email & app password benar
2. **Check Log** → Lihat error message
3. **Test manual** → Pakai test_email.php
4. **Google "Gmail SMTP not working"** → Banyak solusi online

---

## ✅ CHECKLIST DEPLOYMENT

Sebelum go live:

- [ ] Setup Gmail App Password
- [ ] Edit `app/Config/Email.php` (SMTPUser & SMTPPass)
- [ ] Test kirim email approval
- [ ] Test kirim email surat selesai
- [ ] Verify email masuk (check Inbox & Spam)
- [ ] Hapus/disable file test_email.php
- [ ] Backup `.env` & `Email.php` config
- [ ] Inform warga: Check email untuk notifikasi

---

**Dokumentasi oleh:** GitHub Copilot  
**Tanggal:** 15 Februari 2026  
**Version:** 1.0 - Email Notification System
