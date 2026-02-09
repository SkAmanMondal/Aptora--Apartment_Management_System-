<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    header("Location: ../login.php");
    exit;
}

include "../db.php";
$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

/* ===== REAL STATISTICS ===== */

$sql = "SELECT 
SUM(amount) as total_amount,
SUM(due_amount) as total_due
FROM maintenance_bills
WHERE apartment_id='$apartment_id'";

$data = mysqli_fetch_assoc(mysqli_query($conn,$sql));

$total_expected  = $data['total_amount'] ?? 0;
$total_pending   = $data['total_due'] ?? 0;
$total_collected = $total_expected - $total_pending;


/* ===== FETCH LIST ===== */

$sql = "
SELECT f.flat_no, b.*
FROM maintenance_bills b
JOIN flats f ON b.flat_id=f.id
WHERE b.apartment_id='$apartment_id'
ORDER BY year DESC, month DESC";

$bills = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>

<title>Maintenance</title>

<link rel="icon" href="../assets/icon.png">
<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<!-- NAVBAR (YOUR ORIGINAL) -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo">
            <img src="../assets/icon4.png">
        </div>
    </div>

    <div class="nav-right">
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>


<div class="container">

<!-- SIDEBAR (WITH ICONS – UNTOUCHED) -->
<div class="sidebar">

    <a href="dashboard.php">
        <i class="ri-dashboard-fill"></i> Dashboard
    </a>

    <a href="manage_flats.php">
        <i class="ri-building-fill"></i> Flats
    </a>

    <a href="maintanance.php" class="active">
        <i class="ri-hand-coin-fill"></i> Maintenance
    </a>

    <a href="profile.php">
        <i class="ri-user-fill"></i> Profile
    </a>

</div>


<!-- ===== ONLY CONTENT CHANGED ===== -->

<div class="content">

<h1>Maintenance Management</h1>

<!-- CARDS -->
<div class="cards">

<div class="card">
<h3>Total Expected</h3>
<p>₹<?= number_format($total_expected) ?></p>
</div>

<div class="card">
<h3>Collected</h3>
<p style="color:green">
₹<?= number_format($total_collected) ?>
</p>
</div>

<div class="card">
<h3>Pending</h3>
<p style="color:red">
₹<?= number_format($total_pending) ?>
</p>
</div>

</div>


<!-- CHART -->
<div class="dashboard-charts">

<div class="chart-box">
<h3>Collection Status</h3>
<canvas id="pieChart"></canvas>
</div>

</div>


<a class="btn" href="generate_maintenance.php">
Generate This Month
</a>


<!-- TABLE -->
<table>

<tr>
<th>Flat</th>
<th>Month</th>
<th>Amount</th>
<th>Due</th>
<th>Status</th>
<th>Collect</th>
</tr>

<?php while($r=mysqli_fetch_assoc($bills)){ ?>

<tr>

<td><?= $r['flat_no'] ?></td>

<td><?= $r['month']."/".$r['year'] ?></td>

<td>₹<?= $r['amount'] ?></td>

<td>₹<?= $r['due_amount'] ?></td>

<td>
<span class="badge <?= $r['status']=='paid'?'green':'red' ?>">
<?= $r['status'] ?>
</span>
</td>

<td>

<?php if($r['status']!='paid'){ ?>

<form action="collect_payment.php" method="post">

<input type="hidden" name="bill_id" value="<?= $r['id'] ?>">
<input type="hidden" name="flat_id" value="<?= $r['flat_id'] ?>">

<input type="number" name="amount"
max="<?= $r['due_amount'] ?>" required>

<button class="btn">Collect</button>

</form>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

</div>
</div>


<script>
new Chart(pieChart, {
type:'pie',
data:{
labels:['Collected','Pending'],
datasets:[{
data:[
<?= $total_collected ?>,
<?= $total_pending ?>
],
backgroundColor:['#1F4842','#D32F2F']
}]
}
});
</script>

</body>
</html>
