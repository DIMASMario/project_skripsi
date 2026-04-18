<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="bg-gray-50">
    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-12 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Berita & Pengumuman</h1>
            <p class="text-lg text-gray-600">Informasi terkini seputar kegiatan dan pengumuman desa</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <!-- Search & Filter -->
        <div class="mb-8 bg-white rounded-xl shadow-md p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" 
                           name="search" 
                           placeholder="Cari berita..." 
                           value="<?= esc($current_search ?? '') ?>"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <select name="kategori" 
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-48">
                    <option value="">Semua Kategori</option>
                    <?php if (!empty($kategori_list)): ?>
                        <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?= esc($kat['kategori']) ?>" <?= ($current_kategori ?? '') == $kat['kategori'] ? 'selected' : '' ?>>
                                <?= ucfirst(esc($kat['kategori'])) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <span class="material-symbols-outlined">search</span>
                    Cari
                </button>
            </form>
        </div>

        <!-- Berita Grid -->
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php if (!empty($berita)): ?>
                <?php foreach ($berita as $item): ?>
                <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if (!empty($item['gambar'])): ?>
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        <img src="<?= base_url('uploads/berita/' . $item['gambar']) ?>" 
                             alt="<?= esc($item['judul']) ?>"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <?php else: ?>
                    <div class="aspect-video bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">article</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                <span class="material-symbols-outlined text-sm">category</span>
                                <?= ucfirst(esc($item['kategori'])) ?>
                            </span>
                            <span class="text-sm text-gray-500"><?= $item['views'] ?? 0 ?> views</span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="<?= base_url('berita/' . $item['slug']) ?>" 
                               class="text-gray-900 hover:text-blue-600 transition-colors">
                                <?= esc($item['judul']) ?>
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?= word_limiter(strip_tags($item['konten']), 25) ?>
                        </p>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span><?= date('d M Y', strtotime($item['created_at'])) ?></span>
                            </div>
                            <a href="<?= base_url('berita/' . $item['slug']) ?>" 
                               class="inline-flex items-center gap-1 text-blue-600 font-semibold hover:gap-2 transition-all">
                                Baca
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">search_off</span>
                    <p class="text-gray-500 text-lg font-medium">Tidak ada berita yang ditemukan.</p>
                    <p class="text-gray-400 mt-2">Coba ubah kata kunci atau filter kategori</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
        <div class="mt-12">
            <nav class="flex justify-center">
                <ul class="flex items-center gap-2">
                    <?php if ($pager->hasPrevious()): ?>
                        <li>
                            <a href="<?= $pager->getPreviousPage() ?>" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php foreach ($pager->links() as $link): ?>
                        <li>
                            <a href="<?= $link['uri'] ?>" 
                               class="inline-flex items-center justify-center min-w-10 h-10 px-3 rounded-lg transition-colors <?= $link['active'] ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-100' ?>">
                                <?= $link['title'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    
                    <?php if ($pager->hasNext()): ?>
                        <li>
                            <a href="<?= $pager->getNextPage() ?>" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
