<?php
$db = new mysqli('localhost', 'root', '', 'webinvoice');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Cek user dengan email spesifik
$email = 'udin@gmail.com';
$result = $db->query("SELECT id, name, email, password, role, status, created_at FROM users WHERE email = '$email'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<h2>Data User:</h2>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
} else {
    echo "User dengan email $email tidak ditemukan";
}

// Tampilkan semua user untuk perbandingan
echo "<h2>Semua User:</h2>";
$all_users = $db->query("SELECT id, name, email, role, status, created_at FROM users");
echo "<pre>";
while ($row = $all_users->fetch_assoc()) {
    print_r($row);
    echo "\n----------------------------------------\n";
}
echo "</pre>";

$db->close(); 