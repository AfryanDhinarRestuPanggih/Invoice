<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Invoice<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Invoice</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('invoices/' . $invoice['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('invoices/update/' . $invoice['id']) ?>" method="post" id="editInvoiceForm">
                        <?= csrf_field() ?>

                        <!-- Invoice Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Nomor Invoice</label>
                                    <input type="text" class="form-control" id="invoice_number" 
                                           value="<?= $invoice['invoice_number'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Jatuh Tempo</label>
                                    <input type="date" class="form-control <?= session('errors.due_date') ? 'is-invalid' : '' ?>" 
                                           id="due_date" name="due_date" 
                                           value="<?= old('due_date', date('Y-m-d', strtotime($invoice['due_date']))) ?>" required>
                                    <?php if (session('errors.due_date')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.due_date') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Informasi Pelanggan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                        <input type="text" class="form-control <?= session('errors.customer_name') ? 'is-invalid' : '' ?>" 
                                               id="customer_name" name="customer_name" 
                                               value="<?= old('customer_name', $invoice['customer_name']) ?>" required>
                                        <?php if (session('errors.customer_name')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.customer_name') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Email</label>
                                        <input type="email" class="form-control <?= session('errors.customer_email') ? 'is-invalid' : '' ?>" 
                                               id="customer_email" name="customer_email" 
                                               value="<?= old('customer_email', $invoice['customer_email']) ?>" required>
                                        <?php if (session('errors.customer_email')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.customer_email') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_phone" class="form-label">Telepon</label>
                                        <input type="text" class="form-control <?= session('errors.customer_phone') ? 'is-invalid' : '' ?>" 
                                               id="customer_phone" name="customer_phone" 
                                               value="<?= old('customer_phone', $invoice['customer_phone']) ?>" required>
                                        <?php if (session('errors.customer_phone')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.customer_phone') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_address" class="form-label">Alamat</label>
                                        <textarea class="form-control <?= session('errors.customer_address') ? 'is-invalid' : '' ?>" 
                                                  id="customer_address" name="customer_address" rows="1" required><?= old('customer_address', $invoice['customer_address']) ?></textarea>
                                        <?php if (session('errors.customer_address')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.customer_address') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Produk</h5>
                                <button type="button" class="btn btn-sm btn-success" id="addProductRow">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%">Produk</th>
                                            <th style="width: 20%">Harga</th>
                                            <th style="width: 15%">Jumlah</th>
                                            <th style="width: 20%">Subtotal</th>
                                            <th style="width: 5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $index => $item): ?>
                                            <tr class="product-row">
                                                <td>
                                                    <select name="products[]" class="form-select product-select" required>
                                                        <option value="">Pilih Produk</option>
                                                        <?php foreach ($products as $product): ?>
                                                            <option value="<?= $product['id'] ?>" 
                                                                    data-price="<?= $product['price'] ?>"
                                                                    data-stock="<?= $product['stock'] ?>"
                                                                    <?= $product['id'] == $item['product_id'] ? 'selected' : '' ?>>
                                                                <?= esc($product['name']) ?> (<?= esc($product['code']) ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control text-end price" 
                                                               value="<?= number_format($item['price'], 0, ',', '.') ?>" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" name="quantities[]" class="form-control quantity" 
                                                           value="<?= $item['quantity'] ?>" min="1" required>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control text-end subtotal" 
                                                               value="<?= number_format($item['amount'], 0, ',', '.') ?>" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-row" <?= $index === 0 ? 'disabled' : '' ?>>
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td colspan="2">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control" id="totalAmount" 
                                                           value="<?= number_format($invoice['total_amount'], 0, ',', '.') ?>" readonly>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes', $invoice['notes']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Catatan:</h6>
                        <ul class="mb-0">
                            <li>Hanya invoice dengan status draft yang dapat diedit</li>
                            <li>Perubahan akan mempengaruhi total invoice</li>
                            <li>Pastikan stok produk mencukupi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsTable = document.getElementById('productsTable');
    const addProductRow = document.getElementById('addProductRow');
    
    // Format number to currency
    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
    
    // Calculate row subtotal
    function calculateSubtotal(row) {
        const select = row.querySelector('.product-select');
        const quantity = row.querySelector('.quantity');
        const priceDisplay = row.querySelector('.price');
        const subtotalDisplay = row.querySelector('.subtotal');
        
        if (select.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price);
            const qty = parseInt(quantity.value);
            const subtotal = price * qty;
            
            priceDisplay.value = formatCurrency(price);
            subtotalDisplay.value = formatCurrency(subtotal);
        } else {
            priceDisplay.value = '';
            subtotalDisplay.value = '';
        }
        
        calculateTotal();
    }
    
    // Calculate total amount
    function calculateTotal() {
        const rows = document.querySelectorAll('.product-row');
        let total = 0;
        
        rows.forEach(row => {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.quantity');
            
            if (select.value) {
                const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                const qty = parseInt(quantity.value);
                total += price * qty;
            }
        });
        
        document.getElementById('totalAmount').value = formatCurrency(total);
    }
    
    // Add new product row
    addProductRow.addEventListener('click', function() {
        const tbody = productsTable.querySelector('tbody');
        const firstRow = tbody.querySelector('.product-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.price').value = '';
        newRow.querySelector('.quantity').value = '1';
        newRow.querySelector('.subtotal').value = '';
        newRow.querySelector('.remove-row').disabled = false;
        
        // Add event listeners
        addRowEventListeners(newRow);
        
        tbody.appendChild(newRow);
    });
    
    // Add event listeners to row
    function addRowEventListeners(row) {
        const select = row.querySelector('.product-select');
        const quantity = row.querySelector('.quantity');
        const removeBtn = row.querySelector('.remove-row');
        
        select.addEventListener('change', () => calculateSubtotal(row));
        quantity.addEventListener('change', () => calculateSubtotal(row));
        quantity.addEventListener('keyup', () => calculateSubtotal(row));
        
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.product-row').length > 1) {
                row.remove();
                calculateTotal();
            }
        });
    }
    
    // Add event listeners to existing rows
    document.querySelectorAll('.product-row').forEach(row => {
        addRowEventListeners(row);
    });
    
    // Set minimum due date to today
    const dueDateInput = document.getElementById('due_date');
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.min = today;
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 