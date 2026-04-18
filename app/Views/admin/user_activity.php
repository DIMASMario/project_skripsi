<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $breadcrumb ?></p>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Log Aktivitas Pengguna</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Riwayat aktivitas dan akses pengguna ke sistem</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                        <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Waktu</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">User</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Aktivitas</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">IP Address</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-500 dark:text-gray-400">Browser</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $activity): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-3 px-6 text-sm text-gray-900 dark:text-white">
                                    <?= date('d/m/Y H:i:s', strtotime($activity['created_at'])) ?>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <?= esc($activity['user_name'] ?? 'Guest') ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <?= esc($activity['user_email'] ?? '-') ?>
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <?= esc($activity['action'] ?? 'Page Visit') ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <?= esc($activity['page_url']) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-sm text-gray-500 dark:text-gray-400">
                                    <?= esc($activity['ip_address']) ?>
                                </td>
                                <td class="py-3 px-6 text-sm text-gray-500 dark:text-gray-400">
                                    <?= esc($activity['user_agent']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 block">history</span>
                                    <p>Belum ada log aktivitas</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>