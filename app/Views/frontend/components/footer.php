<!-- Footer -->
<footer class="bg-gray-800 text-white dark:bg-card-dark">
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Desa Info -->
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
            
            <!-- Tautan Cepat -->
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
            
            <!-- Kontak -->
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
            
            <!-- Media Sosial -->
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
        
        <!-- Copyright -->
        <div class="mt-12 border-t border-gray-700 pt-8 text-center text-gray-500">
            <p>© <?= date('Y') ?> Pemerintah Desa Blanakan. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>