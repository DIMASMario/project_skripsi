<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8 md:py-12">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-text-light dark:text-white mb-4">Galeri Desa Blanakan</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">Dokumentasi kegiatan, acara, dan momen bersejarah dalam perjalanan pembangunan Desa Blanakan</p>
    </div>

    <!-- Category Filter -->
    <div class="flex justify-center mb-8">
        <div class="flex gap-2 p-2 flex-wrap bg-gray-100 dark:bg-gray-800 rounded-full">
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary px-5 text-white transition-colors" data-category="all">
                <p class="text-sm font-medium">Semua</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-category="kegiatan">
                <p class="text-sm font-medium">Kegiatan</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-category="acara">
                <p class="text-sm font-medium">Acara</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-category="infrastruktur">
                <p class="text-sm font-medium">Infrastruktur</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-category="wisata">
                <p class="text-sm font-medium">Wisata</p>
            </button>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-grid">
        <?php foreach ($gallery_items as $item): ?>
        <div class="gallery-item group cursor-pointer" data-category="<?= $item['category'] ?>">
            <div class="relative overflow-hidden rounded-xl bg-gray-200 dark:bg-gray-700 aspect-[4/3] group-hover:shadow-xl transition-all duration-300">
                <img src="<?= $item['image_url'] ?>" 
                     alt="<?= $item['title'] ?>" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     onclick="openLightbox('<?= $item['image_url'] ?>', '<?= addslashes($item['title']) ?>', '<?= addslashes($item['description']) ?>', '<?= $item['date'] ?>', '<?= $item['category_label'] ?>')">
                
                <!-- Overlay on hover -->
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-end">
                    <div class="p-4 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <p class="text-xs uppercase tracking-wider text-accent mb-1"><?= $item['category_label'] ?></p>
                        <h3 class="font-bold text-sm mb-1"><?= $item['title'] ?></h3>
                        <p class="text-xs opacity-80"><?= date('d M Y', strtotime($item['date'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Load More Button -->
    <div class="text-center mt-12">
        <button id="loadMoreBtn" class="px-8 py-4 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
            Muat Lebih Banyak
        </button>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm">
    <div class="flex items-center justify-center h-full p-4">
        <div class="relative max-w-4xl w-full">
            <!-- Close Button -->
            <button onclick="closeLightbox()" class="absolute top-4 right-4 z-10 text-white bg-black/50 rounded-full p-2 hover:bg-black/70 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
            
            <!-- Navigation Buttons -->
            <button onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 text-white bg-black/50 rounded-full p-2 hover:bg-black/70 transition-colors">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 text-white bg-black/50 rounded-full p-2 hover:bg-black/70 transition-colors">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
            
            <!-- Image Container -->
            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-2xl">
                <div class="relative">
                    <img id="lightboxImage" src="" alt="" class="w-full max-h-[70vh] object-contain">
                </div>
                
                <!-- Image Info -->
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span id="lightboxCategory" class="inline-block px-3 py-1 bg-primary/20 text-primary text-xs font-medium rounded-full"></span>
                        <span id="lightboxDate" class="text-sm text-gray-500 dark:text-gray-400"></span>
                    </div>
                    <h3 id="lightboxTitle" class="text-xl font-bold text-text-light dark:text-white mb-2"></h3>
                    <p id="lightboxDescription" class="text-gray-600 dark:text-gray-400"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentImageIndex = 0;
let currentGalleryItems = [];

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    // Store original gallery items for lightbox navigation
    currentGalleryItems = Array.from(galleryItems);
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('bg-primary', 'text-white');
                b.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            });
            this.classList.add('bg-primary', 'text-white');
            this.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');

            // Filter items
            galleryItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeIn 0.5s ease-in';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Update current gallery items for navigation
            currentGalleryItems = Array.from(galleryItems).filter(item => 
                category === 'all' || item.dataset.category === category
            );
        });
    });
});

// Lightbox functionality
function openLightbox(imageSrc, title, description, date, category) {
    const modal = document.getElementById('lightboxModal');
    const image = document.getElementById('lightboxImage');
    const titleEl = document.getElementById('lightboxTitle');
    const descEl = document.getElementById('lightboxDescription');
    const dateEl = document.getElementById('lightboxDate');
    const categoryEl = document.getElementById('lightboxCategory');
    
    // Find current image index
    const visibleItems = currentGalleryItems.filter(item => item.style.display !== 'none');
    currentImageIndex = visibleItems.findIndex(item => 
        item.querySelector('img').src === imageSrc
    );
    
    image.src = imageSrc;
    image.alt = title;
    titleEl.textContent = title;
    descEl.textContent = description;
    dateEl.textContent = new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    categoryEl.textContent = category;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const modal = document.getElementById('lightboxModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function nextImage() {
    const visibleItems = currentGalleryItems.filter(item => item.style.display !== 'none');
    currentImageIndex = (currentImageIndex + 1) % visibleItems.length;
    showImageAtIndex(currentImageIndex);
}

function prevImage() {
    const visibleItems = currentGalleryItems.filter(item => item.style.display !== 'none');
    currentImageIndex = currentImageIndex === 0 ? visibleItems.length - 1 : currentImageIndex - 1;
    showImageAtIndex(currentImageIndex);
}

function showImageAtIndex(index) {
    const visibleItems = currentGalleryItems.filter(item => item.style.display !== 'none');
    const item = visibleItems[index];
    if (item) {
        const img = item.querySelector('img');
        img.click();
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('lightboxModal');
    if (!modal.classList.contains('hidden')) {
        switch(e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                prevImage();
                break;
            case 'ArrowRight':
                nextImage();
                break;
        }
    }
});

// Close modal when clicking outside
document.getElementById('lightboxModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Load more functionality (placeholder)
document.getElementById('loadMoreBtn').addEventListener('click', function() {
    // This would typically load more images via AJAX
    alert('Fitur muat lebih banyak akan segera tersedia');
});

// CSS Animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>

<?= $this->endSection() ?>