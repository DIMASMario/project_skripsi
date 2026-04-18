<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center h-16 px-4 bg-primary">
        <div class="flex items-center">
            <img src="<?= base_url('images/carousel/logo.png') ?>" alt="Logo Desa" class="h-8 w-8 mr-3">
            <span class="text-white font-bold text-lg">Dashboard</span>
        </div>
    </div>
    
    <nav class="mt-5 px-2">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="<?= base_url('dashboard') ?>" class="<?= uri_string() === 'dashboard' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <span class="material-symbols-outlined mr-3 text-lg">dashboard</span>
                Dashboard
            </a>
            
            <!-- Profil -->
            <a href="<?= base_url('dashboard/profil') ?>" class="<?= uri_string() === 'dashboard/profil' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <span class="material-symbols-outlined mr-3 text-lg">person</span>
                Profil Saya
            </a>
            
            <!-- Layanan Online -->
            <a href="<?= base_url('layanan-online') ?>" class="<?= strpos(uri_string(), 'layanan-online') !== false ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <span class="material-symbols-outlined mr-3 text-lg">description</span>
                Layanan Online
            </a>
            
            <!-- Riwayat Surat -->
            <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="<?= uri_string() === 'dashboard/riwayat-surat' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <span class="material-symbols-outlined mr-3 text-lg">history</span>
                Riwayat Surat
            </a>
            
            <!-- Notifikasi -->
            <a href="<?= base_url('dashboard/notifikasi') ?>" class="<?= uri_string() === 'dashboard/notifikasi' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <span class="material-symbols-outlined mr-3 text-lg">notifications</span>
                Notifikasi
                <?php if (isset($notif_count) && $notif_count > 0): ?>
                <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?= $notif_count ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="space-y-1">
                <!-- Website Utama -->
                <a href="<?= base_url('/') ?>" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <span class="material-symbols-outlined mr-3 text-lg">home</span>
                    Website Utama
                </a>
                
                <!-- Logout -->
                <a href="<?= base_url('auth/logout') ?>" class="text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <span class="material-symbols-outlined mr-3 text-lg">logout</span>
                    Keluar
                </a>
            </div>
        </div>
    </nav>
</aside>