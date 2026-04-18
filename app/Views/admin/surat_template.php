<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Template Surat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola template surat untuk kemudahan pembuatan surat</p>
            </div>
            <a href="<?= base_url('admin/surat-template/tambah') ?>" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Template
            </a>
        </div>
    </div>

    <!-- Template List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">description</span>
                Daftar Template Surat
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Template</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Surat</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat</th>
                        <th class="py-4 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (!empty($templates)): ?>
                        <?php foreach ($templates as $template): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">description</span>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white"><?= esc($template['nama_template']) ?></div>
                                            <?php if (!empty($template['keterangan'])): ?>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5"><?= esc($template['keterangan']) ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($template['file_template'])): ?>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-mono"><?= esc($template['file_template']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <?= esc($template['jenis_surat']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if ($template['status'] === 'aktif'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            <span class="material-symbols-outlined text-sm mr-1">check_circle</span>
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <span class="material-symbols-outlined text-sm mr-1">block</span>
                                            Non-aktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-900 dark:text-white"><?= date('d/m/Y', strtotime($template['created_at'])) ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= date('H:i', strtotime($template['created_at'])) ?> WIB</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <?php if (!empty($template['file_template'])): ?>
                                            <a href="<?= base_url('uploads/templates/' . $template['file_template']) ?>" 
                                               download
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors" 
                                               title="Download Template">
                                                <span class="material-symbols-outlined text-base">download</span>
                                                Download
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('admin/surat-template/edit/' . $template['id']) ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors" 
                                           title="Edit">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                            Edit
                                        </a>
                                        <button onclick="if(confirm('Yakin hapus template ini?')) window.location.href='<?= base_url('admin/surat-template/hapus/' . $template['id']) ?>'" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors" 
                                                title="Hapus">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl mb-3">description</span>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada template surat</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Klik tombol "Tambah Template" untuk membuat template baru</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>