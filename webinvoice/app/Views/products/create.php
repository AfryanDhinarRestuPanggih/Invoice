<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tambah Produk<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Tambah Produk</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('products') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('products') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Produk</label>
                            <input type="text" class="form-control <?= session('errors.code') ? 'is-invalid' : '' ?>" 
                                   id="code" name="code" value="<?= old('code', $code) ?>" readonly>
                            <?php if (session('errors.code')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.code') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" value="<?= old('name') ?>" required>
                            <?php if (session('errors.name')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" 
                                      id="description" name="description" rows="3"><?= old('description') ?></textarea>
                            <?php if (session('errors.description')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.description') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control <?= session('errors.price') ? 'is-invalid' : '' ?>" 
                                       id="price" name="price" value="<?= old('price') ?>" min="0" required>
                                <?php if (session('errors.price')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.price') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" class="form-control <?= session('errors.stock') ? 'is-invalid' : '' ?>" 
                                   id="stock" name="stock" value="<?= old('stock') ?>" min="0" required>
                            <?php if (session('errors.stock')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.stock') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 