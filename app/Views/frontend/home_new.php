<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://github.hubspot.com/odometer/themes/odometer-theme-default.css" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
/* Carousel Debug Styles */
.carousel-container { position: relative; width: 100%; height: 100%; overflow: hidden; z-index: 1; }
.carousel-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.5s ease-in-out; z-index: 1; }
.carousel-slide.active { opacity: 1; z-index: 2; }
.carousel-slide .carousel-title { opacity: 1 !important; transform: translateY(0) !important; }
.carousel-slide .carousel-subtitle { opacity: 1 !important; transform: translateY(0) !important; }
.carousel-slide .carousel-cta { opacity: 1 !important; transform: translateY(0) !important; }

/* Ensure carousel doesn't block navbar */
#hero-carousel { position: relative; z-index: 1 !important; }
header { position: sticky !important; z-index: 9999 !important; }

/* Odometer Custom Styling */
.odometer {
    font-family: inherit;
    font-weight: 700;
}

.odometer.odometer-auto-theme, .odometer.odometer-theme-default {
    display: inline-block;
    vertical-align: middle;
    position: relative;
}

.odometer .odometer-digit {
    display: inline-block;
    vertical-align: middle;
    position: relative;
}

.odometer .odometer-digit .odometer-digit-spacer {
    display: inline-block;
    vertical-align: middle;
    visibility: hidden;
}

.odometer .odometer-digit .odometer-digit-inner {
    text-align: left;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

.odometer .odometer-digit .odometer-ribbon {
    display: block;
}

.odometer .odometer-digit .odometer-ribbon-inner {
    display: block;
    backface-visibility: hidden;
}

.odometer .odometer-digit .odometer-value {
    display: block;
    transform: translateZ(0);
}

.odometer .odometer-digit .odometer-value.odometer-last-value {
    position: absolute;
}

.odometer.odometer-animating-up .odometer-ribbon-inner {
    transition: transform 0.5s;
}

.odometer.odometer-animating-down .odometer-ribbon-inner {
    transform: translateY(-100%);
}

.odometer.odometer-animating-down.odometer-animating .odometer-ribbon-inner {
    transition: transform 0.5s;
    transform: translateY(0);
}

/* AOS Animations */
[data-aos] {
    opacity: 0;
    transition-property: opacity, transform;
}

[data-aos].aos-animate {
    opacity: 1;
}

[data-aos="fade-up"] {
    transform: translate3d(0, 40px, 0);
}

[data-aos="fade-up"].aos-animate {
    transform: translate3d(0, 0, 0);
}

/* Marquee Slider Styles */
.marquee-container {
    display: flex;
    overflow: hidden;
    user-select: none;
    gap: 0;
}

.marquee-content {
    flex-shrink: 0;
    display: flex;
    justify-content: space-around;
    gap: 0;
    min-width: 100%;
    animation: scroll 30s linear infinite;
}

.marquee-item {
    flex-shrink: 0;
}

@keyframes scroll {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-100%);
    }
}

