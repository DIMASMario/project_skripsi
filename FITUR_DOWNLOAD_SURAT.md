# Fitur Download Surat untuk Warga

## Deskripsi Fitur
Fitur download memungkinkan warga untuk mengunduh surat yang telah selesai diproses oleh admin dalam format PDF.

## Cara Kerja

### 1. Alur Lengkap
```
Warga Ajukan Surat 
    ↓
Admin Verifikasi & Proses 
    ↓
Admin Upload File PDF & Ubah Status ke "Selesai"
    ↓
Warga Lihat Status "Selesai" di Dashboard
    ↓
Warga Download File PDF
```

### 2. Lokasi Akses Download
Warga dapat mengakses fitur download dari dua tempat:

#### A. Dari Halaman Daftar Surat (List View)
- Route: `/surat-pengajuan/` atau `/surat-pengajuan/list`
- Menampilkan tabel semua surat yang pernah diajukan
- Tombol "Download" akan muncul hanya untuk surat dengan status "Selesai"
- Tombol aksi tersedia di kolom paling kanan

#### B. Dari Halaman Detail Surat
- Route: `/surat-pengajuan/detail/{id}`  
- Menampilkan detail lengkap surat beserta status timeline
- Bagian "Surat Siap Diunduh" menampilkan tombol besar hijau jika surat siap diunduh
- Informasi timeline dan pesan admin juga ditampilkan di sisi kanan

## Persyaratan Download

Surat hanya dapat diunduh jika memenuhi kondisi berikut:

1. **Surat sudah diproses oleh Admin**
   - Status harus "Selesai" (bukan "Menunggu", "Diproses", atau "Ditolak")

2. **File PDF sudah di-upload oleh Admin**
   - Admin harus telah mengupload file PDF saat memproses surat
   - Field `file_surat` di database tidak boleh kosong

3. **File berada di server**
   - File PDF tersimpan di salah satu folder berikut:
     - `writable/uploads/surat_selesai/`
     - `public/uploads/surat_selesai/`

4. **Warga adalah pemilik surat**
   - Sistem akan memverifikasi bahwa surat milik warga yang sedang login

## Struktur File

### Routes Terdaftar
```php
// Dari app/Config/Routes.php

// Route untuk Warga (dengan autentikasi)
$routes->group('surat-pengajuan', ['filter' => 'auth:warga,admin'], function($routes) {
    $routes->get('/', 'SuratPengajuan::index');
    $routes->match(['GET', 'POST'], 'form/(:segment)', 'SuratPengajuan::form/$1');
    $routes->post('proses', 'SuratPengajuan::proses');
    $routes->get('detail/(:num)', 'SuratPengajuan::detail/$1');
    $routes->get('download/(:num)', 'SuratPengajuan::download/$1');  // ← Download route
    $routes->get('list', 'SuratPengajuan::listSurat');
});
```

### Controller & Method
```php
// Dari app/Controllers/SuratPengajuan.php

public function download($suratId)
{
    // 1. Verifikasi user (hanya dapat download milik sendiri)
    // 2. Cek status surat (harus "selesai")
    // 3. Cari file di server (multiple locations)
    // 4. Download file dengan nama deskriptif
}
```

### Model
```php
// Dari app/Models/SuratModel.php

// Field database untuk file surat
protected $allowedFields = [
    'file_surat',  // ← Nama file PDF disimpan di sini
    // ... field lainnya
];
```

### Database Table: `surat`
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | INT | ID surat |
| user_id | INT | ID warga pemilik |
| jenis_surat | VARCHAR | Tipe surat (domisili, sktm, dll) |
| file_surat | VARCHAR | **Nama file PDF yang diunduh** |
| status | VARCHAR | Status (menunggu/diproses/selesai/ditolak) |
| created_at | DATETIME | Tanggal pengajuan |
| updated_at | DATETIME | Tanggal update terakhir |

## Fitur Download dari Berbagai Panel

### 1. Dashboard Warga (`/surat-pengajuan`)
- **View**: `app/Views/admin/warga_surat_list.php`
- **Tampilan**: Tabel dengan tab filter by status
- **Tombol**: Hijau dengan ikon download, tampil jika status = "Selesai"
- **Fitur**: Statistik, filter tab, sorting

### 2. Detail Surat (`/surat-pengajuan/detail/{id}`)
- **View**: `app/Views/warga/detail_surat.php`
- **Tampilan**: Layout detail dengan sidebar timeline
- **Tombol**: Besar hijau dengan text "Download Surat (PDF)"
- **Lokasi**: Bagian "Surat Siap Diunduh" di tengah halaman
- **Info Tambahan**: Timeline status, pesan admin, durasi proses

