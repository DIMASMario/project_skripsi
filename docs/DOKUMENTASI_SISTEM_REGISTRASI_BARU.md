# 🔐 SISTEM REGISTRASI DAN LOGIN BARU
# Website Desa Blanakan - Berbasis Kode Registrasi Warga

**Tanggal:** 9 Februari 2026  
**Versi:** 2.0 - Privacy Enhanced Registration System

---

## 📋 RINGKASAN PERUBAHAN

Sistem registrasi dan login telah dirancang ulang untuk:
- ✅ **Menghilangkan penggunaan NIK dan KK** dalam proses registrasi dan autentikasi
- ✅ **Menggunakan Kode Registrasi Warga** sebagai validasi administratif
- ✅ **Login menggunakan Email atau Nomor HP** (bukan NIK)
- ✅ **Meningkatkan privasi** data kependudukan
- ✅ **Menyederhanakan proses** registrasi warga

---

## 🎯 KONSEP SISTEM BARU

### Format Kode Registrasi Warga
```
BLK-RT03RW02-0007
│   │    │   └─── Nomor urut warga (4 digit)
│   │    └─────── RW (2 digit dengan leading zero)
│   └──────────── RT (2 digit dengan leading zero)
└──────────────── Kode Desa Blanakan
```

**Contoh:**
- `BLK-RT01RW01-0001` → Warga pertama di RT 01 RW 01
- `BLK-RT03RW02-0025` → Warga ke-25 di RT 03 RW 02

### Karakteristik Kode:
- ✅ Unik untuk setiap warga
- ✅ Sekali pakai (single-use)
- ✅ Dibuat dan dikelola oleh admin desa
- ✅ Dapat memiliki tanggal kadaluarsa (opsional)

---

## 🗄️ STRUKTUR DATABASE

### Tabel Baru: `kode_registrasi`

```sql
CREATE TABLE `kode_registrasi` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode_registrasi` VARCHAR(20) UNIQUE NOT NULL,
    `rt` VARCHAR(3) NOT NULL,
    `rw` VARCHAR(3) NOT NULL,
    `nomor_urut` INT(11) NOT NULL,
    `status` ENUM('belum_digunakan', 'sudah_digunakan', 'kadaluarsa') DEFAULT 'belum_digunakan',
    `user_id` INT(11) UNSIGNED NULL,
    `keterangan` TEXT NULL,
    `created_by` INT(11) UNSIGNED NULL,
    `used_at` DATETIME NULL,
    `expired_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    KEY `kode_registrasi` (`kode_registrasi`),
    KEY `status` (`status`),
    KEY `rt` (`rt`),
    KEY `rw` (`rw`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
```

### Modifikasi Tabel `users`

**Field Baru:**
- `kode_registrasi_id` → Foreign key ke tabel kode_registrasi
- `login_identifier` → Email atau nomor HP (denormalized untuk performa login)

**Field yang Diubah:**
- `nik` → Sekarang NULLABLE (tidak wajib)
- `no_kk` → Sekarang NULLABLE (tidak wajib)
- `username` → Sekarang NULLABLE (tidak digunakan untuk login)

---

## 🔄 ALUR SISTEM

### A. ALUR PEMBUATAN KODE (Admin)

```
┌─────────────────────────────────────┐
│  1. Admin Login ke Dashboard        │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  2. Masuk Menu "Kode Registrasi"    │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  3. Klik "Buat Kode Baru"           │
│     - Input RT                       │
│     - Input RW                       │
│     - Input Jumlah (1-100)          │
│     - Keterangan (opsional)         │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  4. Sistem Generate Kode            │
│     - Format: BLK-RT##RW##-####     │
│     - Nomor urut otomatis           │
│     - Status: belum_digunakan       │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  5. Kode Tersimpan                  │
│     - Bisa dicetak                  │
│     - Bisa diexport CSV             │
│     - Siap dibagikan ke warga       │
└─────────────────────────────────────┘
```

### B. ALUR REGISTRASI WARGA

```
┌─────────────────────────────────────┐
│  1. Warga mengunjungi website       │
│     /auth/register                  │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  2. Mengisi Form Registrasi:        │
│     - Nama Lengkap                  │
│     - Alamat                        │
│     - RT & RW                       │
│     - Email (opsional)              │
│     - Nomor HP (wajib)              │
│     - Kode Registrasi (wajib)       │
│     - Password                      │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  3. Validasi Kode Registrasi        │
│     ✓ Kode ada di database?         │
│     ✓ Status belum digunakan?       │
│     ✓ RT & RW cocok?                │
│     ✓ Tidak kadaluarsa?             │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  4. Data Disimpan                   │
│     - Status: menunggu              │
│     - Kode: sudah_digunakan         │
│     - Notifikasi ke admin           │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  5. Admin Verifikasi Manual         │
│     - Cek data warga                │
│     - Approve / Reject              │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  6. Akun Aktif / Ditolak            │
│     - Notifikasi ke warga           │
└─────────────────────────────────────┘
```

