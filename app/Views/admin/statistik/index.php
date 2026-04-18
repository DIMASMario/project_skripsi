<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<style>
    /* Tab pane default styling */
    .tab-pane {
        display: none !important;
    }
    
    .tab-pane.show,
    .tab-pane.active {
        display: block !important;
    }
    
    /* Fade animation */
    .tab-pane.fade {
        opacity: 0;
        transition: opacity 0.15s ease-in-out;
    }
    
    .tab-pane.fade.show {
        opacity: 1;
    }
</style>

<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Data Statistik Desa</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data statistik dan demografi desa</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alert-container"></div>

    <!-- Navigation Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex overflow-x-auto" id="statistikTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors active border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400" id="demografi-tab" data-bs-toggle="tab" data-bs-target="#demografi" type="button" role="tab">
                        <span class="material-symbols-outlined text-xl">group</span>
                        Demografi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600" id="umur-tab" data-bs-toggle="tab" data-bs-target="#umur" type="button" role="tab">
                        <span class="material-symbols-outlined text-xl">cake</span>
                        Kelompok Umur
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600" id="pendidikan-tab" data-bs-toggle="tab" data-bs-target="#pendidikan" type="button" role="tab">
                        <span class="material-symbols-outlined text-xl">school</span>
                        Pendidikan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600" id="pekerjaan-tab" data-bs-toggle="tab" data-bs-target="#pekerjaan" type="button" role="tab">
                        <span class="material-symbols-outlined text-xl">work</span>
                        Pekerjaan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600" id="fasilitas-tab" data-bs-toggle="tab" data-bs-target="#fasilitas" type="button" role="tab">
                        <span class="material-symbols-outlined text-xl">domain</span>
                        Fasilitas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600" id="wilayah-tab" data-bs-toggle="tab" data-bs-target="#wilayah" type="button" role="tab">
                        <span class="material-symbols-outlined text-xl">map</span>
                        Wilayah
                    </button>
                </li>
            </ul>
        </div>
        <div class="p-6">
            <div class="tab-content" id="statistikTabsContent">
                
                <!-- Tab Demografi -->
                <div class="tab-pane fade show active" id="demografi" role="tabpanel">
                    <!-- Header Action Bar -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 mb-6 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Data Demografi Penduduk</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Kelola data statistik demografi penduduk desa</p>
                            </div>
                            <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all font-medium text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5" onclick="tambahData('demografi')">
                                <span class="material-symbols-outlined text-xl">add_circle</span>
                                Tambah Data Baru
                            </button>
                        </div>
                    </div>
                    
                    <form id="form-demografi" onsubmit="updateStatistik(event, 'demografi')">
                        <?= csrf_field() ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Nama Statistik</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Nilai</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Satuan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php if (isset($statistik['demografi'])): ?>
                                        <?php foreach ($statistik['demografi'] as $item): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= esc($item['nama_statistik']) ?></td>
                                            <td class="px-4 py-4">
                                                <input type="number" class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm" 
                                                       name="statistik[<?= $item['id'] ?>]" 
                                                       value="<?= $item['nilai'] ?>" 
                                                       min="0" required>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"><?= esc($item['satuan']) ?></td>
                                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"><?= esc($item['deskripsi']) ?></td>
                                            <td class="px-4 py-4 text-right">
                                                <button type="button" class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors" 
                                                        onclick="hapusData(<?= $item['id'] ?>)">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg transition-colors font-medium">
                                <span class="material-symbols-outlined text-xl">save</span>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab Kelompok Umur -->
                <div class="tab-pane fade" id="umur" role="tabpanel">
                    <!-- Header Action Bar -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 mb-6 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Data Kelompok Umur</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Kelola data statistik kelompok umur penduduk</p>
                            </div>
                            <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all font-medium text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5" onclick="tambahData('kelompok_umur')">
                                <span class="material-symbols-outlined text-xl">add_circle</span>
                                Tambah Data Baru
                            </button>
                        </div>
                    </div>
                    
                    <form id="form-kelompok_umur" onsubmit="updateStatistik(event, 'kelompok_umur')">
                        <?= csrf_field() ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Kelompok Umur</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Satuan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php if (isset($statistik['kelompok_umur'])): ?>
                                        <?php foreach ($statistik['kelompok_umur'] as $item): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= esc($item['nama_statistik']) ?></td>
                                            <td class="px-4 py-4">
                                                <input type="number" class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm" 
                                                       name="statistik[<?= $item['id'] ?>]" 
                                                       value="<?= $item['nilai'] ?>" 
                                                       min="0" required>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"><?= esc($item['satuan']) ?></td>
                                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"><?= esc($item['deskripsi']) ?></td>
                                            <td class="px-4 py-4 text-right">
                                                <button type="button" class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors" 
                                                        onclick="hapusData(<?= $item['id'] ?>)">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Footer Action Bar -->
                        <div class="mt-8 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400">info</span>
                                        <div>
                                            <p class="font-medium">Jangan lupa simpan perubahan</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Klik tombol simpan setelah mengubah data</p>
                                        </div>
                                    </div>
                                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition-all font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                        <span class="material-symbols-outlined text-xl">save</span>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabs lainnya dengan struktur serupa -->
                <?php 
                $tabs_data = [
                    'pendidikan' => ['icon' => 'school', 'title' => 'Data Tingkat Pendidikan'],
                    'pekerjaan' => ['icon' => 'work', 'title' => 'Data Mata Pencaharian'],
                    'fasilitas' => ['icon' => 'domain', 'title' => 'Data Fasilitas Desa'],
                    'wilayah' => ['icon' => 'map', 'title' => 'Data Wilayah Administrasi']
                ];
                
                foreach ($tabs_data as $kategori => $info): ?>
                <div class="tab-pane fade" id="<?= $kategori ?>" role="tabpanel">
                    <!-- Header Action Bar -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 mb-6 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1"><?= $info['title'] ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Kelola data statistik <?= strtolower($info['title']) ?></p>
                            </div>
                            <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all font-medium text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5" onclick="tambahData('<?= $kategori ?>')">
                                <span class="material-symbols-outlined text-xl">add_circle</span>
                                Tambah Data Baru
                            </button>
                        </div>
                    </div>
                    
                    <form id="form-<?= $kategori ?>" onsubmit="updateStatistik(event, '<?= $kategori ?>')">
                        <?= csrf_field() ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Nilai</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Satuan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php if (isset($statistik[$kategori])): ?>
                                        <?php foreach ($statistik[$kategori] as $item): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= esc($item['nama_statistik']) ?></td>
                                            <td class="px-4 py-4">
                                                <input type="number" class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm" 
                                                       name="statistik[<?= $item['id'] ?>]" 
                                                       value="<?= $item['nilai'] ?>" 
                                                       min="0" required>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"><?= esc($item['satuan']) ?></td>
                                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400"><?= esc($item['deskripsi']) ?></td>
                                            <td class="px-4 py-4 text-right">
                                                <button type="button" class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors" 
                                                        onclick="hapusData(<?= $item['id'] ?>)">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Footer Action Bar -->
                        <div class="mt-8 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400">info</span>
                                        <div>
                                            <p class="font-medium">Jangan lupa simpan perubahan</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Klik tombol simpan setelah mengubah data</p>
                                        </div>
                                    </div>
                                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition-all font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                        <span class="material-symbols-outlined text-xl">save</span>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-0">
            <div class="modal-header border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h5 class="modal-title text-xl font-semibold text-gray-900 dark:text-white" id="modalTambahDataLabel">Tambah Data Statistik</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form id="formTambahData" onsubmit="simpanDataBaru(event)">
                <div class="modal-body px-6 py-5 space-y-5">
                    <input type="hidden" id="kategoriInput" name="kategori">
                    
                    <div>
                        <label for="namaStatistik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Statistik <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors" id="namaStatistik" name="nama_statistik" required>
                    </div>
                    
                    <div>
                        <label for="nilaiStatistik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nilai <span class="text-red-500">*</span>
                        </label>
                        <input type="number" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors" id="nilaiStatistik" name="nilai" min="0" required>
                    </div>
                    
                    <div>
                        <label for="satuanStatistik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Satuan</label>
                        <input type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors" id="satuanStatistik" name="satuan" placeholder="jiwa, unit, km², dll">
                    </div>
                    
                    <div>
                        <label for="deskripsiStatistik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <textarea class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors" id="deskripsiStatistik" name="deskripsi" rows="3"></textarea>
                    </div>
                    
                    <div>
                        <label for="iconStatistik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon (Material Symbols)</label>
                        <input type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors" id="iconStatistik" name="icon" placeholder="people, school, home">
                    </div>
                </div>
                <div class="modal-footer border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <span class="material-symbols-outlined text-xl">save</span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tab Switching Functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            
            if (!targetPane) return;
            
            // Remove active classes from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-600', 'text-blue-600', 'dark:border-blue-400', 'dark:text-blue-400');
                btn.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            });
            
            // Add active classes to clicked tab
            this.classList.add('active', 'border-blue-600', 'text-blue-600', 'dark:border-blue-400', 'dark:text-blue-400');
            this.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Show target tab pane
            targetPane.classList.add('show', 'active');
        });
    });
});

