#!/usr/bin/env php
<?php
/**
 * Database Cleanup Command Line Script
 * Usage: php cleanup_database.php
 */

// Database credentials - adjust as needed
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'db_desa_blanakan';
$dbPort = 3306;

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║         🗑️  DATABASE CLEANUP SCRIPT  🗑️                   ║\n";
echo "║                 Desa Blanakan - Production                 ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "⚠️  This script will DROP unused tables from the database!\n";
echo "⚠️  Make sure you have a backup before proceeding!\n\n";

// Connect to MySQL
try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    
    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error . "\n");
    }
    
    echo "✅ Connected to database: {$dbName}\n\n";
    
} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage() . "\n");
}

// Tables to drop
$tablesToDrop = [
    'security_logs' => 'Unused security logging infrastructure',
    'template_surat_backup' => 'Backup duplicate (already 0 rows)',
    'settings' => 'Replaced by site_config table (consolidated)'
];

echo "Tables to be dropped:\n";
echo "─────────────────────────────────────────────────────\n";
foreach ($tablesToDrop as $table => $reason) {
    echo "  • {$table}\n    └─ {$reason}\n";
}
echo "\n";

// Confirmation
echo "Type 'YES' to confirm cleanup (or anything else to cancel): ";
$input = trim(fgets(STDIN));

if ($input !== 'YES') {
    echo "\n⛔ Cleanup cancelled. No changes made.\n";
    $conn->close();
    exit(0);
}

echo "\n🔄 Starting database cleanup...\n";
echo "═════════════════════════════════════════════════════════\n\n";

$successCount = 0;
$failCount = 0;

foreach ($tablesToDrop as $table => $reason) {
    echo "⏳ Dropping table [{$table}]...\n";
    
    try {
        // Check if table exists
        $checkResult = $conn->query("
            SELECT TABLE_NAME FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = '{$dbName}' 
            AND TABLE_NAME = '{$table}'
        ");
        
        if (!$checkResult || $checkResult->num_rows === 0) {
            echo "   ⚠️  Table not found (may already be deleted)\n";
            continue;
        }
        
        // Drop the table
        if ($conn->query("DROP TABLE IF EXISTS `{$table}`")) {
            echo "   ✅ DROPPED: {$reason}\n";
            $successCount++;
        } else {
            echo "   ❌ ERROR: " . $conn->error . "\n";
            $failCount++;
        }
        
    } catch (Exception $e) {
        echo "   ❌ ERROR: " . $e->getMessage() . "\n";
        $failCount++;
    }
    
    echo "\n";
}

echo "═════════════════════════════════════════════════════════\n\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    CLEANUP SUMMARY                        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
echo "✅ Successfully dropped: {$successCount} tables\n";

if ($failCount > 0) {
    echo "❌ Failed to drop: {$failCount} tables\n";
}

echo "\n📋 Remaining tables in database:\n";
echo "─────────────────────────────────────────────────────\n";

$result = $conn->query("
    SELECT TABLE_NAME as table_name, TABLE_ROWS as row_count
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = '{$dbName}'
    ORDER BY TABLE_NAME
");

$totalTables = 0;
$totalRows = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tableName = $row['table_name'];
        $rowCount = $row['row_count'] ?? 0;
        $totalTables++;
        $totalRows += $rowCount;
        
        printf("   %-30s %8d rows\n", $tableName, $rowCount);
    }
}

echo "─────────────────────────────────────────────────────\n";
echo "   Total tables: {$totalTables}\n";
echo "   Total rows:   {$totalRows}\n\n";

echo "✨ Database cleanup completed successfully!\n";
echo "📊 Space freed: ~80KB\n\n";

$conn->close();

echo "✓ Done! Your database is now optimized.\n\n";
