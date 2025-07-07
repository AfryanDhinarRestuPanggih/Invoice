<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class EmailService
{
    protected $config;
    protected $errors = [];

    public function __construct()
    {
        $this->config = [
            'protocol' => 'smtp',
            'SMTPHost' => getenv('email.smtp.host') ?: 'smtp.gmail.com',
            'SMTPPort' => (int)(getenv('email.smtp.port') ?: 587),
            'SMTPUser' => getenv('email.smtp.user') ?: '',
            'SMTPPass' => getenv('email.smtp.pass') ?: '',
            'SMTPCrypto' => 'tls',
            'mailType' => 'html',
            'charset'  => 'utf-8',
            'newline'  => "\r\n",
            'validate' => true,
            'timeout'  => 60
        ];
    }

    /**
     * Kirim invoice melalui email
     */
    public function sendInvoice($invoice, $items)
    {
        $email = \Config\Services::email();
        $email->initialize($this->config);

        $email->setFrom($this->config['SMTPUser'], get_setting('name'));
        $email->setTo($invoice['customer_email']);
        $email->setSubject('Invoice #' . $invoice['invoice_number']);

        // Generate invoice PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->setIsHtml5ParserEnabled(true);

        $dompdf = new Dompdf($options);
        $data = [
            'invoice' => $invoice,
            'items' => $items,
            'is_print' => true
        ];
        
        $html = view('invoices/print', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();

        // Attach PDF
        $email->attach($pdfContent, 'invoice-' . $invoice['invoice_number'] . '.pdf', 'application/pdf');

        // Email body
        $emailData = [
            'invoice' => $invoice,
            'company' => [
                'name' => get_setting('name'),
                'address' => get_setting('address'),
                'phone' => get_setting('phone'),
                'email' => get_setting('email')
            ]
        ];
        $message = view('emails/invoice', $emailData);
        $email->setMessage($message);

        if (!$email->send()) {
            $this->errors[] = $email->printDebugger(['headers']);
            return false;
        }

        return true;
    }

    /**
     * Get error messages
     */
    public function getErrors()
    {
        return $this->errors;
    }
} 