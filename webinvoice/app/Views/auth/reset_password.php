<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Web Invoice</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .reset-container {
            width: 100%;
            max-width: 400px;
            margin: auto;
            padding: 15px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: #fff;
            border-bottom: none;
            padding: 20px;
            text-align: center;
        }
        .card-body {
            padding: 30px;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-weight: 500;
        }
        .app-name {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-lock fa-3x text-primary"></i>
                <div class="app-name">Reset Password</div>
            </div>
            <div class="card-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->get('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->get('success') ?>
                    </div>
                <?php endif; ?>

                <?php 
                // Tampilkan error validasi jika ada
                $errors = session()->getFlashdata('errors');
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger"><ul class="mb-0">';
                    foreach ($errors as $error) {
                        echo "<li>$error</li>";
                    }
                    echo '</ul></div>';
                }
                ?>

                <form action="<?= base_url('/reset-password') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="form-floating">
                        <input type="email" class="form-control <?= (session()->has('error')) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= old('email') ?>"
                               placeholder="name@example.com" required>
                        <label for="email">Email</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control <?= (session()->has('error')) ? 'is-invalid' : '' ?>" 
                               id="new_password" name="new_password" 
                               placeholder="Password Baru" required>
                        <label for="new_password">Password Baru</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control <?= (session()->has('error')) ? 'is-invalid' : '' ?>" 
                               id="confirm_password" name="confirm_password" 
                               placeholder="Konfirmasi Password" required>
                        <label for="confirm_password">Konfirmasi Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Reset Password
                    </button>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('login') ?>">Kembali ke Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 