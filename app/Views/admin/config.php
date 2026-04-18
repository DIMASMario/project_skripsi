<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Konfigurasi</h1>
        <button id="btn-save-config" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="material-icons text-sm mr-2">save</i>
            Simpan Perubahan
        </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Konfigurasi Situs -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="material-icons text-gray-600 mr-2">settings</i>
                Pengaturan Situs
            </h2>
            
            <form id="form-site-config" class="space-y-4">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Situs</label>
                    <input type="text" id="site_name" name="site_name" value="<?= esc($config['site_name'] ?? 'Website Desa') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Situs</label>
                    <textarea id="site_description" name="site_description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= esc($config['site_description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="site_keywords" class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
                    <input type="text" id="site_keywords" name="site_keywords" value="<?= esc($config['site_keywords'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Pisahkan dengan koma</p>
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email Kontak</label>
                    <input type="email" id="contact_email" name="contact_email" value="<?= esc($config['contact_email'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="<?= esc($config['contact_phone'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea id="address" name="address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= esc($config['address'] ?? '') ?></textarea>
                </div>
            </form>
        </div>

        <!-- Konfigurasi Media Sosial -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="material-icons text-gray-600 mr-2">share</i>
                Media Sosial
            </h2>
            
            <form id="form-social-config" class="space-y-4">
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                    <input type="url" id="facebook_url" name="facebook_url" value="<?= esc($config['facebook_url'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://facebook.com/username">
                </div>

                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                    <input type="url" id="instagram_url" name="instagram_url" value="<?= esc($config['instagram_url'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://instagram.com/username">
                </div>

                <div>
                    <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                    <input type="url" id="twitter_url" name="twitter_url" value="<?= esc($config['twitter_url'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://twitter.com/username">
                </div>

                <div>
                    <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-2">YouTube</label>
                    <input type="url" id="youtube_url" name="youtube_url" value="<?= esc($config['youtube_url'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://youtube.com/channel/id">
                </div>

                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?= esc($config['whatsapp_number'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="628123456789">
                    <p class="text-sm text-gray-500 mt-1">Nomor dengan kode negara (tanpa +)</p>
                </div>
            </form>
        </div>

        <!-- Konfigurasi Maintenance -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="material-icons text-gray-600 mr-2">build</i>
                Mode Maintenance
            </h2>
            
            <form id="form-maintenance-config" class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                           <?= ($config['maintenance_mode'] ?? false) ? 'checked' : '' ?>
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                        Aktifkan Mode Maintenance
                    </label>
                </div>

                <div>
                    <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-2">Pesan Maintenance</label>
                    <textarea id="maintenance_message" name="maintenance_message" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Website sedang dalam pemeliharaan..."><?= esc($config['maintenance_message'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="maintenance_end_time" class="block text-sm font-medium text-gray-700 mb-2">Perkiraan Selesai</label>
                    <input type="datetime-local" id="maintenance_end_time" name="maintenance_end_time" 
                           value="<?= $config['maintenance_end_time'] ?? '' ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </form>
        </div>

        <!-- Konfigurasi Email -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                <i class="material-icons text-gray-600 mr-2">email</i>
                Pengaturan Email
            </h2>
            
            <form id="form-email-config" class="space-y-4">
                <div>
                    <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                    <input type="text" id="smtp_host" name="smtp_host" value="<?= esc($config['smtp_host'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-2">Port</label>
                        <input type="number" id="smtp_port" name="smtp_port" value="<?= esc($config['smtp_port'] ?? '587') ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 mb-2">Enkripsi</label>
                        <select id="smtp_encryption" name="smtp_encryption" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Tidak Ada</option>
                            <option value="ssl" <?= ($config['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : '' ?>>SSL</option>
                            <option value="tls" <?= ($config['smtp_encryption'] ?? '') == 'tls' ? 'selected' : '' ?>>TLS</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="smtp_username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="smtp_username" name="smtp_username" value="<?= esc($config['smtp_username'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="smtp_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="smtp_password" name="smtp_password" value="<?= esc($config['smtp_password'] ?? '') ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <button type="button" id="btn-test-email" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <i class="material-icons text-sm mr-2">send</i>
                        Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-save-config').addEventListener('click', function() {
    // Kumpulkan semua data form
    const siteConfig = new FormData(document.getElementById('form-site-config'));
    const socialConfig = new FormData(document.getElementById('form-social-config'));
    const maintenanceConfig = new FormData(document.getElementById('form-maintenance-config'));
    const emailConfig = new FormData(document.getElementById('form-email-config'));
    
    const allConfig = new FormData();
    
    // Gabungkan semua form data
    for (let [key, value] of siteConfig) {
        allConfig.append(key, value);
    }
    for (let [key, value] of socialConfig) {
        allConfig.append(key, value);
    }
    for (let [key, value] of maintenanceConfig) {
        allConfig.append(key, value);
    }
    for (let [key, value] of emailConfig) {
        allConfig.append(key, value);
    }
    
    // Kirim data
    fetch('<?= base_url('admin/config/update') ?>', {
        method: 'POST',
        body: allConfig,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Konfigurasi berhasil disimpan!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan konfigurasi');
    });
});

document.getElementById('btn-test-email').addEventListener('click', function() {
    const emailConfig = new FormData(document.getElementById('form-email-config'));
    
    fetch('<?= base_url('admin/config/test-email') ?>', {
        method: 'POST',
        body: emailConfig,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Email test berhasil dikirim!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim email test');
    });
});
</script>
<?= $this->endsection() ?>