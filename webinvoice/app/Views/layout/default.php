<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Web Invoice</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url() ?>">Web Invoice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url() ?>">Beranda</a>
                    </li>
                    <?php if (session()->get('logged_in')) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('invoices') ?>">Invoice</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('products') ?>">Produk</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (!session()->get('logged_in')) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('login') ?>">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3" href="<?= site_url('register') ?>">Daftar</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?= session()->get('user_name') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= site_url('profile') ?>">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Keluar</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Web Invoice</h5>
                    <p>Solusi manajemen invoice online untuk bisnis Anda.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= site_url() ?>" class="text-decoration-none">Beranda</a></li>
                        <li><a href="#" class="text-decoration-none">Tentang Kami</a></li>
                        <li><a href="#" class="text-decoration-none">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-decoration-none">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> support@webinvoice.com</li>
                        <li><i class="fas fa-phone me-2"></i> (021) 1234-5678</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Contoh No. 123, Jakarta</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Web Invoice. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 