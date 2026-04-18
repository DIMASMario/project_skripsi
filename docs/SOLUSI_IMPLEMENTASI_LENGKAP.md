# 🔧 SOLUSI IMPLEMENTASI LENGKAP - SISTEM LAYANAN SURAT DESA

**Tanggal:** 9 Maret 2026  
**Project:** Sistem Layanan Surat Desa Tanjungbaru

---

## 📋 DAFTAR PERBAIKAN

### ✅ 1. Perbaikan UX Halaman Detail Admin
- Tambah banner instruksi untuk setiap status
- Tambah progress indicator visual
- Tambah tooltip informatif

### ✅ 2. Validasi & Feedback Frontend
- Validasi ukuran file sebelum upload (JavaScript)
- Loading spinner saat upload
- Success modal setelah upload
- Preview info file sebelum upload

### ✅ 3. Improve View Warga
- Tombol download lebih prominent
- Status tracking lebih jelas
- Informasi estimasi waktu

### ✅ 4. Error Handling
- Pesan error yang lebih informatif
- Fallback jika file tidak ada
- Validasi tipe file lebih ketat

---

## 🛠️ IMPLEMENTASI KODE

### 1️⃣ PERBAIKAN VIEW ADMIN - surat_detail.php

**File:** `app/Views/admin/surat_detail.php`

**Tambahkan setelah Status Badge (sekitar baris 43):**

```php
<!-- Instruksi untuk Admin -->
<?php if ($status === 'pending'): ?>
<div class="mb-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border-2 border-blue-300 dark:border-blue-700 p-6">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-4xl">info</span>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-bold text-blue-900 dark:text-blue-200 mb-2">
                📋 Langkah Selanjutnya
            </h3>
            <ol class="list-decimal list-inside space-y-2 text-blue-800 dark:text-blue-300">
                <li class="font-medium">Periksa data pemohon dan keperluan surat</li>
                <li class="font-medium">Klik tombol <strong>"Proses Surat"</strong> di bawah untuk memulai</li>
                <li class="font-medium">Setelah itu, form upload file PDF akan muncul</li>
                <li class="font-medium">Upload file surat yang sudah ditandatangani</li>
            </ol>
            <div class="mt-4 p-3 bg-blue-100 dark:bg-blue-800/30 rounded-lg">
                <p class="text-sm text-blue-900 dark:text-blue-200">
                    <span class="material-symbols-outlined text-base align-middle mr-1">lightbulb</span>
                    <strong>Tips:</strong> Pastikan file surat dalam format PDF dan sudah ditandatangani sebelum upload.
                </p>
            </div>
        </div>
    </div>
</div>
<?php elseif ($status === 'proses'): ?>
<div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border-2 border-yellow-300 dark:border-yellow-700 p-6">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-4xl">pending_actions</span>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-bold text-yellow-900 dark:text-yellow-200 mb-2">
                ⏳ Surat Sedang Diproses
            </h3>
            <p class="text-yellow-800 dark:text-yellow-300 mb-3">
                Surat ini sedang dalam proses penyelesaian. Silakan upload file PDF surat yang sudah ditandatangani menggunakan form di bawah.
            </p>
            <div class="flex items-center gap-2 text-sm text-yellow-700 dark:text-yellow-300">
                <span class="material-symbols-outlined text-base">schedule</span>
                <span>Status akan otomatis berubah menjadi <strong>"Selesai"</strong> setelah upload berhasil</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
```

**Perbaiki Form Upload (ganti baris 140-177):**

