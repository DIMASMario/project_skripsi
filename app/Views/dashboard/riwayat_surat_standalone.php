<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Riwayat Pengajuan Surat - Pelayanan Digital Desa Blanakan' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
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
                "pending": "#FBBF24",
                "processing": "#3B82F6",
                "completed": "#10B981",
                "rejected": "#EF4444"
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
          font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="relative flex min-h-screen w-full">
    <!-- SideNavBar -->
    <aside class="flex flex-col w-64 bg-white dark:bg-gray-900/50 dark:border-r dark:border-gray-800 p-4 sticky top-0 h-screen shrink-0">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar <?= esc($user['nama_lengkap'] ?? 'User') ?>" style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap'] ?? 'User') ?>&background=005c99&color=ffffff");'></div>
                <div class="flex flex-col">
                    <h1 class="text-[#0c161d] dark:text-white text-base font-semibold leading-normal"><?= esc($user['nama_lengkap'] ?? 'User') ?></h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal"><?= esc($user['email'] ?? 'user@email.com') ?></p>
                </div>
            </div>
            <nav class="flex flex-col gap-2 mt-4">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="<?= base_url('dashboard') ?>">
                    <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">dashboard</span>
                    <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Dashboard</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="<?= base_url('layanan-online') ?>">
                    <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">add_box</span>
                    <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Buat Pengajuan</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 dark:bg-primary/30" href="<?= base_url('dashboard/riwayat-surat') ?>">
                    <span class="material-symbols-outlined text-primary dark:text-white" style="font-variation-settings: 'FILL' 1;">history</span>
                    <p class="text-primary dark:text-white text-sm font-semibold leading-normal">Riwayat Pengajuan</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="<?= base_url('dashboard/profil') ?>">
                    <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">person</span>
                    <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Profil Saya</p>
                </a>
            </nav>
        </div>
        <div class="mt-auto flex flex-col gap-1">
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="<?= base_url('/') ?>">
                <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">home</span>
                <p class="text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal">Website Utama</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/10 transition-colors" href="<?= base_url('auth/logout') ?>">
                <span class="material-symbols-outlined text-red-600 dark:text-red-500">logout</span>
                <p class="text-red-600 dark:text-red-500 text-sm font-medium leading-normal">Keluar</p>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-10">
        <div class="mx-auto max-w-4xl">
            <!-- PageHeading -->
            <header class="flex flex-col gap-2 mb-8">
                <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-tight">Riwayat Pengajuan Surat Anda</h1>
                <p class="text-gray-600 dark:text-gray-400 text-base font-normal leading-normal">Lacak status semua surat yang pernah Anda ajukan melalui layanan digital desa.</p>
            </header>
            
            <!-- Controls -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <!-- SearchBar -->
                <div class="flex-1">
                    <form method="GET" action="<?= current_url() ?>">
                        <label class="flex flex-col h-12 w-full">
                            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                                <div class="text-gray-500 dark:text-gray-400 flex bg-white dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg border border-gray-300 dark:border-gray-700 border-r-0">
                                    <span class="material-symbols-outlined">search</span>
                                </div>
                                <input name="search" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 text-base font-normal leading-normal" placeholder="Cari berdasarkan nama surat..." value="<?= esc($search ?? '') ?>"/>
                                <input type="hidden" name="status" value="<?= esc($statusFilter ?? '') ?>">
                            </div>
                        </label>
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300 shrink-0">Filter:</p>
                    <form method="GET" action="<?= current_url() ?>" id="filterForm">
                        <input type="hidden" name="search" value="<?= esc($search ?? '') ?>">
                        <select name="status" onchange="document.getElementById('filterForm').submit()" class="form-select h-12 w-full md:w-auto rounded-lg bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:ring-primary/50 focus:border-primary text-gray-800 dark:text-gray-200">
                            <option value="">Semua Status</option>
                            <option value="pending" <?= ($statusFilter ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="diproses" <?= ($statusFilter ?? '') === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="selesai" <?= ($statusFilter ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="ditolak" <?= ($statusFilter ?? '') === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <!-- Chips for mobile/tablet view -->
            <div class="md:hidden flex gap-3 p-1 pb-6 overflow-x-auto">
                <a href="<?= current_url() . '?search=' . urlencode($search ?? '') ?>" class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= empty($statusFilter) ? 'bg-primary/20' : 'bg-gray-200 dark:bg-gray-700' ?> px-4">
                    <p class="<?= empty($statusFilter) ? 'text-primary' : 'text-gray-800 dark:text-gray-200' ?> text-sm font-semibold leading-normal">Semua</p>
                </a>
                <a href="<?= current_url() . '?search=' . urlencode($search ?? '') . '&status=pending' ?>" class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= ($statusFilter ?? '') === 'pending' ? 'bg-primary/20' : 'bg-gray-200 dark:bg-gray-700' ?> px-4">
                    <p class="<?= ($statusFilter ?? '') === 'pending' ? 'text-primary' : 'text-gray-800 dark:text-gray-200' ?> text-sm font-medium leading-normal">Pending</p>
                </a>
                <a href="<?= current_url() . '?search=' . urlencode($search ?? '') . '&status=diproses' ?>" class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= ($statusFilter ?? '') === 'diproses' ? 'bg-primary/20' : 'bg-gray-200 dark:bg-gray-700' ?> px-4">
                    <p class="<?= ($statusFilter ?? '') === 'diproses' ? 'text-primary' : 'text-gray-800 dark:text-gray-200' ?> text-sm font-medium leading-normal">Diproses</p>
                </a>
                <a href="<?= current_url() . '?search=' . urlencode($search ?? '') . '&status=selesai' ?>" class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= ($statusFilter ?? '') === 'selesai' ? 'bg-primary/20' : 'bg-gray-200 dark:bg-gray-700' ?> px-4">
                    <p class="<?= ($statusFilter ?? '') === 'selesai' ? 'text-primary' : 'text-gray-800 dark:text-gray-200' ?> text-sm font-medium leading-normal">Selesai</p>
                </a>
                <a href="<?= current_url() . '?search=' . urlencode($search ?? '') . '&status=ditolak' ?>" class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full <?= ($statusFilter ?? '') === 'ditolak' ? 'bg-primary/20' : 'bg-gray-200 dark:bg-gray-700' ?> px-4">
                    <p class="<?= ($statusFilter ?? '') === 'ditolak' ? 'text-primary' : 'text-gray-800 dark:text-gray-200' ?> text-sm font-medium leading-normal">Ditolak</p>
                </a>
            </div>
            
            <!-- Table -->
            <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
                <div class="overflow-x-auto">
                    <?php if (!empty($applications) && count($applications) > 0): ?>
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis Surat</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal Pengajuan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No. Registrasi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <?php foreach ($applications as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?= ucfirst(str_replace('_', ' ', $item['jenis_surat'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <?php
                                    if (!empty($item['nomor_surat'])) {
                                        echo esc($item['nomor_surat']);
                                    } else {
                                        $prefix = strtoupper(substr($item['jenis_surat'], 0, 3));
                                        $year = date('Y', strtotime($item['created_at']));
                                        $month = date('m', strtotime($item['created_at']));
                                        echo $prefix . '/' . $year . '/' . $month . '/' . str_pad($item['id'], 3, '0', STR_PAD_LEFT);
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php
                                    $statusClass = '';
                                    $statusText = ucfirst($item['status']);
                                    switch(strtolower($item['status'])) {
                                        case 'pending':
                                        case 'menunggu':
                                            $statusClass = 'bg-pending/20 text-pending';
                                            $statusText = 'Pending';
                                            break;
                                        case 'diproses':
                                            $statusClass = 'bg-processing/20 text-processing';
                                            $statusText = 'Diproses';
                                            break;
                                        case 'disetujui':
                                        case 'selesai':
                                            $statusClass = 'bg-completed/20 text-completed';
                                            $statusText = 'Selesai';
                                            break;
                                        case 'ditolak':
                                            $statusClass = 'bg-rejected/20 text-rejected';
                                            $statusText = 'Ditolak';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-200 text-gray-800';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if (in_array(strtolower($item['status']), ['selesai', 'disetujui'])): ?>
                                        <?php if (!empty($item['file_surat'])): ?>
                                            <a class="text-primary hover:underline" href="<?= base_url('dashboard/download-surat/' . $item['id']) ?>">Unduh</a>
                                        <?php else: ?>
                                            <a class="text-primary hover:underline" href="<?= base_url('dashboard/detail-surat/' . $item['id']) ?>">Lihat Detail</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a class="text-primary hover:underline" href="<?= base_url('dashboard/detail-surat/' . $item['id']) ?>">Lihat Detail</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-16 px-6">
                        <div class="mx-auto flex items-center justify-center size-16 rounded-full bg-gray-100 dark:bg-gray-800">
                            <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-2xl">description</span>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                            <?= !empty($search) || !empty($statusFilter) ? 'Tidak Ada Hasil Pencarian' : 'Belum Ada Riwayat Surat' ?>
                        </h3>
                        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                            <?= !empty($search) || !empty($statusFilter) ? 'Coba ubah kata kunci atau filter pencarian Anda.' : 'Anda belum pernah mengajukan surat. Mulai dengan membuat pengajuan surat baru.' ?>
                        </p>
                        <div class="mt-6">
                            <?php if (!empty($search) || !empty($statusFilter)): ?>
                                <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Reset Filter
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('layanan-online') ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90">
                                    <span class="material-symbols-outlined mr-2">add_circle</span>
                                    Ajukan Surat Pertama
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
            <nav class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 px-4 sm:px-0 mt-8 pt-6">
                <div class="flex flex-1 w-0">
                    <?php if ($pager->hasPrevious()): ?>
                    <a class="inline-flex items-center pr-1 pt-4 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary" href="<?= $pager->getPreviousPageURI() ?>">
                        <span class="material-symbols-outlined mr-3 h-5 w-5">arrow_back</span>
                        Sebelumnya
                    </a>
                    <?php endif; ?>
                </div>
                <div class="hidden md:flex">
                    <?php foreach ($pager->links() as $link): ?>
                        <?php if ($link['active']): ?>
                            <span class="inline-flex items-center border-t-2 border-primary px-4 pt-4 text-sm font-medium text-primary"><?= $link['title'] ?></span>
                        <?php else: ?>
                            <a class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-700 hover:text-gray-700 dark:hover:text-gray-200" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="flex flex-1 justify-end w-0">
                    <?php if ($pager->hasNext()): ?>
                    <a class="inline-flex items-center pl-1 pt-4 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary" href="<?= $pager->getNextPageURI() ?>">
                        Berikutnya
                        <span class="material-symbols-outlined ml-3 h-5 w-5">arrow_forward</span>
                    </a>
                    <?php endif; ?>
                </div>
            </nav>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>