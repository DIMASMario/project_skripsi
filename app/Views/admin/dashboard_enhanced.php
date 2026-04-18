<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<!-- Enhanced Admin Dashboard -->
<div class="flex-1 flex flex-col min-h-0 bg-gray-50 dark:bg-gray-900/30">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-800 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Selamat datang di Panel Administrasi Desa Blanakan
                </p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Quick Actions -->
                <button onclick="refreshDashboard()" class="flex items-center gap-2 px-3 py-2 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    Refresh
                </button>
                <button onclick="showSystemInfo()" class="flex items-center gap-2 px-3 py-2 text-sm bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400">
                    <span class="material-symbols-outlined text-sm">info</span>
                    System Info
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto p-6 space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Users Stats -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Warga</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-users">
                            <?= number_format($stats['total_users'] ?? 0) ?>
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            <?= $stats['users_approved'] ?? 0 ?> terverifikasi
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">group</span>
                    </div>
                </div>
            </div>

            <!-- Letters Stats -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Surat Masuk</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="pending-letters">
                            <?= number_format($stats['surat_pending'] ?? 0) ?>
                        </p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                            Menunggu proses
                        </p>
                    </div>
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">description</span>
                    </div>
                </div>
            </div>

            <!-- News Stats -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Berita</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="published-news">
                            <?= number_format($stats['berita_published'] ?? 0) ?>
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            Terpublikasi
                        </p>
                    </div>
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">newspaper</span>
                    </div>
                </div>
            </div>

            <!-- Visitors Stats -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pengunjung Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="daily-visitors">
                            <?= number_format($stats['visitor_today'] ?? 0) ?>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Total kunjungan
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">trending_up</span>
                    </div>
                </div>
            </div>

            <!-- Online Users Stats -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">User Online Sekarang</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="online-users">
                            <?= number_format($stats['online_users_now'] ?? 0) ?>
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            Aktif dalam 15 menit terakhir
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">visibility</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Visitor Trends Chart -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trend Pengunjung</h3>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg dark:bg-blue-900/20 dark:text-blue-400">7 Hari</button>
                        <button class="px-3 py-1 text-xs text-gray-600 rounded-lg hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800">30 Hari</button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>

            <!-- Letters Status Chart -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Surat</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Bulan ini</span>
                </div>
                <div class="h-64">
                    <canvas id="lettersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Letters -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Surat Terbaru</h3>
                        <a href="<?= base_url('admin/surat') ?>" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    <?php if (isset($recent_letters) && !empty($recent_letters)): ?>
                        <?php foreach ($recent_letters as $letter): ?>
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">description</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">
                                        <?= esc($letter['jenis_surat'] ?? 'Surat') ?>
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        <?= esc($letter['nama_lengkap'] ?? 'Pemohon') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-lg
                                    <?php 
                                    $status = $letter['status'] ?? 'pending';
                                    echo match($status) {
                                        'pending' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'diproses' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                                        'selesai' => 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                        'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-400'
                                    }
                                    ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <?= date('d M Y', strtotime($letter['created_at'] ?? 'now')) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-4xl mb-2 block">inbox</span>
                            Tidak ada surat terbaru
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aksi Cepat</h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="<?= base_url('admin/surat?status=pending') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300">
                        <span class="material-symbols-outlined text-orange-500">pending_actions</span>
                        <span class="text-sm font-medium">Proses Surat Masuk</span>
                    </a>
                    <a href="<?= base_url('admin/users?status=pending') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300">
                        <span class="material-symbols-outlined text-blue-500">person_check</span>
                        <span class="text-sm font-medium">Verifikasi Warga</span>
                    </a>
                    <a href="<?= base_url('admin/berita/tambah') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300">
                        <span class="material-symbols-outlined text-green-500">add_circle</span>
                        <span class="text-sm font-medium">Tambah Berita</span>
                    </a>
                    <a href="<?= base_url('admin/backup') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300">
                        <span class="material-symbols-outlined text-purple-500">backup</span>
                        <span class="text-sm font-medium">Backup Data</span>
                    </a>
                    <a href="<?= base_url('admin/config') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300">
                        <span class="material-symbols-outlined text-gray-500">settings</span>
                        <span class="text-sm font-medium">Pengaturan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Database</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Berjalan Normal</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Server</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Online</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Backup</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Terakhir: <?= date('d M Y H:i') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal System Info -->
<div id="systemInfoModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Sistem</h3>
                    <button onclick="closeSystemInfo()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-auto">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">CodeIgniter Version</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><?= \CodeIgniter\CodeIgniter::CI_VERSION ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">PHP Version</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><?= phpversion() ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Server Software</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Document Root</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 break-all"><?= ROOTPATH ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dashboard functions
function refreshDashboard() {
    location.reload();
}

function showSystemInfo() {
    document.getElementById('systemInfoModal').classList.remove('hidden');
}

function closeSystemInfo() {
    document.getElementById('systemInfoModal').classList.add('hidden');
}

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Visitors Chart
    const visitorsCtx = document.getElementById('visitorsChart');
    if (visitorsCtx) {
        const visitorData = <?= json_encode($visitor_trends ?? []) ?>;
        const labels = visitorData.map(item => {
            const date = new Date(item.tanggal);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const data = visitorData.map(item => item.total || 0);

        new Chart(visitorsCtx, {
            type: 'line',
            data: {
                labels: labels.length > 0 ? labels : ['Belum ada data'],
                datasets: [{
                    label: 'Pengunjung',
                    data: data.length > 0 ? data : [0],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Letters Chart
    const lettersCtx = document.getElementById('lettersChart');
    if (lettersCtx) {
        new Chart(lettersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Diproses', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [
                        <?= $stats['letters']['pending'] ?? 0 ?>,
                        <?= $stats['letters']['diproses'] ?? 0 ?>,
                        <?= $stats['letters']['selesai'] ?? 0 ?>,
                        <?= $stats['letters']['ditolak'] ?? 0 ?>
                    ],
                    backgroundColor: [
                        '#F59E0B',
                        '#3B82F6', 
                        '#10B981',
                        '#EF4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
<?php $this->endSection() ?>