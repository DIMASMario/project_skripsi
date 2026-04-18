<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Tambah Foto Galeri</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Upload foto baru ke galeri desa</p>
            </div>
            <a href="<?= base_url('admin/galeri') ?>" 
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
    <form method="POST" action="<?= base_url('admin/galeri/tambah') ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Foto -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">image</span>
                        Informasi Foto
                    </h3>
                    
                    <div class="space-y-5">
                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Foto <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="judul" 
                                   name="judul" 
                                   value="<?= old('judul') ?>" 
                                   required 
                                   maxlength="100"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Masukkan judul foto...">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <textarea id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4" 
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Deskripsi foto (opsional)..."><?= old('deskripsi') ?></textarea>
                        </div>

                        <!-- Upload Foto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload Foto <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <input type="file" 
                                       id="gambar" 
                                       name="gambar" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif" 
                                       required
                                       class="hidden">
                                <label for="gambar" class="cursor-pointer">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="material-symbols-outlined text-5xl text-gray-400">cloud_upload</span>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Klik untuk memilih file
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Format: JPG, JPEG, PNG, GIF. Maksimal 5MB
                                        </p>
                                        <button type="button" 
                                                onclick="document.getElementById('gambar').click()"
                                                class="mt-2 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                                            <span class="material-symbols-outlined text-lg">browse_gallery</span>
                                            Pilih file...
                                        </button>
                                    </div>
                                </label>
                            </div>
                            <p id="fileName" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden"></p>
                        </div>

                        <!-- Preview -->
                        <div id="imagePreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Preview:
                            </label>
                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                                <img id="previewImg" 
                                     src="" 
                                     alt="Preview" 
                                     class="max-w-full h-auto rounded-lg shadow-md mx-auto"
                                     style="max-height: 400px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Album -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">folder</span>
                        Album
                    </h3>
                    
                    <div>
                        <label for="album" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Album <span class="text-red-500">*</span>
                        </label>
                        <select id="album" 
                                name="album" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Album</option>
                            <option value="Kegiatan" <?= old('album') === 'Kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                            <option value="Budaya" <?= old('album') === 'Budaya' ? 'selected' : '' ?>>Budaya</option>
                            <option value="Pembangunan" <?= old('album') === 'Pembangunan' ? 'selected' : '' ?>>Pembangunan</option>
                            <option value="Wisata" <?= old('album') === 'Wisata' ? 'selected' : '' ?>>Wisata</option>
                            <option value="Sosial" <?= old('album') === 'Sosial' ? 'selected' : '' ?>>Sosial</option>
                            <option value="Upacara" <?= old('album') === 'Upacara' ? 'selected' : '' ?>>Upacara</option>
                            <option value="Lainnya" <?= old('album') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Info Upload -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
                    <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">info</span>
                        Info Upload
                    </h3>
                    <ul class="space-y-2 text-xs text-blue-800 dark:text-blue-300">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                            <span>Ukuran file maksimal: <strong>5MB</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                            <span>Format: <strong>JPG, JPEG, PNG, GIF</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                            <span>Resolusi: <strong>1200×800px</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">check_circle</span>
                            <span>Foto akan otomatis dicompress jika terlalu besar</span>
                        </li>
                    </ul>
                </div>

                <!-- Tips -->
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-6">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-200 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">lightbulb</span>
                        Tips Foto yang Baik
                    </h3>
                    <ul class="space-y-2 text-xs text-green-800 dark:text-green-300">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                            <span>Gunakan pencahayaan yang cukup</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                            <span>Hindari foto yang blur atau gelap</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                            <span>Berikan judul yang deskriptif</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm mt-0.5">arrow_right</span>
                            <span>Pilih album yang sesuai</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors font-medium">
                            <span class="material-symbols-outlined">cloud_upload</span>
                            Upload Foto
                        </button>
                        <a href="<?= base_url('admin/galeri') ?>" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-3 rounded-lg transition-colors font-medium">
                            <span class="material-symbols-outlined">close</span>
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
    const fileInput = document.getElementById('gambar');
    const fileName = document.getElementById('fileName');
    const previewContainer = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Show file name
            fileName.textContent = `File dipilih: ${file.name}`;
            fileName.classList.remove('hidden');
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                this.value = '';
                fileName.classList.add('hidden');
                previewContainer.classList.add('hidden');
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('File harus berupa gambar (JPG, JPEG, PNG, atau GIF)!');
                this.value = '';
                fileName.classList.add('hidden');
                previewContainer.classList.add('hidden');
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            fileName.classList.add('hidden');
            previewContainer.classList.add('hidden');
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value.trim();
        const album = document.getElementById('album').value;
        const gambar = document.getElementById('gambar').files[0];
        
        if (!judul) {
            e.preventDefault();
            alert('Judul foto harus diisi!');
            document.getElementById('judul').focus();
            return false;
        }
        
        if (!album) {
            e.preventDefault();
            alert('Pilih album terlebih dahulu!');
            document.getElementById('album').focus();
            return false;
        }
        
        if (!gambar) {
            e.preventDefault();
            alert('Pilih file foto yang akan diupload!');
            return false;
        }
        
        // Show confirmation
        const confirmed = confirm('Upload foto ini ke galeri?');
        if (!confirmed) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>
