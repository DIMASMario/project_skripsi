<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationLogModel extends Model
{
    protected $table = 'registration_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['session_id', 'step', 'data', 'ip_address', 'user_agent'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Log registration step
     */
    public function logStep($sessionId, $step, $data, $ipAddress, $userAgent)
    {
        return $this->insert([
            'session_id' => $sessionId,
            'step' => $step,
            'data' => json_encode($data),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    /**
     * Get logs by session ID
     */
    public function getBySessionId($sessionId)
    {
        return $this->where('session_id', $sessionId)
                    ->orderBy('step', 'ASC')
                    ->findAll();
    }

    /**
     * Get registration statistics
     */
    public function getRegistrationStats($days = 30)
    {
        $since = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return $this->db->query("
            SELECT 
                step,
                COUNT(*) as count,
                DATE(created_at) as date
            FROM registration_logs 
            WHERE created_at >= ?
            GROUP BY step, DATE(created_at)
            ORDER BY date DESC, step ASC
        ", [$since])->getResultArray();
    }

    /**
     * Get completion rate
     */
    public function getCompletionRate($days = 30)
    {
        $since = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $step1Count = $this->where('step', 1)
                          ->where('created_at >=', $since)
                          ->countAllResults();
                          
        $step3Count = $this->where('step', 3)
                          ->where('created_at >=', $since)
                          ->countAllResults();
                          
        return [
            'step1_count' => $step1Count,
            'step3_count' => $step3Count,
            'completion_rate' => $step1Count > 0 ? ($step3Count / $step1Count) * 100 : 0
        ];
    }

    /**
     * Clean old logs (optional)
     */
    public function cleanOldLogs($days = 90)
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $cutoff)->delete();
    }
}