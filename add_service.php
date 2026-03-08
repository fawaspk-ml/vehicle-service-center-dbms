<?php 
require 'auth.php';
include 'db.php';

if(isset($_POST['submit'])){
    $service_name = trim($_POST['service_name']);
    $service_cost = trim($_POST['service_cost']);

    $msg_color = "#ff8c8c";

    if (!preg_match("/^[A-Za-z0-9\s&().-]+$/", $service_name)) {
        $msg = "Service name contains invalid characters.";
    }
    elseif (!is_numeric($service_cost)) {
        $msg = "Service cost must be a number.";
    }
    elseif ($service_cost <= 0) {
        $msg = "Service cost must be greater than 0.";
    }
    else {
        // Check duplicate service name
        $check = $conn->prepare("SELECT service_id FROM services WHERE service_name = ?");
        $check->bind_param("s", $service_name);
        $check->execute();
        $check_result = $check->get_result();

        if($check_result->num_rows > 0){
            $msg = "This service already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO services (service_name, service_cost) VALUES (?, ?)");
            $stmt->bind_param("sd", $service_name, $service_cost);

            if($stmt->execute()){
                $msg = "Service added successfully!";
                $msg_color = "#7CFC98";
            } else {
                $msg = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Service</title>

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
    letter-spacing:1px;
}

input[type=text],
input[type=number]{
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

input[type=submit]:hover{
    background:#ffd966;
}

.msg{
    text-align:center;
    margin-bottom:15px;
}

.note{
    font-size:12px;
    color:#bbb;
    margin-top:-8px;
    margin-bottom:12px;
}

.back{
    text-align:center;
    margin-top:15px;
}

.back a{
    color:#f5c542;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="form-box">

    <h2>Add Service</h2>

    <?php if(isset($msg)) echo "<div class='msg' style='color:$msg_color;'>$msg</div>"; ?>

    <form method="post">
        <input type="text" name="service_name" placeholder="Service Name" required
               pattern="[A-Za-z0-9\s&().-]+"
               title="Letters, numbers, spaces, &, dot, hyphen and brackets allowed">

        <input type="number" name="service_cost" placeholder="Service Cost" required
               min="1" step="0.01">

        <div class="note">Cost must be greater than 0.</div>

        <div class="btn-row">
            <input type="submit" name="submit" value="Add Service">
            <a href="add_service_record.php" class="next-btn">Next →</a>
        </div>
    </form>

    <div class="back">
        <a href="index.php">⬅ Back to Home</a>
    </div>

</div>

</body>
</html>