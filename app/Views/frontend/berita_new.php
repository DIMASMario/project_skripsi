<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8 md:py-12">
    <div class="mx-auto max-w-4xl">
        <!-- Page Heading -->
        <div class="flex flex-col gap-2 mb-8">
            <h1 class="text-text-light dark:text-text-dark text-3xl md:text-4xl font-black tracking-tighter">Berita & Pengumuman Desa</h1>
            <p class="text-gray-600 dark:text-gray-400 text-base">Informasi terkini seputar kegiatan, berita, dan pengumuman resmi dari Desa Blanakan.</p>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <!-- Search Bar -->
            <div class="flex-grow">
                <div class="relative flex w-full flex-1 items-stretch rounded-lg h-12">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-2xl">search</span>
                    </div>
                    <input 
                        class="form-input w-full flex-1 resize-none rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 pl-12 pr-4 text-base" 
                        placeholder="Cari berita atau pengumuman..." 
                        value="" 
                        id="search-input"
                    />
                </div>
            </div>
            
            <!-- Category Filter Dropdown -->
            <div class="relative">
                <select 
                    class="form-select w-full md:w-auto h-12 appearance-none rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark py-2 pl-4 pr-10 text-base text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary"
                    id="category-filter"
                >
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?= strtolower($kategori) ?>" <?= ($kategori_aktif == strtolower($kategori)) ? 'selected' : '' ?>>
                            <?= $kategori ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                    <span class="material-symbols-outlined">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Filter Chips (Desktop) -->
        <div class="hidden md:flex gap-3 mb-8">
            <button class="filter-chip flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= empty($kategori_aktif) ? 'bg-primary/10 text-primary' : 'bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400' ?> px-4" data-category="">
                <p class="text-sm font-medium">Semua</p>
            </button>
            <?php foreach ($kategori_list as $kategori): ?>
                <button class="filter-chip flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= ($kategori_aktif == strtolower($kategori)) ? 'bg-primary/10 text-primary' : 'bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400' ?> px-4" data-category="<?= strtolower($kategori) ?>">
                    <p class="text-sm font-medium"><?= $kategori ?></p>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Content List -->
        <div class="flex flex-col gap-8" id="berita-container">
            <?php if (!empty($berita) && is_array($berita)): ?>
                <?php foreach ($berita as $item): ?>
                    <?php 
                    $isPengumuman = ($item['type'] ?? 'berita') === 'pengumuman';
                    
                    // Badge color based on type/prioritas
                    $badgeClass = 'bg-accent/20 text-accent-800 dark:text-accent dark:bg-accent/10';
                    if ($isPengumuman) {
                        $prioritas = $item['prioritas'] ?? 'sedang';
                        if ($prioritas === 'tinggi') {
                            $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                        } elseif ($prioritas === 'sedang') {
                            $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                        } else {
                            $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                        }
                    }
                    
                    // Default image for pengumuman
                    $defaultImage = 'https://images.unsplash.com/photo-1586339949216-35c2747c0851?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                    $imageUrl = $item['gambar'] ? base_url('uploads/berita/' . $item['gambar']) : $defaultImage;
                    
                    // Link
                    $detailUrl = $isPengumuman ? 'javascript:void(0)' : base_url('berita/' . $item['slug']);
                    ?>
                    
                    <article class="flex flex-col md:flex-row items-stretch gap-6 rounded-xl border border-border-light dark:border-border-dark p-4 <?= $isPengumuman ? 'bg-blue-50/50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800' : '' ?> transition-shadow hover:shadow-lg dark:hover:bg-card-dark/50">
                        <?php if (!$isPengumuman): ?>
                            <!-- Image for Berita -->
                            <div class="w-full md:w-1/3 h-48 md:h-auto bg-center bg-no-repeat bg-cover rounded-lg bg-gray-200 dark:bg-gray-700" 
                                 style="background-image: url('<?= $imageUrl ?>');">
                            </div>
                        <?php else: ?>
                            <!-- Icon for Pengumuman -->
                            <div class="w-full md:w-1/3 h-48 md:h-auto flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg">
                                <span class="material-symbols-outlined text-7xl text-blue-600 dark:text-blue-400">
                                    <?= ($item['tipe'] ?? 'info') === 'urgent' ? 'warning' : (($item['tipe'] ?? 'info') === 'peringatan' ? 'info' : 'campaign') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex flex-1 flex-col gap-3 justify-center">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        <?= date('d M Y', strtotime($item['tanggal_publikasi'])) ?>
                                    </p>
                                    <?php if ($isPengumuman): ?>
                                        <span class="flex items-center gap-1 <?= $badgeClass ?> px-2 py-0.5 rounded-full text-xs font-semibold">
                                            <span class="material-symbols-outlined text-xs">notifications_active</span>
                                            PENGUMUMAN
                                        </span>
                                    <?php endif; ?>
                                    <span class="inline-block <?= $badgeClass ?> px-2 py-0.5 rounded-full text-xs font-semibold">
                                        <?= ucfirst($item['kategori']) ?>
                                    </span>
                                </div>
                                <h3 class="text-text-light dark:text-text-dark text-xl font-bold leading-tight <?= !$isPengumuman ? 'hover:text-primary dark:hover:text-primary' : '' ?> text-justify">
                                    <?php if (!$isPengumuman): ?>
                                        <a href="<?= $detailUrl ?>"><?= esc($item['judul']) ?></a>
                                    <?php else: ?>
                                        <?= esc($item['judul']) ?>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed text-justify">
                                    <?= character_limiter(strip_tags($item['konten']), 150) ?>
                                </p>
                            </div>
                            <?php if (!$isPengumuman): ?>
                                <a class="flex items-center gap-2 text-primary dark:text-accent text-sm font-bold w-fit hover:underline" 
                                   href="<?= $detailUrl ?>">
                                    <span>Baca Selengkapnya</span>
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </a>
                            <?php else: ?>
                                <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm font-semibold">
                                    <span class="material-symbols-outlined">info</span>
                                    <span>Pengumuman Resmi Desa</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center gap-4 rounded-xl border-2 border-dashed border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark text-center p-12">
                    <span class="material-symbols-outlined text-5xl text-gray-500 dark:text-gray-400">search_off</span>
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Berita Tidak Ditemukan</h3>
                    <p class="max-w-xs text-gray-600 dark:text-gray-400">Belum ada berita yang dipublikasikan atau coba gunakan kata kunci pencarian lain.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && ($pager->getPageCount)() > 1): ?>
            <nav aria-label="Pagination" class="flex items-center justify-between border-t border-border-light dark:border-border-dark pt-6 mt-12">
                <div class="flex-1 flex justify-start">
                    <?php if (($pager->hasPrevious)()): ?>
                        <a class="relative inline-flex items-center rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-background-light dark:hover:bg-gray-700" 
                           href="<?= ($pager->getPrevious)() ?>">
                            <span class="material-symbols-outlined mr-2">west</span>
                            Sebelumnya
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="hidden md:flex space-x-2">
                    <?php foreach (($pager->links)() as $link): ?>
                        <a class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg <?= $link['active'] ? 'text-white bg-primary' : 'text-gray-600 dark:text-gray-400 hover:bg-background-light dark:hover:bg-gray-700' ?>" 
                           href="<?= $link['uri'] ?>">
                            <?= $link['title'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <div class="flex-1 flex justify-end">
                    <?php if (($pager->hasNext)()): ?>
                        <a class="relative inline-flex items-center rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-background-light dark:hover:bg-gray-700" 
                           href="<?= ($pager->getNext)() ?>">
                            Selanjutnya
                            <span class="material-symbols-outlined ml-2">east</span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter chips functionality
    const filterChips = document.querySelectorAll('.filter-chip');
    const categorySelect = document.getElementById('category-filter');
    const searchInput = document.getElementById('search-input');

    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active state
            filterChips.forEach(c => {
                c.className = c.className.replace('bg-primary/10 text-primary', 'bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400');
            });
            this.className = this.className.replace('bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400', 'bg-primary/10 text-primary');
            
            // Update select value and redirect
            categorySelect.value = category;
            filterBerita(category, searchInput.value);
        });
    });

    // Category select change
    categorySelect.addEventListener('change', function() {
        const category = this.value;
        
        // Update chips
        filterChips.forEach(chip => {
            const isActive = chip.dataset.category === category;
            chip.className = chip.className.replace(/bg-primary\/10 text-primary|bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400/g, '');
            chip.className += isActive ? 'bg-primary/10 text-primary' : 'bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400';
        });
        
        filterBerita(category, searchInput.value);
    });

    // Search functionality (with debounce)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterBerita(categorySelect.value, this.value);
        }, 500);
    });

    function filterBerita(category, search) {
        const url = new URL(window.location);
        
        if (category) {
            url.searchParams.set('kategori', category);
        } else {
            url.searchParams.delete('kategori');
        }
        
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        
        window.location.href = url.toString();
    }
});
</script>
<?= $this->endSection() ?>