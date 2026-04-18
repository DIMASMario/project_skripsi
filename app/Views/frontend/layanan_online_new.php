<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('title') ?>Layanan Online - Desa Tanjung Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="flex-grow">
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumbs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <a class="text-primary/80 hover:text-primary text-sm font-medium leading-normal" href="<?= base_url() ?>">Beranda</a>
                <span class="text-slate-400 dark:text-slate-500 text-sm font-medium leading-normal">/</span>
                <span class="text-slate-800 dark:text-slate-200 text-sm font-medium leading-normal">Layanan Online</span>
            </div>

            <!-- Page Heading -->
            <div class="mb-10">
                <div class="flex flex-col gap-2">
                    <h1 class="text-slate-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Layanan Online</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal">
                        Ajukan berbagai jenis surat dan dokumen secara online dengan mudah dan cepat.
                    </p>
                </div>
            </div>

            <?php if (!session()->get('logged_in')): ?>
            <!-- Login Required Notice -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl p-6 mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">info</span>
                    <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200">Login Diperlukan</h3>
                </div>
                <p class="text-amber-700 dark:text-amber-300 mb-4">
                    Untuk menggunakan layanan online, Anda harus login terlebih dahulu sebagai warga yang terdaftar.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        @click="typeof $store !== 'undefined' && $store.modal ? $store.modal.openLogin() : console.error('Alpine store not ready')"
                        type="button"
                        class="flex items-center justify-center gap-2 rounded-full h-10 px-6 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors"
                    >
                        <span class="material-symbols-outlined !text-base">login</span>
                        Login Sekarang
                    </button>
                    <a 
                        href="<?= base_url('auth/register') ?>" 
                        class="flex items-center justify-center gap-2 rounded-full h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors"
                    >
                        <span class="material-symbols-outlined !text-base">person_add</span>
                        Daftar Akun
                    </a>
                </div>
            </div>

            <!-- Service Types Preview (for non-logged in users) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">home</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Domisili</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan domisili untuk berbagai keperluan administrasi.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">volunteer_activism</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">SKTM</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat Keterangan Tidak Mampu untuk bantuan sosial dan pendidikan.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">child_care</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Kelahiran</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan kelahiran dari kantor desa.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">sentiment_very_dissatisfied</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Kematian</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan kematian untuk keperluan administrasi.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">person_remove</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Pindah Nama</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan perubahan/pindah nama penduduk.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">business</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Keterangan Usaha</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan usaha untuk perizinan dan administrasi bisnis.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">gavel</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Garapan</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan jaminan/garapan tanah atau harta benda.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">local_florist</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Taksiran Harga Tanah</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat perkiraan harga tanah/bangunan dari kantor desa.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 opacity-60">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-2xl text-primary">card_membership</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Surat Keterangan Desa (SKD)</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Surat keterangan penduduk dari kantor desa untuk berbagai keperluan.
                    </p>
                    <div class="flex items-center text-xs text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined !text-sm mr-1">lock</span>
                        Memerlukan login
                    </div>
                </div>
            </div>

            <?php else: ?>
            <!-- Logged In User - Service Selection -->
            <form action="<?= base_url('layanan-online/ajukan') ?>" method="GET">
                <div class="bg-white dark:bg-slate-900/50 p-6 md:p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <!-- Section 1: Pemilihan Jenis Surat -->
                    <div class="mb-8">
                        <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] pb-4">1. Pilih Jenis Surat</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input checked name="jenis" type="radio" value="domisili" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 has-[:checked]:inline hidden">check_circle</span>
                                <span class="text-sm font-medium">Surat Domisili</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="sktm" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">SKTM</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="kelahiran" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Kelahiran</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="kematian" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Kematian</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="pindah_nama" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Pindah Nama</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="usaha" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Keterangan Usaha</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="garapan" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Garapan</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="taksiran_harga" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Taksiran Harga Tanah</span>
                            </label>
                            <label class="flex cursor-pointer items-center p-3 rounded-lg border-2 border-slate-300 dark:border-slate-600 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-950/40 has-[:checked]:shadow-md has-[:checked]:ring-2 has-[:checked]:ring-primary/30 text-slate-700 dark:text-slate-300 has-[:checked]:text-primary has-[:checked]:font-bold transition-all">
                                <input name="jenis" type="radio" value="skd" class="sr-only" />
                                <span class="material-symbols-outlined text-lg mr-2 hidden has-[:checked]:inline">check_circle</span>
                                <span class="text-sm font-medium">Surat Keterangan Desa (SKD)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button 
                            type="submit"
                            class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-full h-12 px-8 bg-primary text-white text-base font-bold hover:bg-primary/90 transition-colors"
                        >
                            <span class="material-symbols-outlined">arrow_forward</span> 
                            Lanjut ke Formulir
                        </button>
                        <a 
                            href="<?= base_url('dashboard/riwayat-surat') ?>" 
                            class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-full h-12 px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-base font-medium hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="material-symbols-outlined">history</span>
                            Riwayat Pengajuan
                        </a>
                    </div>
                </div>
            </form>

            <!-- Recent Applications (if any) -->
            <?php if (!empty($recent_applications)): ?>
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Pengajuan Terbaru</h2>
                <div class="space-y-4">
                    <?php foreach ($recent_applications as $app): ?>
                    <div class="bg-white dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white"><?= esc($app['jenis_surat']) ?></h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Diajukan pada <?= date('d M Y', strtotime($app['created_at'])) ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?= $app['status'] === 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($app['status'] === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400') ?>">
                                    <?= ucfirst($app['status']) ?>
                                </span>
                                <a 
                                    href="<?= base_url('dashboard/detail-surat/' . $app['id']) ?>" 
                                    class="text-primary hover:text-primary/80 text-sm font-medium"
                                >
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Information Section -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">schedule</span>
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Waktu Pelayanan</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                        <li>• Senin - Jumat: 08:00 - 16:00 WIB</li>
                        <li>• Sabtu: 08:00 - 12:00 WIB</li>
                        <li>• Minggu & Hari Libur: Tutup</li>
                        <li>• Proses surat: 1-3 hari kerja</li>
                    </ul>
                </div>

                <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-xl border border-green-200 dark:border-green-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">Persyaratan</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-green-700 dark:text-green-300">
                        <li>• Fotokopi KTP yang masih berlaku</li>
                        <li>• Fotokopi Kartu Keluarga</li>
                        <li>• Dokumen pendukung sesuai jenis surat</li>
                        <li>• Mengisi formulir dengan lengkap</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>