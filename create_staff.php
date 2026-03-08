<?php
require 'db.php';

$username = "staff";
$password = "staff123";
$role = "staff";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hash, $role);

if ($stmt->execute()) {
    echo "Staff user created successfully!<br>";
    echo "Username: <b>staff</b><br>";
    echo "Password: <b>staff123</b><br>";
} else {
    echo "Error: " . $stmt->error;
}
?>