# 🔄 Alur Lengkap Pengajuan Surat & Email Notification

## 📊 Diagram Workflow Sistem Pengajuan Surat

```
┌─────────────────────────────────────────────────────────────────┐
│                    WORKFLOW PENGAJUAN SURAT                      │
└─────────────────────────────────────────────────────────────────┘

1. WARGA MENGAJUKAN SURAT
   ├─ Login ke sistem
   ├─ Pilih "Layanan Online"
   ├─ Pilih jenis surat (9 pilihan tersedia)
   ├─ Isi formulir pengajuan
   │  ├─ Nama Lengkap (required)
   │  ├─ Keperluan Surat (min 10 karakter)
   │  ├─ Data tambahan sesuai jenis surat
   │  └─ Upload dokumen pendukung (jika ada)
   └─ Klik "Ajukan Surat"
      │
      └─► Database: INSERT ke tabel 'surat'
          ├─ status = 'menunggu'
          ├─ user_id = warga yang mengajukan
          ├─ jenis_surat = pilihan warga
          └─ created_at = timestamp sekarang

2. NOTIFIKASI KE ADMIN DAN WARGA
   ├─► Dashboard Admin
   │   ├─ Surat baru muncul di "Surat Menunggu"
   │   └─ Notifikasi admin di sidebar
   │
   └─► Dashboard Warga
       └─ Surat muncul di "Pengajuan Saya" dengan status "Menunggu"


3. ADMIN MEMPROSES SURAT
   ├─ Admin login ke sistem
   ├─ Buka "Admin > Manajemen Surat"
   ├─ Lihat daftar pengajuan (filter: menunggu)
   ├─ Klik "Detail" untuk memeriksa data warga
   │  ├─ Lihat data publik warga
   │  ├─ Lihat detail pengajuan surat
   │  └─ Lihat dokumen pendukung (jika ada)
   ├─ Klik "Proses" (opsional)
   │  └─► Database: status = 'diproses'
   │        └─ Notifikasi warga: "Surat sedang diproses"
   │
   ├─ Buat/Edit surat sesuai template
   │  ├─ Gunakan template surat di sistem
   │  ├─ Isi data otomatis dari pengajuan warga
   │  ├─ Review dan edit jika perlu
   │  └─ Generate/export sebagai PDF
   │
   └─ Upload PDF surat ke sistem
      ├─ Klik "Upload File Surat"
      ├─ Select file PDF (max 5MB)
      ├─ Set pesan untuk warga (optional)
      └─ Klik "Upload"


4. SISTEM MEMPROSES UPLOAD
   ├─ Validasi file
   │  ├─ Check file is PDF ✓
   │  ├─ Check file size < 5MB ✓
   │  └─ Check valid PDF format ✓
   │
   ├─► Database: UPDATE tabel 'surat'
   │   ├─ status = 'selesai' ✓
   │   ├─ file_surat = 'nama_file.pdf'
   │   ├─ pesan_admin = pesan dari admin
   │   └─ updated_at = timestamp sekarang
   │
   ├─► Database: INSERT ke tabel 'notifikasi'
   │   ├─ user_id = warga pengajuan
   │   ├─ surat_id = ID surat
   │   ├─ tipe = 'warga'
   │   ├─ pesan = "Surat XYZ Anda telah selesai..."
   │   └─ status = 'belum_dibaca'
   │
   └─► EMAIL NOTIFICATION
       ├─ Ambil email warga dari tabel users
       ├─ Generate email template HTML
       │  ├─ Salam personal
       │  ├─ Info surat (jenis, nomor, tanggal selesai)
       │  ├─ Tombol "Unduh Surat PDF"
       │  ├─ Tombol "Lihat Detail"
       │  └─ Footer dengan kontak desa
       │
       └─ Kirim via SMTP Gmail
          ├─ Proses: EmailService::sendSuratSelesaiNotification()
          ├─ Server: smtp.gmail.com:587 (TLS)
          ├─ From: noreply@desablanakan.go.id
          ├─ To: email_warga@gmail.com
          │
          └─ Success Track:
             ├─ Log file: writable/logs/log-*.log
             └─ Cek Gmail warga (inbox atau spam folder)


5. WARGA MENERIMA NOTIFIKASI
   ├─ Dashboard Warga
   │  ├─ Muncul notifikasi: "Surat Domisili Anda Selesai"
   │  ├─ Surat berpindah dari "Menunggu" ke "Selesai"
   │  └─ Status badge berubah menjadi hijau "SELESAI"
   │
   └─ Email Gmail Warga
      ├─ Subject: "📄 Surat Domisili Anda Telah Selesai"
      ├─ From: "Desa Blanakan" <noreply@desablanakan.go.id>
      ├─ Body: HTML formatnya cantik dengan info surat
      ├─ Call-to-action: "Unduh Surat PDF" & "Lihat Detail"
      └─ Footer: Kontak dan disclaimer


6. WARGA MENGUNDUH SURAT
   ├─ Melalui Email
   │  ├─ Klik link "Unduh Surat PDF" di email
   │  ├─ Redirect ke sistem: /layanan-online/download/{ID}
   │  └─ Download file PDF
   │
   ├─ Melalui Dashboard
   │  ├─ Login ke dashboard
   │  ├─ Buka menu "Riwayat Pengajuan"
   │  ├─ Cari surat dengan status "SELESAI"
   │  ├─ Klik "Lihat Detail" atau "Unduh"
   │  └─ Download file PDF
   │
   └─► Database: INSERT ke tabel 'download_log' (optional)
       └─ Track siapa saja yang download surat


7. SURAT SIAP DIGUNAKAN
   ├─ Warga cetak surat dari PDF
   ├─ Gunakan surat untuk keperluan administrasi
   │  ├─ Submit ke sekolah (untuk beasiswa)
   │  ├─ Submit ke bank (untuk kredit)
   │  ├─ Submit ke kantor polisi (SKCK)
   │  └─ Kebutuhan administrasi lainnya
   │
   └─ Surat tersimpan selamanya di:
      ├─ Database sistem (backup)
      ├─ Email Gmail warga (backup)
      ├─ File PDF di folder uploads/surat_selesai/
      └─ Dapat diunduh kembali kapan saja
```

