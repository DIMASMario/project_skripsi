<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">
                <?= $count_unread > 0 ? $count_unread . ' notifikasi belum dibaca' : 'Semua notifikasi sudah dibaca' ?>
            </p>
        </div>
        <?php if ($count_unread > 0): ?>
            <button onclick="markAllAsRead()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="material-icons text-sm mr-2">done_all</i>
                Tandai Semua Sudah Dibaca
            </button>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Notifikasi List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <?php if (empty($notifikasi)): ?>
            <div class="p-12 text-center">
                <i class="material-icons text-gray-300 text-6xl mb-4">notifications_none</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada notifikasi</h3>
                <p class="text-gray-500">Anda tidak memiliki notifikasi saat ini</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($notifikasi as $item): ?>
                    <div class="p-4 hover:bg-gray-50 transition-colors <?= $item['is_read'] ? 'bg-white' : 'bg-blue-50' ?>">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 <?= $item['is_read'] ? 'bg-gray-100' : 'bg-blue-100' ?> rounded-full flex items-center justify-center">
                                    <i class="material-icons <?= $item['is_read'] ? 'text-gray-500' : 'text-blue-600' ?>">
                                        <?php
                                            $icon = 'notifications';
                                            if (strpos($item['pesan'], 'surat') !== false) $icon = 'mail';
                                            elseif (strpos($item['pesan'], 'user') !== false || strpos($item['pesan'], 'warga') !== false) $icon = 'person';
                                            elseif (strpos($item['pesan'], 'berita') !== false) $icon = 'article';
                                            elseif (strpos($item['pesan'], 'galeri') !== false) $icon = 'photo_library';
                                            echo $icon;
                                        ?>
                                    </i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm <?= $item['is_read'] ? 'text-gray-700' : 'text-gray-900 font-semibold' ?>">
                                    <?= esc($item['pesan']) ?>
                                </p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-gray-500">
                                        <i class="material-icons text-xs align-middle">schedule</i>
                                        <?= date('d M Y, H:i', strtotime($item['created_at'])) ?>
                                    </span>
                                    <?php if (!$item['is_read']): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Baru
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Link -->
                                <?php if (isset($item['link']) && !empty($item['link'])): ?>
                                    <div class="mt-2">
                                        <a href="<?= base_url($item['link']) ?>" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <span>Lihat Detail</span>
                                            <i class="material-icons text-sm ml-1">arrow_forward</i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Mark as Read Button -->
                            <?php if (!$item['is_read']): ?>
                                <div class="flex-shrink-0">
                                    <button onclick="markAsRead(<?= $item['id'] ?>)" 
                                            class="text-gray-400 hover:text-blue-600 transition-colors"
                                            title="Tandai sudah dibaca">
                                        <i class="material-icons">check_circle</i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info Panel -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="material-icons text-blue-400">info</i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tentang Notifikasi</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Anda akan menerima notifikasi untuk:</p>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li>Pengajuan surat baru dari warga</li>
                        <li>Pendaftaran user baru yang perlu diverifikasi</li>
                        <li>Update status penting dalam sistem</li>
                        <li>Aktivitas yang memerlukan perhatian admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(id) {
    fetch(`<?= base_url('admin/notifikasi/read/') ?>${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    if (!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
        return;
    }
    
    fetch('<?= base_url('admin/notifikasi/read-all') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

<?= $this->endsection() ?>
