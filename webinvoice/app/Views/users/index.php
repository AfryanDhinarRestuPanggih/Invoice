<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Data User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">User</li>
    </ol>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Daftar User
                </div>
                <a href="<?= base_url('users/new') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <form action="" method="get" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari user..." name="search" value="<?= $search ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Login Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1 + (10 * ($pager->getCurrentPage() - 1)); ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <?php if ($user['role'] === 'admin') : ?>
                                        <span class="badge bg-primary">Admin</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">User</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['status'] === 'active') : ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : '-' ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('users/' . $user['id']) ?>" class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('users/' . $user['id'] . '/edit') ?>" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['id'] !== session()->get('id')) : ?>
                                            <form action="<?= base_url('users/' . $user['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($users)) : ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data user</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <?= $pager->links('default', 'bootstrap_pagination') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 