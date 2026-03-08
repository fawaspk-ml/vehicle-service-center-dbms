<?php 
require 'auth.php'; 
include 'db.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: view_records.php");
    exit;
}

$id = (int)$_GET['id'];
$msg_color = "#ff8c8c";

/* FETCH RECORD WITH ALL DETAILS */
$stmt = $conn->prepare("SELECT 
            sr.record_id,
            sr.service_date,

            c.customer_id,
            c.name,
            c.phone,
            c.address,

            v.vehicle_id,
            v.vehicle_number,
            v.brand,
            v.model,

            s.service_id

          FROM service_records sr
          JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
          JOIN customers c ON v.customer_id = c.customer_id
          JOIN services s ON sr.service_id = s.service_id
          WHERE sr.record_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if(!$data){
    header("Location: view_records.php");
    exit;
}

/* FETCH SERVICES FOR DROPDOWN */
$services = mysqli_query($conn, "SELECT * FROM services");

/* UPDATE DATA */
if(isset($_POST['update'])){

    // CUSTOMER
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // VEHICLE
    $vehicle_number = strtoupper(trim($_POST['vehicle_number']));
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);

    // SERVICE RECORD
    $service_id = trim($_POST['service_id']);
    $service_date = trim($_POST['service_date']);

    $vehicle_pattern = "/^([A-Z]{2}\s?\d{1,2}\s?[A-Z]{1,3}\s?\d{1,4}|[0-9]{2}\s?BH\s?\d{4}\s?[A-Z]{1,2})$/";

    if (!preg_match("/^[A-Za-z.\s]+$/", $name)) {
        $msg = "Customer name should contain only letters, spaces, and dots.";
    }
    elseif (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
        $msg = "Enter a valid Indian phone number (10 digits, starts with 6-9).";
    }
    elseif (strlen($address) < 5) {
        $msg = "Address is too short.";
    }
    elseif (!preg_match($vehicle_pattern, $vehicle_number)) {
        $msg = "Enter a valid Indian vehicle number. Example: KL08AB1234 or 22 BH 1234 AA";
    }
    elseif (!preg_match("/^[A-Za-z0-9\s.-]+$/", $brand)) {
        $msg = "Brand contains invalid characters.";
    }
    elseif (!preg_match("/^[A-Za-z0-9\s.-]+$/", $model)) {
        $msg = "Model contains invalid characters.";
    }
    elseif (empty($service_id) || !is_numeric($service_id)) {
        $msg = "Please select a valid service.";
    }
    elseif (empty($service_date)) {
        $msg = "Please select a valid service date.";
    }
    else {
        // Check duplicate vehicle number for other vehicles
        $check = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE vehicle_number = ? AND vehicle_id != ?");
        $check->bind_param("si", $vehicle_number, $data['vehicle_id']);
        $check->execute();
        $check_result = $check->get_result();

        if($check_result->num_rows > 0){
            $msg = "This vehicle number already exists for another vehicle.";
        } else {
            // UPDATE CUSTOMER
            $custStmt = $conn->prepare("UPDATE customers SET name = ?, phone = ?, address = ? WHERE customer_id = ?");
            $custStmt->bind_param("sssi", $name, $phone, $address, $data['customer_id']);
            $custOk = $custStmt->execute();

            // UPDATE VEHICLE
            $vehStmt = $conn->prepare("UPDATE vehicles SET vehicle_number = ?, brand = ?, model = ? WHERE vehicle_id = ?");
            $vehStmt->bind_param("sssi", $vehicle_number, $brand, $model, $data['vehicle_id']);
            $vehOk = $vehStmt->execute();

            // UPDATE SERVICE RECORD
            $recStmt = $conn->prepare("UPDATE service_records SET service_id = ?, service_date = ? WHERE record_id = ?");
            $recStmt->bind_param("isi", $service_id, $service_date, $id);
            $recOk = $recStmt->execute();

            if($custOk && $vehOk && $recOk){
                $msg = "Record updated successfully!";
                $msg_color = "#7CFC98";

                // Refresh displayed data
                $data['name'] = $name;
                $data['phone'] = $phone;
                $data['address'] = $address;
                $data['vehicle_number'] = $vehicle_number;
                $data['brand'] = $brand;
                $data['model'] = $model;
                $data['service_id'] = $service_id;
                $data['service_date'] = $service_date;
            } else {
                $msg = "Error while updating record.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Full Service Record</title>

<style>
body{
    margin:0;
    background:#111;
    color:#fff;
    font-family:Arial, Helvetica, sans-serif;
    padding:40px;
}

.form-box{
    max-width:600px;
    margin:auto;
    background:#1c1c1c;
    padding:25px;
    border-radius:6px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

label{
    font-size:14px;
    display:block;
    margin-top:12px;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:6px;
    border:none;
    outline:none;
    background:#2a2a2a;
    color:#fff;
}

button{
    margin-top:20px;
    width:100%;
    padding:12px;
    background:#f5c542;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}

.msg{
    text-align:center;
    margin-bottom:15px;
}

.note{
    font-size:12px;
    color:#bbb;
    margin-top:5px;
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
<h2>Edit Service Record</h2>

<?php if(isset($msg)) echo "<p class='msg' style='color:$msg_color;'>$msg</p>"; ?>

<form method="post">

<label>Customer Name</label>
<input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required
       pattern="[A-Za-z.\s]+"
       title="Only letters, spaces, and dots allowed">

<label>Phone</label>
<input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>" required
       maxlength="10"
       pattern="[6-9][0-9]{9}"
       title="Enter a valid 10-digit Indian phone number">

<div class="note">Phone must be 10 digits and start with 6, 7, 8, or 9.</div>

<label>Address</label>
<input type="text" name="address" value="<?php echo htmlspecialchars($data['address']); ?>" required>

<label>Vehicle Number</label>
<input type="text" name="vehicle_number" value="<?php echo htmlspecialchars($data['vehicle_number']); ?>" required
       style="text-transform:uppercase;"
       title="Example: KL08AB1234 or 22 BH 1234 AA">

<div class="note">Example: KL08AB1234, KL 08 AB 1234, 22 BH 1234 AA</div>

<label>Brand</label>
<input type="text" name="brand" value="<?php echo htmlspecialchars($data['brand']); ?>" required>

<label>Model</label>
<input type="text" name="model" value="<?php echo htmlspecialchars($data['model']); ?>" required>

<label>Service</label>
<select name="service_id" required>
<?php while($s = mysqli_fetch_assoc($services)){ ?>
<option value="<?php echo $s['service_id']; ?>"
<?php if($s['service_id']==$data['service_id']) echo "selected"; ?>>
<?php echo htmlspecialchars($s['service_name'])." - ₹".$s['service_cost']; ?>
</option>
<?php } ?>
</select>

<label>Service Date</label>
<input type="date" name="service_date" value="<?php echo htmlspecialchars($data['service_date']); ?>" required>

<button type="submit" name="update">Update All Details</button>
</form>

<div class="back">
    <a href="view_records.php">⬅ Back</a>
</div>

</div>

</body>
</html>