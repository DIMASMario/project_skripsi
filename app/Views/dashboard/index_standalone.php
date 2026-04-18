<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Dashboard - Website Desa Blanakan' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005c99",
                        "background-light": "#F8F9FA",
                        "background-dark": "#0f1b23",
                        "text-primary-light": "#212529",
                        "text-primary-dark": "#F8F9FA",
                        "text-secondary-light": "#6c757d",
                        "text-secondary-dark": "#adb5bd",
                        "border-light": "#DEE2E6",
                        "border-dark": "#495057",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1f2937",
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
    </script>
    <style>
        .material-symbols-outlined {
          font-variation-settings:
          'FILL' 0,
          'wght' 400,
          'GRAD' 0,
          'opsz' 24
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark dashboard-page">

<!-- Header Navigation -->
<header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark backdrop-blur-sm px-4 md:px-10 py-3 shadow-sm">
    <div class="flex items-center gap-4">
        <h1 class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold leading-tight tracking-[-0.015em]">Dashboard Warga</h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="h-8 w-8 rounded-full bg-cover bg-center" style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap'] ?? 'User') ?>&background=005c99&color=ffffff");'></div>
        <span class="text-sm font-medium"><?= esc($user['nama_lengkap'] ?? 'User') ?></span>
        <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-2 text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">
            <span class="material-symbols-outlined">logout</span>
        </a>
    </div>
</header>

<div class="flex h-full grow flex-col">
    <main class="flex flex-1 justify-center p-4 sm:p-6 md:p-8">
        <div class="flex w-full max-w-7xl flex-col gap-8">
            
            <!-- Welcome Section -->
            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2">
                <div class="flex flex-col">
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl font-black tracking-tight">
                        Selamat Datang, <?= esc($user['nama_lengkap'] ?? 'User') ?>
                    </h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-base">
                        Kelola pengajuan surat dan layanan digital desa dengan mudah.
                    </p>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            <?= session()->getFlashdata('error') ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success') && !session()->getFlashdata('show_success_modal')): ?>
            <div class="rounded-lg border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            <?= session()->getFlashdata('success') ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <h3 class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">Total Surat</h3>
                            <p class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold"><?= $total_surat ?? 0 ?></p>
                        </div>
                        <div class="rounded-full bg-primary/10 p-3">
                            <span class="material-symbols-outlined text-primary">description</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <h3 class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">Menunggu</h3>
                            <p class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold"><?= $surat_menunggu ?? 0 ?></p>
                        </div>
                        <div class="rounded-full bg-yellow-100 dark:bg-yellow-900/50 p-3">
                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">pending</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <h3 class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">Diproses</h3>
                            <p class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold"><?= $surat_processing ?? 0 ?></p>
                        </div>
                        <div class="rounded-full bg-blue-100 dark:bg-blue-900/50 p-3">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">sync</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <h3 class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">Selesai</h3>
                            <p class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold"><?= $surat_approved ?? 0 ?></p>
                        </div>
                        <div class="rounded-full bg-green-100 dark:bg-green-900/50 p-3">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                
                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="flex flex-col rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
                        <div class="border-b border-border-light dark:border-border-dark px-6 py-4">
                            <h3 class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold">Menu Utama</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="<?= base_url('layanan-online') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg border border-border-light dark:border-border-dark hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-primary">add_circle</span>
                                <div>
                                    <p class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">Ajukan Surat</p>
                                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-xs">Buat pengajuan surat baru</p>
                                </div>
                            </a>

                            <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg border border-border-light dark:border-border-dark hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-primary">history</span>
                                <div>
                                    <p class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">Riwayat Surat</p>
                                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-xs">Lihat status pengajuan</p>
                                </div>
                            </a>

                            <a href="<?= base_url('dashboard/profil') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg border border-border-light dark:border-border-dark hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-primary">person</span>
                                <div>
                                    <p class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">Profil Saya</p>
                                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-xs">Kelola data pribadi</p>
                                </div>
                            </a>

                            <a href="<?= base_url('/') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg border border-border-light dark:border-border-dark hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-primary">home</span>
                                <div>
                                    <p class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">Beranda Website</p>
                                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-xs">Kembali ke website utama</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Surat -->
                <div class="lg:col-span-2">
                    <div class="flex flex-col rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
                        <div class="border-b border-border-light dark:border-border-dark px-6 py-4 flex items-center justify-between">
                            <h3 class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold">Pengajuan Terbaru</h3>
                            <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="text-primary text-sm font-medium hover:underline">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="p-6">
                            <?php if (!empty($recent_surat) && is_array($recent_surat)): ?>
                                <div class="space-y-4">
                                    <?php foreach (array_slice($recent_surat, 0, 5) as $surat): ?>
                                    <div class="flex items-center justify-between border-b border-border-light dark:border-border-dark pb-3 last:border-b-0">
                                        <div class="flex flex-col">
                                            <p class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">
                                                <?= esc($surat['jenis_surat'] ?? 'Surat') ?>
                                            </p>
                                            <p class="text-text-secondary-light dark:text-text-secondary-dark text-xs">
                                                <?= date('d M Y', strtotime($surat['created_at'] ?? date('Y-m-d'))) ?>
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <?php 
                                            $status = $surat['status'] ?? 'menunggu';
                                            $statusClasses = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200',
                                                'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                                                'ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200',
                                                'disetujui' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200'
                                            ];
                                            ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClasses[$status] ?? $statusClasses['menunggu'] ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                            <a href="<?= base_url('dashboard/detail-surat/' . ($surat['id'] ?? '')) ?>" class="text-primary hover:underline text-sm">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <span class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark text-4xl mb-4 block">description</span>
                                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada pengajuan surat</p>
                                    <a href="<?= base_url('layanan-online') ?>" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                        Ajukan Surat Sekarang
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Success Modal -->
<?php if (session()->getFlashdata('show_success_modal')): ?>
<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: flex;">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
        <div class="p-6">
            <div class="flex flex-col items-center text-center">
                <!-- Success Icon -->
                <div class="mb-4 rounded-full bg-green-100 p-3">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Surat Berhasil Terkirim!
                </h3>
                
                <!-- Message -->
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    <?= session()->getFlashdata('success') ?? 'Permohonan surat Anda telah berhasil dikirim dan sedang menunggu verifikasi dari admin.' ?>
                </p>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 w-full">
                    <button onclick="closeSuccessModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Tutup
                    </button>
                    <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors text-center">
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Auto close after 10 seconds
setTimeout(() => {
    closeSuccessModal();
}, 10000);

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSuccessModal();
    }
});
</script>
<?php endif; ?>

<!-- Load dashboard components -->
<script>
document.body.classList.add('dashboard-page');
</script>
</body>
</html>