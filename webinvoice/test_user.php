<?php
$db = new mysqli('localhost', 'root', '', 'webinvoice');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Data user test
$email = 'udin@gmail.com';
$password = 'password123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Hapus user lama jika ada
$db->query("DELETE FROM users WHERE email = '$email'");

// Buat user baru
$sql = "INSERT INTO users (name, email, password, role, status, created_at, updated_at) 
        VALUES ('Udin Test', '$email', '$hashedPassword', 'user', 'active', NOW(), NOW())";

if ($db->query($sql)) {
    echo "<h2>User berhasil dibuat:</h2>";
    echo "Email: $email<br>";
    echo "Password: $password<br><br>";
    
    // Verifikasi data
    $result = $db->query("SELECT * FROM users WHERE email = '$email'");
    $user = $result->fetch_assoc();
    
    echo "<h2>Verifikasi:</h2>";
    echo "User ID: " . $user['id'] . "<br>";
    echo "Password Hash: " . $user['password'] . "<br>";
    echo "Hash Length: " . strlen($user['password']) . "<br>";
    echo "Password Verify: " . (password_verify($password, $user['password']) ? 'SUCCESS' : 'FAILED') . "<br>";
} else {
    echo "Error creating user: " . $db->error;
}

$db->close(); 