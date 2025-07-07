<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['setting_group', 'setting_key', 'setting_value'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    protected $validationRules = [
        'setting_group' => 'required|max_length[50]',
        'setting_key' => 'required|max_length[100]',
    ];

    private $cache = [];

    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    private function ensureTableExists()
    {
        if (!$this->db->tableExists($this->table)) {
            // Buat tabel jika belum ada
            $forge = \Config\Database::forge();
            
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'setting_group' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                ],
                'setting_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ],
                'setting_value' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $forge->addKey('id', true);
            $forge->addKey(['setting_group', 'setting_key']);
            $forge->createTable($this->table);

            // Tambahkan data default
            $this->insertBatch([
                [
                    'setting_group' => 'company',
                    'setting_key' => 'name',
                    'setting_value' => 'Nama Perusahaan Anda',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'setting_group' => 'company',
                    'setting_key' => 'address',
                    'setting_value' => 'Alamat Perusahaan Anda',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'setting_group' => 'company',
                    'setting_key' => 'phone',
                    'setting_value' => 'Nomor Telepon Perusahaan',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'setting_group' => 'company',
                    'setting_key' => 'email',
                    'setting_value' => 'email@perusahaan.com',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }
    }

    /**
     * Get setting value by key
     */
    public function get($key, $group = 'company')
    {
        $cacheKey = "{$group}.{$key}";

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $setting = $this->where('setting_key', $key)
                       ->where('setting_group', $group)
                       ->first();

        $value = $setting['setting_value'] ?? null;
        $this->cache[$cacheKey] = $value;

        // Debug log
        log_message('debug', 'SettingModel::get - Key: ' . $key . ', Group: ' . $group . ', Result: ' . json_encode($setting));

        return $value;
    }

    /**
     * Get all settings by group
     */
    public function getGroup($group)
    {
        $settings = $this->where('setting_group', $group)->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
            $this->cache["{$group}.{$setting['setting_key']}"] = $setting['setting_value'];
        }

        return $result;
    }

    /**
     * Set setting value
     */
    public function setSetting($key, $value, $group = 'company')
    {
        $setting = $this->where('setting_key', $key)
                       ->where('setting_group', $group)
                       ->first();

        $data = [
            'setting_group' => $group,
            'setting_key' => $key,
            'setting_value' => $value,
        ];

        if ($setting) {
            $this->update($setting['id'], $data);
        } else {
            $this->insert($data);
        }

        $this->cache["{$group}.{$key}"] = $value;
        return true;
    }

    /**
     * Set multiple settings
     */
    public function setMany(array $settings, $group = 'company')
    {
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value, $group);
        }

        return true;
    }

    /**
     * Get all settings with structure
     */
    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            if (!isset($result[$setting['setting_group']])) {
                $result[$setting['setting_group']] = [];
            }
            $result[$setting['setting_group']][$setting['setting_key']] = [
                'value' => $setting['setting_value']
            ];
        }

        return $result;
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        $this->cache = [];
    }
} 