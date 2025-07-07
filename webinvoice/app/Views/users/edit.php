<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/users">User</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-edit me-1"></i>
            Form Edit User
        </div>
        <div class="card-body">
            <form action="<?= base_url('users/' . $user['id']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="row mb-3">
                    <label for="name" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= old('name', $user['name']) ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.name') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.email') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                               id="password" name="password">
                        <div class="invalid-feedback">
                            <?= session('errors.password') ?>
                        </div>
                        <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="role" class="col-sm-2 col-form-label">Role</label>
                    <div class="col-sm-10">
                        <select class="form-select <?= session('errors.role') ? 'is-invalid' : '' ?>" 
                                id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= old('role', $user['role']) === 'user' ? 'selected' : '' ?>>User</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.role') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <select class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" 
                                id="status" name="status" required>
                            <option value="active" <?= old('status', $user['status']) === 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= old('status', $user['status']) === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.status') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('users') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 