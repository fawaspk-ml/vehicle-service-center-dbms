<?php
$conn = mysqli_connect("localhost", "root", "", "vehicle_service_db");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>