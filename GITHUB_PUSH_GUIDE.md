# 📋 PANDUAN PUSH KE GITHUB - DESA TANJUNGBARU

## ✅ FILE/FOLDER YANG BOLEH DI-PUSH

### 1. **Kode Aplikasi**
```
/app/                     ✅ Semua file PHP (Controllers, Models, Views)
/public/css/              ✅ Stylesheet & assets
/public/js/               ✅ JavaScript files
/public/fonts/            ✅ Font files
/public/images/           ✅ Template images (bukan user uploads)
/system/                  ✅ CodeIgniter framework (sudah included)
```

### 2. **Dokumentasi & Konfigurasi**
```
/docs/                    ✅ Dokumentasi lengkap
README.md                 ✅ Project overview
.github/                  ✅ GitHub workflows (jika ada)
class_diagram.puml        ✅ Class diagram untuk skripsi
```

### 3. **Dependencies & Build**
```
composer.json             ✅ Dependency list (lock file di-ignore)
.htaccess                 ✅ Apache configuration
```

### 4. **System Files**
```
.gitignore                ✅ Git ignore rules (SUDAH ADA)
.gitattributes            ✅ Git attributes (jika ada)
LICENSE                   ✅ License file
```

---

## ❌ FILE/FOLDER YANG TIDAK BOLEH DI-PUSH

### 1. **Environment & Credentials (CRITICAL!)**
```
.env                      ❌ Database password, API keys, secret
.env.local                ❌ Local development settings
.env.production           ❌ Production credentials
```

### 2. **Dependencies (Auto-generate)**
```
/vendor/                  ❌ Composer packages (~300MB+)
composer.lock             ❌ Lock file (biarkan composer generate)
/node_modules/            ❌ NPM packages (jika ada)
```

### 3. **Generated Files & Caches**
```
/writable/cache/*         ❌ Application cache
/writable/logs/*          ❌ Log files
/writable/session/*       ❌ Session files
/writable/debugbar/*      ❌ Debug toolbar cache
```

### 4. **User Generated Content**
```
/public/uploads/*         ❌ User uploaded files (fotografi, dokumen)
/public/berita/*          ❌ Berita images (user uploads)
/public/galeri/*          ❌ Galeri images (user uploads)
/writable/uploads/*       ❌ Temporary uploads
```

### 5. **Database Files**
```
*.sql                     ❌ Database backup files
*.sqlite                  ❌ SQLite database
backup_db_*.sql           ❌ Database backups
db_*.sql                  ❌ Database exports
```

### 6. **IDE & Editor Files**
```
.vscode/                  ❌ VS Code settings (personal)
.idea/                    ❌ IntelliJ settings (personal)
*.sublime-project         ❌ Sublime Text project
*.code-workspace          ❌ Workspace config
```

### 7. **System & Temporary Files**
```
.DS_Store                 ❌ macOS system files
Thumbs.db                 ❌ Windows thumbnail cache
*.log                     ❌ Log files
*.tmp                     ❌ Temporary files
~*                        ❌ Backup files
CLEANUP_SUMMARY.md        ❌ Temporary project files
LAPORAN_CLEANUP_PROJECT.md ❌ Temporary reports
```

---

## 🚀 LANGKAH-LANGKAH PUSH KE GITHUB

### 1. **Initialize Repository (Jika Baru)**
```bash
cd c:\wamp64\www\project-skripsi\desa_tanjungbaru
git init
```

### 2. **Cek Status (Lihat file yang akan di-push)**
```bash
git status
```

### 3. **Add Files (Stage untuk commit)**
```bash
# Add semua file yang tidak di-ignore
git add .

# Atau add file spesifik
git add app/ public/css/ public/js/ docs/ README.md composer.json
```

### 4. **Commit**
```bash
git commit -m "Initial commit: Sistem Desa Tanjungbaru v1.0"
```

### 5. **Setup Remote Repository**
```bash
# Ganti USERNAME dan REPO_NAME
git remote add origin https://github.com/USERNAME/desa_tanjungbaru.git
```

### 6. **Push ke GitHub**
```bash
git branch -M main
git push -u origin main
```

---

## 📋 CHECKLIST SEBELUM PUSH

- [ ] `.env` SUDAH di-ignore (di `.gitignore`)
- [ ] `/vendor/` SUDAH di-ignore
- [ ] `/writable/` folder kosong atau hanya ada `.gitkeep`
- [ ] `/public/uploads/` kosong atau hanya ada `.gitkeep`
- [ ] `composer.json` ada di root
- [ ] `README.md` sudah dibuat
- [ ] `.gitignore` sudah sesuai
- [ ] Tidak ada file database `.sql` untuk di-push
- [ ] Tidak ada file `.env` yang akan ter-push

---

## 📁 STRUKTUR FOLDER YANG BOLEH DI-PUSH

```
desa_tanjungbaru/
├── app/                    ✅ Aplikasi kode
├── public/                 ✅ Assets (kecuali uploads)
│   ├── css/
│   ├── js/
│   ├── fonts/
│   ├── images/             (template images saja)
│   └── uploads/            ❌ (di-ignore)
├── system/                 ✅ Framework files
├── docs/                   ✅ Dokumentasi
├── scripts/                ✅ Helper scripts
├── writable/               ⚠️  Hanya .gitkeep files
├── .gitignore              ✅
├── README.md               ✅
├── composer.json           ✅
├── LICENSE                 ✅
└── class_diagram.puml      ✅
```

---

## 🔒 SETUP PRODUCTION (.env)

Setelah clone dari GitHub di server production:

```bash
# 1. Copy contoh file
cp .env.example .env

# 2. Edit .env dengan credentials production
nano .env
# Update:
# - DATABASE_HOST
# - DATABASE_USER
# - DATABASE_NAME
# - DATABASE_PASSWORD
# - BASE_URL
# - APP_ENV=production

# 3. Install dependencies
composer install --no-dev

# 4. Set permissions
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

---

## 💡 BEST PRACTICES

1. **Jangan commit kredensial apapun** - gunakan `.env` untuk secrets
2. **Jangan commit file besar** - exclude vendor, uploads, logs
3. **Buat `.env.example`** - template untuk developer lain
4. **Update README.md** - dengan instruksi setup & installation
5. **Gunakan `.gitkeep`** - untuk preserve folder structure
6. **Commit regular** - small, atomic commits dengan pesan jelas

---

## 📧 CONTOH .env.example

Buat file `c:\wamp64\www\project-skripsi\desa_tanjungbaru\.env.example`:

```env
# CodeIgniter Environment
CI_ENVIRONMENT = development

# Database Configuration
database.default.hostname = localhost
database.default.database = db_desa_blanakan
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi

# App Configuration
app.baseURL = http://localhost:8080/project-skripsi/desa_tanjungbaru/public/
app.indexPage = ''

# CSRF & Security
security.tokenName = csrf_token_name
security.headerName = X-CSRF-TOKEN
security.cookieName = csrf_cookie_name

# Email Configuration (jika ada)
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = your-email@gmail.com
email.SMTPPass = your-app-password
```

---

## ⚠️ JIKA SUDAH COMMIT FILE SENSITIF (Minta Bantuan!)

Jika sudah commit `.env` atau database password ke GitHub:

```bash
# URGENT: Remove from history
git rm --cached .env
git commit -m "Remove sensitive .env file"

# Force push (HATI-HATI!)
git push --force-with-lease origin main

# Regenerate semua credentials/passwords!
```

---

**READY TO PUSH? 🚀 Tunggu konfirmasi & bantuan lebih lanjut!**
