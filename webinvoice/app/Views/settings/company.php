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
            <i class="fas fa-building me-1"></i>
            Pengaturan Perusahaan
        </div>
        <div class="card-body">
            <form action="<?= base_url('settings/company') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <label for="company_name" class="col-sm-2 col-form-label">Nama Perusahaan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('company_name')) ? 'is-invalid' : '' ?>" 
                               id="company_name" name="company_name" 
                               value="<?= old('company_name', $settings['company_name'] ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('company_name') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="company_address" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea class="form-control <?= ($validation->hasError('company_address')) ? 'is-invalid' : '' ?>" 
                                  id="company_address" name="company_address" rows="3"><?= old('company_address', $settings['company_address'] ?? '') ?></textarea>
                        <div class="invalid-feedback">
                            <?= $validation->getError('company_address') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="company_phone" class="col-sm-2 col-form-label">No. Telepon</label>
                    <div class="col-sm-10">
                        <input type="tel" class="form-control <?= ($validation->hasError('company_phone')) ? 'is-invalid' : '' ?>" 
                               id="company_phone" name="company_phone" 
                               value="<?= old('company_phone', $settings['company_phone'] ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('company_phone') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="company_email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control <?= ($validation->hasError('company_email')) ? 'is-invalid' : '' ?>" 
                               id="company_email" name="company_email" 
                               value="<?= old('company_email', $settings['company_email'] ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('company_email') ?>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-3">
                    <label for="tax_percentage" class="col-sm-2 col-form-label">Persentase Pajak (%)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control <?= ($validation->hasError('tax_percentage')) ? 'is-invalid' : '' ?>" 
                               id="tax_percentage" name="tax_percentage" min="0" max="100" step="0.1"
                               value="<?= old('tax_percentage', $settings['tax_percentage'] ?? '10') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('tax_percentage') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="invoice_prefix" class="col-sm-2 col-form-label">Prefix No. Invoice</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('invoice_prefix')) ? 'is-invalid' : '' ?>" 
                               id="invoice_prefix" name="invoice_prefix" 
                               value="<?= old('invoice_prefix', $settings['invoice_prefix'] ?? 'INV') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('invoice_prefix') ?>
                        </div>
                        <div class="form-text">
                            Contoh: INV-2024-0001
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="invoice_due_days" class="col-sm-2 col-form-label">Jatuh Tempo (Hari)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control <?= ($validation->hasError('invoice_due_days')) ? 'is-invalid' : '' ?>" 
                               id="invoice_due_days" name="invoice_due_days" min="1" max="365"
                               value="<?= old('invoice_due_days', $settings['invoice_due_days'] ?? '30') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('invoice_due_days') ?>
                        </div>
                        <div class="form-text">
                            Jumlah hari default untuk jatuh tempo invoice
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 