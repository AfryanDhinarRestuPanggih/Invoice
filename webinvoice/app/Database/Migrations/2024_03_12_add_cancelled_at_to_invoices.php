<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCancelledAtToInvoices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('invoices', [
            'cancelled_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'paid_at'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('invoices', 'cancelled_at');
    }
} 