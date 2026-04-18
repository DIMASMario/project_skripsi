# 📋 SISTEM UPLOAD SURAT - DOKUMENTASI LENGKAP

## ✅ IMPLEMENTASI SELESAI!

Sistem pengajuan surat sekarang sudah **LENGKAP dan SINKRON** antara User dan Admin.

---

## 🔄 ALUR SISTEM BARU (LENGKAP)

```
┌─────────────────────────────────────────────────────────────┐
│ 1. USER MENGAJUKAN SURAT                                    │
│    URL: /layanan-online/ajukan?jenis=domisili                │
│    - Isi form data diri                                     │
│    - Upload berkas persyaratan (KTP, KK, dll)               │
│    - Submit → Status: MENUNGGU                              │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. ADMIN PROSES SURAT                                       │
│    URL: /admin/detailSurat/[ID]                              │
│    - Klik tombol "Proses Surat"                             │
│    - Status berubah: MENUNGGU → DIPROSES                    │
│    - User dapat notifikasi                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. ADMIN UPLOAD FILE SURAT (BARU!)                         │
│    URL: /admin/detailSurat/[ID]                              │
│    A. Admin buat surat di Word/PDF                          │
│    B. Export ke PDF                                         │
│    C. Buka halaman detail surat                             │
│    D. Scroll ke bagian "Upload File Surat"                  │
│    E. Pilih file PDF (max 5MB)                              │
│    F. Tambah catatan (optional)                             │
│    G. Klik "Upload & Tandai Selesai"                        │
│                                                             │
│    SISTEM OTOMATIS:                                         │
│    ✅ Upload file ke: uploads/surat_selesai/                │
│    ✅ Simpan nama file ke database                          │
│    ✅ Update status: DIPROSES → SELESAI                     │
│    ✅ Kirim notifikasi ke user                              │
│    ✅ Kirim email ke user (jika ada)                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. USER DOWNLOAD SURAT                                      │
│    URL: /dashboard/surat                                     │
│    - Status berubah: "Selesai & Siap Diunduh" ✓             │
│    - Tombol "Unduh Surat" muncul                            │
│    - Klik → File PDF ter-download                           │
│    - User dapat file surat resmi                            │
└─────────────────────────────────────────────────────────────┘
```

---

## 🛠️ PERUBAHAN YANG SUDAH DITERAPKAN

### 1. **Database (Sudah Ada)**
```sql
-- Kolom file_surat sudah ada, sekarang akan terisi!
`file_surat` varchar(255) DEFAULT NULL
```

### 2. **Folder Upload (Baru Dibuat)**
```
public/uploads/surat_selesai/
├── .htaccess (proteksi folder)
└── [file-file PDF surat akan tersimpan di sini]
```

### 3. **Admin Panel - Halaman Detail Surat**
**File:** `app/Views/admin/surat_detail.php`

**Tambahan Baru:**
- ✅ Form upload file PDF (muncul saat status = DIPROSES)
- ✅ Validasi: Hanya PDF, max 5MB
- ✅ Input catatan admin (optional)
- ✅ Tombol "Upload & Tandai Selesai"
- ✅ Info file yang sudah diupload (jika ada)
- ✅ Link preview file PDF

### 4. **Backend Upload Handler**
**File:** `app/Controllers/Admin.php`
**Method Baru:** `uploadFileSurat($id)`

**Fitur:**
- ✅ Validasi file (PDF only, max 5MB)
- ✅ Generate nama file unik: `surat_domisili_6_1709408400.pdf`
- ✅ Upload ke folder: `public/uploads/surat_selesai/`
- ✅ Update database (status + file_surat)
- ✅ Buat notifikasi untuk user
- ✅ Kirim email (jika ada)
- ✅ Redirect dengan pesan sukses

### 5. **Routes (Baru Ditambahkan)**
**File:** `app/Config/Routes.php`
```php
$routes->post('uploadFileSurat/(:num)', 'Admin::uploadFileSurat/$1');
```

