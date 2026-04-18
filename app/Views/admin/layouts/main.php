<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= $title ?? 'Dashboard Admin - Pelayanan Digital Desa Blanakan' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Bootstrap JS (only for Modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005c99",
                        "background-light": "#f5f7f8",
                        "background-dark": "#0f1b23",
                        "accent": "#FFD700"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem", 
                        "lg": "0.5rem", 
                        "xl": "0.75rem", 
                        "full": "9999px"
                    },
                },
            },
        }
        
        // Global error handler for debugging
        window.addEventListener('error', function(e) {
            console.error('JavaScript Error:', e.error);
            console.error('File:', e.filename, 'Line:', e.lineno);
        });
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Sidebar Menu Styles */
        .menu-group {
            margin-bottom: 2px;
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            margin-left: 1.5rem;
            margin-top: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .submenu.open {
            max-height: 500px !important;
            opacity: 1 !important;
        }
        
        .submenu-link {
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 2px solid transparent;
            padding-left: 0.75rem !important;
        }
        
        .submenu-link:hover {
            border-left-color: #005c99;
            background-color: rgba(0, 92, 153, 0.05);
            transform: translateX(2px);
        }
        
        .submenu-link.text-primary {
            background-color: #eff6ff !important;
            color: #005c99 !important;
            font-weight: 500 !important;
            border-left-color: #005c99 !important;
        }
        
        .dark .submenu-link.text-primary {
            background-color: rgba(59, 130, 246, 0.1) !important;
            color: #60a5fa !important;
        }
        
        .material-symbols-outlined {
            user-select: none;
        }
        
        .submenu a.active {
            background-color: #005c99 !important;
            color: white !important;
        }
        
        .submenu a {
            cursor: pointer;
        }
        
        .sidebar-mobile {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar-mobile.show {
            transform: translateX(0);
        }
        
        @media (min-width: 1024px) {
            .sidebar-mobile {
                transform: translateX(0) !important;
            }
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#333333] dark:text-gray-200">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 flex-shrink-0 bg-white dark:bg-gray-900/50 flex flex-col fixed h-screen z-30 overflow-y-auto sidebar-mobile border-r border-gray-200 dark:border-gray-800 lg:translate-x-0">
            <!-- Logo & Brand -->
            <div class="flex flex-col gap-4 p-4 flex-shrink-0">
                <div class="flex items-center gap-3 p-2">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" 
                         style='background-image: url("<?= base_url('images/carousel/logo.png') ?>");'></div>
                    <div class="flex flex-col">
                        <h1 class="text-base font-bold text-gray-800 dark:text-white leading-normal">Desa Blanakan</h1>
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 leading-normal">Admin Panel</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex flex-col gap-1 mt-4 flex-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= getMenuActiveClass('admin', true) ?>" 
                       href="<?= base_url('admin') ?>">
                        <span class="material-symbols-outlined text-lg">dashboard</span>
                        <p class="text-sm font-medium leading-normal">Dashboard</p>
                    </a>
                    
                    <!-- Manajemen Warga -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('warga-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">group</span>
                                <p class="text-sm font-medium leading-normal">Manajemen Warga</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="warga-arrow">expand_more</span>
                        </button>
                        <div id="warga-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('warga') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/users?status=pending') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/users?status=pending') ?>">
                               <span class="material-symbols-outlined text-xs">schedule</span>
                               User Pending Verifikasi
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/users') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/users') ?>">
                               <span class="material-symbols-outlined text-xs">groups</span>
                               Daftar Warga
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/users?status=suspended') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/users?status=suspended') ?>">
                               <span class="material-symbols-outlined text-xs">block</span>
                               User Suspend
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/user-activity') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/user-activity') ?>">
                               <span class="material-symbols-outlined text-xs">history</span>
                               User Activity Logs
                            </a>
                        </div>
                    </div>
                    
                    <!-- Manajemen Surat -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('surat-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">description</span>
                                <p class="text-sm font-medium leading-normal">Manajemen Surat</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="surat-arrow">expand_more</span>
                        </button>
                        <div id="surat-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('surat') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/surat?status=pending') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/surat?status=pending') ?>">
                               <span class="material-symbols-outlined text-xs">inbox</span>
                               Surat Masuk
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/surat?status=diproses') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/surat?status=diproses') ?>">
                               <span class="material-symbols-outlined text-xs">pending</span>
                               Surat Diproses
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/surat?status=selesai') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/surat?status=selesai') ?>">
                               <span class="material-symbols-outlined text-xs">check_circle</span>
                               Surat Selesai
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/surat?status=ditolak') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/surat?status=ditolak') ?>">
                               <span class="material-symbols-outlined text-xs">cancel</span>
                               Surat Ditolak
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/surat-template') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/surat-template') ?>">
                               <span class="material-symbols-outlined text-xs">description</span>
                               Template Surat
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/surat-arsip') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/surat-arsip') ?>">
                               <span class="material-symbols-outlined text-xs">folder</span>
                               Arsip Surat
                            </a>
                        </div>
                    </div>
                    
                    <!-- Konten Website -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('konten-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">web</span>
                                <p class="text-sm font-medium leading-normal">Konten Website</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="konten-arrow">expand_more</span>
                        </button>
                        <div id="konten-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('konten') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/berita') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/berita') ?>">
                                <span class="material-symbols-outlined text-base">article</span>
                                Berita
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/galeri') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/galeri') ?>">
                                <span class="material-symbols-outlined text-base">photo_library</span>
                                Galeri
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/pengumuman') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/pengumuman') ?>">
                                <span class="material-symbols-outlined text-base">campaign</span>
                                Pengumuman
                            </a>
                        </div>
                    </div>
                    
                    <!-- Data & Analytics -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('analytics-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">analytics</span>
                                <p class="text-sm font-medium leading-normal">Data & Analytics</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="analytics-arrow">expand_more</span>
                        </button>
                        <div id="analytics-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('statistik') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getStatistikActiveClass('admin/statistik') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/statistik') ?>">
                                <span class="material-symbols-outlined text-base">dashboard</span>
                                Data Statistik Desa
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getStatistikActiveClass('admin/statistik/surat') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/statistik/surat') ?>">
                                <span class="material-symbols-outlined text-base">mail</span>
                                Statistik Surat
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getStatistikActiveClass('admin/statistik/user') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/statistik/user') ?>">
                                <span class="material-symbols-outlined text-base">group</span>
                                Statistik User
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getStatistikActiveClass('admin/statistik/traffic') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/statistik/traffic') ?>">
                                <span class="material-symbols-outlined text-base">monitoring</span>
                                Traffic Website
                            </a>
                        </div>
                    </div>
                    
                    <!-- Konfigurasi Sistem -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('config-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">settings</span>
                                <p class="text-sm font-medium leading-normal">Konfigurasi Sistem</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="config-arrow">expand_more</span>
                        </button>
                        <div id="config-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('config') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/settings') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/settings') ?>">
                                <span class="material-symbols-outlined text-lg">location_city</span>
                                <span>Profil Desa</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/config') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/config') ?>">
                                <span class="material-symbols-outlined text-lg">tune</span>
                                <span>Konfigurasi Umum</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/config-email') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/config-email') ?>">
                                <span class="material-symbols-outlined text-lg">mail</span>
                                <span>Konfigurasi Email</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/config-payment') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/config-payment') ?>">
                                <span class="material-symbols-outlined text-lg">payments</span>
                                <span>Konfigurasi Payment</span>
                            </a>
                        </div>
                    </div>

                    <!-- Keamanan -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('security-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">security</span>
                                <p class="text-sm font-medium leading-normal">Keamanan</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="security-arrow">expand_more</span>
                        </button>
                        <div id="security-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('security') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/roles') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/roles') ?>">
                                <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                <span>Manajemen Role</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/security') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/security') ?>">
                                <span class="material-symbols-outlined text-lg">shield</span>
                                <span>Setting Keamanan</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/login-attempts') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/login-attempts') ?>">
                                <span class="material-symbols-outlined text-lg">login</span>
                                <span>Login Attempts</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/security-logs') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/security-logs') ?>">
                                <span class="material-symbols-outlined text-lg">description</span>
                                <span>Security Logs</span>
                            </a>
                        </div>
                    </div>

                    <!-- Maintenance -->
                    <div class="menu-group">
                        <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                                onclick="toggleSubmenu('maintenance-menu', event)">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-lg">build</span>
                                <p class="text-sm font-medium leading-normal">Maintenance</p>
                            </div>
                            <span class="material-symbols-outlined text-sm transform transition-transform" id="maintenance-arrow">expand_more</span>
                        </button>
                        <div id="maintenance-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('maintenance') ? 'open' : '' ?>">
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/backup') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/backup') ?>">
                                <span class="material-symbols-outlined text-lg">backup</span>
                                <span>Backup Database</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/logs') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/logs') ?>">
                                <span class="material-symbols-outlined text-lg">article</span>
                                <span>System Logs</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/cache') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/cache') ?>">
                                <span class="material-symbols-outlined text-lg">delete_sweep</span>
                                <span>Cache Management</span>
                            </a>
                            <a class="submenu-link flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/system-info') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                               href="<?= base_url('admin/system-info') ?>">
                                <span class="material-symbols-outlined text-lg">info</span>
                                <span>System Info</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Notifikasi Admin -->
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg <?= getMenuActiveClass('admin/notifikasi', true) ?>" 
                       href="<?= base_url('admin/notifikasi') ?>">
                        <span class="material-symbols-outlined text-lg">notifications</span>
                        <p class="text-sm font-medium leading-normal">Notifikasi Admin</p>
                    </a>
                </nav>
            </div>
            
            <!-- Bottom Menu -->
            <div class="flex flex-col gap-1 p-4 border-t border-gray-200 dark:border-gray-800 mt-auto flex-shrink-0">
                <!-- Lihat Website -->
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-600 dark:text-blue-400 transition-colors" 
                   href="<?= base_url('/') ?>" 
                   target="_blank">
                    <span class="material-symbols-outlined text-lg">open_in_new</span>
                    <p class="text-sm font-medium leading-normal">Lihat Website</p>
                </a>
                
                <!-- Admin Panel -->
                <div class="menu-group">
                    <button class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300" 
                            onclick="toggleSubmenu('admin-menu', event)">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                            <p class="text-sm font-medium leading-normal">Admin Panel</p>
                        </div>
                        <span class="material-symbols-outlined text-sm transform transition-transform" id="admin-arrow">expand_more</span>
                    </button>
                    <div id="admin-menu" class="submenu ml-6 mt-1 <?= shouldSubmenuOpen('admin') ? 'open' : '' ?>">
                        <a class="submenu-link block px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/profil') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                           href="<?= base_url('admin/profil') ?>">Edit Profil Admin</a>
                        <a class="submenu-link block px-3 py-2 text-sm rounded-lg transition-all duration-200 <?= getSubmenuActiveClass('admin/role-management') ?: 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>" 
                           href="<?= base_url('admin/role-management') ?>">Manajemen Role Admin</a>
                    </div>
                </div>
                
                <!-- Logout -->
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors" 
                   href="<?= base_url('auth/logout') ?>" 
                   onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    <p class="text-sm font-medium leading-normal">Logout</p>
                </a>
            </div>
        </aside>
        
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col w-full lg:ml-64 min-h-screen">
            <!-- Header -->
            <header class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-background-dark sticky top-0 z-10">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden p-2 text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                
                <!-- Breadcrumbs -->
                <div class="hidden sm:flex flex-wrap gap-2">
                    <a class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal" href="<?= base_url('admin') ?>">Admin</a>
                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">/</span>
                    <span class="text-gray-800 dark:text-white text-sm font-medium leading-normal"><?= $breadcrumb ?? 'Dashboard' ?></span>
                </div>
                
                <!-- Toolbar -->
                <div class="flex items-center gap-2">
                    <!-- Notification Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-600 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 relative">
                            <span class="material-symbols-outlined">notifications</span>
                            <?php
                            $notifModel = new \App\Models\NotifikasiModel();
                            $unreadCount = $notifModel->countNotifikasiAdminBelumBaca();
                            ?>
                            <span id="notif-badge" class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center <?= $unreadCount > 0 ? '' : 'hidden' ?>"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="open" 
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                             style="display: none;">
                            <div class="p-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                                <?php if ($unreadCount > 0): ?>
                                    <button onclick="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Tandai semua dibaca
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div id="notif-list" class="max-h-96 overflow-y-auto">
                                <?php
                                // Ambil SEMUA notifikasi (belum dibaca dan sudah dibaca)
                                $notifications = $notifModel->where('tipe', 'admin')
                                                           ->orderBy('created_at', 'DESC')
                                                           ->limit(10)
                                                           ->findAll();
                                if (empty($notifications)):
                                ?>
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <span class="material-symbols-outlined text-4xl mb-2">notifications_off</span>
                                        <p class="text-sm">Tidak ada notifikasi</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($notifications as $notif): ?>
                                        <?php 
                                        $isUnread = $notif['status'] === 'belum_dibaca'; 
                                        
                                        // FIX: Dynamic redirect based on notification type
                                        if (!empty($notif['surat_id'])) {
                                            // Notifikasi tentang surat → redirect ke manajemen surat
                                            $redirectUrl = base_url('admin/surat?status=pending');
                                            $iconName = 'mail';
                                            $iconColor = 'blue';
                                        } else {
                                            // Notifikasi tentang user baru → redirect ke manajemen user
                                            $redirectUrl = base_url('admin/users?status=pending');
                                            $iconName = 'person_add';
                                            $iconColor = 'blue';
                                        }
                                        ?>
                                        <a href="<?= $redirectUrl ?>" 
                                           onclick="return markAsRead(event, <?= $notif['id'] ?>, <?= $isUnread ? '1' : '0' ?>)"
                                           data-notif-id="<?= $notif['id'] ?>"
                                           data-unread="<?= $isUnread ? '1' : '0' ?>"
                                           class="notif-item block p-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-b-0 <?= $isUnread ? 'bg-blue-50 dark:bg-blue-900/10' : '' ?>">
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-<?= $iconColor ?>-100 dark:bg-<?= $iconColor ?>-900/30 rounded-full flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-<?= $iconColor ?>-600 dark:text-<?= $iconColor ?>-400 text-xl"><?= $iconName ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="notif-text text-sm text-gray-900 dark:text-white <?= $isUnread ? 'font-semibold' : '' ?>">
                                                        <?= esc($notif['pesan']) ?>
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <?= timeAgo($notif['created_at']) ?>
                                                    </p>
                                                </div>
                                                <?php if ($isUnread): ?>
                                                    <div class="flex-shrink-0 notif-dot">
                                                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="p-2 text-gray-600 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                        <span class="material-symbols-outlined">dark_mode</span>
                    </button>
                    
                    <!-- Admin Profile -->
                    <div class="flex items-center gap-2 ml-2">
                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-semibold">
                            <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'A', 0, 1)) ?>
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-medium text-gray-800 dark:text-white"><?= session()->get('nama_lengkap') ?? 'Admin' ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= ucfirst(session()->get('role') ?? 'Administrator') ?></p>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <div class="p-4">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="bg-green-100 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4" role="alert">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-2">check_circle</span>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4" role="alert">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-2">error</span>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (session()->getFlashdata('warning')): ?>
                    <div class="bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded mb-4" role="alert">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-2">warning</span>
                            <?= session()->getFlashdata('warning') ?>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (session()->getFlashdata('info')): ?>
                    <div class="bg-blue-100 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300 px-4 py-3 rounded mb-4" role="alert">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-2">info</span>
                            <?= session()->getFlashdata('info') ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            
            <!-- Page Content -->
            <div class="flex-1">
                <div class="p-3 sm:p-4 lg:p-6">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('hidden');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.add('hidden');
            });
        }
        
        // Submenu toggle
        function toggleSubmenu(menuId, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            // Tutup semua submenu lain dulu
            const allSubmenus = document.querySelectorAll('.submenu');
            const allArrows = document.querySelectorAll('[id$="-arrow"]');
            
            allSubmenus.forEach(submenu => {
                if (submenu.id !== menuId) {
                    submenu.classList.remove('open');
                }
            });
            
            allArrows.forEach(arrow => {
                const arrowMenuId = arrow.id.replace('-arrow', '-menu');
                if (arrowMenuId !== menuId) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle submenu yang diklik
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById(menuId.replace('-menu', '-arrow'));
            
            if (menu && arrow) {
                const isOpen = menu.classList.contains('open');
                
                if (isOpen) {
                    menu.classList.remove('open');
                    arrow.style.transform = 'rotate(0deg)';
                } else {
                    menu.classList.add('open');
                    arrow.style.transform = 'rotate(180deg)';
                }
                
                arrow.style.transition = 'transform 0.3s ease';
            }
        }
        
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        if (themeToggle) {
            // Check for saved theme preference or default to light
            const currentTheme = localStorage.getItem('theme') || 'light';
            html.className = currentTheme;
            updateThemeIcon(currentTheme);
            
            themeToggle.addEventListener('click', function() {
                const newTheme = html.className === 'dark' ? 'light' : 'dark';
                html.className = newTheme;
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
        }
        
        function updateThemeIcon(theme) {
            const icon = themeToggle?.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.textContent = theme === 'dark' ? 'light_mode' : 'dark_mode';
            }
        }
        
        // Initialize menu state dan auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial arrow states for open submenus
            const openSubmenus = document.querySelectorAll('.submenu.open');
            openSubmenus.forEach(submenu => {
                const menuId = submenu.id;
                const arrowId = menuId.replace('-menu', '-arrow');
                const arrow = document.getElementById(arrowId);
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                    arrow.style.transition = 'transform 0.3s ease';
                }
            });
            
            // Add click handlers untuk submenu links
            const submenuLinks = document.querySelectorAll('.submenu-link');
            submenuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Allow normal navigation
                    console.log('Navigating to:', this.href);
                });
            });
            
            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
        
        // Notification functions
        function markAsRead(event, notifId, isUnread) {
            event.preventDefault();
            
            const notifItem = document.querySelector(`[data-notif-id="${notifId}"]`);
            const targetUrl = event.currentTarget.href;
            
            // Jika sudah dibaca (isUnread = 0), langsung redirect
            if (isUnread == 0) {
                window.location.href = targetUrl;
                return false;
            }
            
            // Update UI instantly (remove highlight dan titik biru)
            if (notifItem) {
                // Remove background color
                notifItem.classList.remove('bg-blue-50', 'dark:bg-blue-900/10');
                
                // Remove bold dari text
                const notifText = notifItem.querySelector('.notif-text');
                if (notifText) {
                    notifText.classList.remove('font-semibold');
                }
                
                // Remove titik biru
                const notifDot = notifItem.querySelector('.notif-dot');
                if (notifDot) {
                    notifDot.remove();
                }
                
                // Update data attribute
                notifItem.setAttribute('data-unread', '0');
            }
            
            // Update badge
            updateNotifBadge();
            
            // Send to backend
            fetch(`<?= base_url('admin/notifikasi/read/') ?>${notifId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                // Redirect setelah berhasil update
                window.location.href = targetUrl;
            })
            .catch(error => {
                console.error('Error:', error);
                // Still redirect on error
                window.location.href = targetUrl;
            });
            
            return false;
        }
        
        function markAllAsRead() {
            fetch(`<?= base_url('admin/notifikasi/read-all') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update semua notifikasi yang belum dibaca
                    const unreadNotifs = document.querySelectorAll('.notif-item[data-unread="1"]');
                    unreadNotifs.forEach(notif => {
                        // Remove background
                        notif.classList.remove('bg-blue-50', 'dark:bg-blue-900/10');
                        
                        // Remove bold
                        const text = notif.querySelector('.notif-text');
                        if (text) text.classList.remove('font-semibold');
                        
                        // Remove dot
                        const dot = notif.querySelector('.notif-dot');
                        if (dot) dot.remove();
                        
                        // Update attribute
                        notif.setAttribute('data-unread', '0');
                    });
                    
                    // Hide badge
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        
        function updateNotifBadge() {
            // Hitung hanya notifikasi yang belum dibaca
            const unreadNotifs = document.querySelectorAll('.notif-item[data-unread="1"]').length;
            const badge = document.getElementById('notif-badge');
            
            if (badge) {
                if (unreadNotifs > 0) {
                    badge.textContent = unreadNotifs > 9 ? '9+' : unreadNotifs;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        }
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>