<?php
/**
 * RESET PASSWORD ADMIN TOOL
 * URL: http://localhost:8080/reset_password_admin.php
 * 
 * Tool ini akan:
 * 1. Generate password baru
 * 2. Update hash di database
 * 3. Test login dengan password baru
 */

// Database settings
$host = 'localhost';
$port = 3306;
$database = 'db_desa_blanakan';
$username = 'root';
$password = '';

// Connect to database
$db = new mysqli($host, $username, $password, $database, $port);
if ($db->connect_error) {
    die("❌ Connection failed: " . $db->connect_error);
}
$db->set_charset("utf8mb4");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #dc3545; border-bottom: 3px solid #dc3545; padding-bottom: 10px; }
        h3 { color: #333; margin-top: 25px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 10px 0; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 10px 0; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 10px 0; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table th { background: #dc3545; color: white; padding: 10px; text-align: left; }
        table td { padding: 10px; border-bottom: 1px solid #ddd; }
        form { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 15px 0; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        input[type="radio"] { margin: 0 5px 0 15px; }
        .btn { padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; margin: 5px; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-info:hover { background: #138496; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; font-size: 14px; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .password-box { background: #fffacd; border: 2px solid #ffc107; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .password-value { font-size: 24px; font-weight: bold; color: #dc3545; padding: 15px; background: white; border-radius: 5px; margin: 10px 0; font-family: monospace; }
    </style>
</head>
<body>

<div class="container">
    <h2>🔐 RESET PASSWORD ADMIN</h2>
    <p><strong>⚠️ PERHATIAN:</strong> Tool ini akan mengubah password admin di database!</p>
    
    <hr>
    
    <!-- Step 1: Select Admin -->
    <h3>1. Pilih Admin User</h3>
    <?php
    $result = $db->query("SELECT id, nama_lengkap, email, no_hp FROM users WHERE role = 'admin' ORDER BY id");
    if ($result && $result->num_rows > 0):
        $admins = $result->fetch_all(MYSQLI_ASSOC);
    ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
            </tr>
            <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?= $admin['id'] ?></td>
                <td><?= htmlspecialchars($admin['nama_lengkap']) ?></td>
                <td><strong><?= htmlspecialchars($admin['email']) ?></strong></td>
                <td><?= htmlspecialchars($admin['no_hp']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="error">❌ No admin users found!</div>
    <?php endif; ?>
    
    <hr>
    
    <!-- Step 2: Reset Password Form -->
    <h3>2. Reset Password</h3>
    
    <form method="post">
        <label><strong>Pilih Admin:</strong></label><br>
        <?php foreach ($admins as $admin): ?>
            <label style="display: block; padding: 8px; background: white; margin: 5px 0; border-radius: 4px; cursor: pointer;">
                <input type="radio" name="user_id" value="<?= $admin['id'] ?>" <?= $admin['id'] == 1 ? 'checked' : '' ?> required>
                <strong><?= htmlspecialchars($admin['nama_lengkap']) ?></strong> (<?= htmlspecialchars($admin['email']) ?>)
            </label>
        <?php endforeach; ?>
        
        <br><br>
        <label><strong>Password Baru:</strong></label>
        <input type="text" name="new_password" value="admin123" placeholder="Masukkan password baru" required>
        <small style="color: #666; display: block; margin-top: 5px;">
            💡 Gunakan password yang mudah diingat seperti: admin123, password, blanakan2026
        </small>
        
        <br><br>
        <button type="submit" name="reset_submit" class="btn btn-danger">
            🔓 Reset Password Sekarang
        </button>
    </form>
    
    <?php
    if (isset($_POST['reset_submit'])) {
        $userId = (int)$_POST['user_id'];
        $newPassword = $_POST['new_password'];
        
        // Get user info
        $stmt = $db->prepare("SELECT nama_lengkap, email FROM users WHERE id = ? AND role = 'admin'");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user) {
            // Generate password hash
            $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
            
            // Update database
            $updateStmt = $db->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            $updateStmt->bind_param("si", $passwordHash, $userId);
            
            if ($updateStmt->execute()) {
                echo '<div class="success">';
                echo '<h3 style="color:green;">✅ PASSWORD BERHASIL DIRESET!</h3>';
                echo '<p><strong>User:</strong> ' . htmlspecialchars($user['nama_lengkap']) . '</p>';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
                echo '</div>';
                
                echo '<div class="password-box">';
                echo '<h3>🔑 PASSWORD BARU ANDA:</h3>';
                echo '<div class="password-value">' . htmlspecialchars($newPassword) . '</div>';
                echo '<p><strong>⚠️ SIMPAN PASSWORD INI!</strong></p>';
                echo '<p>Gunakan password ini untuk login di: <code>' . htmlspecialchars($user['email']) . '</code></p>';
                echo '</div>';
                
                echo '<div class="info">';
                echo '<h4>📋 Detail Teknis:</h4>';
                echo '<p><strong>Password Hash Baru:</strong></p>';
                echo '<pre>' . htmlspecialchars($passwordHash) . '</pre>';
                echo '<p><strong>Panjang:</strong> ' . strlen($newPassword) . ' karakter</p>';
                echo '<p><strong>Hash Algorithm:</strong> bcrypt (PASSWORD_BCRYPT)</p>';
                echo '<p><strong>Updated At:</strong> ' . date('Y-m-d H:i:s') . '</p>';
                echo '</div>';
                
                // Test password verification
                if (password_verify($newPassword, $passwordHash)) {
                    echo '<div class="success">';
                    echo '<h4>✅ Verifikasi Password: BERHASIL</h4>';
                    echo '<p>Password dapat digunakann untuk login.</p>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h4>❌ Verifikasi Password: GAGAL</h4>';
                    echo '<p>Ada masalah dengan password hash!</p>';
                    echo '</div>';
                }
                
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<a href="test_login_admin.php" class="btn btn-info">🧪 Test Login dengan Password Baru</a> ';
                echo '<a href="../auth/admin-login" class="btn btn-success">🚀 Login ke Admin Panel</a>';
                echo '</div>';
                
            } else {
                echo '<div class="error">';
                echo '<h4>❌ UPDATE GAGAL!</h4>';
                echo '<p>Error: ' . htmlspecialchars($db->error) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<div class="error">❌ User tidak ditemukan atau bukan admin!</div>';
        }
    }
    ?>
    
    <hr>
    
    <!-- Step 3: Quick Password Presets -->
    <h3>3. Password Presets (One-Click Reset)</h3>
    <div class="info">
        <p>Klik salah satu password di bawah untuk langsung reset password admin utama (ID: 1):</p>
        <form method="post" style="display: inline-block; margin: 5px;">
            <input type="hidden" name="user_id" value="1">
            <input type="hidden" name="new_password" value="password">
            <button type="submit" name="reset_submit" class="btn btn-info">Reset ke: password</button>
        </form>
        <form method="post" style="display: inline-block; margin: 5px;">
            <input type="hidden" name="user_id" value="1">
            <input type="hidden" name="new_password" value="admin123">
            <button type="submit" name="reset_submit" class="btn btn-info">Reset ke: admin123</button>
        </form>
        <form method="post" style="display: inline-block; margin: 5px;">
            <input type="hidden" name="user_id" value="1">
            <input type="hidden" name="new_password" value="blanakan2026">
            <button type="submit" name="reset_submit" class="btn btn-info">Reset ke: blanakan2026</button>
        </form>
    </div>
    
    <hr>
    
    <!-- Step 4: Common Passwords Tester -->
    <h3>4. Test Common Passwords</h3>
    <div class="info">
        <p>Coba berbagai password umum terhadap hash yang ada di database:</p>
        <?php
        $commonPasswords = ['password', 'admin', 'admin123', 'blanakan', 'blanakan123', 'desa123', '12345678', 'password123'];
        
        // Get admin 1 password hash
        $result = $db->query("SELECT password FROM users WHERE id = 1 AND role = 'admin'");
        if ($result && $row = $result->fetch_assoc()) {
            $currentHash = $row['password'];
            echo '<p><strong>Testing against Admin ID 1 hash...</strong></p>';
            echo '<ul style="list-style: none; padding: 0;">';
            
            $found = false;
            foreach ($commonPasswords as $testPass) {
                if (password_verify($testPass, $currentHash)) {
                    echo '<li style="background: #d4edda; padding: 8px; margin: 3px 0; border-radius: 4px;">';
                    echo '✅ <strong style="color: green;">' . htmlspecialchars($testPass) . '</strong> - MATCH!';
                    echo '</li>';
                    $found = true;
                } else {
                    echo '<li style="background: #f8f9fa; padding: 8px; margin: 3px 0;">';
                    echo '❌ ' . htmlspecialchars($testPass) . ' - tidak cocok';
                    echo '</li>';
                }
            }
            
            if (!$found) {
                echo '</ul>';
                echo '<div class="warning" style="margin-top: 10px;">';
                echo '<p><strong>⚠️ Tidak ada password umum yang cocok!</strong></p>';
                echo '<p>Gunakan form di atas untuk reset password ke nilai yang baru.</p>';
                echo '</div>';
            } else {
                echo '</ul>';
                echo '<div class="success" style="margin-top: 10px;">';
                echo '<p><strong>✅ Password ditemukan!</strong> Gunakan password yang match untuk login.</p>';
                echo '</div>';
            }
        }
        ?>
    </div>
    
    <hr>
    <p style="color:#666;font-size:12px;">📍 Reset Password Tool v1.0 - Updated: 15 Feb 2026</p>
</div>

<?php $db->close(); ?>
</body>
</html>