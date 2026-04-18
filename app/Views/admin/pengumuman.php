<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Manajemen Pengumuman</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pengumuman dan informasi penting</p>
            </div>
            <a href="<?= base_url('admin/pengumuman/tambah') ?>" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Pengumuman
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pengumuman</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?= count($pengumuman ?? []) ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">campaign</span>
                </div>
            </div>
        </div>

        <!-- Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Aktif</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                        <?= count(array_filter($pengumuman ?? [], fn($p) => $p['status'] === 'aktif')) ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">check_circle</span>
                </div>
            </div>
        </div>

        <!-- Draft -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Draft</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                        <?= count(array_filter($pengumuman ?? [], fn($p) => $p['status'] === 'draft')) ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-yellow-600 dark:text-yellow-400">edit_note</span>
                </div>
            </div>
        </div>

        <!-- Urgent -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Urgent</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                        <?= count(array_filter($pengumuman ?? [], fn($p) => $p['tipe'] === 'urgent')) ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-red-600 dark:text-red-400">warning</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto" x-data="{ activeTab: 'all' }">
                <button @click="activeTab = 'all'; filterPengumuman('all')"
                        :class="activeTab === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">list</span>
                    Semua
                </button>
                <button @click="activeTab = 'aktif'; filterPengumuman('aktif')"
                        :class="activeTab === 'aktif' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    Aktif
                </button>
                <button @click="activeTab = 'draft'; filterPengumuman('draft')"
                        :class="activeTab === 'draft' ? 'bg-yellow-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">edit_note</span>
                    Draft
                </button>
                <button @click="activeTab = 'urgent'; filterPengumuman('urgent')"
                        :class="activeTab === 'urgent' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">warning</span>
                    Urgent
                </button>
            </div>

            <!-- Search -->
            <div class="relative flex-1 md:max-w-xs">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" 
                       id="searchPengumuman" 
                       placeholder="Cari judul pengumuman..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Pengumuman Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Pengumuman</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tipe</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Prioritas</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Periode</span>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pengumuman)): ?>
                        <?php foreach ($pengumuman as $item): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-4 px-6">
                                    <div class="flex items-start gap-2">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900 dark:text-white"><?= esc($item['judul']) ?></div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                                <?= esc(substr(strip_tags($item['isi']), 0, 100)) ?>...
                                            </div>
                                            <?php if ($item['tampil_di_beranda'] == 1): ?>
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 rounded-full">
                                                        <span class="material-symbols-outlined" style="font-size: 14px;">home</span>
                                                        Tampil di Beranda
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php
                                    $tipeColors = [
                                        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'peringatan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'biasa' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300'
                                    ];
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $tipeColors[$item['tipe']] ?? $tipeColors['biasa'] ?>">
                                        <?= ucfirst($item['tipe']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <?php
                                    $prioritasColors = [
                                        'tinggi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'sedang' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'rendah' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                    ];
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $prioritasColors[$item['prioritas']] ?? $prioritasColors['rendah'] ?>">
                                        <?= ucfirst($item['prioritas']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if ($item['status'] === 'aktif'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full">
                                            Aktif
                                        </span>
                                    <?php elseif ($item['status'] === 'draft'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full">
                                            Draft
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 rounded-full">
                                            Non-aktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-400">
                                    <div><?= date('d/m/Y', strtotime($item['tanggal_mulai'])) ?></div>
                                    <?php if ($item['tanggal_selesai']): ?>
                                        <div class="text-xs">s/d <?= date('d/m/Y', strtotime($item['tanggal_selesai'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= base_url('admin/pengumuman/edit/' . $item['id']) ?>" 
                                           class="text-primary hover:text-blue-700 p-1" title="Edit">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </a>
                                        <a href="<?= base_url('admin/pengumuman/hapus/' . $item['id']) ?>" 
                                           class="text-red-600 hover:text-red-800 p-1" title="Hapus"
                                           onclick="return confirm('Yakin hapus pengumuman ini?')">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 block">campaign</span>
                                    <p>Belum ada pengumuman</p>
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