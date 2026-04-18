<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Registrasi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0c5c8c 0%, #1a789e 50%, #0c5c8c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            width: 100%;
            max-width: 600px;
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
        .register-card {
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
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
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
        .btn-register {
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
        .btn-register:hover {
            background: #094a6d;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(12, 92, 140, 0.3);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .login-link a {
            color: #0c5c8c;
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 8px;
            font-size: 14px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0c5c8c;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #495057;
        }
        .info-box h6 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #0c5c8c;
        }
        .info-box ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .kode-format {
            font-family: 'Courier New', monospace;
            background: #fff3cd;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            color: #856404;
            margin: 10px 0;
            border: 1px solid #ffc107;
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
    <div class="register-container">
        <!-- Logo dan Welcome Text -->
        <div class="logo-container">
            <div class="logo-box">
                <img src="<?= base_url('images/carousel/logo.png') ?>" alt="Logo Desa">
            </div>
            <div class="welcome-text">
                <h4>Registrasi Warga Baru</h4>
                <p>Sistem Pelayanan Digital Desa Blanakan</p>
            </div>
        </div>

        <!-- Register Card -->
        <div class="register-card">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="info-box">
                            <h6><i class="bi bi-info-circle-fill"></i> Informasi Penting</h6>
                            <ul class="mb-0 small">
                                <li>Registrasi menggunakan <strong>Nomor KTP (Identitas Penduduk)</strong> yang berlaku</li>
                                <li>KTP harus terdaftar di database penduduk desa kami</li>
                                <li>Format KTP: <strong>16 digit angka</strong> tanpa spasi atau karakter khusus</li>
                                <li>Nomor KK (Kartu Keluarga) bersifat opsional untuk verifikasi tambahan</li>
                                <li>Gunakan <strong>Email</strong> atau <strong>Nomor HP</strong> untuk login nantinya</li>
                            </ul>
                        </div>

                        <form action="<?= base_url('auth/register') ?>" method="post">
                            <?= csrf_field() ?>

                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" 
                                           id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" 
                                           placeholder="Masukkan nama lengkap" required>
                                    <?php if (isset($validation) && $validation->hasError('nama_lengkap')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('nama_lengkap') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- No KTP -->
                            <div class="mb-3">
                                <label for="no_ktp" class="form-label">Nomor KTP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('no_ktp') ? 'is-invalid' : '' ?>" 
                                           id="no_ktp" name="no_ktp" value="<?= old('no_ktp') ?>" 
                                           placeholder="16 digit nomor KTP" maxlength="16" pattern="[0-9]{16}" required>
                                    <?php if (isset($validation) && $validation->hasError('no_ktp')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('no_ktp') ?></div>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">Harus 16 digit angka tanpa spasi atau karakter khusus</small>
                            </div>

                            <!-- No KK (optional) -->
                            <div class="mb-3">
                                <label for="no_kk" class="form-label">Nomor Kartu Keluarga <span class="text-muted">(Opsional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('no_kk') ? 'is-invalid' : '' ?>" 
                                           id="no_kk" name="no_kk" value="<?= old('no_kk') ?>" 
                                           placeholder="16 digit nomor KK (jika ada)" maxlength="16" pattern="[0-9]{0,16}">
                                    <?php if (isset($validation) && $validation->hasError('no_kk')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('no_kk') ?></div>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">Tidak wajib diisi, untuk verifikasi tambahan saja</small>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <textarea class="form-control <?= isset($validation) && $validation->hasError('alamat') ? 'is-invalid' : '' ?>" 
                                              id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap" required><?= old('alamat') ?></textarea>
                                    <?php if (isset($validation) && $validation->hasError('alamat')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- RT dan RW -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-house"></i></span>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('rt') ? 'is-invalid' : '' ?>" 
                                               id="rt" name="rt" value="<?= old('rt') ?>" placeholder="Contoh: 03" maxlength="3" required>
                                        <?php if (isset($validation) && $validation->hasError('rt')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('rt') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-houses"></i></span>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('rw') ? 'is-invalid' : '' ?>" 
                                               id="rw" name="rw" value="<?= old('rw') ?>" placeholder="Contoh: 02" maxlength="3" required>
                                        <?php if (isset($validation) && $validation->hasError('rw')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('rw') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                           id="email" name="email" value="<?= old('email') ?>" placeholder="contoh@email.com" required>
                                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><strong>Wajib diisi!</strong> Email digunakan untuk notifikasi status akun & surat</small>
                            </div>

                            <!-- Nomor HP -->
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('no_hp') ? 'is-invalid' : '' ?>" 
                                           id="no_hp" name="no_hp" value="<?= old('no_hp') ?>" placeholder="08xxxxxxxxxx" required>
                                    <?php if (isset($validation) && $validation->hasError('no_hp')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('no_hp') ?></div>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">1 nomor HP hanya untuk 1 akun</small>
                            </div>

                            <!-- Password -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                           id="password" name="password" placeholder="Minimal 6 karakter" required>
                                    <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                        <i class="bi bi-eye" id="toggleIcon1"></i>
                                    </span>
                                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4 position-relative">
                                <label for="password_confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control <?= isset($validation) && $validation->hasError('password_confirm') ? 'is-invalid' : '' ?>" 
                                           id="password_confirm" name="password_confirm" placeholder="Konfirmasi password" required>
                                    <span class="password-toggle" onclick="togglePassword('password_confirm', 'toggleIcon2')">
                                        <i class="bi bi-eye" id="toggleIcon2"></i>
                                    </span>
                                    <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('password_confirm') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-register">
                                <i class="bi bi-check-circle"></i> Daftar Sekarang
                            </button>
                        </form>

                        <!-- Login Link -->
                        <div class="login-link">
                            Sudah punya akun? <a href="<?= base_url('auth/login') ?>">Login di sini</a>
                        </div>

                        <!-- Footer Links -->
                        <div class="footer-links">
                            <a href="<?= base_url() ?>">
                                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                            </a>
                        </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
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
    </script>
</body>
</html>
