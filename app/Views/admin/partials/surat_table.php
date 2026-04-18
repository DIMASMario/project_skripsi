<table class="w-full">
    <thead>
        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <th class="py-4 px-6 text-left">
                <input type="checkbox" id="selectAllSurat" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            </th>
            <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pemohon</th>
            <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Surat</th>
            <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
            <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        <?php if (empty($surat)): ?>
            <tr>
                <td colspan="6" class="py-12 px-6 text-center">
                    <div class="flex flex-col items-center">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl mb-3">draft</span>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada pengajuan surat</p>
                    </div>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($surat as $item): ?>
                <tr class="surat-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" 
                    data-status="<?= esc($item['status'] ?? 'pending') ?>"
                    data-jenis="<?= esc($item['jenis_surat'] ?? '') ?>">
                    <td class="py-4 px-6">
                        <input type="checkbox" value="<?= $item['id'] ?? '' ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">
                                    <?= strtoupper(substr($item['nama_lengkap'] ?? 'U', 0, 1)) ?>
                                </span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white surat-nama"><?= esc($item['nama_lengkap'] ?? 'Tidak diketahui') ?></div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">NIK: <?= esc($item['nik'] ?? 'Tidak diketahui') ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white surat-jenis"><?= esc($item['jenis_surat'] ?? 'Tidak diketahui') ?></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">No: <?= esc($item['nomor_surat'] ?? 'Belum ada nomor') ?></div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <?php 
                        $status = $item['status'] ?? 'pending';
                        // Normalize status
                        if ($status === 'menunggu') $status = 'pending';
                        if ($status === 'diproses') $status = 'proses';
                        
                        $badgeClass = '';
                        $badgeClassBootstrap = ''; // For Bootstrap modal
                        $iconName = '';
                        $icon = ''; // For Bootstrap modal
                        $statusText = '';
                        
                        switch ($status) {
                            case 'selesai':
                                $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
                                $badgeClassBootstrap = 'bg-success text-white';
                                $iconName = 'check_circle';
                                $icon = 'fas fa-check-circle'; // Bootstrap FA icon
                                $statusText = 'Selesai';
                                break;
                            case 'proses':
                                $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
                                $badgeClassBootstrap = 'bg-primary text-white';
                                $iconName = 'pending';
                                $icon = 'fas fa-spinner'; // Bootstrap FA icon
                                $statusText = 'Diproses';
                                break;
                            case 'ditolak':
                                $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
                                $badgeClassBootstrap = 'bg-danger text-white';
                                $iconName = 'cancel';
                                $icon = 'fas fa-times-circle'; // Bootstrap FA icon
                                $statusText = 'Ditolak';
                                break;
                            default:
                                $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300';
                                $badgeClassBootstrap = 'bg-warning text-dark';
                                $iconName = 'schedule';
                                $icon = 'fas fa-clock'; // Bootstrap FA icon
                                $statusText = 'Menunggu';
                        }
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $badgeClass ?>">
                            <span class="material-symbols-outlined text-sm mr-1"><?= $iconName ?></span>
                            <?= $statusText ?>
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white"><?= date('d/m/Y', strtotime($item['tanggal_pengajuan'] ?? 'now')) ?></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400"><?= date('H:i', strtotime($item['tanggal_pengajuan'] ?? 'now')) ?> WIB</div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="window.location.href='<?= base_url('admin/detailSurat/' . ($item['id'] ?? 0)) ?>'" 
                                    class="p-2 text-blue-600 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition-colors" 
                                    title="Lihat Detail">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                            
                            <?php if ($status === 'pending'): ?>
                                <button type="button" onclick="prosesSurat(<?= $item['id'] ?? 0 ?>)" 
                                        class="p-2 text-blue-600 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition-colors" 
                                        title="Proses Surat">
                                    <span class="material-symbols-outlined text-xl">settings</span>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($status === 'proses'): ?>
                                <button type="button" onclick="selesaiSurat(<?= $item['id'] ?? 0 ?>)" 
                                        class="p-2 text-green-600 hover:bg-green-100 dark:text-green-400 dark:hover:bg-green-900/30 rounded-lg transition-colors" 
                                        title="Selesai">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($status === 'proses' || $status === 'selesai'): ?>
                                <button type="button" onclick="window.location.href='<?= base_url('admin-surat/detail/' . ($item['id'] ?? 0)) ?>'" 
                                        class="p-2 text-purple-600 hover:bg-purple-100 dark:text-purple-400 dark:hover:bg-purple-900/30 rounded-lg transition-colors" 
                                        title="Upload File Surat">
                                    <span class="material-symbols-outlined text-xl">upload_file</span>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($status === 'selesai'): ?>
                                <button type="button" onclick="cetakSurat(<?= $item['id'] ?? 0 ?>)" 
                                        class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 rounded-lg transition-colors" 
                                        title="Cetak Surat">
                                    <span class="material-symbols-outlined text-xl">print</span>
                                </button>
                            <?php endif; ?>
                            
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" type="button" 
                                        class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-xl">more_vert</span>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10"
                                     style="display: none;">
                                    <?php if ($status === 'proses' || $status === 'selesai'): ?>
                                        <button onclick="window.location.href='<?= base_url('admin-surat/detail/' . ($item['id'] ?? 0)) ?>'" 
                                                class="w-full text-left px-4 py-2 text-sm text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/20 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-lg">upload_file</span>
                                            Upload PDF
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($status === 'pending' || $status === 'proses'): ?>
                                        <button onclick="tolakSurat(<?= $item['id'] ?? 0 ?>)" 
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-lg">cancel</span>
                                            Tolak
                                        </button>
                                    <?php endif; ?>
                                    <!-- Edit feature temporarily disabled - surat tidak bisa diedit setelah diajukan -->
                                    <!-- 
                                    <button onclick="editSurat(<?= $item['id'] ?? 0 ?>)" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                        Edit
                                    </button>
                                    -->
                                    <button onclick="window.open('<?= base_url('admin/cetakSurat/' . ($item['id'] ?? 0)) ?>', '_blank')" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">print</span>
                                        Cetak
                                    </button>
                                    <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                    <button onclick="hapusSurat(<?= $item['id'] ?? 0 ?>)" 
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
</table>

