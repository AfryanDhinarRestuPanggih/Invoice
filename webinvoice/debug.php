<?php
// Koneksi ke database
$db = new mysqli('localhost', 'root', '', 'webinvoice');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Tampilkan semua user
$result = $db->query('SELECT id, name, email, password, role, status, created_at FROM users');

echo "<h2>Daftar User Terdaftar:</h2>";
echo "<pre>";
while ($row = $result->fetch_assoc()) {
    print_r($row);
    echo "\n----------------------------------------\n";
}
echo "</pre>";

// Test insert user baru
$name = "Test Debug User";
$email = "debug@test.com";
$password = password_hash("password123", PASSWORD_DEFAULT);
$role = "user";
$status = "active";
$created_at = date('Y-m-d H:i:s');

$stmt = $db->prepare("INSERT INTO users (name, email, password, role, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $email, $password, $role, $status, $created_at, $created_at);

echo "<h2>Mencoba insert user baru:</h2>";
if ($stmt->execute()) {
    echo "Berhasil menambahkan user baru<br>";
    
    // Test login dengan user baru
    $test_result = $db->query("SELECT * FROM users WHERE email = 'debug@test.com'");
    $test_user = $test_result->fetch_assoc();
    
    echo "<br>Test verifikasi password:<br>";
    if (password_verify("password123", $test_user['password'])) {
        echo "Password verification SUCCESS!";
    } else {
        echo "Password verification FAILED!";
    }
} else {
    echo "Error: " . $stmt->error;
}

$db->close(); 