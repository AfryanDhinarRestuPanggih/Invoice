<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ResetUsers extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:reset-users';
    protected $description = 'Reset users table and add default admin user';

    public function run(array $params)
    {
        try {
            $db = \Config\Database::connect();
            
            // Drop table if exists
            $db->query('DROP TABLE IF EXISTS users');
            
            // Create table
            $db->query('CREATE TABLE users (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM("admin", "user") NOT NULL DEFAULT "user",
                status ENUM("active", "inactive") NOT NULL DEFAULT "active",
                last_login DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                deleted_at DATETIME NULL,
                CONSTRAINT pk_users PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci');
            
            // Insert default admin
            $db->query('INSERT INTO users (name, email, password, role, status, created_at, updated_at)
                VALUES (
                    "Administrator",
                    "admin@admin.com",
                    "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
                    "admin",
                    "active",
                    NOW(),
                    NOW()
                )');
            
            CLI::write('Users table has been reset successfully', 'green');
        } catch (\Exception $e) {
            CLI::error($e->getMessage());
        }
    }
} 