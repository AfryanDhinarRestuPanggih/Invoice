<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'after'      => 'role'
            ],
            'last_login' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'after'   => 'status'
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['status', 'last_login']);
    }
} 