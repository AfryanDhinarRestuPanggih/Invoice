<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('invoices/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Invoice
            </a>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted">TOTAL INVOICE</h6>
                            <h2 class="card-title mb-0"><?= $total_invoices ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-file-invoice fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                TOTAL PENDAPATAN</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_revenue, 0, ',', '.') ?></div>
                            <small class="text-muted">(Dari invoice yang sudah dibayar)</small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted">INVOICE PENDING</h6>
                            <h2 class="card-title mb-0"><?= $pending_invoices ?></h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Pendapatan Bulanan -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pendapatan Bulanan</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Invoice -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Invoice</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Grafik Pendapatan Bulanan
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($monthly_revenue['months']) ?>,
        datasets: [{
            label: 'Pendapatan',
            data: <?= json_encode($monthly_revenue['revenues']) ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                    }
                }
            }
        }
    }
});

// Grafik Status Invoice
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Draft', 'Terkirim', 'Dibayar', 'Batal'],
        datasets: [{
            data: [
                <?= $invoice_status['draft'] ?>,
                <?= $invoice_status['sent'] ?>,
                <?= $invoice_status['paid'] ?>,
                <?= $invoice_status['cancelled'] ?>
            ],
            backgroundColor: [
                '#6c757d',  // Draft - Abu-abu
                '#0d6efd',  // Terkirim - Biru
                '#198754',  // Dibayar - Hijau
                '#dc3545'   // Batal - Merah
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
<?= $this->endSection() ?> 