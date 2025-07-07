<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Detail Invoice<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Header with Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Detail Invoice</h1>
        <div class="btn-group">
            <?php if (session('role') === 'admin'): ?>
                <?php if ($invoice['status'] === 'draft'): ?>
                    <a href="<?= base_url('invoices/' . $invoice['id'] . '/edit') ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="<?= base_url('invoices/' . $invoice['id'] . '/send') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-info ms-2" onclick="return confirm('Kirim invoice ini ke pelanggan?')">
                            <i class="fas fa-paper-plane"></i> Kirim
                        </button>
                    </form>
                <?php elseif (in_array($invoice['status'], ['sent', 'overdue'])): ?>
                    <form action="<?= base_url('invoices/' . $invoice['id'] . '/pay') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Tandai invoice ini sebagai sudah dibayar?')">
                            <i class="fas fa-check"></i> Tandai Sudah Dibayar
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (!in_array($invoice['status'], ['paid', 'cancelled'])): ?>
                    <form action="<?= base_url('invoices/' . $invoice['id'] . '/cancel') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger ms-2" onclick="return confirm('Batalkan invoice ini?')">
                            <i class="fas fa-times"></i> Batalkan
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

            <a href="<?= base_url('invoices/' . $invoice['id'] . '/print') ?>" class="btn btn-secondary ms-2" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Invoice Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Company and Customer Info -->
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="mb-3">Dari:</h6>
                            <div><strong><?= company_name() ?></strong></div>
                            <div><?= company_address() ?></div>
                            <div>Email: <?= company_email() ?></div>
                            <div>Phone: <?= company_phone() ?></div>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="mb-3">Kepada:</h6>
                            <div><strong><?= esc($invoice['customer_name']) ?></strong></div>
                            <div><?= nl2br(esc($invoice['customer_address'])) ?></div>
                            <div>Email: <?= esc($invoice['customer_email']) ?></div>
                            <div>Phone: <?= esc($invoice['customer_phone']) ?></div>
                        </div>
                    </div>

                    <!-- Invoice Details -->
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <div><strong>Nomor Invoice:</strong> <?= $invoice['invoice_number'] ?></div>
                            <div><strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($invoice['created_at'])) ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div><strong>Status:</strong> 
                                <span class="badge bg-<?= get_invoice_status_color($invoice['status']) ?>">
                                    <?= get_invoice_status_label($invoice['status']) ?>
                                </span>
                            </div>
                            <div><strong>Jatuh Tempo:</strong> <?= date('d/m/Y', strtotime($invoice['due_date'])) ?></div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if (!empty($invoice['notes'])): ?>
                    <div class="mt-4">
                        <h6>Catatan:</h6>
                        <p class="mb-0"><?= nl2br(esc($invoice['notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline Status</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Invoice Dibuat</h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($invoice['created_at'])) ?></small>
                            </div>
                        </div>

                        <?php if (isset($invoice['sent_at']) && $invoice['sent_at']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Invoice Dikirim</h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($invoice['sent_at'])) ?></small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($invoice['paid_at']) && $invoice['paid_at']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pembayaran Diterima</h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($invoice['paid_at'])) ?></small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($invoice['cancelled_at']) && $invoice['cancelled_at']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Invoice Dibatalkan</h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($invoice['cancelled_at'])) ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <?php if (in_array($invoice['status'], ['sent', 'overdue'])): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading">Total yang harus dibayar:</h6>
                        <h3 class="mb-0">Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></h3>
                    </div>

                    <h6>Metode Pembayaran:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Bank Transfer:</strong><br>
                            <?= setting('bank_name', 'payment') ?><br>
                            No. Rek: <?= setting('bank_account', 'payment') ?><br>
                            a.n <?= setting('bank_account_name', 'payment') ?>
                        </li>
                    </ul>

                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="fas fa-info-circle"></i> Harap cantumkan nomor invoice 
                            <strong><?= $invoice['invoice_number'] ?></strong> pada keterangan transfer.
                        </small>
                    </div>

                    <?php if (session('role') === 'admin'): ?>
                    <div class="mt-3">
                        <form action="<?= base_url('invoices/' . $invoice['id'] . '/pay') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success btn-block w-100" onclick="return confirm('Tandai invoice ini sebagai sudah dibayar?')">
                                <i class="fas fa-check"></i> Tandai Sudah Dibayar
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 25px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
}

.btn-block {
    display: block;
    width: 100%;
}
</style>
<?= $this->endSection() ?> 