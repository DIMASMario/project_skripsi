# Update Profil Pengguna: Form Edit Lengkap + Upload Foto

## 📋 Ringkasan Perubahan

Sistem profil pengguna telah diubah agar **warga dapat mengelola profil mereka sendiri**:

### ✅ Field yang Dapat Diedit Warga:
- **Foto Profil** (upload sendiri)
- Tempat Lahir
- Tanggal Lahir
- Jenis Kelamin
- Agama
- Alamat
- Nomor HP
- Email

### 👁️ Info yang Dilihat Admin:
- Nama Lengkap
- Foto Profil
- Email
- No HP
- Alamat

**Keuntungan:**
- ❌ Tidak perlu hubungi kantor desa untuk update profil
- ✅ User lebih mandiri
- ✅ Admin fokus ke verifikasi data penting
- ✅ Upload foto profil sendiri

---

## 🗂️ File yang Dimodifikasi

### 1. Database Update
**File:** `TEMP_CLEANUP/add_agama_column.sql`
- Menambahkan kolom `agama` (VARCHAR 20)
- Menambahkan kolom `foto_profil` (VARCHAR 255)  
- Posisi: agama setelah `jenis_kelamin`, foto_profil setelah `foto_selfie`

### 2. UserModel
**File:** `app/Models/UserModel.php`
- Menambahkan `'agama'` dan `'foto_profil'` ke `$allowedFields`

### 3. View Profil User (Warga)
**File:** `app/Views/dashboard/profil_standalone.php`

**Perubahan Utama:**
- **Profile Card**: Menampilkan foto profil upload (jika ada) atau avatar generated
- **Form Edit Profil Lengkap**:
  - Upload foto profil dengan preview real-time
  - Field tempat lahir (text input)
  - Field tanggal lahir (date picker)
  - Field jenis kelamin (dropdown: L/P)
  - Field agama (dropdown: 7 pilihan)
  - Field alamat (textarea)
  - Field nomor HP (tel input)
  - Field email (email input)
- **JavaScript**: Preview foto sebelum upload
- **Helper Text**: "Anda dapat mengubah semua informasi di atas melalui form di bawah"

### 4. Controller Dashboard
**File:** `app/Controllers/Dashboard.php` - Method `updateProfile()`

**Perubahan:**
- **Validasi diperluas**: tempat_lahir, tanggal_lahir, jenis_kelamin, agama, foto_profil
- **File Upload Handler**:
  - Validasi: max 2MB, format JPG/PNG
  - Simpan ke `public/uploads/profiles/`
  - Naming: `profile_{user_id}_{timestamp}.{ext}`
  - Hapus foto lama otomatis
- **Error Handling**: Log error jika upload gagal
- **Session Update**: Update nama dan email di session

### 5. Upload Directory
**Folder:** `public/uploads/profiles/`
- Folder baru untuk menyimpan foto profil user
- Permissions: 0755

---

## 📊 Struktur Field Profil Baru

| No | Field | Input Type | Warga Edit? | Admin Lihat? |
|----|-------|------------|-------------|--------------|
| 1 | **Foto Profil** | File Upload | ✅ Ya | ✅ Ya |
| 2 | Nama Lengkap | - | ❌ Tidak | ✅ Ya |
| 3 | Tempat Lahir | Text | ✅ Ya | ➖ Opsional |
| 4 | Tanggal Lahir | Date | ✅ Ya | ➖ Opsional |
| 5 | Jenis Kelamin | Dropdown | ✅ Ya | ➖ Opsional |
| 6 | Agama | Dropdown | ✅ Ya | ➖ Opsional |
| 7 | **Alamat** | Textarea | ✅ Ya | ✅ Ya |
| 8 | **Nomor HP** | Tel | ✅ Ya | ✅ Ya |
| 9 | **Email** | Email | ✅ Ya | ✅ Ya |

**Catatan:** Field yang di-bold adalah yang paling penting untuk admin.

---

## 🚀 Cara Setup

### Langkah 1: Update Database

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database: `db_desa_blanakan`
3. Klik tab **SQL**
4. Copy-paste query dari file: `TEMP_CLEANUP/add_agama_column.sql`
5. Klik **"Go"**

Query yang dijalankan:
```sql
ALTER TABLE `users` 
ADD COLUMN `agama` VARCHAR(20) DEFAULT NULL 
AFTER `jenis_kelamin`;

ALTER TABLE `users` 
ADD COLUMN `foto_profil` VARCHAR(255) DEFAULT NULL 
AFTER `foto_selfie`;
```

### Langkah 2: Verifikasi Folder Upload

Folder `public/uploads/profiles/` sudah dibuat otomatis.  
Jika tidak ada, buat manual dengan permissions 0755.

### Langkah 3: Test Form Profil

1. Login sebagai warga
2. Buka halaman **Profil** (`/dashboard/profil`)
3. Test upload foto profil
4. Isi semua field
5. Klik **Simpan Perubahan**
6. Refresh halaman - foto dan data harus berubah

---

## ✅ Testing Checklist

### Test 1: Update Database
- [ ] Jalankan query SQL di phpMyAdmin
- [ ] Verifikasi kolom `agama` dan `foto_profil` sudah ada:
  ```sql
  SHOW COLUMNS FROM users;
  ```

