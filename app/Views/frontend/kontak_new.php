<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('title') ?>Kontak Kami - Desa Tanjung Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="layout-container flex h-full grow flex-col">
    <div class="container mx-auto px-4 py-8 md:py-16">
        <div class="layout-content-container flex flex-col w-full">
            <!-- Breadcrumbs & Page Heading -->
            <div class="flex flex-col gap-4 mb-10 md:mb-12">
                <div class="flex flex-wrap gap-2">
                    <a class="text-primary/80 dark:text-primary/70 text-sm font-medium leading-normal hover:underline" href="<?= base_url() ?>">Beranda</a>
                    <span class="text-slate-400 dark:text-slate-500 text-sm font-medium leading-normal">/</span>
                    <span class="text-[#0c161d] dark:text-slate-200 text-sm font-medium leading-normal">Kontak Kami</span>
                </div>
                <div class="flex flex-wrap justify-between gap-3">
                    <div class="flex flex-col gap-3">
                        <h1 class="text-[#0c161d] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Hubungi Kami</h1>
                        <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal max-w-2xl">
                            Kami siap membantu Anda. Silakan isi formulir di bawah ini atau gunakan informasi kontak yang tersedia untuk terhubung dengan kami.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content: Form & Info -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16">
                <!-- Left Column: Contact Form -->
                <div class="lg:col-span-3 bg-white dark:bg-background-dark/50 p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-slate-800">
                    <h2 class="text-[#0c161d] dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] mb-6">Kirimkan Pesan Anda</h2>
                    
                    <form class="flex flex-col gap-6" action="<?= base_url('kontak/kirim') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#0c161d] dark:text-slate-300 text-sm font-medium leading-normal pb-2">Nama Lengkap <span class="text-red-500">*</span></p>
                                <input 
                                    name="nama"
                                    required
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0c161d] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary dark:focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 p-3 text-base font-normal leading-normal" 
                                    placeholder="Masukkan nama lengkap Anda" 
                                    value="<?= old('nama') ?>"
                                />
                            </label>
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#0c161d] dark:text-slate-300 text-sm font-medium leading-normal pb-2">Alamat Email <span class="text-red-500">*</span></p>
                                <input 
                                    name="email"
                                    type="email"
                                    required
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0c161d] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary dark:focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 p-3 text-base font-normal leading-normal" 
                                    placeholder="cth: john.doe@email.com" 
                                    value="<?= old('email') ?>"
                                />
                            </label>
                        </div>
                        
                        <label class="flex flex-col min-w-40 flex-1">
                            <p class="text-[#0c161d] dark:text-slate-300 text-sm font-medium leading-normal pb-2">Subjek Pesan <span class="text-red-500">*</span></p>
                            <input 
                                name="subjek"
                                required
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0c161d] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary dark:focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 p-3 text-base font-normal leading-normal" 
                                placeholder="Tuliskan subjek pesan Anda" 
                                value="<?= old('subjek') ?>"
                            />
                        </label>
                        
                        <label class="flex flex-col min-w-40 flex-1">
                            <p class="text-[#0c161d] dark:text-slate-300 text-sm font-medium leading-normal pb-2">Isi Pesan <span class="text-red-500">*</span></p>
                            <textarea 
                                name="pesan"
                                required
                                class="form-textarea flex w-full min-w-0 flex-1 resize-y overflow-hidden rounded-lg text-[#0c161d] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary dark:focus:border-primary min-h-36 placeholder:text-slate-400 dark:placeholder:text-slate-500 p-3 text-base font-normal leading-normal" 
                                placeholder="Tuliskan pesan Anda di sini..."
                            ><?= old('pesan') ?></textarea>
                        </label>
                        
                        <button 
                            type="submit"
                            class="flex w-full md:w-auto md:self-start min-w-[140px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-opacity-90 transition-colors"
                        >
                            <span class="truncate">Kirim Pesan</span>
                        </button>
                    </form>
                </div>

                <!-- Right Column: Info & Map -->
                <div class="lg:col-span-2 flex flex-col gap-8">
                    <!-- Contact Info Card -->
                    <div class="bg-white dark:bg-background-dark/50 p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-slate-800">
                        <h3 class="text-[#0c161d] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-6">Informasi Kontak</h3>
                        
                        <div class="flex flex-col gap-5">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 size-10 flex items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20 text-primary">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-base text-[#0c161d] dark:text-white">Alamat Kantor Desa</h4>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm">Blanakan, Kec. Blanakan, Kabupaten Subang, Jawa Barat</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 size-10 flex items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20 text-primary">
                                    <span class="material-symbols-outlined">call</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-base text-[#0c161d] dark:text-white">Telepon</h4>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm">(0765) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 size-10 flex items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20 text-primary">
                                    <span class="material-symbols-outlined">mail</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-base text-[#0c161d] dark:text-white">Email</h4>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm">kontak@desablanakan.go.id</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 size-10 flex items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20 text-primary">
                                    <span class="material-symbols-outlined">schedule</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-base text-[#0c161d] dark:text-white">Jam Pelayanan</h4>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm">Senin - Jumat: 08:00 - 16:00 WIB</p>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm">Sabtu: 08:00 - 12:00 WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Embedded Map -->
                    <div class="aspect-w-16 aspect-h-9 w-full overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                        <iframe 
                            allowfullscreen="" 
                            data-location="Blanakan, Subang" 
                            height="100%" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade" 
                            src="https://www.google.com/maps?q=Blanakan,+Kec.+Blanakan,+Kabupaten+Subang,+Jawa+Barat&output=embed" 
                            style="border:0;" 
                            width="100%"
                        ></iframe>
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mt-8 p-4 bg-green-100 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mt-8 p-4 bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?= $this->endSection() ?>