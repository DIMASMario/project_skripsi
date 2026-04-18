<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Manajemen Berita</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola berita dan artikel website desa</p>
            </div>
            <a href="<?= base_url('admin/berita/tambah') ?>" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Berita
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Berita -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Berita</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?= count($berita ?? []) ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">article</span>
                </div>
            </div>
        </div>

        <!-- Dipublikasi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Dipublikasi</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                        <?= count(array_filter($berita ?? [], fn($b) => $b['status'] === 'publish')) ?>
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
                        <?= count(array_filter($berita ?? [], fn($b) => $b['status'] === 'draft')) ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-yellow-600 dark:text-yellow-400">edit_note</span>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Views</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        <?= array_sum(array_column($berita ?? [], 'views')) ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-400">visibility</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto" x-data="{ activeTab: 'all' }">
                <button @click="activeTab = 'all'; filterBerita('all')"
                        :class="activeTab === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">list</span>
                    Semua Berita
                </button>
                <button @click="activeTab = 'publish'; filterBerita('publish')"
                        :class="activeTab === 'publish' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    Dipublikasi
                </button>
                <button @click="activeTab = 'draft'; filterBerita('draft')"
                        :class="activeTab === 'draft' ? 'bg-yellow-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">edit_note</span>
                    Draft
                </button>
            </div>

            <!-- Search -->
            <div class="relative flex-1 md:max-w-xs">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" 
                       id="searchBerita" 
                       placeholder="Cari judul berita..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Berita Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Berita</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Kategori</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Views</span>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Dibuat</span>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (!empty($berita)): ?>
                        <?php foreach ($berita as $item): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors berita-row" 
                                data-status="<?= esc($item['status']) ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <?php if (!empty($item['gambar'])): ?>
                                            <img src="<?= base_url('uploads/berita/' . $item['gambar']) ?>" 
                                                 alt="<?= esc($item['judul']) ?>"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        <?php else: ?>
                                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-400">image</span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 dark:text-white berita-judul truncate">
                                                <?= esc($item['judul']) ?>
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                                <?= esc($item['author_name'] ?? 'Admin') ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 berita-kategori">
                                        <?= esc($item['kategori']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($item['status'] === 'publish'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Publish
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">
                                            <span class="material-symbols-outlined text-sm">edit_note</span>
                                            Draft
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                        <?= number_format($item['views']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    <?= date('d M Y', strtotime($item['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= base_url('berita/' . $item['slug']) ?>" 
                                           target="_blank"
                                           class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                           title="Lihat">
                                            <span class="material-symbols-outlined text-xl">visibility</span>
                                        </a>
                                        <a href="<?= base_url('admin/berita/edit/' . $item['id']) ?>" 
                                           class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                                           title="Edit">
                                            <span class="material-symbols-outlined text-xl">edit</span>
                                        </a>
                                        <button onclick="if(confirm('Yakin hapus berita ini?')) window.location.href='<?= base_url('admin/berita/hapus/' . $item['id']) ?>'" 
                                                class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                title="Hapus">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl mb-3">article</span>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada berita</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Klik tombol "Tambah Berita" untuk membuat berita baru</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Filter berita by status
    function filterBerita(status) {
        const rows = document.querySelectorAll('.berita-row');
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Search functionality
    document.getElementById('searchBerita').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.berita-row');
        
        rows.forEach(row => {
            const judul = row.querySelector('.berita-judul').textContent.toLowerCase();
            const kategori = row.querySelector('.berita-kategori').textContent.toLowerCase();
            
            if (judul.includes(searchTerm) || kategori.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<?php $this->endSection() ?>