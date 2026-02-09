<?php
include "../includes/auth.php";
include "../db.php";
$conn = dbconnect();

$user_id = $_SESSION['user_id'];

/* ===== FIND USER FLAT ===== */
$flat = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT flat_id FROM flat_owners WHERE id='$user_id'
"));

$flat_id = $flat['flat_id'];


/* ===== STATS ===== */
$sql = "
SELECT 
SUM(amount) as total,
SUM(due_amount) as due
FROM maintenance_bills
WHERE flat_id='$flat_id'
";

$data = mysqli_fetch_assoc(mysqli_query($conn,$sql));

$total = $data['total'] ?? 0;
$due   = $data['due'] ?? 0;
$paid  = $total - $due;


/* ===== RECENT ===== */
$recent = mysqli_query($conn,"
SELECT *
FROM maintenance_payments
WHERE flat_id='$flat_id'
ORDER BY payment_date DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<a href="dashboard.php" class="active">
<i class="ri-dashboard-fill"></i> Dashboard
</a>

<a href="pay_maintenance.php">
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

<h1>Your Dashboard</h1>

<div class="cards">

<div class="card">
<h3>Total Bill</h3>
<p>₹<?= number_format($total) ?></p>
</div>

<div class="card">
<h3>Paid</h3>
<p style="color:green">
₹<?= number_format($paid) ?>
</p>
</div>

<div class="card">
<h3>Due</h3>
<p style="color:red">
₹<?= number_format($due) ?>
</p>
</div>

</div>


<div class="dashboard-charts">
<div class="chart-box">
<h3>Overview</h3>
<canvas id="chart"></canvas>
</div>
</div>


<h3>Recent Payments</h3>

<table>
<tr>
<th>Date</th>
<th>Amount</th>
<th>Mode</th>
</tr>

<?php while($r=mysqli_fetch_assoc($recent)){ ?>

<tr>
<td><?= $r['payment_date'] ?></td>
<td>₹<?= $r['amount'] ?></td>
<td><?= $r['mode'] ?></td>
</tr>

<?php } ?>

</table>

</div>
</div>


<script>
new Chart(chart,{
type:'pie',
data:{
labels:['Paid','Due'],
datasets:[{
data:[<?= $paid ?>,<?= $due ?>],
backgroundColor:['#1F4842','#D32F2F']
}]
}
});
</script>

</body>
</html>
