<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8 md:py-12">
    <!-- Hero Section -->
    <div class="w-full @container mb-12">
        <div class="flex min-h-[480px] flex-col gap-6 rounded-xl bg-cover bg-center bg-no-repeat p-6 md:p-12 items-center justify-center text-center" 
             style='background-image: linear-gradient(rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.5) 100%), url("<?= $hero['background_image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmR9S6ytJhCDIlz_TWXQgOBG2Y1LqqWmlTAV5XOQbTw9ppjfQTJgVLc5atxWngwEaNWVEMNMgVkSV4osqYRqXFMkTVsmvoU1Z5hOs7wvZv8S_tjO6TwKgL9saY5mbhpk6vsH_NZIfCWUTWvyuuiMDQeNmRlUPF_6M1hJXP4yj2nmH6_RPkHwkbeUzlJ3T0Yb5bJsCwhAxYh-yjILVA7qLPvDsPHrCBsRKCZRepF5Gorl7IOoa_xtz3y7TPrBdbV7NFSde4EMcCuA_H' ?>");'>
            <div class="flex flex-col gap-4 max-w-2xl">
                <h1 class="text-white text-4xl font-black leading-tight tracking-tight @[480px]:text-5xl">
                    <?= $hero['title'] ?? 'Pesona Budaya & Wisata Desa Blanakan' ?>
                </h1>
                <p class="text-slate-100 text-base font-normal leading-normal @[480px]:text-lg">
                    <?= $hero['subtitle'] ?? 'Jelajahi keunikan perpaduan alam, budaya, dan kearifan lokal yang ditawarkan desa kami.' ?>
                </p>
            </div>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-accent text-slate-900 text-base font-bold tracking-wide hover:bg-accent/90 transition-colors">
                <span class="truncate"><?= $hero['cta_text'] ?? 'Jelajahi Sekarang' ?></span>
            </button>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="flex justify-center mb-8">
        <div class="flex gap-2 p-2 flex-wrap bg-slate-200 dark:bg-slate-800 rounded-full">
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary px-5 text-white" data-category="all">
                <p class="text-sm font-medium">Semua</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors" data-category="wisata">
                <p class="text-sm font-medium">Wisata</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors" data-category="budaya">
                <p class="text-sm font-medium">Budaya</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors" data-category="kuliner">
                <p class="text-sm font-medium">Kuliner</p>
            </button>
            <button class="filter-btn flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full px-5 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors" data-category="kerajinan">
                <p class="text-sm font-medium">Kerajinan</p>
            </button>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-grid">
        <?php foreach ($gallery_items as $item): ?>
        <div class="gallery-item flex flex-col gap-3 group cursor-pointer" data-category="<?= $item['category'] ?>">
            <a href="<?= isset($item['slug']) ? base_url('/wisata/' . $item['slug']) : '#' ?>" class="block">
                <div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover rounded-xl overflow-hidden transform group-hover:scale-105 transition-transform duration-300" 
                     style='background-image: url("<?= $item['image'] ?>");'>
                </div>
                <div class="p-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-primary"><?= $item['category_label'] ?></p>
                    <p class="text-slate-900 dark:text-slate-50 text-lg font-bold leading-tight mt-1 group-hover:text-primary transition-colors"><?= $item['title'] ?></p>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-normal leading-normal mt-1"><?= $item['description'] ?></p>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-center p-4 mt-8">
        <a class="flex size-10 items-center justify-center rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">chevron_left</span>
        </a>
        <a class="text-sm font-bold leading-normal flex size-10 items-center justify-center text-white rounded-lg bg-primary" href="#">1</a>
        <a class="text-sm font-medium leading-normal flex size-10 items-center justify-center text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" href="#">2</a>
        <a class="text-sm font-medium leading-normal flex size-10 items-center justify-center text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" href="#">3</a>
        <span class="text-sm font-medium leading-normal flex size-10 items-center justify-center text-slate-700 dark:text-slate-300 rounded-lg">...</span>
        <a class="text-sm font-medium leading-normal flex size-10 items-center justify-center text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" href="#">8</a>
        <a class="flex size-10 items-center justify-center rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">chevron_right</span>
        </a>
    </div>
</div>

<!-- JavaScript for filtering functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('bg-primary', 'text-white');
                b.classList.add('text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-700');
            });
            this.classList.add('bg-primary', 'text-white');
            this.classList.remove('text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-700');

            // Filter items
            galleryItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'flex';
                    item.style.animation = 'fadeIn 0.5s ease-in';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// CSS Animation keyframes
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