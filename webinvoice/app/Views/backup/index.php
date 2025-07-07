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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-database me-1"></i>
                    Daftar Backup
                </div>
                <div>
                    <button type="button" class="btn btn-success btn-sm me-2" onclick="location.href='<?= base_url('backup/create') ?>'">
                        <i class="fas fa-plus"></i> Buat Backup
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#restoreModal">
                        <i class="fas fa-upload"></i> Restore
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($backups)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada file backup</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($backups as $backup): ?>
                        <tr>
                            <td><?= esc($backup['name']) ?></td>
                            <td><?= esc($backup['size']) ?></td>
                            <td><?= esc($backup['date']) ?></td>
                            <td>
                                <a href="<?= base_url('backup/download/' . $backup['name']) ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete('<?= $backup['name'] ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
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

<!-- Restore Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('backup/restore') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="modal-header">
                    <h5 class="modal-title">Restore Database</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Perhatian! Proses restore akan menghapus semua data yang ada dan menggantinya dengan data dari file backup.
                    </div>
                    <div class="mb-3">
                        <label for="backup" class="form-label">File Backup</label>
                        <input type="file" class="form-control" id="backup" name="backup" accept=".zip" required>
                        <div class="form-text">
                            Pilih file backup dalam format ZIP
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Restore</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus file backup ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" class="btn btn-danger" id="deleteButton">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(filename) {
    const modal = document.getElementById('deleteModal');
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.href = '<?= base_url('backup/delete/') ?>' + filename;
    
    const deleteModal = new bootstrap.Modal(modal);
    deleteModal.show();
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 