<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Login Attempts</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Monitor percobaan login ke sistem</p>
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
                        <a href="<?= base_url('admin/login-attempts/export?format=csv') ?>" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined text-sm align-middle mr-2">table_chart</span>
                            Export ke CSV
                        </a>
                        <a href="<?= base_url('admin/login-attempts/export?format=json') ?>" 
                           target="_blank"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined text-sm align-middle mr-2">code</span>
                            Export ke JSON
                        </a>
                    </div>
                </div>
                <button onclick="refreshData()" 
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                    <span class="material-symbols-outlined text-xl">refresh</span>
                    Refresh
                </button>
                <button onclick="clearOldAttempts()" 
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                    <span class="material-symbols-outlined text-xl">delete_sweep</span>
                    Hapus Lama
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Attempts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Attempts</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?= $total_attempts ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">login</span>
                </div>
            </div>
        </div>

        <!-- Failed Attempts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Gagal</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400"><?= $failed_attempts ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-red-600 dark:text-red-400">block</span>
                </div>
            </div>
        </div>

        <!-- Success Attempts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Berhasil</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400"><?= $success_attempts ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">check_circle</span>
                </div>
            </div>
        </div>

        <!-- Blocked IPs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">IP Diblokir</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400"><?= $blocked_ips ?? 0 ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-orange-600 dark:text-orange-400">shield</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="success">Berhasil</option>
                    <option value="failed">Gagal</option>
                    <option value="blocked">Diblokir</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">IP Address</label>
                <input type="text" placeholder="Cari IP..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                <input type="text" placeholder="Cari username..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
        </div>
    </div>

    <!-- Login Attempts Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            User Agent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (empty($login_attempts)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">login</span>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data login attempts</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($login_attempts as $attempt): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">schedule</span>
                                    <?= date('d/m/Y H:i:s', strtotime($attempt['created_at'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">person</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= esc($attempt['username']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400">location_on</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-mono"><?= esc($attempt['ip_address']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                <?= esc($attempt['user_agent']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($attempt['status'] == 'success'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Berhasil
                                    </span>
                                <?php elseif ($attempt['status'] == 'blocked'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                        <span class="material-symbols-outlined text-sm">block</span>
                                        Diblokir
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        Gagal
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="blockIP('<?= esc($attempt['ip_address']) ?>')" 
                                        class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <span class="material-symbols-outlined text-sm">block</span>
                                    Blokir
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
                    Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium"><?= $total_attempts ?? 0 ?></span> hasil
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
function refreshData() {
    location.reload();
}

function clearOldAttempts() {
    if (confirm('Hapus semua login attempts yang lebih dari 30 hari?')) {
        // AJAX call to delete old attempts
        alert('Fitur ini akan segera diimplementasikan');
    }
}

function blockIP(ip) {
    if (confirm('Blokir IP address: ' + ip + '?')) {
        // AJAX call to block IP
        alert('IP ' + ip + ' akan diblokir');
    }
}
</script>
<?php $this->endSection() ?>