```php
<!-- Upload File Surat (untuk status proses) -->
<?php if ($status === 'proses'): ?>
<div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-blue-200 dark:border-blue-700 p-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl">upload_file</span>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Upload File Surat yang Sudah di-ACC
        </h2>
    </div>
    
    <form id="uploadSuratForm" 
          action="<?= base_url('admin/uploadFileSurat/' . $surat['id']) ?>" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-4">
        <?= csrf_field() ?>
        
        <!-- File Input with Preview -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                File Surat (PDF) <span class="text-red-500">*</span>
            </label>
            <input type="file" 
                   id="fileSurat"
                   name="file_surat" 
                   accept=".pdf,application/pdf"
                   required
                   onchange="validateFile(this)"
                   class="block w-full text-sm text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 p-2.5">
            
            <!-- File Info Display -->
            <div id="fileInfo" class="hidden mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-300">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    <span id="fileName"></span>
                    <span class="text-gray-500">•</span>
                    <span id="fileSize"></span>
                </div>
            </div>
            
            <!-- Error Display -->
            <div id="fileError" class="hidden mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">
                <div class="flex items-center gap-2 text-sm text-red-700 dark:text-red-300">
                    <span class="material-symbols-outlined text-base">error</span>
                    <span id="errorMessage"></span>
                </div>
            </div>
            
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                📄 Upload file PDF surat yang sudah ditandatangani. Maksimal 5MB.
            </p>
        </div>

        <!-- Catatan Admin -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Catatan untuk Warga (Opsional)
            </label>
            <textarea name="pesan_admin" 
                      rows="3"
                      placeholder="Contoh: Surat dapat diambil di kantor desa mulai besok jam 08.00-14.00"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= old('pesan_admin', $surat['pesan_admin'] ?? '') ?></textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Catatan ini akan dikirim ke warga melalui notifikasi
            </p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" 
                    id="submitBtn"
                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined text-lg mr-2" id="submitIcon">check_circle</span>
                <span id="submitText">Upload & Tandai Selesai</span>
            </button>
            <button type="button" 
                    onclick="window.location.href='<?= base_url('admin/surat') ?>'"
                    class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined text-lg mr-2">cancel</span>
                Batal
            </button>
        </div>
    </form>
</div>

<!-- JavaScript untuk Validasi & Feedback -->
<script>
function validateFile(input) {
    const file = input.files[0];
    const fileInfo = document.getElementById('fileInfo');
    const fileError = document.getElementById('fileError');
    const submitBtn = document.getElementById('submitBtn');
    
    // Reset displays
    fileInfo.classList.add('hidden');
    fileError.classList.add('hidden');
    
    if (!file) {
        submitBtn.disabled = true;
        return;
    }
    
    // Validasi tipe file
    if (file.type !== 'application/pdf') {
        showError('File harus berformat PDF! File yang dipilih: ' + file.type);
        input.value = '';
        submitBtn.disabled = true;
        return;
    }
    
    // Validasi ukuran file (5MB = 5 * 1024 * 1024 bytes)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
        showError(`Ukuran file terlalu besar (${sizeMB} MB). Maksimal 5 MB!`);
        input.value = '';
        submitBtn.disabled = true;
        return;
    }
    
    // Tampilkan info file
    showFileInfo(file);
    submitBtn.disabled = false;
}

function showFileInfo(file) {
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    fileInfo.classList.remove('hidden');
}

function showError(message) {
    const fileError = document.getElementById('fileError');
    const errorMessage = document.getElementById('errorMessage');
    
    errorMessage.textContent = message;
    fileError.classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
    return (bytes / 1024 / 1024).toFixed(2) + ' MB';
}

// Form Submit Handler dengan Loading State
document.getElementById('uploadSuratForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');
    
    // Disable button & show loading
    submitBtn.disabled = true;
    submitIcon.textContent = 'sync';
    submitIcon.classList.add('animate-spin');
    submitText.textContent = 'Mengupload...';
});
</script>
<?php endif; ?>
```

---

### 2️⃣ PERBAIKAN CONTROLLER ADMIN

**File:** `app/Controllers/Admin.php`

**Ganti function uploadFileSurat (baris 226-302) dengan:**

