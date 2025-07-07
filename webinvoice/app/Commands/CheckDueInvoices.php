<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\InvoiceModel;
use App\Models\NotificationModel;

class CheckDueInvoices extends BaseCommand
{
    protected $group = 'Invoice';
    protected $name = 'invoice:check-due';
    protected $description = 'Check for invoices that are due soon and create notifications';
    
    public function run(array $params)
    {
        $invoiceModel = new InvoiceModel();
        $notificationModel = new NotificationModel();
        
        // Get invoices that are due in the next 7 days
        $dueInvoices = $invoiceModel
            ->where('status', 'sent')
            ->where('due_date >=', date('Y-m-d'))
            ->where('due_date <=', date('Y-m-d', strtotime('+7 days')))
            ->find();
        
        if (empty($dueInvoices)) {
            CLI::write('No invoices due soon.', 'yellow');
            return;
        }
        
        $count = 0;
        foreach ($dueInvoices as $invoice) {
            // Create notification if not already exists for this invoice
            $existingNotification = $notificationModel
                ->where('reference_id', $invoice['id'])
                ->where('reference_type', 'invoice')
                ->where('type', 'warning')
                ->where('created_at >=', date('Y-m-d'))
                ->first();
            
            if (!$existingNotification) {
                $notificationModel->createDueNotification($invoice);
                $count++;
            }
        }
        
        CLI::write("Created {$count} new notifications for due invoices.", 'green');
    }
} 