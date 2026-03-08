<?php
require 'db.php';

$username = "admin";
$password = "admin123";
$role = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hash, $role);

if ($stmt->execute()) {
    echo "Admin created successfully!<br>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b><br>";
} else {
    echo "Error: " . $stmt->error;
}
?>