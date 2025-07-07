<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Invoice</h3>
                    <div class="card-tools">
                        <a href="<?= site_url('invoices') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= site_url('invoices/print/' . $invoice['id']) ?>" class="btn btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Nomor Invoice</th>
                                    <td>: <?= esc($invoice['invoice_number']) ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>: <?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Jatuh Tempo</th>
                                    <td>: <?= date('d/m/Y', strtotime($invoice['due_date'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: 
                                        <?php
                                        $statusBadge = [
                                            'draft' => 'badge-secondary',
                                            'sent' => 'badge-primary',
                                            'paid' => 'badge-success',
                                            'cancelled' => 'badge-danger'
                                        ];
                                        $statusLabel = [
                                            'draft' => 'Draft',
                                            'sent' => 'Terkirim',
                                            'paid' => 'Lunas',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusBadge[$invoice['status']] ?>">
                                            <?= $statusLabel[$invoice['status']] ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Customer</th>
                                    <td>: <?= esc($invoice['user']['username']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: <?= esc($invoice['user']['email']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th width="150">Jumlah</th>
                                    <th width="200">Harga</th>
                                    <th width="200">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoice['items'] as $item) : ?>
                                    <tr>
                                        <td><?= esc($item['product_name']) ?></td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-right">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td class="text-right">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong>Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if ($invoice['notes']) : ?>
                        <div class="mt-4">
                            <h5>Catatan:</h5>
                            <p><?= nl2br(esc($invoice['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 