### 6. **User Dashboard (Sudah Ada, Tinggal Pakai)**
**File:** `app/Views/dashboard/detail_surat_standalone.php`

**Logika:**
```php
<?php if (status == 'selesai' && !empty(file_surat)): ?>
    <button>Unduh Surat</button>  ← Baru akan muncul!
<?php endif; ?>
```

---

## 📝 CARA TESTING

### **Test Case 1: Upload File Surat**

1. **Login sebagai Admin**
   - URL: http://localhost:8080/admin-login
   - Username: admin
   - Password: [password admin]

2. **Buka Manajemen Surat**
   - URL: http://localhost:8080/admin/surat

3. **Pilih surat dengan status MENUNGGU**
   - Klik tombol mata (👁️) di surat habib

4. **Proses Surat**
   - Klik tombol "Proses Surat"
   - Status berubah: MENUNGGU → DIPROSES
   - Halaman refresh otomatis

5. **Buka Detail Surat Lagi**
   - URL: http://localhost:8080/admin/detailSurat/6
   - Scroll ke bawah
   - Lihat form "Upload File Surat yang Sudah di-ACC"

6. **Siapkan File PDF Test**
   - Buat file PDF dengan nama: `surat_test_domisili.pdf`
   - Isi: "Ini adalah surat domisili untuk habib"
   - Simpan di desktop/downloads

7. **Upload File**
   - Klik "Choose File"
   - Pilih PDF yang sudah dibuat
   - (Optional) Tambah catatan: "Surat telah selesai, silakan diunduh"
   - Klik "Upload & Tandai Selesai"

8. **Verifikasi Upload Berhasil**
   - Muncul pesan: "File surat berhasil diupload..."
   - Redirect ke: /admin/surat
   - Status surat berubah: DIPROSES → SELESAI

9. **Cek File di Server**
   - Buka folder: `public/uploads/surat_selesai/`
   - Harus ada file: `surat_domisili_6_[timestamp].pdf`

### **Test Case 2: User Download Surat**

1. **Login sebagai User (habib)**
   - URL: http://localhost:8080/auth/login
   - Username: habib
   - Password: [password habib]

2. **Buka Dashboard**
   - URL: http://localhost:8080/dashboard

3. **Lihat Status Surat**
   - Status: "Selesai & Siap Diunduh" ✓ (warna hijau)
   - Icon: check_circle

4. **Klik Detail Surat**
   - Lihat timeline lengkap
   - Ada step "Selesai & Siap Diunduh"

5. **Download File**
   - Klik tombol "Unduh Surat (PDF)"
   - File ter-download otomatis
   - Buka PDF → isi sesuai yang diupload admin

---

## 🎯 VALIDASI & ERROR HANDLING

### **Validasi Upload File:**
- ✅ File wajib diupload (tidak boleh kosong)
- ✅ Format hanya PDF (.pdf)
- ✅ Ukuran maksimal 5MB (5120 KB)
- ✅ File harus valid (tidak corrupt)

### **Error Messages:**
```
❌ "File surat harus diupload"
   → Jika tidak memilih file

❌ "File harus berformat PDF"
   → Jika upload file .doc, .jpg, dll

❌ "Ukuran file maksimal 5MB"
   → Jika file terlalu besar

❌ "File tidak valid"
   → Jika file corrupt

❌ "File tidak ditemukan di server"
   → Jika user coba download tapi file hilang
```

---

## 🔐 SECURITY

### **Upload Security:**
- ✅ Hanya PDF yang diizinkan (extension whitelist)
- ✅ File disimpan di folder terpisah
- ✅ Nama file di-generate (tidak pakai nama original)
- ✅ Ukuran file dibatasi (max 5MB)
- ✅ CSRF protection di form

