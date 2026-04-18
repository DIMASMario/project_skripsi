<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Website Desa Blanakan' ?></title>
    <meta name="description" content="Portal digital Desa Blanakan - Layanan online, informasi terkini, dan transparansi pemerintahan desa."/>
    <meta name="keywords" content="desa blanakan, pelayanan digital, surat online, pemerintah desa"/>
    <meta name="robots" content="index, follow"/>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('images/carousel/logo.png') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <!-- Main CSS -->
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
    
    <!-- Layout specific styles moved to external CSS -->
    
    <!-- Alpine.js (loaded at end of body) -->
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <div @keydown.escape.window="loginModalOpen = false" class="relative w-full overflow-x-hidden" x-data="{ loginModalOpen: false }">
        
        <!-- Header -->
        <header class="sticky top-0 z-50 w-full bg-card-light/80 dark:bg-card-dark/80 backdrop-blur-sm border-b border-border-light dark:border-border-dark shadow-sm">
            <div class="container mx-auto px-4">
                <div class="flex h-20 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="<?= base_url('images/carousel/logo.png') ?>" alt="Logo Desa Blanakan" class="h-12 w-12">
                        <h1 class="text-xl font-bold text-text-light dark:text-text-dark">Desa Blanakan</h1>
                    </div>
                    
                    <nav class="hidden lg:flex items-center gap-8">
                        <a class="text-base font-medium <?= (current_url(true)->getPath() == '/') ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url() ?>">Beranda</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'profil') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('profil') ?>">Profil Desa</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'data-desa') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('data-desa') ?>">Data Desa</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'budaya-wisata') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('budaya-wisata') ?>">Budaya & Pariwisata</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'galeri') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('galeri') ?>">Galeri</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'layanan-online') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('layanan-online') ?>">Layanan Online</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'berita') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('berita') ?>">Berita & Pengumuman</a>
                        <a class="text-base font-medium <?= (strpos(current_url(), 'kontak') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('kontak') ?>">Kontak</a>
                    </nav>
                    
                    <div class="flex items-center gap-4">
                        <?php if (session()->get('logged_in')): ?>
                            <!-- Notifikasi untuk Warga -->
                            <?php if (session()->get('role') === 'warga'): ?>
                            <div class="relative" x-data="{ notifOpen: false }">
                                <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-lg text-gray-600 hover:text-primary hover:bg-primary/10">
                                    <span class="material-symbols-outlined text-2xl">notifications</span>
                                    <span class="absolute top-1 right-1 size-2 bg-red-500 rounded-full" id="notifikasi-indicator" style="display: none;"></span>
                                </button>
                                
                                <div x-show="notifOpen" @click.outside="notifOpen = false" x-cloak
                                     class="absolute right-0 mt-2 w-80 bg-card-light dark:bg-card-dark rounded-lg shadow-xl border border-border-light dark:border-border-dark z-50">
                                    <div class="p-4 border-b border-border-light dark:border-border-dark">
                                        <h3 class="font-semibold text-text-light dark:text-text-dark">Notifikasi</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto" id="notifikasi-list">
                                        <div class="p-4 text-center text-gray-500">Memuat notifikasi...</div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ userMenuOpen: false }">
                                <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 p-2 rounded-lg hover:bg-primary/10">
                                    <span class="material-symbols-outlined text-2xl">account_circle</span>
                                    <span class="hidden sm:block font-medium"><?= session()->get('nama_lengkap') ?></span>
                                    <span class="material-symbols-outlined text-sm">expand_more</span>
                                </button>
                                
                                <div x-show="userMenuOpen" @click.outside="userMenuOpen = false" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-card-light dark:bg-card-dark rounded-lg shadow-xl border border-border-light dark:border-border-dark z-50">
                                    <a href="<?= base_url('dashboard') ?>" class="block px-4 py-2 text-sm hover:bg-primary/10 rounded-t-lg">Dashboard</a>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                        <a href="<?= base_url('admin') ?>" class="block px-4 py-2 text-sm hover:bg-primary/10">Admin Panel</a>
                                    <?php endif; ?>
                                    <div class="border-t border-border-light dark:border-border-dark"></div>
                                    <a href="<?= base_url('auth/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg">Logout</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <button @click="loginModalOpen = true" class="flex min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] transition hover:bg-primary/90">
                                <span class="truncate">Login</span>
                            </button>
                        <?php endif; ?>
                        
                        <button class="lg:hidden p-2 rounded-lg hover:bg-primary/10" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="hidden lg:hidden bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark">
                    <nav class="px-4 py-4 space-y-2">
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (current_url(true)->getPath() == '/') ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url() ?>">
                            Beranda
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'profil') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('profil') ?>">
                            Profil Desa
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'data-desa') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('data-desa') ?>">
                            Data Desa
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'budaya-wisata') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('budaya-wisata') ?>">
                            Budaya & Pariwisata
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'galeri') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('galeri') ?>">
                            Galeri
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'layanan-online') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('layanan-online') ?>">
                            Layanan Online
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'berita') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('berita') ?>">
                            Berita & Pengumuman
                        </a>
                        <a class="block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(current_url(), 'kontak') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('kontak') ?>">
                            Kontak
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <?= $this->renderSection('content') ?>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white dark:bg-card-dark">
            <div class="container mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="size-8 text-white">
                                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill-rule="evenodd"></path>
                                    <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold">Desa Blanakan</h2>
                        </div>
                        <p class="mt-4 text-gray-400">Jl. Raya Blanakan No. 123, Kecamatan Blanakan, Kabupaten Subang, Jawa Barat 41259</p>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold">Tautan Cepat</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a class="text-gray-400 hover:text-white" href="<?= base_url('profil') ?>">Profil Desa</a></li>
                            <li><a class="text-gray-400 hover:text-white" href="<?= base_url('profil#struktur-organisasi') ?>">Struktur Organisasi</a></li>
                            <li><a class="text-gray-400 hover:text-white" href="<?= base_url('profil#visi-misi') ?>">Visi & Misi</a></li>
                            <li><a class="text-gray-400 hover:text-white" href="<?= base_url('data-desa') ?>">Data Desa</a></li>
                            <li><a class="text-gray-400 hover:text-white" href="<?= base_url('layanan-online') ?>">Layanan Online</a></li>
                            <li><a class="text-gray-400 hover:text-white" href="<?= base_url('berita') ?>">Berita Terkini</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold">Kontak Kami</h3>
                        <ul class="mt-4 space-y-2">
                            <li class="flex items-center gap-2 text-gray-400">
                                <span class="material-symbols-outlined text-xl">call</span> 
                                (0260) 123-456
                            </li>
                            <li class="flex items-center gap-2 text-gray-400">
                                <span class="material-symbols-outlined text-xl">mail</span> 
                                kontak@blanakan.desa.id
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold">Media Sosial</h3>
                        <div class="flex mt-4 gap-4">
                            <a class="text-gray-400 hover:text-white" href="#" target="_blank" rel="noopener">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                                </svg>
                            </a>
                            <a class="text-gray-400 hover:text-white" href="#" target="_blank" rel="noopener">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919 1.266-.057 1.644-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.059-1.281.073-1.689.073-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"></path>
                                </svg>
                            </a>
                            <a class="text-gray-400 hover:text-white" href="#" target="_blank" rel="noopener">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 border-t border-gray-700 pt-8 text-center text-gray-500">
                    <p>© <?= date('Y') ?> Pemerintah Desa Blanakan. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>

        <!-- Login Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" 
             x-cloak x-show="loginModalOpen" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0">
             
            <div @click.outside="loginModalOpen = false" 
                 class="relative w-full max-w-md bg-card-light dark:bg-card-dark rounded-xl shadow-2xl p-8 text-center" 
                 x-show="loginModalOpen" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-95">
                 
                <button @click="loginModalOpen = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
                
                <h2 class="text-2xl font-bold text-text-light dark:text-text-dark">Pilih Tipe Login</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Silakan pilih jenis akun untuk masuk ke sistem.</p>
                
                <div class="mt-8 space-y-4">
                    <a class="group flex items-center w-full p-4 border border-border-light dark:border-border-dark rounded-lg hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors" 
                       href="<?= base_url('auth/login') ?>">
                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white dark:bg-primary/20 dark:group-hover:bg-primary transition-colors">
                            <span class="material-symbols-outlined text-3xl">groups</span>
                        </div>
                        <div class="ml-4 text-left">
                            <p class="text-lg font-semibold text-text-light dark:text-text-dark">Login sebagai Warga</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Untuk mengakses layanan digital desa.</p>
                        </div>
                    </a>
                    
                    <a class="group flex items-center w-full p-4 border border-border-light dark:border-border-dark rounded-lg hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors" 
                       href="<?= base_url('auth/admin-login') ?>">
                        <div class="flex items-center justify-center size-12 rounded-lg bg-accent/20 text-accent group-hover:bg-accent group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                        </div>
                        <div class="ml-4 text-left">
                            <p class="text-lg font-semibold text-text-light dark:text-text-dark">Login sebagai Admin</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Untuk mengelola website & layanan.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Set base URL for JavaScript -->
    <script>
        window.BASE_URL = '<?= base_url() ?>';
    </script>

    <!-- User role data for JavaScript -->
    <?php if (session()->get('logged_in')): ?>
    <div style="display: none;" data-user-role="<?= session()->get('role') ?>"></div>
    <?php endif; ?>

    <!-- Main JavaScript -->
    <script src="<?= base_url('js/app.js') ?>"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>