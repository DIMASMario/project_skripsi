# 📋 Dokumentasi Sistem Layanan Surat Online

**Dokumen ini menjelaskan implementasi lengkap sistem pengajuan surat online untuk Desa Blanakan.**

Disiapkan: 17 Maret 2026

---

## 📌 Ringkasan Eksekutif

Sistem Layanan Surat Online telah diperbarui dengan fitur-fitur baru sesuai spesifikasi yang diberikan. Sistem ini memungkinkan warga desa untuk mengajukan surat secara online melalui website dan admin desa untuk memproses serta mengelola pengajuan surat tersebut.

### Perubahan Utama

**✅ Fitur Surat Ditambahkan:**
- Surat Domisili
- SKTM (Surat Keterangan Tidak Mampu)
- Surat Kelahiran
- Surat Kematian
- Surat Keterangan Pindah Nama
- SKU (Surat Keterangan Usaha)
- SKG (Surat Keterangan Garapan)
- Surat Taksiran Harga Tanah
- SKD (Surat Keterangan Desa)

**❌ Fitur Surat Dihapus:**
- Pengantar SKCK (harus langsung ke Polres)
- Pengantar Nikah (tidak dapat dilakukan online)

**📋 Persyaratan Pengajuan:**
- ✅ Nomor KTP (wajib)
- ✅ Nomor KK (opsional)
- ✅ Keperluan/alasan (wajib)
- ✅ Foto verifikasi identitas (saat registrasi)

---

## 🗄️ Database Schema

### Perubahan pada Tabel `surat`

#### Kolom Baru:
```sql
-- Status Perkawinan (khusus untuk SKD)
ALTER TABLE surat ADD COLUMN status_perkawinan ENUM(
    'janda_hidup', 'janda_mati', 'duda_hidup', 'duda_mati',
    'menikah', 'belum_menikah', 'cerai_hidup', 'cerai_mati'
) NULL DEFAULT NULL;

-- Nomor KK (opsional)
ALTER TABLE surat ADD COLUMN no_kk VARCHAR(16) NULL DEFAULT NULL;
```

#### Kolom yang Dimodifikasi:
```sql
-- Update jenis_surat ENUM
ALTER TABLE surat MODIFY COLUMN jenis_surat ENUM(
    'domisili', 'tidak_mampu', 'kelahiran', 'kematian', 'pindah_nama',
    'usaha', 'garapan', 'taksiran_harga_tanah', 'desa'
) NOT NULL;

-- Update status ENUM (tambah 'diproses' dan 'selesai' mengganti 'disetujui')
ALTER TABLE surat MODIFY COLUMN status ENUM(
    'menunggu', 'diproses', 'selesai', 'ditolak'
) DEFAULT 'menunggu';
```

### Perubahan pada Tabel `users`

#### Kolom Baru:
```sql
-- Nomor KTP (wajib saat registrasi)
ALTER TABLE users ADD COLUMN no_ktp VARCHAR(16) UNIQUE NOT NULL DEFAULT '';

-- Status Verifikasi Foto
ALTER TABLE users ADD COLUMN foto_verifikasi_status ENUM(
    'pending', 'verified', 'rejected'
) DEFAULT 'pending';
```

#### Kolom yang Dimodifikasi:
```sql
-- no_kk menjadi nullable
ALTER TABLE users MODIFY COLUMN no_kk VARCHAR(16) NULL DEFAULT NULL;

-- nik menjadi nullable (legacy, tidak lagi required)
ALTER TABLE users MODIFY COLUMN nik VARCHAR(16) NULL DEFAULT NULL;
```

---

## 🔄 Alur Sistem Lengkap

### 1️⃣ Registrasi Warga

```
Warga mengunjungi website
    ↓
Klik "Daftar" / "Registrasi"
    ↓
Isi Form Registrasi:
  - Nama Lengkap (wajib)
  - Nomor KTP (wajib) - 16 digit
  - Nomor KK (opsional) - 16 digit
  - Alamat (wajib)
  - RT/RW (wajib)
  - Email (opsional)
  - Nomor HP (opsional)
  - Password (wajib)
  - Upload Foto Verifikasi (wajib)
    ↓
Sistem memvalidasi data
    ↓
Jika valid → Akun dibuat dengan status "menunggu verifikasi"
    ↓
Admin memverifikasi identitas melalui foto
    ↓
Jika terverifikasi → Status akun menjadi "disetujui"
    ↓
Warga dapat login dan mengajukan surat
```

