<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('admin-surat') ?>" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pengajuan Surat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">ID: #<?= isset($surat['id']) ? $surat['id'] : '-' ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Pemohon -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Data Pemohon</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($surat['nama_lengkap'] ?? $user['nama_lengkap'] ?? '-') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($user['email'] ?? '-') ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor HP/Telepon</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($user['no_hp'] ?? '-') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($surat['alamat'] ?? $user['alamat'] ?? '-') ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor KTP</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($surat['nik'] ?? '-') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor KK</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($user['no_kk'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pengajuan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Data Pengajuan</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Surat</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($jenis_surat_text) ?></p>
                    </div>

                    <?php if (!empty($surat['status_perkawinan'])): ?>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status Perkawinan</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                <?= isset($status_perkawinan_list) && is_array($status_perkawinan_list) ? ($status_perkawinan_list[$surat['status_perkawinan']] ?? $surat['status_perkawinan']) : $surat['status_perkawinan'] ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Keperluan/Alasan</p>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap"><?= esc($surat['keperluan'] ?? '-') ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pengajuan</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= isset($surat['created_at']) && !empty($surat['created_at']) ? date('d M Y H:i', strtotime($surat['created_at'])) : '-' ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status Saat Ini</p>
                            <?php 
                            $statusColors = [
                                'menunggu' => 'yellow',
                                'diproses' => 'blue',
                                'selesai' => 'green',
                                'ditolak' => 'red'
                            ];
                            $color = $statusColors[$surat['status']] ?? 'gray';
                            $statusLabels = [
                                'menunggu' => 'Menunggu',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'ditolak' => 'Ditolak'
                            ];
                            $label = $statusLabels[$surat['status']] ?? $surat['status'];
                            ?>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium bg-<?= $color ?>-100 dark:bg-<?= $color ?>-900/30 text-<?= $color ?>-800 dark:text-<?= $color ?>-300">
                                <?= $label ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($surat['pesan_admin']): ?>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pesan Admin</p>
                            <p class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white p-3 rounded whitespace-pre-wrap">
                                <?= esc($surat['pesan_admin']) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar - Actions -->
        <div class="space-y-6">
            <!-- Action Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tindakan</h2>
                
                <?php if ($surat['status'] !== 'selesai' && $surat['status'] !== 'ditolak'): ?>
                    <!-- Change Status Form -->
                    <form action="<?= base_url('admin-surat/ubahStatus/' . $surat['id']) ?>" method="POST" class="space-y-4">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Ubah Status
                            </label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm">
                                <option value="menunggu" <?= $surat['status'] === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option value="diproses" <?= $surat['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="selesai" <?= $surat['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Pesan (Opsional)
                            </label>
                            <textarea name="pesan_admin" placeholder="Tambahkan pesan untuk pemohon..." rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white"></textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            Simpan Perubahan
                        </button>
                    </form>

                    <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>
                <?php endif; ?>

                <!-- Upload File Form -->
                <?php if ($surat['status'] !== 'ditolak'): ?>
                    <!-- Show error if any -->
                    <?php if (session()->has('error')): ?>
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                            <p class="text-red-700 dark:text-red-200 text-sm"><strong>Error:</strong> <?= session('error') ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Show success if any -->
                    <?php if (session()->has('success')): ?>
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg">
                            <p class="text-green-700 dark:text-green-200 text-sm"><strong>Sukses:</strong> <?= session('success') ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('admin-surat/uploadFile/' . $surat['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-4" id="uploadForm" onsubmit="handleUploadSubmit(event)">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="file_surat" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Upload File Surat (PDF)
                            </label>
                            <input type="file" id="file_surat" name="file_surat" accept=".pdf" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm"
                                   onchange="document.getElementById('fileInfo').textContent = this.files[0] ? this.files[0].name + ' (' + (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB)' : ''">
                            <p class="text-xs text-gray-500 mt-1">File PDF maksimal 5MB</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1" id="fileInfo"></p>
                        </div>

                        <button type="submit" id="uploadSubmitBtn" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                            Upload File & Tandai Selesai
                        </button>
                    </form>

                    <script>
                    function handleUploadSubmit(event) {
                        const fileInput = document.getElementById('file_surat');
                        const submitBtn = document.getElementById('uploadSubmitBtn');
                        
                        console.log('Upload form submitted');
                        console.log('File selected:', fileInput.files.length > 0);
                        
                        if (fileInput.files.length === 0) {
                            event.preventDefault();
                            alert('Silakan pilih file PDF untuk diupload');
                            console.log('File validation failed - no file selected');
                            return false;
                        }
                        
                        const file = fileInput.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        
                        console.log('File details:', {
                            name: file.name,
                            size: file.size,
                            type: file.type,
                            maxSize: maxSize,
                            withinLimit: file.size <= maxSize
                        });
                        
                        if (file.size > maxSize) {
                            event.preventDefault();
                            alert('Ukuran file terlalu besar. Maksimal 5MB, file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB');
                            console.log('File validation failed - too large');
                            return false;
                        }
                        
                        if (file.type !== 'application/pdf') {
                            event.preventDefault();
                            alert('File harus berformat PDF. File Anda: ' + file.type);
                            console.log('File validation failed - wrong type');
                            return false;
                        }
                        
                        console.log('File validation passed - allowing submission');
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Uploading...';
                        
                        return true;
                    }
                    </script>

                    <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>
                <?php endif; ?>

                <!-- Reject Form -->
                <?php if ($surat['status'] !== 'ditolak'): ?>
                    <form action="<?= base_url('admin-surat/tolak/' . $surat['id']) ?>" method="POST" id="formTolak" class="space-y-4">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="alasan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Alasan Penolakan (Jika Ditolak)
                            </label>
                            <textarea id="alasan" name="alasan" placeholder="Jelaskan alasan penolakan..." rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white"></textarea>
                        </div>

                        <button type="button" onclick="if(document.getElementById('alasan').value.trim()){if(confirm('Yakin akan menolak pengajuan ini?')){document.getElementById('formTolak').submit();}}" 
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                            Tolak Pengajuan
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <p class="text-xs font-semibold text-blue-900 dark:text-blue-100 mb-2">CATATAN PENTING:</p>
                <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-1 list-disc list-inside">
                    <li>Periksa data pemohon sebelum memproses</li>
                    <li>Upload file surat setelah dokumen selesai dibuat</li>
                    <li>Warga akan menerima notifikasi via email</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>
