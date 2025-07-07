<div class="sidebar">
    <div class="logo">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
        <h3>Web Invoice</h3>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        <!-- Menu untuk semua user -->
        <li class="nav-item">
            <a href="<?= base_url('invoices') ?>" class="nav-link">
                <i class="fas fa-file-invoice"></i> Invoice
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('customers') ?>" class="nav-link">
                <i class="fas fa-users"></i> Customers
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('products') ?>" class="nav-link">
                <i class="fas fa-box"></i> Products
            </a>
        </li>

        <!-- Menu khusus admin -->
        <?php if (session('role') === 'admin'): ?>
            <li class="nav-item">
                <a href="<?= base_url('admin/users') ?>" class="nav-link">
                    <i class="fas fa-user-cog"></i> User Management
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/backup') ?>" class="nav-link">
                    <i class="fas fa-database"></i> Backup/Restore
                </a>
            </li>
        <?php endif; ?>

        <!-- Menu untuk semua user -->
        <li class="nav-item">
            <a href="<?= base_url('reports') ?>" class="nav-link">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('settings') ?>" class="nav-link">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('notifications') ?>" class="nav-link">
                <i class="fas fa-bell"></i> Notifications
                <?php if (isset($unread_notifications) && $unread_notifications > 0): ?>
                    <span class="badge badge-danger"><?= $unread_notifications ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('logout') ?>" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div> 