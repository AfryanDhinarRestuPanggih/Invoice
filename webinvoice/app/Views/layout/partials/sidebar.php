<div class="sb-sidenav-menu-heading">Menu</div>

<a class="nav-link <?= url_is('dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
    Dashboard
</a>

<?php if (session()->get('role') === 'admin') : ?>
    <div class="sb-sidenav-menu-heading">Master Data</div>
    
    <a class="nav-link <?= url_is('products*') ? 'active' : '' ?>" href="<?= base_url('products') ?>">
        <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
        Produk
    </a>

    <a class="nav-link <?= url_is('customers*') ? 'active' : '' ?>" href="<?= base_url('customers') ?>">
        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
        Customer
    </a>

    <div class="sb-sidenav-menu-heading">Transaksi</div>
<?php endif; ?>

<a class="nav-link <?= url_is('invoices*') ? 'active' : '' ?>" href="<?= base_url('invoices') ?>">
    <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i></div>
    Invoice
</a>

<?php if (session()->get('role') === 'admin') : ?>
    <div class="sb-sidenav-menu-heading">Admin</div>
    
    <a class="nav-link <?= url_is('users*') ? 'active' : '' ?>" href="<?= base_url('users') ?>">
        <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
        Manajemen User
    </a>
<?php endif; ?> 