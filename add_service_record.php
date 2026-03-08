<?php 
require 'auth.php'; 
include 'db.php';

// Fetch vehicles and services for dropdowns
$vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
$services = mysqli_query($conn, "SELECT * FROM services");

if(isset($_POST['submit'])){
    $vehicle_id = $_POST['vehicle_id'];
    $service_id = $_POST['service_id'];
    $service_date = $_POST['service_date'];

    $query = "INSERT INTO service_records (vehicle_id, service_id, service_date)
              VALUES ('$vehicle_id', '$service_id', '$service_date')";
    $result = mysqli_query($conn, $query);

    if($result){
        // ✅ Get last inserted record ID
        $new_id = mysqli_insert_id($conn);

        // ✅ Auto redirect to invoice page
        header("Location: invoice.php?record_id=" . $new_id);
        exit;
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Service Record</title>

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
    width:480px;
    background:#1c1c1c;
    padding:30px;
    border-radius:6px;
}

h2{
    text-align:center;
    margin-bottom:20px;
    letter-spacing:1px;
}

select,
input[type=date]{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:none;
    outline:none;
    background:#2a2a2a;
    color:#fff;
}

select option{
    background:#1c1c1c;
}

/* BUTTON ROW */
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
    color:#ff6b6b; /* error color */
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

    <h2>Add Service Record</h2>

    <?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <form method="post">

        <select name="vehicle_id" required>
            <option value="">Select Vehicle</option>
            <?php while($row = mysqli_fetch_assoc($vehicles)){ ?>
                <option value="<?php echo $row['vehicle_id']; ?>">
                    <?php echo $row['vehicle_number']." (".$row['brand']." ".$row['model'].")"; ?>
                </option>
            <?php } ?>
        </select>

        <select name="service_id" required>
            <option value="">Select Service</option>
            <?php while($row = mysqli_fetch_assoc($services)){ ?>
                <option value="<?php echo $row['service_id']; ?>">
                    <?php echo $row['service_name']." - ₹".$row['service_cost']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="date" name="service_date" required>

        <div class="btn-row">
            <input type="submit" name="submit" value="Add Record">
            <a href="view_records.php" class="next-btn">Next →</a>
        </div>

    </form>

    <div class="back">
        <a href="index.php">⬅ Back to Home</a>
    </div>

</div>

</body>
</html>