<?php 
require 'auth.php'; 
include 'db.php';

/*
 RELATION:
 customers -> vehicles -> service_records -> services
*/

$search = trim($_GET['search'] ?? "");

$query = "SELECT 
            sr.record_id,
            sr.service_date,

            c.customer_id,
            c.name AS customer_name,
            c.phone,

            v.vehicle_id,
            v.vehicle_number,
            v.brand,
            v.model,

            s.service_id,
            s.service_name,
            s.service_cost

          FROM service_records sr
          JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
          JOIN customers c ON v.customer_id = c.customer_id
          JOIN services s ON sr.service_id = s.service_id";

if ($search !== "") {
    $query .= " WHERE 
                c.name LIKE ? OR
                c.phone LIKE ? OR
                v.vehicle_number LIKE ? OR
                v.brand LIKE ? OR
                v.model LIKE ? OR
                s.service_name LIKE ?";
    
    $stmt = $conn->prepare($query);
    $like = "%" . $search . "%";
    $stmt->bind_param("ssssss", $like, $like, $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>View Service Records</title>

<style>
body{
    margin:0;
    font-family: Arial, Helvetica, sans-serif;
    background:#111;
    color:#fff;
    padding:40px;
}

h2{
    text-align:center;
    margin-bottom:25px;
}

.table-box{
    max-width:1300px;
    margin:auto;
    background:#1c1c1c;
    padding:20px;
    border-radius:6px;
}

.search-box{
    max-width:1300px;
    margin:0 auto 20px auto;
    display:flex;
    gap:10px;
    justify-content:center;
    align-items:center;
}

.search-box input[type=text]{
    width:420px;
    padding:12px;
    border:none;
    outline:none;
    background:#1c1c1c;
    color:#fff;
    border-radius:6px;
}

.search-btn{
    padding:12px 20px;
    background:#f5c542;
    border:none;
    font-weight:bold;
    cursor:pointer;
    border-radius:6px;
}

.clear-btn{
    padding:12px 20px;
    background:#4da6ff;
    color:#000;
    text-decoration:none;
    font-weight:bold;
    border-radius:6px;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th{
    background:#222;
    padding:12px;
    border-bottom:2px solid #333;
}

td{
    padding:10px;
    border-bottom:1px solid #333;
    text-align:center;
}

tr:hover{
    background:#262626;
}

.cost{
    color:#f5c542;
    font-weight:bold;
}

.action-edit{
    color:#4da6ff;
    text-decoration:none;
    font-weight:600;
    margin-right:12px;
}

.action-delete{
    color:#ff4d4d;
    text-decoration:none;
    font-weight:600;
    margin-right:12px;
}

.action-invoice{
    color:#ffd700;
    text-decoration:none;
    font-weight:600;
}

.no-data{
    text-align:center;
    padding:20px;
    color:#bbb;
}

/* back button */
.back{
    text-align:center;
    margin-top:20px;
}

.back a{
    color:#f5c542;
    text-decoration:none;
    font-weight:600;
}
</style>
</head>

<body>

<h2>All Service Records</h2>

<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search by customer, phone, vehicle no, brand, model or service"
           value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="search-btn">Search</button>
    <a href="view_records.php" class="clear-btn">Clear</a>
</form>

<div class="table-box">
<table>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Vehicle No</th>
        <th>Brand</th>
        <th>Model</th>
        <th>Service</th>
        <th>Cost</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

<?php if(mysqli_num_rows($result) > 0){ ?>
    <?php while($row = mysqli_fetch_assoc($result)){ ?>
    <tr>
        <td><?php echo htmlspecialchars($row['record_id']); ?></td>
        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
        <td><?php echo htmlspecialchars($row['phone']); ?></td>
        <td><?php echo htmlspecialchars($row['vehicle_number']); ?></td>
        <td><?php echo htmlspecialchars($row['brand']); ?></td>
        <td><?php echo htmlspecialchars($row['model']); ?></td>
        <td><?php echo htmlspecialchars($row['service_name']); ?></td>
        <td class="cost">₹<?php echo htmlspecialchars($row['service_cost']); ?></td>
        <td><?php echo htmlspecialchars($row['service_date']); ?></td>

        <td>
            <a class="action-edit"
               href="edit_record.php?id=<?php echo $row['record_id']; ?>">
               ✏ Edit
            </a>

            <?php if($_SESSION['role'] === 'admin'){ ?>
            <a class="action-delete"
               href="delete_record.php?id=<?php echo $row['record_id']; ?>"
               onclick="return confirm('Delete this record?');">
               🗑 Delete
            </a>
            <?php } ?>

            <a class="action-invoice"
               href="invoice.php?record_id=<?php echo $row['record_id']; ?>">
               🧾 Invoice
            </a>
        </td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="10" class="no-data">No matching records found.</td>
    </tr>
<?php } ?>

</table>
</div>

<div class="back">
    <a href="index.php">⬅ Back to Home</a>
</div>

</body>
</html>