<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $this->renderSection('title') ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#005c99",
                        "background-light": "#f5f7f8",
                        "background-dark": "#0f1b23",
                        pending: "#FBBF24",
                        processing: "#3B82F6",
                        completed: "#10B981",
                        rejected: "#EF4444"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    }
                }
            }
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js -->
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="relative flex min-h-screen w-full" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar -->
        <aside class="flex flex-col w-64 bg-white dark:bg-gray-900/50 dark:border-r dark:border-gray-800 p-4 sticky top-0 h-screen shrink-0 hidden lg:flex">
            <div class="flex flex-col gap-4">
                <!-- User Profile -->
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 bg-primary text-white flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-gray-800 dark:text-white text-base font-semibold leading-normal">
                            <?= esc(session()->get('nama_lengkap') ?? 'User') ?>
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                            <?= esc(session()->get('email') ?? 'user@email.com') ?>
                        </p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex flex-col gap-2 mt-4">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= uri_string() === 'dashboard' ? 'bg-primary/20 dark:bg-primary/30' : 'hover:bg-primary/10' ?> transition-colors" 
                       href="<?= base_url('dashboard') ?>">
                        <span class="material-symbols-outlined <?= uri_string() === 'dashboard' ? 'text-primary dark:text-white' : 'text-gray-700 dark:text-gray-300' ?>" <?= uri_string() === 'dashboard' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>dashboard</span>
                        <p class="<?= uri_string() === 'dashboard' ? 'text-primary dark:text-white font-semibold' : 'text-gray-800 dark:text-gray-200 font-medium' ?> text-sm leading-normal">Dashboard</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" 
                       href="<?= base_url('layanan-online') ?>">
                        <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">add_box</span>
                        <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Buat Pengajuan</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= strpos(uri_string(), 'riwayat-surat') !== false ? 'bg-primary/20 dark:bg-primary/30' : 'hover:bg-primary/10' ?> transition-colors" 
                       href="<?= base_url('dashboard/riwayat-surat') ?>">
                        <span class="material-symbols-outlined <?= strpos(uri_string(), 'riwayat-surat') !== false ? 'text-primary dark:text-white' : 'text-gray-700 dark:text-gray-300' ?>" <?= strpos(uri_string(), 'riwayat-surat') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>history</span>
                        <p class="<?= strpos(uri_string(), 'riwayat-surat') !== false ? 'text-primary dark:text-white font-semibold' : 'text-gray-800 dark:text-gray-200 font-medium' ?> text-sm leading-normal">Riwayat Pengajuan</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= strpos(uri_string(), 'profil') !== false ? 'bg-primary/20 dark:bg-primary/30' : 'hover:bg-primary/10' ?> transition-colors" 
                       href="<?= base_url('dashboard/profil') ?>">
                        <span class="material-symbols-outlined <?= strpos(uri_string(), 'profil') !== false ? 'text-primary dark:text-white' : 'text-gray-700 dark:text-gray-300' ?>" <?= strpos(uri_string(), 'profil') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>person</span>
                        <p class="<?= strpos(uri_string(), 'profil') !== false ? 'text-primary dark:text-white font-semibold' : 'text-gray-800 dark:text-gray-200 font-medium' ?> text-sm leading-normal">Profil Saya</p>
                    </a>
                </nav>
            </div>
            
            <!-- Bottom Actions -->
            <div class="mt-auto flex flex-col gap-1">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" 
                   href="<?= base_url() ?>">
                    <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">home</span>
                    <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Kembali ke Website</p>
                </a>
                
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/10 transition-colors" 
                   href="<?= base_url('auth/logout') ?>"
                   onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-500">logout</span>
                    <p class="text-red-600 dark:text-red-500 text-sm font-medium leading-normal">Keluar</p>
                </a>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
             @click="sidebarOpen = false">
        </div>

        <!-- Mobile Sidebar -->
        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-in-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed top-0 left-0 z-50 w-64 h-full bg-white dark:bg-gray-900 p-4 lg:hidden">
            <!-- Same content as desktop sidebar -->
            <div class="flex flex-col gap-4 h-full">
                <!-- Mobile close button -->
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Menu</h2>
                    <button @click="sidebarOpen = false" class="p-1 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">close</span>
                    </button>
                </div>
                
                <!-- User Profile -->
                <div class="flex items-center gap-3 px-2 py-2 border-b border-gray-200 dark:border-gray-700">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 bg-primary text-white flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-gray-800 dark:text-white text-base font-semibold leading-normal">
                            <?= esc(session()->get('nama_lengkap') ?? 'User') ?>
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                            <?= esc(session()->get('email') ?? 'user@email.com') ?>
                        </p>
                    </div>
                </div>
                
                <!-- Navigation (same as desktop) -->
                <nav class="flex flex-col gap-2 flex-grow">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= uri_string() === 'dashboard' ? 'bg-primary/20 dark:bg-primary/30' : 'hover:bg-primary/10' ?> transition-colors" 
                       href="<?= base_url('dashboard') ?>" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined <?= uri_string() === 'dashboard' ? 'text-primary dark:text-white' : 'text-gray-700 dark:text-gray-300' ?>" <?= uri_string() === 'dashboard' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>dashboard</span>
                        <p class="<?= uri_string() === 'dashboard' ? 'text-primary dark:text-white font-semibold' : 'text-gray-800 dark:text-gray-200 font-medium' ?> text-sm leading-normal">Dashboard</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" 
                       href="<?= base_url('layanan-online') ?>" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">add_box</span>
                        <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Buat Pengajuan</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= strpos(uri_string(), 'riwayat-surat') !== false ? 'bg-primary/20 dark:bg-primary/30' : 'hover:bg-primary/10' ?> transition-colors" 
                       href="<?= base_url('dashboard/riwayat-surat') ?>" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined <?= strpos(uri_string(), 'riwayat-surat') !== false ? 'text-primary dark:text-white' : 'text-gray-700 dark:text-gray-300' ?>" <?= strpos(uri_string(), 'riwayat-surat') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>history</span>
                        <p class="<?= strpos(uri_string(), 'riwayat-surat') !== false ? 'text-primary dark:text-white font-semibold' : 'text-gray-800 dark:text-gray-200 font-medium' ?> text-sm leading-normal">Riwayat Pengajuan</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= strpos(uri_string(), 'profil') !== false ? 'bg-primary/20 dark:bg-primary/30' : 'hover:bg-primary/10' ?> transition-colors" 
                       href="<?= base_url('dashboard/profil') ?>" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined <?= strpos(uri_string(), 'profil') !== false ? 'text-primary dark:text-white' : 'text-gray-700 dark:text-gray-300' ?>" <?= strpos(uri_string(), 'profil') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>person</span>
                        <p class="<?= strpos(uri_string(), 'profil') !== false ? 'text-primary dark:text-white font-semibold' : 'text-gray-800 dark:text-gray-200 font-medium' ?> text-sm leading-normal">Profil Saya</p>
                    </a>
                </nav>
                
                <!-- Bottom Actions -->
                <div class="flex flex-col gap-1 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" 
                       href="<?= base_url() ?>" @click="sidebarOpen = false">
                        <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">home</span>
                        <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Kembali ke Website</p>
                    </a>
                    
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/10 transition-colors" 
                       href="<?= base_url('auth/logout') ?>"
                       onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-500">logout</span>
                        <p class="text-red-600 dark:text-red-500 text-sm font-medium leading-normal">Keluar</p>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:p-6 p-4">
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-800">
                <button @click="sidebarOpen = true" 
                        class="p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">menu</span>
                </button>
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-white">Dashboard Warga</h1>
                </div>
                <div class="w-10"></div> <!-- Spacer for centering -->
            </div>

            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Set base URL for JavaScript -->
    <script>
        window.BASE_URL = '<?= base_url() ?>';
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>