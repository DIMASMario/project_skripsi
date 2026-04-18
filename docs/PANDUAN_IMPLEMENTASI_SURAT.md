# ✅ Panduan Implementasi Sistem Layanan Surat Online

## 🎯 Langkah-Langkah Implementasi

### TAHAP 1: Setup Database

#### 1.1 Backup Database (SANGAT PENTING)

```bash
# Backup database sebelum menjalankan migration
mysqldump -u root -p desa_blanakan > backup_$(date +%Y%m%d_%H%M%S).sql
```

#### 1.2 Jalankan Database Migrations

```bash
cd c:\wamp64\www\project-skripsi\desa_tanjungbaru

# Jalankan semua migrations yang pending
php spark migrate

# Atau jalankan migrations spesifik
php spark migrate --name 2026-03-17-000001_UpdateSuratTableNewJenis
php spark migrate --name 2026-03-17-000002_AddFotoVerifikasiToUsersTable
```

#### 1.3 Verifikasi Struktur Database

Buka phpMyAdmin dan verifikasi tabel `surat`:
- ✅ Kolom `jenis_surat` memiliki enum baru
- ✅ Kolom `status_perkawinan` ada
- ✅ Kolom `no_kk` ada
- ✅ Kolom `status` memiliki value 'diproses' dan 'selesai'

Verifikasi tabel `users`:
- ✅ Kolom `no_ktp` ada
- ✅ Kolom `foto_verifikasi_status` ada
- ✅ Kolom `no_kk` nullable
- ✅ Kolom `nik` nullable

---

### TAHAP 2: File & Folder Setup

#### 2.1 Buat Folder untuk Uploads

```bash
# Windows Command Prompt (jalankan sebagai Administrator)
mkdir c:\wamp64\www\project-skripsi\desa_tanjungbaru\writable\uploads\surat_selesai

# Atau melalui file explorer
# Navigate ke: desa_tanjungbaru\writable\uploads\
# Buat folder baru: surat_selesai
```

#### 2.2 Verifikasi File-File yang Dimuat

Pastikan file-file berikut sudah ada:

**Controllers:**
- ✅ `app/Controllers/SuratPengajuan.php`
- ✅ `app/Controllers/AdminSurat.php`

**Models:**
- ✅ `app/Models/SuratModel.php` (dimodifikasi)
- ✅ `app/Models/UserModel.php` (dimodifikasi)

**Views:**
- ✅ `app/Views/warga/form_pengajuan_surat.php`
- ✅ `app/Views/admin/warga_surat_list.php`
- ✅ `app/Views/admin/manajemen_surat.php`
- ✅ `app/Views/admin/detail_surat.php`
- ✅ `app/Views/warga/detail_surat.php`
- ✅ `app/Views/emails/notifikasi_surat_selesai.php`

**Database:**
- ✅ `app/Database/Migrations/2026-03-17-000001_UpdateSuratTableNewJenis.php`
- ✅ `app/Database/Migrations/2026-03-17-000002_AddFotoVerifikasiToUsersTable.php`

---

### TAHAP 3: Konfigurasi Routes

#### 3.1 Edit File Routes

Buka file: `app/Config/Routes.php`

Cari bagian routes dan tambahkan sebelum `$routes->get('/', 'Home::index');`:

```php
// ========== SURAT PENGAJUAN (WARGA) ==========
$routes->group('surat-pengajuan', function($routes) {
    $routes->get('/', 'SuratPengajuan::index');
    $routes->get('form/(:any)?', 'SuratPengajuan::form/$1');
    $routes->post('proses', 'SuratPengajuan::proses');
    $routes->get('detail/(:num)', 'SuratPengajuan::detail/$1');
    $routes->get('download/(:num)', 'SuratPengajuan::download/$1');
    $routes->get('listSurat', 'SuratPengajuan::listSurat');
});

// ========== ADMIN SURAT ==========
$routes->group('admin-surat', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'AdminSurat::index');
    $routes->get('detail/(:num)', 'AdminSurat::detail/$1');
    $routes->post('ubahStatus/(:num)', 'AdminSurat::ubahStatus/$1');
    $routes->post('uploadFile/(:num)', 'AdminSurat::uploadFile/$1');
    $routes->post('tolak/(:num)', 'AdminSurat::tolak/$1');
    $routes->get('laporan', 'AdminSurat::laporan');
});
```

#### 3.2 Verifikasi Routes

Test routes di browser:
- Akses: `http://localhost/project-skripsi/desa_tanjungbaru/surat-pengajuan`
- Akses: `http://localhost/project-skripsi/desa_tanjungbaru/admin-surat` (harus login admin)

---

### TAHAP 4: Konfigurasi Email (Opsional)

