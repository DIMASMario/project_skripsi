# 🏛️ Website Desa Blanakan - Portal Digital Pemerintahan Desa

Website resmi Desa Blanakan dengan sistem pelayanan digital lengkap untuk warga. Dibangun dengan CodeIgniter 4, sistem ini menyediakan layanan administrasi online, manajemen konten, dan transparansi pemerintahan desa.

## ✨ Fitur Utama

### 👥 Untuk Warga
- **� Registrasi dengan Kode** - Pendaftaran menggunakan kode registrasi dari admin desa (tanpa NIK)
- **📱 Login Email/HP** - Login dengan email atau nomor HP (tidak lagi menggunakan NIK)
- **📝 Pengajuan Surat Online** - Surat Domisili, SKTM, Surat Usaha, dan lainnya
- **📊 Dashboard Pribadi** - Tracking status surat dan riwayat pengajuan
- **📢 Pengumuman Real-time** - Informasi terkini dari pemerintah desa
- **📰 Berita Desa** - Update kegiatan dan program desa
- **📷 Galeri** - Dokumentasi kegiatan desa
- **🔔 Notifikasi** - Pemberitahuan status surat dan pengumuman penting

### 👨‍💼 Untuk Admin
- **� Kelola Kode Registrasi** ⭐ BARU - Generate dan kelola kode registrasi warga (format: BLK-RT##RW##-####)
- **📈 Dashboard Analytics** - Statistik lengkap user, surat, berita, pengunjung
- **✅ Verifikasi User** - Sistem approval registrasi warga baru
- **📄 Manajemen Surat** - Proses, setujui/tolak, upload hasil surat
- **📝 Kelola Berita** - CRUD berita dengan editor rich text dan upload gambar
- **📢 Kelola Pengumuman** - Buat pengumuman dengan prioritas dan periode tayang
- **📷 Kelola Galeri** - Upload dan organisasi foto kegiatan desa
- **👥 Manajemen User** - Edit, suspend, export data user
- **🔒 Keamanan** - Monitoring login attempts, security logs, block IP
- **💾 Backup & Restore** - Database dan file backup otomatis
- **🗂️ System Logs** - View, search, dan clear log files
- **⚙️ Cache Management** - Clear cache data, debugbar, session
- **📊 Visitor Analytics** - Tracking pengunjung harian dengan statistik
- **👤 Role Management** - Assign dan manage user roles

## 🛠️ Teknologi

### Backend
- **Framework**: CodeIgniter 4.6.3 (PHP 8.1+)
- **Database**: MySQL 9.1.0
- **Authentication**: Session-based dengan bcrypt password hashing
- **Security**: CSRF Protection, Rate Limiting, File Upload Validation
- **Cache**: File-based caching untuk performa optimal

### Frontend
- **UI Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js untuk interaktivity
- **Icons**: Material Symbols (Google Icons)
- **Responsive**: Mobile-first design
- **Dark Mode**: Full dark mode support

### Security Features
- ✅ **Registration Code System** ⭐ Registrasi dengan kode untuk privasi warga (tanpa NIK)
- ✅ **Email/Phone Login** ⭐ Login menggunakan email atau nomor HP (bukan NIK)
- ✅ Rate Limiting (Login, Registration)
- ✅ CSRF Token Protection
- ✅ Secure File Upload (Multi-layer validation)
- ✅ Password Hashing (bcrypt)
- ✅ SQL Injection Prevention (Query Builder)
- ✅ XSS Protection (Input filtering & escaping)
- ✅ Security Logging & Monitoring
- ✅ IP Blocking untuk suspicious activity
- ✅ Session Regeneration
- ✅ Security Headers (.htaccess)
- ✅ IP Blocking System
- ✅ Security Event Logging
- ✅ Directory Listing Protection

## 📋 Requirements

- PHP 8.1 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Apache Web Server dengan mod_rewrite
- PHP Extensions:
  - intl
  - mbstring
  - mysqli
  - curl
  - gd (untuk image processing)

## 🚀 Instalasi

### 1. Clone/Download Project
```bash
git clone <repository-url>
cd desa_tanjungbaru
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Database
```bash
# Buat database MySQL
mysql -u root -p

CREATE DATABASE db_desa_blanakan;
EXIT;

# Import database
mysql -u root -p db_desa_blanakan < db_desa_blanakan(5).sql
```

### 4. Konfigurasi Environment
```bash
# Copy file .env
cp .env.example .env

# Edit .env dengan konfigurasi Anda
```

**Konfigurasi penting di `.env`:**
```env
# Environment
CI_ENVIRONMENT = development

# Base URL (sesuaikan dengan setup Anda)
app.baseURL = 'http://localhost:8080/'
# Atau jika pakai WAMP/XAMPP:
# app.baseURL = 'http://localhost/project-skripsi/desa_tanjungbaru/public/'

# Database
database.default.hostname = localhost
database.default.database = db_desa_blanakan
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5. Setup Permissions
```bash
# Linux/Mac
chmod -R 755 writable/
chmod -R 755 public/uploads/

# Windows (lewat File Explorer)
# Klik kanan folder writable & public/uploads > Properties > Security
# Berikan Full Control untuk user IIS_IUSRS atau IUSR
```

### 6. Setup Visitor Logs
```bash
php spark setup:visitor-logs
```

### 7. Jalankan Aplikasi

**Opsi 1: PHP Built-in Server (Development)**
```bash
php spark serve --host localhost --port 8080
```
Akses: http://localhost:8080

**Opsi 2: WAMP/XAMPP**
- Pastikan Apache running
- Akses: http://localhost/project-skripsi/desa_tanjungbaru/public/

## 🔐 Login Admin

**URL**: `/admin` atau `/admin/login`

**Credentials Default:**
- **Email**: `admin@desa.com`
- **Password**: `admin123`

⚠️ **PENTING**: Segera ganti password default setelah login pertama!

## 📁 Struktur Project

```
desa_tanjungbaru/
├── app/
│   ├── Controllers/        # Logic aplikasi
│   │   ├── Admin.php      # Admin panel controller (3300+ lines)
│   │   ├── Auth.php       # Authentication & registration
│   │   ├── Dashboard.php  # Warga dashboard
│   │   ├── Home.php       # Frontend controller
│   │   └── Warga.php      # Warga directory
│   ├── Models/            # Database models
│   │   ├── UserModel.php
│   │   ├── SuratModel.php
│   │   ├── BeritaModel.php
│   │   ├── PengumumanModel.php
│   │   ├── GaleriModel.php
│   │   ├── NotifikasiModel.php
│   │   └── VisitorLogModel.php
│   ├── Views/             # Template HTML
│   │   ├── admin/         # Admin panel views (30+ files)
│   │   ├── dashboard/     # Warga dashboard
│   │   ├── frontend/      # Public frontend
│   │   └── auth/          # Login & registration
│   ├── Config/            # Konfigurasi
│   │   ├── Routes.php     # URL routing
│   │   ├── Database.php   # DB config
│   │   └── ...
│   ├── Helpers/           # Helper functions
│   │   ├── security_helper.php  # 8 security functions
│   │   └── ...
│   └── Commands/          # CLI commands
│       └── SetupVisitorLogs.php
├── public/                # Web accessible files
│   ├── index.php         # Entry point
│   ├── .htaccess         # Apache config
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   ├── images/           # Static images
│   ├── uploads/          # User uploads (KTP, KK, Selfie)
│   ├── berita/           # Berita images
│   └── galeri/           # Galeri photos
├── system/               # CodeIgniter framework
├── vendor/               # Composer dependencies
├── writable/             # Cache, logs, sessions
│   ├── cache/
│   ├── logs/
│   ├── session/
│   ├── backups/          # Database backups
│   └── uploads/          # Temp uploads
├── .env                  # Environment config (JANGAN DI-COMMIT!)
├── .gitignore            # Git ignore rules
├── composer.json         # PHP dependencies
├── spark                 # CLI tool
└── README.md            # Dokumentasi ini
```

## 🎯 Fitur Detail

### 1. Sistem Registrasi Warga
- **Step 1**: Data Pribadi (NIK, Nama, Tempat/Tanggal Lahir, dll)
- **Step 2**: Data Alamat Lengkap (RT/RW, Kelurahan, Kecamatan, dll)
- **Step 3**: Upload Dokumen (KTP, KK, Selfie dengan KTP)
- **Validasi**:
  - NIK 16 digit unik
  - Email valid & unik
  - File: Max 5MB, format JPG/PNG
  - Malicious file detection
  - MIME type validation

### 2. Manajemen Surat
- **Jenis Surat**:
  - Surat Keterangan Domisili
  - Surat Keterangan Tidak Mampu (SKTM)
  - Surat Keterangan Usaha
  - Surat Pengantar SKCK
  - Dan lainnya (extensible)
- **Flow**:
  1. Warga ajukan → Status: Menunggu
  2. Admin proses → Status: Diproses
  3. Admin setujui/tolak → Status: Disetujui/Ditolak
  4. Upload surat hasil (PDF)
  5. Warga download
- **Notifikasi** otomatis setiap perubahan status

### 3. Sistem Pengumuman
- **Tipe**: Info, Peringatan, Urgent, Biasa
- **Prioritas**: Tinggi, Sedang, Rendah
- **Periode Tayang**: Tanggal mulai & selesai
- **Target**: Semua, Warga, Admin
- **Tampil di Beranda**: Toggle on/off
- **Status**: Aktif, Non-Aktif, Draft

### 4. Galeri Foto
- **Album-based** organization
- **Auto-thumbnail** generation
- **Lightbox** viewer
- **Bulk upload** support
- **Filter by album**
- **Image optimization**

### 5. Security Monitoring
- **Login Attempts** tracking
- **Security Logs** dengan JSON context
- **IP Blocking** manual & automatic
- **Rate Limiting**:
  - Login: 5 attempts / 15 minutes
  - Admin Login: 5 attempts / 15 minutes
  - Registration: 3 attempts / 60 minutes
- **Session Security**:
  - Regeneration on login
  - HttpOnly cookies
  - Secure flag (production)

### 6. Backup & Restore
- **Backup Types**:
  - Database only (.sql)
  - Files only (.zip)
  - Full backup (database + files)
- **Auto-reminder**: Jika backup > 7 hari
- **One-click restore**
- **Download & upload** backup files

### 7. Visitor Analytics
- **Auto-tracking** setiap page view
- **Statistics**:
  - Pengunjung hari ini
  - Unique visitors
  - Most visited pages
  - Daily/Monthly trends
- **Anti-duplicate**: Same URL < 60 detik tidak ditrack 2x

## 🔧 Development

### Menjalankan Commands
```bash
# List all commands
php spark list

# Setup visitor logs
php spark setup:visitor-logs

# Run migrations
php spark migrate

# Clear cache
php spark cache:clear

# Development server
php spark serve
```

### Membuat Controller Baru
```bash
php spark make:controller NamaController
```

### Membuat Model Baru
```bash
php spark make:model NamaModel
```

## 📝 Catatan Penting

### Sebelum Deployment Production

1. **Ubah Environment**
```env
CI_ENVIRONMENT = production
```

2. **Enable HTTPS**
```env
app.forceGlobalSecureRequests = true
```

3. **Ganti Encryption Key**
```bash
php spark key:generate
```

4. **Disable Debug Toolbar**
```php
// app/Config/Toolbar.php
public bool $enabled = false;
```

5. **Setup Automated Backup**
```bash
# Cron job untuk backup harian
0 2 * * * cd /path/to/project && php spark backup:database
```

6. **Review Security Headers**
```apache
# public/.htaccess already configured
# Verify and customize if needed
```

7. **Database User Privileges**
```sql
-- Gunakan user dengan privileges minimal
GRANT SELECT, INSERT, UPDATE, DELETE ON db_desa_blanakan.* TO 'desa_user'@'localhost';
```

### File Upload Limits
```ini
# php.ini
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300
```

### Email Configuration
Edit `app/Config/Email.php` untuk SMTP settings:
```php
public string $protocol = 'smtp';
public string $SMTPHost = 'smtp.gmail.com';
public int $SMTPPort = 587;
public string $SMTPUser = 'your-email@gmail.com';
public string $SMTPPass = 'your-app-password';
```

## 🐛 Troubleshooting

### Error 500 saat akses halaman
```bash
# Cek log error
tail -f writable/logs/log-YYYY-MM-DD.log

# Pastikan permissions correct
chmod -R 755 writable/
```

### Database connection error
```bash
# Cek .env
# Pastikan MySQL running
# Verify credentials
```

### File upload gagal
```bash
# Cek folder permissions
chmod -R 755 public/uploads/
chmod -R 755 writable/uploads/

# Cek php.ini upload_max_filesize
```

### CSRF token mismatch
```bash
# Clear session
rm -rf writable/session/*

# Atau via browser: Clear cookies untuk domain ini
```

## 📊 Database Schema

### Tabel Utama:
- **users** - Data warga & admin
- **surat** - Pengajuan surat
- **berita** - Konten berita
- **pengumuman** - Pengumuman desa
- **galeri** - Foto kegiatan
- **notifikasi** - Notification system
- **visitor_logs** - Analytics
- **security_logs** - Security events
- **login_attempts** - Login tracking

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

MIT License - see LICENSE file

## 👨‍💻 Developer

Dibuat dengan ❤️ untuk Desa Blanakan, Subang, Jawa Barat

---

**Last Updated**: December 2025
**Version**: 2.0.0
**CodeIgniter**: 4.6.3
**PHP**: 8.1+

🌐 **Website**: https://desablanakan.go.id (demo)
📧 **Support**: support@desablanakan.go.id
📱 **WhatsApp**: +62-812-3456-7890