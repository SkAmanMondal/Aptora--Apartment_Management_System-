<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

include "../db.php";
$conn = dbconnect();

/* OVERALL COUNTS */
$sql = "
SELECT 
    (SELECT COUNT(id) FROM apartments) AS total_apartments,
    (SELECT COUNT(id) FROM flats) AS total_flats,
    (SELECT COUNT(id) FROM flat_owners) AS total_flat_owners
";
$data = mysqli_fetch_assoc(mysqli_query($conn, $sql));

$occupied_flats = $data['total_flat_owners'];
$vacant_flats   = $data['total_flats'] - $occupied_flats;
$occupancy_rate = ($data['total_flats'] > 0)
    ? round(($occupied_flats / $data['total_flats']) * 100)
    : 0;

/* APARTMENT-WISE DATA */
$apartmentSql = "
SELECT 
    a.id,
    a.name AS apartment_name,
    COUNT(f.id) AS total_flats,
    COUNT(fo.flat_id) AS occupied_flats,
    (COUNT(f.id) - COUNT(fo.flat_id)) AS vacant_flats
FROM apartments a
LEFT JOIN flats f ON f.apartment_id = a.id
LEFT JOIN flat_owners fo ON fo.flat_id = f.id
GROUP BY a.id
ORDER BY a.name
";

$result = mysqli_query($conn, $apartmentSql);

$aptNames = $occupiedData = $vacantData = [];
$aptRows = [];

while ($row = mysqli_fetch_assoc($result)) {
    $aptRows[] = $row;
    $aptNames[] = $row['apartment_name'];
    $occupiedData[] = $row['occupied_flats'];
    $vacantData[] = $row['vacant_flats'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Super Admin Dashboard</title>
<link rel="icon" href="../assets/icon.png">
<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* .dashboard-stats {
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:15px;
        margin-bottom:25px;
    }
    .stat-box {
        background:#fff;
        padding:15px;
        border-radius:8px;
        text-align:center;
        box-shadow:0 3px 8px rgba(0,0,0,.08);
    }
    .stat-box h4 { font-size:14px; color:#666 }
    .stat-box p { font-size:22px; font-weight:bold }

    .chart-box {
        background:#fff;
        padding:15px;
        border-radius:8px;
        box-shadow:0 4px 10px rgba(0,0,0,.08);
        margin-top:25px;
    }

    .dashboard-charts {
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
        gap:20px;
    }

    canvas { max-height:220px }

    table {
        width:100%;
        border-collapse:collapse;
        margin-top:15px;
    }
    th, td {
        padding:10px;
        border-bottom:1px solid #ddd;
        text-align:center;
    }
    th { background:#f5f5f5 }
    .badge {
        padding:4px 8px;
        border-radius:4px;
        color:#fff;
        font-size:12px;
    }
    .green { background:#4CAF50 }
    .red { background:#E53935 } */
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
    <div class="logo">
        <img src="../assets/icon4.png">
    </div>
</div>
<div class="nav-right">
        <a href="../logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to Logout....')">Logout</a>
    </div>
</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="add_apartment.php">Add Apartment</a>
    <a href="manage_apartments.php">Manage Apartments</a>
    <a href="profile.php">Profile</a>
</div>

<!-- CONTENT -->
<div class="content">
<h1>Super Admin Dashboard</h1>

<!-- OVERALL STATS -->
<div class="dashboard-stats">
    <div class="stat-box"><h4>Total Apartments</h4><p><?= $data['total_apartments'] ?></p></div>
    <div class="stat-box"><h4>Total Flats</h4><p><?= $data['total_flats'] ?></p></div>
    <div class="stat-box"><h4>Total Owners</h4><p><?= $data['total_flat_owners'] ?></p></div>
    <div class="stat-box"><h4>Occupied Flats</h4><p><?= $occupied_flats ?></p></div>
    <div class="stat-box"><h4>Vacant Flats</h4><p><?= $vacant_flats ?></p></div>
    <div class="stat-box"><h4>Occupancy Rate</h4><p><?= $occupancy_rate ?>%</p></div>
</div>

<!-- OVERALL CHARTS -->
<div class="dashboard-charts">
    <div class="chart-box">
        <h3>System Distribution</h3>
        <canvas id="systemChart"></canvas>
    </div>

    <div class="chart-box">
        <h3>Overall Flat Occupancy</h3>
        <canvas id="occupancyChart"></canvas>
    </div>
</div>

<!-- APARTMENT-WISE CHART -->
<div class="chart-box">
<h3>Apartment-wise Flat Availability</h3>
<canvas id="apartmentChart"></canvas>
</div>

<!-- APARTMENT TABLE -->
<div class="chart-box">
<h3>Apartment Details</h3>

<table>
<tr>
    <th>Apartment</th>
    <th>Total Flats</th>
    <th>Occupied</th>
    <th>Available</th>
    <th>Status</th>
</tr>

<?php foreach ($aptRows as $apt): ?>
<tr>
    <td><?= $apt['apartment_name'] ?></td>
    <td><?= $apt['total_flats'] ?></td>
    <td><?= $apt['occupied_flats'] ?></td>
    <td><?= $apt['vacant_flats'] ?></td>
    <td>
        <?php if ($apt['vacant_flats'] > 0): ?>
            <span class="badge green">Available</span>
        <?php else: ?>
            <span class="badge red">Full</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

</div>
</div>

<script>
// OVERALL CHARTS
new Chart(systemChart, {
    type:'pie',
    data:{
        labels:['Apartments','Flats','Owners'],
        datasets:[{
            data:[<?= $data['total_apartments'] ?>,<?= $data['total_flats'] ?>,<?= $data['total_flat_owners'] ?>],
            backgroundColor:['#4CAF50','#2196F3','#FF9800']
        }]
    }
});

new Chart(occupancyChart, {
    type:'doughnut',
    data:{
        labels:['Occupied','Vacant'],
        datasets:[{
            data:[<?= $occupied_flats ?>,<?= $vacant_flats ?>],
            backgroundColor:['#2E7D32','#D32F2F']
        }]
    }
});

// APARTMENT-WISE CHART
new Chart(apartmentChart, {
    type:'bar',
    data:{
        labels:<?= json_encode($aptNames) ?>,
        datasets:[
            {
                label:'Occupied',
                data:<?= json_encode($occupiedData) ?>,
                backgroundColor:'#4CAF50'
            },
            {
                label:'Available',
                data:<?= json_encode($vacantData) ?>,
                backgroundColor:'#F44336'
            }
        ]
    },
    options:{
        scales:{ y:{ beginAtZero:true } }
    }
});
</script>

</body>
</html>