### 3. Dashboard Admin (untuk mengupload)
- **Controller**: `AdminSurat.php`
- **Method**: `uploadFile($suratId)` - upload file PDF
- **Route**: `/admin/surat-pengajuan/uploadFile/{id}`
- **Proses**: Admin upload file → system set status ke "selesai"

## Keamanan

Sistem mengimplementasikan beberapa lapisan keamanan:

1. **Autentikasi Wajib**
   - Filter `auth:warga,admin` mencegah akses tanpa login
   - Cek session untuk memastikan user terdaftar

2. **Ownership Verification**
   - Sistem verifikasi bahwa surat milik user yang login
   - Mencegah download surat milik orang lain

3. **Status Check**
   - Hanya surat dengan status "selesai" yang dapat didownload
   - Surat dalam proses/ ditolak tidak dapat didownload

4. **File Validation**
   - Sistem cek file benar-benar ada di server
   - Jika file tidak ditemukan, tampilkan error message

5. **Logging**
   - Download activity di-log untuk audit trail
   - User ID, surat ID, timestamp tercatat di log

## Perubahan Terbaru (Changelog)

### Versi 2.0 (Update Terbaru)
- ✅ Menambahkan route grup untuk SuratPengajuan controller
- ✅ Memperbaiki download method dengan multiple path checks
- ✅ Menambahkan custom filename untuk download (format: `{id}_{jenis_surat}.pdf`)
- ✅ Meningkatkan error handling dan logging
- ✅ Dokumentasi lengkap sebagai referensi

### Versi 1.0 (Sebelumnya)
- Download method sudah ada tapi route tidak terdaftar
- View sudah menyiapkan download link

## Troubleshooting

### Masalah: Tombol Download Tidak Muncul
**Penyebab:** Status surat bukan "selesai" atau file belum di-upload
**Solusi:**
1. Admin check apakah sudah upload file PDF
2. Admin ubah status ke "selesai"
3. User refresh halaman

### Masalah: Download Error "File tidak ditemukan"
**Penyebab:** File PDF belum ada di server
**Solusi:**
1. Admin upload file PDF melalui halaman detail surat
2. Pastikan file berformat PDF
3. Check folder `public/uploads/surat_selesai/` dan `writable/uploads/surat_selesai/`

### Masalah: Akses Ditolak
**Penyebab:** User belum login atau surat bukan milik user
**Solusi:**
1. Login terlebih dahulu
2. Pastikan mengakses surat milik sendiri

### Masalah: Filename Aneh Saat Download
**Penyebab:** Nama file tidak ter-set dengan baik
**Solusi:** File otomatis di-rename dengan format `{id}_{jenis_surat}.pdf` saat download

## Testing

### Test Cases

```php
// Test 1: Download surat yang selesai
1. Login sebagai warga
2. Buka /surat-pengajuan
3. Cari surat dengan status "Selesai"
4. Klik tombol "Download"
5. ✅ File PDF berhasil didownload

// Test 2: Download dari halaman detail
1. Login sebagai warga
2. Buka /surat-pengajuan/detail/{id} (surat selesai)
3. Scroll ke section "Surat Siap Diunduh"
4. Klik "Download Surat (PDF)"
5. ✅ File PDF berhasil didownload

// Test 3: Tidak bisa download surat menunggu
1. Login sebagai warga
2. Buka /surat-pengajuan/detail/{id} (surat menunggu)
3. Scroll ke bawah
4. ✅ Tombol download TIDAK muncul

// Test 4: Tidak bisa download surat orang lain
1. Login sebagai warga A
2. Coba akses /surat-pengajuan/download/{id_surat_warga_b}
3. ✅ Redirect dengan error "Surat tidak ditemukan"
```

## Informasi Tambahan

### File Locations
- **View List**: `app/Views/admin/warga_surat_list.php`
- **View Detail**: `app/Views/warga/detail_surat.php`
- **Controller**: `app/Controllers/SuratPengajuan.php`
- **Routes**: `app/Config/Routes.php`
- **Upload Folder**: `public/uploads/surat_selesai/`

### Related Features
- Pengajuan Surat: `/surat-pengajuan/form/{jenis}`
- Riwayat Surat: `/dashboard/riwayat-surat`
- Dashboard: `/dashboard/`
- Upload oleh Admin: `/admin/surat-pengajuan/uploadFile/{id}`

## Kontribusi & Feedback
Jika ada pertanyaan atau saran, hubungi tim development.

---
**Last Updated**: April 2026
**Version**: 2.0