---

## 📝 Status Surat dan Lifecycle

```
MENUNGGU (Warga baru ajukan)
    ↓
[ADMIN REVIEW]
    ↓
DIPROSES (Admin sedang membuat surat)
    ↓
SELESAI (Admin upload PDF) ←─── EMAIL SENT ✉️
    ↓
[WARGA DOWNLOAD & GUNAKAN]
    ↓
SELESAI (Warga sudah download)

-------- ALTERNATIVE PATHS --------

MENUNGGU
    ↓
DITOLAK (Admin menolak pengajuan)
    ↓
[NOTIFIKASI EMAIL: Surat ditolak]
```

---

## 📧 Email Flow Detail

### Saat Surat Selesai Diupload:

```
1. TRIGGER: Admin controller uploadFileSurat()
   └─ File PDF successfully uploaded
   └─ Database status updated to 'selesai'

2. NOTIFICATION CREATION
   └─ NotifikasiModel::insert() → dashboard notification

3. EMAIL SERVICE EXECUTION
   ├─ Get warga data from users table
   ├─ Validate email field not empty
   ├─ Create email content via template
   ├─ Configure SMTP connection
   ├─ Send via smtp.gmail.com:587
   └─ Log hasil pengiriman

4. EMAIL TEMPLATE RENDERING
   ├─ File: app/Views/emails/surat_selesai.php
   ├─ Variables injected:
   │  ├─ $nama_warga = nama dari tabel users
   │  ├─ $jenis_surat = display name dari getJenisSuratText()
   │  ├─ $no_surat = surat ID
   │  ├─ $tanggal_selesai = surat updated_at
   │  ├─ $download_link = base_url('layanan-online/download/{ID}')
   │  └─ $dashboard_link = base_url('dashboard/detail-surat/{ID}')
   │
   └─ HTML rendered with styling

5. EMAIL SENT
   ├─ SMTP Connection: TLS secured
   ├─ From: noreply@desablanakan.go.id
   ├─ To: {email_warga}
   ├─ Subject: "📄 Surat {Jenis} Anda Telah Selesai"
   ├─ Body: HTML dengan info lengkap
   ├─ Protocol: HTML email
   └─ Charset: UTF-8

6. DELIVERY TRACKING
   ├─ Success: writable/logs/log-*.log entry
   ├─ Failed: writable/logs/log-*.log entry + error details
   └─ Timeout: 30 seconds per email
```

---

## 🗄️ Database Changes

### Tabel yang Terlibat:

1. **surat**
   - id (PK)
   - user_id (FK to users)
   - jenis_surat (enum)
   - status (enum: menunggu, diproses, selesai, ditolak)
   - file_surat (varchar - nama file PDF)
   - updated_at (timestamp)

2. **users**
   - id (PK)
   - email (varchar) ← REQUIRED untuk email notification
   - nama_lengkap (varchar)

3. **notifikasi**
   - id (PK)
   - user_id (FK to users)
   - surat_id (FK to surat)
   - pesan (text)
   - status (enum: belum_dibaca, sudah_dibaca)

---

## 🔐 Security Notes

1. **Email Configuration**
   - App Password dari Google (bukan password biasa)
   - Store di app/Config/Email.php (gitignore di production)
   - Never commit actual passwords ke repository

2. **Email Content**
   - Authenticated user only (warga sudah login saat Submit)
   - Email link dengan ID surat (no auth bypass possible)
   - Download link verify user ownership

3. **Log Files**
   - SMTP conversations logged (debug benign)
   - Sensitive data masked in logs
   - Logs retained per CodeIgniter default

---

## ✅ Implementation Checklist

- [x] EmailService library created
- [x] Email template surat_selesai.php created
- [x] Admin controller updated with EmailService
- [x] uploadFileSurat() sends email notification
- [x] Email config file ready (needs SMTP setup)
- [ ] **TODO: Setup Gmail App Password**
- [ ] **TODO: Update app/Config/Email.php with credentials**
- [ ] Test email sending from admin panel
- [ ] Verify email delivery to warga Gmail

---

## 🚀 Next Steps

1. **Setup SMTP Gmail** (see SETUP_EMAIL_NOTIFICATION.md)
2. **Test Email Sending:**
   - Login as admin
   - Upload test surat PDF
   - Check email received by warga
3. **Monitor Production:**
   - Check writable/logs/
   - Verify email deliverability
   - Handle bounces/spam

---

**Dokumentasi: ALUR_PENGAJUAN_SURAT.md**  
**Status:** ✅ Implementation Complete  
**Last Updated:** 17 Maret 2026
