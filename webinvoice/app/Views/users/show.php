<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/users">User</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user me-1"></i>
                    Detail User
                </div>
                <div>
                    <a href="<?= base_url('users/' . $user['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php if ($user['id'] !== session()->get('id')) : ?>
                        <form action="<?= base_url('users/' . $user['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Nama</th>
                        <td><?= esc($user['name']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= esc($user['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <?php if ($user['role'] === 'admin') : ?>
                                <span class="badge bg-primary">Admin</span>
                            <?php else : ?>
                                <span class="badge bg-secondary">User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($user['status'] === 'active') : ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Login Terakhir</th>
                        <td><?= $user['last_login'] ? date('d/m/Y H:i:s', strtotime($user['last_login'])) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat pada</th>
                        <td><?= $user['created_at'] ? date('d/m/Y H:i:s', strtotime($user['created_at'])) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Diperbarui pada</th>
                        <td><?= $user['updated_at'] ? date('d/m/Y H:i:s', strtotime($user['updated_at'])) : '-' ?></td>
                    </tr>
                </table>
            </div>

            <div class="mt-3">
                <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 