// Fungsi untuk update statistik
function updateStatistik(event, kategori) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    formData.append('kategori', kategori);
    
    console.log('Menyimpan data untuk kategori:', kategori);
    console.log('FormData keys:', Array.from(formData.keys()));
    
    fetch('<?= base_url('admin/statistik/update') ?>', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        showAlert(data.success ? 'success' : 'danger', data.message);
        
        // Reload page setelah 1.5 detik jika sukses
        if (data.success) {
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showAlert('danger', 'Terjadi kesalahan saat menyimpan data: ' + error.message);
    });
}

// Fungsi untuk tambah data baru
function tambahData(kategori) {
    // Mapping kategori ke nama yang lebih readable
    const kategoriNames = {
        'demografi': 'Demografi Penduduk',
        'kelompok_umur': 'Kelompok Umur',
        'pendidikan': 'Tingkat Pendidikan',
        'pekerjaan': 'Mata Pencaharian',
        'fasilitas': 'Fasilitas Desa',
        'wilayah': 'Wilayah Administrasi'
    };
    
    const namaKategori = kategoriNames[kategori] || kategori.toUpperCase();
    
    document.getElementById('kategoriInput').value = kategori;
    document.getElementById('modalTambahDataLabel').textContent = 'Tambah Data ' + namaKategori;
    document.getElementById('formTambahData').reset();
    document.getElementById('kategoriInput').value = kategori;
    
    // Pastikan Bootstrap JS sudah loaded
    if (typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(document.getElementById('modalTambahData'));
        modal.show();
    } else {
        console.error('Bootstrap JS tidak tersedia');
        alert('Modal tidak dapat dibuka. Silakan refresh halaman.');
    }
}

