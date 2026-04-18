<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('title') ?>Formulir Pengajuan Surat - Desa Tanjung Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="flex-grow">
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumbs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <a class="text-primary/80 hover:text-primary text-sm font-medium leading-normal" href="<?= base_url() ?>">Beranda</a>
                <span class="text-slate-400 dark:text-slate-500 text-sm font-medium leading-normal">/</span>
                <a class="text-primary/80 hover:text-primary text-sm font-medium leading-normal" href="<?= base_url('layanan-online') ?>">Layanan Online</a>
                <span class="text-slate-400 dark:text-slate-500 text-sm font-medium leading-normal">/</span>
                <span class="text-slate-800 dark:text-slate-200 text-sm font-medium leading-normal">Formulir Pengajuan</span>
            </div>

            <!-- Page Heading -->
            <div class="mb-10">
                <div class="flex flex-col gap-2">
                    <h1 class="text-slate-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Formulir Pengajuan Surat Online</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal">
                        Lengkapi data yang diperlukan untuk pengajuan <?= esc($jenis_surat_display) ?> di bawah ini.
                    </p>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                    <p class="text-red-700 dark:text-red-300 font-medium"><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                    <p class="text-red-700 dark:text-red-300 font-bold">Terdapat kesalahan pada form:</p>
                </div>
                <ul class="list-disc list-inside text-red-600 dark:text-red-400 text-sm space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form id="suratForm" action="<?= base_url('layanan-online/submit') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="jenis_surat" value="<?= esc($jenis_surat) ?>">
                
                <div class="bg-white dark:bg-slate-900/50 p-6 md:p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <!-- Section 1: Jenis Surat yang Dipilih -->
                    <div class="mb-8">
                        <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] pb-4">1. Jenis Surat yang Dipilih</h3>
                        <div class="bg-primary/10 dark:bg-primary/20 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary"><?= $surat_icon ?></span>
                                <div>
                                    <h4 class="font-semibold text-slate-900 dark:text-white"><?= esc($jenis_surat_display) ?></h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400"><?= esc($surat_description) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Data Pemohon -->
                    <div class="mb-8">
                        <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] pb-4">2. Data Pemohon</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="nama_lengkap">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="nama_lengkap" 
                                    name="nama_lengkap"
                                    placeholder="Sesuai KTP" 
                                    type="text"
                                    value="<?= old('nama_lengkap', session()->get('nama_lengkap')) ?>"
                                    required
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="nik">
                                    NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="nik" 
                                    name="nik"
                                    placeholder="16 digit angka" 
                                    type="text"
                                    maxlength="16"
                                    value="<?= old('nik', session()->get('nik')) ?>"
                                    required
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="no_kk">
                                    Nomor Kartu Keluarga (KK) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="no_kk" 
                                    name="no_kk"
                                    placeholder="16 digit angka" 
                                    type="text"
                                    maxlength="16"
                                    value="<?= old('no_kk') ?>"
                                    required
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="telepon">
                                    Nomor Telepon (WhatsApp) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="telepon" 
                                    name="telepon"
                                    placeholder="08xxxxxxxxxx" 
                                    type="tel"
                                    value="<?= old('telepon', session()->get('telepon')) ?>"
                                    required
                                />
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="alamat">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="alamat" 
                                    name="alamat"
                                    placeholder="Jl. Desa, RT/RW, Dusun, Desa Tanjung Baru" 
                                    rows="3"
                                    required
                                ><?= old('alamat', session()->get('alamat')) ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="tempat_lahir">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="tempat_lahir" 
                                    name="tempat_lahir"
                                    placeholder="Kota Kelahiran" 
                                    type="text"
                                    value="<?= old('tempat_lahir') ?>"
                                    required
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="tanggal_lahir">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="tanggal_lahir" 
                                    name="tanggal_lahir"
                                    type="date"
                                    value="<?= old('tanggal_lahir') ?>"
                                    required
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="jenis_kelamin">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="jenis_kelamin"
                                    name="jenis_kelamin"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" <?= old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="status_kawin">
                                    Status Perkawinan <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="status_kawin"
                                    name="status_kawin"
                                    required
                                >
                                    <option value="">Pilih Status</option>
                                    <?php if ($jenis_surat === 'skd'): ?>
                                        <!-- Opsi lengkap untuk SKD -->
                                        <option value="Belum Menikah" <?= old('status_kawin') === 'Belum Menikah' ? 'selected' : '' ?>>Belum Menikah</option>
                                        <option value="Menikah" <?= old('status_kawin') === 'Menikah' ? 'selected' : '' ?>>Menikah</option>
                                        <option value="Cerai Hidup" <?= old('status_kawin') === 'Cerai Hidup' ? 'selected' : '' ?>>Cerai Hidup</option>
                                        <option value="Cerai Mati" <?= old('status_kawin') === 'Cerai Mati' ? 'selected' : '' ?>>Cerai Mati</option>
                                        <option value="Janda Hidup" <?= old('status_kawin') === 'Janda Hidup' ? 'selected' : '' ?>>Janda Hidup</option>
                                        <option value="Janda Mati" <?= old('status_kawin') === 'Janda Mati' ? 'selected' : '' ?>>Janda Mati</option>
                                        <option value="Duda Hidup" <?= old('status_kawin') === 'Duda Hidup' ? 'selected' : '' ?>>Duda Hidup</option>
                                        <option value="Duda Mati" <?= old('status_kawin') === 'Duda Mati' ? 'selected' : '' ?>>Duda Mati</option>
                                    <?php else: ?>
                                        <!-- Opsi standar untuk surat lain -->
                                        <option value="Belum Kawin" <?= old('status_kawin') === 'Belum Kawin' ? 'selected' : '' ?>>Belum Kawin</option>
                                        <option value="Kawin" <?= old('status_kawin') === 'Kawin' ? 'selected' : '' ?>>Kawin</option>
                                        <option value="Cerai Hidup" <?= old('status_kawin') === 'Cerai Hidup' ? 'selected' : '' ?>>Cerai Hidup</option>
                                        <option value="Cerai Mati" <?= old('status_kawin') === 'Cerai Mati' ? 'selected' : '' ?>>Cerai Mati</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <?php if ($jenis_surat === 'skd'): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="deskripsi_skd">
                                    Keterangan/Detail SKD yang Dibutuhkan <span class="text-slate-400">(Opsional)</span>
                                </label>
                                <textarea 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="deskripsi_skd" 
                                    name="deskripsi_skd"
                                    placeholder="Contoh: SKD untuk izin tinggal sementara, melamar pekerjaan, atau keperluan administrasi lainnya" 
                                    rows="3"
                                ><?= old('deskripsi_skd') ?></textarea>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Jelaskan secara detail apa kebutuhan SKD Anda atau informasi tambahan apapun yang perlu diketahui
                                </p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1" for="keperluan">
                                    Keperluan <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    class="w-full rounded border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary focus:ring-opacity-50" 
                                    id="keperluan" 
                                    name="keperluan"
                                    placeholder="Contoh: Untuk melamar pekerjaan di PT. Maju Mundur" 
                                    rows="3"
                                    required
                                ><?= old('keperluan') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Upload Dokumen -->
                    <div class="mb-8">
                        <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] pb-2">3. Unggah Dokumen Pendukung</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                            Mohon unggah dokumen yang diperlukan dalam format .JPG, .PNG, atau .PDF (ukuran maks. 2MB per file).
                        </p>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2" for="file_ktp">
                                        Scan KTP <span class="text-slate-400">(Opsional)</span>
                                    </label>
                                    <input 
                                        class="w-full text-sm text-slate-900 bg-slate-50 rounded border border-slate-300 cursor-pointer dark:text-slate-400 focus:outline-none dark:bg-slate-700 dark:border-slate-600 dark:placeholder-slate-400" 
                                        id="file_ktp" 
                                        name="file_ktp"
                                        type="file"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                    />
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">File tidak wajib diupload saat ini</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2" for="file_kk">
                                        Scan Kartu Keluarga <span class="text-slate-400">(Opsional)</span>
                                    </label>
                                    <input 
                                        class="w-full text-sm text-slate-900 bg-slate-50 rounded border border-slate-300 cursor-pointer dark:text-slate-400 focus:outline-none dark:bg-slate-700 dark:border-slate-600 dark:placeholder-slate-400" 
                                        id="file_kk" 
                                        name="file_kk"
                                        type="file"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                    />
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">File tidak wajib diupload saat ini</p>
                                </div>
                            </div>
                            
                            <?php if ($jenis_surat === 'usaha'): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2" for="file_usaha">
                                    Dokumen Usaha (opsional)
                                </label>
                                <input 
                                    class="w-full text-sm text-slate-900 bg-slate-50 rounded border border-slate-300 cursor-pointer dark:text-slate-400 focus:outline-none dark:bg-slate-700 dark:border-slate-600 dark:placeholder-slate-400" 
                                    id="file_usaha" 
                                    name="file_usaha"
                                    type="file"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                />
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Foto tempat usaha, izin usaha, atau dokumen pendukung lainnya
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Section 4: Pernyataan dan Submit -->
                    <div>
                        <div class="flex items-start mb-6">
                            <input 
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary mt-0.5" 
                                id="persetujuan" 
                                name="persetujuan"
                                type="checkbox"
                                required
                            />
                            <label class="ml-3 text-sm text-slate-600 dark:text-slate-300" for="persetujuan">
                                Saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan. <span class="text-red-500">*</span>
                            </label>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <button 
                                type="submit"
                                id="submitBtn"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-full h-12 px-8 bg-primary text-white text-base font-bold hover:bg-primary/90 transition-colors disabled:bg-slate-300 dark:disabled:bg-slate-700 disabled:cursor-not-allowed"
                            >
                                <span class="material-symbols-outlined" id="submitIcon">send</span> 
                                <span id="submitText">Ajukan Permohonan</span>
                            </button>
                            
                            <a 
                                href="<?= base_url('layanan-online') ?>"
                                class="w-full sm:w-auto flex items-center justify-center rounded-full h-12 px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-base font-medium hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors"
                            >
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// File upload preview functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Form initialized');
    
    const fileInputs = document.querySelectorAll('input[type="file"]');
    const form = document.getElementById('suratForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Basic file size validation (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    this.value = '';
                    return;
                }
                
                // Basic file type validation
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak didukung! Gunakan JPG, PNG, atau PDF.');
                    this.value = '';
                    return;
                }
            }
        });
    });
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        console.log('Form submit event triggered');
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitIcon.textContent = 'hourglass_empty';
        submitText.textContent = 'Mengirim...';
        
        // Log form data for debugging
        const formData = new FormData(form);
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(key + ': ' + value.name + ' (' + value.size + ' bytes)');
            } else {
                console.log(key + ': ' + value);
            }
        }
    });
});
</script>
<?= $this->endSection() ?>