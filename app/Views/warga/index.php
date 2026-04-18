<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Warga - Desa Tanjung Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.js" defer></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Direktori Warga Desa Tanjung Baru</h1>
        
        <!-- Search and Filter -->
        <div class="mb-6" x-data="{ showFilter: false }">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <input type="text" 
                       id="searchWarga"
                       placeholder="Cari nama warga..." 
                       class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <button @click="showFilter = !showFilter"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Filter
                </button>
                
                <button id="btnSearch"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Cari
                </button>
            </div>

            <!-- Filter Options -->
            <div x-show="showFilter" class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-white rounded-lg border">
                <div>
                    <label class="block text-sm font-medium mb-1">Dusun</label>
                    <select id="filterDusun" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Semua Dusun</option>
                        <option value="1">Dusun 1</option>
                        <option value="2">Dusun 2</option>
                        <option value="3">Dusun 3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                    <select id="filterGender" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Semua</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select id="filterStatus" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Rentang Umur</label>
                    <select id="filterAge" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Semua Umur</option>
                        <option value="0-17">0-17 tahun</option>
                        <option value="18-30">18-30 tahun</option>
                        <option value="31-50">31-50 tahun</option>
                        <option value="51-100">51+ tahun</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Memuat data...</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <div class="text-2xl font-bold text-blue-600"><?= $total_warga ?? 0 ?></div>
                <div class="text-sm text-gray-600">Total Warga</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <div class="text-2xl font-bold text-green-600"><?= $laki_laki ?? 0 ?></div>
                <div class="text-sm text-gray-600">Laki-laki</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <div class="text-2xl font-bold text-pink-600"><?= $perempuan ?? 0 ?></div>
                <div class="text-sm text-gray-600">Perempuan</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <div class="text-2xl font-bold text-purple-600"><?= $kepala_keluarga ?? 0 ?></div>
                <div class="text-sm text-gray-600">Kepala Keluarga</div>
            </div>
        </div>

        <!-- Warga List -->
        <div id="wargaContainer" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <?php if (!empty($warga_list)): ?>
                <?php foreach ($warga_list as $warga): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                <?= esc($warga['nama_lengkap']) ?>
                            </h3>
                            <p class="text-sm text-gray-600">NIK: <?= esc($warga['nik']) ?></p>
                        </div>
                        <span class="px-2 py-1 bg-<?= $warga['jenis_kelamin'] == 'L' ? 'blue' : 'pink' ?>-100 text-<?= $warga['jenis_kelamin'] == 'L' ? 'blue' : 'pink' ?>-800 text-xs rounded-full">
                            <?= $warga['jenis_kelamin'] == 'L' ? 'L' : 'P' ?>
                        </span>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tempat/Tgl Lahir:</span>
                            <span><?= esc($warga['tempat_lahir'] ?? '-') ?>, <?= !empty($warga['tanggal_lahir']) ? date('d/m/Y', strtotime($warga['tanggal_lahir'])) : '-' ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Umur:</span>
                            <span><?= !empty($warga['tanggal_lahir']) ? date_diff(date_create($warga['tanggal_lahir']), date_create('today'))->y : '-' ?> tahun</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Alamat:</span>
                            <span><?= esc($warga['alamat'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">RT/RW:</span>
                            <span>RT. <?= esc($warga['rt'] ?? '-') ?> / RW. <?= esc($warga['rw'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span><?= esc($warga['email'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500 text-lg">Tidak ada data warga yang ditemukan.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($pager)): ?>
        <div class="mt-8 flex justify-center">
            <?= $pager->links() ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchWarga');
        const btnSearch = document.getElementById('btnSearch');
        const loading = document.getElementById('loading');
        const container = document.getElementById('wargaContainer');
        
        function searchWarga() {
            const search = searchInput.value;
            const dusun = document.getElementById('filterDusun').value;
            const gender = document.getElementById('filterGender').value;
            const status = document.getElementById('filterStatus').value;
            const age = document.getElementById('filterAge').value;
            
            loading.classList.remove('hidden');
            
            const params = new URLSearchParams({
                search: search,
                dusun: dusun,
                gender: gender,
                status: status,
                age: age
            });
            
            fetch(`<?= base_url('warga/api/search') ?>?${params}`)
                .then(response => response.json())
                .then(data => {
                    loading.classList.add('hidden');
                    if (data.success) {
                        updateWargaList(data.data);
                    }
                })
                .catch(error => {
                    loading.classList.add('hidden');
                    console.error('Error:', error);
                });
        }
        
        function updateWargaList(warga) {
            if (warga.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500 text-lg">Tidak ada data warga yang ditemukan.</p></div>';
                return;
            }
            
            container.innerHTML = warga.map(w => `
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">${w.nama}</h3>
                            <p class="text-sm text-gray-600">NIK: ${w.nik}</p>
                        </div>
                        <span class="px-2 py-1 bg-${w.jenis_kelamin == 'L' ? 'blue' : 'pink'}-100 text-${w.jenis_kelamin == 'L' ? 'blue' : 'pink'}-800 text-xs rounded-full">
                            ${w.jenis_kelamin}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tempat/Tgl Lahir:</span>
                            <span>${w.tempat_lahir}, ${new Date(w.tgl_lahir).toLocaleDateString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span>${w.status_kawin}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pekerjaan:</span>
                            <span>${w.pekerjaan}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dusun:</span>
                            <span>Dusun ${w.dusun || '-'}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        btnSearch.addEventListener('click', searchWarga);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchWarga();
            }
        });
    });
    </script>
</body>
</html>