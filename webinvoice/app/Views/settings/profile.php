<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    
    <?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-edit me-1"></i>
            Edit Profil
        </div>
        <div class="card-body">
            <form action="<?= base_url('settings/profile') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <label for="name" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('name')) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= old('name', $user['name']) ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('name') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= old('email', $user['email']) ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('email') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="phone" class="col-sm-2 col-form-label">No. Telepon</label>
                    <div class="col-sm-10">
                        <input type="tel" class="form-control <?= ($validation->hasError('phone')) ? 'is-invalid' : '' ?>" 
                               id="phone" name="phone" value="<?= old('phone', $user['phone']) ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('phone') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="address" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea class="form-control <?= ($validation->hasError('address')) ? 'is-invalid' : '' ?>" 
                                  id="address" name="address" rows="3"><?= old('address', $user['address']) ?></textarea>
                        <div class="invalid-feedback">
                            <?= $validation->getError('address') ?>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-3">
                    <label for="password" class="col-sm-2 col-form-label">Password Baru</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                               id="password" name="password">
                        <div class="invalid-feedback">
                            <?= $validation->getError('password') ?>
                        </div>
                        <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="password_confirm" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control <?= ($validation->hasError('password_confirm')) ? 'is-invalid' : '' ?>" 
                               id="password_confirm" name="password_confirm">
                        <div class="invalid-feedback">
                            <?= $validation->getError('password_confirm') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 