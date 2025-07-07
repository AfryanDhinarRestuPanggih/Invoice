<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'setting_group' => 'company',
                'setting_key' => 'name',
                'setting_value' => 'PT. Indo Succes Abadi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'company',
                'setting_key' => 'address',
                'setting_value' => 'Jl. Mh Thamrin No.1, Bekasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'company',
                'setting_key' => 'phone',
                'setting_value' => '+021 899089',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'company',
                'setting_key' => 'email',
                'setting_value' => 'IndoSucces@gmail.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'payment',
                'setting_key' => 'bank_name',
                'setting_value' => 'Bank BCA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'payment',
                'setting_key' => 'bank_account',
                'setting_value' => '1234567890',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'payment',
                'setting_key' => 'bank_account_name',
                'setting_value' => 'PT. Indo Succes Abadi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }
} 