# SEQUENCE DIAGRAM - SISTEM PENGAJUAN SURAT DESA BLANAKAN

---

## SKENARIO 1: WARGA MENGAJUKAN SURAT

### Alur: Warga mengajukan surat dari awal hingga surat masuk ke sistem

**Aktor:** Warga, Sistem, Admin

---

### LANGKAH-LANGKAH:

#### **1. Warga Login ke Sistem**
- **Warga** → **Sistem**: Kirim username dan password
- **Sistem**: Validasi kredensial di database (users table)
  - ✅ **Jika berhasil**: Buat session, arahkan ke dashboard warga
  - ❌ **Jika gagal**: Tampilkan pesan error "Email/password salah"

#### **2. Warga Memilih Menu Pengajuan Surat**
- **Warga** → **Sistem**: Klik menu "Ajukan Surat"
- **Sistem**: Tampilkan halaman formulir dengan daftar jenis surat
  - Domisili
  - Tidak Mampu
  - Keterangan Usaha
  - Izin Usaha
  - dll

#### **3. Warga Memilih Jenis Surat**
- **Warga** → **Sistem**: Pilih jenis surat (contoh: Domisili)
- **Sistem**: Tampilkan form sesuai jenis surat yang dipilih
  - Nama lengkap
  - NIK
  - Alamat
  - Keperluan
  - Form khusus sesuai jenis surat

#### **4. Warga Mengisi Form**
- **Warga** → **Sistem**: Isi semua field dalam form
- **Sistem**: Menampilkan form untuk diisi

#### **5. Warga Submit Form**
- **Warga** → **Sistem**: Klik tombol "Ajukan Surat"
- **Sistem**: Proses validasi data
  - ✅ **Jika semua field lengkap dan valid**:
    - Simpan data surat ke database (tabel: surat)
      - user_id = ID warga yang login
      - jenis_surat = jenis yang dipilih
      - status = "menunggu"
      - created_at = waktu pengajuan
    - Buat notifikasi untuk admin
    - Kirim email notifikasi ke admin
    - Tampilkan pesan sukses: "Surat berhasil diajukan, menunggu proses admin"
    - Redirect ke dashboard warga
    - **Warga** ← **Sistem**: Terima konfirmasi pengajuan surat
  
  - ❌ **Jika ada field yang kosong atau tidak valid**:
    - Tampilkan pesan error pada field yang bermasalah
    - Tidak simpan data ke database
    - Warga dapat memperbaiki dan submit ulang

#### **6. Admin Menerima Notifikasi**
- **Sistem** → **Admin**: Kirim notifikasi ada pengajuan surat baru
  - Notifikasi muncul di dashboard admin
  - Email dikirim ke email admin
  - Status surat: "Menunggu Proses"

---

## SKENARIO 2: ADMIN MEMPROSES DAN UPLOAD SURAT SELESAI

### Alur: Admin menerima, memproses, dan mengirim surat selesai ke warga

**Aktor:** Admin, Sistem, Warga

---

### LANGKAH-LANGKAH:

#### **1. Admin Login ke Sistem**
- **Admin** → **Sistem**: Kirim username dan password
- **Sistem**: Validasi kredensial
  - ✅ **Jika berhasil**: Buat session admin, arahkan ke admin dashboard
  - ❌ **Jika gagal**: Tampilkan pesan error

#### **2. Admin Melihat Daftar Pengajuan Surat**
- **Admin** → **Sistem**: Akses menu "Manajemen Surat"
- **Sistem**: Tampilkan tabel surat yang masuk dengan status "Menunggu"
  - Nama pemohon
  - Jenis surat
  - Tanggal pengajuan
  - Status saat ini
  - Tombol aksi (Lihat Detail)

#### **3. Admin Membuka Detail Surat**
- **Admin** → **Sistem**: Klik "Lihat Detail" pada surat tertentu
- **Sistem**: Ambil data dari database
  - Query data dari tabel: surat + users
  - Tampilkan informasi lengkap:
    - Data pemohon
    - Detail pengajuan
    - Berkas/lampiran jika ada

#### **4. Admin Membuat/Menyiapkan Surat**
- **Admin**: Membuat atau menyiapkan file surat (PDF) sesuai data pengajuan
  - Bisa dilakukan di luar sistem (manual dengan template)
  - Atau menggunakan template yang sudah ada di sistem

#### **5. Admin Upload File Surat**
- **Admin** → **Sistem**: Upload file surat yang sudah jadi (format: PDF)
- **Sistem**: Proses upload
  - ✅ **Jika file valid (PDF, ukuran < 5MB)**:
    - Simpan file ke folder: `/writable/uploads/surat_selesai/`
    - Beri nama file unik: `surat_{id_surat}__{timestamp}.pdf`
    - Update data surat di database:
      - status = "selesai"
      - file_surat = nama file yang disimpan
      - updated_at = waktu update
    - Buat notifikasi untuk warga
    - Kirim email ke warga: "Surat Anda siap diunduh"
    - Tampilkan pesan sukses: "File surat berhasil diupload"
    - Update status di tabel surat menjadi "Selesai"

  - ❌ **Jika file tidak valid**:
    - File bukan PDF → Tampilkan error: "Format file harus PDF"
    - Ukuran terlalu besar → Tampilkan error: "Ukuran file maksimal 5MB"
    - Tidak ada file → Tampilkan error: "Pilih file terlebih dahulu"

