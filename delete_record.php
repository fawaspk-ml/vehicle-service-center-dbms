<?php
require 'auth.php';
include 'db.php';

/* ONLY ADMIN CAN DELETE */
if($_SESSION['role'] !== 'admin'){
    header("Location: view_records.php");
    exit;
}

/* VALIDATE ID */
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: view_records.php");
    exit;
}

$id = (int)$_GET['id'];

/* DELETE RECORD SAFELY */
$stmt = $conn->prepare("DELETE FROM service_records WHERE record_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: view_records.php");
exit;