<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Pelanggan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2>Edit Pelanggan</h2>
        </div>
        <div class="col text-end">
            <a href="<?= base_url('customers') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('customers/' . $customer['id']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                           id="name" name="name" value="<?= old('name', $customer['name']) ?>" required>
                    <?php if (session('errors.name')) : ?>
                        <div class="invalid-feedback"><?= session('errors.name') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                           id="email" name="email" value="<?= old('email', $customer['email']) ?>" required>
                    <?php if (session('errors.email')) : ?>
                        <div class="invalid-feedback"><?= session('errors.email') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">No. Telepon</label>
                    <input type="tel" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" 
                           id="phone" name="phone" value="<?= old('phone', $customer['phone']) ?>">
                    <?php if (session('errors.phone')) : ?>
                        <div class="invalid-feedback"><?= session('errors.phone') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $customer['address']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="company_name" class="form-label">Nama Perusahaan</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" 
                           value="<?= old('company_name', $customer['company_name']) ?>">
                </div>

                <div class="mb-3">
                    <label for="tax_number" class="form-label">NPWP</label>
                    <input type="text" class="form-control" id="tax_number" name="tax_number" 
                           value="<?= old('tax_number', $customer['tax_number']) ?>">
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes', $customer['notes']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" 
                            id="status" name="status" required>
                        <option value="active" <?= old('status', $customer['status']) == 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= old('status', $customer['status']) == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                    <?php if (session('errors.status')) : ?>
                        <div class="invalid-feedback"><?= session('errors.status') ?></div>
                    <?php endif; ?>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 