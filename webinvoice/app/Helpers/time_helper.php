<?php

if (!function_exists('time_ago')) {
    function time_ago($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } elseif ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return $weeks . ' minggu yang lalu';
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return $months . ' bulan yang lalu';
        } else {
            $years = floor($diff / 31536000);
            return $years . ' tahun yang lalu';
        }
    }
} 