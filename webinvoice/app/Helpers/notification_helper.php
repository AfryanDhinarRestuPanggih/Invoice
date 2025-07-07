<?php

if (!function_exists('send_notification')) {
    function send_notification($userId, $title, $message, $type = 'info', $link = null)
    {
        $notificationModel = new \App\Models\NotificationModel();
        return $notificationModel->createNotification($userId, $title, $message, $type, $link);
    }
}

if (!function_exists('notify_invoice_created')) {
    function notify_invoice_created($invoice)
    {
        $title = 'Invoice Baru Dibuat';
        $message = sprintf(
            'Invoice %s untuk %s telah dibuat dengan total Rp %s',
            $invoice['invoice_number'],
            $invoice['customer_name'],
            number_format($invoice['total_amount'])
        );
        $link = base_url('invoices/' . $invoice['id']);
        
        // Notify admin
        $adminModel = new \Myth\Auth\Models\UserModel();
        $admins = $adminModel->getUsersInGroup('admin');
        foreach ($admins as $admin) {
            send_notification($admin->id, $title, $message, 'info', $link);
        }
    }
}

if (!function_exists('notify_invoice_sent')) {
    function notify_invoice_sent($invoice)
    {
        $title = 'Invoice Terkirim';
        $message = sprintf(
            'Invoice %s untuk %s telah dikirim ke email %s',
            $invoice['invoice_number'],
            $invoice['customer_name'],
            $invoice['customer_email']
        );
        $link = base_url('invoices/' . $invoice['id']);
        
        // Notify creator and admin
        send_notification($invoice['user_id'], $title, $message, 'primary', $link);
        
        $adminModel = new \Myth\Auth\Models\UserModel();
        $admins = $adminModel->getUsersInGroup('admin');
        foreach ($admins as $admin) {
            if ($admin->id != $invoice['user_id']) {
                send_notification($admin->id, $title, $message, 'primary', $link);
            }
        }
    }
}

if (!function_exists('notify_invoice_paid')) {
    function notify_invoice_paid($invoice)
    {
        $title = 'Pembayaran Invoice Diterima';
        $message = sprintf(
            'Pembayaran untuk invoice %s dari %s telah diterima sebesar Rp %s',
            $invoice['invoice_number'],
            $invoice['customer_name'],
            number_format($invoice['total_amount'])
        );
        $link = base_url('invoices/' . $invoice['id']);
        
        // Notify creator and admin
        send_notification($invoice['user_id'], $title, $message, 'success', $link);
        
        $adminModel = new \Myth\Auth\Models\UserModel();
        $admins = $adminModel->getUsersInGroup('admin');
        foreach ($admins as $admin) {
            if ($admin->id != $invoice['user_id']) {
                send_notification($admin->id, $title, $message, 'success', $link);
            }
        }
    }
}

if (!function_exists('notify_invoice_cancelled')) {
    function notify_invoice_cancelled($invoice)
    {
        $title = 'Invoice Dibatalkan';
        $message = sprintf(
            'Invoice %s untuk %s telah dibatalkan',
            $invoice['invoice_number'],
            $invoice['customer_name']
        );
        $link = base_url('invoices/' . $invoice['id']);
        
        // Notify creator and admin
        send_notification($invoice['user_id'], $title, $message, 'danger', $link);
        
        $adminModel = new \Myth\Auth\Models\UserModel();
        $admins = $adminModel->getUsersInGroup('admin');
        foreach ($admins as $admin) {
            if ($admin->id != $invoice['user_id']) {
                send_notification($admin->id, $title, $message, 'danger', $link);
            }
        }
    }
}

if (!function_exists('notify_payment_reminder')) {
    function notify_payment_reminder($invoice)
    {
        $title = 'Pengingat Pembayaran Invoice';
        $message = sprintf(
            'Invoice %s untuk %s belum dibayar. Total: Rp %s',
            $invoice['invoice_number'],
            $invoice['customer_name'],
            number_format($invoice['total_amount'])
        );
        $link = base_url('invoices/' . $invoice['id']);
        
        // Notify creator and admin
        send_notification($invoice['user_id'], $title, $message, 'warning', $link);
        
        $adminModel = new \Myth\Auth\Models\UserModel();
        $admins = $adminModel->getUsersInGroup('admin');
        foreach ($admins as $admin) {
            if ($admin->id != $invoice['user_id']) {
                send_notification($admin->id, $title, $message, 'warning', $link);
            }
        }
    }
} 