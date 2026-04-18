<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->get('/', 'Home::index');

// Dynamic Frontend Routes (New)
$routes->get('/profil-desa', 'Profil::index');
$routes->get('/profil', 'Profil::index'); // Keep for backward compatibility
$routes->get('/data-desa', 'DataDesa::index');
$routes->get('/budaya-wisata', 'BudayaWisata::index');
$routes->get('/galeri', 'Galeri::index');
$routes->get('/kontak', 'Kontak::index');
$routes->post('/kontak/kirim', 'Kontak::kirimPesan');

// Layanan Online (Dynamic)
$routes->get('/layanan-online', 'LayananOnline::index');
$routes->get('/layanan-online/surat-keterangan', 'LayananOnline::suratKeterangan');
$routes->get('/layanan-online/surat-keterangan-usaha', 'LayananOnline::suratKeteranganUsaha');
$routes->get('/layanan-online/surat-keterangan-domisili', 'LayananOnline::suratKeteranganDomisili');
$routes->get('/layanan-online/cek-status', 'LayananOnline::cekStatus');

// Legacy Routes (Keep for backward compatibility)
$routes->get('/wisata/(:segment)', 'Home::wisataDetail/$1');

// Berita Routes (Dynamic)
$routes->get('/berita', 'Berita::index');
$routes->get('/berita/(:segment)', 'Berita::detailBerita/$1');
$routes->get('/berita-api/kategori/(:segment)', 'Berita::getByKategori/$1');
$routes->get('/berita-api/search', 'Berita::search');

// Warga (Direktori Warga) Routes
$routes->get('/warga', 'Warga::index');
$routes->get('/warga/(:num)', 'Warga::detail/$1');
$routes->get('/warga-api/search', 'Warga::search');
$routes->get('/warga-api/filter', 'Warga::filter');
$routes->get('/warga-api/statistics', 'Warga::statistics');

// Keep old routes for backward compatibility (now handled by new controllers)
// $routes->get('/galeri', 'Home::galeri'); - Now handled by Galeri::index
// $routes->get('/kontak', 'Home::kontak'); - Now handled by Kontak::index
// $routes->post('/kontak/kirim', 'Home::kirimKontak'); - Now handled by Kontak::kirimPesan

// Surat Pengajuan Routes untuk Warga (Protected)
$routes->group('surat-pengajuan', ['filter' => 'auth:warga,admin'], function($routes) {
    $routes->get('/', 'SuratPengajuan::index');
    $routes->match(['GET', 'POST'], 'form/(:segment)', 'SuratPengajuan::form/$1');
    $routes->post('proses', 'SuratPengajuan::proses');
    $routes->get('detail/(:num)', 'SuratPengajuan::detail/$1');
    $routes->get('download/(:num)', 'SuratPengajuan::download/$1');
    $routes->get('list', 'SuratPengajuan::listSurat');
});

// Authentication Routes
$routes->group('auth', function($routes) {
    $routes->get('login-selection', 'Auth::loginSelection');
    $routes->match(['GET', 'POST'], 'login', 'Auth::login');
    $routes->match(['GET', 'POST'], 'admin-login', 'Auth::adminLogin');
    $routes->match(['GET', 'POST'], 'register', 'Auth::register'); // Step 1: Data Pribadi
    $routes->match(['GET', 'POST'], 'register/step2', 'Auth::registerStep2'); // Step 2: Alamat
    $routes->match(['GET', 'POST'], 'register/step3', 'Auth::registerStep3'); // Step 3: Upload Dokumen
    $routes->match(['GET', 'POST'], 'register/review', 'Auth::registerReview'); // Step 4: Review
    $routes->get('register/success', 'Auth::registerSuccess');
    
    // AJAX Routes for Wilayah
    $routes->get('api/wilayah/kabupaten-kota', 'Auth::getKabupatenKota');
    $routes->get('api/wilayah/kecamatan', 'Auth::getKecamatan');
    $routes->get('api/wilayah/kelurahan-desa', 'Auth::getKelurahanDesa');
    
    $routes->get('logout', 'Auth::logout');
    $routes->match(['GET', 'POST'], 'forgot-password', 'Auth::forgotPassword');
});

