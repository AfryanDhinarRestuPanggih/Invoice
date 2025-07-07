<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah User
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'info' ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] !== session()->get('user_id')): ?>
                                <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data user</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 