### 2️⃣ Pengajuan Surat oleh Warga

```
Warga login ke sistem
    ↓
Masuk ke Dashboard → "Pengajuan Surat"
    ↓
Klik "Ajukan Surat Baru"
    ↓
Pilih Jenis Surat dari dropdown
    ↓
Isi Formulir:
  1. Nomor KTP (wajib) - dari data user atau diisi ulang
  2. Nomor KK (opsional)
  3. Keperluan/Alasan (wajib, min 10 karakter)
  4. Jika memilih SKD → Pilih Status Perkawinan (wajib)
    ↓
Klik "Kirim Pengajuan"
    ↓
Sistem memvalidasi & menyimpan
    ↓
Notifikasi muncul di dashboard warga
    ↓
Warga menerima email konfirmasi (opsional)
    ↓
Status surat = "menunggu"
```

### 3️⃣ Proses oleh Admin

```
Admin login ke sistem
    ↓
Masuk ke "Manajemen Surat"
    ↓
Lihat daftar surat dengan status "menunggu"
    ↓
Klik surat untuk melihat detail
    ↓
Admin memeriksa data pengajuan:
  - Informasi pemohon lengkap?
  - Data surat sesuai?
  - Keperluan jelas?
    ↓
Jika Data Sesuai:
  a. Ubah status → "Diproses"
  b. Admin membuat surat / dokumen
  c. Simpan hasilnya dalam format PDF
  d. Upload file PDF ke sistem
  e. Status otomatis berubah menjadi "Selesai"
    ↓
Jika Data Tidak Sesuai:
  a. Ubah status → "Ditolak"
  b. Masukkan alasan penolakan
  c. Simpan
    ↓
Sistem membuat notifikasi untuk warga
    ↓
Email notifikasi dikirim ke warga
```

### 4️⃣ Surat Selesai Diproses

```
Admin upload file surat
    ↓
Status otomatis berubah menjadi "Selesai"
    ↓
Sistem membuat notifikasi:
  ✓ Di dashboard warga
  ✓ Kirim email ke warga
    ↓
Warga login ke sistem
    ↓
Lihat surat di bagian "Selesai"
    ↓
Klik "Download" untuk mengunduh PDF
    ↓
Warga dapat mencetak atau menyimpan file
```

---

## 📁 File-File yang Dibuat/Dimodifikasi

### Database Migrations

**File Baru:**
1. `2026-03-17-000001_UpdateSuratTableNewJenis.php`
   - Update tabel surat dengan jenis surat baru
   - Tambah kolom status_perkawinan dan no_kk
   - Update enum status surat

2. `2026-03-17-000002_AddFotoVerifikasiToUsersTable.php`
   - Tambah kolom no_ktp (wajib)
   - Tambah kolom foto_verifikasi_status
   - Update kolom no_kk dan nik menjadi nullable

### Models

**File Dimodifikasi:**
1. `app/Models/SuratModel.php`
   - Update allowedFields dengan kolom baru
   - Update validationRules
   - Fungsi baru: `getListJenisSurat()`
   - Fungsi baru: `getListStatusPerkawinan()`

2. `app/Models/UserModel.php`
   - Update allowedFields dengan kolom baru
   - Update validationRules untuk registrasi
   - Validasi nomor KTP wajib

### Controllers

**File Baru:**
1. `app/Controllers/SuratPengajuan.php`
   - `index()` - Dashboard pengajuan surat warga
   - `form()` - Halaman form pengajuan surat
   - `proses()` - Proses pengajuan (AJAX/POST)
   - `detail()` - Lihat detail surat
   - `download()` - Download file surat
   - `listSurat()` - API list surat (AJAX)

2. `app/Controllers/AdminSurat.php`
   - `index()` - Dashboard manajemen surat admin
   - `detail()` - Detail surat untuk review
   - `ubahStatus()` - Ubah status surat
   - `uploadFile()` - Upload file surat yang selesai
   - `tolak()` - Tolak pengajuan surat
   - `laporan()` - Laporan surat bulanan

### Views

**File Baru:**
1. `app/Views/warga/form_pengajuan_surat.php`
   - Form pengajuan surat untuk warga

2. `app/Views/admin/warga_surat_list.php`
   - Dashboard pengajuan surat warga

3. `app/Views/admin/manajemen_surat.php`
   - Dashboard manajemen surat admin

