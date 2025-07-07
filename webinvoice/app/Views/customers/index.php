<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Data Pelanggan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2>Data Pelanggan</h2>
        </div>
        <div class="col text-end">
            <a href="<?= base_url('customers/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pelanggan
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)) : ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pelanggan</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($customers as $index => $customer) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($customer['name']) ?></td>
                                    <td><?= esc($customer['email']) ?></td>
                                    <td><?= esc($customer['phone']) ?></td>
                                    <td><?= esc($customer['address']) ?></td>
                                    <td>
                                        <a href="<?= base_url('customers/' . $customer['id']) ?>" class="btn btn-sm btn-info text-white" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('customers/' . $customer['id'] . '/edit') ?>" class="btn btn-sm btn-warning text-white" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= base_url('customers/' . $customer['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="mt-3">
                <?= $pager->links() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 