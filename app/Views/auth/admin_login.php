<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login Admin' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0c5c8c 0%, #1a789e 50%, #0c5c8c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-box {
            background: white;
            width: 80px;
            height: 80px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        .logo-box img {
            max-width: 60px;
            max-height: 60px;
        }
        .admin-badge {
            background: #dc3545;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .welcome-text {
            color: white;
            text-align: center;
            margin-bottom: 10px;
        }
        .welcome-text h4 {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 8px;
        }
        .welcome-text p {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 0;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.25);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #0c5c8c;
            box-shadow: 0 0 0 0.2rem rgba(12, 92, 140, 0.15);
        }
        .input-group-text {
            background: white;
            border: 1px solid #dee2e6;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
        .btn-login {
            background: #0c5c8c;
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s;
            color: white;
        }
        .btn-login:hover {
            background: #094a6d;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(12, 92, 140, 0.3);
        }
        .alert {
            border-radius: 8px;
            font-size: 14px;
        }
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #856404;
        }
        .info-box i {
            color: #ffc107;
        }
        .footer-links {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        .footer-links a {
            color: #6c757d;
            font-size: 13px;
            text-decoration: none;
            display: block;
            margin: 5px 0;
        }
        .footer-links a:hover {
            color: #0c5c8c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo dan Welcome Text -->
        <div class="logo-container">
            <div class="logo-box">
                <img src="<?= base_url('images/carousel/logo.png') ?>" alt="Logo Desa">
            </div>
            <div class="admin-badge">
                <i class="bi bi-shield-check"></i> Admin Access
            </div>
            <div class="welcome-text">
                <h4>Login Administrator</h4>
                <p>Panel Administrasi Desa Blanakan</p>
            </div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill"></i> <?= session()->getFlashdata('warning') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <i class="bi bi-shield-lock"></i> 
                Area khusus <strong>Administrator</strong> dan <strong>Petugas Desa</strong>
            </div>

            <form action="<?= base_url('auth/admin-login') ?>" method="post" id="adminLoginForm">
                <?= csrf_field() ?>
                
                <!-- Email atau No HP -->
                <div class="mb-3">
                    <label for="identifier" class="form-label">Email atau Nomor HP</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('identifier') ? 'is-invalid' : '' ?>" 
                               id="identifier" 
                               name="identifier" 
                               value="<?= old('identifier') ?>" 
                               placeholder="Masukkan email atau nomor HP admin"
                               required 
                               autofocus>
                        <?php if (isset($validation) && $validation->hasError('identifier')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('identifier') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" 
                               class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan kata sandi admin"
                               required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </span>
                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login" id="btnLogin">
                    <i class="bi bi-shield-check"></i> Masuk ke Admin Panel
                </button>
            </form>

            <!-- Footer Links -->
            <div class="footer-links">
                <a href="<?= base_url('auth/login') ?>">
                    <i class="bi bi-person"></i> Login sebagai Warga
                </a>
                <a href="<?= base_url() ?>">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Disable button on submit to prevent double submit
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const btnLogin = document.getElementById('btnLogin');
            btnLogin.disabled = true;
            btnLogin.innerHTML = '<i class="spinner-border spinner-border-sm"></i> Memproses...';
        });
    </script>
</body>
</html>
