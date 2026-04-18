<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white"><?= esc($title) ?></h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Informasi detail tentang server dan aplikasi</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- PHP & Application Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-2xl text-primary">code</span>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Aplikasi</h2>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">PHP Version</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['php_version']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">CodeIgniter Version</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['ci_version']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Base URL</span>
                    <span class="font-medium text-gray-800 dark:text-white text-sm truncate max-w-xs"><?= esc($system_info['base_url']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Timezone</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['app_timezone']) ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-400">Current Time</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['current_time']) ?></span>
                </div>
            </div>
        </div>

        <!-- Server Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-2xl text-primary">dns</span>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Server</h2>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Server Software</span>
                    <span class="font-medium text-gray-800 dark:text-white text-sm"><?= esc($system_info['server_software']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Server Name</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['server_name']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Server Port</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['server_port']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Protocol</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['server_protocol']) ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-400">Document Root</span>
                    <span class="font-medium text-gray-800 dark:text-white text-xs truncate max-w-xs" title="<?= esc($system_info['document_root']) ?>"><?= esc($system_info['document_root']) ?></span>
                </div>
            </div>
        </div>

        <!-- PHP Configuration -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-2xl text-primary">settings</span>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Konfigurasi PHP</h2>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Memory Limit</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['memory_limit']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Max Execution Time</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['max_execution_time']) ?>s</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Upload Max Filesize</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['upload_max_filesize']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Post Max Size</span>
                    <span class="font-medium text-gray-800 dark:text-white"><?= esc($system_info['post_max_size']) ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-400">Display Errors</span>
                    <span class="font-medium <?= $system_info['display_errors'] === 'On' ? 'text-yellow-600' : 'text-green-600' ?>"><?= esc($system_info['display_errors']) ?></span>
                </div>
            </div>
        </div>

        <!-- Database Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-2xl text-primary">storage</span>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Database</h2>
            </div>
            <?php if (isset($database_info['error'])): ?>
                <div class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-lg">
                    <p class="text-sm"><?= esc($database_info['error']) ?></p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Driver</span>
                        <span class="font-medium text-gray-800 dark:text-white"><?= esc($database_info['driver']) ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Database</span>
                        <span class="font-medium text-gray-800 dark:text-white"><?= esc($database_info['database']) ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Host</span>
                        <span class="font-medium text-gray-800 dark:text-white"><?= esc($database_info['hostname']) ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Port</span>
                        <span class="font-medium text-gray-800 dark:text-white"><?= esc($database_info['port']) ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Version</span>
                        <span class="font-medium text-gray-800 dark:text-white"><?= esc($database_info['version']) ?></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Charset</span>
                        <span class="font-medium text-gray-800 dark:text-white"><?= esc($database_info['charset']) ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Disk Usage -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-2xl text-primary">hard_drive</span>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Penggunaan Disk</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Space</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white"><?= esc($disk_info['total_space']) ?></p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Used Space</p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400"><?= esc($disk_info['used_space']) ?></p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Free Space</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400"><?= esc($disk_info['free_space']) ?></p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Disk Usage</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white"><?= esc($disk_info['usage_percentage']) ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                    <div class="bg-primary h-3 rounded-full transition-all duration-300" style="width: <?= esc($disk_info['usage_percentage']) ?>%"></div>
                </div>
            </div>
        </div>

        <!-- PHP Extensions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-2xl text-primary">extension</span>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">PHP Extensions</h2>
                <span class="ml-auto text-sm text-gray-500 dark:text-gray-400"><?= count($extensions) ?> loaded</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-96 overflow-y-auto">
                <?php foreach ($extensions as $ext): ?>
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="material-symbols-outlined text-sm text-green-600 dark:text-green-400">check_circle</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300"><?= esc($ext) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
