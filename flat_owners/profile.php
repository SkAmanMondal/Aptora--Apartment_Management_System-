<?php
include "../includes/auth.php";
include "../db.php";
$conn=dbconnect();

$user_id=$_SESSION['user_id'];

$user=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT o.*, f.flat_no, f.flat_type
FROM flat_owners o
JOIN flats f ON o.flat_id=f.id
WHERE o.id='$user_id'
"));
?>

<!DOCTYPE html>
<html>
<head>

<title>Profile</title>

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

<a href="payment_history.php">
<i class="ri-history-fill"></i> History
</a>

<a href="profile.php" class="active">
<i class="ri-user-fill"></i> Profile
</a>

</div>


<div class="content">

<h1>My Profile</h1>

<div class="info-box">

<p><b>Name:</b> <?= $user['name'] ?></p>

<p><b>Email:</b> <?= $user['email'] ?></p>

<p><b>Phone:</b> <?= $user['phone'] ?></p>

<p><b>Flat No:</b> <?= $user['flat_no'] ?></p>

<p><b>Flat Type:</b> <?= $user['flat_type'] ?></p>

</div>

</div>
</div>

</body>
</html>
