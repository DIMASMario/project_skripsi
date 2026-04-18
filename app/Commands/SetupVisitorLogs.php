<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SetupVisitorLogs extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'setup:visitor-logs';
    protected $description = 'Setup visitor logs table and sample data';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // Create visitor_logs table
        $sql = "CREATE TABLE IF NOT EXISTS `visitor_logs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `ip_address` varchar(45) NOT NULL,
          `user_agent` varchar(255) DEFAULT NULL,
          `page_visited` varchar(255) DEFAULT 'home',
          `session_id` varchar(128) DEFAULT NULL,
          `visited_at` datetime DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_visited_at` (`visited_at`),
          KEY `idx_ip_address` (`ip_address`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        try {
            $db->query($sql);
            CLI::write('✓ Tabel visitor_logs berhasil dibuat', 'green');
            
            // Insert sample data
            $sampleData = [
                ['127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'admin', 'sess_123abc'],
                ['127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'admin/users', 'sess_123abc'],
                ['192.168.1.100', 'Mozilla/5.0 (Android 10; Mobile; rv:91.0) Gecko/91.0 Firefox/91.0', 'home', 'sess_456def'],
                ['192.168.1.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)', 'berita', 'sess_789ghi'],
                ['127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'admin/surat', 'sess_123abc'],
                ['192.168.1.102', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', 'dashboard', 'sess_101jkl'],
                ['127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'admin/user-activity', 'sess_123abc'],
            ];
            
            foreach ($sampleData as $i => $data) {
                $minutesAgo = (7 - $i) * 10; // Different times
                $visitedAt = date('Y-m-d H:i:s', strtotime("-$minutesAgo minutes"));
                
                $insertSql = "INSERT IGNORE INTO visitor_logs (ip_address, user_agent, page_visited, session_id, visited_at) 
                              VALUES (?, ?, ?, ?, ?)";
                $db->query($insertSql, [$data[0], $data[1], $data[2], $data[3], $visitedAt]);
            }
            
            CLI::write('✓ Data sample berhasil ditambahkan', 'green');
            
            // Check results
            $count = $db->query("SELECT COUNT(*) as total FROM visitor_logs")->getRow()->total;
            CLI::write("✓ Total record visitor_logs: $count", 'green');
            
        } catch (\Exception $e) {
            CLI::write('❌ Error: ' . $e->getMessage(), 'red');
        }
    }
}