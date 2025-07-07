<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pengaturan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Pengaturan</h1>
    </div>

    <?= view('shared/messages') ?>

    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url('settings') ?>" method="post" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#company">
                                    <i class="fas fa-building"></i> Informasi Perusahaan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#invoice">
                                    <i class="fas fa-file-invoice"></i> Invoice
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#email">
                                    <i class="fas fa-envelope"></i> Email
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#notification">
                                    <i class="fas fa-bell"></i> Notifikasi
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Company Settings -->
                            <div class="tab-pane fade show active" id="company">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="company.name" class="form-label">Nama Perusahaan</label>
                                        <input type="text" class="form-control" id="company.name" name="company[name]"
                                               value="<?= esc($settings['company']['name']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="company.tax_id" class="form-label">NPWP</label>
                                        <input type="text" class="form-control" id="company.tax_id" name="company[tax_id]"
                                               value="<?= esc($settings['company']['tax_id']['value']) ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="company.address" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="company.address" name="company[address]" 
                                                rows="3"><?= esc($settings['company']['address']['value']) ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="company.phone" class="form-label">Telepon</label>
                                        <input type="tel" class="form-control" id="company.phone" name="company[phone]"
                                               value="<?= esc($settings['company']['phone']['value']) ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="company.email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="company.email" name="company[email]"
                                               value="<?= esc($settings['company']['email']['value']) ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="company.website" class="form-label">Website</label>
                                        <input type="url" class="form-control" id="company.website" name="company[website]"
                                               value="<?= esc($settings['company']['website']['value']) ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="company.logo" class="form-label">Logo</label>
                                        <input type="file" class="form-control" id="company.logo" name="company[logo]"
                                               accept="image/*">
                                        <?php if ($settings['company']['logo']['value']): ?>
                                            <div class="mt-2">
                                                <img src="<?= base_url('settings/logo/' . $settings['company']['logo']['value']) ?>" 
                                                     alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Settings -->
                            <div class="tab-pane fade" id="invoice">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="invoice.prefix" class="form-label">Prefix Nomor Invoice</label>
                                        <input type="text" class="form-control" id="invoice.prefix" name="invoice[prefix]"
                                               value="<?= esc($settings['invoice']['prefix']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="invoice.next_number" class="form-label">Nomor Invoice Berikutnya</label>
                                        <input type="number" class="form-control" id="invoice.next_number" 
                                               name="invoice[next_number]"
                                               value="<?= esc($settings['invoice']['next_number']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="invoice.due_days" class="form-label">Jatuh Tempo (Hari)</label>
                                        <input type="number" class="form-control" id="invoice.due_days" 
                                               name="invoice[due_days]"
                                               value="<?= esc($settings['invoice']['due_days']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="invoice.currency" class="form-label">Mata Uang</label>
                                        <input type="text" class="form-control" id="invoice.currency" 
                                               name="invoice[currency]"
                                               value="<?= esc($settings['invoice']['currency']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="invoice.notes" class="form-label">Catatan Invoice</label>
                                        <textarea class="form-control" id="invoice.notes" name="invoice[notes]" 
                                                rows="3"><?= esc($settings['invoice']['notes']['value']) ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="invoice.terms" class="form-label">Syarat & Ketentuan</label>
                                        <textarea class="form-control" id="invoice.terms" name="invoice[terms]" 
                                                rows="3"><?= esc($settings['invoice']['terms']['value']) ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="invoice.late_payment_fee" class="form-label">Denda Keterlambatan (%)</label>
                                        <input type="number" class="form-control" id="invoice.late_payment_fee" 
                                               name="invoice[late_payment_fee]" step="0.01"
                                               value="<?= esc($settings['invoice']['late_payment_fee']['value']) ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Email Settings -->
                            <div class="tab-pane fade" id="email">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="email.from_name" class="form-label">Nama Pengirim</label>
                                        <input type="text" class="form-control" id="email.from_name" 
                                               name="email[from_name]"
                                               value="<?= esc($settings['email']['from_name']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email.from_email" class="form-label">Email Pengirim</label>
                                        <input type="email" class="form-control" id="email.from_email" 
                                               name="email[from_email]"
                                               value="<?= esc($settings['email']['from_email']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email.smtp_host" class="form-label">SMTP Host</label>
                                        <input type="text" class="form-control" id="email.smtp_host" 
                                               name="email[smtp_host]"
                                               value="<?= esc($settings['email']['smtp_host']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email.smtp_port" class="form-label">SMTP Port</label>
                                        <input type="number" class="form-control" id="email.smtp_port" 
                                               name="email[smtp_port]"
                                               value="<?= esc($settings['email']['smtp_port']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email.smtp_crypto" class="form-label">SMTP Crypto</label>
                                        <select class="form-select" id="email.smtp_crypto" name="email[smtp_crypto]">
                                            <option value="tls" <?= $settings['email']['smtp_crypto']['value'] == 'tls' ? 'selected' : '' ?>>TLS</option>
                                            <option value="ssl" <?= $settings['email']['smtp_crypto']['value'] == 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email.smtp_user" class="form-label">SMTP Username</label>
                                        <input type="text" class="form-control" id="email.smtp_user" 
                                               name="email[smtp_user]"
                                               value="<?= esc($settings['email']['smtp_user']['value']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email.smtp_pass" class="form-label">SMTP Password</label>
                                        <input type="password" class="form-control" id="email.smtp_pass" 
                                               name="email[smtp_pass]"
                                               placeholder="Biarkan kosong jika tidak ingin mengubah password">
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="tab-pane fade" id="notification">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="notification.payment_reminder_days" class="form-label">
                                            Pengingat Pembayaran (Hari)
                                        </label>
                                        <input type="number" class="form-control" id="notification.payment_reminder_days" 
                                               name="notification[payment_reminder_days]"
                                               value="<?= esc($settings['notification']['payment_reminder_days']['value']) ?>" 
                                               required>
                                        <div class="form-text">
                                            Kirim pengingat pembayaran sebelum jatuh tempo
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mt-4">
                                            <input type="hidden" name="notification[enable_email_notifications]" value="0">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="notification.enable_email_notifications"
                                                   name="notification[enable_email_notifications]" value="1"
                                                   <?= $settings['notification']['enable_email_notifications']['value'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="notification.enable_email_notifications">
                                                Aktifkan Notifikasi Email
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mt-4">
                                            <input type="hidden" name="notification[enable_system_notifications]" value="0">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="notification.enable_system_notifications"
                                                   name="notification[enable_system_notifications]" value="1"
                                                   <?= $settings['notification']['enable_system_notifications']['value'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="notification.enable_system_notifications">
                                                Aktifkan Notifikasi Sistem
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 