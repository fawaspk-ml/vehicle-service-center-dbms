<?php 
require 'auth.php'; 
include 'db.php';

// Get customer ID safely
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$msg_color = "#ff8c8c";

// Fetch existing customer
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if(!$customer){
    header("Location: index.php");
    exit;
}

// Update customer
if(isset($_POST['update'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (!preg_match("/^[A-Za-z.\s]+$/", $name)) {
        $msg = "Name should contain only letters, spaces, and dots.";
    } 
    elseif (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
        $msg = "Enter a valid Indian phone number (10 digits, starts with 6-9).";
    } 
    elseif (strlen($address) < 5) {
        $msg = "Address is too short.";
    } 
    else {
        $updateStmt = $conn->prepare("UPDATE customers SET name = ?, phone = ?, address = ? WHERE customer_id = ?");
        $updateStmt->bind_param("sssi", $name, $phone, $address, $id);

        if($updateStmt->execute()){
            $msg = "Customer updated successfully!";
            $msg_color = "#7CFC98";

            // Refresh displayed values
            $customer['name'] = $name;
            $customer['phone'] = $phone;
            $customer['address'] = $address;
        } else {
            $msg = "Error: " . $updateStmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Customer</title>

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

input[type=submit]{
    width:100%;
    padding:12px;
    background:#4da6ff;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

input[type=submit]:hover{
    background:#66b3ff;
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
    <h2>Edit Customer</h2>

    <?php if(isset($msg)) echo "<p class='msg' style='color:$msg_color;'>$msg</p>"; ?>

    <form method="post">
        <input type="text" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required
               pattern="[A-Za-z.\s]+"
               title="Only letters, spaces, and dots allowed">

        <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required
               maxlength="10"
               pattern="[6-9][0-9]{9}"
               title="Enter a valid 10-digit Indian phone number">

        <div class="note">Phone must be 10 digits and start with 6, 7, 8, or 9.</div>

        <input type="text" name="address" value="<?php echo htmlspecialchars($customer['address']); ?>" required>

        <input type="submit" name="update" value="Update Customer">
    </form>

    <div class="back">
        <a href="index.php">⬅ Back</a>
    </div>
</div>

</body>
</html>