// Layanan Online Routes (Old - still needed for authenticated services)
$routes->get('layanan', 'Layanan::index'); // Allow non-logged users to see the page
$routes->group('layanan-online', ['filter' => 'auth:warga,admin'], function($routes) {
    $routes->get('ajukan', 'Layanan::ajukan'); // New form route
    $routes->get('ajukan/domisili', 'Layanan::ajukanSurat/domisili');
    $routes->post('ajukan/domisili', 'Layanan::ajukanSurat/domisili');
    $routes->get('ajukan/usaha', 'Layanan::ajukanSurat/usaha');
    $routes->post('ajukan/usaha', 'Layanan::ajukanSurat/usaha');
    $routes->get('ajukan/skck', 'Layanan::ajukanSurat/skck');
    $routes->post('ajukan/skck', 'Layanan::ajukanSurat/skck');
    $routes->get('ajukan/(:segment)', 'Layanan::ajukanSurat/$1');
    $routes->post('ajukan/(:segment)', 'Layanan::ajukanSurat/$1');
    $routes->post('submit', 'Layanan::submit');
    $routes->get('download/(:num)', 'Layanan::download/$1');
    $routes->get('checkStatus/(:num)', 'Layanan::checkStatus/$1');
});

// User Dashboard Routes (Protected)
$routes->group('dashboard', ['filter' => 'auth:warga,admin'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->match(['GET', 'POST'], 'profil', 'Dashboard::profil');
    $routes->get('riwayat-surat', 'Dashboard::riwayatSurat');
    $routes->get('detail-surat/(:num)', 'Dashboard::detailSurat/$1');
    $routes->get('cancel-surat/(:num)', 'Dashboard::cancelSurat/$1');
    $routes->get('surat', 'Dashboard::surat');
    $routes->get('download-surat/(:num)', 'Dashboard::downloadSurat/$1');
    $routes->get('refresh-summary', 'Dashboard::refreshSummary');
    
    // Notifikasi Warga
    $routes->get('notifikasi', 'Dashboard::getNotifikasi');
    $routes->post('notifikasi/read/(:num)', 'Dashboard::markNotifikasiRead/$1');
    $routes->post('notifikasi/read-all', 'Dashboard::markAllNotifikasiRead');
});