#### 4.1 Edit Email Configuration

Buka file: `app/Config/Email.php`

Ubah konfigurasi SMTP:

```php
public class Email
{
    public $fromEmail = 'noreply@desablanakan.id';
    public $fromName = 'Desa Blanakan';
    public $protocol = 'smtp';
    public $SMTPHost = 'smtp.gmail.com'; // Ganti dengan SMTP provider Anda
    public $SMTPUser = 'your-email@gmail.com'; // Email Anda
    public $SMTPPass = 'your-app-password'; // App Password (bukan password biasa)
    public $SMTPPort = 587;
    public $SMTPCrypto = 'tls';  // atau 'ssl'
    public $SMTPTimeout = 30;
}
```

#### 4.2 Setup Gmail (Jika Menggunakan Gmail)

1. Buka: https://myaccount.google.com/security
2. Aktifkan "2-Step Verification"
3. Buat "App Passwords" untuk Gmail
4. Copy app password ke config `SMTPPass`

#### 4.3 Test Email Sending

Di dalam controller atau command, test dengan:

```php
$email = \Config\Services::email();
$email->setTo('test@example.com');
$email->setSubject('Test Email');
$email->setMessage('Ini email test');
if ($email->send()) {
    echo 'Email berhasil dikirim';
} else {
    echo 'Email gagal dikirim: ' . $email->printDebugger();
}
```

---

### TAHAP 5: Testing Sistem

#### 5.1 Test Registrasi Warga

**Langkah-langkah:**

1. Buka website: `http://localhost/project-skripsi/desa_tanjungbaru/`
2. Klik "Daftar" atau "Registrasi"
3. Isi form dengan data:
   - Nama: `Test Warga`
   - No KTP: `1234567890123456` (16 digit)
   - No KK: `9876543210654321` (opsional)
   - Alamat: `Jl. Test No 1`
   - RT/RW: `01/01`
   - Email: `test.warga@example.com`
   - No HP: `081234567890`
   - Password: `password123`
   - Upload foto: upload file gambar

4. Klik "Daftar"
5. Verifikasi di database: `SELECT * FROM users WHERE nama_lengkap='Test Warga'`
6. Status akun seharusnya: "menunggu" atau "pending"

#### 5.2 Test Pengajuan Surat (Warga)

**Langkah-langkah:**

1. Login sebagai warga (dengan akun yang sudah terdaftar)
2. Masuk ke Dashboard → "Pengajuan Surat" atau klik menu "Surat"
3. Klik "Ajukan Surat Baru"
4. Pilih jenis surat: "Surat Keterangan Domisili"
5. Isi form:
   - Nomor KTP: `1234567890123456`
   - Nomor KK: (kosongkan, opsional)
   - Keperluan: `Saya membutuhkan surat domisili untuk keperluan administrasi`
6. Klik "Kirim Pengajuan"
7. Verifikasi:
   - Notifikasi muncul di dashboard warga
   - Database check: `SELECT * FROM surat WHERE user_id=[user_id]`
   - Status seharusnya: "menunggu"

#### 5.3 Test Admin Proses Surat

**Langkah-langkah:**

1. Login sebagai admin
2. Masuk ke "Manajemen Surat"
3. Lihat surat dengan status "Menunggu"
4. Klik "Lihat" untuk melihat detail
5. Di halaman detail:
   - Review data pemohon
   - Ubah status menjadi "Diproses"
   - Klik "Simpan Perubahan"
6. Kembali ke daftar, surat sekarang status "Diproses"
7. Klik "Lihat" lagi untuk upload file
8. Upload file PDF surat:
   - Pilih file PDF
   - Klik "Upload File & Tandai Selesai"
9. Verifikasi:
   - Status berubah menjadi "Selesai"
   - File tersimpan di: `writable/uploads/surat_selesai/`
   - Database check: `SELECT * FROM surat WHERE id=[surat_id]`

#### 5.4 Test Download Surat (Warga)

**Langkah-langkah:**

1. Login sebagai warga
2. Masuk ke "Pengajuan Surat Saya"
3. Lihat surat dengan status "Selesai"
4. Klik tombol "Download PDF"
5. File PDF seharusnya berhasil diunduh

#### 5.5 Test Email Notifikasi

**Langkah-langkah:**

1. Lakukan proses surat hingga selesai
2. Cek email warga (inbox atau spam folder)
3. Verifikasi email berisi:
   - ✅ Pemberitahuan surat selesai
   - ✅ Link download
   - ✅ Formatnya menarik dan profesional

---

### TAHAP 6: Validasi Fitur Khusus

#### 6.1 Test SKD dengan Status Perkawinan

**Langkah-langkah:**

