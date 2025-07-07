<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Notifikasi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Notifikasi</h1>
        <?php if (!empty($notifications)): ?>
            <button type="button" class="btn btn-secondary" onclick="markAllAsRead()">
                <i class="fas fa-check-double"></i> Tandai Semua Sudah Dibaca
            </button>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php if (empty($notifications)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada notifikasi
                </div>
            <?php else: ?>
                <div class="list-group notification-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="list-group-item list-group-item-action <?= is_null($notification['read_at']) ? 'unread' : '' ?>"
                             id="notification-<?= $notification['id'] ?>">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h6 class="mb-1">
                                    <?php if (is_null($notification['read_at'])): ?>
                                        <span class="badge bg-<?= $notification['type'] ?> me-2">Baru</span>
                                    <?php endif; ?>
                                    <?= esc($notification['title']) ?>
                                </h6>
                                <small class="text-muted">
                                    <?= time_ago($notification['created_at']) ?>
                                </small>
                            </div>
                            <p class="mb-1"><?= esc($notification['message']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($notification['link']): ?>
                                    <a href="<?= $notification['link'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt"></i> Lihat Detail
                                    </a>
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>
                                <?php if (is_null($notification['read_at'])): ?>
                                    <button type="button" class="btn btn-sm btn-light" 
                                            onclick="markAsRead(<?= $notification['id'] ?>)">
                                        <i class="fas fa-check"></i> Tandai Sudah Dibaca
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.notification-list .unread {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}
.notification-list .list-group-item {
    transition: background-color 0.3s;
}
.notification-list .list-group-item:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function markAsRead(id) {
    fetch(`<?= base_url('notifications/mark-read') ?>/${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.getElementById(`notification-${id}`);
            notification.classList.remove('unread');
            notification.querySelector('.badge')?.remove();
            notification.querySelector('.btn-light')?.remove();
            updateNotificationCount(data.unread_count);
        }
    });
}

function markAllAsRead() {
    fetch('<?= base_url('notifications/mark-read') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-list .unread').forEach(el => {
                el.classList.remove('unread');
                el.querySelector('.badge')?.remove();
                el.querySelector('.btn-light')?.remove();
            });
            updateNotificationCount(0);
        }
    });
}

function updateNotificationCount(count) {
    const badge = document.querySelector('#notification-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}
</script>
<?= $this->endSection() ?> 