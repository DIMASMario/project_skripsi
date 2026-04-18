# ❓ FAQ - UPLOAD & DOWNLOAD SURAT

**Pertanyaan yang Sering Ditanyakan**  
Sistem Layanan Surat Desa Tanjungbaru

---

## 🔷 UNTUK ADMIN

### Q1: Kenapa form upload tidak muncul?

**A:** Form upload hanya muncul jika status surat **"Diproses"**.

**Solusi:**
1. Buka detail surat
2. Pastikan statusnya **"Menunggu"** (warna kuning)
3. Klik tombol **"Proses Surat"** (warna biru)
4. Tunggu halaman refresh
5. Form upload akan muncul otomatis

**Ilustrasi:**
```
Status: Menunggu      →  Klik "Proses Surat"  →  Status: Diproses
❌ Form tidak ada        ✅ Form upload muncul
```

---

### Q2: File PDF saya tidak bisa diupload, error "File harus PDF"

**A:** Kemungkinan penyebab:
1. File bukan PDF asli (mungkin rename manual)
2. File rusak/corrupt
3. File ter-password

**Solusi:**
1. Buka file di Adobe Reader, klik **File > Save As > PDF**
2. Atau print to PDF:
   - Buka file → Ctrl+P
   - Pilih printer: **Microsoft Print to PDF**
   - Save
3. Upload file PDF yang baru

---

### Q3: Error "Ukuran file terlalu besar"

**A:** Maksimal ukuran file adalah **5 MB**.

**Cara Compress PDF:**

**Online (Gratis):**
1. Buka: https://www.ilovepdf.com/compress_pdf
2. Upload PDF
3. Klik "Compress PDF"
4. Download hasil

**Offline:**
1. Buka PDF di Adobe Acrobat (bukan Reader)
2. File > Save As Other > Reduced Size PDF
3. Pilih compatibility version
4. Save

---

### Q4: Upload berhasil tapi file tidak muncul

**A:** Kemungkinan masalah:

**Cek 1: Permission Folder**
```bash
# Di server, jalankan:
chmod 755 public/uploads/surat_selesai
```

**Cek 2: Database**
1. Buka phpMyAdmin
2. Buka tabel `surat`
3. Cari surat berdasarkan ID
4. Cek kolom `file_surat`:
   - Jika **NULL** → upload gagal
   - Jika ada nama file → upload berhasil

**Cek 3: File di Server**
```bash
# Cek folder:
ls -la public/uploads/surat_selesai/

# Harus ada file: surat_xxx_timestamp.pdf
```

---

### Q5: Bagaimana cara mengganti file yang sudah diupload?

**A:** Sistem akan **otomatis menghapus file lama** saat upload file baru.

**Langkah:**
1. Ubah status surat kembali ke **"Diproses"**:
   - Buka database
   - Edit tabel `surat`
   - Update `status` = 'diproses'
2. Refresh halaman detail surat
3. Form upload akan muncul lagi
4. Upload file baru
5. File lama akan terhapus otomatis

---

### Q6: Apakah bisa upload file Word (.docx)?

**A:** ❌ **TIDAK BISA**. Hanya file **PDF** yang diterima.

**Alasan:**
- PDF lebih aman (tidak bisa diedit)
- PDF universal (bisa dibuka di semua device)
- PDF lebih kecil ukurannya
- PDF preserve formatting

**Convert Word ke PDF:**
1. Buka file Word
2. File > Save As
3. Pilih format: **PDF**
4. Save

---

### Q7: Berapa lama proses upload?

**A:** Tergantung ukuran file dan kecepatan internet:

| Ukuran File | Koneksi 10 Mbps | Koneksi 100 Mbps |
|-------------|-----------------|------------------|
| 500 KB      | ~1 detik        | <1 detik         |
| 1 MB        | ~2 detik        | <1 detik         |
| 3 MB        | ~5-7 detik      | 1-2 detik        |
| 5 MB        | ~10-15 detik    | 2-3 detik        |

