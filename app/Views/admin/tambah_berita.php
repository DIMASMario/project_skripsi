<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Tambah Berita Baru</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Buat berita atau artikel baru untuk website desa</p>
            </div>
            <a href="<?= base_url('admin/berita') ?>" 
               class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined">error</span>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" action="<?= base_url('admin/berita/tambah') ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">article</span>
                        Konten Berita
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Berita <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="judul" 
                                   name="judul" 
                                   value="<?= old('judul') ?>" 
                                   required 
                                   maxlength="255"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Masukkan judul berita yang menarik...">
                        </div>

                        <!-- Konten -->
                        <div>
                            <label for="konten" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konten Berita <span class="text-red-500">*</span>
                            </label>
                            <textarea id="konten" 
                                      name="konten" 
                                      rows="12" 
                                      required
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm"
                                      placeholder="Tulis konten berita di sini..."><?= old('konten') ?></textarea>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span class="material-symbols-outlined text-sm align-middle">info</span>
                                Minimal 50 karakter
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Pengaturan Publikasi -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">settings</span>
                        Pengaturan
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori" 
                                    name="kategori" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Kategori</option>
                                <option value="Pengumuman" <?= old('kategori') === 'Pengumuman' ? 'selected' : '' ?>>Pengumuman</option>
                                <option value="Berita" <?= old('kategori') === 'Berita' ? 'selected' : '' ?>>Berita</option>
                                <option value="Kegiatan" <?= old('kategori') === 'Kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                                <option value="Budaya" <?= old('kategori') === 'Budaya' ? 'selected' : '' ?>>Budaya</option>
                                <option value="Pembangunan" <?= old('kategori') === 'Pembangunan' ? 'selected' : '' ?>>Pembangunan</option>
                                <option value="Sosial" <?= old('kategori') === 'Sosial' ? 'selected' : '' ?>>Sosial</option>
                                <option value="Ekonomi" <?= old('kategori') === 'Ekonomi' ? 'selected' : '' ?>>Ekonomi</option>
                                <option value="Kesehatan" <?= old('kategori') === 'Kesehatan' ? 'selected' : '' ?>>Kesehatan</option>
                                <option value="Pendidikan" <?= old('kategori') === 'Pendidikan' ? 'selected' : '' ?>>Pendidikan</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Publikasi <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="draft" <?= old('status', 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="publish" <?= old('status') === 'publish' ? 'selected' : '' ?>>Publish</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Gambar -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">image</span>
                        Gambar Berita
                    </h3>
                    
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload Gambar
                        </label>
                        <input type="file" 
                               id="gambar" 
                               name="gambar" 
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400">
                        <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-xs text-blue-800 dark:text-blue-300 flex items-start gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Format: JPG, JPEG, PNG, GIF, WebP<br>Maksimal ukuran: 5MB</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors font-semibold">
                            <span class="material-symbols-outlined text-xl">save</span>
                            Simpan Berita
                        </button>
                        
                        <a href="<?= base_url('admin/berita') ?>" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            <span class="material-symbols-outlined text-xl">cancel</span>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const judulInput = document.getElementById('judul');
    
    // Character counter untuk konten
    const kontenTextarea = document.getElementById('konten');
    if (kontenTextarea) {
        const charInfo = document.createElement('p');
        charInfo.className = 'mt-2 text-xs text-gray-500 dark:text-gray-400';
        charInfo.innerHTML = '<span class="material-symbols-outlined text-sm align-middle">edit_note</span> <span id="char-count">0</span> karakter';
        kontenTextarea.parentNode.appendChild(charInfo);
        
        kontenTextarea.addEventListener('input', function() {
            document.getElementById('char-count').textContent = this.value.length;
        });
        
        // Initial count
        if (kontenTextarea.value) {
            document.getElementById('char-count').textContent = kontenTextarea.value.length;
        }
    }
    
    // Preview gambar
    const gambarInput = document.getElementById('gambar');
    if (gambarInput) {
        gambarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB');
                    this.value = '';
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
