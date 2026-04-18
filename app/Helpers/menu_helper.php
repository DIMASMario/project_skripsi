<?php

/**
 * Helper function untuk menentukan menu aktif pada sidebar admin
 * 
 * @param string $path Path yang akan dicek
 * @param string $exact Apakah pengecekan harus exact match atau tidak
 * @return bool
 */
function isMenuActive($path, $exact = false)
{
    $currentPath = uri_string();
    
    if ($exact) {
        return $currentPath === $path;
    }
    
    return strpos($currentPath, $path) === 0;
}

/**
 * Helper function khusus untuk submenu statistik
 * Mengatasi masalah double active pada menu statistik
 * 
 * @param string $submenuPath Path submenu yang akan dicek
 * @return string CSS class untuk menu aktif
 */
function getStatistikActiveClass($submenuPath)
{
    $currentPath = uri_string();
    $segments = explode('/', $currentPath);
    
    // Dapatkan segment-segment yang diperlukan
    $segment1 = $segments[0] ?? '';
    $segment2 = $segments[1] ?? '';
    $segment3 = $segments[2] ?? '';
    
    // Parsing path yang akan dicek
    $checkSegments = explode('/', $submenuPath);
    $checkSegment1 = $checkSegments[0] ?? '';
    $checkSegment2 = $checkSegments[1] ?? '';
    $checkSegment3 = $checkSegments[2] ?? '';
    
    // Pengecekan spesifik untuk menu statistik
    if ($segment1 === 'admin' && $segment2 === 'statistik') {
        
        // Jika yang dicek adalah main statistik (admin/statistik)
        if ($submenuPath === 'admin/statistik') {
            // Aktif hanya jika tidak ada segment ke-3 atau segment ke-3 kosong
            return (empty($segment3)) ? 'bg-primary text-white' : '';
        }
        
        // Jika yang dicek adalah submenu statistik
        if ($checkSegment2 === 'statistik' && !empty($checkSegment3)) {
            // Aktif hanya jika segment ke-3 exact match
            return ($segment3 === $checkSegment3) ? 'bg-primary text-white' : '';
        }
    }
    
    return '';
}

/**
 * Helper function untuk mendapatkan class active menu biasa
 * 
 * @param string $menuPath Path menu yang akan dicek
 * @param bool $exact Apakah pengecekan harus exact match
 * @return string CSS class untuk menu aktif
 */
function getMenuActiveClass($menuPath, $exact = false)
{
    if (isMenuActive($menuPath, $exact)) {
        return 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-white';
    }
    
    return 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300';
}



/**
 * Helper function untuk menentukan apakah submenu harus terbuka
 * 
 * @param string $menuGroup Group menu (misal: statistik, warga, surat)
 * @return bool
 */
function shouldSubmenuOpen($menuGroup)
{
    $currentPath = uri_string();
    
    switch ($menuGroup) {
        case 'statistik':
            return strpos($currentPath, 'admin/statistik') === 0;
        case 'warga':
            return strpos($currentPath, 'admin/users') === 0 || 
                   strpos($currentPath, 'admin/user-activity') === 0;
        case 'surat':
            return strpos($currentPath, 'admin/surat') === 0;
        case 'konten':
            return strpos($currentPath, 'admin/berita') === 0 || 
                   strpos($currentPath, 'admin/galeri') === 0 || 
                   strpos($currentPath, 'admin/pengumuman') === 0;
        case 'config':
            // Harus exact match atau dengan query string, tidak boleh prefix lain
            return $currentPath === 'admin/settings' || 
                   strpos($currentPath, 'admin/settings?') === 0 ||
                   $currentPath === 'admin/config' || 
                   strpos($currentPath, 'admin/config?') === 0 ||
                   strpos($currentPath, 'admin/config-email') === 0 || 
                   strpos($currentPath, 'admin/config-payment') === 0;
        case 'security':
            return strpos($currentPath, 'admin/roles') === 0 || 
                   strpos($currentPath, 'admin/security') === 0 ||
                   strpos($currentPath, 'admin/keamanan') === 0;
        case 'maintenance':
            return strpos($currentPath, 'admin/backup') === 0 || 
                   strpos($currentPath, 'admin/logs') === 0 ||
                   strpos($currentPath, 'admin/maintenance') === 0;
        case 'admin':
            return strpos($currentPath, 'admin/profil') === 0 || 
                   strpos($currentPath, 'admin/role-management') === 0;
        default:
            return false;
    }
}

/**
 * Helper function untuk submenu active class
 * 
 * @param string $path Path submenu yang akan dicek
 * @return string CSS class untuk submenu aktif
 */
function getSubmenuActiveClass($path)
{
    $currentPath = uri_string();
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    
    // Pisahkan path dan query string dari parameter
    $pathParts = explode('?', $path);
    $checkPath = $pathParts[0];
    $checkQuery = $pathParts[1] ?? '';
    
    // Jika ada query string di URL saat ini
    if ($queryString) {
        $fullCurrentPath = $currentPath . '?' . $queryString;
        
        // Jika path yang dicek juga ada query string
        if ($checkQuery) {
            // Harus exact match untuk path + query
            if ($fullCurrentPath === $path) {
                return 'text-primary bg-blue-50 dark:bg-blue-900/20 font-medium';
            }
        }
        // Jika path yang dicek tidak ada query string, jangan aktifkan
        // karena URL saat ini ada query string
        return '';
    } else {
        // Jika tidak ada query string di URL saat ini
        // Hanya aktifkan jika path yang dicek juga tidak ada query string
        if (!$checkQuery && $currentPath === $checkPath) {
            return 'text-primary bg-blue-50 dark:bg-blue-900/20 font-medium';
        }
    }
    
    return '';
}