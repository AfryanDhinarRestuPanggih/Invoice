<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateCustomersTable extends Seeder
{
    public function run()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `customers` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL UNIQUE,
                `phone` VARCHAR(20) NULL,
                `address` TEXT NULL,
                `company_name` VARCHAR(100) NULL,
                `tax_number` VARCHAR(50) NULL,
                `notes` TEXT NULL,
                `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
                `created_at` DATETIME NULL,
                `updated_at` DATETIME NULL,
                `deleted_at` DATETIME NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }
} 