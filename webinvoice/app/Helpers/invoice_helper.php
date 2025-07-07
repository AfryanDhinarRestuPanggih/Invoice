<?php

use App\Models\SettingModel;

if (!function_exists('format_currency')) {
    function format_currency($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('get_invoice_status_label')) {
    function get_invoice_status_label($status)
    {
        $labels = [
            'draft' => 'Draft',
            'sent' => 'Terkirim',
            'paid' => 'Dibayar',
            'overdue' => 'Jatuh Tempo',
            'cancelled' => 'Batal'
        ];

        return $labels[$status] ?? $status;
    }
}

if (!function_exists('get_invoice_status_color')) {
    function get_invoice_status_color($status)
    {
        $colors = [
            'draft' => 'secondary',
            'sent' => 'info',
            'paid' => 'success',
            'overdue' => 'warning',
            'cancelled' => 'danger'
        ];

        return $colors[$status] ?? 'secondary';
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $group = 'company')
    {
        try {
            $settingModel = new SettingModel();
            return $settingModel->get($key, $group) ?? '';
        } catch (\Exception $e) {
            // Jika tabel belum ada atau terjadi error, kembalikan nilai default
            $defaults = [
                'company' => [
                    'name' => 'Nama Perusahaan',
                    'address' => 'Alamat Perusahaan',
                    'phone' => 'Telepon Perusahaan',
                    'email' => 'email@perusahaan.com'
                ]
            ];
            
            return $defaults[$group][$key] ?? '';
        }
    }
} 