### **Download Security:**
- ✅ User hanya bisa download surat miliknya sendiri
- ✅ Cek ownership: `surat['user_id'] == session('user_id')`
- ✅ Cek status: hanya surat SELESAI yang bisa didownload
- ✅ Cek file exists sebelum download

---

## 📊 DATABASE CHANGES

### **Before (File Selalu NULL):**
```sql
SELECT file_surat FROM surat WHERE id = 6;
-- Result: NULL ❌
```

### **After (File Terisi):**
```sql
SELECT file_surat FROM surat WHERE id = 6;
-- Result: surat_domisili_6_1709408400.pdf ✅
```

---

## 🚀 NEXT STEPS (Opsional)

Jika ingin lebih advanced:

### **1. Generate PDF Otomatis (Optional)**
- Install library: `dompdf` atau `tcpdf`
- Buat template surat dengan placeholder
- Generate PDF otomatis dari template + data user
- Eliminasi proses manual buat surat di Word

### **2. Preview Before Upload (Optional)**
- Tambah preview PDF di browser sebelum upload
- User bisa lihat isi surat sebelum finalisasi

### **3. Multiple Revisions (Optional)**
- Admin bisa upload ulang file (revisi)
- Simpan history file sebelumnya
- User bisa lihat history revisi

### **4. E-Signature (Advanced)**
- Tambah tanda tangan digital
- Integrasi dengan sistem e-signature
- QR Code untuk verifikasi autentikasi

---

## 📸 SCREENSHOT TESTING

Setelah implementasi, screenshot ini untuk dokumentasi:

1. **Admin - Form Upload File**
   - URL: /admin/detailSurat/6
   - Tampilan form upload dengan button hijau

2. **Admin - Success Message**
   - Pesan: "File surat berhasil diupload..."

3. **User Dashboard - Status Selesai**
   - Status hijau: "Selesai & Siap Diunduh"
   - Tombol "Unduh Surat" muncul

4. **File di Server**
   - Screenshot folder `uploads/surat_selesai/`
   - Tampak file PDF yang terupload

5. **Downloaded PDF**
   - File PDF terbuka di browser/PDF reader
   - Isi sesuai yang diupload

---

## ✅ CHECKLIST IMPLEMENTASI

- [x] Buat folder `uploads/surat_selesai/`
- [x] Buat file `.htaccess` proteksi folder
- [x] Update view `admin/surat_detail.php` - tambah form upload
- [x] Buat method `uploadFileSurat()` di Admin Controller
- [x] Tambah route POST untuk upload
- [x] Tambah validasi file (PDF, 5MB)
- [x] Simpan file ke server
- [x] Update database (status + file_surat)
- [x] Buat notifikasi untuk user
- [x] Clear cache
- [ ] **TESTING** - Upload file PDF
- [ ] **TESTING** - Download dari user dashboard
- [ ] **TESTING** - Error handling (file terlalu besar, bukan PDF, dll)

---

## 🐛 TROUBLESHOOTING

### **Problem: "File tidak ditemukan di server"**
**Solution:**
```php
// Cek apakah file exist
$filePath = FCPATH . 'uploads/surat_selesai/' . $surat['file_surat'];
if (!file_exists($filePath)) {
    // File tidak ada! Check folder permissions
}
```

### **Problem: "Failed to upload file"**
**Solution:**
- Cek permission folder: `chmod 755 public/uploads/surat_selesai/`
- Pastikan folder exist
- Cek disk space server

### **Problem: "Maximum upload file size exceeded"**
**Solution:**
Update `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

---

## 📞 SUPPORT

Jika ada error atau pertanyaan:
1. Cek log: `writable/logs/log-[date].log`
2. Screenshot error message
3. Kirim detail error + screenshot

---

**SISTEM SEKARANG SUDAH LENGKAP DAN SIAP DIGUNAKAN!** 🎉

Silakan test dengan skenario di atas. Jika ada error, langsung lapor dengan screenshot ya!
