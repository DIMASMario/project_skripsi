<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Manajemen Pengajuan Surat</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Proses dan kelola pengajuan surat dari warga</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg flex items-gap-3">
            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
            <p class="text-green-800 dark:text-green-200"><?= session('success') ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg flex items-gap-3">
            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
            <p class="text-red-800 dark:text-red-200"><?= session('error') ?></p>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">mail</span>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['total'] ?? 0 ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-2xl">schedule</span>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['menunggu'] ?? 0 ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Menunggu</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-2xl">accessibility</span>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['diproses'] ?? 0 ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Diproses</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-cyan-600 dark:text-cyan-400 text-2xl">verified</span>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['disetujui'] ?? 0 ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Disetujui</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['selesai'] ?? 0 ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">cancel</span>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['ditolak'] ?? 0 ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Ditolak</p>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" 
                       placeholder="Cari berdasarkan nama pemohon, NIK, atau jenis surat..." 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>
            <select name="status" class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                <option value="" <?= empty($status) ? 'selected' : '' ?>>Semua Status</option>
                <option value="menunggu" <?= ($status ?? '') === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                <option value="diproses" <?= ($status ?? '') === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                <option value="disetujui" <?= ($status ?? '') === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                <option value="selesai" <?= ($status ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                <option value="ditolak" <?= ($status ?? '') === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
            </select>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Surat Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Pemohon</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Jenis Surat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Tgl Pengajuan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($surat)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <span class="material-symbols-outlined text-5xl mb-2 text-gray-300 dark:text-gray-600 block">inbox</span>
                                <p>Tidak ada pengajuan surat</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($surat as $s): ?>
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        <?= esc($s['nama_lengkap'] ?? 'N/A') ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <?= esc($s['email'] ?? '') ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <?= esc($s['no_hp'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        <?= $jenis_surat_list[$s['jenis_surat']] ?? $s['jenis_surat'] ?? 'N/A' ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        NIK: <?= esc(substr($s['nik'] ?? '', -4)) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    <?= date('d M Y H:i', strtotime($s['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $statusColors = [
                                        'menunggu' => 'yellow',
                                        'diproses' => 'blue',
                                        'selesai' => 'green',
                                        'ditolak' => 'red'
                                    ];
                                    $color = $statusColors[$s['status']] ?? 'gray';
                                    $statusLabels = [
                                        'menunggu' => 'Menunggu',
                                        'diproses' => 'Diproses',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak'
                                    ];
                                    $label = $statusLabels[$s['status']] ?? $s['status'];
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?= $color ?>-100 dark:bg-<?= $color ?>-900/30 text-<?= $color ?>-800 dark:text-<?= $color ?>-300">
                                        <?= $label ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="<?= base_url('admin-surat/detail/' . $s['id']) ?>" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded hover:bg-blue-200 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                                        <span class="material-symbols-outlined" style="font-size: 1rem;">visibility</span>
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pager): ?>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-center">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $this->endSection() ?>
