<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // Delete existing company settings
        $this->db->table('settings')->where('setting_group', 'company')->delete();

        // Insert new company settings
        $data = [
            [
                'setting_group' => 'company',
                'setting_key' => 'name',
                'setting_value' => 'PT. Succes Indonesia Abadi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_group' => 'company',
                'setting_key' => 'address',
                'setting_value' => 'Jl. Mh Thamrin No.1 Kab.Bekasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_group' => 'company',
                'setting_key' => 'email',
                'setting_value' => 'abadisukses1@gmail.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'setting_group' => 'company',
                'setting_key' => 'phone',
                'setting_value' => '021-1231101',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('settings')->insertBatch($data);
    }
} 