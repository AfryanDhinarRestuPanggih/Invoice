<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SendPaymentReminders extends BaseCommand
{
    protected $group = 'Invoice';
    protected $name = 'invoice:send-reminders';
    protected $description = 'Send payment reminders for unpaid invoices';

    public function run(array $params)
    {
        $invoiceModel = new \App\Models\InvoiceModel();
        
        // Get unpaid invoices that are sent and due in 3 days
        $unpaidInvoices = $invoiceModel->where('status', 'sent')
            ->where('due_date <=', date('Y-m-d', strtotime('+3 days')))
            ->where('due_date >', date('Y-m-d'))
            ->findAll();

        if (empty($unpaidInvoices)) {
            CLI::write('No payment reminders to send.', 'yellow');
            return;
        }

        $count = 0;
        foreach ($unpaidInvoices as $invoice) {
            try {
                notify_payment_reminder($invoice);
                $count++;
                CLI::write(
                    sprintf(
                        'Sent reminder for invoice %s to %s',
                        $invoice['invoice_number'],
                        $invoice['customer_name']
                    ),
                    'green'
                );
            } catch (\Exception $e) {
                CLI::error(sprintf(
                    'Failed to send reminder for invoice %s: %s',
                    $invoice['invoice_number'],
                    $e->getMessage()
                ));
            }
        }

        CLI::write(sprintf('Sent %d payment reminders.', $count), 'green');
    }
} 