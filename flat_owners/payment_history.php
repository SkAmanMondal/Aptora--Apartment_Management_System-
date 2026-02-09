<?php
include "../includes/auth.php";
include "../db.php";
$conn = dbconnect();

$user_id = $_SESSION['user_id'];


/* ===== CORRECT QUERY FOR YOUR DB ===== */

$sql = "
SELECT 
    p.*, 
    b.month,
    b.year,
    f.flat_no

FROM maintenance_payments p

JOIN maintenance_bills b 
    ON p.bill_id = b.id

JOIN flat_owners o 
    ON b.flat_id = o.flat_id

JOIN flats f 
    ON b.flat_id = f.id

WHERE o.id = '$user_id'

ORDER BY p.payment_date DESC
";

$his = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>

<title>Payment History</title>

<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body>


<div class="navbar">
<div class="logo">
<img src="../assets/icon4.png">
</div>

<a href="../logout.php" class="logout-btn">Logout</a>
</div>


<div class="container">

<div class="sidebar">

<a href="dashboard.php">
<i class="ri-dashboard-fill"></i> Dashboard
</a>

<a href="pay_maintenance.php">
<i class="ri-hand-coin-fill"></i> Pay Maintenance
</a>

<a href="payment_history.php" class="active">
<i class="ri-history-fill"></i> History
</a>

<a href="profile.php">
<i class="ri-user-fill"></i> Profile
</a>

</div>


<div class="content">

<h1>Payment History</h1>

<table>

<tr>
<th>Flat</th>
<th>Month</th>
<th>Amount</th>
<th>Date</th>
<th>Mode</th>
</tr>

<?php while($r = mysqli_fetch_assoc($his)){ ?>

<tr>

<td><?= $r['flat_no'] ?></td>

<td><?= $r['month']."/".$r['year'] ?></td>

<td>₹<?= $r['amount'] ?></td>

<td><?= $r['payment_date'] ?></td>

<td><?= $r['mode'] ?></td>

</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>
