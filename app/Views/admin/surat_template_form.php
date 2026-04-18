<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                    <?= isset($template) ? 'Edit Template Surat' : 'Tambah Template Surat' ?>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <?= isset($template) ? 'Perbarui template surat yang sudah ada' : 'Buat template surat baru untuk kemudahan pembuatan surat' ?>
                </p>
            </div>
            <a href="<?= base_url('admin/surat-template') ?>" 
               class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="<?= isset($template) ? base_url('admin/surat-template/edit/' . $template['id']) : base_url('admin/surat-template/tambah') ?>" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Form Fields -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">description</span>
                        Informasi Template
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Nama Template -->
                        <div>
                            <label for="nama_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Template <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_template" 
                                   name="nama_template" 
                                   value="<?= old('nama_template', $template['nama_template'] ?? '') ?>"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Contoh: Template Surat Keterangan Domisili"
                                   required>
                            <?php if (isset($errors['nama_template'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['nama_template'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Jenis Surat -->
                        <div>
                            <label for="jenis_surat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Surat <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_surat" 
                                    name="jenis_surat"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="domisili" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'domisili') ? 'selected' : '' ?>>Surat Keterangan Domisili</option>
                                <option value="usaha" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'usaha') ? 'selected' : '' ?>>Surat Keterangan Usaha</option>
                                <option value="skck" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'skck') ? 'selected' : '' ?>>Surat Pengantar SKCK</option>
                                <option value="kelahiran" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'kelahiran') ? 'selected' : '' ?>>Surat Keterangan Kelahiran</option>
                                <option value="kematian" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'kematian') ? 'selected' : '' ?>>Surat Keterangan Kematian</option>
                                <option value="tidak_mampu" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'tidak_mampu') ? 'selected' : '' ?>>Surat Keterangan Tidak Mampu</option>
                                <option value="lainnya" <?= (old('jenis_surat', $template['jenis_surat'] ?? '') === 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                            <?php if (isset($errors['jenis_surat'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['jenis_surat'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" 
                                      name="keterangan" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Deskripsi singkat tentang template ini..."><?= old('keterangan', $template['keterangan'] ?? '') ?></textarea>
                        </div>

                        <!-- Upload File Template Word -->
                        <div>
                            <label for="file_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File Template (Word) <span class="text-red-500">*</span>
                            </label>
                            
                            <?php if (isset($template) && !empty($template['file_template'])): ?>
                                <div class="mb-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">description</span>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-900 dark:text-blue-200">File saat ini:</p>
                                            <p class="text-sm text-blue-700 dark:text-blue-300"><?= basename($template['file_template']) ?></p>
                                        </div>
                                        <a href="<?= base_url('uploads/templates/' . $template['file_template']) ?>" 
                                           download
                                           class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
                                            <span class="material-symbols-outlined text-base">download</span>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" 
                                   id="file_template" 
                                   name="file_template" 
                                   accept=".doc,.docx"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300"
                                   <?= !isset($template) ? 'required' : '' ?>>
                            <?php if (isset($errors['file_template'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['file_template'] ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                Upload file Word (.doc atau .docx). Max 5MB. <?= isset($template) ? 'Biarkan kosong jika tidak ingin mengubah file.' : '' ?>
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="aktif" <?= (old('status', $template['status'] ?? 'aktif') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= (old('status', $template['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>Non-aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Panduan Pengisian Template -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        Panduan Template Word
                    </h3>
                    
                    <div class="space-y-3 text-sm text-blue-800 dark:text-blue-300">
                        <div class="flex gap-2">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            <p>Buat template surat dalam format Microsoft Word (.docx)</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            <p>Gunakan placeholder untuk data yang akan diisi warga, contoh: <code class="bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 rounded">[NAMA_LENGKAP]</code>, <code class="bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 rounded">[NIK]</code>, <code class="bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 rounded">[ALAMAT]</code></p>
                        </div>
                        <div class="flex gap-2">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            <p>Warga akan mengunduh template, mengisi data, dan mengupload kembali</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            <p>Ukuran file maksimal 5MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Guide & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Variabel Template -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">code</span>
                        Variabel Template
                    </h3>
                    
                    <div>
                        <label for="variabel_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Variabel (JSON Array)
                        </label>
                        <textarea id="variabel_template" 
                                  name="variabel_template" 
                                  rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-xs"
                                  placeholder='["nama_lengkap","nik","alamat"]'><?= old('variabel_template', $template['variabel_template'] ?? '') ?></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Daftar variabel yang digunakan dalam format JSON array
                        </p>
                    </div>
                </div>

                <!-- Common Variables Guide -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">lightbulb</span>
                        Variabel Umum
                    </h4>
                    <div class="space-y-2 text-xs">
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{nomor_surat}</code> - Nomor surat</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{nama_lengkap}</code> - Nama pemohon</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{nik}</code> - NIK pemohon</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{tempat_lahir}</code> - Tempat lahir</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{tanggal_lahir}</code> - Tanggal lahir</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{jenis_kelamin}</code> - Jenis kelamin</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{pekerjaan}</code> - Pekerjaan</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{alamat}</code> - Alamat lengkap</p>
                        <p class="text-blue-800 dark:text-blue-300"><code class="bg-blue-100 dark:bg-blue-900/50 px-1 py-0.5 rounded">{keperluan}</code> - Keperluan surat</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors font-medium flex items-center justify-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-xl">save</span>
                        <?= isset($template) ? 'Perbarui Template' : 'Simpan Template' ?>
                    </button>
                    
                    <a href="<?= base_url('admin/surat-template') ?>" 
                       class="w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white px-4 py-3 rounded-lg transition-colors font-medium flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl">close</span>
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
