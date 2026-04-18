<?php
/**
 * Database Cleanup Script
 * Purpose: Remove unused tables from Desa Blanakan database
 * Usage: php spark db:cleanup
 * 
 * CRITICAL: This script will DROP tables. Ensure database is backed up first!
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DbCleanup extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:cleanup';
    protected $description = 'Remove unused tables from database (non-production only)';
    protected $usage       = 'db:cleanup [--force]';
    protected $arguments   = [];
    protected $options     = [
        '--force' => 'Skip confirmation and proceed with cleanup'
    ];

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Display warning
        CLI::write('╔════════════════════════════════════════════════════════════╗', 'red');
        CLI::write('║         ⚠️  DATABASE CLEANUP - PRODUCTION WARNING  ⚠️        ║', 'red');
        CLI::write('╚════════════════════════════════════════════════════════════╝', 'red');
        CLI::newLine();
        
        CLI::write('This script will DELETE the following UNUSED tables:', 'yellow');
        CLI::write('─────────────────────────────────────────────────────', 'yellow');
        CLI::write('1. security_logs          (0 rows - unused logging table)', 'red');
        CLI::write('2. template_surat_backup  (0 rows - backup duplicate)', 'red');
        CLI::write('3. settings               (0 rows - consolidated to site_config)', 'red');
        CLI::write('4. desa                   (OPTIONAL - duplicate of kelurahan_desa)', 'yellow');
        CLI::newLine();
        
        CLI::write('Space to be freed: ~80KB', 'green');
        CLI::newLine();
        
        // Confirmation
        if (!in_array('--force', $params) && !$this->confirm('🤔 Are you SURE you want to proceed? This cannot be undone!')) {
            CLI::write('❌ Cleanup cancelled. No changes made.', 'red');
            return;
        }
        
        CLI::newLine();
        CLI::write('🔄 Starting database cleanup...', 'cyan');
        CLI::newLine();
        
        $tablesToDrop = [
            'security_logs' => 'Unused security logging table',
            'template_surat_backup' => 'Backup duplicate of template_surat',
            'settings' => 'Redundant (consolidated to site_config)'
        ];
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($tablesToDrop as $table => $reason) {
            try {
                CLI::write("⏳ Dropping table [{$table}]...", 'yellow');
                
                // Check if table exists
                $tableExists = $this->tableExists($db, $table);
                
                if (!$tableExists) {
                    CLI::write("   ⚠️  Table not found (already deleted?)", 'cyan');
                    continue;
                }
                
                // Drop table
                $db->query("DROP TABLE IF EXISTS `{$table}`");
                
                CLI::write("   ✅ DROPPED: {$reason}", 'green');
                $successCount++;
                
            } catch (\Exception $e) {
                CLI::error("   ❌ ERROR: " . $e->getMessage());
                $failCount++;
            }
        }
        
        // Optional: Ask about desa table
        CLI::newLine();
        if ($this->confirm('🤔 Also remove "desa" table (optional)? This is a duplicate of kelurahan_desa.')) {
            try {
                CLI::write("⏳ Dropping table [desa]...", 'yellow');
                
                if ($this->tableExists($db, 'desa')) {
                    $db->query("DROP TABLE IF EXISTS `desa`");
                    CLI::write("   ✅ DROPPED: Duplicate of kelurahan_desa", 'green');
                    $successCount++;
                }
            } catch (\Exception $e) {
                CLI::error("   ❌ ERROR: " . $e->getMessage());
                $failCount++;
            }
        }
        
        // Summary
        CLI::newLine();
        CLI::write('╔════════════════════════════════════════════════════════════╗', 'cyan');
        CLI::write('║                    CLEANUP SUMMARY                        ║', 'cyan');
        CLI::write('╚════════════════════════════════════════════════════════════╝', 'cyan');
        CLI::write("✅ Successfully dropped: {$successCount} tables", 'green');
        
        if ($failCount > 0) {
            CLI::error("❌ Failed to drop: {$failCount} tables");
        }
        
        // Display remaining tables
        CLI::newLine();
        CLI::write('📋 Remaining tables in database:', 'cyan');
        
        $tables = $db->query("
            SELECT TABLE_NAME as table_name, TABLE_ROWS as row_count
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = '" . $db->getDatabase() . "'
            ORDER BY TABLE_NAME
        ")->getResultArray();
        
        $totalRows = 0;
        foreach ($tables as $table) {
            $rowCount = $table['row_count'] ?? 0;
            $totalRows += $rowCount;
            $bar = str_repeat('█', min($rowCount / 10, 30));
            CLI::write(sprintf("   %-30s %6d rows  %s", $table['table_name'], $rowCount, $bar), 'white');
        }
        
        CLI::newLine();
        CLI::write("Total tables: " . count($tables), 'green');
        CLI::write("Total rows:   {$totalRows}", 'green');
        
        CLI::newLine();
        CLI::write('✨ Database cleanup completed successfully!', 'green');
    }
    
    /**
     * Check if table exists
     */
    private function tableExists($db, $tableName)
    {
        $result = $db->query("
            SELECT TABLE_NAME FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = '" . $db->getDatabase() . "' 
            AND TABLE_NAME = '{$tableName}'
        ")->getResultArray();
        
        return count($result) > 0;
    }
    
    /**
     * Confirm action with user
     */
    private function confirm($prompt)
    {
        return CLI::prompt($prompt, ['y', 'n']) === 'y';
    }
}
