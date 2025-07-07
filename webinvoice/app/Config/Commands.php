<?php

namespace App\Config;

class Commands
{
    public $commands = [
        // ... existing commands ...
        'App\Commands\CheckDueInvoices',
        'invoice:send-reminders' => \App\Commands\SendPaymentReminders::class
    ];
} 