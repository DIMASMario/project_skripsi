# 🚀 QUICK START GUIDE - ADMIN DESA

**Panduan Cepat untuk Admin**  
Sistem Layanan Surat Desa Tanjungbaru

---

## 📌 ALUR KERJA ADMIN

### Langkah 1: Login ke Admin Panel

1. Buka browser, akses: `http://localhost/project-skripsi/desa_tanjungbaru/admin`
2. Masukkan username & password admin
3. Klik "Login"

---

### Langkah 2: Lihat Pengajuan Surat Baru

1. Di dashboard, lihat widget **"Surat Menunggu"**
2. Atau klik menu **"Manajemen Surat"** → **"Daftar Surat"**
3. Filter berdasarkan status: **"Menunggu"**

---

### Langkah 3: Proses Pengajuan Surat

#### 3.1. Buka Detail Surat

1. Klik tombol **"Detail"** pada surat yang ingin diproses
2. Periksa data pemohon:
   - Nama Lengkap
   - NIK (harus 16 digit)
   - Alamat
   - Keperluan

#### 3.2. Verifikasi Kelengkapan

✅ **Checklist Verifikasi:**
- [ ] Data pemohon lengkap dan benar
- [ ] NIK valid dan sesuai KTP
- [ ] Alamat jelas
- [ ] Keperluan masuk akal
- [ ] Tidak ada indikasi pemalsuan

❌ **Jika Ada Masalah:**
- Klik tombol **"Tolak Surat"**
- Masukkan alasan penolakan
- Warga akan menerima notifikasi

#### 3.3. Proses Surat

1. Klik tombol **"Proses Surat"** (warna biru)
2. Sistem otomatis:
   - Mengubah status ke **"Diproses"**
   - Mengirim notifikasi ke warga
   - Menampilkan form upload file

---

### Langkah 4: Upload File Surat PDF

#### 4.1. Persiapan File

📄 **Syarat File:**
- Format: **PDF** (bukan Word, JPG, atau lainnya)
- Ukuran: **Maksimal 5 MB**
- Isi: Surat yang sudah ditandatangani kepala desa
- Nama file: Bebas (sistem akan rename otomatis)

#### 4.2. Upload File

1. Setelah klik "Proses Surat", halaman akan refresh
2. Scroll ke bawah, akan muncul **form upload**
3. Klik tombol **"Choose File"** atau **"Browse"**
4. Pilih file PDF dari komputer
5. Sistem akan validasi otomatis:
   - ✅ Hijau: File valid, bisa diupload
   - ❌ Merah: File tidak valid, pilih ulang

#### 4.3. Tambah Catatan (Opsional)

1. Isi kolom **"Catatan untuk Warga"**
2. Contoh:
   ```
   Surat dapat diambil di kantor desa mulai besok jam 08.00-14.00.
   Harap membawa KTP asli.
   ```

#### 4.4. Kirim File

1. Klik tombol **"Upload & Tandai Selesai"** (warna hijau)
2. Tunggu loading (icon berputar)
3. **Jangan tutup browser** saat upload!
4. Setelah selesai, muncul notifikasi sukses
5. Surat otomatis berstatus **"Selesai"**

---

### Langkah 5: Verifikasi Hasil

1. Buka kembali detail surat
2. Pastikan:
   - Status = **"Selesai"** ✅
   - File surat tercantum
   - Ada tombol **"Lihat File"**
3. Klik "Lihat File" untuk preview
4. Pastikan file benar dan dapat dibuka

---

## 🎯 TIPS & BEST PRACTICES

### ✅ DO (Lakukan):

1. **Verifikasi data** sebelum proses
2. **Upload file dengan nama jelas** (sistem akan rename)
3. **Berikan catatan** yang informatif untuk warga
4. **Cek preview PDF** sebelum upload
5. **Pastikan internet stabil** saat upload

### ❌ DON'T (Jangan):

1. **Jangan upload file selain PDF**
2. **Jangan upload file >5MB** (compress dulu)
3. **Jangan tutup browser** saat upload
4. **Jangan lupa isi catatan** (minimal "Surat sudah selesai")
5. **Jangan upload surat yang salah**

---

## 🔥 SHORTCUT & KEYBOARD

| Shortcut | Fungsi |
|----------|--------|
| `Ctrl + F` | Cari surat di daftar |
| `Ctrl + R` | Refresh halaman |
| `Tab` | Pindah ke field berikutnya |
| `Enter` | Submit form (hati-hati!) |

---

## 🚨 TROUBLESHOOTING CEPAT

### Problem: Form upload tidak muncul

**Penyebab:** Status surat bukan "Diproses"

**Solusi:**
1. Klik tombol **"Proses Surat"** dulu
2. Tunggu halaman refresh
3. Form upload akan muncul otomatis

---

### Problem: Error "File harus PDF"

**Penyebab:** File yang dipilih bukan PDF

**Solusi:**
1. Cek extension file (harus `.pdf`)
2. Jika Word: Save As → PDF
3. Jika gambar: Convert to PDF dulu

---

### Problem: Error "Ukuran file terlalu besar"

**Penyebab:** File >5MB

**Solusi:**
1. Compress PDF online: 
   - https://www.ilovepdf.com/compress_pdf
   - https://smallpdf.com/compress-pdf
2. Atau gunakan Adobe Acrobat "Reduce File Size"
3. Upload ulang

---

### Problem: Upload gagal terus

**Penyebab:** Koneksi internet lambat/terputus

**Solusi:**
1. Cek koneksi internet
2. Coba browser lain (Chrome recommended)
3. Clear browser cache
4. Restart browser
5. Coba lagi

---

## 📊 WORKFLOW DIAGRAM

```
┌─────────────────┐
│  Surat Masuk    │
│  (Menunggu)     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐       ❌ Ditolak
│  Cek Data       ├──────────────┐
│  Pemohon        │              │
└────────┬────────┘              │
         │ ✅ Valid              │
         ▼                       ▼
┌─────────────────┐       ┌──────────┐
│  Klik "Proses"  │       │  Selesai │
│  Status: Proses │       └──────────┘
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Upload File    │
│  PDF Surat      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Status: Selesai│ ───► Warga Download
└─────────────────┘
```

---

## 📞 BUTUH BANTUAN?

### 🐛 Lapor Bug/Error:

1. Screenshot error yang muncul
2. Catat langkah yang dilakukan
3. Cek log file di: `writable/logs/log-YYYY-MM-DD.log`
4. Hubungi developer

### 📧 Kontak Support:

- **Email:** admin@desatanjungbaru.id
- **WhatsApp:** 0812-3456-7890
- **Jam Kerja:** Senin-Jumat, 08.00-16.00 WIB

---

## 📚 DOKUMENTASI LANJUTAN

- 📖 [Dokumentasi Lengkap](ANALISIS_SISTEM_SURAT_LENGKAP.md)
- 🔧 [Solusi Implementasi](SOLUSI_IMPLEMENTASI_LENGKAP.md)
- ❓ [FAQ](FAQ_UPLOAD_SURAT.md)

---

**Terakhir Diperbarui:** 9 Maret 2026  
**Versi:** 1.0  
**Dibuat oleh:** GitHub Copilot
