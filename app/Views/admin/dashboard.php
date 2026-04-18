<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<!-- PageHeading -->
<div class="flex flex-wrap justify-between gap-1 mb-2">
    <div class="flex min-w-72 flex-col gap-0.5">
        <h1 class="text-gray-900 dark:text-white text-2xl font-bold leading-tight tracking-tight">Selamat Datang, Admin!</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Berikut adalah ringkasan aktivitas di sistem Pelayanan Digital Desa Blanakan.</p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
    <div class="flex flex-col gap-1 rounded-xl p-4 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800">
        <p class="text-gray-600 dark:text-gray-300 text-sm font-medium leading-normal">Total Pengguna</p>
        <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold leading-tight"><?= number_format($stats['total_users'] ?? 0) ?></p>
        <p class="text-green-600 dark:text-green-500 text-xs font-medium leading-normal">Menunggu: <?= $stats['users_pending'] ?? 0 ?></p>
    </div>
    <div class="flex flex-col gap-1 rounded-xl p-4 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800">
        <p class="text-gray-600 dark:text-gray-300 text-sm font-medium leading-normal">Total Pengajuan Surat</p>
        <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold leading-tight"><?= number_format($stats['total_surat'] ?? 0) ?></p>
        <p class="text-green-600 dark:text-green-500 text-xs font-medium leading-normal">Menunggu: <?= $stats['surat_pending'] ?? 0 ?></p>
    </div>
    <div class="flex flex-col gap-1 rounded-xl p-4 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800">
        <p class="text-gray-600 dark:text-gray-300 text-sm font-medium leading-normal">Total Berita</p>
        <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold leading-tight"><?= number_format($stats['total_berita'] ?? 0) ?></p>
        <p class="text-green-600 dark:text-green-500 text-xs font-medium leading-normal">Dipublikasi: <?= $stats['berita_published'] ?? 0 ?></p>
    </div>
    <div class="flex flex-col gap-1 rounded-xl p-4 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800">
        <p class="text-gray-600 dark:text-gray-300 text-sm font-medium leading-normal">Pengunjung Hari Ini</p>
        <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold leading-tight"><?= number_format($stats['visitor_today'] ?? 0) ?></p>
        <p class="text-gray-500 dark:text-gray-400 text-xs font-medium leading-normal">Total kunjungan</p>
    </div>
    <div class="flex flex-col gap-1 rounded-xl p-4 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800">
        <p class="text-gray-600 dark:text-gray-300 text-sm font-medium leading-normal">User Online Sekarang</p>
        <p class="text-gray-900 dark:text-white tracking-tight text-2xl font-bold leading-tight"><?= number_format($stats['online_users_now'] ?? 0) ?></p>
        <p class="text-green-600 dark:text-green-500 text-xs font-medium leading-normal">Aktif 15 menit</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
    <!-- Performance Chart -->
    <div class="xl:col-span-2 bg-white dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-800">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">Grafik Performa Pelayanan Bulanan</h2>
            <select class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm focus:border-primary focus:ring-primary">
                <option>Bulanan</option>
                <option>Mingguan</option>
                <option>Tahunan</option>
            </select>
        </div>
        <div class="h-80 flex items-end justify-between px-2">
            <!-- Chart Bars -->
            <div class="flex flex-col items-center gap-2 w-1/5">
                <div class="w-full bg-primary/20 dark:bg-primary/30 rounded-t-lg" style="height: 60%">
                    <div class="w-full h-full bg-primary rounded-t-lg"></div>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Minggu 1</span>
            </div>
            <div class="flex flex-col items-center gap-2 w-1/5">
                <div class="w-full bg-primary/20 dark:bg-primary/30 rounded-t-lg" style="height: 85%">
                    <div class="w-full h-full bg-primary rounded-t-lg"></div>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Minggu 2</span>
            </div>
            <div class="flex flex-col items-center gap-2 w-1/5">
                <div class="w-full bg-primary/20 dark:bg-primary/30 rounded-t-lg" style="height: 50%">
                    <div class="w-full h-full bg-primary rounded-t-lg"></div>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Minggu 3</span>
            </div>
            <div class="flex flex-col items-center gap-2 w-1/5">
                <div class="w-full bg-primary/20 dark:bg-primary/30 rounded-t-lg" style="height: 75%">
                    <div class="w-full h-full bg-primary rounded-t-lg"></div>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Minggu 4</span>
            </div>
        </div>
    </div>
    
    <!-- Activity Log -->
    <div class="bg-white dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-2">Log Aktivitas Terbaru</h2>
        <div class="flex flex-col gap-2">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-full">
                    <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">add</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm text-gray-700 dark:text-gray-200">User <span class="font-semibold">Budi Santoso</span> mengajukan Surat Keterangan Usaha.</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">10 menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-full">
                    <span class="material-symbols-outlined text-sm text-green-600 dark:text-green-400">check</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm text-gray-700 dark:text-gray-200">Admin memverifikasi pengajuan No. <span class="font-semibold">#123</span>.</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">1 jam yang lalu</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-full">
                    <span class="material-symbols-outlined text-sm text-yellow-600 dark:text-yellow-400">person_add</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm text-gray-700 dark:text-gray-200">User baru <span class="font-semibold">Citra Lestari</span> telah terdaftar.</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">3 jam yang lalu</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-full">
                    <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">add</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-sm text-gray-700 dark:text-gray-200">User <span class="font-semibold">Agus Wijaya</span> mengajukan Surat Keterangan Domisili.</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">5 jam yang lalu</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>