// Admin Panel Routes (Protected)
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin::index');
    
    // User Management
    $routes->get('users', 'Admin::users');
    $routes->post('users/verifikasi/(:num)', 'Admin::verifikasiUser/$1');
    $routes->post('users/suspend/(:num)', 'Admin::suspendUser/$1');
    $routes->post('users/activate/(:num)', 'Admin::activateUser/$1');
    $routes->get('user-activity', 'Admin::userActivity');
    
    // New User Management Features
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->post('users/reset-password/(:num)', 'Admin::resetPassword/$1');
    $routes->post('users/delete/(:num)', 'Admin::deleteUser/$1');
    
    // Bulk Actions
    $routes->post('users/bulk-delete', 'Admin::bulkDeleteUsers');
    $routes->post('users/bulk-status', 'Admin::bulkChangeStatus');
    $routes->post('users/bulk-export', 'Admin::bulkExportUsers');
    $routes->get('users/download-export/(:segment)', 'Admin::downloadExport/$1');
    
    // Surat Management
    $routes->get('surat', 'Admin::surat');
    $routes->get('detailSurat/(:num)', 'Admin::detailSurat/$1');
    $routes->post('uploadFileSurat/(:num)', 'Admin::uploadFileSurat/$1');
    
    // New Surat Management Routes (AdminSurat Controller)
    $routes->get('surat-pengajuan', 'AdminSurat::index');
    $routes->get('surat-pengajuan/detail/(:num)', 'AdminSurat::detail/$1');
    $routes->post('surat-pengajuan/ubahStatus/(:num)', 'AdminSurat::ubahStatus/$1');
    $routes->post('surat-pengajuan/uploadFile/(:num)', 'AdminSurat::uploadFile/$1');
    $routes->post('surat-pengajuan/tolak/(:num)', 'AdminSurat::tolak/$1');
    
    // Redirect old editSurat route to detailSurat (backward compatibility)
    // Edit feature disabled - surat tidak dapat diedit setelah diajukan
    $routes->get('editSurat/(:num)', 'Admin::detailSurat/$1');
    $routes->get('surat/edit/(:num)', 'Admin::detailSurat/$1');
    
    // Delete surat
    $routes->match(['GET', 'POST'], 'hapusSurat/(:num)', 'Admin::deleteSurat/$1');
    $routes->match(['GET', 'POST'], 'surat/hapus/(:num)', 'Admin::deleteSurat/$1');
    
    // Print surat
    $routes->get('cetakSurat/(:num)', 'Admin::cetakSurat/$1');
    $routes->get('surat/cetak/(:num)', 'Admin::cetakSurat/$1');
    
    $routes->match(['GET', 'POST'], 'surat/proses/(:num)', 'Admin::prosesSurat/$1');
    $routes->match(['GET', 'POST'], 'surat/tolak/(:num)', 'Admin::tolakSurat/$1');
    $routes->match(['GET', 'POST'], 'surat/selesai/(:num)', 'Admin::selesaiSurat/$1');
    $routes->match(['GET', 'POST'], 'tolakSurat/(:num)', 'Admin::tolakSurat/$1');
    $routes->match(['GET', 'POST'], 'selesaiSurat/(:num)', 'Admin::selesaiSurat/$1');
    $routes->match(['GET', 'POST'], 'prosesSurat/(:num)', 'Admin::prosesSurat/$1');
    $routes->get('surat-template', 'Admin::suratTemplate');
    $routes->get('surat-template/preview/(:num)', 'Admin::previewTemplate/$1');
    $routes->match(['GET', 'POST'], 'surat-template/tambah', 'Admin::tambahTemplate');
    $routes->match(['GET', 'POST'], 'surat-template/edit/(:num)', 'Admin::editTemplate/$1');
    $routes->get('surat-template/hapus/(:num)', 'Admin::hapusTemplate/$1');
    $routes->get('surat-arsip', 'Admin::suratArsip');
    
    // Berita Management
    $routes->get('berita', 'Admin::berita');
    $routes->match(['GET', 'POST'], 'berita/tambah', 'Admin::tambahBerita');
    $routes->match(['GET', 'POST'], 'berita/edit/(:num)', 'Admin::editBerita/$1');
    $routes->get('berita/hapus/(:num)', 'Admin::hapusBerita/$1');
    $routes->post('berita/publish/(:num)', 'Admin::publishBerita/$1');
    
    // Galeri Management
    $routes->get('galeri', 'Admin::galeri');
    $routes->match(['GET', 'POST'], 'galeri/tambah', 'Admin::tambahGaleri');
    $routes->match(['GET', 'POST'], 'tambah_galeri', 'Admin::tambahGaleri'); // Alternative route
    $routes->match(['GET', 'POST'], 'galeri/edit/(:num)', 'Admin::editGaleri/$1');
    $routes->get('galeri/hapus/(:num)', 'Admin::hapusGaleri/$1');
    
    // Pengumuman Management
    $routes->get('pengumuman', 'Admin::pengumuman');
    $routes->match(['GET', 'POST'], 'pengumuman/tambah', 'Admin::tambahPengumuman');
    $routes->match(['GET', 'POST'], 'pengumuman/edit/(:num)', 'Admin::editPengumuman/$1');
    $routes->get('pengumuman/hapus/(:num)', 'Admin::hapusPengumuman/$1');
    
    // Statistik Management (New Dynamic System)
    $routes->get('statistik', 'Admin\StatistikController::index');
    $routes->get('statistik/user', 'Admin\StatistikController::user');
    $routes->get('statistik/surat', 'Admin\StatistikController::surat');
    $routes->get('statistik/traffic', 'Admin\StatistikController::traffic');
    $routes->get('statistik/edit/(:num)', 'Admin\StatistikController::edit/$1');
    $routes->post('statistik/update', 'Admin\StatistikController::update');
    $routes->post('statistik/store', 'Admin\StatistikController::store');
    $routes->post('statistik/update/(:num)', 'Admin\StatistikController::update/$1');
    $routes->delete('statistik/delete/(:num)', 'Admin\StatistikController::delete/$1');
    $routes->get('statistik/api/kategori/(:segment)', 'Admin\StatistikController::getByKategori/$1');
    
    // Settings Management
    $routes->match(['GET', 'POST'], 'settings', 'Admin::settings');
    $routes->match(['GET', 'POST'], 'config-email', 'Admin::configEmail');
    $routes->match(['GET', 'POST'], 'config-payment', 'Admin::configPayment');
    $routes->match(['GET', 'POST'], 'keamanan', 'Admin::keamanan');
    $routes->match(['GET', 'POST'], 'backup', 'Admin::backup');
    
    // New Configuration Management
    $routes->get('config', 'Admin::config');
    $routes->post('config/update', 'Admin::updateConfig');
    $routes->post('config/test-email', 'Admin::testEmail');
    
    // Backup Management
    $routes->post('backup/create', 'Admin::createBackup');
    $routes->get('backup/download/(:segment)', 'Admin::downloadBackup/$1');
    $routes->post('backup/restore', 'Admin::restoreBackup');
    $routes->post('backup/delete', 'Admin::deleteBackup');
    $routes->post('backup/upload', 'Admin::uploadBackup');
    $routes->post('backup/check-last', 'Admin::checkLastBackup');
    
    // Role & Permission Management
    $routes->get('roles', 'Admin::roles');
    $routes->post('roles/create', 'Admin::createRole');
    $routes->post('roles/update', 'Admin::updateRole');
    $routes->post('roles/delete', 'Admin::deleteRole');
    $routes->get('roles/get/(:num)', 'Admin::getRole/$1');
    $routes->post('users/toggle-status', 'Admin::toggleUserStatus');
    
    // Security Management
    $routes->get('security', 'Admin::security');
    $routes->post('security/update-settings', 'Admin::updateSecuritySettings');
    $routes->post('security/block-ip', 'Admin::blockIP');
    
    // System Information
    $routes->get('system-info', 'Admin::systemInfo');
    $routes->post('security/unblock-ip', 'Admin::unblockIP');
    $routes->post('security/refresh-status', 'Admin::refreshSecurityStatus');
    
    // Login Attempts & Security Logs
    $routes->get('login-attempts', 'Admin::loginAttempts');
    $routes->get('login-attempts/export', 'Admin::exportLoginAttempts');
    $routes->get('security-logs', 'Admin::securityLogs');
    $routes->get('security-logs/export', 'Admin::exportSecurityLogs');
    
    // System Logs
    $routes->get('logs', 'Admin::systemLogs');
    $routes->get('logs/view/(:segment)', 'Admin::viewLog/$1');
    $routes->post('logs/clear', 'Admin::clearLogs');
    
    // Cache Management
    $routes->get('cache', 'Admin::cacheManagement');
    $routes->post('cache/clear', 'Admin::clearCache');
    $routes->post('cache/clear-specific', 'Admin::clearSpecificCache');
    
    // Admin Panel Management
    $routes->match(['GET', 'POST'], 'profil', 'Admin::profil');
    $routes->get('role-management', 'Admin::roleManagement');
    $routes->post('role-management/assign/(:num)', 'Admin::assignRole/$1');
    
    // Notifikasi Admin
    $routes->get('notifikasi', 'Admin::notifikasi');
    $routes->get('notifikasi/get', 'Admin::getNotifikasi');
    $routes->post('notifikasi/read/(:num)', 'Admin::markNotifikasiRead/$1');
    $routes->post('notifikasi/read-all', 'Admin::markAllNotifikasiRead');
    
    // Pengumuman Management
    $routes->get('pengumuman', 'Admin::pengumuman');
    $routes->post('pengumuman/add', 'Admin::addPengumuman');
    $routes->match(['GET', 'POST'], 'pengumuman/edit/(:num)', 'Admin::editPengumuman/$1');
    $routes->delete('pengumuman/delete/(:num)', 'Admin::deletePengumuman/$1');
    
    // Galeri Management
    $routes->get('galeri', 'Admin::galeri');
    $routes->post('galeri/add', 'Admin::addGaleri');
    $routes->match(['GET', 'POST'], 'galeri/edit/(:num)', 'Admin::editGaleri/$1');
    $routes->delete('galeri/delete/(:num)', 'Admin::deleteGaleri/$1');
});

// AdminSurat Routes - Separate route group for Surat Management (Protected)
$routes->group('admin-surat', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'AdminSurat::index');
    $routes->get('detail/(:num)', 'AdminSurat::detail/$1');
    $routes->post('ubahStatus/(:num)', 'AdminSurat::ubahStatus/$1');
    $routes->post('uploadFile/(:num)', 'AdminSurat::uploadFile/$1');
    $routes->post('tolak/(:num)', 'AdminSurat::tolak/$1');
});

// Error Handler Routes
$routes->group('error', function($routes) {
    $routes->get('404', 'ErrorHandler::show404');
    $routes->get('403', 'ErrorHandler::show403'); 
    $routes->get('500', 'ErrorHandler::show500');
    $routes->get('maintenance', 'ErrorHandler::showMaintenance');
    $routes->get('csrf', 'ErrorHandler::showCSRFError');
    $routes->get('(:num)', 'ErrorHandler::showGenericError/$1');
    $routes->get('(:num)/(:any)', 'ErrorHandler::showGenericError/$1/$2');
});

// Set 404 override untuk redirect ke custom error page
// $routes->set404Override('ErrorHandler::show404'); // Disabled temporarily


