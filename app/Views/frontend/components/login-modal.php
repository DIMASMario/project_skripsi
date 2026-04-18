<!-- Login Modal -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" 
     x-cloak 
     x-show="$store.modal.loginModalOpen" 
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-200" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0">
     
    <div @click.outside="$store.modal.closeLogin()" 
         class="relative w-full max-w-md bg-card-light dark:bg-card-dark rounded-xl shadow-2xl p-8 text-center" 
         x-show="$store.modal.loginModalOpen" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95">
         
        <button @click="$store.modal.closeLogin()" 
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