1. Warga klik "Ajukan Surat Baru"
2. Pilih jenis: "Surat Keterangan Desa (SKD)"
3. Field "Status Perkawinan" seharusnya muncul
4. Pilih status: "Menikah"
5. Isi data lainnya
6. Kirim pengajuan
7. Verifikasi di database: `SELECT status_perkawinan FROM surat WHERE id=[surat_id]`

#### 6.2 Test Nomor KK Opsional

**Langkah-langkah:**

1. Warga ajukan surat dengan nomor KK kosong
2. Sistem seharusnya menerima
3. Verifikasi di database: nomor KK seharusnya NULL

#### 6.3 Test Penolakan Surat

**Langkah-langkah:**

1. Admin buka detail surat
2. Isi alasan penolakan: "Data tidak lengkap"
3. Klik tombol "Tolak Pengajuan"
4. Verifikasi:
   - Status berubah menjadi "Ditolak"
   - Pesan admin tersimpan
   - Warga menerima notifikasi penolakan

---

### TAHAP 7: Data Validation Testing

#### 7.1 Invalid NIK Input

- ✅ NIK kurang dari 16 digit → Err: "NIK harus 16 digit"
- ✅ NIK lebih dari 16 digit → Err: "NIK harus 16 digit"
- ✅ NIK berisi huruf → Err: Validasi gagal

#### 7.2 Invalid No KK Input

- ✅ No KK kurang dari 16 digit (jika diisi) → Error
- ✅ No KK lebih dari 16 digit → Error

#### 7.3 Invalid Keperluan Input

- ✅ Keperluan kurang dari 10 karakter → Err: "Minimum 10 karakter"
- ✅ Keperluan kosong → Err: "Wajib diisi"

---

## 🔍 Troubleshooting

### ❌ Error: "404 Page Not Found" saat akses surat-pengajuan

**Solusi:**
1. Pastikan routes sudah ditambahkan di `app/Config/Routes.php`
2. Clear cache: `php spark cache:clear`
3. Restart Apache/WAMP

### ❌ Error: "SQLSTATE[42S22]: Column not found"

**Solusi:**
1. Pastikan migrations sudah jalan: `php spark migrate`
2. Check database struktur di phpMyAdmin
3. Jalankan migration ulang jika perlu: `php spark migrate:rollback` lalu `php spark migrate`

### ❌ Email tidak terkirim

**Solusi:**
1. Cek config SMTP di `app/Config/Email.php`
2. Test koneksi SMTP
3. Cek error log: `writable/logs/`
4. Jika menggunakan Gmail, gunakan "App Passwords", bukan password biasa

### ❌ File PDF tidak bisa diupload

**Solusi:**
1. Cek folder `writable/uploads/surat_selesai` sudah ada
2. Cek permission folder: seharusnya 755
3. Cek ukuran file: max 5MB
4. Pastikan file adalah PDF yang valid

### ❌ Status perkawinan tidak muncul untuk SKD

**Solusi:**
1. Check migration sudah berjalan: kolom `status_perkawinan` ada di database
2. Clear browser cache (Ctrl+F5)
3. Check JavaScript harusnya menampilkan field saat pilih "desa"

---

## 📋 Checklist Verifikasi Akhir

Sebelum go-live, pastikan:

- [ ] Database migrations sudah jalan
- [ ] Folder `writable/uploads/surat_selesai` sudah dibuat
- [ ] Routes sudah ditambahkan di `app/Config/Routes.php`
- [ ] Controllers dan Models sudah ter-upload dengan benar
- [ ] Views sudah ter-upload dengan benar
- [ ] Email config sudah diatur (jika menggunakan email)
- [ ] Test registrasi warga berhasil
- [ ] Test pengajuan surat berhasil
- [ ] Test admin proses surat berhasil
- [ ] Test download surat berhasil
- [ ] Test email notifikasi berhasil (optional)
- [ ] Data validation berfungsi dengan baik
- [ ] No error di browser console
- [ ] No error di server logs

---

## 🚀 Go-Live Checklist

Ketika siap untuk production:

1. **Backup Everything**
   - Database backup
   - File backup (uploads, views, controllers)

2. **Performance Tuning**
   - Clear cache: `php spark cache:clear`
   - Optimize database indexes
   - Enable query caching jika perlu

3. **Security**
   - Set `ENVIRONMENT=production` di `.env`
   - Disable debug toolbar
   - Update security headers
   - Set proper CORS jika needed

4. **Monitoring**
   - Setup error logging
   - Monitor email delivery
   - Monitor file uploads
   - Check CPU & memory usage

5. **Documentation**
   - Inform users tentang fitur baru
   - Provide tutorial untuk warga
   - Siapkan contact untuk support

---

**Panduan Implementasi Selesai**  
**Semoga sukses! 🎉**
