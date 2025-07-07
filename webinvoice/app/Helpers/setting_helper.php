<?php

if (!function_exists('setting')) {
    function setting($key, $group = null, $default = null)
    {
        $settingModel = model('SettingModel');
        $value = $settingModel->get($key, $group);
        return $value !== null ? $value : $default;
    }
}

if (!function_exists('setting_group')) {
    function setting_group($group)
    {
        $settingModel = new \App\Models\SettingModel();
        return $settingModel->getGroup($group);
    }
}

if (!function_exists('set_setting')) {
    function set_setting($key, $value, $group = null)
    {
        $settingModel = model('SettingModel');
        
        if ($value !== null) {
            return $settingModel->setSetting($key, $value, $group);
        }
        
        return $settingModel->get($key, $group);
    }
}

if (!function_exists('company_name')) {
    function company_name()
    {
        return setting('name', 'company', 'My Company');
    }
}

if (!function_exists('company_address')) {
    function company_address()
    {
        return setting('address', 'company', '');
    }
}

if (!function_exists('company_phone')) {
    function company_phone()
    {
        return setting('phone', 'company', '');
    }
}

if (!function_exists('company_email')) {
    function company_email()
    {
        return setting('email', 'company', '');
    }
}

if (!function_exists('company_website')) {
    function company_website()
    {
        return setting('website', 'company', '');
    }
}

if (!function_exists('company_tax_id')) {
    function company_tax_id()
    {
        return setting('tax_id', 'company', '');
    }
}

if (!function_exists('company_logo')) {
    function company_logo()
    {
        return setting('logo', 'company', '');
    }
}

if (!function_exists('invoice_prefix')) {
    function invoice_prefix()
    {
        return setting('prefix', 'invoice', 'INV');
    }
}

if (!function_exists('invoice_next_number')) {
    function invoice_next_number()
    {
        return (int) setting('next_number', 'invoice', '1');
    }
}

if (!function_exists('invoice_due_days')) {
    function invoice_due_days()
    {
        return (int) setting('due_days', 'invoice', '30');
    }
}

if (!function_exists('invoice_notes')) {
    function invoice_notes()
    {
        return setting('notes', 'invoice', 'Terima kasih atas kepercayaan Anda.');
    }
}

if (!function_exists('invoice_terms')) {
    function invoice_terms()
    {
        return setting('terms', 'invoice', 'Syarat dan ketentuan berlaku.');
    }
}

if (!function_exists('invoice_late_payment_fee')) {
    function invoice_late_payment_fee()
    {
        return (float) setting('late_payment_fee', 'invoice', '0');
    }
}

if (!function_exists('invoice_currency')) {
    function invoice_currency()
    {
        return setting('currency', 'invoice', 'IDR');
    }
}

if (!function_exists('email_from_name')) {
    function email_from_name()
    {
        return setting('from_name', 'email', company_name());
    }
}

if (!function_exists('email_from_address')) {
    function email_from_address()
    {
        return setting('from_email', 'email', company_email());
    }
}

if (!function_exists('smtp_config')) {
    function smtp_config()
    {
        return [
            'protocol' => 'smtp',
            'SMTPHost' => setting('smtp_host', 'email', ''),
            'SMTPPort' => (int) setting('smtp_port', 'email', '587'),
            'SMTPUser' => setting('smtp_user', 'email', ''),
            'SMTPPass' => setting('smtp_pass', 'email', ''),
            'SMTPCrypto' => setting('smtp_crypto', 'email', 'tls'),
            'mailType' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];
    }
}

if (!function_exists('notification_payment_reminder_days')) {
    function notification_payment_reminder_days()
    {
        return (int) setting('payment_reminder_days', 'notification', '3');
    }
}

if (!function_exists('notification_email_enabled')) {
    function notification_email_enabled()
    {
        return (bool) setting('enable_email_notifications', 'notification', '1');
    }
}

if (!function_exists('notification_system_enabled')) {
    function notification_system_enabled()
    {
        return (bool) setting('enable_system_notifications', 'notification', '1');
    }
} 