```php
public function uploadFileSurat($id)
{
    $surat = $this->suratModel->find($id);
    
    if (!$surat) {
        session()->setFlashdata('error', 'Surat tidak ditemukan');
        return redirect()->to('admin/surat');
    }

    // Validasi hanya bisa upload jika status = diproses
    if ($surat['status'] !== 'diproses') {
        session()->setFlashdata('error', 'Surat harus dalam status "Diproses" untuk upload file. Status saat ini: ' . $surat['status']);
        return redirect()->back();
    }

    // Validasi file upload
    $validationRule = [
        'file_surat' => [
            'label' => 'File Surat',
            'rules' => 'uploaded[file_surat]|max_size[file_surat,5120]|ext_in[file_surat,pdf]|mime_in[file_surat,application/pdf]',
            'errors' => [
                'uploaded' => 'File surat harus diupload',
                'max_size' => 'Ukuran file maksimal 5MB',
                'ext_in' => 'File harus berformat PDF',
                'mime_in' => 'File harus berformat PDF yang valid'
            ]
        ]
    ];

    if (!$this->validate($validationRule)) {
        log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
        session()->setFlashdata('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        return redirect()->back()->withInput();
    }

    $file = $this->request->getFile('file_surat');
    
    if (!$file->isValid()) {
        log_message('error', 'File not valid: ' . $file->getErrorString());
        session()->setFlashdata('error', 'File tidak valid: ' . $file->getErrorString());
        return redirect()->back();
    }

    // Hapus file lama jika ada
    if (!empty($surat['file_surat'])) {
        $oldFile = FCPATH . 'uploads/surat_selesai/' . $surat['file_surat'];
        if (file_exists($oldFile)) {
            @unlink($oldFile);
            log_message('info', 'Old file deleted: ' . $oldFile);
        }
    }

    // Generate unique filename
    $jenisSurat = str_replace(' ', '_', $surat['jenis_surat']);
    $newName = 'surat_' . $jenisSurat . '_' . $id . '_' . time() . '.pdf';
    
    // Pastikan folder ada
    $uploadPath = FCPATH . 'uploads/surat_selesai';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
        log_message('info', 'Created upload directory: ' . $uploadPath);
    }

    // Upload file
    try {
        $file->move($uploadPath, $newName);
        log_message('info', 'File uploaded successfully: ' . $newName);
    } catch (\Exception $e) {
        log_message('error', 'Failed to upload file: ' . $e->getMessage());
        session()->setFlashdata('error', 'Gagal mengupload file: ' . $e->getMessage());
        return redirect()->back();
    }

    // Update database
    $pesanAdmin = $this->request->getPost('pesan_admin');
    if (empty($pesanAdmin)) {
        $pesanAdmin = 'Surat telah selesai diproses dan siap diunduh.';
    }

    $updateData = [
        'status' => 'selesai',
        'file_surat' => $newName,
        'pesan_admin' => $pesanAdmin,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $updated = $this->suratModel->update($id, $updateData);
    
    if (!$updated) {
        log_message('error', 'Failed to update database for surat ID: ' . $id);
        // Hapus file yang sudah diupload
        @unlink($uploadPath . '/' . $newName);
        session()->setFlashdata('error', 'Gagal menyimpan data ke database');
        return redirect()->back();
    }

    // Buat notifikasi untuk warga
    $jenisSuratText = $this->suratModel->getJenisSuratText($surat['jenis_surat']);
    
    try {
        $this->notifikasiModel->insert([
            'user_id' => $surat['user_id'],
            'surat_id' => $id,
            'jenis' => 'surat',
            'judul' => 'Surat Selesai Diproses! 🎉',
            'isi' => 'Selamat! ' . $jenisSuratText . ' Anda telah selesai diproses dan siap diunduh. Silakan login ke dashboard untuk mengunduh surat Anda.',
            'status' => 'belum_dibaca',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        log_message('info', 'Notification sent to user ID: ' . $surat['user_id']);
    } catch (\Exception $e) {
        log_message('error', 'Failed to create notification: ' . $e->getMessage());
    }

    // Send email notification (if email exists)
    $user = $this->userModel->find($surat['user_id']);
    if ($user && !empty($user['email'])) {
        try {
            // TODO: Implement email sending
            log_message('info', 'Email notification should be sent to: ' . $user['email']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    session()->setFlashdata('success', '✅ File surat berhasil diupload! Surat telah diselesaikan dan notifikasi telah dikirim ke warga.');
    return redirect()->to('admin/surat');
}
```

---

### 3️⃣ PERBAIKAN VIEW WARGA - detail_surat_standalone.php

**File:** `app/Views/dashboard/detail_surat_standalone.php`

**Ganti bagian tombol download (sekitar baris 280) dengan:**

