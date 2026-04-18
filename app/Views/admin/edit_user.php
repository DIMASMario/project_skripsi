<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $breadcrumb ?></p>
        </div>
        <a href="<?= base_url('admin/users') ?>" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2">error</span>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2">error</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Edit User Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                <span class="material-symbols-outlined mr-2">edit</span>
                Edit Data User
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Perbarui informasi pengguna di bawah ini
            </p>
        </div>

        <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post" class="p-6">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column - Personal Info -->
                <div class="space-y-4">
                    <h4 class="font-medium text-primary mb-3 flex items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">badge</span>
                        Data Pribadi
                    </h4>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" 
                               value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NIK
                        </label>
                        <input type="text" id="nik" name="nik" 
                               value="<?= old('nik', $user['nik'] ?? '') ?>"
                               pattern="[0-9]{16}" maxlength="16"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <p class="text-xs text-gray-500 mt-1">16 digit angka</p>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tempat Lahir
                        </label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" 
                               value="<?= old('tempat_lahir', $user['tempat_lahir'] ?? '') ?>"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                               value="<?= old('tanggal_lahir', $user['tanggal_lahir'] ?? '') ?>"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Kelamin
                        </label>
                        <select id="jenis_kelamin" name="jenis_kelamin" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">-- Pilih --</option>
                            <option value="L" <?= old('jenis_kelamin', $user['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= old('jenis_kelamin', $user['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    
                    <!-- Agama -->
                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Agama
                        </label>
                        <select id="agama" name="agama" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">-- Pilih --</option>
                            <option value="Islam" <?= old('agama', $user['agama'] ?? '') === 'Islam' ? 'selected' : '' ?>>Islam</option>
                            <option value="Kristen" <?= old('agama', $user['agama'] ?? '') === 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                            <option value="Katolik" <?= old('agama', $user['agama'] ?? '') === 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                            <option value="Hindu" <?= old('agama', $user['agama'] ?? '') === 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                            <option value="Buddha" <?= old('agama', $user['agama'] ?? '') === 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                            <option value="Konghucu" <?= old('agama', $user['agama'] ?? '') === 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                            <option value="Lainnya" <?= old('agama', $user['agama'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column - Contact & Address -->
                <div class="space-y-4">
                    <h4 class="font-medium text-green-600 dark:text-green-400 mb-3 flex items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">contact_mail</span>
                        Kontak & Alamat
                    </h4>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" 
                               value="<?= old('email', $user['email']) ?>" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            No. HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="no_hp" name="no_hp" 
                               value="<?= old('no_hp', $user['no_hp']) ?>" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <p class="text-xs text-gray-500 mt-1">Format: 08xxxxxxxxx atau +62xxxxxxxxx</p>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"><?= old('alamat', $user['alamat']) ?></textarea>
                    </div>

                    <!-- RT/RW -->
                    <div>
                        <label for="rt_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            RT/RW
                        </label>
                        <input type="text" id="rt_rw" name="rt_rw" 
                               value="<?= old('rt_rw', $user['rt_rw'] ?? '') ?>"
                               placeholder="Contoh: 001/002"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Desa -->
                    <div>
                        <label for="desa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Desa/Kelurahan
                        </label>
                        <input type="text" id="desa" name="desa" 
                               value="<?= old('desa', $user['desa'] ?? '') ?>"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Akun <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="menunggu" <?= old('status', $user['status']) === 'menunggu' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                            <option value="disetujui" <?= old('status', $user['status']) === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= old('status', $user['status']) === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            <option value="suspended" <?= old('status', $user['status']) === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="<?= base_url('admin/users') ?>" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['nama_lengkap', 'email', 'no_hp', 'alamat', 'status'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    // Email validation
    const email = document.getElementById('email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.value && !emailPattern.test(email.value)) {
        isValid = false;
        email.classList.add('border-red-500');
    }
    
    // NIK validation (if provided)
    const nik = document.getElementById('nik');
    if (nik.value && !/^\d{16}$/.test(nik.value)) {
        isValid = false;
        nik.classList.add('border-red-500');
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon periksa kembali data yang Anda masukkan');
    }
});

// Auto-format phone number
document.getElementById('no_hp').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d+]/g, '');
    if (value.startsWith('08')) {
        value = '+62' + value.substring(1);
    }
    e.target.value = value;
});

// Auto-format NIK (numbers only)
document.getElementById('nik').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^\d]/g, '');
});
</script>
<?= $this->endSection() ?>