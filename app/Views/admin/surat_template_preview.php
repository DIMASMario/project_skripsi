<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Preview Template Surat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?= esc($template['nama_template']) ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?= base_url('admin/surat-template/edit/' . $template['id']) ?>" 
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                    <span class="material-symbols-outlined text-xl">edit</span>
                    Edit Template
                </a>
                <a href="<?= base_url('admin/surat-template') ?>" 
                   class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Template Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                    Informasi Template
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Template</label>
                        <p class="text-gray-900 dark:text-white mt-1"><?= esc($template['nama_template']) ?></p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Surat</label>
                        <p class="text-gray-900 dark:text-white mt-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                <?= esc($template['jenis_surat']) ?>
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-gray-900 dark:text-white mt-1">
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
                        </p>
                    </div>
                    
                    <?php if (!empty($template['keterangan'])): ?>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan</label>
                        <p class="text-gray-900 dark:text-white mt-1 text-sm"><?= esc($template['keterangan']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($template['variabel_template'])): ?>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Variabel Template</label>
                        <div class="mt-2 space-y-1">
                            <?php
                            $variabel = json_decode($template['variabel_template'], true);
                            if (is_array($variabel)) {
                                foreach ($variabel as $var) {
                                    echo '<span class="inline-block px-2 py-1 text-xs font-mono bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded mr-1 mb-1">{' . esc($var) . '}</span>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">
                            <?= date('d/m/Y H:i', strtotime($template['created_at'])) ?> WIB
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">visibility</span>
                        Preview Konten
                    </h3>
                </div>
                
                <div class="p-8">
                    <!-- Preview Content -->
                    <div class="bg-white p-8 rounded border border-gray-300" style="min-height: 600px;">
                        <div class="prose max-w-none">
                            <?= $template['konten_template'] ?>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl">info</span>
                            <div>
                                <h4 class="font-medium text-yellow-900 dark:text-yellow-200 mb-1">Catatan</h4>
                                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                    Ini adalah preview template. Variabel seperti <code class="px-1 py-0.5 bg-yellow-100 dark:bg-yellow-900/50 rounded text-xs">{nama_lengkap}</code>, 
                                    <code class="px-1 py-0.5 bg-yellow-100 dark:bg-yellow-900/50 rounded text-xs">{nik}</code>, dll. akan diganti dengan data sebenarnya saat surat dibuat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
