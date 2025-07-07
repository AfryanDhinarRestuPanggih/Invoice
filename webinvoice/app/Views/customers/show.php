<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Detail Pelanggan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2>Detail Pelanggan</h2>
        </div>
        <div class="col text-end">
            <a href="<?= base_url('customers') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="<?= base_url('customers/' . $customer['id'] . '/edit') ?>" class="btn btn-warning text-white">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nama Pelanggan</div>
                <div class="col-md-9"><?= esc($customer['name']) ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Email</div>
                <div class="col-md-9"><?= esc($customer['email']) ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">No. Telepon</div>
                <div class="col-md-9"><?= esc($customer['phone']) ?: '-' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Alamat</div>
                <div class="col-md-9"><?= nl2br(esc($customer['address'])) ?: '-' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nama Perusahaan</div>
                <div class="col-md-9"><?= esc($customer['company_name']) ?: '-' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">NPWP</div>
                <div class="col-md-9"><?= esc($customer['tax_number']) ?: '-' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Catatan</div>
                <div class="col-md-9"><?= nl2br(esc($customer['notes'])) ?: '-' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Status</div>
                <div class="col-md-9">
                    <span class="badge bg-<?= $customer['status'] === 'active' ? 'success' : 'danger' ?>">
                        <?= $customer['status'] === 'active' ? 'Aktif' : 'Tidak Aktif' ?>
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Tanggal Dibuat</div>
                <div class="col-md-9"><?= date('d/m/Y H:i', strtotime($customer['created_at'])) ?></div>
            </div>

            <div class="row">
                <div class="col-md-3 fw-bold">Terakhir Diupdate</div>
                <div class="col-md-9"><?= date('d/m/Y H:i', strtotime($customer['updated_at'])) ?></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 