**Tips:**
- Jangan tutup browser saat upload
- Gunakan koneksi internet yang stabil
- Upload saat jam-jam sepi (pagi/malam)

---

## 🔷 UNTUK WARGA

### Q8: Kenapa saya tidak bisa download surat?

**A:** Tombol download hanya muncul jika:

**Syarat 1:** Status surat = **"Selesai"** atau **"Disetujui"**
- Cek di halaman "Riwayat Pengajuan"
- Lihat bagian status

**Syarat 2:** File PDF sudah diupload admin
- Jika status "Selesai" tapi tidak ada tombol download
- Berarti file belum diupload
- Hubungi admin desa

---

### Q9: Surat saya status "Selesai" tapi tombol download tidak ada

**A:** Kemungkinan:

**1. File belum diupload**
```
Status: Selesai ✅
File: NULL ❌
```
**Solusi:** Hubungi admin untuk upload file

**2. Browser cache**
**Solusi:** 
- Tekan `Ctrl + F5` untuk hard refresh
- Atau clear cache browser

**3. Session expired**
**Solusi:**
- Logout
- Login lagi
- Cek kembali

---

### Q10: File yang didownload tidak bisa dibuka

**A:** Kemungkinan penyebab:

**1. Download belum selesai**
- Cek ukuran file hasil download
- Jika hanya beberapa KB → download ulang
- Pastikan internet stabil

**2. PDF Reader tidak terinstall**
- Install Adobe Acrobat Reader
- Atau gunakan browser untuk buka PDF

**3. File corrupt saat upload**
- Laporkan ke admin
- Minta admin upload ulang

---

### Q11: Apakah surat yang didownload sah?

**A:** ✅ **SAH**, selama:
- Didownload dari sistem resmi desa
- Status surat "Selesai"
- Ada tanda tangan kepala desa di PDF
- Ada stempel desa

**Verifikasi Keaslian:**
1. Cek tanda tangan basah (digital signature)
2. Cek stempel desa
3. Cek nomor surat (harus unik)
4. Bisa dicek ulang ke kantor desa

---

### Q12: Berapa lama surat saya diproses?

**A:** Estimasi waktu:

| Status | Estimasi Waktu |
|--------|----------------|
| Menunggu → Diproses | 1-2 hari kerja |
| Diproses → Selesai | 1-2 hari kerja |
| **Total** | **2-4 hari kerja** |

**Percepat Proses:**
- Pastikan data lengkap dan benar
- Upload dokumen pendukung (jika ada)
- Hubungi admin desa untuk follow-up

---

### Q13: Apakah saya bisa download surat berkali-kali?

**A:** ✅ **BISA**. Tidak ada batasan download.

**Saran:**
- Simpan file di cloud (Google Drive, OneDrive)
- Backup di USB/hardisk
- Print untuk arsip fisik

---

### Q14: Apakah orang lain bisa download surat saya?

**A:** ❌ **TIDAK BISA**. Sistem memiliki keamanan:

**Proteksi:**
1. Hanya pemilik akun yang bisa download
2. Link download memerlukan login
3. Verifikasi user_id di backend
4. File tidak bisa diakses langsung via URL

**Test:**
```
User A: ID = 5, punya surat ID = 10
User B: ID = 7, coba akses surat ID = 10

Result: ❌ Error 404 / Akses Ditolak
```

---

### Q15: Bagaimana cara print surat setelah download?

**A:** Langkah:

**Windows:**
1. Buka file PDF hasil download
2. Tekan `Ctrl + P`
3. Pilih printer
4. Atur setting:
   - Paper size: A4
   - Orientation: Portrait
   - Color: Hitam-putih (hemat tinta)
5. Klik "Print"

**Android:**
1. Buka file PDF di app (Adobe, Google PDF Viewer)
2. Tap icon menu (3 titik)
3. Tap "Print"
4. Pilih printer (jika ada)
5. Atau pilih "Save as PDF" untuk simpan ulang

**iOS:**
1. Buka file PDF
2. Tap icon share
3. Pilih "Print"
4. Pilih printer

---

## 🔷 TROUBLESHOOTING UMUM

