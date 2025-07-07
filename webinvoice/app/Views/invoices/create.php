<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Buat Invoice<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Buat Invoice</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('invoices') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('invoices/store') ?>" method="post" id="createInvoiceForm">
                        <?= csrf_field() ?>

                        <!-- Invoice Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Nomor Invoice</label>
                                    <input type="text" class="form-control <?= session('errors.invoice_number') ? 'is-invalid' : '' ?>" 
                                           id="invoice_number" name="invoice_number" value="<?= old('invoice_number', $invoice_number) ?>" readonly>
                                    <?php if (session('errors.invoice_number')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.invoice_number') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Jatuh Tempo</label>
                                    <input type="date" class="form-control <?= session('errors.due_date') ? 'is-invalid' : '' ?>" 
                                           id="due_date" name="due_date" value="<?= old('due_date') ?>" required>
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
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="customer_search" class="form-label">Cari Pelanggan</label>
                                        <input type="text" class="form-control" id="customer_search" 
                                               placeholder="Ketik nama atau email pelanggan...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                        <input type="text" class="form-control <?= session('errors.customer_name') ? 'is-invalid' : '' ?>" 
                                               id="customer_name" name="customer_name" value="<?= old('customer_name') ?>" required>
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
                                               id="customer_email" name="customer_email" value="<?= old('customer_email') ?>" required>
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
                                               id="customer_phone" name="customer_phone" value="<?= old('customer_phone') ?>" required>
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
                                                  id="customer_address" name="customer_address" rows="1" required><?= old('customer_address') ?></textarea>
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
                                        <tr class="product-row">
                                            <td>
                                                <select name="products[]" class="form-select product-select" required>
                                                    <option value="">Pilih Produk</option>
                                                    <?php foreach ($products as $product): ?>
                                                        <option value="<?= $product['id'] ?>" 
                                                                data-price="<?= $product['price'] ?>"
                                                                data-stock="<?= $product['stock'] ?>">
                                                            <?= esc($product['name']) ?> (<?= esc($product['code']) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control price-display" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="quantities[]" class="form-control quantity" 
                                                       min="1" value="1" required>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control subtotal-display" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td colspan="2">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control" id="totalAmount" readonly>
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
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes') ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Customer search autocomplete
    $("#customer_search").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "<?= base_url('api/customers/search') ?>",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name + ' (' + item.email + ')',
                            value: item.name,
                            customer: item
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $("#customer_name").val(ui.item.customer.name);
            $("#customer_email").val(ui.item.customer.email);
            $("#customer_phone").val(ui.item.customer.phone);
            $("#customer_address").val(ui.item.customer.address);
            return false;
        }
    });

    const productsTable = document.getElementById('productsTable');
    const addProductRow = document.getElementById('addProductRow');
    
    // Format number to currency
    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
    
    // Calculate row subtotal
    function calculateSubtotal(row) {
        const select = row.querySelector('.product-select');
        const quantity = row.querySelector('.quantity').value;
        const price = select.options[select.selectedIndex]?.dataset?.price || 0;
        const subtotal = price * quantity;
        
        row.querySelector('.price-display').value = formatCurrency(price);
        row.querySelector('.subtotal-display').value = formatCurrency(subtotal);
        
        calculateTotal();
    }
    
    // Calculate total amount
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.quantity').value;
            const price = select.options[select.selectedIndex]?.dataset?.price || 0;
            total += price * quantity;
        });
        document.getElementById('totalAmount').value = formatCurrency(total);
    }

    // Handle product selection change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity')) {
            calculateSubtotal(e.target.closest('.product-row'));
        }
    });

    // Add new product row
    addProductRow.addEventListener('click', function() {
        const newRow = productsTable.querySelector('.product-row').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = input.type === 'number' ? 1 : '');
        newRow.querySelector('select').selectedIndex = 0;
        productsTable.querySelector('tbody').appendChild(newRow);
        
        // Enable all remove buttons if there's more than one row
        if (document.querySelectorAll('.product-row').length > 1) {
            document.querySelectorAll('.remove-row').forEach(btn => btn.disabled = false);
        }
    });

    // Remove product row
    productsTable.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const rows = document.querySelectorAll('.product-row');
            if (rows.length > 1) {
                e.target.closest('.product-row').remove();
                calculateTotal();
                
                // Disable remove button if only one row left
                if (rows.length === 2) {
                    document.querySelector('.remove-row').disabled = true;
                }
            }
        }
    });

    // Initialize first row
    calculateSubtotal(document.querySelector('.product-row'));
    document.querySelector('.remove-row').disabled = true;

    // Set minimum due date to today
    const dueDateInput = document.getElementById('due_date');
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.min = today;
    
    // Set default due date to 7 days from now if not set
    if (!dueDateInput.value) {
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        dueDateInput.value = nextWeek.toISOString().split('T')[0];
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 