```php
<!-- Action Buttons -->
<div class="flex flex-col gap-3 mt-8 pt-6 border-t border-border-light dark:border-border-dark">
    <?php if (in_array(strtolower($surat['status']), ['selesai', 'disetujui'])): ?>
        <?php if (!empty($surat['file_surat'])): ?>
            <!-- Download Button - PRIMARY ACTION -->
            <a href="<?= base_url('dashboard/download-surat/' . $surat['id']) ?>" 
               class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white gap-3 text-lg font-bold leading-normal tracking-[0.015em] px-6 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-2xl">download</span>
                <span>Unduh Surat Sekarang (PDF)</span>
            </a>
            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">check_circle</span>
                    <div>
                        <p class="text-sm font-medium text-green-900 dark:text-green-100">Surat Siap Diunduh!</p>
                        <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                            File: <span class="font-mono"><?= esc($surat['file_surat']) ?></span>
                        </p>
                        <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                            📅 Diproses pada: <?= date('d M Y, H:i', strtotime($surat['updated_at'])) ?> WIB
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- File belum tersedia -->
            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl">schedule</span>
                    <div>
                        <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100">File Sedang Disiapkan</p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">
                            Surat Anda sudah disetujui, namun file PDF sedang dalam proses finalisasi. Silakan cek kembali dalam beberapa saat.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php elseif (strtolower($surat['status']) === 'diproses'): ?>
        <!-- Status Diproses -->
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl animate-pulse">pending_actions</span>
                <div>
                    <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Surat Sedang Diproses</p>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                        Petugas sedang memproses surat Anda. Estimasi selesai dalam 1-2 hari kerja.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (in_array(strtolower($surat['status']), ['menunggu', 'diproses'])): ?>
        <button onclick="confirmCancel()" 
                class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 bg-transparent text-red-600 dark:text-red-400 gap-2 text-base font-bold leading-normal tracking-[0.015em] px-4 hover:bg-red-500/10 transition-colors border border-red-300 dark:border-red-600">
            <span class="material-symbols-outlined">cancel</span>
            <span>Batalkan Pengajuan</span>
        </button>
    <?php endif; ?>
    
    <a href="<?= base_url('dashboard/riwayat-surat') ?>" 
       class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 bg-transparent text-text-light-primary dark:text-text-dark-primary gap-2 text-base font-medium leading-normal tracking-[0.015em] px-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors border border-border-light dark:border-border-dark">
        <span class="material-symbols-outlined">arrow_back</span>
        <span>Kembali ke Daftar</span>
    </a>
</div>
```

---

### 4️⃣ PERBAIKAN CONTROLLER DASHBOARD

**File:** `app/Controllers/Dashboard.php`

**Ganti function downloadSurat (baris 159-183) dengan:**

```php
public function downloadSurat($id)
{
    $userId = session()->get('user_id');
    $surat = $this->suratModel->find($id);
    
    // Check if surat exists
    if (!$surat) {
        log_message('error', 'Download attempt - Surat not found: ID ' . $id);
        session()->setFlashdata('error', 'Surat tidak ditemukan');
        return redirect()->to('dashboard/riwayat-surat');
    }
    
    // Check if surat belongs to user
    if ($surat['user_id'] != $userId) {
        log_message('warning', 'Unauthorized download attempt - User ' . $userId . ' tried to access surat ID ' . $id);
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Akses ditolak');
    }
    
    // Check if surat is completed
    if (!in_array(strtolower($surat['status']), ['selesai', 'disetujui'])) {
        log_message('info', 'Download attempt on incomplete surat - ID ' . $id . ', Status: ' . $surat['status']);
        session()->setFlashdata('error', 'Surat belum selesai diproses. Status saat ini: ' . ucfirst($surat['status']));
        return redirect()->back();
    }
    
    // Check if file exists in database
    if (empty($surat['file_surat'])) {
        log_message('error', 'Download attempt - File field empty for surat ID ' . $id);
        session()->setFlashdata('error', 'File surat belum tersedia. Silakan hubungi admin desa.');
        return redirect()->back();
    }
    
    $filePath = FCPATH . 'uploads/surat_selesai/' . $surat['file_surat'];
    
    // Check if file exists on server
    if (!file_exists($filePath)) {
        log_message('error', 'Download attempt - File not found on server: ' . $filePath);
        session()->setFlashdata('error', 'File tidak ditemukan di server. Silakan hubungi admin desa.');
        return redirect()->back();
    }
    
    // Log successful download
    log_message('info', 'Successful download - User ' . $userId . ' downloaded surat ID ' . $id . ' (' . $surat['file_surat'] . ')');
    
    // Download file with proper filename
    $jenisSurat = ucfirst(str_replace('_', ' ', $surat['jenis_surat']));
    $downloadName = 'Surat_' . str_replace(' ', '_', $jenisSurat) . '_' . $surat['nama_lengkap'] . '.pdf';
    
    return $this->response->download($filePath, null)->setFileName($downloadName);
}
```

