<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $invoice['invoice_number'] ?></title>
    
    <style>
        @media print {
            @page {
                margin: 0;
                size: A4;
            }
            body {
                margin: 1.6cm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
        }

        .invoice-header {
            margin-bottom: 2rem;
        }

        .company-info {
            margin-bottom: 2rem;
        }

        .company-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .invoice-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1rem;
            text-align: right;
        }

        .invoice-details {
            margin-bottom: 2rem;
        }

        .customer-info {
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .total-row {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .notes {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            font-weight: bold;
            border-radius: 0.25rem;
            text-transform: uppercase;
        }

        .status-draft { background-color: #e9ecef; color: #495057; }
        .status-sent { background-color: #cce5ff; color: #004085; }
        .status-paid { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }

        .footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6c757d;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 2rem;">
        <button onclick="window.print()">Cetak Invoice</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="invoice-header">
        <div style="display: flex; justify-content: space-between;">
            <div class="company-info">
                <div class="company-name"><?= company_name() ?></div>
                <div><?= company_address() ?></div>
                <div>Phone: <?= company_phone() ?></div>
                <div>Email: <?= company_email() ?></div>
            </div>
            <div>
                <div class="invoice-title">INVOICE</div>
                <div class="text-end">#<?= $invoice['invoice_number'] ?></div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between;" class="invoice-details">
        <div class="customer-info">
            <div style="margin-bottom: 0.5rem;"><strong>Kepada:</strong></div>
            <div style="font-size: 1.1rem;"><strong><?= esc($invoice['customer_name']) ?></strong></div>
            <div><?= nl2br(esc($invoice['customer_address'])) ?></div>
            <div>Phone: <?= esc($invoice['customer_phone']) ?></div>
            <div>Email: <?= esc($invoice['customer_email']) ?></div>
        </div>
        <div>
            <table style="width: auto;">
                <tr>
                    <td><strong>Tanggal Invoice:</strong></td>
                    <td><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                </tr>
                <tr>
                    <td><strong>Jatuh Tempo:</strong></td>
                    <td><?= date('d/m/Y', strtotime($invoice['due_date'])) ?></td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        <?php
                        $statusClass = [
                            'draft' => 'status-draft',
                            'sent' => 'status-sent',
                            'paid' => 'status-paid',
                            'cancelled' => 'status-cancelled'
                        ];
                        $statusText = [
                            'draft' => 'Draft',
                            'sent' => 'Terkirim',
                            'paid' => 'Lunas',
                            'cancelled' => 'Dibatalkan'
                        ];
                        ?>
                        <span class="status-badge <?= $statusClass[$invoice['status']] ?>">
                            <?= $statusText[$invoice['status']] ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-end">Harga</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <div><strong><?= esc($item['product_name']) ?></strong></div>
                    <div class="text-muted"><?= esc($item['product_code']) ?></div>
                </td>
                <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                <td class="text-center"><?= $item['quantity'] ?></td>
                <td class="text-end">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td class="text-end"><strong>Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <?php if (!empty($invoice['notes'])): ?>
    <div class="notes">
        <h4>Catatan:</h4>
        <p><?= nl2br(esc($invoice['notes'])) ?></p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda.</p>
        <?php if ($invoice['status'] === 'sent'): ?>
        <p>
            <strong>Metode Pembayaran:</strong><br>
            <?= setting('bank_name', 'payment') ?><br>
            No. Rek: <?= setting('bank_account', 'payment') ?><br>
            a.n <?= setting('bank_account_name', 'payment') ?>
        </p>
        <?php endif; ?>
    </div>
</body>
</html> 