### C. ALUR LOGIN WARGA

```
┌─────────────────────────────────────┐
│  1. Warga mengunjungi /auth/login   │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  2. Input Kredensial:               │
│     - Email ATAU Nomor HP           │
│     - Password                      │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  3. Validasi:                       │
│     ✓ User exists?                  │
│     ✓ Password benar?               │
│     ✓ Status disetujui?             │
└──────────────┬──────────────────────┘
               ▼
┌─────────────────────────────────────┐
│  4. Login Berhasil                  │
│     - Redirect ke /dashboard        │
└─────────────────────────────────────┘
```

---

## 📂 FILE-FILE YANG DIBUAT

### 1. Migration Files
- `app/Database/Migrations/2026-02-09-000001_CreateKodeRegistrasiTable.php`
- `app/Database/Migrations/2026-02-09-000002_UpdateUsersTableForNewRegistration.php`

### 2. Model Files
- `app/Models/KodeRegistrasiModel.php` (NEW)
- `app/Models/UserModel.php` (UPDATED)

### 3. Controller Files
- `app/Controllers/AuthNew.php` (NEW - Controller baru untuk sistem baru)
- `app/Controllers/Admin/KodeRegistrasiController.php` (NEW)

### 4. View Files

**Auth Views:**
- `app/Views/auth/register_new.php` - Form registrasi baru
- `app/Views/auth/login_new.php` - Form login baru
- `app/Views/auth/admin_login_new.php` - Form login admin

**Admin Views:**
- `app/Views/admin/kode_registrasi/index.php` - List kode registrasi
- `app/Views/admin/kode_registrasi/create.php` - Form buat kode
- `app/Views/admin/kode_registrasi/detail.php` - Detail kode

---

## 🚀 CARA IMPLEMENTASI

### Langkah 1: Jalankan Migration

```bash
cd c:\wamp64\www\project-skripsi\desa_tanjungbaru
php spark migrate
```

Atau manual import SQL:
```sql
-- Import migration SQL ke database db_desa_blanakan
```

### Langkah 2: Update Routes

Edit `app/Config/Routes.php`:

```php
// Auth Routes - New System
$routes->get('auth/login', 'AuthNew::login');
$routes->post('auth/login', 'AuthNew::login');
$routes->get('auth/register', 'AuthNew::register');
$routes->post('auth/register', 'AuthNew::register');
$routes->get('auth/admin-login', 'AuthNew::adminLogin');
$routes->post('auth/admin-login', 'AuthNew::adminLogin');
$routes->get('auth/logout', 'AuthNew::logout');

// Admin - Kode Registrasi Management
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('kode-registrasi', 'Admin\KodeRegistrasiController::index');
    $routes->get('kode-registrasi/create', 'Admin\KodeRegistrasiController::create');
    $routes->post('kode-registrasi/create', 'Admin\KodeRegistrasiController::create');
    $routes->get('kode-registrasi/detail/(:num)', 'Admin\KodeRegistrasiController::detail/$1');
    $routes->post('kode-registrasi/delete/(:num)', 'Admin\KodeRegistrasiController::delete/$1');
    $routes->post('kode-registrasi/update-status/(:num)', 'Admin\KodeRegistrasiController::updateStatus/$1');
    $routes->get('kode-registrasi/export', 'Admin\KodeRegistrasiController::export');
    $routes->post('kode-registrasi/print', 'Admin\KodeRegistrasiController::printCodes');
});
```

### Langkah 3: Test System

1. **Login sebagai Admin:**
   - URL: `http://localhost/project-skripsi/desa_tanjungbaru/public/auth/admin-login`
   - Email: admin@desa.com
   - Password: (password admin yang ada)

2. **Buat Kode Registrasi:**
   - Menu: Kode Registrasi → Buat Kode Baru
   - Input RT: 01
   - Input RW: 01
   - Jumlah: 5

3. **Test Registrasi Warga:**
   - URL: `http://localhost/project-skripsi/desa_tanjungbaru/public/auth/register`
   - Isi form dengan data test
   - Gunakan salah satu kode yang dibuat

4. **Test Login:**
   - URL: `http://localhost/project-skripsi/desa_tanjungbaru/public/auth/login`
   - Gunakan email atau nomor HP yang didaftarkan

---

## 🔧 FITUR ADMIN - KODE REGISTRASI

### Menu Utama
- **Dashboard Statistik:**
  - Total kode
  - Belum digunakan
  - Sudah digunakan
  - Kadaluarsa

- **Filter & Search:**
  - Filter by status
  - Filter by RT/RW
  - Search by kode

