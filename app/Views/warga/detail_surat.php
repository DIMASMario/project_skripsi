<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('surat-pengajuan') ?>" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pengajuan Surat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">ID: #<?= $surat['id'] ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Pengajuan</h2>
                    <?php 
                    $statusColors = [
                        'menunggu' => 'yellow',
                        'diproses' => 'blue',
                        'selesai' => 'green',
                        'ditolak' => 'red'
                    ];
                    $color = $statusColors[$surat['status']] ?? 'gray';
                    $statusLabels = [
                        'menunggu' => 'Menunggu Proses',
                        'diproses' => 'Sedang Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak'
                    ];
                    $label = $statusLabels[$surat['status']] ?? $surat['status'];
                    ?>
                    <span class="px-4 py-2 rounded-full text-base font-bold bg-<?= $color ?>-100 dark:bg-<?= $color ?>-900/30 text-<?= $color ?>-800 dark:text-<?= $color ?>-300">
                        <?= $label ?>
                    </span>
                </div>

                <!-- Status Timeline -->
                <div class="relative">
                    <div class="flex items-center gap-4">
                        <!-- Submitted -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center border-2 border-green-500 dark:border-green-400">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 text-center">Dikirim<br><?= date('d M', strtotime($surat['created_at'])) ?></p>
                        </div>

                        <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700"></div>

                        <!-- Processing -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 <?= in_array($surat['status'], ['diproses', 'selesai']) ? 'bg-green-100 dark:bg-green-900/30 border-green-500 dark:border-green-400' : 'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600' ?>">
                                <span class="material-symbols-outlined <?= in_array($surat['status'], ['diproses', 'selesai']) ? 'text-green-600 dark:text-green-400' : 'text-gray-400' ?>">check</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 text-center">Diproses</p>
                        </div>

                        <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700"></div>

                        <!-- Completed -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 <?= $surat['status'] === 'selesai' ? 'bg-green-100 dark:bg-green-900/30 border-green-500 dark:border-green-400' : 'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600' ?>">
                                <span class="material-symbols-outlined <?= $surat['status'] === 'selesai' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' ?>">check</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 text-center">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Pengajuan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Data Pengajuan Surat</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Surat</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white"><?= esc($jenis_surat_text) ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor KTP</p>
                            <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white"><?= esc($surat['nik']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor KK</p>
                            <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white"><?= $surat['no_kk'] ? esc($surat['no_kk']) : '-' ?></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p>
                        <p class="text-sm text-gray-900 dark:text-white"><?= esc($surat['alamat']) ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Keperluan/Alasan</p>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap bg-gray-50 dark:bg-gray-700 p-3 rounded">
                            <?= esc($surat['keperluan']) ?>
                        </p>
                    </div>

                    <?php if ($surat['status_perkawinan']): ?>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status Perkawinan</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white capitalize">
                                <?= str_replace('_', ' ', $surat['status_perkawinan']) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Message from Admin -->
            <?php if ($surat['pesan_admin']): ?>
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">info</span>
                        Pesan dari Admin
                    </h2>
                    <p class="text-sm text-blue-800 dark:text-blue-200 whitespace-pre-wrap">
                        <?= esc($surat['pesan_admin']) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Download Section -->
            <?php if ($surat['status'] === 'selesai' && $surat['file_surat']): ?>
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        Surat Siap Diunduh
                    </h2>
                    <p class="text-sm text-green-800 dark:text-green-200 mb-4">
                        Surat Anda sudah selesai dan siap untuk diunduh dalam format PDF.
                    </p>
                    <a href="<?= base_url('surat-pengajuan/download/' . $surat['id']) ?>" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                        <span class="material-symbols-outlined">download</span>
                        Download Surat (PDF)
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Informasi Timeline</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900 dark:text-white"><?= date('d M Y H:i', strtotime($surat['created_at'])) ?></p>
                    </div>

                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Terakhir Diupdate</p>
                        <p class="font-semibold text-gray-900 dark:text-white"><?= date('d M Y H:i', strtotime($surat['updated_at'])) ?></p>
                    </div>

                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Durasi Proses</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <?php 
                            $created = new DateTime($surat['created_at']);
                            $updated = new DateTime($surat['updated_at']);
                            $interval = $created->diff($updated);
                            echo $interval->days . ' hari ' . $interval->h . ' jam';
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 1.2rem;">help</span>
                    Pertanyaan?
                </h4>
                <p class="text-xs text-blue-800 dark:text-blue-200 mb-3">
                    Jika ada pertanyaan atau masalah dengan pengajuan surat, silakan menghubungi kantor desa kami.
                </p>
                <a href="<?= base_url('kontak') ?>" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                    Hubungi Admin →
                </a>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>
