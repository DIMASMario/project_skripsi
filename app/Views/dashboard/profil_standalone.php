<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Profil Saya - Pelayanan Digital Desa Blanakan' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005c99",
                        "background-light": "#F8F9FA",
                        "background-dark": "#0f1b23",
                        "text-primary-light": "#212529",
                        "text-primary-dark": "#F8F9FA",
                        "text-secondary-light": "#6c757d",
                        "text-secondary-dark": "#adb5bd",
                        "border-light": "#DEE2E6",
                        "border-dark": "#495057",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1f2937",
                        "accent": "#FFD700"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
          font-variation-settings:
          'FILL' 0,
          'wght' 400,
          'GRAD' 0,
          'opsz' 24
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark">
<div class="relative flex min-h-screen w-full flex-col">
    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark backdrop-blur-sm px-4 md:px-10 py-3 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 text-text-primary-light dark:text-text-primary-dark hover:text-primary">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="font-medium">Kembali ke Dashboard</span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <div class="h-8 w-8 rounded-full bg-cover bg-center" style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap'] ?? 'User') ?>&background=005c99&color=ffffff");'></div>
            <span class="text-sm font-medium"><?= esc($user['nama_lengkap'] ?? 'User') ?></span>
        </div>
    </header>

    <div class="flex h-full grow flex-col">
        <main class="flex flex-1 justify-center p-4 sm:p-6 md:p-8">
            <div class="flex w-full max-w-4xl flex-col gap-8">
                <!-- Page Heading -->
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2">
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-black tracking-tight text-text-primary-light dark:text-text-primary-dark">Profil Saya</h1>
                        <p class="text-base text-text-secondary-light dark:text-text-secondary-dark">Lihat dan kelola informasi pribadi serta keamanan akun Anda.</p>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/50">
                    <div class="flex">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 mr-3">check_circle</span>
                        <p class="text-sm text-green-800 dark:text-green-200"><?= session()->getFlashdata('success') ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/50">
                    <div class="flex">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 mr-3">error</span>
                        <p class="text-sm text-red-800 dark:text-red-200"><?= session()->getFlashdata('error') ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Left Column: Profile Card -->
                    <div class="lg:col-span-1">
                        <div class="flex w-full flex-col items-center rounded-xl border border-border-light bg-surface-light p-6 text-center dark:border-border-dark dark:bg-surface-dark">
                            <div class="relative">
                                <?php 
                                $photoUrl = !empty($user['foto_profil']) && file_exists(FCPATH . $user['foto_profil']) 
                                    ? base_url($user['foto_profil']) 
                                    : "https://ui-avatars.com/api/?name=" . urlencode($user['nama_lengkap'] ?? 'User') . "&background=005c99&color=ffffff&size=128";
                                ?>
                                <div class="h-32 w-32 rounded-full bg-cover bg-center border-4 border-primary/20" data-alt="User profile picture" style='background-image: url("<?= $photoUrl ?>");'></div>
                            </div>
                            <p class="mt-4 text-xl font-bold text-text-primary-light dark:text-text-primary-dark"><?= esc($user['nama_lengkap'] ?? 'User') ?></p>
                            <p class="mt-1 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                                <?= !empty($user['tempat_lahir']) ? esc($user['tempat_lahir']) : 'Belum diisi' ?>, <?= !empty($user['tanggal_lahir']) ? date('d M Y', strtotime($user['tanggal_lahir'])) : 'Belum diisi' ?>
                            </p>
                            <?php if (!empty($user['status']) && $user['status'] === 'verified'): ?>
                            <div class="mt-3 flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 dark:bg-green-900/50">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-sm">verified</span>
                                <span class="text-xs font-medium text-green-700 dark:text-green-300">Terverifikasi</span>
                            </div>
                            <?php else: ?>
                            <div class="mt-3 flex items-center gap-2 rounded-full bg-yellow-100 px-3 py-1 dark:bg-yellow-900/50">
                                <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-sm">pending</span>
                                <span class="text-xs font-medium text-yellow-700 dark:text-yellow-300">Menunggu Verifikasi</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right Column: Edit Profile Form -->
                    <div class="lg:col-span-2 flex flex-col gap-8">
                        <!-- Profile Information Form -->
                        <div class="flex flex-col rounded-xl border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
                            <div class="border-b border-border-light px-6 py-4 dark:border-border-dark">
                                <h2 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Edit Profil</h2>
                                <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mt-1">Lengkapi dan update informasi profil Anda</p>
                            </div>
                            
                            <div class="p-6">
                                <form method="POST" action="<?= current_url() ?>" enctype="multipart/form-data" class="space-y-6">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="update_profile">
                                    
                                    <!-- Upload Foto Profil -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-2">
                                            Foto Profil
                                        </label>
                                        <div class="flex items-center gap-4">
                                            <?php 
                                            $currentPhoto = !empty($user['foto_profil']) && file_exists(FCPATH . $user['foto_profil']) 
                                                ? base_url($user['foto_profil']) 
                                                : "https://ui-avatars.com/api/?name=" . urlencode($user['nama_lengkap'] ?? 'User') . "&background=005c99&color=ffffff&size=128";
                                            ?>
                                            <div id="preview-foto" class="h-20 w-20 rounded-full bg-cover bg-center border-2 border-border-light dark:border-border-dark" style='background-image: url("<?= $currentPhoto ?>");'></div>
                                            <div class="flex-1">
                                                <input type="file" name="foto_profil" id="foto_profil" accept="image/*" class="block w-full text-sm text-text-secondary-light file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 dark:text-text-secondary-dark" onchange="previewImage(this)">
                                                <p class="mt-1 text-xs text-text-secondary-light dark:text-text-secondary-dark">Format: JPG, PNG, max 2MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <script>
                                    function previewImage(input) {
                                        if (input.files && input.files[0]) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                document.getElementById('preview-foto').style.backgroundImage = 'url(' + e.target.result + ')';
                                            };
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                    </script>
                                    
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <!-- Tempat Lahir -->
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Tempat Lahir</p>
                                            <input name="tempat_lahir" type="text" class="form-input h-12 w-full rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" value="<?= esc($user['tempat_lahir'] ?? '') ?>" placeholder="Contoh: Jakarta"/>
                                        </label>
                                        
                                        <!-- Tanggal Lahir -->
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Tanggal Lahir</p>
                                            <input name="tanggal_lahir" type="date" class="form-input h-12 w-full rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" value="<?= esc($user['tanggal_lahir'] ?? '') ?>"/>
                                        </label>
                                        
                                        <!-- Jenis Kelamin -->
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Jenis Kelamin</p>
                                            <div class="relative">
                                                <select name="jenis_kelamin" class="form-select appearance-none h-12 w-full rounded-lg border border-border-light bg-background-light p-3 pr-10 text-sm text-text-primary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="L" <?= ($user['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                                    <option value="P" <?= ($user['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                                </select>
                                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark pointer-events-none">expand_more</span>
                                            </div>
                                        </label>
                                        
                                        <!-- Agama -->
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Agama</p>
                                            <div class="relative">
                                                <select name="agama" class="form-select appearance-none h-12 w-full rounded-lg border border-border-light bg-background-light p-3 pr-10 text-sm text-text-primary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Islam" <?= ($user['agama'] ?? '') === 'Islam' ? 'selected' : '' ?>>Islam</option>
                                                    <option value="Kristen" <?= ($user['agama'] ?? '') === 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                                                    <option value="Katolik" <?= ($user['agama'] ?? '') === 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                                    <option value="Hindu" <?= ($user['agama'] ?? '') === 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                                    <option value="Buddha" <?= ($user['agama'] ?? '') === 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                                    <option value="Konghucu" <?= ($user['agama'] ?? '') === 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                                                    <option value="Lainnya" <?= ($user['agama'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                                </select>
                                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark pointer-events-none">expand_more</span>
                                            </div>
                                        </label>
                                        
                                        <!-- Alamat -->
                                        <label class="flex flex-col col-span-2">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Alamat</p>
                                            <textarea name="alamat" rows="3" class="form-input w-full rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary resize-none" placeholder="Masukkan alamat lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
                                        </label>
                                        
                                        <!-- Nomor HP -->
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Nomor HP</p>
                                            <input name="no_hp" type="tel" class="form-input h-12 w-full rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" value="<?= esc($user['no_hp'] ?? '') ?>" placeholder="Contoh: 081234567890"/>
                                        </label>
                                        
                                        <!-- Email -->
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Email</p>
                                            <input name="email" type="email" class="form-input h-12 w-full rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" value="<?= esc($user['email'] ?? '') ?>" placeholder="email@example.com"/>
                                        </label>
                                    </div>
                                    
                                    <div class="flex justify-end gap-3">
                                        <button class="flex h-10 cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-gray-200 px-4 text-sm font-bold text-gray-800 dark:bg-gray-600 dark:text-gray-200" type="button" onclick="window.location.reload()">Batal</button>
                                        <button class="flex h-10 cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary/90 transition-colors" type="submit">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Account Security Panel -->
                        <div class="flex flex-col rounded-xl border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
                            <div class="border-b border-border-light px-6 py-4 dark:border-border-dark">
                                <h2 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Keamanan Akun</h2>
                            </div>
                            <div class="p-6">
                                <form method="POST" action="<?= current_url() ?>" class="space-y-6">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="change_password">
                                    
                                    <div class="grid grid-cols-1 gap-4">
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Kata Sandi Lama</p>
                                            <input name="old_password" class="form-input h-12 w-full flex-1 rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" placeholder="Masukkan kata sandi lama" type="password" required/>
                                        </label>
                                        
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Kata Sandi Baru</p>
                                            <input name="new_password" class="form-input h-12 w-full flex-1 rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" placeholder="Minimal 8 karakter" type="password" minlength="8" required/>
                                        </label>
                                        
                                        <label class="flex flex-col">
                                            <p class="pb-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Konfirmasi Kata Sandi Baru</p>
                                            <input name="confirm_password" class="form-input h-12 w-full flex-1 rounded-lg border border-border-light bg-background-light p-3 text-sm text-text-primary-light placeholder:text-text-secondary-light focus:border-primary focus:ring-primary dark:border-border-dark dark:bg-background-dark dark:text-text-primary-dark dark:focus:border-primary" placeholder="Ulangi kata sandi baru" type="password" required/>
                                        </label>
                                    </div>
                                    
                                    <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/50">
                                        <div class="flex">
                                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mr-3">info</span>
                                            <div class="text-sm text-blue-800 dark:text-blue-200">
                                                <p class="font-medium">Tips Kata Sandi Aman:</p>
                                                <ul class="mt-1 list-disc pl-5 space-y-1">
                                                    <li>Minimal 8 karakter</li>
                                                    <li>Kombinasi huruf besar, kecil, angka, dan simbol</li>
                                                    <li>Jangan gunakan informasi pribadi</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button class="flex h-10 cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary/90 transition-colors" type="submit">Ubah Kata Sandi</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Account Activity Panel -->
                        <div class="flex flex-col rounded-xl border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
                            <div class="border-b border-border-light px-6 py-4 dark:border-border-dark">
                                <h2 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Aktivitas Akun</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark">login</span>
                                            <div>
                                                <p class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Login Terakhir</p>
                                                <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">
                                                    <?= !empty($user['last_login']) ? date('d M Y, H:i', strtotime($user['last_login'])) . ' WIB' : 'Belum ada data' ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark">person_add</span>
                                            <div>
                                                <p class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Bergabung Sejak</p>
                                                <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">
                                                    <?= !empty($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : 'Belum ada data' ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark">description</span>
                                            <div>
                                                <p class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Total Pengajuan Surat</p>
                                                <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">
                                                    <?= $totalSurat ?? 0 ?> surat telah diajukan
                                                </p>
                                            </div>
                                        </div>
                                        <a href="<?= base_url('dashboard/riwayat-surat') ?>" class="text-primary hover:underline text-sm font-medium">Lihat Riwayat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Form validation for password change
document.querySelector('form[action*="change_password"] input[name="confirm_password"]').addEventListener('input', function() {
    const newPassword = document.querySelector('input[name="new_password"]').value;
    const confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('Konfirmasi kata sandi tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

// Auto-hide flash messages after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('[class*="border-green-"], [class*="border-red-"]');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
}, 5000);
</script>
</body>
</html>