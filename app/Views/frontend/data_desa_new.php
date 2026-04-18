<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col gap-8">
        <!-- Page Heading & Filters -->
        <section>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="flex flex-col gap-2">
                    <p class="text-text-light dark:text-white text-4xl font-black tracking-tighter">Data Statistik Desa</p>
                    <p class="text-gray-600 dark:text-gray-400 text-base font-normal">Kelola data statistik dan demografi desa</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-card-dark px-4 border border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary">
                        <p class="text-text-light dark:text-gray-200 text-sm font-medium">Tahun: <?= date('Y') ?></p>
                        <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-base">expand_more</span>
                    </button>
                    <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-card-dark px-4 border border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary">
                        <p class="text-text-light dark:text-gray-200 text-sm font-medium">Dusun: Semua</p>
                        <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-base">expand_more</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- TAB NAVIGATION -->
        <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700" id="tab-nav">
            <button type="button" class="tab-btn active" onclick="document.querySelectorAll('.tab-content').forEach(t => t.style.display='none'); document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); document.getElementById('demografi-content').style.display='block'; this.classList.add('active');">
                <span class="material-symbols-outlined text-sm">person</span>
                <span>Demografi</span>
            </button>
            <button type="button" class="tab-btn" onclick="document.querySelectorAll('.tab-content').forEach(t => t.style.display='none'); document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); document.getElementById('kelompok-umur-content').style.display='block'; this.classList.add('active');">
                <span class="material-symbols-outlined text-sm">group</span>
                <span>Kelompok Umur</span>
            </button>
            <button type="button" class="tab-btn" onclick="document.querySelectorAll('.tab-content').forEach(t => t.style.display='none'); document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); document.getElementById('pendidikan-content').style.display='block'; this.classList.add('active');">
                <span class="material-symbols-outlined text-sm">school</span>
                <span>Pendidikan</span>
            </button>
            <button type="button" class="tab-btn" onclick="document.querySelectorAll('.tab-content').forEach(t => t.style.display='none'); document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); document.getElementById('pekerjaan-content').style.display='block'; this.classList.add('active');">
                <span class="material-symbols-outlined text-sm">work</span>
                <span>Pekerjaan</span>
            </button>
            <button type="button" class="tab-btn" onclick="document.querySelectorAll('.tab-content').forEach(t => t.style.display='none'); document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); document.getElementById('fasilitas-content').style.display='block'; this.classList.add('active');">
                <span class="material-symbols-outlined text-sm">home_repair_service</span>
                <span>Fasilitas</span>
            </button>
            <button type="button" class="tab-btn" onclick="document.querySelectorAll('.tab-content').forEach(t => t.style.display='none'); document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); document.getElementById('wilayah-content').style.display='block'; this.classList.add('active');">
                <span class="material-symbols-outlined text-sm">map</span>
                <span>Wilayah</span>
            </button>
        </div>

        </div>

        <!-- Section 1: Data Demografi -->
        <section class="tab-content" id="demografi-content" style="display: block;">
            <div class="flex flex-col gap-6">
                <h2 class="text-text-light dark:text-white text-2xl font-bold tracking-tight">Data Demografi Penduduk</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data statistik demografi penduduk desa</p>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Jumlah Penduduk</p>
                        <p class="text-text-light dark:text-white text-4xl font-bold"><?= number_format($demographics['total_population']) ?> Jiwa</p>
                    </div>
                    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Jumlah KK</p>
                        <p class="text-text-light dark:text-white text-4xl font-bold"><?= number_format($demographics['total_families']) ?> KK</p>
                    </div>
                    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Kepadatan Penduduk</p>
                        <p class="text-text-light dark:text-white text-4xl font-bold"><?= $demographics['population_density'] ?> Jiwa/km²</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
                    <!-- Gender Distribution Chart -->
                    <div class="lg:col-span-2 flex flex-col gap-4 rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                        <h3 class="text-text-light dark:text-white font-bold">Jenis Kelamin</h3>
                        <div class="flex items-center justify-center">
                            <div class="relative w-48 h-48">
                                <!-- Simple CSS Donut Chart -->
                                <div class="absolute inset-0 rounded-full" style="background: conic-gradient(#005C99 0deg <?= ($demographics['male_percentage'] * 3.6) ?>deg, #FFD700 <?= ($demographics['male_percentage'] * 3.6) ?>deg 360deg);"></div>
                                <div class="absolute inset-4 bg-white dark:bg-card-dark rounded-full flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-text-light dark:text-white"><?= number_format($demographics['total_population']) ?></div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-around text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Laki-laki</p>
                                    <p class="font-bold text-text-light dark:text-white"><?= number_format($demographics['male_count']) ?> (<?= $demographics['male_percentage'] ?>%)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-accent"></div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Perempuan</p>
                                    <p class="font-bold text-text-light dark:text-white"><?= number_format($demographics['female_count']) ?> (<?= $demographics['female_percentage'] ?>%)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics Summary -->
                    <div class="lg:col-span-3 flex flex-col gap-4 rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                        <h3 class="text-text-light dark:text-white font-bold">Statistik Tambahan</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Luas Wilayah</span>
                                <span class="font-bold text-text-light dark:text-white"><?= $demographics['area'] ?> km²</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Jumlah RT</span>
                                <span class="font-bold text-text-light dark:text-white"><?= $infrastructure['rt_count'] ?> RT</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Jumlah RW</span>
                                <span class="font-bold text-text-light dark:text-white"><?= $infrastructure['rw_count'] ?> RW</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-300">Jumlah Dusun</span>
                                <span class="font-bold text-text-light dark:text-white"><?= $infrastructure['dusun_count'] ?> Dusun</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Kelompok Umur -->
        <section class="tab-content" id="kelompok-umur-content">
            <div class="flex flex-col gap-6">
                <h2 class="text-text-light dark:text-white text-2xl font-bold tracking-tight">Data Kelompok Umur</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data statistik kelompok umur penduduk desa</p>
                
                <div class="rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                    <h3 class="text-text-light dark:text-white font-bold mb-6">Distribusi Kelompok Usia</h3>
                    <div class="space-y-4">
                        <?php foreach ($demographics['age_groups'] as $group): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-32 text-sm text-gray-600 dark:text-gray-400 font-medium"><?= $group['range'] ?></div>
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-6 relative">
                                <div class="absolute top-0 left-0 h-full bg-primary rounded-full transition-all duration-500 flex items-center justify-end pr-2" 
                                     style="width: <?= $group['percentage'] ?>%">
                                    <span class="text-white text-xs font-bold"><?= $group['percentage'] ?>%</span>
                                </div>
                            </div>
                            <div class="w-24 text-sm font-bold text-text-light dark:text-white text-right"><?= $group['count'] ?> jiwa</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Pendidikan -->
        <section class="tab-content" id="pendidikan-content">
            <div class="flex flex-col gap-6">
                <h2 class="text-text-light dark:text-white text-2xl font-bold tracking-tight">Data Pendidikan</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data statistik tingkat pendidikan penduduk desa</p>
                
                <div class="rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                    <h3 class="text-text-light dark:text-white font-bold mb-6">Tingkat Pendidikan</h3>
                    <div class="space-y-4">
                        <?php foreach ($economics['education'] as $education): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-32 text-sm text-gray-600 dark:text-gray-400 font-medium"><?= $education['level'] ?></div>
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-6 relative">
                                <div class="absolute top-0 left-0 h-full bg-accent rounded-full transition-all duration-500 flex items-center justify-end pr-2" 
                                     style="width: <?= $education['percentage'] ?>%">
                                    <span class="text-white text-xs font-bold"><?= $education['percentage'] ?>%</span>
                                </div>
                            </div>
                            <div class="w-24 text-sm font-bold text-text-light dark:text-white text-right"><?= $education['count'] ?> jiwa</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Pekerjaan -->
        <section class="tab-content" id="pekerjaan-content">
            <div class="flex flex-col gap-6">
                <h2 class="text-text-light dark:text-white text-2xl font-bold tracking-tight">Data Pekerjaan</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data statistik mata pencaharian penduduk desa</p>
                
                <div class="rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                    <h3 class="text-text-light dark:text-white font-bold mb-6">Mata Pencaharian Utama</h3>
                    <div class="space-y-4">
                        <?php foreach ($economics['occupations'] as $occupation): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-32 text-sm text-gray-600 dark:text-gray-400 font-medium"><?= $occupation['name'] ?></div>
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-6 relative">
                                <div class="absolute top-0 left-0 h-full bg-primary rounded-full transition-all duration-500 flex items-center justify-end pr-2" 
                                     style="width: <?= $occupation['percentage'] ?>%">
                                    <span class="text-white text-xs font-bold"><?= $occupation['percentage'] ?>%</span>
                                </div>
                            </div>
                            <div class="w-24 text-sm font-bold text-text-light dark:text-white text-right"><?= $occupation['count'] ?> jiwa</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5: Fasilitas -->
        <section class="tab-content" id="fasilitas-content">
            <div class="flex flex-col gap-6">
                <h2 class="text-text-light dark:text-white text-2xl font-bold tracking-tight">Data Fasilitas</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data sarana dan prasarana desa</p>
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Map Section -->
                    <div class="lg:col-span-2 flex flex-col rounded-lg bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-text-light dark:text-white font-bold">Peta Fasilitas Desa</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Klik ikon untuk melihat detail fasilitas.</p>
                        </div>
                        <div class="bg-gray-200 dark:bg-gray-700 h-96 w-full flex-grow relative">
                        <!-- Simple map placeholder with facility markers -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <span class="material-symbols-outlined text-6xl mb-4 block">map</span>
                                <p class="text-lg font-medium">Peta Fasilitas Desa Blanakan</p>
                                <p class="text-sm">Integrasi dengan layanan peta akan ditambahkan</p>
                            </div>
                        </div>
                        
                        <!-- Sample facility markers -->
                        <div class="absolute top-16 left-20 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Kantor Desa">
                            <span class="material-symbols-outlined text-sm">location_city</span>
                        </div>
                        <div class="absolute top-32 right-24 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Puskesmas">
                            <span class="material-symbols-outlined text-sm">local_hospital</span>
                        </div>
                        <div class="absolute bottom-20 left-1/3 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Sekolah">
                            <span class="material-symbols-outlined text-sm">school</span>
                        </div>
                        <div class="absolute bottom-32 right-1/3 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg cursor-pointer hover:scale-110 transition-transform" title="Masjid">
                            <span class="material-symbols-outlined text-sm">mosque</span>
                        </div>
                    </div>
                </div>
                
                    <!-- Facilities Summary -->
                    <div class="flex flex-col gap-4">
                        <div class="rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                            <h3 class="text-text-light dark:text-white font-bold mb-4">Jumlah Fasilitas</h3>
                            <div class="space-y-3 text-sm">
                                <?php foreach ($infrastructure['facilities'] as $facility): ?>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-primary"><?= $facility['icon'] ?></span>
                                        <span class="text-gray-600 dark:text-gray-300"><?= $facility['name'] ?></span>
                                    </div>
                                    <span class="font-bold text-text-light dark:text-white"><?= $facility['count'] ?> Unit</span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 6: Wilayah -->
        <section class="tab-content" id="wilayah-content">
            <div class="flex flex-col gap-6">
                <h2 class="text-text-light dark:text-white text-2xl font-bold tracking-tight">Data Wilayah</h2>
                <p class="text-gray-600 dark:text-gray-400">Kelola data administratif wilayah desa</p>
                
                <div class="rounded-lg p-6 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700">
                    <h3 class="text-text-light dark:text-white font-bold mb-6">Informasi Wilayah</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <div class="flex flex-col gap-2 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Luas Wilayah</p>
                            <p class="text-2xl font-bold text-text-light dark:text-white"><?= $demographics['area'] ?> km²</p>
                        </div>
                        <div class="flex flex-col gap-2 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Jumlah Dusun</p>
                            <p class="text-2xl font-bold text-text-light dark:text-white"><?= $infrastructure['dusun_count'] ?> Dusun</p>
                        </div>
                        <div class="flex flex-col gap-2 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Jumlah RW</p>
                            <p class="text-2xl font-bold text-text-light dark:text-white"><?= $infrastructure['rw_count'] ?> RW</p>
                        </div>
                        <div class="flex flex-col gap-2 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Jumlah RT</p>
                            <p class="text-2xl font-bold text-text-light dark:text-white"><?= $infrastructure['rt_count'] ?> RT</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
/* Tab Navigation */
.tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-bottom: 3px solid transparent;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    background: none;
    border: none;
}

.dark .tab-btn {
    color: #ccc;
}

.tab-btn:hover {
    color: #005C99;
}

.dark .tab-btn:hover {
    color: #fff;
}

.tab-btn.active {
    color: #005C99 !important;
    border-bottom-color: #005C99 !important;
}

.dark .tab-btn.active {
    color: #fff !important;
    border-bottom-color: #005C99 !important;
}

/* Tabs - Simple display control */
.tab-content {
    display: none;
}
</style>

<?= $this->endSection() ?>