### Test 2: Upload Foto Profil
- [ ] Login sebagai warga
- [ ] Buka halaman Profil
- [ ] Upload foto (JPG/PNG, < 2MB)
- [ ] Lihat preview foto berubah real-time
- [ ] Klik Simpan
- [ ] Refresh - foto harus muncul di profile card

### Test 3: Edit Field Lainnya
- [ ] Isi Tempat Lahir
- [ ] Pilih Tanggal Lahir
- [ ] Pilih Jenis Kelamin
- [ ] Pilih Agama
- [ ] Update Alamat, No HP, Email
- [ ] Simpan - semua data terisi dengan benar

### Test 4: Validasi
- [ ] Coba upload file > 2MB - harus error
- [ ] Coba upload file PDF - harus error  
- [ ] Coba isi no HP dengan huruf - harus error
- [ ] Coba isi email tidak valid - harus error

### Test 5: Admin View
- [ ] Login sebagai admin
- [ ] Buka daftar users
- [ ] Pastikan admin bisa lihat: Nama, Foto, Email, No HP, Alamat

---

## 🔍 Troubleshooting

### Error: Column 'agama' or 'foto_profil' doesn't exist
**Penyebab:** Query SQL belum dijalankan

**Solusi:** 
1. Buka phpMyAdmin
2. Jalankan query dari `TEMP_CLEANUP/add_agama_column.sql`

### Foto Tidak Muncul Setelah Upload
**Penyebab:** Folder `uploads/profiles/` tidak ada atau permissions salah

**Solusi:**
```bash
# Windows PowerShell
New-Item -ItemType Directory -Force -Path "public\uploads\profiles"

# Linux/Mac
mkdir -m 0755 -p public/uploads/profiles
```

### Preview Foto Tidak Berubah
**Penyebab:** JavaScript error atau browser cache

**Solusi:**
1. Hard refresh (Ctrl+Shift+R)
2. Cek Console untuk JavaScript errors

### Upload Error: "max_size"
**Penyebab:** File > 2MB

**Solusi:** Kompres foto atau pilih foto yang lebih kecil

### Upload Error: "is_image" atau "mime_in"
**Penyebab:** Format file tidak valid

**Solusi:** Gunakan hanya JPG, JPEG, atau PNG

---

## 📝 Fitur Upload Foto Profil

### Spesifikasi:
- **Format:** JPG, JPEG, PNG
- **Ukuran Max:** 2MB (2048 KB)
- **Lokasi:** `public/uploads/profiles/`
- **Naming:** `profile_{user_id}_{timestamp}.{ext}`
- **Preview:** Real-time saat memilih file
- **Auto Delete:** Foto lama dihapus otomatis saat upload baru

### JavaScript Preview:
```javascript
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-foto').style.backgroundImage = 
                'url(' + e.target.result + ')';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
```

### Fallback Avatar:
Jika user belum upload foto, sistem akan generate avatar dari nama menggunakan:
```
https://ui-avatars.com/api/?name={nama}&background=005c99&color=ffffff&size=128
```

---

## 🔐 Keamanan

1. **Validasi File**:
   - File type checking (mime type)
   - File size limit (2MB)
   - Only image files allowed

2. **File Naming**:
   - Unique filename dengan user_id + timestamp
   - Mencegah overwrite file user lain

3. **Permissions**:
   - Folder uploads: 0755
   - Files: Default dari sistem

4. **Auto Cleanup**:
   - Foto lama otomatis dihapus saat upload baru
   - Mencegah disk space penuh

---

## 📅 Changelog

**Date:** 2026-02-15

**Changes:**
- ✅ Removed migration script (error bootstrap.php)
- ✅ Created simple SQL file for manual execution
- ✅ Added `agama` column (VARCHAR 20)
- ✅ Added `foto_profil` column (VARCHAR 255)
- ✅ Updated UserModel allowedFields
- ✅ Rebuilt profile form - all fields editable by user
- ✅ Added photo upload feature with preview
- ✅ Updated Dashboard controller - handle all fields + file upload
- ✅ Created uploads/profiles directory
- ✅ Updated profile card to show uploaded photo
- ✅ Removed text about "contact office to change data"

**Impact:**
- **User Experience:** Warga lebih mandiri, tidak perlu ke kantor desa
- **Admin Workload:** Berkurang, fokus ke verifikasi
- **Data Accuracy:** Lebih akurat karena diisi langsung oleh user
- **Personalization:** User bisa upload foto profil sendiri

---

## 🔗 Related Files

- SQL Update: `TEMP_CLEANUP/add_agama_column.sql`
- Model: `app/Models/UserModel.php`
- View: `app/Views/dashboard/profil_standalone.php`
- Controller: `app/Controllers/Dashboard.php` (method `updateProfile()`)
- Upload Dir: `public/uploads/profiles/`

---

**Catatan Penting:**
- Tidak perlu migration script PHP seperti sebelumnya
- Cukup jalankan SQL manual di phpMyAdmin
- User sekarang bisa update profil sendiri tanpa hubungi admin
- Admin fokus ke data penting: Nama, Foto, Email, HP, Alamat

---

**Dokumentasi dibuat oleh:** GitHub Copilot  
**Tanggal:** 15 Februari 2026  
**Status:** ✅ Siap Digunakan
