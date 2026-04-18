<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Formulir Pengajuan Surat</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ajukan surat yang Anda butuhkan secara online</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            
            <!-- Alert Info -->
            <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg">
                <div class="flex gap-3">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 flex-shrink-0">info</span>
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-1">Persyaratan Pengajuan</h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 list-disc list-inside space-y-1">
                            <li>Nomor KTP wajib diisi</li>
                            <li>Nomor KK bersifat opsional</li>
                            <li>Isi data dengan lengkap dan benar</li>
                            <li>Admin akan memproses dalam 1-2 hari kerja</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form id="formPengajuanSurat" action="<?= base_url('surat-pengajuan/proses') ?>" method="POST">
                <?= csrf_field() ?>

                <!-- Pilih Jenis Surat -->
                <div class="mb-6">
                    <label for="jenis_surat" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_surat" name="jenis_surat" required 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">-- Pilih Jenis Surat --</option>
                        <?php foreach ($jenis_surat_list as $key => $namaJenis): ?>
                            <option value="<?= $key ?>" <?= $jenis_surat_selected === $key ? 'selected' : '' ?>>
                                <?= esc($namaJenis) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">*Wajib dipilih</p>
                </div>

                <!-- Data dari User -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" 
                               value="<?= esc($user['nama_lengkap'] ?? '') ?>" 
                               readonly
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Alamat Lengkap
                        </label>
                        <input type="text" id="alamat" name="alamat" 
                               value="<?= esc($user['alamat'] ?? '') ?>" 
                               readonly
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- NIK dan KK -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nik" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Nomor KTP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nik" name="nik" 
                               value="<?= old('nik') ?>"
                               pattern="[0-9]{16}"
                               maxlength="16"
                               required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Masukkan 16 digit KTP">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">*Wajib diisi (16 digit)</p>
                    </div>

                    <div>
                        <label for="no_kk" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Nomor KK
                        </label>
                        <input type="text" id="no_kk" name="no_kk" 
                               value="<?= old('no_kk') ?>"
                               pattern="[0-9]{16}"
                               maxlength="16"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Masukkan 16 digit KK (opsional)">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Opsional (16 digit)</p>
                    </div>
                </div>

                <!-- Status Perkawinan (untuk SKD) -->
                <div id="statusPerkawinanDiv" class="mb-6 hidden">
                    <label for="status_perkawinan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Status Perkawinan <span class="text-red-500">*</span>
                    </label>
                    <select id="status_perkawinan" name="status_perkawinan" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">-- Pilih Status --</option>
                        <?php foreach ($status_perkawinan_list as $key => $namaStatus): ?>
                            <option value="<?= $key ?>"><?= esc($namaStatus) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">*Wajib dipilih untuk SKD (Surat Keterangan Desa)</p>
                </div>

                <!-- Keperluan/Alasan -->
                <div class="mb-6">
                    <label for="keperluan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Keperluan/Alasan Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keperluan" name="keperluan" 
                              rows="4"
                              required
                              minlength="10"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                              placeholder="Jelaskan keperluan atau alasan Anda membutuhkan surat ini...">
                    </textarea><?= old('keperluan') ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">*Minimum 10 karakter</p>
                </div>

                <!-- Error Messages -->
                <?php if (session()->has('errors')): ?>
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                        <h4 class="font-semibold text-red-900 dark:text-red-100 mb-2">Validasi Gagal:</h4>
                        <ul class="text-sm text-red-800 dark:text-red-200 list-disc list-inside space-y-1">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">send</span>
                        Kirim Pengajuan
                    </button>
                    <a href="<?= base_url('surat-pengajuan') ?>" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-lg transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Informasi Jenis Surat</h3>
            <div id="jenisSuratInfo" class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                <p>Pilih jenis surat terlebih dahulu untuk melihat informasi lengkap...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide status perkawinan untuk SKD
document.getElementById('jenis_surat').addEventListener('change', function() {
    const statusDiv = document.getElementById('statusPerkawinanDiv');
    const statusInput = document.getElementById('status_perkawinan');
    
    if (this.value === 'desa') {
        statusDiv.classList.remove('hidden');
        statusInput.required = true;
    } else {
        statusDiv.classList.add('hidden');
        statusInput.required = false;
        statusInput.value = '';
    }
});

// Form validation
document.getElementById('formPengajuanSurat').addEventListener('submit', function(e) {
    const nik = document.getElementById('nik').value;
    const noKk = document.getElementById('no_kk').value;
    
    if (nik.length !== 16) {
        e.preventDefault();
        alert('Nomor KTP harus 16 digit');
        return false;
    }
    
    if (noKk && noKk.length !== 16) {
        e.preventDefault();
        alert('Nomor KK harus 16 digit jika diisi');
        return false;
    }
});
</script>
<?php $this->endSection() ?>
