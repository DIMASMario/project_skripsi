<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Back Button & Header -->
    <div class="mb-6">
        <a href="<?= base_url('admin/surat') ?>" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-4">
            <span class="material-symbols-outlined text-lg mr-1">arrow_back</span>
            Kembali ke Manajemen Surat
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pengajuan Surat</h1>
    </div>

    <?php 
    // Status mapping
    $status = $surat['status'] ?? 'menunggu';
    if ($status === 'menunggu') $status = 'pending';
    if ($status === 'diproses') $status = 'proses';
    
    $badgeClass = '';
    $iconName = '';
    $statusText = '';
    
    switch ($status) {
        case 'selesai':
        case 'disetujui':
            $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
            $iconName = 'check_circle';
            $statusText = 'Selesai';
            break;
        case 'proses':
            $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
            $iconName = 'pending';
            $statusText = 'Diproses';
            break;
        case 'ditolak':
            $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
            $iconName = 'cancel';
            $statusText = 'Ditolak';
            break;
        default:
            $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300';
            $iconName = 'schedule';
            $statusText = 'Menunggu';
    }
    ?>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium <?= $badgeClass ?>">
            <span class="material-symbols-outlined text-lg mr-2"><?= $iconName ?></span>
            Status: <?= $statusText ?>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Pemohon -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mr-2">person</span>
                Data Pemohon
            </h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Lengkap</label>
                    <p class="text-base text-gray-900 dark:text-white"><?= esc($surat['nama_lengkap'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">NIK</label>
                    <p class="text-base text-gray-900 dark:text-white"><?= esc($surat['nik'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat</label>
                    <p class="text-base text-gray-900 dark:text-white"><?= esc($surat['alamat'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <!-- Data Surat -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 mr-2">description</span>
                Data Surat
            </h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jenis Surat</label>
                    <p class="text-base text-gray-900 dark:text-white"><?= esc(ucwords(str_replace('_', ' ', $surat['jenis_surat'] ?? '-'))) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor Surat</label>
                    <p class="text-base text-gray-900 dark:text-white"><?= esc($surat['nomor_surat'] ?? 'Belum ada nomor') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Pengajuan</label>
                    <p class="text-base text-gray-900 dark:text-white">
                        <?= date('d F Y, H:i', strtotime($surat['created_at'] ?? 'now')) ?> WIB
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Keperluan -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 mr-2">info</span>
            Keperluan
        </h2>
        <p class="text-base text-gray-700 dark:text-gray-300 whitespace-pre-line"><?= esc($surat['keperluan'] ?? '-') ?></p>
    </div>

    <!-- Pesan Admin (jika ada) -->
    <?php if (!empty($surat['pesan_admin'])): ?>
    <div class="mt-6 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 p-6">
        <h2 class="text-lg font-semibold text-amber-900 dark:text-amber-300 mb-4 flex items-center">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 mr-2">chat</span>
            Pesan dari Admin
        </h2>
        <p class="text-base text-amber-800 dark:text-amber-200 whitespace-pre-line"><?= esc($surat['pesan_admin']) ?></p>
    </div>
    <?php endif; ?>

    <!-- Upload File Surat (untuk status proses) -->
    <?php if ($status === 'proses'): ?>
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mr-2">upload_file</span>
            Upload File Surat yang Sudah di-ACC
        </h2>
        <form action="<?= base_url('admin/uploadFileSurat/' . $surat['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    File Surat (PDF) <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       name="file_surat" 
                       accept=".pdf"
                       required
                       class="block w-full text-sm text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 p-2.5">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Upload file PDF surat yang sudah ditandatangani. Maksimal 5MB.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Catatan Admin (Opsional)
                </label>
                <textarea name="pesan_admin" 
                          rows="3"
                          placeholder="Tambahkan catatan untuk warga (misalnya: waktu pengambilan, persyaratan tambahan, dll)"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= old('pesan_admin', $surat['pesan_admin'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <span class="material-symbols-outlined text-lg mr-2">check_circle</span>
                    Upload & Tandai Selesai
                </button>
                <button type="button" 
                        onclick="window.location.href='<?= base_url('admin/surat') ?>'"
                        class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Informasi File Surat (jika sudah ada) -->
    <?php if (!empty($surat['file_surat'])): ?>
    <div class="mt-6 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-6">
        <h2 class="text-lg font-semibold text-green-900 dark:text-green-300 mb-4 flex items-center">
            <span class="material-symbols-outlined text-green-600 dark:text-green-400 mr-2">description</span>
            File Surat Tersedia
        </h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-green-700 dark:text-green-300 mb-1">Nama File:</p>
                <p class="text-base font-medium text-green-900 dark:text-green-200"><?= esc($surat['file_surat']) ?></p>
            </div>
            <a href="<?= base_url('uploads/surat_selesai/' . $surat['file_surat']) ?>" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined text-lg mr-2">visibility</span>
                Lihat File
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="mt-8 flex gap-3">
        <?php if ($status === 'pending'): ?>
            <button onclick="prosesSurat(<?= $surat['id'] ?>)" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined text-lg mr-2">settings</span>
                Proses Surat
            </button>
            <button onclick="tolakSurat(<?= $surat['id'] ?>)" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined text-lg mr-2">cancel</span>
                Tolak Surat
            </button>
        <?php elseif ($status === 'proses'): ?>
            <!-- Upload form is shown above, no button needed here -->
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <span class="material-symbols-outlined text-base align-middle mr-1">info</span>
                    Silakan upload file surat yang sudah di-ACC menggunakan form di atas.
                </p>
            </div>
        <?php elseif (in_array($status, ['selesai', 'disetujui'])): ?>
            <button onclick="cetakSurat(<?= $surat['id'] ?>)" 
                    class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg font-medium transition-colors">
                <span class="material-symbols-outlined text-lg mr-2">print</span>
                Cetak Surat
            </button>
        <?php endif; ?>
        
        <a href="<?= base_url('admin/surat') ?>" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
            <span class="material-symbols-outlined text-lg mr-2">arrow_back</span>
            Kembali
        </a>
    </div>
</div>

<script>
function prosesSurat(id) {
    if (confirm('Yakin ingin memproses surat ini?')) {
        window.location.href = `<?= base_url('admin/surat/proses/') ?>${id}`;
    }
}

function selesaiSurat(id) {
    if (confirm('Yakin surat ini sudah selesai?')) {
        window.location.href = `<?= base_url('admin/selesaiSurat/') ?>${id}`;
    }
}

function tolakSurat(id) {
    const alasan = prompt('Masukkan alasan penolakan:');
    if (alasan) {
        window.location.href = `<?= base_url('admin/tolakSurat/') ?>${id}?alasan=${encodeURIComponent(alasan)}`;
    }
}

function cetakSurat(id) {
    window.open(`<?= base_url('admin/cetakSurat/') ?>${id}`, '_blank');
}
</script>

<?php $this->endSection() ?>
