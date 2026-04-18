<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="material-icons mr-2">check_circle</i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-start">
                <i class="material-icons mr-2">error</i>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profil Card -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    <?= strtoupper(substr($admin['nama'] ?? $admin['username'] ?? 'A', 0, 1)) ?>
                </div>
                <h2 class="mt-4 text-xl font-semibold text-gray-900"><?= esc($admin['nama'] ?? $admin['username'] ?? 'Admin') ?></h2>
                <p class="text-sm text-gray-500"><?= esc($admin['email'] ?? 'email@example.com') ?></p>
                <div class="mt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="material-icons text-sm mr-1">admin_panel_settings</i>
                        <?= ucfirst($admin['role'] ?? 'admin') ?>
                    </span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-600">
                        <i class="material-icons text-sm mr-2">calendar_today</i>
                        <span>Bergabung: <?= date('d M Y', strtotime($admin['created_at'] ?? 'now')) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="material-icons text-sm mr-2">update</i>
                        <span>Update: <?= date('d M Y', strtotime($admin['updated_at'] ?? $admin['created_at'] ?? 'now')) ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="material-icons text-sm mr-2 <?= ($admin['status'] ?? 'approved') === 'approved' ? 'text-green-600' : 'text-gray-400' ?>">
                            <?= ($admin['status'] ?? 'approved') === 'approved' ? 'check_circle' : 'radio_button_unchecked' ?>
                        </i>
                        <span class="<?= ($admin['status'] ?? 'approved') === 'approved' ? 'text-green-600 font-medium' : 'text-gray-600' ?>">
                            Status: <?= ucfirst($admin['status'] ?? 'approved') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Akun</h3>
                    <p class="text-sm text-gray-500 mt-1">Update informasi pribadi dan password Anda</p>
                </div>

                <form method="POST" action="<?= base_url('admin/profil') ?>" class="p-6">
                    <?= csrf_field() ?>

                    <div class="space-y-6">
                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="material-icons text-sm align-middle mr-1">person</i>
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   id="nama" 
                                   name="nama" 
                                   value="<?= esc($admin['nama'] ?? $admin['username'] ?? '') ?>"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="material-icons text-sm align-middle mr-1">email</i>
                                Alamat Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= esc($admin['email'] ?? '') ?>"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">
                                <i class="material-icons text-sm align-middle mr-1">lock</i>
                                Ubah Password
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   placeholder="Minimal 6 karakter"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter. Kombinasikan huruf, angka, dan simbol untuk keamanan lebih baik.</p>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" 
                                   id="password_confirm" 
                                   name="password_confirm"
                                   placeholder="Ketik ulang password baru"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="<?= base_url('admin/dashboard') ?>" 
                           class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            <i class="material-icons text-sm align-middle mr-1">arrow_back</i>
                            Kembali ke Dashboard
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="material-icons text-sm align-middle mr-1">save</i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-yellow-400">warning</i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian Keamanan</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Jangan bagikan password Anda kepada siapapun</li>
                                <li>Gunakan password yang kuat dan unik</li>
                                <li>Logout setelah selesai menggunakan sistem</li>
                                <li>Jika mengubah email, pastikan email baru masih dapat diakses</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validasi konfirmasi password
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    
    if (password && password !== passwordConfirm) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (password && password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        return false;
    }
});
</script>

<?= $this->endsection() ?>
