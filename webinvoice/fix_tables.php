<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'webinvoice';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Disable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop existing tables if they exist
    $conn->exec("DROP TABLE IF EXISTS invoice_items");
    $conn->exec("DROP TABLE IF EXISTS invoices");
    echo "Existing tables dropped successfully\n";
    
    // Create invoices table
    $sql = "CREATE TABLE invoices (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $conn->exec($sql);
    echo "Table 'invoices' created successfully\n";
    
    // Create invoice_items table
    $sql = "CREATE TABLE invoice_items (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $conn->exec($sql);
    echo "Table 'invoice_items' created successfully\n";
    
    // Enable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Verify table structure
    $result = $conn->query("DESCRIBE invoices");
    echo "\nInvoices table structure:\n";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // Re-enable foreign key checks in case of error
    try {
        $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    } catch(Exception $e2) {
        // Ignore any errors here
    }
}

$conn = null; 