/* Pause animation on hover */
.marquee-container:hover .marquee-content {
    animation-play-state: paused;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
        

            <!-- Hero Carousel Section -->
            <section class="relative w-full h-[70vh] min-h-[500px] bg-gray-900 overflow-hidden" id="hero-carousel" style="min-height: 500px; height: 70vh; z-index: 1; position: relative;">
                <!-- Carousel Container -->
                <div class="carousel-container relative w-full h-full overflow-hidden">
                    <?php foreach ($hero_slides as $index => $slide): ?>
                        <div class="carousel-slide <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>" style="position: absolute;">
                            <div class="absolute inset-0 w-full h-full bg-cover bg-center transition-transform duration-700" 
                                 data-alt="<?= esc($slide['title']) ?>" 
                                 style="background-image: url('<?= esc($slide['background_image']) ?>'); pointer-events: none;">
                            </div>
                            <div class="absolute inset-0 bg-black/40" style="pointer-events: none;"></div>
                            
                            <div class="relative container mx-auto px-4 h-full flex flex-col justify-center text-white">
                                <div class="max-w-2xl text-center sm:text-left">
                                    <h2 class="text-4xl md:text-5xl font-bold leading-tight opacity-0 translate-y-8 carousel-title"><?= esc($slide['title']) ?></h2>
                                    <p class="mt-4 text-lg md:text-xl opacity-0 translate-y-8 carousel-subtitle"><?= esc($slide['subtitle']) ?></p>
                                    <a href="<?= esc($slide['cta_url']) ?>" class="mt-8 inline-flex min-w-[150px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-8 bg-primary text-white text-lg font-bold transition hover:bg-primary/90 opacity-0 translate-y-8 carousel-cta">
                                        <span class="truncate"><?= esc($slide['cta_text']) ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Carousel Navigation -->
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-4 z-10">
                    <button class="carousel-prev size-10 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-all duration-300">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </button>
                    
                    <!-- Dynamic Indicators -->
                    <div class="flex gap-2">
                        <?php foreach ($hero_slides as $index => $slide): ?>
                            <button class="carousel-indicator size-3 rounded-full transition-all duration-300 <?= $index === 0 ? 'bg-white' : 'bg-white/50' ?>" 
                                    data-slide="<?= $index ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="carousel-next size-10 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-all duration-300">
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>
                
                <!-- Auto-play progress indicator (optional) -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-white/20">
                    <div class="carousel-progress h-full bg-primary transition-all duration-100 ease-linear" style="width: 0%;"></div>
                </div>
            </section>

            <!-- Pengumuman Marquee Slider -->
            <?php if (!empty($pengumuman_beranda)): ?>
            <section class="py-6 bg-yellow-50 dark:bg-yellow-900/20 border-y border-yellow-200 dark:border-yellow-800 overflow-hidden">
                <div class="container mx-auto px-4 mb-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-3xl text-yellow-600 dark:text-yellow-400">campaign</span>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pengumuman Penting</h2>
                    </div>
                </div>
                
                <!-- Marquee Container -->
                <div class="relative">
                    <div class="marquee-container">
                        <div class="marquee-content">
                            <?php foreach ($pengumuman_beranda as $pengumuman): ?>
                                <?php
                                    // Tentukan warna berdasarkan tipe
                                    $badgeColors = [
                                        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'peringatan' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'biasa' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    ];
                                    $badgeColor = $badgeColors[$pengumuman['tipe']] ?? $badgeColors['biasa'];
                                    
                                    $iconMap = [
                                        'urgent' => 'emergency',
                                        'peringatan' => 'warning',
                                        'info' => 'info',
                                        'biasa' => 'article'
                                    ];
                                    $icon = $iconMap[$pengumuman['tipe']] ?? 'article';
                                ?>
                                <div class="marquee-item bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mx-3 min-w-[350px] max-w-[350px]">
                                    <div class="flex items-start gap-3">
                                        <span class="material-symbols-outlined text-2xl text-yellow-600 dark:text-yellow-400"><?= $icon ?></span>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-2 py-1 text-xs font-semibold rounded <?= $badgeColor ?>">
                                                    <?= strtoupper($pengumuman['tipe']) ?>
                                                </span>
                                                <?php if ($pengumuman['prioritas'] == 'tinggi'): ?>
                                                    <span class="material-symbols-outlined text-sm text-red-500">keyboard_double_arrow_up</span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                                                <?= esc($pengumuman['judul']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">
                                                <?= esc($pengumuman['isi']) ?>
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="material-symbols-outlined text-sm">schedule</span>
                                                <span>
                                                    <?= date('d M Y', strtotime($pengumuman['tanggal_mulai'])) ?>
                                                    <?php if ($pengumuman['tanggal_selesai']): ?>
                                                        - <?= date('d M Y', strtotime($pengumuman['tanggal_selesai'])) ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Duplicate for seamless loop -->
                        <div class="marquee-content" aria-hidden="true">
                            <?php foreach ($pengumuman_beranda as $pengumuman): ?>
                                <?php
                                    $badgeColors = [
                                        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'peringatan' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'biasa' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    ];
                                    $badgeColor = $badgeColors[$pengumuman['tipe']] ?? $badgeColors['biasa'];
                                    
                                    $iconMap = [
                                        'urgent' => 'emergency',
                                        'peringatan' => 'warning',
                                        'info' => 'info',
                                        'biasa' => 'article'
                                    ];
                                    $icon = $iconMap[$pengumuman['tipe']] ?? 'article';
                                ?>
                                <div class="marquee-item bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mx-3 min-w-[350px] max-w-[350px]">
                                    <div class="flex items-start gap-3">
                                        <span class="material-symbols-outlined text-2xl text-yellow-600 dark:text-yellow-400"><?= $icon ?></span>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-2 py-1 text-xs font-semibold rounded <?= $badgeColor ?>">
                                                    <?= strtoupper($pengumuman['tipe']) ?>
                                                </span>
                                                <?php if ($pengumuman['prioritas'] == 'tinggi'): ?>
                                                    <span class="material-symbols-outlined text-sm text-red-500">keyboard_double_arrow_up</span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                                                <?= esc($pengumuman['judul']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">
                                                <?= esc($pengumuman['isi']) ?>
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="material-symbols-outlined text-sm">schedule</span>
                                                <span>
                                                    <?= date('d M Y', strtotime($pengumuman['tanggal_mulai'])) ?>
                                                    <?php if ($pengumuman['tanggal_selesai']): ?>
                                                        - <?= date('d M Y', strtotime($pengumuman['tanggal_selesai'])) ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Berita Terbaru Section -->
            <section class="py-16 sm:py-24 bg-background-light dark:bg-background-dark">
                <div class="container mx-auto px-4">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-text-light dark:text-text-dark">Berita Terbaru</h2>
                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Ikuti perkembangan dan kegiatan terkini di Desa Blanakan.</p>
                    </div>
                    
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php if (!empty($berita_terbaru)): ?>
                            <?php foreach ($berita_terbaru as $berita): ?>
                                <div class="flex flex-col gap-4 bg-card-light dark:bg-card-dark rounded-xl shadow-md overflow-hidden transition-transform hover:-translate-y-1">
                                    <div class="w-full bg-center bg-no-repeat aspect-video bg-cover" 
                                         data-alt="<?= esc($berita['judul']) ?>" 
                                         style='background-image: url("<?= esc($berita['image_url']) ?>");'>
                                    </div>
                                    
                                    <div class="p-6 flex flex-col flex-1">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 text-justify"><?= esc($berita['formatted_date']) ?></p>
                                        <h3 class="mt-2 text-xl font-semibold text-text-light dark:text-text-dark text-justify">
                                            <?= esc($berita['judul']) ?>
                                        </h3>
                                        <p class="mt-2 text-gray-600 dark:text-gray-400 flex-grow text-justify">
                                            <?= esc($berita['excerpt']) ?>
                                        </p>
                                        <a class="mt-4 text-primary font-semibold hover:underline text-justify" 
                                           href="<?= esc($berita['detail_url']) ?>">
                                            Baca Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400">Belum ada berita terbaru.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex mt-12 justify-center">
                        <a href="<?= base_url('/berita') ?>" class="flex min-w-[180px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary/10 text-primary dark:bg-primary/20 dark:text-white text-base font-bold transition hover:bg-primary/20 dark:hover:bg-primary/30">
                            <span class="truncate">Lihat Semua Berita</span>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Statistik Desa Section -->
            <section class="py-16 sm:py-24 bg-card-light dark:bg-card-dark">
                <div class="container mx-auto px-4">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-text-light dark:text-text-dark">Desa Blanakan dalam Angka</h2>
                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Data terkini mengenai demografi dan potensi desa kami.</p>
                    </div>
                    
                    <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <?php foreach ($statistik_desa as $index => $stat): ?>
                            <div class="flex flex-col items-center p-6 bg-background-light dark:bg-background-dark rounded-xl stat-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                                <span class="material-symbols-outlined text-5xl text-primary"><?= esc($stat['icon']) ?></span>
                                <div class="mt-4 text-4xl font-bold text-primary">
                                    <span class="odometer" data-target="<?= esc($stat['number']) ?>" data-suffix="<?= esc($stat['suffix']) ?>">0</span>
                                </div>
                                <p class="mt-1 text-base text-gray-600 dark:text-gray-400"><?= esc($stat['label']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Layanan Digital Section -->
            <section class="py-16 sm:py-24 bg-background-light dark:bg-background-dark">
                <div class="container mx-auto px-4">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-text-light dark:text-text-dark">Layanan Digital Untuk Warga</h2>
                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Akses layanan administrasi desa dengan mudah dan cepat, kapan saja dan di mana saja.</p>
                    </div>
                    
                    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($layanan_digital as $layanan): ?>
                            <a class="block p-8 bg-card-light dark:bg-card-dark rounded-xl shadow-md text-center transition-transform hover:-translate-y-1" 
                               href="<?= esc($layanan['url']) ?>">
                                <div class="flex justify-center">
                                    <div class="flex items-center justify-center size-16 rounded-full bg-accent/20 text-accent">
                                        <span class="material-symbols-outlined text-4xl"><?= esc($layanan['icon']) ?></span>
                                    </div>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold"><?= esc($layanan['title']) ?></h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400"><?= esc($layanan['description']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://github.hubspot.com/odometer/themes/odometer-theme-default.css" />
<style>
/* Odometer Custom Styling */
.odometer {
    font-family: inherit;
    font-weight: 700;
}

.odometer.odometer-auto-theme, .odometer.odometer-theme-default {
    display: inline-block;
    vertical-align: middle;
    position: relative;
}

.odometer .odometer-digit {
    display: inline-block;
    vertical-align: middle;
    position: relative;
}

.odometer .odometer-digit .odometer-digit-spacer {
    display: inline-block;
    vertical-align: middle;
    visibility: hidden;
}

.odometer .odometer-digit .odometer-digit-inner {
    text-align: left;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

.odometer .odometer-digit .odometer-ribbon {
    display: block;
}

.odometer .odometer-digit .odometer-ribbon-inner {
    display: block;
    backface-visibility: hidden;
}

.odometer .odometer-digit .odometer-value {
    display: block;
    transform: translateZ(0);
}

.odometer .odometer-digit .odometer-value.odometer-last-value {
    position: absolute;
}

.odometer.odometer-animating-up .odometer-ribbon-inner {
    transition: transform 0.5s;
}

.odometer.odometer-animating-down .odometer-ribbon-inner {
    transform: translateY(-100%);
}

.odometer.odometer-animating-down.odometer-animating .odometer-ribbon-inner {
    transition: transform 0.5s;
    transform: translateY(0);
}

/* AOS Animations */
[data-aos] {
    opacity: 0;
    transition-property: opacity, transform;
}

[data-aos].aos-animate {
    opacity: 1;
}

[data-aos="fade-up"] {
    transform: translate3d(0, 40px, 0);
}

[data-aos="fade-up"].aos-animate {
    transform: translate3d(0, 0, 0);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://github.hubspot.com/odometer/odometer.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 120
    });

    // Odometer Animation for Statistics
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const odometers = entry.target.querySelectorAll('.odometer');
                odometers.forEach((odometer, index) => {
                    if (!odometer.classList.contains('animated')) {
                        odometer.classList.add('animated');
                        const targetValue = odometer.dataset.target;
                        const suffix = odometer.dataset.suffix || '';
                        
                        // Set the target value with a staggered delay for better effect
                        setTimeout(() => {
                            // Handle different number formats
                            if (targetValue === '125') {
                                // Special case for area (12.5 km²)
                                let current = 0;
                                const target = 125;
                                const increment = target / 50; // 50 steps
                                const interval = setInterval(() => {
                                    current += increment;
                                    if (current >= target) {
                                        current = target;
                                        clearInterval(interval);
                                        odometer.innerHTML = '12.5' + suffix;
                                    } else {
                                        const display = (current / 10).toFixed(1);
                                        odometer.innerHTML = display;
                                    }
                                }, 20);
                            } else if (targetValue === '8540') {
                                // Population with comma formatting
                                odometer.innerHTML = targetValue;
                                setTimeout(() => {
                                    odometer.innerHTML = '8,540' + suffix;
                                }, 500);
                            } else if (targetValue === '2750') {
                                // KK with comma formatting  
                                odometer.innerHTML = targetValue;
                                setTimeout(() => {
                                    odometer.innerHTML = '2,750' + suffix;
                                }, 500);
                            } else if (parseInt(targetValue) >= 1000) {
                                // Numbers >= 1000 with comma formatting
                                odometer.innerHTML = targetValue;
                                setTimeout(() => {
                                    const formatted = parseInt(targetValue).toLocaleString('id-ID');
                                    odometer.innerHTML = formatted + suffix;
                                }, 500);
                            } else {
                                // Simple numbers
                                odometer.innerHTML = targetValue + suffix;
                            }
                        }, 300 + (index * 200)); // Staggered animation
                    }
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe statistics section
    const statsSection = document.querySelector('.stat-card').closest('section');
    if (statsSection) {
        observer.observe(statsSection);
    }

    // Carousel functionality
    const carousel = document.getElementById('hero-carousel');
    if (!carousel) return;
    
    const slides = carousel.querySelectorAll('.carousel-slide');
    const indicators = carousel.querySelectorAll('.carousel-indicator');
    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    
    let currentSlide = 0;
    let autoplayInterval;
    
    console.log('Carousel initialized with', slides.length, 'slides');
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('bg-white', i === index);
            indicator.classList.toggle('bg-white/50', i !== index);
        });
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    // Auto-play
    function startAutoplay() {
        autoplayInterval = setInterval(nextSlide, 5000);
    }
    
    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }
    
    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', () => { stopAutoplay(); nextSlide(); startAutoplay(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { stopAutoplay(); prevSlide(); startAutoplay(); });
    
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            stopAutoplay();
            showSlide(index);
            startAutoplay();
        });
    });
    
    carousel.addEventListener('mouseenter', stopAutoplay);
    carousel.addEventListener('mouseleave', startAutoplay);
    
    // Initialize
    showSlide(0);
    startAutoplay();
});
</script>
<?= $this->endSection() ?>