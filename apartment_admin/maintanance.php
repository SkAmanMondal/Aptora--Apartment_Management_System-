<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    header("Location: ../login.php");
    exit;
}

include "../db.php";
$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

/* TOTAL FLATS */
$q1 = mysqli_query($conn,
    "SELECT COUNT(*) AS total_flats 
     FROM flats 
     WHERE apartment_id='$apartment_id'"
);
$total_flats = mysqli_fetch_assoc($q1)['total_flats'];

/* TOTAL OWNERS */
$q2 = mysqli_query($conn,
    "SELECT COUNT(*) AS total_owners 
     FROM flat_owners 
     WHERE apartment_id='$apartment_id'"
);
$total_owners = mysqli_fetch_assoc($q2)['total_owners'];

/* MAINTENANCE TOTALS (FROM FLATS TABLE) */
$q3 = mysqli_query($conn,
    "SELECT 
        SUM(maintanance) AS total_expected,
        SUM(CASE WHEN maintanance = 0 THEN 0 ELSE maintanance END) AS total_due
     FROM flats 
     WHERE apartment_id='$apartment_id'"
);
$maintenance = mysqli_fetch_assoc($q3);

$total_expected = $maintenance['total_expected'] ?? 0;

/* For now: assume maintanance > 0 = pending */
$total_collected = 0;
$pending_amount  = $total_expected;

/* OWNER + FLAT + MAINTENANCE DETAILS */
$owners = mysqli_query($conn, "
    SELECT 
        fo.name AS owner_name,
        f.flat_no,
        f.flat_type,
        f.maintanance
    FROM flat_owners fo
    INNER JOIN flats f ON f.id = fo.flat_id
    WHERE fo.apartment_id = '$apartment_id'
");
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

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo"><img src="../assets/icon4.png"></div>
    </div>
    <div class="nav-right">
        <a href="../logout.php" class="logout-btn"
           onclick="return confirm('Logout?')">Logout</a>
    </div>
</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="dashboard.php"><i class="ri-dashboard-fill"></i> Dashboard</a>
    <a href="manage_flats.php"><i class="ri-building-fill"></i> Flats</a>
    <a href="maintenance.php" class="active"><i class="ri-hand-coin-fill"></i> Maintenance</a>
    <a href="profile.php"><i class="ri-user-fill"></i> Profile</a>
</div>

<!-- CONTENT -->
<div class="content">
<h1>Maintenance Overview</h1>

<!-- SUMMARY CARDS -->
<div class="cards">
    <div class="card">
        <h3>Total Flats</h3>
        <p><?= $total_flats ?></p>
    </div>

    <div class="card">
        <h3>Total Owners</h3>
        <p><?= $total_owners ?></p>
    </div>

    <div class="card">
        <h3>Total Maintenance</h3>
        <p>₹<?= number_format($total_expected) ?></p>
    </div>

    <div class="card">
        <h3>Pending Amount</h3>
        <p style="color:red;">₹<?= number_format($pending_amount) ?></p>
    </div>
</div>

<!-- CHARTS -->
<div class="dashboard-charts">

    <div class="chart-box">
        <h3>Maintenance Status</h3>
        <canvas id="maintenanceChart"></canvas>
    </div>

    <div class="chart-box">
        <h3>Flat Type vs Maintenance</h3>
        <canvas id="typeChart"></canvas>
    </div>

</div>

<!-- OWNER-WISE MAINTENANCE TABLE -->
<div class="chart-box">
<h3>Owner-wise Maintenance</h3>

<table>
<tr>
    <th>Flat No</th>
    <th>Flat Type</th>
    <th>Owner Name</th>
    <th>Maintenance</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($owners)): ?>
<tr>
    <td><?= $row['flat_no'] ?></td>
    <td><?= $row['flat_type'] ?></td>
    <td><?= $row['owner_name'] ?></td>
    <td>₹<?= $row['maintanance'] ?></td>
    <td>
        <?php if($row['maintanance'] > 0): ?>
            <span class="badge red">Pending</span>
        <?php else: ?>
            <span class="badge green">Paid</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>

</div>
</div>

<script>
/* DOUGHNUT */
new Chart(maintenanceChart, {
    type: 'doughnut',
    data: {
        labels: ['Pending'],
        datasets: [{
            data: [<?= $pending_amount ?>],
            backgroundColor: ['#E53935']
        }]
    }
});

/* BAR (DEMO BY TYPE) */
new Chart(typeChart, {
    type: 'bar',
    data: {
        labels: ['1 BHK', '2 BHK', '3 BHK'],
        datasets: [{
            label: 'Maintenance',
            data: [5000, 8000, 10000],
            backgroundColor: '#4CAF50'
        }]
    },
    options: {
        scales: { y: { beginAtZero:true } }
    }
});
</script>

</body>
</html>
