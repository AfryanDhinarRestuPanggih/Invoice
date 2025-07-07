<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_group' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
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
        
        $this->forge->addKey('id', true);
        $this->forge->addKey(['setting_group', 'setting_key']);
        $this->forge->createTable('settings');

        // Menambahkan data awal untuk pengaturan perusahaan
        $data = [
            [
                'setting_group' => 'company',
                'setting_key'   => 'name',
                'setting_value' => 'Nama Perusahaan Anda',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'company',
                'setting_key'   => 'address',
                'setting_value' => 'Alamat Perusahaan Anda',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'company',
                'setting_key'   => 'phone',
                'setting_value' => 'Nomor Telepon Perusahaan',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'setting_group' => 'company',
                'setting_key'   => 'email',
                'setting_value' => 'email@perusahaan.com',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
} 