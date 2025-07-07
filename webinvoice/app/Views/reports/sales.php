<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <form action="" method="get" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label">Periode</label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" name="start_date" value="<?= $startDate ?>">
                </div>
                <div class="col-auto">
                    <label class="col-form-label">s/d</label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" name="end_date" value="<?= $endDate ?>">
                </div>
                <div class="col-auto">
                    <label class="col-form-label">Status</label>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="draft" <?= $status == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="sent" <?= $status == 'sent' ? 'selected' : '' ?>>Terkirim</option>
                        <option value="paid" <?= $status == 'paid' ? 'selected' : '' ?>>Lunas</option>
                        <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-auto ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('reports/sales/excel') ?>?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&status=<?= $status ?>">
                                    <i class="fas fa-file-excel me-2"></i> Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('reports/sales/pdf') ?>?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&status=<?= $status ?>">
                                    <i class="fas fa-file-pdf me-2"></i> PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Total Invoice</div>
                                    <div class="text-lg fw-bold">
                                        Rp <?= number_format($totalAmount, 0, ',', '.') ?>
                                    </div>
                                </div>
                                <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Total Lunas</div>
                                    <div class="text-lg fw-bold">
                                        Rp <?= number_format($totalPaid, 0, ',', '.') ?>
                                    </div>
                                </div>
                                <i class="fas fa-check-circle fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Total Belum Lunas</div>
                                    <div class="text-lg fw-bold">
                                        Rp <?= number_format($totalUnpaid, 0, ',', '.') ?>
                                    </div>
                                </div>
                                <i class="fas fa-clock fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="salesTable">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Pajak</th>
                            <th class="text-end">Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?= esc($invoice['invoice_number']) ?></td>
                            <td><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                            <td><?= esc($invoice['customer_name']) ?></td>
                            <td>
                                <?php 
                                $status = model('InvoiceModel')->getStatusLabel($invoice['status']);
                                ?>
                                <span class="badge bg-<?= $status['class'] ?>"><?= $status['text'] ?></span>
                            </td>
                            <td class="text-end">
                                <?= number_format($invoice['subtotal'], 0, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                <?= number_format($invoice['tax_amount'], 0, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                <?= number_format($invoice['total_amount'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <a href="<?= base_url('invoices/' . $invoice['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new simpleDatatables.DataTable("#salesTable", {
        searchable: true,
        fixedHeight: true,
        perPage: 10
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 