<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Produk<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Produk</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('products/new') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    <?= view('shared/messages') ?>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="<?= base_url('products') ?>" method="get" class="form-inline">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   value="<?= $keyword ?>" placeholder="Cari produk...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <?php if ($keyword): ?>
                                <a href="<?= base_url('products') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data produk.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= esc($product['code']) ?></td>
                                    <td><?= esc($product['name']) ?></td>
                                    <td><?= esc($product['description']) ?></td>
                                    <td class="text-end"><?= format_currency($product['price']) ?></td>
                                    <td><?= esc($product['unit']) ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status" type="checkbox" 
                                                   data-id="<?= $product['id'] ?>"
                                                   <?= $product['is_active'] ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('products/' . $product['id'] . '/edit') ?>" 
                                               class="btn btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger delete-product" 
                                                    data-id="<?= $product['id'] ?>"
                                                    data-name="<?= esc($product['name']) ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="productName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" action="" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteButtons = document.querySelectorAll('.delete-product');
    const deleteForm = document.getElementById('deleteForm');
    const productNameElement = document.getElementById('productName');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            deleteForm.action = `<?= base_url('products/') ?>${id}/delete`;
            productNameElement.textContent = name;
            deleteModal.show();
        });
    });

    // Handle status toggle
    const toggleButtons = document.querySelectorAll('.toggle-status');
    toggleButtons.forEach(button => {
        button.addEventListener('change', function() {
            const id = this.dataset.id;
            fetch(`<?= base_url('products/') ?>${id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success toast
                    const toast = new bootstrap.Toast(document.createElement('div'));
                    toast.innerHTML = `
                        <div class="toast-body bg-success text-white">
                            ${data.message}
                        </div>
                    `;
                    document.body.appendChild(toast._element);
                    toast.show();
                    
                    // Update checkbox state
                    this.checked = data.is_active;
                } else {
                    // Show error toast and revert checkbox
                    const toast = new bootstrap.Toast(document.createElement('div'));
                    toast.innerHTML = `
                        <div class="toast-body bg-danger text-white">
                            ${data.message}
                        </div>
                    `;
                    document.body.appendChild(toast._element);
                    toast.show();
                    
                    this.checked = !this.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
            });
        });
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 