<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'key_name', 'value'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'key_name' => 'required|is_unique[settings.key_name]',
        'value' => 'required'
    ];

    public function getSetting($key)
    {
        try {
            if (!$this->db->tableExists($this->table)) {
                return null;
            }
            $result = $this->where('key_name', $key)->first();
            return $result ? $result['value'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setSetting($key, $value)
    {
        $existing = $this->where('key_name', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], ['value' => $value]);
        } else {
            return $this->insert(['key_name' => $key, 'value' => $value]);
        }
    }

    public function getAllSettings()
    {
        try {
            // Check if table exists before querying
            if (!$this->db->tableExists($this->table)) {
                log_message('warning', 'Settings table does not exist yet');
                return [];
            }
            
            $settings = $this->findAll();
            $result = [];
            
            foreach ($settings as $setting) {
                $result[$setting['key_name']] = $setting['value'];
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('warning', 'Could not retrieve settings: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get multiple settings dengan caching untuk performance
     */
    public function getMultipleSettings(array $keys)
    {
        $settings = $this->whereIn('key_name', $keys)->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key_name']] = $setting['value'];
        }
        
        return $result;
    }

    /**
     * Update multiple settings sekaligus
     */
    public function updateMultipleSettings(array $data)
    {
        $success = true;
        
        foreach ($data as $key => $value) {
            if (!$this->setSetting($key, $value)) {
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * Get settings dengan default values
     */
    public function getSettingWithDefault($key, $default = '')
    {
        $value = $this->getSetting($key);
        return $value !== null ? $value : $default;
    }
}