4. `app/Views/admin/detail_surat.php`
   - Detail surat untuk admin (review & proses)

5. `app/Views/warga/detail_surat.php`
   - Detail surat untuk warga

6. `app/Views/emails/notifikasi_surat_selesai.php`
   - Template email notifikasi surat selesai

---

## 🔗 URL Routes

**Surat Pengajuan (Warga):**
- `GET /surat-pengajuan` - Dashboard pengajuan surat
- `GET /surat-pengajuan/form` - Form pengajuan surat
- `GET /surat-pengajuan/form/{jenis}` - Form dengan pre-select jenis surat
- `POST /surat-pengajuan/proses` - Submit pengajuan surat
- `GET /surat-pengajuan/detail/{id}` - Detail surat
- `GET /surat-pengajuan/download/{id}` - Download surat PDF
- `GET /surat-pengajuan/listSurat` - API list surat

**Admin Surat:**
- `GET /admin-surat` - Dashboard manajemen surat
- `GET /admin-surat/detail/{id}` - Detail surat
- `POST /admin-surat/ubahStatus/{id}` - Ubah status surat
- `POST /admin-surat/uploadFile/{id}` - Upload file surat
- `POST /admin-surat/tolak/{id}` - Tolak pengajuan surat
- `GET /admin-surat/laporan` - Laporan surat bulanan

**Catatan:** Routes perlu ditambahkan ke file `routes.php` sesuai pattern yang ada.

---

## 🔐 Fitur Keamanan

1. **Validasi Input**
   - Validasi NIK/KTP 16 digit
   - Validasi nomor KK 16 digit
   - Validasi keperluan minimum 10 karakter
   - Sanitasi input untuk mencegah XSS

2. **Autentikasi**
   - Session-based login
   - Warga hanya bisa akses data mereka sendiri
   - Admin hanya bisa akses dashboard admin

3. **File Upload**
   - Hanya file PDF yang diperbolehkan
   - Maksimal ukuran 5MB
   - Menyimpan di folder khusus `writable/uploads/surat_selesai`
   - File di-generate nama unik untuk keamanan

4. **Data Privacy**
   - Nomor KTP hanya ditampilkan sebagian (4 digit terakhir)
   - Email hanya bisa dilihat oleh admin

---

## 📧 Sistem Notifikasi

### Dashboard Notifikasi

**Untuk Warga:**
- Ketika pengajuan diterima: "Pengajuan Surat Diterima"
- Ketika surat diproses: "Surat Sedang Diproses"
- Ketika surat selesai: "Surat Sudah Selesai"
- Ketika surat ditolak: "Pengajuan Surat Ditolak"

**Untuk Admin:**
- Saat ada pengajuan surat baru (count di dashboard)

### Email Notifikasi

**Email dikirim saat:**
1. Pengajuan surat diterima (opsional)
2. Surat selesai dan siap diunduh (WAJIB)

**Template Email:**
- File: `app/Views/emails/notifikasi_surat_selesai.php`
- Format: HTML yang responsive
- Berisi: Link download, instruksi, info surat

**Konfigurasi Email:**
- Edit file: `app/Config/Email.php`
- Set SMTP credentials sesuai penyedia email
- Contoh provider: Gmail, SendGrid, Mailgun

---

## ⚙️ Instalasi & Setup

### 1. Jalankan Migrations

```bash
php spark migrate
```

Atau jalankan migrations spesifik yang baru:

```bash
php spark migrate --group Database/Migrations
```

### 2. Konfigurasi Email (Opsional)

Edit `app/Config/Email.php`:

```php
public $fromEmail = 'noreply@desablanakan.id';
public $fromName = 'Desa Blanakan';

// SMTP Configuration
public $protocol = 'smtp';
public $SMTPHost = 'smtp.gmail.com'; // atau provider lain
public $SMTPUser = 'your-email@gmail.com';
public $SMTPPass = 'your-app-password';
public $SMTPPort = 587;
public $SMTPCrypto = 'tls';
```

### 3. Buat Folder Uploads (Jika Belum Ada)

```bash
mkdir -p writable/uploads/surat_selesai
chmod 755 writable/uploads/surat_selesai
```

### 4. Update Routes

Edit `app/Config/Routes.php` dan tambahkan routes baru:

