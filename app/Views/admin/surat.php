<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Manajemen Surat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pengajuan surat dari warga</p>
            </div>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-medium">
                    <span class="material-symbols-outlined text-base mr-1">mail</span>
                    Total: <?= count($surat ?? []) ?>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-sm font-medium">
                    <span class="material-symbols-outlined text-base mr-1">schedule</span>
                    Pending: <?= count(array_filter($surat ?? [], fn($s) => in_array($s['status'], ['menunggu', 'pending']))) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-3xl">inbox</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                <?= count(array_filter($surat ?? [], fn($s) => in_array($s['status'], ['menunggu', 'pending']))) ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu Proses</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-3xl">pending</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                <?= count(array_filter($surat ?? [], fn($s) => in_array($s['status'], ['diproses', 'proses']))) ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Dalam Proses</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">check_circle</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                <?= count(array_filter($surat ?? [], fn($s) => $s['status'] === 'selesai')) ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl">cancel</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                <?= count(array_filter($surat ?? [], fn($s) => $s['status'] === 'ditolak')) ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ditolak</p>
        </div>
    </div>

    <!-- Surat Table with Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <!-- Header with Search and Filter -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">table_chart</span>
                    Daftar Pengajuan Surat
                </h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                        <input type="text" id="searchSurat" 
                               class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64" 
                               placeholder="Cari nama atau jenis surat...">
                    </div>
                    <select id="filterType" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">Semua Jenis</option>
                        <option value="domisili">Surat Domisili</option>
                        <option value="kelahiran">Surat Kelahiran</option>
                        <option value="kematian">Surat Kematian</option>
                        <option value="usaha">Surat Usaha</option>
                        <option value="skck">Surat SKCK</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" x-data="{ activeTab: 'all' }">
                <button @click="activeTab = 'all'; filterStatus('all')" 
                        :class="activeTab === 'all' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">mail</span>
                    Semua Surat
                </button>
                <button @click="activeTab = 'menunggu'; filterStatus('menunggu')" 
                        :class="activeTab === 'menunggu' ? 'border-yellow-600 text-yellow-600 dark:border-yellow-400 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">schedule</span>
                    Menunggu
                </button>
                <button @click="activeTab = 'diproses'; filterStatus('diproses')" 
                        :class="activeTab === 'diproses' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">pending</span>
                    Dalam Proses
                </button>
                <button @click="activeTab = 'selesai'; filterStatus('selesai')" 
                        :class="activeTab === 'selesai' ? 'border-green-600 text-green-600 dark:border-green-400 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    Selesai
                </button>
                <button @click="activeTab = 'ditolak'; filterStatus('ditolak')" 
                        :class="activeTab === 'ditolak' ? 'border-red-600 text-red-600 dark:border-red-400 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">cancel</span>
                    Ditolak
                </button>
            </nav>
        </div>
        
        <!-- Table Content -->
        <div class="overflow-x-auto">
            <?= $this->include('admin/partials/surat_table', ['surat' => $surat ?? []]) ?>
        </div>
    </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('additionalJS') ?>
<script>
    // Search functionality
    document.getElementById('searchSurat').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.surat-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Filter by type
    document.getElementById('filterType').addEventListener('change', function() {
        const type = this.value;
        const rows = document.querySelectorAll('.surat-row');
        
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else {
                const jenis = row.getAttribute('data-jenis');
                row.style.display = jenis && jenis.toLowerCase().includes(type) ? '' : 'none';
            }
        });
    });

    // Filter by status
    function filterStatus(status) {
        const rows = document.querySelectorAll('.surat-row');
        
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                const rowStatus = row.getAttribute('data-status');
                const normalizedStatus = status === 'menunggu' ? 'pending' : (status === 'diproses' ? 'proses' : status);
                const normalizedRowStatus = rowStatus === 'menunggu' ? 'pending' : (rowStatus === 'diproses' ? 'proses' : rowStatus);
                row.style.display = normalizedRowStatus === normalizedStatus ? '' : 'none';
            }
        });
    }

    // Action functions
    function prosesSurat(id) {
        if (confirm('Proses surat ini?')) {
            window.location.href = `<?= base_url('admin/surat/proses/') ?>${id}`;
        }
    }

    function selesaiSurat(id) {
        if (confirm('Tandai surat ini sebagai selesai?')) {
            window.location.href = `<?= base_url('admin/surat/selesai/') ?>${id}`;
        }
    }

    function tolakSurat(id) {
        if (confirm('Tolak surat ini?')) {
            const alasan = prompt('Alasan penolakan:');
            if (alasan) {
                window.location.href = `<?= base_url('admin/surat/tolak/') ?>${id}?alasan=${encodeURIComponent(alasan)}`;
            }
        }
    }

    function cetakSurat(id) {
        window.open(`<?= base_url('admin/surat/cetak/') ?>${id}`, '_blank');
    }

    // Edit function disabled - redirect to detail view instead
    // Surat tidak dapat diedit setelah diajukan (business logic)
    function editSurat(id) {
        window.location.href = `<?= base_url('admin/detailSurat/') ?>${id}`;
    }

    function hapusSurat(id) {
        if (confirm('Yakin hapus surat ini? Tindakan tidak dapat dibatalkan.')) {
            window.location.href = `<?= base_url('admin/surat/hapus/') ?>${id}`;
        }
    }
</script>
<?php $this->endSection() ?>