- **Aksi:**
  - Lihat detail kode
  - Hapus kode (hanya yang belum digunakan)
  - Export ke CSV
  - Cetak kode untuk distribusi

### Batch Generation
- Buat hingga 100 kode sekaligus
- Nomor urut otomatis dilanjutkan
- Preview format kode real-time

---

## 🔒 KEAMANAN & PRIVASI

### Apa yang TIDAK Disimpan:
- ❌ NIK tidak wajib saat registrasi
- ❌ Nomor KK tidak wajib
- ❌ Foto KTP/KK tidak wajib
- ❌ Dokumen identitas nasional

### Apa yang Disimpan:
- ✅ Nama lengkap
- ✅ Alamat
- ✅ RT/RW
- ✅ Email (opsional)
- ✅ Nomor HP (untuk login)
- ✅ Password (hashed)
- ✅ Kode registrasi (reference)

### Validasi:
- Kode registrasi harus valid
- RT/RW harus sesuai dengan kode
- Email/HP tidak boleh duplikat
- Verifikasi manual oleh admin

---

## 📊 PERBANDINGAN SISTEM

| Aspek | Sistem Lama | Sistem Baru |
|-------|-------------|-------------|
| **Registrasi** | NIK + KK wajib | Kode registrasi |
| **Login** | NIK/Username | Email/Nomor HP |
| **Privasi** | Data sensitif tersimpan | Data minimal |
| **Validasi** | Otomatis by NIK | Manual by admin |
| **Keamanan** | Risiko kebocoran NIK | Tidak ada NIK |
| **Kemudahan** | Form panjang | Form sederhana |

---

## 📝 PANDUAN OPERASIONAL

### Untuk Admin Desa:

1. **Persiapan Kode:**
   - Tentukan jumlah kode yang dibutuhkan per RT/RW
   - Buat kode melalui dashboard
   - Cetak atau export kode untuk distribusi

2. **Distribusi Kode:**
   - Bagikan kode kepada warga yang datang ke kantor desa
   - Verifikasi identitas warga sebelum memberi kode
   - Catat pemberian kode (manual di buku registrasi)

3. **Verifikasi Pendaftaran:**
   - Cek notifikasi pendaftaran baru
   - Verifikasi data warga
   - Approve atau reject pendaftaran

### Untuk Warga:

1. **Dapatkan Kode:**
   - Datang ke kantor desa
   - Bawa identitas diri
   - Minta kode registrasi

2. **Registrasi Online:**
   - Kunjungi website desa
   - Isi form registrasi
   - Masukkan kode yang didapat
   - Tunggu verifikasi admin

3. **Login:**
   - Gunakan email atau nomor HP
   - Masukkan password
   - Akses layanan online

---

## ⚠️ CATATAN PENTING

1. **Backward Compatibility:**
   - Data user lama tetap aman
   - User lama masih bisa login dengan cara lama
   - Implementasi bertahap direkomendasikan

2. **Migration Data Lama:**
   - User existing tidak perlu kode registrasi
   - Field NIK/KK tetap ada untuk user lama
   - Hanya user BARU yang menggunakan sistem baru

3. **Maintenance:**
   - Jalankan `KodeRegistrasiModel::expireOldCodes()` secara berkala
   - Monitor kode yang tidak terpakai
   - Hapus kode expired secara periodik

---

## 🆘 TROUBLESHOOTING

### Kode Registrasi Tidak Valid
**Penyebab:**
- Kode salah ketik
- RT/RW tidak sesuai
- Kode sudah digunakan
- Kode kadaluarsa

**Solusi:**
- Periksa kembali kode yang diberikan
- Pastikan RT/RW yang diinput benar
- Hubungi admin untuk kode baru

### Login Gagal
**Penyebab:**
- Email/HP salah
- Password salah
- Status masih "menunggu"
- Akun ditolak

**Solusi:**
- Cek email atau nomor HP yang terdaftar
- Reset password jika lupa
- Tunggu verifikasi admin
- Hubungi admin jika ditolak

---

## 📞 KONTAK & DUKUNGAN

Untuk pertanyaan atau bantuan:
- **Admin Desa:** admin@desa.com
- **Kantor Desa:** 0260-123456
- **Website:** https://desablanakan.go.id

---

## 📅 LOG PERUBAHAN

**Versi 2.0 - 9 Februari 2026:**
- ✅ Implementasi sistem kode registrasi
- ✅ Hapus ketergantungan NIK/KK
- ✅ Login dengan email/HP
- ✅ Panel admin untuk kelola kode
- ✅ Dokumentasi lengkap

---

**Dibuat oleh:** GitHub Copilot  
**Tanggal:** 9 Februari 2026  
**Sistem:** Website Desa Blanakan - Kabupaten Subang, Jawa Barat
