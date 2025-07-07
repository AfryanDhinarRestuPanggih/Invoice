<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'webinvoice';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create invoices table
    $sql = "CREATE TABLE IF NOT EXISTS invoices (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        invoice_number VARCHAR(50) NOT NULL UNIQUE,
        user_id INT(11) UNSIGNED NOT NULL,
        customer_name VARCHAR(100) NOT NULL,
        customer_email VARCHAR(100) NOT NULL,
        customer_phone VARCHAR(20),
        customer_address TEXT,
        date DATE NOT NULL DEFAULT CURRENT_DATE,
        due_date DATE,
        status ENUM('draft', 'sent', 'paid', 'cancelled') DEFAULT 'draft',
        total_amount DECIMAL(10,2) DEFAULT 0.00,
        notes TEXT,
        created_at DATETIME,
        updated_at DATETIME,
        deleted_at DATETIME,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";
    
    $conn->exec($sql);
    echo "Table 'invoices' created successfully\n";
    
    // Create invoice_items table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS invoice_items (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        invoice_id INT(11) UNSIGNED NOT NULL,
        product_id INT(11) UNSIGNED NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        created_at DATETIME,
        updated_at DATETIME,
        deleted_at DATETIME,
        PRIMARY KEY (id),
        FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";
    
    $conn->exec($sql);
    echo "Table 'invoice_items' created successfully\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$conn = null; 