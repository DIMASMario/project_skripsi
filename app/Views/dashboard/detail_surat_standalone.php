<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Detail Pengajuan Surat - Pelayanan Digital Desa Blanakan' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005c99",
                        "background-light": "#f5f7f8",
                        "background-dark": "#0f1b23",
                        "accent-yellow": "#FFD700",
                        "text-light-primary": "#0c161d",
                        "text-light-secondary": "#457ca1",
                        "border-light": "#cddeea",
                        "text-dark-primary": "#f5f7f8",
                        "text-dark-secondary": "#a9c3d4",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2a38",
                        "border-dark": "#344e64"
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
<body class="font-display bg-background-light dark:bg-background-dark text-text-light-primary dark:text-text-dark-primary">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <!-- Header -->
    <header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark/80 backdrop-blur-sm px-4 md:px-10 py-3 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="size-6 text-primary">
                <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
                </svg>
            </div>
            <h2 class="text-text-light-primary dark:text-text-dark-primary text-lg font-bold leading-tight tracking-[-0.015em]">Pelayanan Digital Desa Blanakan</h2>
        </div>
        <div class="flex flex-1 justify-end gap-2 md:gap-4">
            <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary/10 text-text-light-primary dark:text-text-dark-primary dark:bg-primary/20 hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                <span class="material-symbols-outlined text-xl">notifications</span>
            </button>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar <?= esc($user['nama_lengkap'] ?? 'User') ?>" style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap'] ?? 'User') ?>&background=005c99&color=ffffff");'></div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="layout-container flex h-full grow flex-col">
        <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-5 md:py-10">
            <div class="layout-content-container flex flex-col w-full max-w-4xl flex-1 gap-6">
                <!-- Breadcrumb & Header -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-2 px-4">
                        <a class="text-text-light-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal" href="<?= base_url('dashboard') ?>">Beranda</a>
                        <span class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-medium leading-normal">/</span>
                        <a class="text-text-light-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal" href="<?= base_url('dashboard/riwayat-surat') ?>">Pengajuan Saya</a>
                        <span class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-medium leading-normal">/</span>
                        <span class="text-text-light-primary dark:text-text-dark-primary text-sm font-medium leading-normal">Detail Pengajuan</span>
                    </div>
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        <div class="flex min-w-72 flex-col gap-2">
                            <p class="text-text-light-primary dark:text-text-dark-primary text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">Detail Pengajuan: <?= ucfirst(str_replace('_', ' ', $surat['jenis_surat'])) ?></p>
                            <p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-normal leading-normal">Lacak status pengajuan surat Anda secara real-time.</p>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-4">
                    <!-- Left Content -->
                    <div class="lg:col-span-2 flex flex-col gap-8">
                        <!-- Information Card -->
                        <div class="flex flex-col rounded-xl shadow-sm bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark overflow-hidden">
                            <div class="p-6">
                                <p class="text-text-light-primary dark:text-text-dark-primary text-lg font-bold leading-tight tracking-[-0.015em]">Informasi Pengajuan</p>
                                <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm mt-1">Data kunci terkait pengajuan surat Anda.</p>
                            </div>
                            <div class="px-6 pb-6 grid grid-cols-[auto_1fr] gap-x-6">
                                <div class="col-span-2 grid grid-cols-subgrid border-t border-border-light dark:border-border-dark py-4">
                                    <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-normal leading-normal">Nomor Tiket</p>
                                    <p class="text-text-light-primary dark:text-text-dark-primary text-sm font-semibold leading-normal">
                                        <?php
                                        if (!empty($surat['nomor_surat'])) {
                                            echo esc($surat['nomor_surat']);
                                        } else {
                                            $prefix = strtoupper(substr($surat['jenis_surat'], 0, 3));
                                            $year = date('Y', strtotime($surat['created_at']));
                                            $month = date('m', strtotime($surat['created_at']));
                                            echo $prefix . '-' . $year . $month . '-' . str_pad($surat['id'], 3, '0', STR_PAD_LEFT);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-span-2 grid grid-cols-subgrid border-t border-border-light dark:border-border-dark py-4">
                                    <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-normal leading-normal">Jenis Surat</p>
                                    <p class="text-text-light-primary dark:text-text-dark-primary text-sm font-semibold leading-normal"><?= ucfirst(str_replace('_', ' ', $surat['jenis_surat'])) ?></p>
                                </div>
                                <div class="col-span-2 grid grid-cols-subgrid border-t border-border-light dark:border-border-dark py-4">
                                    <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-normal leading-normal">Nama Pemohon</p>
                                    <p class="text-text-light-primary dark:text-text-dark-primary text-sm font-semibold leading-normal"><?= esc($user['nama_lengkap']) ?></p>
                                </div>
                                <div class="col-span-2 grid grid-cols-subgrid border-t border-border-light dark:border-border-dark py-4">
                                    <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-normal leading-normal">Tanggal Pengajuan</p>
                                    <p class="text-text-light-primary dark:text-text-dark-primary text-sm font-semibold leading-normal"><?= date('d M Y', strtotime($surat['created_at'])) ?></p>
                                </div>
                                <div class="col-span-2 grid grid-cols-subgrid border-t border-border-light dark:border-border-dark py-4">
                                    <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-normal leading-normal">Estimasi Selesai</p>
                                    <p class="text-text-light-primary dark:text-text-dark-primary text-sm font-semibold leading-normal">
                                        <?php
                                        $estimasi = date('d M Y', strtotime($surat['created_at'] . ' +3 days'));
                                        echo $estimasi;
                                        ?>
                                    </p>
                                </div>
                                <?php if (!empty($surat['keterangan'])): ?>
                                <div class="col-span-2 grid grid-cols-subgrid border-t border-border-light dark:border-border-dark py-4">
                                    <p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-normal leading-normal">Keterangan</p>
                                    <p class="text-text-light-primary dark:text-text-dark-primary text-sm font-semibold leading-normal"><?= esc($surat['keterangan']) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <?php if (in_array(strtolower($surat['status']), ['selesai', 'disetujui'])): ?>
                        <div class="flex flex-col rounded-xl shadow-sm bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/50 p-6">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-3xl">check_circle</span>
                                <div>
                                    <h3 class="font-bold text-green-900 dark:text-green-100">Surat Sudah Selesai</h3>
                                    <p class="text-sm text-green-800 dark:text-green-200 mt-1">Surat Anda telah selesai diproses dan siap untuk diunduh. Silakan klik tombol "Unduh Surat" di panel sebelah kanan.</p>
                                </div>
                            </div>
                        </div>
                        <?php elseif (strtolower($surat['status']) === 'ditolak'): ?>
                        <div class="flex flex-col rounded-xl shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 p-6">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl">error</span>
                                <div>
                                    <h3 class="font-bold text-red-900 dark:text-red-100">Pengajuan Ditolak</h3>
                                    <p class="text-sm text-red-800 dark:text-red-200 mt-1">
                                        Mohon maaf, pengajuan surat Anda ditolak.
                                        <?php if (!empty($surat['catatan_admin'])): ?>
                                        Alasan: <?= esc($surat['catatan_admin']) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="flex flex-col rounded-xl shadow-sm bg-accent-yellow/10 dark:bg-accent-yellow/20 border border-accent-yellow/30 p-6">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-accent-yellow/90 dark:text-accent-yellow text-3xl">info</span>
                                <div>
                                    <h3 class="font-bold text-yellow-900 dark:text-accent-yellow">Perlu Diperhatikan</h3>
                                    <p class="text-sm text-yellow-800 dark:text-yellow-100 mt-1">Harap membawa dokumen asli (KTP & KK) saat pengambilan surat di kantor desa.</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <div class="rounded-xl shadow-sm bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark p-6">
                            <h3 class="text-lg font-bold leading-tight tracking-[-0.015em] mb-6">Status Pengajuan</h3>
                            <ol class="relative border-s border-border-light dark:border-border-dark">
                                <?php
                                $statusSteps = [
                                    'menunggu' => [
                                        'title' => 'Pengajuan Diterima',
                                        'icon' => 'inventory_2',
                                        'completed' => true
                                    ],
                                    'diproses' => [
                                        'title' => 'Verifikasi Dokumen oleh Petugas',
                                        'icon' => 'hourglass_top',
                                        'completed' => in_array(strtolower($surat['status']), ['diproses', 'selesai', 'disetujui'])
                                    ],
                                    'disetujui' => [
                                        'title' => 'Surat Diproses & Ditandatangani',
                                        'icon' => 'edit_document',
                                        'completed' => in_array(strtolower($surat['status']), ['selesai', 'disetujui'])
                                    ],
                                    'selesai' => [
                                        'title' => 'Selesai & Siap Diunduh',
                                        'icon' => 'done',
                                        'completed' => in_array(strtolower($surat['status']), ['selesai', 'disetujui'])
                                    ]
                                ];

                                $currentStatus = strtolower($surat['status']);
                                $isRejected = $currentStatus === 'ditolak';
                                
                                foreach ($statusSteps as $stepKey => $step):
                                    $isActive = $stepKey === $currentStatus && !$isRejected;
                                    $isCompleted = $step['completed'] && !$isRejected;
                                    $isLast = $stepKey === 'selesai';
                                ?>
                                <li class="<?= !$isLast ? 'mb-8' : '' ?> ms-8">
                                    <span class="absolute flex items-center justify-center w-6 h-6 <?= $isCompleted ? 'bg-green-100 text-green-600' : ($isActive ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400') ?> rounded-full -start-3 ring-4 ring-surface-light dark:ring-surface-dark">
                                        <span class="material-symbols-outlined text-sm"><?= $isCompleted ? 'done' : $step['icon'] ?></span>
                                    </span>
                                    <h4 class="flex items-center mb-1 text-base font-semibold <?= $isCompleted ? 'text-text-light-primary dark:text-text-dark-primary' : ($isActive ? 'text-primary' : 'text-gray-500 dark:text-gray-400') ?>">
                                        <?= $step['title'] ?>
                                        <?= $isCompleted ? ' ✓' : '' ?>
                                    </h4>
                                    <time class="block mb-2 text-xs font-normal leading-none <?= $isCompleted || $isActive ? 'text-text-light-secondary dark:text-text-dark-secondary' : 'text-gray-400 dark:text-gray-500' ?>">
                                        <?php
                                        if ($isCompleted || $isActive) {
                                            if (!empty($surat['updated_at'])) {
                                                echo date('d M Y, H:i', strtotime($surat['updated_at'])) . ' WIB';
                                            } else {
                                                echo date('d M Y, H:i', strtotime($surat['created_at'])) . ' WIB';
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </time>
                                </li>
                                <?php endforeach; ?>

                                <!-- Rejected Status -->
                                <?php if ($isRejected): ?>
                                <li class="ms-8">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-red-100 text-red-600 rounded-full -start-3 ring-4 ring-surface-light dark:ring-surface-dark">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                    </span>
                                    <h4 class="mb-1 text-base font-semibold text-red-600 dark:text-red-400">Pengajuan Ditolak</h4>
                                    <time class="block mb-2 text-xs font-normal leading-none text-text-light-secondary dark:text-text-dark-secondary">
                                        <?= date('d M Y, H:i', strtotime($surat['updated_at'] ?? $surat['created_at'])) ?> WIB
                                    </time>
                                    <?php if (!empty($surat['catatan_admin'])): ?>
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2"><?= esc($surat['catatan_admin']) ?></p>
                                    <?php endif; ?>
                                </li>
                                <?php endif; ?>
                            </ol>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-3 mt-8 pt-6 border-t border-border-light dark:border-border-dark">
                                <?php if (in_array(strtolower($surat['status']), ['selesai', 'disetujui']) && !empty($surat['file_surat'])): ?>
                                    <a href="<?= base_url('dashboard/download-surat/' . $surat['id']) ?>" class="flex w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 bg-primary text-white gap-2 text-base font-bold leading-normal tracking-[0.015em] px-4 hover:bg-primary/90 transition-colors">
                                        <span class="material-symbols-outlined">download</span>
                                        <span>Unduh Surat (PDF)</span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (in_array(strtolower($surat['status']), ['menunggu', 'diproses'])): ?>
                                    <button onclick="confirmCancel()" class="flex w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 bg-transparent text-red-600 dark:text-red-400 gap-2 text-base font-bold leading-normal tracking-[0.015em] px-4 hover:bg-red-500/10 transition-colors border border-red-300 dark:border-red-600">
                                        <span class="material-symbols-outlined">cancel</span>
                                        <span>Batalkan Pengajuan</span>
                                    </button>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="flex w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 bg-transparent text-text-light-primary dark:text-text-dark-primary gap-2 text-base font-medium leading-normal tracking-[0.015em] px-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors border border-border-light dark:border-border-dark">
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    <span>Kembali ke Daftar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-surface-light dark:bg-surface-dark border-t border-border-light dark:border-border-dark mt-auto">
        <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex items-center mb-4 sm:mb-0 space-x-3">
                    <div class="size-6 text-primary">
                        <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <span class="self-center text-xl font-semibold whitespace-nowrap text-text-light-primary dark:text-text-dark-primary">Desa Blanakan</span>
                </div>
                <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-text-light-secondary dark:text-text-dark-secondary sm:mb-0">
                    <li><a class="hover:underline me-4 md:me-6" href="#">Bantuan</a></li>
                    <li><a class="hover:underline me-4 md:me-6" href="#">Kebijakan Privasi</a></li>
                    <li><a class="hover:underline" href="#">Kontak</a></li>
                </ul>
            </div>
            <hr class="my-6 border-border-light dark:border-border-dark sm:mx-auto lg:my-8"/>
            <span class="block text-sm text-text-light-secondary dark:text-text-dark-secondary sm:text-center">© <?= date('Y') ?> <a class="hover:underline" href="#">Pemerintah Desa Blanakan™</a>. Hak Cipta Dilindungi.</span>
        </div>
    </footer>
</div>

<!-- JavaScript for Cancel Confirmation -->
<script>
function confirmCancel() {
    if (confirm('Apakah Anda yakin ingin membatalkan pengajuan surat ini? Tindakan ini tidak dapat dibatalkan.')) {
        window.location.href = '<?= base_url('dashboard/cancel-surat/' . $surat['id']) ?>';
    }
}
</script>
</body>
</html>