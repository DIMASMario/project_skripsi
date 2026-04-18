<?php
// Direct database initialization script
// This creates missing tables without running through CodeIgniter bootstrap

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'db_desa_blanakan';

try {
    $mysqli = new mysqli($host, $user, $pass, $db);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Create settings table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS `settings` (
      `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
      `setting_key` varchar(100) NOT NULL,
      `setting_value` longtext,
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uk_setting_key` (`setting_key`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";

    if ($mysqli->query($sql)) {
        echo "✓ Settings table created/verified\n";
    } else {
        echo "✗ Error: " . $mysqli->error . "\n";
    }

    // Verify table exists
    $result = $mysqli->query("SHOW TABLES LIKE 'settings'");
    if ($result && $result->num_rows > 0) {
        echo "✓ Settings table confirmed in database\n";
    } else {
        echo "✗ Settings table not found after creation\n";
    }

    $mysqli->close();
    echo "\nDatabase setup complete!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
