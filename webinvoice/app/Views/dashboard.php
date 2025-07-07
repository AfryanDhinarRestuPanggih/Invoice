<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="<?= base_url('invoices/new') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Buat Invoice
                </a>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Invoices Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Invoice</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_invoices) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= format_currency($total_revenue) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Invoices Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Invoice Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($pending_invoices) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Monthly Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pendapatan Bulanan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Status Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Status Invoice</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="invoiceStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Invoice Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_invoices)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada invoice</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_invoices as $invoice): ?>
                                <tr>
                                    <td><?= esc($invoice['number']) ?></td>
                                    <td><?= esc($invoice['customer_name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($invoice['date'])) ?></td>
                                    <td class="text-end"><?= format_currency($invoice['total']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= get_invoice_status_color($invoice['status']) ?>">
                                            <?= get_invoice_status_label($invoice['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('invoices/' . $invoice['id']) ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart');
    new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Pendapatan',
                data: <?= json_encode(array_values($monthly_revenue)) ?>,
                lineTension: 0.3,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Invoice Status Chart
    const invoiceStatusCtx = document.getElementById('invoiceStatusChart');
    new Chart(invoiceStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'Terkirim', 'Dibayar', 'Batal'],
            datasets: [{
                data: <?= json_encode(array_values($invoice_status)) ?>,
                backgroundColor: ['#f8f9fa', '#36b9cc', '#1cc88a', '#e74a3b'],
                hoverBackgroundColor: ['#e9ecef', '#2c9faf', '#17a673', '#be2617'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '80%'
        }
    });
});
</script>
<?= $this->endSection() ?> 