### Q16: Error "Session Expired"

**A:** Session login habis (default: 2 jam).

**Solusi:**
1. Logout
2. Login ulang
3. Ulangi aksi yang dilakukan

**Mencegah:**
- Aktifkan "Remember Me" saat login
- Jangan idle terlalu lama

---

### Q17: Error 500 Internal Server Error

**A:** Error di server.

**Solusi immediate:**
1. Screenshot error
2. Refresh halaman (Ctrl+F5)
3. Coba lagi 5 menit kemudian
4. Jika masih error, hubungi admin

**Untuk Admin:**
1. Cek log: `writable/logs/log-YYYY-MM-DD.log`
2. Cari error terakhir
3. Fix bug atau hubungi developer

---

### Q18: Website lemot/loading lama

**A:** Kemungkinan:

**1. Koneksi Internet Lambat**
- Cek speedtest: https://fast.com
- Minimal 1 Mbps untuk smooth experience

**2. Server Overload**
- Coba akses jam-jam sepi
- Atau tunggu beberapa menit

**3. Browser Issue**
- Clear cache: Ctrl+Shift+Delete
- Gunakan Chrome/Firefox terbaru

---

### Q19: Lupa password

**A:** Untuk warga:

**Cara 1: Reset via Email** (jika fitur ada)
1. Klik "Lupa Password"
2. Masukkan email
3. Cek inbox untuk link reset

**Cara 2: Hubungi Admin**
1. Datang ke kantor desa
2. Bawa KTP
3. Admin akan reset password manual

---

### Q20: Surat saya ditolak, kenapa?

 **A:** Alasan umum:

**Data Tidak Valid:**
- NIK salah/tidak terdaftar
- Alamat tidak sesuai domisili
- Nama tidak sesuai KTP

**Dokumen Tidak Lengkap:**
- Fotocopy KTP tidak jelas
- Surat keterangan RT tidak ada
- Data tidak lengkap

**Keperluan Tidak Masuk Akal:**
- Keperluan tidak jelas
- Tidak sesuai jenis surat
- Mencurigakan

**Solusi:**
1. Baca pesan penolakan dari admin
2. Perbaiki data yang salah
3. Ajukan ulang dengan data benar

---

## 📞 KONTAK BANTUAN

### Untuk Warga:

**WhatsApp Kantor Desa:**  
📱 0812-3456-7890

**Email:**  
📧 pelayanan@desatanjungbaru.id

**Datang Langsung:**  
📍 Kantor Desa Tanjungbaru  
Jl. Raya Blanakan No. 123  
Senin-Jumat: 08.00-14.00 WIB

---

### Untuk Admin/Developer:

**Technical Support:**  
📱 0856-7890-1234  
📧 developer@desatanjungbaru.id

**Check Logs:**
```bash
tail -f writable/logs/log-$(date +%Y-%m-%d).log
```

---

## 📚 DOKUMENTASI TERKAIT

- 📖 [Analisis Sistem Lengkap](ANALISIS_SISTEM_SURAT_LENGKAP.md)
- 🔧 [Solusi Implementation](SOLUSI_IMPLEMENTASI_LENGKAP.md)
- 🚀 [Quick Start Admin](QUICK_START_ADMIN.md)

---

**Terakhir Diperbarui:** 9 Maret 2026  
**Versi:** 1.0  
**Kontributor:** GitHub Copilot

---

## 💡 TIPS TAMBAHAN

### Untuk Admin:
1. **Backup database** setiap minggu
2. **Arsip file PDF** setiap bulan di hardisk eksternal
3. **Catat semua upload** di buku log
4. **Jangan upload file salah** (sulit untuk hapus)

### Untuk Warga:
1. **Screenshot** setiap langkah pengajuan
2. **Simpan file PDF** di banyak tempat (cloud + lokal)
3. **Print surat penting** untuk arsip fisik
4. **Ajukan surat H-3** dari waktu dibutuhkan

---

🎉 **Terima kasih sudah menggunakan Sistem Layanan Surat Desa!**