```php
// Surat Pengajuan (Warga)
$routes->group('surat-pengajuan', function($routes) {
    $routes->get('/', 'SuratPengajuan::index');
    $routes->get('form/(:any)?', 'SuratPengajuan::form/$1');
    $routes->post('proses', 'SuratPengajuan::proses');
    $routes->get('detail/(:num)', 'SuratPengajuan::detail/$1');
    $routes->get('download/(:num)', 'SuratPengajuan::download/$1');
    $routes->get('listSurat', 'SuratPengajuan::listSurat');
});

// Admin Surat
$routes->group('admin-surat', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'AdminSurat::index');
    $routes->get('detail/(:num)', 'AdminSurat::detail/$1');
    $routes->post('ubahStatus/(:num)', 'AdminSurat::ubahStatus/$1');
    $routes->post('uploadFile/(:num)', 'AdminSurat::uploadFile/$1');
    $routes->post('tolak/(:num)', 'AdminSurat::tolak/$1');
    $routes->get('laporan', 'AdminSurat::laporan');
});
```

---

## 📊 Fitur Laporan

Admin dapat melihat laporan surat per bulan:
- URL: `/admin-surat/laporan`
- Filter: Tahun & Bulan
- Data: Jumlah surat per jenis dan status

---

## 🧪 Testing Checklist

### Registrasi Warga
- [ ] Isi form registrasi dengan nomor KTP 16 digit
- [ ] Upload foto verifikasi
- [ ] Verifikasi berhasil dari admin
- [ ] Dapat login dengan email/nomor HP

### Pengajuan Surat
- [ ] Login sebagai warga
- [ ] Klik "Ajukan Surat Baru"
- [ ] Pilih jenis surat
- [ ] Isi nomor KTP & Keperluan
- [ ] Submit pengajuan
- [ ] Lihat notifikasi di dashboard
- [ ] Surat muncul dengan status "Menunggu"

### Proses Admin
- [ ] Login sebagai admin
- [ ] Masuk ke "Manajemen Surat"
- [ ] Lihat surat dengan status "Menunggu"
- [ ] Klik "Lihat" untuk detail
- [ ] Ubah status menjadi "Diproses"
- [ ] Upload file PDF surat
- [ ] Status berubah menjadi "Selesai"

### Download Surat
- [ ] Login sebagai warga
- [ ] Lihat surat dengan status "Selesai"
- [ ] Klik tombol "Download"
- [ ] File PDF berhasil diunduh

### Email Notifikasi
- [ ] Cek email warga saat surat selesai
- [ ] Email berisi link download
- [ ] Link dapat diakses

---

## 🚀 Deployment Notes

1. **Backup Database Sebelum Migrate**
   ```bash
   mysqldump -u root -p desa_blanakan > backup_sebelum_migrate.sql
   ```

2. **Production Configuration**
   - Set `ENVIRONMENT=production` di `.env`
   - Enable error logging
   - Disable debug toolbar

3. **Email Configuration**
   - Gunakan SMTP yang reliable
   - Set sender email resmi desa
   - Monitor email delivery

4. **File Security**
   - Backup folder uploads secara berkala
   - Set proper permissions (755 untuk folder, 644 untuk file)
   - Gunakan virus scanner untuk uploads

---

## 📞 Support & Troubleshooting

### Masalah: Email tidak terkirim

**Solusi:**
1. Cek konfigurasi SMTP di `app/Config/Email.php`
2. Test koneksi SMTP
3. Cek log file: `writable/logs/`

### Masalah: File tidak bisa diupload

**Solusi:**
1. Cek permission folder `writable/uploads/surat_selesai`
2. Cek ukuran file (max 5MB)
3. Gunakan format PDF

### Masalah: 404 Routes Not Found

**Solusi:**
1. Pastikan routes sudah ditambahkan di `app/Config/Routes.php`
2. Cek nama controller & method yang benar
3. Clear cache: `php spark cache:clear`

---

## 📝 Notes & Future Enhancements

**Fitur Potensial untuk di-masa depan:**

1. ✅ Template surat yang dapat dikustomisasi per jenis surat
2. ✅ Tracking real-time pengajuan dengan status update
3. ✅ Upload dokumen tambahan (lampiran)
4. ✅ Sistem antrian & jadwal pengambilan surat
5. ✅ QR code di surat untuk verifikasi
6. ✅ Integrasi dengan WhatsApp notifikasi
7. ✅ Dashboard statistik untuk analisis pengajuan

---

**Dokumen versi 1.0**  
**Dibuat: 17 Maret 2026**  
**Oleh: Tim Pengembang Sistem Desa**
