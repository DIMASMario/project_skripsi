<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitorLogModel extends Model
{
    protected $table = 'visitor_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ip_address', 'user_agent', 'page_visited', 'session_id', 'visited_at'
    ];
    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';

    /**
     * Log kunjungan visitor
     */
    public function logVisitor($page = 'home')
    {
        $request = service('request');
        
        return $this->insert([
            'ip_address' => $request->getIPAddress(),
            'user_agent' => substr($request->getUserAgent()->getAgentString(), 0, 255),
            'page_visited' => $page,
            'session_id' => session_id(),
            'visited_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get total visitors today
     */
    public function getTotalVisitorHariIni()
    {
        return $this->where('DATE(visited_at)', date('Y-m-d'))
                   ->countAllResults();
    }

    /**
     * Get total unique visitors today
     */
    public function getTotalUniqueVisitorHariIni()
    {
        return $this->select('DISTINCT ip_address')
                   ->where('DATE(visited_at)', date('Y-m-d'))
                   ->countAllResults();
    }

    /**
     * Get total visitors this month
     */
    public function getTotalVisitorBulanIni()
    {
        return $this->where('YEAR(visited_at)', date('Y'))
                   ->where('MONTH(visited_at)', date('m'))
                   ->countAllResults();
    }

    /**
     * Get visitors by date range
     */
    public function getVisitorByDateRange($startDate, $endDate)
    {
        return $this->where('DATE(visited_at) >=', $startDate)
                   ->where('DATE(visited_at) <=', $endDate)
                   ->orderBy('visited_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get most visited pages
     */
    public function getMostVisitedPages($limit = 10)
    {
        return $this->select('page_visited, COUNT(*) as total')
                   ->groupBy('page_visited')
                   ->orderBy('total', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get daily visitor statistics for chart
     */
    public function getDailyStatistics($days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-$days days"));
        
        return $this->select('DATE(visited_at) as tanggal, COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_visitors')
                   ->where('visited_at >=', $startDate)
                   ->groupBy('DATE(visited_at)')
                   ->orderBy('tanggal', 'ASC')
                   ->findAll();
    }

    /**
     * Get recent visitor activity
     */
    public function getRecentActivity($limit = 50)
    {
        return $this->select('ip_address, page_visited, visited_at, user_agent')
                   ->orderBy('visited_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get visitor statistics summary
     */
    public function getStatisticsSummary()
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        
        return [
            'today' => [
                'total' => $this->where('DATE(visited_at)', $today)->countAllResults(),
                'unique' => $this->select('DISTINCT ip_address')->where('DATE(visited_at)', $today)->countAllResults()
            ],
            'this_month' => [
                'total' => $this->where('DATE(visited_at) LIKE', $thisMonth . '%')->countAllResults(),
                'unique' => $this->select('DISTINCT ip_address')->where('DATE(visited_at) LIKE', $thisMonth . '%')->countAllResults()
            ],
            'all_time' => [
                'total' => $this->countAll(),
                'unique' => $this->select('DISTINCT ip_address')->countAllResults()
            ]
        ];
    }

    /**
     * Get active/online users berdasarkan session terakhir dalam N menit
     * @param int $minutes Berapa menit dianggap masih online (default: 15 menit)
     */
    public function getActiveUsersNow($minutes = 15)
    {
        $timeLimit = date('Y-m-d H:i:s', strtotime("-$minutes minutes"));
        
        return $this->select('DISTINCT session_id, ip_address, page_visited, visited_at')
                   ->where('visited_at >=', $timeLimit)
                   ->where('DATE(visited_at)', date('Y-m-d'))
                   ->orderBy('visited_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get count of active/online users berdasarkan session (unique sessions) dalam N menit
     * @param int $minutes Berapa menit dianggap masih online (default: 15 menit)
     */
    public function getCountActiveUsers($minutes = 15)
    {
        $timeLimit = date('Y-m-d H:i:s', strtotime("-$minutes minutes"));
        
        return $this->select('COUNT(DISTINCT session_id) as active_count')
                   ->where('visited_at >=', $timeLimit)
                   ->where('DATE(visited_at)', date('Y-m-d'))
                   ->get()
                   ->getRow()
                   ->active_count ?? 0;
    }

    /**
     * Get unique IPs that are active now (alternative calculation)
     */
    public function getActiveIPsCount($minutes = 15)
    {
        $timeLimit = date('Y-m-d H:i:s', strtotime("-$minutes minutes"));
        
        return $this->select('COUNT(DISTINCT ip_address) as active_ips')
                   ->where('visited_at >=', $timeLimit)
                   ->where('DATE(visited_at)', date('Y-m-d'))
                   ->get()
                   ->getRow()
                   ->active_ips ?? 0;
    }

    /**
     * Get user activities - show visitor logs as user activities
     */
    public function getUserActivities($limit = 100)
    {
        // Get recent visitor logs
        $logs = $this->select('ip_address, page_visited, visited_at, user_agent, session_id')
                     ->orderBy('visited_at', 'DESC')
                     ->limit($limit)
                     ->findAll();

        // Format the results to match expected structure
        $formattedActivities = [];
        foreach ($logs as $log) {
            // Try to determine user action based on page visited
            $action = 'Page Visit';
            $page = $log['page_visited'];
            
            if (strpos($page, 'admin') !== false) {
                $action = 'Admin Access';
            } elseif (strpos($page, 'dashboard') !== false) {
                $action = 'Dashboard Access';
            } elseif (strpos($page, 'surat') !== false) {
                $action = 'Surat Access';
            } elseif (strpos($page, 'berita') !== false) {
                $action = 'Berita Access';
            }

            $formattedActivities[] = [
                'created_at' => $log['visited_at'],
                'user_name' => 'Visitor', // Since we don't have user mapping
                'user_email' => '-',
                'action' => $action,
                'page_url' => $log['page_visited'],
                'ip_address' => $log['ip_address'],
                'user_agent' => substr($log['user_agent'], 0, 100) . (strlen($log['user_agent']) > 100 ? '...' : '')
            ];
        }

        return $formattedActivities;
    }
}
