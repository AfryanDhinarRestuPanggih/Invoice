<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Daftar Invoice<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Invoice</h1>
        <?php if (session()->get('role') === 'admin'): ?>
        <a href="<?= base_url('invoices/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Invoice
        </a>
        <?php endif; ?>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Cari</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Nomor invoice / nama pelanggan" 
                               value="<?= $search ?? '' ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="sent" <?= ($status ?? '') === 'sent' ? 'selected' : '' ?>>Terkirim</option>
                            <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
                            <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="<?= $start_date ?? '' ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="<?= $end_date ?? '' ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="<?= base_url('invoices') ?>" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Invoice</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_invoices']) ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Pendapatan</h6>
                            <h3 class="mb-0">Rp <?= number_format($stats['total_income']) ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Belum Dibayar</h6>
                            <h3 class="mb-0">Rp <?= number_format($stats['total_unpaid']) ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Rata-rata Invoice</h6>
                            <h3 class="mb-0">Rp <?= number_format($stats['average_amount']) ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Invoice -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($invoices)): ?>
                <div class="text-center py-5">
                    <img src="<?= base_url('assets/images/empty.svg') ?>" alt="No Data" class="mb-3" width="200">
                    <h4>Tidak ada invoice</h4>
                    <p class="text-muted">Belum ada invoice yang dibuat atau tidak ada yang sesuai dengan filter.</p>
                    <a href="<?= base_url('invoices/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Invoice Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nomor Invoice</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('invoices/' . $invoice['id']) ?>" class="text-decoration-none">
                                            <?= esc($invoice['invoice_number']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div><?= esc($invoice['customer_name']) ?></div>
                                        <small class="text-muted"><?= esc($invoice['customer_email']) ?></small>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                                    <td>
                                        <?php
                                        $due_date = strtotime($invoice['due_date']);
                                        $now = time();
                                        $is_overdue = $due_date < $now && $invoice['status'] === 'sent';
                                        ?>
                                        <span class="<?= $is_overdue ? 'text-danger' : '' ?>">
                                            <?= date('d/m/Y', $due_date) ?>
                                            <?php if ($is_overdue): ?>
                                                <i class="fas fa-exclamation-circle" title="Telah melewati jatuh tempo"></i>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="text-end">Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-<?= get_invoice_status_color($invoice['status']) ?>">
                                            <?= get_invoice_status_label($invoice['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?= base_url('invoices/' . $invoice['id']) ?>" 
                                               class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($invoice['status'] === 'draft'): ?>
                                                <a href="<?= base_url('invoices/' . $invoice['id'] . '/edit') ?>" 
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('invoices/' . $invoice['id'] . '/print') ?>" 
                                               class="btn btn-sm btn-secondary" title="Cetak"
                                               target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($pager): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            Menampilkan <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> - 
                            <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
                            dari <?= $pager->getTotal() ?> invoice
                        </div>
                        <?= $pager->links('default', 'bootstrap_pagination') ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus invoice ini?</p>
                <p class="text-danger"><small>Hanya invoice dengan status draft yang dapat dihapus.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteButton" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteButton').href = `<?= base_url('invoices/delete') ?>/${id}`;
    modal.show();
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 