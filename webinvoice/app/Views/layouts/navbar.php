<ul class="navbar-nav ms-auto">
    <!-- Notifications -->
    <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="notificationsDropdown" role="button" 
           data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            <span class="badge bg-danger" id="notification-badge" style="display: none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationsDropdown" 
             style="width: 300px; max-height: 400px; overflow-y: auto;">
            <div class="list-group list-group-flush" id="notifications-list">
                <div class="list-group-item text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="dropdown-divider m-0"></div>
            <a class="dropdown-item text-center py-2" href="<?= base_url('notifications') ?>">
                Lihat Semua Notifikasi
            </a>
        </div>
    </li>

    <!-- User Menu -->
    <li class="nav-item dropdown">
        // ... existing code ...
    </li>

    <?php if (in_groups('admin')): ?>
    <li class="nav-item">
        <a class="nav-link <?= url_is('settings*') ? 'active' : '' ?>" href="<?= base_url('settings') ?>">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
    </li>
    <?php endif; ?>

    <li class="nav-item">
        <a class="nav-link <?= url_is('products*') ? 'active' : '' ?>" href="<?= base_url('products') ?>">
            <i class="fas fa-box"></i> Produk
        </a>
    </li>
</ul>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationDropdown = document.getElementById('notificationsDropdown');
    const notificationsList = document.getElementById('notifications-list');
    let isLoading = false;

    // Load notifications when dropdown is opened
    notificationDropdown.addEventListener('show.bs.dropdown', function() {
        if (!isLoading) {
            loadNotifications();
        }
    });

    // Load notifications
    function loadNotifications() {
        isLoading = true;
        fetch('<?= base_url('notifications') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderNotifications(data.data.notifications);
                updateNotificationCount(data.data.unread_count);
            }
        })
        .finally(() => {
            isLoading = false;
        });
    }

    // Render notifications in dropdown
    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            notificationsList.innerHTML = `
                <div class="list-group-item text-center py-3">
                    <i class="fas fa-bell-slash text-muted"></i>
                    <p class="mb-0 text-muted">Tidak ada notifikasi</p>
                </div>
            `;
            return;
        }

        notificationsList.innerHTML = notifications.slice(0, 5).map(notification => `
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h6 class="mb-1">
                        ${notification.read_at === null ? 
                            `<span class="badge bg-${notification.type} me-2">Baru</span>` : ''}
                        ${notification.title}
                    </h6>
                    <small class="text-muted">${timeAgo(notification.created_at)}</small>
                </div>
                <p class="mb-1 text-truncate">${notification.message}</p>
                ${notification.link ? 
                    `<a href="${notification.link}" class="btn btn-sm btn-primary">
                        <i class="fas fa-external-link-alt"></i> Lihat Detail
                    </a>` : ''}
            </div>
        `).join('');
    }

    // Check for new notifications every minute
    function checkNotifications() {
        fetch('<?= base_url('notifications/count') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount(data.unread_count);
            }
        });
    }

    // Update notification badge
    function updateNotificationCount(count) {
        const badge = document.getElementById('notification-badge');
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }

    // Time ago function
    function timeAgo(datetime) {
        const time = new Date(datetime).getTime();
        const now = new Date().getTime();
        const diff = now - time;

        if (diff < 60000) {
            return 'Baru saja';
        } else if (diff < 3600000) {
            return Math.floor(diff / 60000) + ' menit yang lalu';
        } else if (diff < 86400000) {
            return Math.floor(diff / 3600000) + ' jam yang lalu';
        } else if (diff < 604800000) {
            return Math.floor(diff / 86400000) + ' hari yang lalu';
        } else {
            return new Date(time).toLocaleDateString('id-ID');
        }
    }

    // Initial check
    checkNotifications();

    // Check every minute
    setInterval(checkNotifications, 60000);
});
</script>
<?= $this->endSection() ?> 