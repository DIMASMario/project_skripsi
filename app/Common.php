<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('formatBytes')) {
    /**
     * Format bytes into human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Convert timestamp to human readable time ago format
     *
     * @param string $datetime
     * @return string
     */
    function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        $units = [
            31536000 => 'tahun',
            2592000  => 'bulan', 
            604800   => 'minggu',
            86400    => 'hari',
            3600     => 'jam',
            60       => 'menit',
            1        => 'detik'
        ];
        
        foreach ($units as $unit => $val) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return ($val == 'detik') ? 'baru saja' : $numberOfUnits . ' ' . $val . (($numberOfUnits > 1) ? '' : '') . ' yang lalu';
        }
        
        return 'baru saja';
    }
}

if (!function_exists('getStatusBadgeClass')) {
    /**
     * Get CSS class for status badge
     *
     * @param string $status
     * @return string
     */
    function getStatusBadgeClass($status)
    {
        switch (strtolower($status)) {
            case 'aktif':
            case 'active':
            case 'selesai':
            case 'completed':
                return 'bg-green-100 text-green-800';
            case 'pending':
            case 'menunggu':
                return 'bg-yellow-100 text-yellow-800';
            case 'diproses':
            case 'processing':
                return 'bg-blue-100 text-blue-800';
            case 'ditolak':
            case 'rejected':
            case 'nonaktif':
                return 'bg-red-100 text-red-800';
            case 'suspended':
                return 'bg-orange-100 text-orange-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}
