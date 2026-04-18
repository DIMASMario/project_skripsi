<?php $this->extend('admin/layouts/main') ?>

<?php $this->section('content') ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Pengaturan</h1>
                <p class="text-gray-600 dark:text-gray-400">Kelola pengaturan sistem dan konfigurasi website</p>
            </div>
            <div class="flex gap-2">
                <button type="button" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2" 
                        onclick="showHelp()" 
                        title="Bantuan troubleshooting">
                    <span class="material-symbols-outlined text-lg">help</span>
                    <span>Help</span>
                </button>
                <button type="button" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2" 
                        onclick="resetAll()" 
                        title="Reset tombol yang macet">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    <span>Reset</span>
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2" 
                        onclick="backupData()">
                    <span class="material-symbols-outlined text-lg">download</span>
                    <span>Backup Data</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Box -->
    <div class="mb-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">info</span>
                    <span class="text-blue-800 dark:text-blue-300">
                        <strong>Tombol "Memproses..." tidak selesai?</strong> 
                        Gunakan tombol panic reset di sebelah kanan.
                    </span>
                </div>
                <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center gap-2" 
                        onclick="panicReset()">
                    <span class="material-symbols-outlined text-lg">warning</span>
                    <span>PANIC RESET</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-1 p-4" id="settingsTabs">
                <button class="tab-button active px-4 py-2.5 text-sm font-medium rounded-lg flex items-center gap-2 transition-colors" 
                        data-tab="general">
                    <span class="material-symbols-outlined text-lg">settings</span>
                    <span>Umum</span>
                </button>
                <button class="tab-button px-4 py-2.5 text-sm font-medium rounded-lg flex items-center gap-2 transition-colors" 
                        data-tab="website">
                    <span class="material-symbols-outlined text-lg">language</span>
                    <span>Website</span>
                </button>
                <button class="tab-button px-4 py-2.5 text-sm font-medium rounded-lg flex items-center gap-2 transition-colors" 
                        data-tab="desa">
                    <span class="material-symbols-outlined text-lg">location_on</span>
                    <span>Data Desa</span>
                </button>
                <button class="tab-button px-4 py-2.5 text-sm font-medium rounded-lg flex items-center gap-2 transition-colors" 
                        data-tab="email">
                    <span class="material-symbols-outlined text-lg">mail</span>
                    <span>Email</span>
                </button>
                <button class="tab-button px-4 py-2.5 text-sm font-medium rounded-lg flex items-center gap-2 transition-colors" 
                        data-tab="security">
                    <span class="material-symbols-outlined text-lg">shield</span>
                    <span>Keamanan</span>
                </button>
            </nav>
        </div>
        
        <div class="p-6">
            <div class="tab-content">
                <!-- General Settings -->
                <div id="general" class="tab-pane active">
                    <form id="generalForm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">settings</span>
                                    <span>Pengaturan Umum</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Zona Waktu</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                            id="timezone" name="timezone" required>
                                        <option value="">Pilih Zona Waktu</option>
                                        <option value="Asia/Jakarta" selected>WIB (Asia/Jakarta)</option>
                                        <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                                        <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="date_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Format Tanggal</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                            id="date_format" name="date_format">
                                        <option value="d/m/Y" selected>DD/MM/YYYY</option>
                                        <option value="Y-m-d">YYYY-MM-DD</option>
                                        <option value="d F Y">DD Month YYYY</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="per_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Per Halaman</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                            id="per_page" name="per_page">
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">database</span>
                                    <span>Pengaturan Database</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Auto Backup</label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="auto_backup" name="auto_backup" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        <span class="ms-3 text-sm text-gray-700 dark:text-gray-300">Backup otomatis setiap hari</span>
                                    </label>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Maintenance Mode</label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="maintenance_mode" name="maintenance_mode" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        <span class="ms-3 text-sm text-gray-700 dark:text-gray-300">Aktifkan mode maintenance</span>
                                    </label>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="cache_lifetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cache Lifetime (menit)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="cache_lifetime" name="cache_lifetime" value="60" min="1" max="1440">
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    <span>Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Website Settings -->
                <div id="website" class="tab-pane">
                    <form id="websiteForm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">language</span>
                                    <span>Informasi Website</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Website</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="site_name" name="site_name" value="Website Desa Blanakan" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="site_tagline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tagline</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="site_tagline" name="site_tagline" value="Portal Informasi Desa">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="site_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                              id="site_description" name="site_description" rows="3">Website resmi Desa Blanakan</textarea>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">image</span>
                                    <span>Logo & Media</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="site_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Website</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="site_logo" name="site_logo" accept="image/*">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Maksimal 2MB, format: JPG, PNG</p>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="site_favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="site_favicon" name="site_favicon" accept="image/*">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Maksimal 1MB, format: ICO, PNG</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    <span>Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Data Desa -->
                <div id="desa" class="tab-pane">
                    <form id="desaForm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">location_on</span>
                                    <span>Data Desa</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="nama_desa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Desa</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="nama_desa" name="nama_desa" value="Blanakan" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kecamatan</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="kecamatan" name="kecamatan" value="Blanakan" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="kabupaten" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kabupaten</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="kabupaten" name="kabupaten" value="Subang" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="provinsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Provinsi</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="provinsi" name="provinsi" value="Jawa Barat" required>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">contact_mail</span>
                                    <span>Kontak Desa</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon</label>
                                    <input type="tel" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="telepon" name="telepon" value="">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="email_desa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="email_desa" name="email_desa" value="">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Kantor Desa</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                              id="alamat" name="alamat" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    <span>Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Email Settings -->
                <div id="email" class="tab-pane">
                    <form id="emailForm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">dns</span>
                                    <span>Konfigurasi SMTP</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SMTP Host</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="smtp_host" name="smtp_host" placeholder="smtp.gmail.com">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SMTP Port</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="smtp_port" name="smtp_port" value="587">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="smtp_user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="smtp_user" name="smtp_user">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="smtp_pass" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="smtp_pass" name="smtp_pass">
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">mail</span>
                                    <span>Pengaturan Email</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="mail_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Address</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="mail_from" name="mail_from">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="mail_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Name</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="mail_name" name="mail_name" value="Desa Blanakan">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enkripsi</label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="smtp_secure" name="smtp_secure" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        <span class="ms-3 text-sm text-gray-700 dark:text-gray-300">Gunakan SSL/TLS</span>
                                    </label>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2" 
                                            onclick="testEmail()">
                                        <span class="material-symbols-outlined text-lg">send</span>
                                        <span>Test Email</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    <span>Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div id="security" class="tab-pane">
                    <form id="securityForm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">shield</span>
                                    <span>Keamanan Login</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="max_login" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Maksimal Percobaan Login</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="max_login" name="max_login" value="5" min="3" max="10">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="lockout_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durasi Lockout (menit)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="lockout_time" name="lockout_time" value="30" min="5">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="session_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Session Timeout (menit)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="session_timeout" name="session_timeout" value="120" min="30">
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined">password</span>
                                    <span>Kebijakan Password</span>
                                </h3>
                                
                                <div class="mb-4">
                                    <label for="min_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Panjang Minimal Password</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           id="min_password" name="min_password" value="8" min="6" max="20">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Persyaratan Password</label>
                                    <div class="space-y-2">
                                        <label class="relative inline-flex items-center cursor-pointer w-full">
                                            <input type="checkbox" id="require_uppercase" name="require_uppercase" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                            <span class="ms-3 text-sm text-gray-700 dark:text-gray-300">Harus ada huruf besar</span>
                                        </label>
                                        <label class="relative inline-flex items-center cursor-pointer w-full">
                                            <input type="checkbox" id="require_number" name="require_number" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                            <span class="ms-3 text-sm text-gray-700 dark:text-gray-300">Harus ada angka</span>
                                        </label>
                                        <label class="relative inline-flex items-center cursor-pointer w-full">
                                            <input type="checkbox" id="require_special" name="require_special" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                            <span class="ms-3 text-sm text-gray-700 dark:text-gray-300">Harus ada karakter khusus</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    <span>Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
            
            // Hide all tab panes
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
                pane.classList.add('hidden');
            });
            
            // Show target tab pane
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('active');
                targetPane.classList.remove('hidden');
            }
        });
    });
    
    // Form submission
    const generalForm = document.getElementById('generalForm');
    if (generalForm) {
        generalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Pengaturan umum berhasil disimpan!');
        });
    }
    
    const websiteForm = document.getElementById('websiteForm');
    if (websiteForm) {
        websiteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Pengaturan website berhasil disimpan!');
        });
    }
    
    const desaForm = document.getElementById('desaForm');
    if (desaForm) {
        desaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Data desa berhasil disimpan!');
        });
    }
    
    const emailForm = document.getElementById('emailForm');
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Pengaturan email berhasil disimpan!');
        });
    }
    
    const securityForm = document.getElementById('securityForm');
    if (securityForm) {
        securityForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Pengaturan keamanan berhasil disimpan!');
        });
    }
});

function backupData() {
    if (confirm('Yakin ingin membuat backup data?')) {
        window.location.href = '<?= base_url('admin/backup') ?>';
    }
}

function showHelp() {
    alert('Panduan:\n\n1. Klik tab untuk melihat pengaturan berbeda\n2. Ubah nilai sesuai kebutuhan\n3. Klik "Simpan Pengaturan" untuk menyimpan perubahan');
}

function resetAll() {
    if (confirm('Reset semua tombol yang macet?')) {
        location.reload();
    }
}

function panicReset() {
    if (confirm('PANIC RESET: Refresh halaman sekarang?')) {
        location.reload();
    }
}

function testEmail() {
    alert('Test email akan dikirim ke alamat yang dikonfigurasi.');
}
</script>

<style>
.tab-button {
    @apply text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700;
}

.tab-button.active {
    @apply bg-blue-600 text-white;
}

.tab-pane {
    @apply hidden;
}

.tab-pane.active {
    @apply block;
}
</style>

<?php $this->endSection() ?>
