<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    header("Location: ../login.php");
    exit;
}

include "../db.php";
$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

/* FLATS */
$sqlFlats = "SELECT COUNT(*) AS total_flats FROM flats WHERE apartment_id='$apartment_id'";
$total_flats = mysqli_fetch_assoc(mysqli_query($conn,$sqlFlats))['total_flats'];

/* OWNERS */
$sqlOwners = "SELECT COUNT(*) AS total_owners FROM flat_owners WHERE apartment_id='$apartment_id'";
$total_owners = mysqli_fetch_assoc(mysqli_query($conn,$sqlOwners))['total_owners'];

$occupied_flats = $total_owners;
$vacant_flats   = $total_flats - $occupied_flats;

/* MAINTENANCE (DEMO DATA – CONNECT LATER) */
$maintenance_per_flat = 2000;
$total_expected = $total_flats * $maintenance_per_flat;
$total_collected = 120000;
$pending_amount = $total_expected - $total_collected;
?>

<!DOCTYPE html>
<html>
<head>
<title>Apartment Admin Dashboard</title>
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
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="dashboard.php" class="active">
        <i class="ri-dashboard-fill"></i> Dashboard
    </a>

    <!-- ===== FLATS GROUP START ===== -->
    <div class="sidebar-group">

        <div class="group-title" onclick="toggleFlats()">
            <i class="ri-building-fill"></i> Flats
            <i class="ri-arrow-down-s-line arrow"></i>
        </div>

        <div class="group-items" id="flatsMenu">

            <a href="add_flat.php">
                <i class="ri-add-circle-fill"></i> Add Flat
            </a>

            <a href="manage_flats.php">
                <i class="ri-settings-3-fill"></i> Manage Flats
            </a>

        </div>

    </div>
    <!-- ===== FLATS GROUP END ===== -->

    <a href="maintanance.php">
        <i class="ri-hand-coin-fill"></i> Maintenance
    </a>

    <a href="profile.php">
        <i class="ri-user-fill"></i> Profile
    </a>

</div>


<!-- CONTENT -->
<div class="content">
<h1><?= $_SESSION['apartment_name']; ?> – Dashboard</h1>

<!-- QUICK CARDS -->
<div class="cards">
    <div class="card"><h3>Total Flats</h3><p><?= $total_flats ?></p></div>
    <div class="card"><h3>Occupied</h3><p><?= $occupied_flats ?></p></div>
    <div class="card"><h3>Vacant</h3><p><?= $vacant_flats ?></p></div>
</div>

<div class="cards" style="margin-top:20px;">
    <div class="card"><h3>Total Maintenance</h3><p>₹<?= number_format($total_expected) ?></p></div>
    <div class="card"><h3>Collected</h3><p style="color:green">₹<?= number_format($total_collected) ?></p></div>
    <div class="card"><h3>Pending</h3><p style="color:red">₹<?= number_format($pending_amount) ?></p></div>
</div>

<!-- CHARTS -->
<div class="dashboard-charts">

    <!-- OCCUPANCY -->
    <div class="chart-box">
        <h3>Flat Occupancy</h3>
        <canvas id="occupancyChart"></canvas>
    </div>

    <!-- MAINTENANCE -->
    <div class="chart-box">
        <h3>Maintenance Collection</h3>
        <canvas id="maintenanceChart"></canvas>
    </div>

    <!-- MONTHLY -->
    <div class="chart-box">
        <h3>Monthly Maintenance Trend</h3>
        <canvas id="monthlyChart"></canvas>
    </div>

</div>

</div>
</div>

<script>

function toggleFlats() {
        var menu = document.getElementById("flatsMenu");

        if (menu.style.display === "block") {
            menu.style.display = "none";
        } else {
            menu.style.display = "block";
        }
    }

/* OCCUPANCY PIE */
new Chart(occupancyChart, {
    type: 'pie',
    data: {
        labels: ['Occupied', 'Vacant'],
        datasets: [{
            data: [<?= $occupied_flats ?>, <?= $vacant_flats ?>],
            backgroundColor: ['#2E7D32', '#D32F2F']
        }]
    }
});

/* MAINTENANCE DOUGHNUT */
new Chart(maintenanceChart, {
    type: 'doughnut',
    data: {
        labels: ['Collected', 'Pending'],
        datasets: [{
            data: [<?= $total_collected ?>, <?= $pending_amount ?>],
            backgroundColor: ['#1F4842', '#FF7043']
        }]
    }
});

/* MONTHLY BAR (DEMO) */
new Chart(monthlyChart, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Maintenance Collected',
            data: [15000, 18000, 22000, 20000, 25000, 30000],
            backgroundColor: '#4CAF50'
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } }
    }
});
</script>

</body>
</html>
