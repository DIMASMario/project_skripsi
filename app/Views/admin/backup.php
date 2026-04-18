<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Backup & Restore</h1>
        <button id="btn-create-backup" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="material-icons text-sm mr-2">backup</i>
            Buat Backup Baru
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Database Backup -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-blue-600">storage</i>
                    </div>
                </div>
                <div class="ml-3">
                    <h2 class="text-lg font-medium text-gray-900">Database</h2>
                    <p class="text-sm text-gray-500">Backup data aplikasi</p>
                </div>
            </div>
            <div class="space-y-3">
                <button onclick="createBackup('database')" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="material-icons text-sm mr-2">backup</i>
                    Backup Database
                </button>
                <p class="text-xs text-gray-500">Termasuk semua tabel dan data</p>
            </div>
        </div>

        <!-- Files Backup -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-green-600">folder</i>
                    </div>
                </div>
                <div class="ml-3">
                    <h2 class="text-lg font-medium text-gray-900">File Upload</h2>
                    <p class="text-sm text-gray-500">Backup file media</p>
                </div>
            </div>
            <div class="space-y-3">
                <button onclick="createBackup('files')" 
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="material-icons text-sm mr-2">folder_zip</i>
                    Backup Files
                </button>
                <p class="text-xs text-gray-500">Gambar, dokumen, dan media</p>
            </div>
        </div>

        <!-- Full Backup -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="material-icons text-purple-600">archive</i>
                    </div>
                </div>
                <div class="ml-3">
                    <h2 class="text-lg font-medium text-gray-900">Full Backup</h2>
                    <p class="text-sm text-gray-500">Backup lengkap</p>
                </div>
            </div>
            <div class="space-y-3">
                <button onclick="createBackup('full')" 
                        class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="material-icons text-sm mr-2">archive</i>
                    Backup Lengkap
                </button>
                <p class="text-xs text-gray-500">Database + File + Config</p>
            </div>
        </div>
    </div>

    <!-- Backup List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                <i class="material-icons text-gray-600 mr-2">history</i>
                Riwayat Backup
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="backup-list">
                    <?php if (!empty($backups)): ?>
                        <?php foreach ($backups as $backup): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="material-icons text-gray-400 mr-2">
                                            <?php 
                                            switch($backup['type']) {
                                                case 'database': echo 'storage'; break;
                                                case 'files': echo 'folder'; break;
                                                case 'full': echo 'archive'; break;
                                                default: echo 'description';
                                            }
                                            ?>
                                        </i>
                                        <span class="text-sm font-medium text-gray-900"><?= esc($backup['filename']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php 
                                        switch($backup['type']) {
                                            case 'database': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'files': echo 'bg-green-100 text-green-800'; break;
                                            case 'full': echo 'bg-purple-100 text-purple-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(esc($backup['type'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= formatBytes($backup['size']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y H:i', $backup['created_at']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="<?= base_url('admin/backup/download/' . $backup['filename']) ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="material-icons text-sm">download</i>
                                    </a>
                                    <?php if ($backup['type'] !== 'files'): ?>
                                    <button onclick="restoreBackup('<?= esc($backup['filename']) ?>')" 
                                            class="text-green-600 hover:text-green-900 ml-2">
                                        <i class="material-icons text-sm">restore</i>
                                    </button>
                                    <?php endif; ?>
                                    <button onclick="deleteBackup('<?= esc($backup['filename']) ?>')" 
                                            class="text-red-600 hover:text-red-900 ml-2">
                                        <i class="material-icons text-sm">delete</i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="material-icons text-4xl text-gray-300 mb-2">backup</i>
                                    <p>Belum ada backup yang dibuat</p>
                                    <p class="text-sm">Buat backup pertama untuk melindungi data Anda</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upload Backup -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">
            <i class="material-icons text-gray-600 mr-2">cloud_upload</i>
            Upload Backup
        </h2>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
            <div class="text-center">
                <i class="material-icons text-4xl text-gray-400 mb-4">cloud_upload</i>
                <div class="mb-4">
                    <label for="backup-file" class="cursor-pointer">
                        <span class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Pilih File Backup
                        </span>
                        <input id="backup-file" type="file" accept=".sql,.zip" class="hidden" onchange="uploadBackup(this)">
                    </label>
                </div>
                <p class="text-sm text-gray-500">Atau drag and drop file backup di sini</p>
                <p class="text-xs text-gray-400 mt-2">Format yang didukung: .sql, .zip (Max: 100MB)</p>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progress-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <div class="flex items-center mb-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-3"></div>
            <h3 class="text-lg font-medium text-gray-900" id="progress-title">Memproses...</h3>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 0%"></div>
        </div>
        <p class="text-sm text-gray-500 mt-2" id="progress-text">Mohon tunggu...</p>
    </div>
</div>

<script>
function createBackup(type) {
    if (!confirm(`Yakin ingin membuat backup ${type}?`)) return;
    
    showProgress(`Membuat backup ${type}...`);
    
    fetch('<?= base_url('admin/backup/create') ?>', {
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
        hideProgress();
        if (data.status === 'success') {
            alert('Backup berhasil dibuat!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideProgress();
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membuat backup');
    });
}

function restoreBackup(filename) {
    if (!confirm(`Yakin ingin restore dari backup ${filename}? Data saat ini akan ditimpa!`)) return;
    
    showProgress('Melakukan restore...');
    
    fetch('<?= base_url('admin/backup/restore') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({ filename: filename })
    })
    .then(response => response.json())
    .then(data => {
        hideProgress();
        if (data.status === 'success') {
            alert('Restore berhasil!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideProgress();
        console.error('Error:', error);
        alert('Terjadi kesalahan saat melakukan restore');
    });
}

function deleteBackup(filename) {
    if (!confirm(`Yakin ingin menghapus backup ${filename}?`)) return;
    
    fetch('<?= base_url('admin/backup/delete') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({ filename: filename })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Backup berhasil dihapus!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus backup');
    });
}

function uploadBackup(input) {
    const file = input.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('backup_file', file);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    showProgress('Mengupload backup...');
    
    fetch('<?= base_url('admin/backup/upload') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideProgress();
        if (data.status === 'success') {
            alert('Backup berhasil diupload!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideProgress();
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupload backup');
    });
}

function showProgress(title) {
    document.getElementById('progress-title').textContent = title;
    document.getElementById('progress-modal').style.display = 'flex';
}

function hideProgress() {
    document.getElementById('progress-modal').style.display = 'none';
}

// Auto backup reminder
document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk tombol Buat Backup Baru
    const btnCreateBackup = document.getElementById('btn-create-backup');
    if (btnCreateBackup) {
        btnCreateBackup.addEventListener('click', function() {
            // Show modal atau dropdown untuk pilih tipe backup
            const type = prompt('Pilih tipe backup:\n1. database\n2. files\n3. full\n\nMasukkan pilihan (1/2/3):', '3');
            let backupType = 'full';
            
            if (type === '1') {
                backupType = 'database';
            } else if (type === '2') {
                backupType = 'files';
            } else if (type === '3') {
                backupType = 'full';
            } else if (type === null) {
                return; // Cancel
            }
            
            createBackup(backupType);
        });
    }
    
    // Check last backup date
    fetch('<?= base_url('admin/backup/check-last') ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.days_since_backup > 7) {
            setTimeout(() => {
                if (confirm('Backup terakhir lebih dari 7 hari yang lalu. Buat backup sekarang?')) {
                    createBackup('full');
                }
            }, 2000);
        }
    });
});
</script>
<?= $this->endsection() ?>