<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Web Invoice</title>
    
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
        .login-container {
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
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-file-invoice fa-3x text-primary"></i>
                <div class="app-name">Web Invoice</div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="name@example.com" value="<?= old('email') ?>" required>
                        <label for="email">Email</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <div class="text-center mt-3">
                        Belum punya akun? <a href="<?= base_url('register') ?>">Daftar di sini</a>
                        <br>
                        <a href="<?= base_url('reset-password') ?>">Lupa Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 