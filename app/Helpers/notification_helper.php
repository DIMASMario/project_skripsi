<?php

/**
 * Convert timestamp to relative time (time ago)
 * 
 * @param string $datetime
 * @return string
 */
if (!function_exists('timeAgo')) {
    function timeAgo($datetime) {
        $timestamp = strtotime($datetime);
        $difference = time() - $timestamp;
        
        if ($difference < 60) {
            return 'Baru saja';
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return $days . ' hari yang lalu';
        } elseif ($difference < 2592000) {
            $weeks = floor($difference / 604800);
            return $weeks . ' minggu yang lalu';
        } else {
            return date('d M Y H:i', $timestamp);
        }
    }
}