<script>
function prosesSurat(suratId) {
    if (confirm('Yakin ingin memproses surat ini?')) {
        window.location.href = `<?= base_url('admin/prosesSurat/') ?>${suratId}`;
    }
}

function selesaiSurat(suratId) {
    if (confirm('Yakin surat ini sudah selesai?')) {
        window.location.href = `<?= base_url('admin/selesaiSurat/') ?>${suratId}`;
    }
}

function tolakSurat(suratId) {
    const alasan = prompt('Masukkan alasan penolakan:');
    if (alasan) {
        window.location.href = `<?= base_url('admin/tolakSurat/') ?>${suratId}?alasan=${encodeURIComponent(alasan)}`;
    }
}

function cetakSurat(suratId) {
    window.open(`<?= base_url('admin/cetakSurat/') ?>${suratId}`, '_blank');
}

// Edit function disabled - redirect to detail view instead
// Surat tidak dapat diedit setelah diajukan (business logic)
function editSurat(suratId) {
    // Redirect to detail page instead of non-existent edit page
    window.location.href = `<?= base_url('admin/detailSurat/') ?>${suratId}`;
}

function hapusSurat(suratId) {
    if (confirm('Yakin ingin menghapus surat ini? Tindakan ini tidak dapat dibatalkan!')) {
        window.location.href = `<?= base_url('admin/hapusSurat/') ?>${suratId}`;
    }
}

// Select All functionality
document.getElementById('selectAllSurat').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody .form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>