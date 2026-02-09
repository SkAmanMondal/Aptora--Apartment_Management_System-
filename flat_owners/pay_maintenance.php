<?php
include "../includes/auth.php";
include "../db.php";
$conn = dbconnect();

$user_id = $_SESSION['user_id'];

/* ===== FIND USER FLAT ===== */

$sql = "
SELECT b.*, f.flat_no
FROM maintenance_bills b

JOIN flat_owners o 
    ON b.flat_id = o.flat_id

JOIN flats f 
    ON b.flat_id = f.id

WHERE o.id = '$user_id'
AND b.status != 'paid'
";


$res = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Pay Maintenance</title>

<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
<div class="logo">
<img src="../assets/icon4.png">
</div>

<a href="../logout.php" class="logout-btn">Logout</a>
</div>


<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">

<a href="dashboard.php">
<i class="ri-dashboard-fill"></i> Dashboard
</a>

<a href="pay_maintenance.php" class="active">
<i class="ri-hand-coin-fill"></i> Pay Maintenance
</a>

<a href="payment_history.php">
<i class="ri-history-fill"></i> History
</a>

<a href="profile.php">
<i class="ri-user-fill"></i> Profile
</a>

</div>


<div class="content">

<h1>Your Dues</h1>

<table>

<tr>
<th>Flat</th>
<th>Month</th>
<th>Due</th>
<th>Pay</th>
</tr>

<?php while($r=mysqli_fetch_assoc($res)){ ?>

<tr>

<td><?= $r['flat_no'] ?></td>

<td><?= $r['month']."/".$r['year'] ?></td>

<td>₹<?= $r['due_amount'] ?></td>

<td>

<form action="pay_now.php" method="post">

<input type="hidden" name="bill_id" value="<?= $r['id'] ?>">

<input type="number"
name="amount"
max="<?= $r['due_amount'] ?>"
required>

<button class="btn">
Pay
</button>

</form>

</td>

</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>
