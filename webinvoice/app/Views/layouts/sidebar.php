<li class="nav-item">
    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= url_is('dashboard*') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="<?= base_url('products') ?>" class="nav-link <?= url_is('products*') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-box"></i>
        <p>Produk</p>
    </a>
</li>

<li class="nav-item">
    <a href="<?= base_url('customers') ?>" class="nav-link <?= url_is('customers*') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-users"></i>
        <p>Pelanggan</p>
    </a>
</li>

<li class="nav-item">
    <a href="<?= base_url('invoices') ?>" class="nav-link <?= url_is('invoices*') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-file-invoice"></i>
        <p>Invoice</p>
    </a>
</li>

<li class="nav-item">
    <a href="<?= base_url('reports') ?>" class="nav-link <?= url_is('reports*') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-chart-bar"></i>
        <p>Laporan</p>
    </a>
</li> 