<?php
require 'auth.php';
include 'db.php';

if(isset($_POST['submit'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (!preg_match("/^[A-Za-z.\s]+$/", $name)) {
        $msg = "Name should contain only letters, spaces, and dots.";
        $msg_color = "#ff8c8c";
    } elseif (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
        $msg = "Enter a valid Indian phone number (10 digits, starts with 6-9).";
        $msg_color = "#ff8c8c";
    } elseif (strlen($address) < 5) {
        $msg = "Address is too short.";
        $msg_color = "#ff8c8c";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (name, phone, address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $address);

        if($stmt->execute()){
            $msg = "Customer added successfully!";
            $msg_color = "#7CFC98";
        } else {
            $msg = "Error: " . $stmt->error;
            $msg_color = "#ff8c8c";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Customer</title>

<style>
body{
    margin:0;
    font-family: Arial, Helvetica, sans-serif;
    background:#111;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.form-box{
    width:420px;
    background:#1c1c1c;
    padding:30px;
    border-radius:6px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

input[type=text]{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:none;
    outline:none;
    background:#2a2a2a;
    color:#fff;
}

.btn-row{
    display:flex;
    gap:10px;
}

input[type=submit]{
    flex:1;
    padding:12px;
    background:#f5c542;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

.next-btn{
    flex:1;
    padding:12px;
    background:#4da6ff;
    border:none;
    font-weight:bold;
    cursor:pointer;
    text-align:center;
    text-decoration:none;
    color:#000;
}

.back{
    text-align:center;
    margin-top:15px;
}

.back a{
    color:#f5c542;
    text-decoration:none;
}

.msg{
    text-align:center;
    margin-bottom:10px;
}

.note{
    font-size:12px;
    color:#bbb;
    margin-top:-8px;
    margin-bottom:12px;
}
</style>
</head>

<body>

<div class="form-box">

    <h2>Add Customer</h2>

    <?php if(isset($msg)) echo "<p class='msg' style='color:$msg_color;'>$msg</p>"; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Customer Name" required
               pattern="[A-Za-z.\s]+"
               title="Only letters, spaces, and dots allowed">

        <input type="text" name="phone" placeholder="Phone Number" required
               maxlength="10"
               pattern="[6-9][0-9]{9}"
               title="Enter a valid 10-digit Indian phone number">

        <div class="note">Phone must be 10 digits and start with 6, 7, 8, or 9.</div>

        <input type="text" name="address" placeholder="Address" required>

        <div class="btn-row">
            <input type="submit" name="submit" value="Add Customer">
            <a href="add_vehicle.php" class="next-btn">Next →</a>
        </div>
    </form>

    <div class="back">
        <a href="index.php">⬅ Back to Home</a>
    </div>

</div>

</body>
</html>