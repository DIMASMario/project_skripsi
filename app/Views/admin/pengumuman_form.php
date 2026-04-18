<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <?= isset($pengumuman) ? 'Edit Pengumuman' : 'Tambah Pengumuman' ?>
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?= $breadcrumb ?></p>
        </div>
        <a href="<?= base_url('admin/pengumuman') ?>" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form id="pengumumanForm" method="post" action="<?= isset($pengumuman) ? base_url('admin/pengumuman/edit/' . $pengumuman['id']) : base_url('admin/pengumuman/tambah') ?>" enctype="application/x-www-form-urlencoded" class="space-y-6">
            <?= csrf_field() ?>
            
            <!-- Judul & Tipe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Judul Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="judul" 
                           name="judul" 
                           value="<?= old('judul', $pengumuman['judul'] ?? '') ?>" 
                           required
                           maxlength="200"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Masukkan judul pengumuman">
                </div>

                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipe <span class="text-red-500">*</span>
                    </label>
                    <select id="tipe" name="tipe" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Pilih Tipe</option>
                        <option value="info" <?= old('tipe', $pengumuman['tipe'] ?? '') == 'info' ? 'selected' : '' ?>>Info</option>
                        <option value="peringatan" <?= old('tipe', $pengumuman['tipe'] ?? '') == 'peringatan' ? 'selected' : '' ?>>Peringatan</option>
                        <option value="urgent" <?= old('tipe', $pengumuman['tipe'] ?? '') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                        <option value="biasa" <?= old('tipe', $pengumuman['tipe'] ?? '') == 'biasa' ? 'selected' : '' ?>>Biasa</option>
                    </select>
                </div>
            </div>

            <!-- Isi Pengumuman -->
            <div>
                <label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Isi Pengumuman <span class="text-red-500">*</span>
                </label>
                <textarea id="isi" 
                          name="isi" 
                          rows="8" 
                          required
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Tulis isi pengumuman di sini..."><?= old('isi', $pengumuman['isi'] ?? '') ?></textarea>
            </div>

            <!-- Prioritas & Target Audience -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prioritas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Prioritas <span class="text-red-500">*</span>
                    </label>
                    <select id="prioritas" name="prioritas" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="rendah" <?= old('prioritas', $pengumuman['prioritas'] ?? 'sedang') == 'rendah' ? 'selected' : '' ?>>Rendah</option>
                        <option value="sedang" <?= old('prioritas', $pengumuman['prioritas'] ?? 'sedang') == 'sedang' ? 'selected' : '' ?>>Sedang</option>
                        <option value="tinggi" <?= old('prioritas', $pengumuman['prioritas'] ?? 'sedang') == 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
                    </select>
                </div>

                <div>
                    <label for="target_audience" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Target Audience
                    </label>
                    <input type="text" 
                           id="target_audience" 
                           name="target_audience" 
                           value="<?= old('target_audience', $pengumuman['target_audience'] ?? '') ?>"
                           maxlength="100"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Contoh: Seluruh Warga, RT 01, dsb">
                </div>
            </div>

            <!-- Tanggal Mulai & Selesai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="tanggal_mulai" 
                           name="tanggal_mulai" 
                           value="<?= old('tanggal_mulai', isset($pengumuman['tanggal_mulai']) ? date('Y-m-d', strtotime($pengumuman['tanggal_mulai'])) : date('Y-m-d')) ?>"
                           required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Selesai
                    </label>
                    <input type="date" 
                           id="tanggal_selesai" 
                           name="tanggal_selesai" 
                           value="<?= old('tanggal_selesai', isset($pengumuman['tanggal_selesai']) && $pengumuman['tanggal_selesai'] ? date('Y-m-d', strtotime($pengumuman['tanggal_selesai'])) : '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kosongkan jika tidak ada batas waktu</p>
                </div>
            </div>

            <!-- Status & Tampil di Beranda -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="draft" <?= old('status', $pengumuman['status'] ?? 'draft') == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="aktif" <?= old('status', $pengumuman['status'] ?? 'draft') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= old('status', $pengumuman['status'] ?? 'draft') == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Opsi Tampilan
                    </label>
                    <div class="flex items-center space-x-3 h-[42px]">
                        <input type="checkbox" 
                               id="tampil_di_beranda" 
                               name="tampil_di_beranda" 
                               value="1"
                               <?= old('tampil_di_beranda', $pengumuman['tampil_di_beranda'] ?? 0) ? 'checked' : '' ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="tampil_di_beranda" class="text-sm text-gray-700 dark:text-gray-300">
                            Tampilkan di halaman beranda
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="<?= base_url('admin/pengumuman') ?>" 
                   class="px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        id="submitBtn"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="material-symbols-outlined text-xl">save</span>
                    <?= isset($pengumuman) ? 'Perbarui' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pengumumanForm');
    if (form) {
        console.log('=== FORM DEBUG ===');
        console.log('Initial method:', form.method);
        console.log('Initial action:', form.action);
        
        // FORCE method to post (lowercase)
        form.method = 'post';
        console.log('After set:', form.method);
        
        form.addEventListener('submit', function(e) {
            console.log('SUBMITTING!');
            console.log('Method at submit:', this.method);
            console.log('Action at submit:', this.action);
            
            // Get all form data
            const formData = new FormData(this);
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
        });
    }
});
</script>

<?= $this->endSection() ?>