---

### 5️⃣ TAMBAHAN CSS UNTUK ANIMASI

**File:** `app/Views/admin/layouts/main.php` atau di header view

**Tambahkan di dalam tag `<style>`:**

```css
/* Loading Spinner Animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Pulse Animation for Status */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth Transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
```

---

## 📝 CHECKLIST IMPLEMENTASI

### Sebelum Implementasi:
- [ ] Backup database
- [ ] Backup semua file yang akan diubah
- [ ] Pastikan server development aktif
- [ ] Buat folder `uploads/surat_selesai/` jika belum ada

### Saat Implementasi:
- [ ] Copy kode perbaikan satu per satu
- [ ] Test setiap perubahan
- [ ] Cek console browser untuk error JavaScript

### Setelah Implementasi:
- [ ] Test alur lengkap dari pengajuan sampai download
- [ ] Test validasi file (upload file non-PDF, file >5MB)
- [ ] Test akses unauthorized (user lain coba download)
- [ ] Cek log file di `writable/logs/`

---

## 🧪 TESTING SCENARIO

### Test Case 1: Upload File Berhasil
1. Admin login
2. Buka surat dengan status "menunggu"
3. Klik "Proses Surat"
4. Upload file PDF (<5MB)
5. Isi catatan admin
6. Klik "Upload & Tandai Selesai"
7. **Expected:** Success message, redirect ke list, surat status jadi "selesai"

### Test Case 2: Validasi File Gagal
1. Admin coba upload file .docx
2. **Expected:** Error message "File harus berformat PDF"
3. Admin coba upload PDF >5MB
4. **Expected:** Error message "Ukuran file maksimal 5MB"

### Test Case 3: Warga Download
1. Warga login
2. Buka riwayat surat
3. Lihat surat dengan status "Selesai"
4. Klik tombol "Unduh"
5. **Expected:** File PDF terdownload dengan nama yang benar

### Test Case 4: Security Check
1. User A mengajukan surat (ID = 10)
2. User B login
3. User B coba akses: `dashboard/download-surat/10`
4. **Expected:** Error 404 atau "Akses ditolak"

---

## 🚀 CARA DEPLOY

### 1. Gunakan Git (Recommended)
```bash
# Commit changes
git add -A
git commit -m "Improve surat upload/download UX with validation"
git push origin main

# Di server production
git pull origin main
```

### 2. Manual Upload via FTP
1. Upload file yang diubah via FileZilla/WinSCP
2. Pastikan permission folder `uploads/` = 755
3. Clear cache: hapus folder `writable/cache/*`

### 3. Database Migration (Jika Ada)
```bash
php spark migrate
```

---

## 📞 TROUBLESHOOTING

### Problem: File tidak terupload
**Solusi:**
1. Cek permission folder: `chmod 755 public/uploads/surat_selesai`
2. Cek file size limit di php.ini:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
3. Restart Apache/Nginx

### Problem: Error "File not found"
**Solusi:**
1. Cek path: `public/uploads/surat_selesai/`
2. Cek database: kolom `file_surat` ada isinya?
3. Cek log: `writable/logs/log-YYYY-MM-DD.log`

### Problem: Tombol download tidak muncul
**Solusi:**
1. Cek status surat: harus "selesai" atau "disetujui"
2. Cek kolom `file_surat`: tidak boleh NULL atau kosong
3. Cek view: conditional rendering benar?

---

## 📚 DOKUMENTASI TAMBAHAN

Lihat file lainnya:
- `QUICK_START_ADMIN.md` - Panduan cepat untuk admin
- `FAQ_TROUBLESHOOTING.md` - Pertanyaan umum
- `API_DOCUMENTATION.md` - Dokumentasi API (jika ada)

---

**Last Updated:** 9 Maret 2026  
**Maintainer:** GitHub Copilot  
**Need Help?** Check logs di `writable/logs/`
