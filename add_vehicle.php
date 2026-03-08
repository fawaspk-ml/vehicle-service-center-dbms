<?php 
require 'auth.php';
include 'db.php';

if(isset($_POST['submit'])){
    $customer_id = trim($_POST['customer_id']);
    $vehicle_number = strtoupper(trim($_POST['vehicle_number']));
    $vehicle_type = trim($_POST['vehicle_type']);
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);

    $msg_color = "#ff8c8c";

    // Common Indian vehicle number formats
    $vehicle_pattern = "/^([A-Z]{2}\s?\d{1,2}\s?[A-Z]{1,3}\s?\d{1,4}|[0-9]{2}\s?BH\s?\d{4}\s?[A-Z]{1,2})$/";

    if (!preg_match($vehicle_pattern, $vehicle_number)) {
        $msg = "Enter a valid Indian vehicle number. Example: KL08AB1234 or 22 BH 1234 AA";
    }
    elseif (!preg_match("/^[A-Za-z\s]+$/", $vehicle_type)) {
        $msg = "Vehicle type should contain only letters and spaces.";
    }
    elseif (!preg_match("/^[A-Za-z0-9\s.-]+$/", $brand)) {
        $msg = "Brand contains invalid characters.";
    }
    elseif (!preg_match("/^[A-Za-z0-9\s.-]+$/", $model)) {
        $msg = "Model contains invalid characters.";
    }
    else {
        // Check duplicate vehicle number
        $check = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE vehicle_number = ?");
        $check->bind_param("s", $vehicle_number);
        $check->execute();
        $check_result = $check->get_result();

        if($check_result->num_rows > 0){
            $msg = "This vehicle number already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO vehicles (customer_id, vehicle_number, vehicle_type, brand, model) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $customer_id, $vehicle_number, $vehicle_type, $brand, $model);

            if($stmt->execute()){
                $msg = "Vehicle added successfully!";
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
<title>Add Vehicle</title>

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
    width:460px;
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
input[type=text]{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:none;
    outline:none;
    background:#2a2a2a;
    color:#fff;
}

select option{
    background:#111;
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

    <h2>Add Vehicle</h2>

    <?php if(isset($msg)) echo "<div class='msg' style='color:$msg_color;'>$msg</div>"; ?>

    <form method="post">

        <select name="customer_id" required>
            <option value="">Select Customer</option>
            <?php 
            $customers = mysqli_query($conn, "SELECT * FROM customers");
            while($row = mysqli_fetch_assoc($customers)){ ?>
                <option value="<?php echo $row['customer_id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="text" name="vehicle_number" placeholder="Vehicle Number" required
               style="text-transform:uppercase;"
               title="Example: KL08AB1234 or 22 BH 1234 AA">

        <div class="note">Example: KL08AB1234, KL 08 AB 1234, 22 BH 1234 AA</div>

        <input type="text" name="vehicle_type" placeholder="Vehicle Type" required
               pattern="[A-Za-z\s]+"
               title="Only letters and spaces allowed">

        <input type="text" name="brand" placeholder="Brand" required
               title="Letters, numbers, spaces, dot and hyphen allowed">

        <input type="text" name="model" placeholder="Model" required
               title="Letters, numbers, spaces, dot and hyphen allowed">

        <div class="btn-row">
            <input type="submit" name="submit" value="Add Vehicle">
            <a href="add_service.php" class="next-btn">Next →</a>
        </div>
    </form>

    <div class="back">
        <a href="index.php">⬅ Back to Home</a>
    </div>

</div>

</body>
</html>