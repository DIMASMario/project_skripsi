<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Berita</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Perbarui informasi berita</p>
        </div>
        <a href="<?= base_url('admin/berita') ?>" class="flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    </div>
    <?php endif ?>
    
    <?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg dark:bg-green-900/20 dark:border-green-800 dark:text-green-200">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    </div>
    <?php endif ?>
    
    <?php if (isset($errors) && !empty($errors)): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
            <div>
                <p class="font-semibold mb-2">Terdapat kesalahan pada form:</p>
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif ?>

    <form method="POST" action="<?= base_url('admin/berita/edit/' . $berita['id']) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-4">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Berita <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" 
                                   value="<?= old('judul', $berita['judul']) ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                   placeholder="Masukkan judul berita..." required maxlength="255">
                        </div>

                        <div>
                            <label for="konten" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konten Berita <span class="text-red-600">*</span>
                            </label>
                            <textarea id="konten" name="konten" rows="12" 
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                      placeholder="Tulis konten berita di sini..." required><?= old('konten', $berita['konten']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="space-y-4">
                    <!-- Kategori -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kategori <span class="text-red-600">*</span>
                        </label>
                        <select id="kategori" name="kategori" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Pengumuman" <?= old('kategori', $berita['kategori']) === 'Pengumuman' ? 'selected' : '' ?>>Pengumuman</option>
                            <option value="Berita" <?= old('kategori', $berita['kategori']) === 'Berita' ? 'selected' : '' ?>>Berita</option>
                            <option value="Kegiatan" <?= old('kategori', $berita['kategori']) === 'Kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                            <option value="Budaya" <?= old('kategori', $berita['kategori']) === 'Budaya' ? 'selected' : '' ?>>Budaya</option>
                            <option value="Pembangunan" <?= old('kategori', $berita['kategori']) === 'Pembangunan' ? 'selected' : '' ?>>Pembangunan</option>
                            <option value="Sosial" <?= old('kategori', $berita['kategori']) === 'Sosial' ? 'selected' : '' ?>>Sosial</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-600">*</span>
                        </label>
                        <select id="status" name="status" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                            <option value="draft" <?= old('status', $berita['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="publish" <?= old('status', $berita['status']) === 'publish' ? 'selected' : '' ?>>Publish</option>
                        </select>
                    </div>

                    <!-- Gambar -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar Berita</label>
                        <?php if (!empty($berita['gambar'])): ?>
                        <div class="mb-3">
                            <img src="<?= base_url('uploads/berita/' . $berita['gambar']) ?>" 
                                 alt="Current Image" class="w-full rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Gambar saat ini</p>
                        </div>
                        <?php endif ?>
                        <input type="file" id="gambar" name="gambar" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.
                        </p>
                    </div>

                    <!-- Info Berita -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Info Berita</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                                <span class="font-medium text-gray-800 dark:text-white"><?= date('d/m/Y H:i', strtotime($berita['created_at'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Diupdate:</span>
                                <span class="font-medium text-gray-800 dark:text-white"><?= date('d/m/Y H:i', strtotime($berita['updated_at'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Views:</span>
                                <span class="font-medium text-gray-800 dark:text-white"><?= number_format($berita['views']) ?> kali</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-4 mt-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <a href="<?= base_url('admin/berita') ?>" class="flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                <span class="material-symbols-outlined text-sm">close</span>
                Batal
            </a>
            <div class="flex gap-3">
                <button type="submit" name="action" value="draft" class="flex items-center gap-2 px-5 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan sebagai Draft
                </button>
                <button type="submit" name="action" value="publish" class="flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-sm">publish</span>
                    Update & Publish
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>