#### **6. Warga Menerima Notifikasi**
- **Sistem** → **Warga**: Kirim notifikasi dan email
  - Notifikasi: "Surat Anda siap diunduh"
  - Email: Link untuk download surat
  - Status di dashboard warga berubah menjadi "Selesai" dengan tombol download

---

## SKENARIO 3: WARGA DOWNLOAD SURAT SELESAI

### Alur: Warga mengakses dan mengunduh surat yang sudah selesai

**Aktor:** Warga, Sistem

---

### LANGKAH-LANGKAH:

#### **1. Warga Membuka Dashboard**
- **Warga** → **Sistem**: Akses halaman dashboard
- **Sistem**: Tampilkan daftar surat yang telah diajukan
  - Tabel riwayat surat dengan kolom:
    - Jenis surat
    - Tanggal pengajuan
    - Status
    - Aksi (Lihat Detail, Download jika selesai)

#### **2. Warga Melihat Surat dengan Status "Selesai"**
- **Sistem**: Tampilkan surat dengan status "Selesai"
  - Surat akan memiliki badge hijau "Selesai"
  - Tombol "Download" tersedia

#### **3. Warga Klik Tombol Download**
- **Warga** → **Sistem**: Klik tombol "Download Surat"
- **Sistem**: Proses validasi
  - ✅ **Jika validasi berhasil**:
    - Cek kepemilikan: apakah surat milik warga yang login?
    - Cek status: apakah surat sudah "selesai"?
    - Cek file ada: apakah file surat ada di server?
    - Jika semua valid:
      - Ambil file dari folder: `/writable/uploads/surat_selesai/`
      - Set response header untuk download
      - Kirim file ke browser warga
      - Nama file yang diunduh: `{id_surat}_{jenis_surat}.pdf`
      - **Warga** ← **Sistem**: File PDF mulai diunduh

  - ❌ **Jika validasi gagal**:
    - Surat tidak milik warga → Error: "Anda tidak memiliki akses ke surat ini"
    - Status bukan "selesai" → Error: "Surat masih dalam proses"
    - File tidak ada → Error: "File surat tidak ditemukan, hubungi admin"

#### **4. File Tersimpan di Perangkat Warga**
- **Warga**: Terima file PDF di perangkatnya (Download folder)
- **Warga**: Dapat membuka dan mencetak surat sesuai kebutuhan

---

## SKENARIO 4: ADMIN MENOLAK PENGAJUAN SURAT

### Alur: Admin menolak pengajuan surat dengan alasan

**Aktor:** Admin, Sistem, Warga

---

### LANGKAH-LANGKAH:

#### **1. Admin Membuka Detail Surat**
- **Admin** → **Sistem**: Klik "Lihat Detail" pada surat yang ingin ditolak
- **Sistem**: Tampilkan informasi lengkap surat

#### **2. Admin Klik Tombol "Tolak"**
- **Admin** → **Sistem**: Klik tombol "Tolak Pengajuan"
- **Sistem**: Tampilkan form penolakan dengan field:
  - Alasan penolakan (text area)
  - Tombol "Tolak" dan "Batal"

#### **3. Admin Mengisi Alasan Penolakan**
- **Admin** → **Sistem**: Isi alasan penolakan
  - Contoh: "Data tidak lengkap", "NIK tidak sesuai", dll

#### **4. Admin Confirm Penolakan**
- **Admin** → **Sistem**: Klik tombol "Tolak"
- **Sistem**: Proses penolakan
  - ✅ **Jika berhasil**:
    - Update status surat di database menjadi "ditolak"
    - Simpan pesan penolakan ke database
    - Buat notifikasi untuk warga
    - Kirim email ke warga dengan alasan penolakan
    - Tampilkan pesan sukses: "Surat berhasil ditolak"

  - ❌ **Jika gagal**:
    - Tampilkan error dan coba lagi

#### **5. Warga Menerima Notifikasi Penolakan**
- **Sistem** → **Warga**: Kirim notifikasi dan email
  - Status surat berubah menjadi "Ditolak"
  - Warga dapat melihat alasan penolakan
  - Warga dapat mengajukan surat baru

---

## SKENARIO 5: ADMIN MENGUBAH STATUS SURAT (PROSES)

### Alur: Admin mengubah status surat menjadi "Diproses"

**Aktor:** Admin, Sistem, Warga

---

### LANGKAH-LANGKAH:

#### **1. Admin Membuka Halaman Manajemen Surat**
- **Admin** → **Sistem**: Akses "Manajemen Surat"
- **Sistem**: Tampilkan tabel surat

