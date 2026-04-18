<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Arsip Surat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar surat yang telah selesai atau ditolak</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                    <span class="material-symbols-outlined text-base mr-1">folder</span>
                    Total: <?= count($arsip_surat ?? []) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">check_circle</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                <?= count(array_filter($arsip_surat ?? [], fn($s) => ($s['status'] ?? '') === 'selesai')) ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Surat Selesai</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl">cancel</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                <?= count(array_filter($arsip_surat ?? [], fn($s) => ($s['status'] ?? '') === 'ditolak')) ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Surat Ditolak</p>
        </div>
    </div>

    <!-- Arsip Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">folder_open</span>
                    Daftar Arsip Surat
                </h2>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                        <input type="text" id="searchArsip" placeholder="Cari surat..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                    </div>
                    <select id="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">Semua Status</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pemohon</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Surat</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="arsipTableBody">
                    <?php if (empty($arsip_surat)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-4xl mb-2">folder_open</span>
                                    <p class="text-sm">Belum ada arsip surat</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($arsip_surat as $index => $surat): ?>
                            <tr class="arsip-row hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <?= $index + 1 ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center font-semibold">
                                                <?= strtoupper(substr($surat['nama_lengkap'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white surat-pemohon">
                                                <?= esc($surat['nama_lengkap'] ?? 'Tidak diketahui') ?>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                NIK: <?= esc($surat['nik'] ?? '-') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white surat-jenis">
                                        <?php
                                        $jenisSurat = [
                                            'domisili' => 'Surat Keterangan Domisili',
                                            'usaha' => 'Surat Keterangan Usaha',
                                            'skck' => 'Surat Pengantar SKCK',
                                            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
                                            'kehilangan' => 'Surat Keterangan Kehilangan',
                                            'kelahiran' => 'Surat Keterangan Kelahiran',
                                            'kematian' => 'Surat Keterangan Kematian',
                                        ];
                                        echo $jenisSurat[$surat['jenis_surat'] ?? ''] ?? 'Surat Keterangan';
                                        ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div><?= date('d/m/Y', strtotime($surat['created_at'] ?? 'now')) ?></div>
                                    <div class="text-xs"><?= date('H:i', strtotime($surat['created_at'] ?? 'now')) ?> WIB</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (($surat['status'] ?? '') === 'selesai'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                            <span class="material-symbols-outlined text-xs mr-1">check_circle</span>
                                            Selesai
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                                            <span class="material-symbols-outlined text-xs mr-1">cancel</span>
                                            Ditolak
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="viewDetail(<?= $surat['id'] ?? 0 ?>)" 
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                        </button>
                                        <?php if (($surat['status'] ?? '') === 'selesai' && !empty($surat['file_surat'])): ?>
                                            <a href="<?= base_url('uploads/surat/' . $surat['file_surat']) ?>" target="_blank"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                                <span class="material-symbols-outlined text-sm">download</span>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" onclick="deleteSurat(<?= $surat['id'] ?? 0 ?>)"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($pager)): ?>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
    // Search functionality
    document.getElementById('searchArsip').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.arsip-row');
        
        rows.forEach(row => {
            const pemohon = row.querySelector('.surat-pemohon')?.textContent.toLowerCase() || '';
            const jenis = row.querySelector('.surat-jenis')?.textContent.toLowerCase() || '';
            
            if (pemohon.includes(searchTerm) || jenis.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // View detail
    function viewDetail(suratId) {
        // Implementasi detail modal atau redirect
        window.location.href = `<?= base_url('admin/surat/detail/') ?>${suratId}`;
    }

    // Delete surat
    function deleteSurat(suratId) {
        if (confirm('Yakin ingin menghapus arsip surat ini? Tindakan ini tidak dapat dibatalkan.')) {
            fetch(`<?= base_url('admin/surat/delete/') ?>${suratId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Arsip berhasil dihapus');
                    location.reload();
                } else {
                    alert('Gagal menghapus arsip: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus arsip');
            });
        }
    }
</script>
<?php $this->endSection() ?>
