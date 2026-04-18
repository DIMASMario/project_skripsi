<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Pengajuan Surat Saya</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola dan pantau status pengajuan surat Anda</p>
            </div>
            <a href="<?= base_url('surat-pengajuan/form') ?>" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                <span class="material-symbols-outlined">add</span>
                Ajukan Surat Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl">mail</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white"><?= $stats['total'] ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengajuan</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-3xl">schedule</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white"><?= $stats['menunggu'] ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl">pending</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white"><?= $stats['diproses'] ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Diproses</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">check_circle</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white"><?= $stats['selesai'] ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl">cancel</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white"><?= $stats['ditolak'] ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ditolak</p>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex gap-3 mb-6 border-b border-gray-200 dark:border-gray-700">
        <button class="tab-button active px-4 py-3 text-sm font-semibold text-blue-600 border-b-2 border-blue-600 dark:text-blue-400 dark:border-blue-400" data-tab="semua">
            Semua (<?= $stats['total'] ?>)
        </button>
        <button class="tab-button px-4 py-3 text-sm font-semibold text-gray-600 border-b-2 border-transparent hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" data-tab="menunggu">
            Menunggu (<?= $stats['menunggu'] ?>)
        </button>
        <button class="tab-button px-4 py-3 text-sm font-semibold text-gray-600 border-b-2 border-transparent hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" data-tab="diproses">
            Diproses (<?= $stats['diproses'] ?>)
        </button>
        <button class="tab-button px-4 py-3 text-sm font-semibold text-gray-600 border-b-2 border-transparent hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" data-tab="selesai">
            Selesai (<?= $stats['selesai'] ?>)
        </button>
        <button class="tab-button px-4 py-3 text-sm font-semibold text-gray-600 border-b-2 border-transparent hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" data-tab="ditolak">
            Ditolak (<?= $stats['ditolak'] ?>)
        </button>
    </div>

    <!-- Surat Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Jenis Surat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Tgl Pengajuan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($surat)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-5xl mb-2 text-gray-300 dark:text-gray-600">inbox</span>
                                    <p>Anda belum mengajukan surat apapun</p>
                                    <a href="<?= base_url('surat-pengajuan/form') ?>" class="mt-4 text-blue-600 dark:text-blue-400 hover:underline">
                                        Ajukan sekarang →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($surat as $s): ?>
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition surat-row" data-status="<?= $s['status'] ?>">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        <?= $jenis_surat_list[$s['jenis_surat']] ?? $s['jenis_surat'] ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        ID: <?= $s['id'] ?>
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
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('surat-pengajuan/detail/' . $s['id']) ?>" 
                                           class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded hover:bg-blue-200 dark:hover:bg-blue-900/50 transition text-xs font-semibold">
                                            Lihat Detail
                                        </a>
                                        <?php if ($s['status'] === 'selesai' && !empty($s['file_surat'])): ?>
                                            <a href="<?= base_url('surat-pengajuan/download/' . $s['id']) ?>" 
                                               class="px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded hover:bg-green-200 dark:hover:bg-green-900/50 transition text-xs font-semibold">
                                                <span class="material-symbols-outlined" style="font-size: 1rem;">download</span>
                                                Download
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Tab filtering
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        // Update active tab
        document.querySelectorAll('.tab-button').forEach(b => {
            b.classList.remove('active', 'text-blue-600', 'border-blue-600', 'dark:text-blue-400', 'dark:border-blue-400');
            b.classList.add('text-gray-600', 'border-transparent', 'dark:text-gray-400');
        });
        
        this.classList.remove('text-gray-600', 'border-transparent', 'dark:text-gray-400');
        this.classList.add('active', 'text-blue-600', 'border-blue-600', 'dark:text-blue-400', 'dark:border-blue-400');
        
        // Filter rows
        document.querySelectorAll('.surat-row').forEach(row => {
            if (tab === 'semua' || row.dataset.status === tab) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
<?php $this->endSection() ?>