#### **2. Admin Filter Surat dengan Status "Menunggu"**
- **Admin** → **Sistem**: Pilih filter status "Menunggu"
- **Sistem**: Tampilkan hanya surat dengan status menunggu

#### **3. Admin Membuka Detail Surat**
- **Admin** → **Sistem**: Klik "Lihat Detail"
- **Sistem**: Tampilkan halaman detail surat

#### **4. Admin Klik "Mulai Proses"**
- **Admin** → **Sistem**: Klik tombol "Mulai Proses"
- **Sistem**: Update status
  - ✅ **Jika berhasil**:
    - Update status surat menjadi "diproses"
    - Update diproses_oleh = ID admin
    - Update diproses_at = waktu proses dimulai
    - Buat notifikasi untuk warga
    - Tampilkan pesan: "Status surat diubah menjadi Diproses"

#### **5. Warga Menerima Notifikasi**
- **Sistem** → **Warga**: Kirim notifikasi
  - Status surat di dashboard warga berubah menjadi "Diproses"
  - Warga tahu surat sedang diproses admin

---

## SKENARIO 6: STATISTIK DAN MONITORING ADMIN

### Alur: Admin melihat statistik pengajuan surat

**Aktor:** Admin, Sistem

---

### LANGKAH-LANGKAH:

#### **1. Admin Membuka Dashboard**
- **Admin** → **Sistem**: Akses halaman Dashboard Admin
- **Sistem**: Ambil data statistik dari database
  - Query COUNT dari tabel surat untuk setiap status:
    - Total Surat = COUNT(*)
    - Menunggu = COUNT WHERE status='menunggu'
    - Diproses = COUNT WHERE status='diproses'
    - Selesai = COUNT WHERE status='selesai'
    - Ditolak = COUNT WHERE status='ditolak'
    - Disetujui = COUNT WHERE status='disetujui'

#### **2. Sistem Menampilkan Statistik**
- **Sistem** → **Admin**: Tampilkan card-card statistik
  - Total Surat: 9
  - Menunggu: 0
  - Diproses: 0
  - Selesai: 5
  - Ditolak: 1
  - Disetujui: 3

#### **3. Admin Melihat Grafik Pengajuan**
- **Sistem**: Tampilkan grafik tren pengajuan surat
  - Sumbu X: Tanggal
  - Sumbu Y: Jumlah pengajuan
  - Menampilkan data terakhir 7 hari atau 30 hari

#### **4. Admin Melihat Daftar Surat Terbaru**
- **Sistem**: Tampilkan 5 surat terakhir yang masuk
  - Urutan: dari terbaru ke terlama
  - Informasi: Nama pemohon, Jenis surat, Tanggal, Status

---

## RINGKASAN ALUR UMUM

```
WARGA
├─ Login
├─ Buka Menu Ajukan Surat
├─ Pilih Jenis Surat
├─ Isi Form
├─ Submit
│  └─ [SISTEM] Validasi → Simpan DB → Email Notif
└─ Terima Notifikasi

         ↓

ADMIN
├─ Login
├─ Buka Manajemen Surat
├─ Lihat Detail Surat
├─ Pilih Aksi: Proses/Tolak/Selesai
├─ Jika Selesai: Upload File
│  └─ [SISTEM] Validasi File → Simpan → Email Notif
└─ Terima Konfirmasi

         ↓

WARGA
├─ Terima Notifikasi Selesai
├─ Buka Dashboard
├─ Lihat Surat Status "Selesai"
├─ Klik Download
│  └─ [SISTEM] Validasi Akses → Kirim File PDF
└─ Unduh dan Simpan ke Perangkat
```

---

## KONDISI KEAMANAN & VALIDASI

### Validasi di Setiap Langkah:

**Validasi Input Warga:**
- Nama tidak boleh kosong
- NIK harus 16 digit
- Alamat tidak boleh kosong
- Semua field required harus diisi

**Validasi Akses Admin:**
- Hanya role "admin" yang bisa akses manajemen surat
- Filter auth:admin di setiap route admin

**Validasi Upload File:**
- File harus format PDF
- Ukuran maksimal 5MB
- Nama file dibuat otomatis untuk menghindari konflik

**Validasi Download Warga:**
- Surat harus milik warga yang login (cek user_id)
- Status harus "selesai"
- File harus ada di server
- Return error jika validasi gagal

---

## TABEL DATABASE YANG TERLIBAT

| Tabel | Digunakan untuk |
|-------|-----------------|
| `surat` | Menyimpan data pengajuan surat |
| `users` | Menyimpan data pengguna (warga & admin) |
| `notifikasi` | Menyimpan notifikasi untuk admin/warga |
| `visitor_logs` | Tracking pengunjung website |

---

**Dibuat:** 17 April 2026  
**Sistem:** Website Desa Blanakan - Sistem Pengajuan Surat Digital
