<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">System Logs</h1>
        <button onclick="clearOldLogs()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
            <i class="material-icons text-sm mr-2">delete_sweep</i>
            Hapus Log Lama
        </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-blue-600">description</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Log Files</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($logFiles) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-green-600">storage</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Size</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?php 
                            $totalSize = array_sum(array_column($logFiles, 'size'));
                            echo $totalSize > 1048576 ? round($totalSize / 1048576, 2) . ' MB' : round($totalSize / 1024, 2) . ' KB';
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-purple-600">schedule</i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Latest Log</p>
                    <p class="text-sm font-bold text-gray-900">
                        <?= !empty($logFiles) ? date('d M Y', strtotime($logFiles[0]['modified'])) : 'N/A' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Files Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Log Files</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Filename
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Size
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Modified
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($logFiles)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada log file ditemukan
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logFiles as $log): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="material-icons text-gray-400 mr-2">description</i>
                                        <span class="text-sm font-medium text-gray-900"><?= esc($log['name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $log['size'] > 1048576 ? round($log['size'] / 1048576, 2) . ' MB' : round($log['size'] / 1024, 2) . ' KB' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= esc($log['modified']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?= base_url('admin/logs/view/' . $log['name']) ?>" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="material-icons text-sm mr-1">visibility</i>
                                        View
                                    </a>
                                    <a href="<?= base_url('writable/logs/' . $log['name']) ?>" 
                                       download
                                       class="inline-flex items-center text-green-600 hover:text-green-900">
                                        <i class="material-icons text-sm mr-1">download</i>
                                        Download
                                    </a>
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
function clearOldLogs() {
    const days = prompt('Hapus log yang lebih lama dari berapa hari?\n(Contoh: 7, 30, 60)', '30');
    
    if (days === null) return;
    
    if (!days || isNaN(days) || days < 1) {
        alert('Masukkan angka yang valid');
        return;
    }
    
    if (!confirm(`Yakin ingin menghapus log yang lebih lama dari ${days} hari?`)) {
        return;
    }
    
    fetch('<?= base_url('admin/logs/clear') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({ days: parseInt(days) })
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
        alert('Terjadi kesalahan saat menghapus log');
    });
}
</script>

<?= $this->endsection() ?>
