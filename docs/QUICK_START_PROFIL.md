# 🚀 QUICK START: Update Profil Sendiri

## Step 1: Update Database (WAJIB!)

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database: `db_desa_blanakan`
3. Klik tab **SQL**
4. Copy-paste query ini:

```sql
ALTER TABLE `users` 
ADD COLUMN `agama` VARCHAR(20) DEFAULT NULL 
AFTER `jenis_kelamin`;

ALTER TABLE `users` 
ADD COLUMN `foto_profil` VARCHAR(255) DEFAULT NULL 
AFTER `foto_selfie`;
```

5. Klik **GO**

## Step 2: Test Form Profil

1. Buka browser: `http://localhost:8080`
2. Login sebagai warga (atau register baru)
3. Klik menu **"Profil Saya"**
4. Upload foto profil (JPG/PNG, max 2MB)
5. Isi semua field yang kosong:
   - Tempat Lahir
   - Tanggal Lahir
   - Jenis Kelamin
   - Agama
   - Alamat
   - No HP
   - Email
6. Klik **"Simpan Perubahan"**
7. Refresh halaman - foto dan data harus update!

## ✅ Fitur Baru

- ✅ Upload foto profil sendiri
- ✅ Edit tempat lahir, tanggal lahir
- ✅ Pilih jenis kelamin dan agama
- ✅ Update kontak (HP & email)
- ✅ Preview foto real-time

## 📷 Upload Foto

**Format:** JPG, PNG  
**Max Size:** 2MB  
**Preview:** Langsung muncul saat pilih foto

## 🔧 Troubleshooting

### Foto tidak upload?
- Cek format (harus JPG/PNG)
- Cek size (max 2MB)
- Cek folder `public/uploads/profiles/` ada

### Column agama not found?
- Jalankan query SQL di Step 1

### Form tidak muncul?
- Hard refresh: Ctrl + Shift + R
- Clear browser cache

---

**Query sudah dijalankan? ✅**  
**Foto profil berhasil upload? ✅**  
**Semua field bisa diedit? ✅**  

🎉 **Sistem sudah siap digunakan!**
