<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    protected $table = 'site_config';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'config_key', 'config_value', 'config_group', 'description', 'data_type', 'is_public'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all configuration as key-value pairs
     */
    public function getAllConfig()
    {
        $configs = $this->findAll();
        $result = [];
        
        foreach ($configs as $config) {
            $result[$config['config_key']] = $config['config_value'];
        }
        
        return $result;
    }

    /**
     * Get configuration by group
     */
    public function getConfigByGroup($group)
    {
        return $this->where('config_group', $group)
                   ->findAll();
    }

    /**
     * Get single config value
     */
    public function getConfigValue($key, $default = null)
    {
        $config = $this->where('config_key', $key)
                      ->first();
        
        return $config ? $config['config_value'] : $default;
    }

    /**
     * Set configuration value
     */
    public function setConfig($key, $value, $group = 'general', $description = '', $dataType = 'string', $isPublic = 1)
    {
        $existing = $this->where('config_key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'config_value' => $value,
                'config_group' => $group,
                'description' => $description,
                'data_type' => $dataType,
                'is_public' => $isPublic
            ]);
        } else {
            return $this->insert([
                'config_key' => $key,
                'config_value' => $value,
                'config_group' => $group,
                'description' => $description,
                'data_type' => $dataType,
                'is_public' => $isPublic
            ]);
        }
    }

    /**
     * Update multiple configurations at once
     */
    public function updateMultipleConfig($configs)
    {
        $db = $this->db;
        $db->transStart();
        
        foreach ($configs as $key => $value) {
            $this->setConfig($key, $value);
        }
        
        $db->transComplete();
        
        return $db->transStatus();
    }

    /**
     * Delete configuration
     */
    public function deleteConfig($key)
    {
        return $this->where('config_key', $key)->delete();
    }

    /**
     * Get configuration groups
     */
    public function getConfigGroups()
    {
        return $this->select('config_group')
                   ->distinct()
                   ->findAll();
    }
}