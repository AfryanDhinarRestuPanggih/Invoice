<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= isset($product['id']) ? 'Edit' : 'Tambah' ?> Produk<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= isset($product['id']) ? 'Edit' : 'Tambah' ?> Produk</h3>
                </div>
                <div class="card-body">
                    <form action="<?= isset($product['id']) ? base_url('products/update/' . $product['id']) : base_url('products/create') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Produk</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="code" 
                                   name="code" 
                                   value="<?= old('code', $product['code']) ?>" 
                                   required>
                            <?php if (isset($validation) && $validation->hasError('code')) : ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('code') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="<?= old('name', $product['name']) ?>" 
                                   required>
                            <?php if (isset($validation) && $validation->hasError('name')) : ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"><?= old('description', $product['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" 
                                       class="form-control price-input" 
                                       id="price" 
                                       name="price" 
                                       value="<?= old('price', number_format((float)$product['price'], 2, ',', '.')) ?>"
                                       required>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('price')) : ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('price') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="unit" class="form-label">Satuan</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="unit" 
                                   name="unit" 
                                   value="<?= old('unit', $product['unit']) ?>" 
                                   required>
                            <?php if (isset($validation) && $validation->hasError('unit')) : ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('unit') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="stock" 
                                   name="stock" 
                                   value="<?= old('stock', $product['stock']) ?>" 
                                   min="0">
                            <?php if (isset($validation) && $validation->hasError('stock')) : ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('stock') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       <?= old('is_active', $product['is_active']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('products') ?>" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
<script>
    // Initialize AutoNumeric for price input
    new AutoNumeric('#price', {
        currencySymbol: 'Rp ',
        decimalCharacter: ',',
        digitGroupSeparator: '.',
        decimalPlaces: 0,
        minimumValue: '0'
    });

    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const priceInput = document.querySelector('#price');
        // Get the unformatted value
        const unformattedPrice = AutoNumeric.getNumber(priceInput);
        // Update the input value
        priceInput.value = unformattedPrice;
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 