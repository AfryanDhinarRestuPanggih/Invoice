<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Laporan Invoice<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css">
<style>
@media print {
    .btn-toolbar,
    .sidebar,
    .navbar,
    .card-header,
    .pagination,
    .btn {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
    body {
        padding: 20px !important;
    }
    .container-fluid {
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    canvas {
        max-width: 100% !important;
        height: auto !important;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Laporan Invoice</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('reports') ?>" method="get" id="filterForm" class="row g-3">
                        <div class="col-md-5">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?= $start_date ?>">
                        </div>
                        <div class="col-md-5">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?= $end_date ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Invoice</h6>
                            <h3 class="mb-0"><?= number_format($summary['total_invoices']) ?></h3>
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
                            <h3 class="mb-0">Rp <?= number_format($summary['total_revenue']) ?></h3>
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
                            <h3 class="mb-0">Rp <?= number_format($summary['total_pending']) ?></h3>
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
                            <h3 class="mb-0">Rp <?= number_format($summary['average_amount']) ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pendapatan per Bulan</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Invoice</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nomor Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $invoice): ?>
                            <tr>
                                <td><?= esc($invoice['invoice_number']) ?></td>
                                <td><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></td>
                                <td>
                                    <div><?= esc($invoice['customer_name']) ?></div>
                                    <small class="text-muted"><?= esc($invoice['customer_email']) ?></small>
                                </td>
                                <td class="text-end">Rp <?= number_format($invoice['total_amount'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-<?= get_invoice_status_color($invoice['status']) ?>">
                                        <?= get_invoice_status_label($invoice['status']) ?>
                                    </span>
                                </td>
                                <td><?= esc($invoice['created_by']) ?></td>
                                <td class="text-end">
                                    <a href="<?= base_url('invoices/' . $invoice['id']) ?>" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('invoices/' . $invoice['id'] . '/print') ?>" 
                                       class="btn btn-sm btn-secondary" title="Cetak" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
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
                        Menampilkan <?= $pager->getFirstIndex()+1 ?> - <?= $pager->getLastIndex()+1 ?> 
                        dari <?= $pager->getTotal() ?> transaksi
                    </div>
                    <?= $pager->links('default', 'bootstrap_pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($monthlyRevenue, 'month')) ?>,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: <?= json_encode(array_column($monthlyRevenue, 'revenue')) ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgb(40, 167, 69)',
                    borderWidth: 1
                },
                {
                    label: 'Belum Dibayar',
                    data: <?= json_encode(array_column($monthlyRevenue, 'pending')) ?>,
                    backgroundColor: 'rgba(255, 193, 7, 0.5)',
                    borderColor: 'rgb(255, 193, 7)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + 
                                new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_map(function($item) {
                return get_invoice_status_label($item['status']);
            }, $statusCount)) ?>,
            datasets: [{
                data: <?= json_encode(array_column($statusCount, 'total')) ?>,
                backgroundColor: [
                    'rgba(108, 117, 125, 0.8)',  // Draft
                    'rgba(0, 123, 255, 0.8)',    // Sent
                    'rgba(40, 167, 69, 0.8)',    // Paid
                    'rgba(220, 53, 69, 0.8)'     // Cancelled
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<style>
@media print {
    .btn-toolbar,
    .card-header,
    .pagination,
    .btn {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 