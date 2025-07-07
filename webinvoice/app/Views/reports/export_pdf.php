<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
        .summary-item strong {
            display: block;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            color: white;
        }
        .badge-secondary { background-color: #6c757d; }
        .badge-primary { background-color: #007bff; }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Invoice</h1>
        <p>Periode: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Invoice</strong>
            <?= number_format($summary['total_invoices']) ?>
        </div>
        <div class="summary-item">
            <strong>Total Pendapatan</strong>
            Rp <?= number_format($summary['total_revenue']) ?>
        </div>
        <div class="summary-item">
            <strong>Total Belum Dibayar</strong>
            Rp <?= number_format($summary['total_pending']) ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th class="text-end">Total</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $i => $invoice): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($invoice['invoice_number']) ?></td>
                    <td><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                    <td>
                        <?= esc($invoice['customer_name']) ?><br>
                        <small><?= esc($invoice['customer_email']) ?></small>
                    </td>
                    <td class="text-end">Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></td>
                    <td>
                        <span class="badge badge-<?= get_invoice_status_color($invoice['status']) ?>">
                            <?= get_invoice_status_label($invoice['status']) ?>
                        </span>
                    </td>
                    <td><?= esc($invoice['created_by']) ?></td>
                    <td><?= $invoice['paid_at'] ? date('d/m/Y', strtotime($invoice['paid_at'])) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html> 