// Fungsi untuk simpan data baru
function simpanDataBaru(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('<?= base_url('admin/statistik/store') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalTambahData')).hide();
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
    });
}

// Fungsi untuk hapus data
function hapusData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch(`<?= base_url('admin/statistik/delete') ?>/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            showAlert(data.success ? 'success' : 'danger', data.message);
            if (data.success) {
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => {
            showAlert('danger', 'Terjadi kesalahan saat menghapus data');
        });
    }
}

// Fungsi untuk show alert
function showAlert(type, message) {
    const alertColors = {
        success: 'bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-300 border-green-200 dark:border-green-800',
        danger: 'bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800',
        warning: 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800',
        info: 'bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-800'
    };
    
    const alertIcons = {
        success: 'check_circle',
        danger: 'error',
        warning: 'warning',
        info: 'info'
    };
    
    const alertHtml = `
        <div class="flex items-start gap-3 p-4 mb-4 border rounded-lg ${alertColors[type] || alertColors.info}" role="alert">
            <span class="material-symbols-outlined text-xl">${alertIcons[type] || alertIcons.info}</span>
            <div class="flex-1 text-sm font-medium">${message}</div>
            <button type="button" class="text-current opacity-50 hover:opacity-100" onclick="this.closest('div[role=alert]').remove()">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
    `;
    
    const container = document.getElementById('alert-container');
    container.innerHTML = alertHtml;
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('div[role=alert]');
        if (alert) alert.remove();
    }, 5000);
}

// ========== TAB SWITCHING HANDLER ==========
document.addEventListener('DOMContentLoaded', function() {
    // Get all tab buttons
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get target pane ID from data-bs-target
            const targetId = this.getAttribute('data-bs-target');
            if (!targetId) return;
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-600', 'text-blue-600', 'dark:border-blue-400', 'dark:text-blue-400');
                btn.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'border-blue-600', 'text-blue-600', 'dark:border-blue-400', 'dark:text-blue-400');
            this.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            
            // Hide all tab panes
            const allPanes = document.querySelectorAll('.tab-pane');
            allPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });
            
            // Show target pane
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.style.display = 'block';
                targetPane.classList.add('show', 'active');
                // Trigger reflow for fade animation
                void targetPane.offsetWidth;
            }
            
            console.log('Tab switched to: ' + targetId);
        });
    });
    
    console.log('Tab switching initialized. Total tabs: ' + tabButtons.length);
});
</script>

<?= $this->endSection() ?>