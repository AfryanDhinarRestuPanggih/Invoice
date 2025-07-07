<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $title ?> - Web Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="<?= base_url('css/styles.css') ?>" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?= base_url() ?>">Web Invoice</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger notification-badge" style="display: none;"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="navbarDropdown" style="width: 300px;">
                    <li>
                        <h6 class="dropdown-header">Notifikasi Terbaru</h6>
                    </li>
                    <div class="notification-list">
                        <li class="text-center p-2 text-muted">
                            <small>Memuat notifikasi...</small>
                        </li>
                    </div>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <a class="dropdown-item text-center" href="<?= base_url('notifications') ?>">
                            Lihat Semua Notifikasi
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?= base_url('settings/profile') ?>">Profil</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('settings/company') ?>">Perusahaan</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Utama</div>
                        <a class="nav-link" href="<?= base_url() ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Transaksi</div>
                        <a class="nav-link" href="<?= base_url('invoices') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                            Invoice
                        </a>
                        <a class="nav-link" href="<?= base_url('products') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Produk
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Laporan</div>
                        <a class="nav-link" href="<?= base_url('reports/sales') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Laporan Penjualan
                        </a>

                        <!-- Settings Menu -->
                        <div class="sb-sidenav-menu-heading">Pengaturan</div>
                        <a class="nav-link" href="<?= base_url('settings/profile') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Profil
                        </a>
                        <a class="nav-link" href="<?= base_url('settings/company') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                            Perusahaan
                        </a>
                        <a class="nav-link" href="<?= base_url('backup') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                            Backup Database
                        </a>

                        <!-- Master Data Menu -->
                        <div class="sb-sidenav-menu-heading">Master Data</div>
                        <a class="nav-link" href="<?= base_url('products') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Produk
                        </a>
                        <a class="nav-link" href="<?= base_url('customers') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Pelanggan
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login sebagai:</div>
                    <?= session()->get('name') ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <?= $this->renderSection('content') ?>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Web Invoice 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="<?= base_url('js/scripts.js') ?>"></script>
    
    <!-- Notification Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const badge = document.querySelector('.notification-badge');
        const list = document.querySelector('.notification-list');
        
        function updateNotifications() {
            // Get unread count
            fetch('<?= base_url('notifications/unread-count') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                });
            
            // Get latest notifications
            fetch('<?= base_url('notifications/latest') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(notification => `
                            <li class="dropdown-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="fas fa-${notification.type == 'warning' ? 'exclamation-triangle' : 
                                                        (notification.type == 'success' ? 'check-circle' : 
                                                        (notification.type == 'danger' ? 'times-circle' : 'info-circle'))} me-2"></i>
                                        ${notification.title}
                                    </h6>
                                    <small class="text-muted">${new Date(notification.created_at).toLocaleDateString('id-ID')}</small>
                                </div>
                                <p class="mb-1 small">${notification.message}</p>
                                ${notification.reference_type == 'invoice' ? 
                                    `<a href="<?= base_url('invoices/') ?>${notification.reference_id}" class="btn btn-sm btn-link px-0">Lihat Invoice</a>` : 
                                    ''}
                            </li>
                        `).join('');
                    } else {
                        list.innerHTML = `
                            <li class="text-center p-2 text-muted">
                                <small>Tidak ada notifikasi</small>
                            </li>
                        `;
                    }
                });
        }
        
        // Update notifications every 30 seconds
        updateNotifications();
        setInterval(updateNotifications, 30000);
    });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html> 