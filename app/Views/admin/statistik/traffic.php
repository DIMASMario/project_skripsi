<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Statistik Traffic Website</h1>
        <p class="text-gray-600 dark:text-gray-400">Analisis lalu lintas dan performa website desa</p>
    </div>

    <!-- Error Message -->
    <?php if (isset($error_message)): ?>
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-2xl">warning</span>
                <div>
                    <h3 class="font-semibold text-yellow-800 dark:text-yellow-300">Perhatian</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1"><?= esc($error_message) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Page Views -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Page Views</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?= number_format($traffic_stats['page_views']) ?></p>
                </div>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unique Visitors</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?= number_format($traffic_stats['unique_visitors']) ?></p>
                </div>
            </div>
        </div>

        <!-- Bounce Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bounce Rate</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?= $traffic_stats['bounce_rate'] ?></p>
                </div>
            </div>
        </div>

        <!-- Avg Session -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Sesi</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?= $traffic_stats['avg_session'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Traffic Trend -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Trend Traffic Mingguan</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-500 dark:text-gray-400">Line Chart Traffic akan ditampilkan di sini</p>
            </div>
        </div>

        <!-- Top Pages -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Halaman Terpopuler</h3>
            <div class="space-y-4">
                <?php if (!empty($popular_pages)): ?>
                    <?php foreach ($popular_pages as $page): ?>
                        <?php
                        // Extract page name from URL (sesuaikan dengan kolom page_visited)
                        $pageName = $page['page_visited'] ?? 'home';
                        if ($pageName === '/' || $pageName === '' || $pageName === 'home') {
                            $pageName = 'Beranda';
                        } else {
                            // Remove leading slash and format
                            $pageName = ucwords(str_replace(['/', '-', '_'], [' > ', ' ', ' '], ltrim($pageName, '/')));
                        }
                        ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= esc($pageName) ?></span>
                            <span class="text-sm text-gray-500 dark:text-gray-400"><?= number_format($page['views']) ?> views</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data traffic</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Browser & Device Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Browser Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Browser Pengguna</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Chrome</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">65%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-orange-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Firefox</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">20%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Safari</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">10%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-gray-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Lainnya</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">5%</span>
                </div>
            </div>
        </div>

        <!-- Device Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Device Pengguna</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Desktop</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">55%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Mobile</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">40%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Tablet</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">5%</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>