<!-- Main Navbar -->
<header class="sticky top-0 w-full bg-card-light/80 dark:bg-card-dark/80 backdrop-blur-sm border-b border-border-light dark:border-border-dark shadow-sm" style="outline: none !important; z-index: 9999 !important; position: sticky !important;">
    <div class="container mx-auto px-4">
        <div class="flex h-20 items-center justify-between">
            <!-- Logo -->
            <a href="<?= base_url() ?>" class="flex items-center gap-3 hover:opacity-80 transition-opacity" style="outline: none !important; box-shadow: none !important;">
                <img src="<?= base_url('images/carousel/logo.png') ?>" alt="Logo Desa Blanakan" class="h-8 w-8 object-contain">
                <h1 class="text-xl font-bold text-text-light dark:text-text-dark">Desa Blanakan</h1>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8" style="outline: none !important;">
                <a class="navbar-link text-base font-medium <?= (uri_string() == '') ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url() ?>" style="outline: none !important; box-shadow: none !important;">
                    Beranda
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'profil') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('profil') ?>" style="outline: none !important; box-shadow: none !important;">
                    Profil Desa
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'data-desa') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('data-desa') ?>" style="outline: none !important; box-shadow: none !important;">
                    Data Desa
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'budaya-wisata') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('budaya-wisata') ?>" style="outline: none !important; box-shadow: none !important;">
                    Budaya & Pariwisata
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'galeri') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('galeri') ?>" style="outline: none !important; box-shadow: none !important;">
                    Galeri
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'layanan-online') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('layanan-online') ?>" style="outline: none !important; box-shadow: none !important;">
                    Layanan Online
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'berita') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('berita') ?>" style="outline: none !important; box-shadow: none !important;">
                    Berita & Pengumuman
                </a>
                <a class="navbar-link text-base font-medium <?= (strpos(uri_string(), 'kontak') !== false) ? 'text-primary' : 'transition hover:text-primary' ?>" href="<?= base_url('kontak') ?>" style="outline: none !important; box-shadow: none !important;">
                    Kontak
                </a>
            </nav>
            
            <!-- User Actions -->
            <div class="flex items-center gap-4">
                <?php if (session()->get('logged_in')): ?>
                    <!-- Notifikasi untuk Warga -->
                    <?php if (session()->get('role') === 'warga'): ?>
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" class="navbar-button relative p-2 rounded-lg text-gray-600 hover:text-primary hover:bg-primary/10" style="outline: none !important; box-shadow: none !important; border: none !important;">
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
                        <button @click="userMenuOpen = !userMenuOpen" class="navbar-button flex items-center gap-2 p-2 rounded-lg hover:bg-primary/10" style="outline: none !important; box-shadow: none !important; border: none !important;">
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
                    <button 
                        @click="$store.modal.openLogin()" 
                        type="button"
                        class="navbar-button flex min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] transition hover:bg-primary/90" 
                        style="outline: none !important; box-shadow: none !important; border: none !important; z-index: 10000; position: relative;">
                        <span class="truncate">Login</span>
                    </button>
                <?php endif; ?>
                
                <!-- Mobile Menu Toggle -->
                <button class="navbar-button lg:hidden p-2 rounded-lg hover:bg-primary/10" onclick="toggleMobileMenu()" style="outline: none !important; box-shadow: none !important; border: none !important;">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark" style="outline: none !important;">
            <nav class="px-4 py-4 space-y-2" style="outline: none !important;">
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (uri_string() == '') ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url() ?>" style="outline: none !important; box-shadow: none !important;">
                    Beranda
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'profil') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('profil') ?>" style="outline: none !important; box-shadow: none !important;">
                    Profil Desa
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'data-desa') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('data-desa') ?>" style="outline: none !important; box-shadow: none !important;">
                    Data Desa
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'budaya-wisata') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('budaya-wisata') ?>" style="outline: none !important; box-shadow: none !important;">
                    Budaya & Pariwisata
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'galeri') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('galeri') ?>" style="outline: none !important; box-shadow: none !important;">
                    Galeri
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'layanan-online') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('layanan-online') ?>" style="outline: none !important; box-shadow: none !important;">
                    Layanan Online
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'berita') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('berita') ?>" style="outline: none !important; box-shadow: none !important;">
                    Berita & Pengumuman
                </a>
                <a class="navbar-link block px-4 py-2 rounded-lg text-base font-medium <?= (strpos(uri_string(), 'kontak') !== false) ? 'text-primary bg-primary/10' : 'hover:text-primary hover:bg-primary/10' ?>" href="<?= base_url('kontak') ?>" style="outline: none !important; box-shadow: none !important;">
                    Kontak
                </a>
            </nav>
        </div>
    </div>
</header>