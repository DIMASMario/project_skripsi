<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Cache Management</h1>
        <button onclick="clearAllCache()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
            <i class="material-icons text-sm mr-2">delete_sweep</i>
            Clear All Cache
        </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Cache Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-blue-600">storage</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Files</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($cacheInfo['total_files']) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-purple-600">folder</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Size</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?php 
                            $size = $cacheInfo['total_size'];
                            echo $size > 1048576 ? round($size / 1048576, 2) . ' MB' : round($size / 1024, 2) . ' KB';
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-green-600">data_usage</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Data Cache</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $cacheInfo['data_cache']['files'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-yellow-600">lock</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Sessions</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $cacheInfo['session']['files'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Types -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Data Cache -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="flex items-center text-white">
                    <i class="material-icons mr-2">data_usage</i>
                    <h2 class="text-lg font-semibold">Data Cache</h2>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Files:</span>
                        <span class="font-semibold"><?= number_format($cacheInfo['data_cache']['files']) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Size:</span>
                        <span class="font-semibold">
                            <?php 
                                $size = $cacheInfo['data_cache']['size'];
                                echo $size > 1048576 ? round($size / 1048576, 2) . ' MB' : round($size / 1024, 2) . ' KB';
                            ?>
                        </span>
                    </div>
                    <div class="pt-3">
                        <button onclick="clearSpecificCache('data')" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="material-icons text-sm mr-1">delete</i>
                            Clear Data Cache
                        </button>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <p>Cache dari query database dan data aplikasi</p>
                </div>
            </div>
        </div>

        <!-- Debug Bar -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600">
                <div class="flex items-center text-white">
                    <i class="material-icons mr-2">bug_report</i>
                    <h2 class="text-lg font-semibold">Debug Bar</h2>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Files:</span>
                        <span class="font-semibold"><?= number_format($cacheInfo['debugbar']['files']) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Size:</span>
                        <span class="font-semibold">
                            <?php 
                                $size = $cacheInfo['debugbar']['size'];
                                echo $size > 1048576 ? round($size / 1048576, 2) . ' MB' : round($size / 1024, 2) . ' KB';
                            ?>
                        </span>
                    </div>
                    <div class="pt-3">
                        <button onclick="clearSpecificCache('debugbar')" 
                                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="material-icons text-sm mr-1">delete</i>
                            Clear Debug Cache
                        </button>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <p>File debug toolbar dan profiling</p>
                </div>
            </div>
        </div>

        <!-- Session Cache -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600">
                <div class="flex items-center text-white">
                    <i class="material-icons mr-2">lock</i>
                    <h2 class="text-lg font-semibold">Session Cache</h2>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Files:</span>
                        <span class="font-semibold"><?= number_format($cacheInfo['session']['files']) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Size:</span>
                        <span class="font-semibold">
                            <?php 
                                $size = $cacheInfo['session']['size'];
                                echo $size > 1048576 ? round($size / 1048576, 2) . ' MB' : round($size / 1024, 2) . ' KB';
                            ?>
                        </span>
                    </div>
                    <div class="pt-3">
                        <button onclick="clearSpecificCache('session')" 
                                class="w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <i class="material-icons text-sm mr-1">delete</i>
                            Clear Sessions
                        </button>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <p>⚠️ Akan logout semua user aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="material-icons text-blue-400">info</i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tentang Cache Management</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Data Cache:</strong> Menyimpan hasil query database untuk meningkatkan performa</li>
                        <li><strong>Debug Bar:</strong> File debugging untuk development (aman dihapus di production)</li>
                        <li><strong>Session Cache:</strong> Menyimpan data sesi user yang sedang aktif</li>
                    </ul>
                </div>
                <div class="mt-3 text-xs text-blue-600">
                    💡 Tip: Bersihkan cache secara berkala untuk memastikan data terbaru ditampilkan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearAllCache() {
    if (!confirm('Yakin ingin membersihkan semua cache?\n\nIni akan menghapus:\n- Data cache\n- Debug bar files\n- Session files (logout semua user)')) {
        return;
    }
    
    fetch('<?= base_url('admin/cache/clear') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membersihkan cache');
    });
}

function clearSpecificCache(type) {
    let confirmMsg = `Yakin ingin membersihkan ${type} cache?`;
    
    if (type === 'session') {
        confirmMsg += '\n\n⚠️ WARNING: Ini akan logout semua user yang sedang aktif!';
    }
    
    if (!confirm(confirmMsg)) {
        return;
    }
    
    fetch('<?= base_url('admin/cache/clear-specific') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membersihkan cache');
    });
}
</script>

<?= $this->endsection() ?>
