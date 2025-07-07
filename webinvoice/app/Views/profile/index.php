<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>Profil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=random" 
                         alt="<?= $user['name'] ?>" 
                         class="rounded-circle mb-3" 
                         style="width: 128px; height: 128px;">
                    <h4 class="card-title"><?= esc($user['name']) ?></h4>
                    <p class="text-muted"><?= esc($user['email']) ?></p>
                    <p class="text-muted">Bergabung sejak <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab">
                        <i class="fas fa-user me-2"></i>Edit Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </a>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Edit Profile Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Edit Profil</h5>
                            
                            <?php if (session()->has('success')) : ?>
                                <div class="alert alert-success">
                                    <?= session('success') ?>
                                </div>
                            <?php endif ?>
                            
                            <?php if (session()->has('errors')) : ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach (session('errors') as $error) : ?>
                                            <li><?= $error ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif ?>
                            
                            <form action="<?= site_url('profile/update') ?>" method="post">
                                <?= csrf_field() ?>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= old('name', $user['name']) ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= old('email', $user['email']) ?>" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Ubah Password</h5>
                            
                            <?php if (session()->has('error')) : ?>
                                <div class="alert alert-danger">
                                    <?= session('error') ?>
                                </div>
                            <?php endif ?>
                            
                            <form action="<?= site_url('profile/changePassword') ?>" method="post">
                                <?= csrf_field() ?>
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password_confirm" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="new_password_confirm" 
                                           name="new_password_confirm" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 