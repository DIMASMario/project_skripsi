<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Konfigurasi Payment</h1>
                <p class="text-gray-600 dark:text-gray-400">Atur payment gateway untuk pembayaran layanan online</p>
            </div>
            <a href="<?= base_url('admin/config') ?>" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
            <span class="text-green-800 dark:text-green-300"><?= session()->getFlashdata('success') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
            <span class="text-red-800 dark:text-red-300"><?= session()->getFlashdata('error') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Configuration Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="<?= base_url('admin/config-payment') ?>" method="POST" class="p-6">
            <?= csrf_field() ?>

            <!-- Payment Status Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">toggle_on</span>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Payment</h2>
                </div>

                <div class="space-y-4">
                    <!-- Enable Payment -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="flex-1">
                            <label for="enable_payment" class="block text-sm font-medium text-gray-900 dark:text-white mb-1">
                                Aktifkan Payment Gateway
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Izinkan pembayaran online untuk layanan administrasi
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   id="enable_payment" 
                                   name="enable_payment" 
                                   class="sr-only peer" 
                                   <?= ($config['enable_payment'] ?? false) ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label for="payment_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mode Payment
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_mode" 
                                name="payment_mode" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                required>
                            <option value="sandbox" <?= ($config['payment_mode'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' ?>>Sandbox (Testing)</option>
                            <option value="production" <?= ($config['payment_mode'] ?? 'sandbox') == 'production' ? 'selected' : '' ?>>Production (Live)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan Sandbox untuk testing, Production untuk live</p>
                    </div>
                </div>
            </div>

            <!-- Payment Gateway Settings -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">credit_card</span>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Gateway</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Gateway -->
                    <div class="md:col-span-2">
                        <label for="payment_gateway" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Gateway
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_gateway" 
                                name="payment_gateway" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                onchange="updateGatewayFields()"
                                required>
                            <option value="">-- Pilih Gateway --</option>
                            <option value="midtrans" <?= ($config['payment_gateway'] ?? 'midtrans') == 'midtrans' ? 'selected' : '' ?>>Midtrans</option>
                            <option value="xendit" <?= ($config['payment_gateway'] ?? '') == 'xendit' ? 'selected' : '' ?>>Xendit</option>
                            <option value="duitku" <?= ($config['payment_gateway'] ?? '') == 'duitku' ? 'selected' : '' ?>>Duitku</option>
                            <option value="ipaymu" <?= ($config['payment_gateway'] ?? '') == 'ipaymu' ? 'selected' : '' ?>>iPaymu</option>
                        </select>
                    </div>

                    <!-- Merchant ID -->
                    <div>
                        <label for="merchant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Merchant ID
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="merchant_id" 
                               name="merchant_id" 
                               value="<?= old('merchant_id', $config['merchant_id'] ?? '') ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="G123456789"
                               required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ID merchant dari dashboard gateway</p>
                    </div>

                    <!-- Client Key / Merchant Key -->
                    <div>
                        <label for="merchant_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span id="merchant_key_label">Client Key</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="merchant_key" 
                                   name="merchant_key" 
                                   value="<?= old('merchant_key', $config['merchant_key'] ?? '') ?>" 
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="SB-Mid-client-xxxxxxxxxx"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('merchant_key')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-lg" id="merchant_key_icon">visibility</span>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Client key untuk autentikasi</p>
                    </div>

                    <!-- Server Key / API Key -->
                    <div>
                        <label for="api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span id="api_key_label">Server Key</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="api_key" 
                                   name="api_key" 
                                   value="<?= old('api_key', $config['api_key'] ?? '') ?>" 
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="SB-Mid-server-xxxxxxxxxx"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('api_key')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-lg" id="api_key_icon">visibility</span>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Server key untuk backend API</p>
                    </div>

                    <!-- Callback URL -->
                    <div>
                        <label for="callback_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Callback URL
                        </label>
                        <input type="url" 
                               id="callback_url" 
                               name="callback_url" 
                               value="<?= old('callback_url', $config['callback_url'] ?? base_url('payment/callback')) ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="https://yourdomain.com/payment/callback">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URL untuk notifikasi pembayaran</p>
                    </div>
                </div>
            </div>

            <!-- Payment Fee Settings -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">attach_money</span>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Biaya Layanan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Fee -->
                    <div>
                        <label for="payment_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Biaya Admin (Rp)
                        </label>
                        <input type="number" 
                               id="payment_fee" 
                               name="payment_fee" 
                               value="<?= old('payment_fee', $config['payment_fee'] ?? 0) ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="5000"
                               min="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biaya admin per transaksi (0 untuk gratis)</p>
                    </div>
                </div>
            </div>

            <!-- Test Connection Section -->
            <div class="mb-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">Tes Koneksi Payment Gateway</h3>
                            <p class="text-sm text-blue-800 dark:text-blue-300 mb-3">
                                Setelah menyimpan konfigurasi, disarankan untuk melakukan tes koneksi untuk memastikan kredensial sudah benar.
                            </p>
                            <button type="button" 
                                    onclick="testConnection()" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">cloud_sync</span>
                                <span>Tes Koneksi</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="<?= base_url('admin/config') ?>" 
                   class="px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">close</span>
                    <span>Batal</span>
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    <span>Simpan Konfigurasi</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Documentation Section -->
    <div class="mt-6 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-amber-600">lightbulb</span>
            Panduan Konfigurasi Payment Gateway
        </h3>
        
        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
            <div id="midtrans-guide">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Midtrans:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Daftar akun di: <a href="https://dashboard.midtrans.com/register" target="_blank" class="text-blue-600 hover:underline">dashboard.midtrans.com</a></li>
                    <li>Dapatkan <strong>Client Key</strong> dan <strong>Server Key</strong> dari Settings → Access Keys</li>
                    <li>Mode Sandbox menggunakan prefix <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">SB-</code></li>
                    <li>Set Callback URL di Settings → Configuration</li>
                </ul>
            </div>

            <div id="xendit-guide" style="display: none;">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Xendit:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Daftar akun di: <a href="https://dashboard.xendit.co/register" target="_blank" class="text-blue-600 hover:underline">dashboard.xendit.co</a></li>
                    <li>Dapatkan <strong>API Key</strong> dari Settings → Developers → API Keys</li>
                    <li>Set Webhook URL di Settings → Developers → Webhooks</li>
                </ul>
            </div>

            <div id="duitku-guide" style="display: none;">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Duitku:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Daftar akun di: <a href="https://passport.duitku.com/register" target="_blank" class="text-blue-600 hover:underline">passport.duitku.com</a></li>
                    <li>Dapatkan <strong>Merchant Code</strong> dan <strong>API Key</strong> dari dashboard</li>
                    <li>Mode Sandbox tersedia untuk testing</li>
                </ul>
            </div>

            <div id="ipaymu-guide" style="display: none;">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">iPaymu:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Daftar akun di: <a href="https://ipaymu.com/register" target="_blank" class="text-blue-600 hover:underline">ipaymu.com</a></li>
                    <li>Dapatkan <strong>VA</strong> dan <strong>API Key</strong> dari Member Area</li>
                    <li>Set Callback URL di Member Area → Settings</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Update gateway fields based on selected gateway
function updateGatewayFields() {
    const gateway = document.getElementById('payment_gateway').value;
    const merchantKeyLabel = document.getElementById('merchant_key_label');
    const apiKeyLabel = document.getElementById('api_key_label');
    
    // Hide all guides
    document.querySelectorAll('[id$="-guide"]').forEach(el => el.style.display = 'none');
    
    // Show relevant guide
    const guideEl = document.getElementById(gateway + '-guide');
    if (guideEl) guideEl.style.display = 'block';
    
    // Update labels based on gateway
    switch(gateway) {
        case 'midtrans':
            merchantKeyLabel.textContent = 'Client Key';
            apiKeyLabel.textContent = 'Server Key';
            break;
        case 'xendit':
            merchantKeyLabel.textContent = 'Public Key';
            apiKeyLabel.textContent = 'API Key';
            break;
        case 'duitku':
            merchantKeyLabel.textContent = 'Merchant Code';
            apiKeyLabel.textContent = 'API Key';
            break;
        case 'ipaymu':
            merchantKeyLabel.textContent = 'Virtual Account';
            apiKeyLabel.textContent = 'API Key';
            break;
        default:
            merchantKeyLabel.textContent = 'Client Key';
            apiKeyLabel.textContent = 'Server Key';
    }
}

// Test connection function
function testConnection() {
    const gateway = document.getElementById('payment_gateway').value;
    
    if (!gateway) {
        alert('Pilih payment gateway terlebih dahulu');
        return;
    }
    
    // Show loading
    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">progress_activity</span> Testing...';
    
    // Test connection via AJAX
    fetch('<?= base_url('admin/config/test-payment') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            gateway: gateway,
            mode: document.getElementById('payment_mode').value
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        
        if (data.success) {
            alert('✓ Koneksi berhasil! Gateway ' + gateway + ' dapat terhubung.');
        } else {
            alert('✗ Koneksi gagal: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        alert('Terjadi kesalahan: ' + error.message);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateGatewayFields();
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi');
    }
});
</script>

<?php $this->endSection() ?>
