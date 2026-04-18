<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Manajemen Galeri</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola foto dan video galeri website desa</p>
            </div>
            <a href="<?= base_url('admin/tambah_galeri') ?>" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Media
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Media -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Media</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?= count($galeri ?? []) ?></p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">photo_library</span>
                </div>
            </div>
        </div>

        <!-- Foto -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Foto</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                        <?= count($galeri ?? []) ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">image</span>
                </div>
            </div>
        </div>

        <!-- Video -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Video</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">0</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-400">play_circle</span>
                </div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Kategori</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                        <?= $total_albums ?? 0 ?>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-orange-600 dark:text-orange-400">folder</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto" x-data="{ activeTab: 'all' }">
                <button @click="activeTab = 'all'; filterGaleri('all')"
                        :class="activeTab === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">grid_view</span>
                    Semua
                </button>
                <?php 
                $albumsList = ['Kegiatan', 'Budaya', 'Pembangunan', 'Wisata', 'Sosial', 'Upacara', 'Lainnya'];
                foreach ($albumsList as $albumName): 
                ?>
                <button @click="activeTab = '<?= strtolower($albumName) ?>'; filterByAlbum('<?= $albumName ?>')"
                        :class="activeTab === '<?= strtolower($albumName) ?>' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-base">folder</span>
                    <?= $albumName ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Search -->
            <div class="relative flex-1 md:max-w-xs">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" 
                       id="searchGaleri" 
                       placeholder="Cari judul media..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Galeri Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="galeriGrid">
        <?php if (!empty($galeri)): ?>
            <?php foreach ($galeri as $item): ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow galeri-item" 
                     data-album="<?= esc($item['album']) ?>">
                    <!-- Media -->
                    <div class="relative group">
                        <img src="<?= base_url('uploads/galeri/' . $item['gambar']) ?>" 
                             alt="<?= esc($item['judul']) ?>"
                             class="w-full h-48 object-cover">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button onclick="viewImage('<?= base_url('uploads/galeri/' . $item['gambar']) ?>', '<?= esc($item['judul']) ?>')"
                                    class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                            <button onclick="if(confirm('Yakin hapus foto ini?')) window.location.href='<?= base_url('admin/galeri/hapus/' . $item['id']) ?>'" 
                                    class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>

                        <!-- Badge Album -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium">
                                <span class="material-symbols-outlined text-sm">folder</span>
                                <?= esc($item['album']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 galeri-judul">
                            <?= esc($item['judul']) ?>
                        </h3>
                        <?php if (!empty($item['deskripsi'])): ?>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 line-clamp-2">
                            <?= esc($item['deskripsi']) ?>
                        </p>
                        <?php endif; ?>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span><?= date('d M Y', strtotime($item['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full py-12 text-center">
                <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl mb-3">photo_library</span>
                <p class="text-gray-500 dark:text-gray-400">Belum ada media galeri</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Klik tombol "Tambah Media" untuk menambah foto atau video</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" onclick="closeModal()">
    <div class="relative max-w-4xl max-h-[90vh]" onclick="event.stopPropagation()">
        <button onclick="closeModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <span class="material-symbols-outlined text-3xl">close</span>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] rounded-lg">
        <p id="modalTitle" class="text-white text-center mt-4 text-lg"></p>
    </div>
</div>

<script>
    // Filter galeri by album
    function filterGaleri(type) {
        const items = document.querySelectorAll('.galeri-item');
        items.forEach(item => {
            item.style.display = '';
        });
    }
    
    function filterByAlbum(album) {
        const items = document.querySelectorAll('.galeri-item');
        items.forEach(item => {
            if (item.dataset.album === album) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Search functionality
    document.getElementById('searchGaleri').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.galeri-item');
        
        items.forEach(item => {
            const judul = item.querySelector('.galeri-judul').textContent.toLowerCase();
            if (judul.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // View image in modal
    function viewImage(src, title) {
        document.getElementById('modalImage').src = src;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('imageModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>

<?php $this->endSection() ?>
