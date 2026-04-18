<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="flex flex-1 justify-center py-5 px-4 sm:px-6 md:px-8">
    <div class="flex flex-col w-full max-w-4xl flex-1 gap-6 md:gap-8">
        <!-- Breadcrumbs -->
        <div class="flex flex-wrap gap-2 px-1 py-2">
            <a class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm font-medium leading-normal" href="<?= base_url('/') ?>">Beranda</a>
            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">/</span>
            <a class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm font-medium leading-normal" href="<?= base_url('/budaya-wisata') ?>">Budaya & Wisata</a>
            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">/</span>
            <span class="text-text-light dark:text-white text-sm font-medium leading-normal"><?= esc($detail['title']) ?></span>
        </div>

        <!-- Header Image -->
        <div class="w-full h-64 md:h-80 lg:h-96 rounded-xl overflow-hidden shadow-lg">
            <div class="w-full h-full bg-center bg-no-repeat bg-cover" 
                 style='background-image: url("<?= esc($detail['main_image']) ?>");'>
            </div>
        </div>

        <!-- Page Heading & Tags -->
        <div class="flex flex-col gap-4 px-1">
            <div class="flex flex-col gap-2">
                <h1 class="text-primary text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]"><?= esc($detail['title']) ?></h1>
                <p class="text-gray-600 dark:text-gray-400 text-base font-normal leading-normal"><?= esc($detail['subtitle']) ?></p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <?php foreach ($detail['tags'] as $tag): ?>
                <div class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary/20 dark:bg-primary/30 px-4">
                    <p class="text-primary dark:text-slate-100 text-sm font-medium leading-normal"><?= esc($tag) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-1">
            <!-- Main Content -->
            <div class="md:col-span-2 flex flex-col gap-8">
                <!-- Description Card -->
                <div class="bg-white dark:bg-card-dark p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-text-light dark:text-white">Deskripsi</h3>
                    <div class="space-y-4 text-gray-600 dark:text-gray-400 text-base leading-relaxed">
                        <?php foreach ($detail['description'] as $paragraph): ?>
                        <p><?= esc($paragraph) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Photo Gallery Section -->
                <div>
                    <h3 class="text-xl font-bold mb-4 text-text-light dark:text-white">Galeri Foto</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <?php foreach ($detail['gallery'] as $index => $image): ?>
                        <?php if ($index < 5): ?>
                        <img class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity" 
                             src="<?= esc($image['url']) ?>" 
                             alt="<?= esc($image['alt']) ?>"
                             onclick="openImageModal('<?= esc($image['url']) ?>', '<?= esc($image['alt']) ?>')"/>
                        <?php elseif ($index === 5): ?>
                        <div class="w-full h-32 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center cursor-pointer hover:bg-primary/10 transition-colors"
                             onclick="showAllPhotos()">
                            <span class="text-primary font-bold">+<?= count($detail['gallery']) - 5 ?> Lainnya</span>
                        </div>
                        <?php break; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Activities Section -->
                <?php if (!empty($detail['activities'])): ?>
                <div class="bg-white dark:bg-card-dark p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-text-light dark:text-white">Aktivitas yang Dapat Dilakukan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($detail['activities'] as $activity): ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <span class="material-symbols-outlined text-primary"><?= esc($activity['icon']) ?></span>
                            <div>
                                <p class="font-semibold text-text-light dark:text-white"><?= esc($activity['name']) ?></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?= esc($activity['description']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="md:col-span-1 flex flex-col gap-6">
                <!-- Information Card -->
                <div class="bg-white dark:bg-card-dark p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-text-light dark:text-white">Informasi</h3>
                    <ul class="space-y-4">
                        <?php if (!empty($detail['info']['hours'])): ?>
                        <li class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-accent text-2xl mt-0.5">schedule</span>
                            <div>
                                <p class="font-semibold text-text-light dark:text-white">Jam Operasional</p>
                                <p class="text-gray-600 dark:text-gray-400"><?= esc($detail['info']['hours']) ?></p>
                            </div>
                        </li>
                        <?php endif; ?>

                        <?php if (!empty($detail['info']['price'])): ?>
                        <li class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-accent text-2xl mt-0.5">confirmation_number</span>
                            <div>
                                <p class="font-semibold text-text-light dark:text-white">Harga Tiket</p>
                                <p class="text-gray-600 dark:text-gray-400"><?= esc($detail['info']['price']) ?></p>
                            </div>
                        </li>
                        <?php endif; ?>

                        <?php if (!empty($detail['info']['contact'])): ?>
                        <li class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-accent text-2xl mt-0.5">call</span>
                            <div>
                                <p class="font-semibold text-text-light dark:text-white">Kontak</p>
                                <p class="text-gray-600 dark:text-gray-400"><?= esc($detail['info']['contact']) ?></p>
                            </div>
                        </li>
                        <?php endif; ?>

                        <?php if (!empty($detail['info']['facilities'])): ?>
                        <li class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-accent text-2xl mt-0.5">deck</span>
                            <div>
                                <p class="font-semibold text-text-light dark:text-white">Fasilitas</p>
                                <p class="text-gray-600 dark:text-gray-400"><?= esc($detail['info']['facilities']) ?></p>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Map Section -->
                <?php if (!empty($detail['location'])): ?>
                <div>
                    <h3 class="text-xl font-bold mb-4 text-text-light dark:text-white">Lokasi</h3>
                    <div class="aspect-w-1 aspect-h-1 rounded-xl overflow-hidden shadow-sm">
                        <iframe 
                            allowfullscreen="" 
                            height="200" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade" 
                            src="<?= esc($detail['location']['embed_url']) ?>" 
                            style="border:0;" 
                            width="100%">
                        </iframe>
                    </div>
                    <button class="mt-4 flex w-full cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-12 px-4 bg-primary text-white text-base font-bold leading-normal hover:bg-primary/90 transition-colors"
                            onclick="openGoogleMaps('<?= esc($detail['location']['google_maps_url']) ?>')">
                        <span class="material-symbols-outlined">near_me</span>
                        <span class="truncate">Lihat Rute di Peta</span>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Related Places -->
                <?php if (!empty($detail['related_places'])): ?>
                <div class="bg-white dark:bg-card-dark p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-text-light dark:text-white">Tempat Terkait</h3>
                    <div class="space-y-3">
                        <?php foreach ($detail['related_places'] as $place): ?>
                        <a href="<?= base_url('/wisata/' . $place['slug']) ?>" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <div class="flex gap-3">
                                <img src="<?= esc($place['image']) ?>" alt="<?= esc($place['name']) ?>" class="w-12 h-12 object-cover rounded-lg">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-text-light dark:text-white"><?= esc($place['name']) ?></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400"><?= esc($place['category']) ?></p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </aside>
        </div>

        <!-- Back to Gallery Button -->
        <div class="flex justify-center px-1">
            <a href="<?= base_url('/budaya-wisata') ?>" class="flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-800 text-text-light dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Kembali ke Galeri</span>
            </a>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm">
    <div class="flex items-center justify-center h-full p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white bg-black/50 rounded-full p-2 hover:bg-black/70">
                <span class="material-symbols-outlined">close</span>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>
</div>

<script>
function openImageModal(imageUrl, altText) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageUrl;
    modalImage.alt = altText;
    modal.classList.remove('hidden');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    
    // Restore body scroll
    document.body.style.overflow = '';
}

function openGoogleMaps(url) {
    window.open(url, '_blank');
}

function showAllPhotos() {
    // This could open a gallery lightbox or navigate to a full gallery page
    alert('Fitur galeri lengkap akan segera tersedia');
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<?= $this->endSection() ?>