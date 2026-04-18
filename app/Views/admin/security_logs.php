<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Security Logs</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Log aktivitas keamanan sistem</p>
            </div>
            <div class="flex gap-3">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                        <span class="material-symbols-outlined text-xl">download</span>
                        Export
                        <span class="material-symbols-outlined text-xl">expand_more</span>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10">
                        <a href="<?= base_url('admin/security-logs/export?format=csv') ?>" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined text-sm align-middle mr-2">table_chart</span>
                            Export ke CSV
                        </a>
                        <a href="<?= base_url('admin/security-logs/export?format=json') ?>" 
                           target="_blank"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined text-sm align-middle mr-2">code</span>
                            Export ke JSON
                        </a>
                    </div>
                </div>
                <button onclick="refreshLogs()" 
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                    <span class="material-symbols-outlined text-xl">refresh</span>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Logs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Logs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?= $total_logs ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">description</span>
                </div>
            </div>
        </div>

        <!-- Critical Events -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Critical</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400"><?= $critical_logs ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-red-600 dark:text-red-400">error</span>
                </div>
            </div>
        </div>

        <!-- Warning Events -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Warning</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400"><?= $warning_logs ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-orange-600 dark:text-orange-400">warning</span>
                </div>
            </div>
        </div>

        <!-- Info Events -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Info</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400"><?= $info_logs ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">info</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Level</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Level</option>
                    <option value="critical">Critical</option>
                    <option value="warning">Warning</option>
                    <option value="info">Info</option>
                    <option value="debug">Debug</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Type</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="access_denied">Access Denied</option>
                    <option value="permission_change">Permission Change</option>
                    <option value="config_change">Config Change</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dari Tanggal</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sampai Tanggal</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
        </div>
    </div>

    <!-- Security Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Level
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Event Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (empty($security_logs)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">security</span>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data security logs</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($security_logs as $log): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">schedule</span>
                                    <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $levelColors = [
                                    'critical' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'warning' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                    'info' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'debug' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
                                ];
                                $levelIcons = [
                                    'critical' => 'error',
                                    'warning' => 'warning',
                                    'info' => 'info',
                                    'debug' => 'bug_report'
                                ];
                                $level = strtolower($log['level']);
                                ?>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium <?= $levelColors[$level] ?? $levelColors['debug'] ?>">
                                    <span class="material-symbols-outlined text-sm"><?= $levelIcons[$level] ?? 'info' ?></span>
                                    <?= ucfirst($log['level']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">category</span>
                                    <?= esc($log['event_type']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">person</span>
                                    <span class="text-sm text-gray-900 dark:text-white"><?= esc($log['username'] ?? '-') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">location_on</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-mono"><?= esc($log['ip_address']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-md">
                                <?= esc($log['description']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="viewDetails(<?= $log['id'] ?>)" 
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium"><?= $total_logs ?? 0 ?></span> hasil
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50" disabled>
                        Sebelumnya
                    </button>
                    <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshLogs() {
    location.reload();
}

function viewDetails(logId) {
    alert('Menampilkan detail log ID: ' + logId);
}
</script>
<?php $this->endSection() ?>
