<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .message {
            margin-bottom: 30px;
        }
        .invoice-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name"><?= $company['name'] ?></div>
        </div>

        <div class="message">
            <p>Yth. <?= esc($invoice['customer_name']) ?>,</p>
            
            <p>Terima kasih atas kepercayaan Anda kepada <?= $company['name'] ?>. 
               Berikut kami lampirkan invoice untuk pesanan Anda:</p>
        </div>

        <div class="invoice-details">
            <p><strong>Nomor Invoice:</strong> <?= $invoice['invoice_number'] ?></p>
            <p><strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($invoice['created_at'])) ?></p>
            <p><strong>Jatuh Tempo:</strong> <?= date('d/m/Y', strtotime($invoice['due_date'])) ?></p>
            <p><strong>Total Pembayaran:</strong></p>
            <div class="amount">Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></div>
        </div>

        <div class="payment-info">
            <p>Silakan lakukan pembayaran ke rekening berikut:</p>
            <p>
                <?= get_setting('bank_name') ?><br>
                No. Rekening: <?= get_setting('bank_account') ?><br>
                a.n <?= get_setting('bank_account_name') ?>
            </p>
            <p>
                <strong>Catatan:</strong> Mohon cantumkan nomor invoice 
                <strong><?= $invoice['invoice_number'] ?></strong> pada keterangan transfer.
            </p>
        </div>

        <p>Invoice dalam format PDF terlampir dalam email ini.</p>

        <p>Jika Anda memiliki pertanyaan, silakan hubungi kami:</p>
        <p>
            Email: <?= $company['email'] ?><br>
            Telepon: <?= $company['phone'] ?><br>
            Alamat: <?= $company['address'] ?>
        </p>

        <div class="footer">
            Email ini dibuat secara otomatis. Mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html> 