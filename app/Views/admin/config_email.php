<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Konfigurasi Email</h1>
                <p class="text-gray-600 dark:text-gray-400">Atur konfigurasi SMTP untuk pengiriman email sistem</p>
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
        <form action="<?= base_url('admin/config-email') ?>" method="POST" class="p-6">
            <?= csrf_field() ?>

            <!-- SMTP Settings Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">mail_outline</span>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan SMTP</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SMTP Host -->
                    <div>
                        <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Host SMTP
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="smtp_host" 
                               name="smtp_host" 
                               value="<?= old('smtp_host', $config['smtp_host'] ?? 'smtp.gmail.com') ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="smtp.gmail.com"
                               required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contoh: smtp.gmail.com, smtp.office365.com</p>
                    </div>

                    <!-- SMTP Port -->
                    <div>
                        <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Port SMTP
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="smtp_port" 
                               name="smtp_port" 
                               value="<?= old('smtp_port', $config['smtp_port'] ?? '587') ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="587"
                               required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Port 587 (TLS) atau 465 (SSL)</p>
                    </div>

                    <!-- SMTP Username -->
                    <div>
                        <label for="smtp_user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Username/Email
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="smtp_user" 
                               name="smtp_user" 
                               value="<?= old('smtp_user', $config['smtp_user'] ?? '') ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="email@domain.com"
                               required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alamat email pengirim</p>
                    </div>

                    <!-- SMTP Password -->
                    <div>
                        <label for="smtp_pass" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password/App Password
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="smtp_pass" 
                                   name="smtp_pass" 
                                   value="<?= old('smtp_pass', $config['smtp_pass'] ?? '') ?>" 
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                   placeholder="••••••••"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('smtp_pass')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-lg" id="smtp_pass_icon">visibility</span>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan App Password untuk Gmail</p>
                    </div>

                    <!-- Encryption Type -->
                    <div>
                        <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Enkripsi
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="smtp_encryption" 
                                name="smtp_encryption" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                required>
                            <option value="tls" <?= (old('smtp_encryption', $config['smtp_encryption'] ?? 'tls') == 'tls') ? 'selected' : '' ?>>TLS (Port 587)</option>
                            <option value="ssl" <?= (old('smtp_encryption', $config['smtp_encryption'] ?? 'tls') == 'ssl') ? 'selected' : '' ?>>SSL (Port 465)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">TLS direkomendasikan</p>
                    </div>

                    <!-- From Name -->
                    <div>
                        <label for="smtp_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Pengirim
                        </label>
                        <input type="text" 
                               id="smtp_from_name" 
                               name="smtp_from_name" 
                               value="<?= old('smtp_from_name', $config['smtp_from_name'] ?? 'Website Desa Blanakan') ?>" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="Website Desa">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nama yang ditampilkan pada email</p>
                    </div>
                </div>
            </div>

            <!-- Email Template Settings -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">palette</span>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Template Email</h2>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Email Header -->
                    <div>
                        <label for="email_header" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Header Email
                        </label>
                        <textarea id="email_header" 
                                  name="email_header" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                  placeholder="Teks yang ditampilkan di header email"><?= old('email_header', $config['email_header'] ?? '') ?></textarea>
                    </div>

                    <!-- Email Footer -->
                    <div>
                        <label for="email_footer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Footer Email
                        </label>
                        <textarea id="email_footer" 
                                  name="email_footer" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                  placeholder="Teks yang ditampilkan di footer email"><?= old('email_footer', $config['email_footer'] ?? '© 2025 Desa Blanakan. All rights reserved.') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Test Email Section -->
            <div class="mb-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">Tes Konfigurasi Email</h3>
                            <p class="text-sm text-blue-800 dark:text-blue-300 mb-3">
                                Setelah menyimpan konfigurasi, disarankan untuk mengirim test email untuk memastikan pengaturan sudah benar.
                            </p>
                            <button type="button" 
                                    onclick="testEmail()" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">send</span>
                                <span>Kirim Test Email</span>
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
            Panduan Konfigurasi
        </h3>
        
        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Gmail:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Host: <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">smtp.gmail.com</code></li>
                    <li>Port: <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">587</code> (TLS)</li>
                    <li>Aktifkan 2-Step Verification dan gunakan App Password</li>
                    <li>Buat App Password di: <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-blue-600 hover:underline">myaccount.google.com/apppasswords</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Office 365/Outlook:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Host: <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">smtp.office365.com</code></li>
                    <li>Port: <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">587</code> (TLS)</li>
                    <li>Gunakan email dan password Office 365 Anda</li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Yahoo Mail:</h4>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Host: <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">smtp.mail.yahoo.com</code></li>
                    <li>Port: <code class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded">587</code> (TLS)</li>
                    <li>Aktifkan "Allow apps that use less secure sign in"</li>
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

// Test email function
function testEmail() {
    const email = prompt('Masukkan alamat email tujuan untuk test:');
    if (email) {
        // Show loading
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">progress_activity</span> Mengirim...';
        
        // Send test email via AJAX
        fetch('<?= base_url('admin/test-email') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ test_email: email })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            
            if (data.success) {
                alert('Test email berhasil dikirim ke ' + email);
            } else {
                alert('Gagal mengirim test email: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            alert('Terjadi kesalahan: ' + error.message);
        });
    }
}

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
