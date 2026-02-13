<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    header("Location: ../login.php");
    exit;
}

include "../db.php";
$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];


/* ======================
FILTER TYPE
====================== */

$currentMonth = date("n");
$currentYear  = date("Y");

$type  = $_GET['type'] ?? 'month';
$month = $_GET['month'] ?? $currentMonth;
$year  = $_GET['year'] ?? $currentYear;


/* ======================
WHERE CONDITION
====================== */

$where = "apartment_id='$apartment_id'";

if($type == "month")
{
$where .= " AND month='$month' AND year='$year'";
}
elseif($type == "year")
{
$where .= " AND year='$year'";
}
elseif($type == "overall")
{
/* no filter */
}


/* ======================
FLATS COUNT
====================== */

$sqlFlats="
SELECT COUNT(*) as total_flats
FROM flats
WHERE apartment_id='$apartment_id'
";

$total_flats=
mysqli_fetch_assoc(mysqli_query($conn,$sqlFlats))['total_flats'];


/* ======================
OWNERS COUNT
====================== */

$sqlOwners="
SELECT COUNT(*) as total
FROM flat_owners
WHERE apartment_id='$apartment_id'
";

$total_owners=
mysqli_fetch_assoc(mysqli_query($conn,$sqlOwners))['total'];

$occupied_flats=$total_owners;
$vacant_flats=$total_flats-$occupied_flats;


/* ======================
MAINTENANCE STATS
====================== */

$sqlMaintenance="
SELECT
SUM(amount) as expected,
SUM(due_amount) as pending
FROM maintenance_bills
WHERE $where
";

$data=mysqli_fetch_assoc(mysqli_query($conn,$sqlMaintenance));

$total_expected=$data['expected'] ?? 0;
$pending_amount=$data['pending'] ?? 0;
$total_collected=$total_expected-$pending_amount;


/* ======================
STATUS COUNT
====================== */

function getCount($conn,$where,$status)
{
$sql="
SELECT COUNT(*) as total
FROM maintenance_bills
WHERE $where
AND status='$status'
";

return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'] ?? 0;
}

$paid_flats=getCount($conn,$where,'paid');
$partial_flats=getCount($conn,$where,'partial');
$unpaid_flats=getCount($conn,$where,'unpaid');


/* ======================
MONTHLY TREND (THIS YEAR)
====================== */

$sqlTrend="
SELECT month,
SUM(amount-due_amount) as collected
FROM maintenance_bills
WHERE apartment_id='$apartment_id'
AND year='$year'
GROUP BY month
ORDER BY month
";

$res=mysqli_query($conn,$sqlTrend);

$months=[];
$values=[];

while($row=mysqli_fetch_assoc($res))
{
$months[]=date("M", mktime(0,0,0,$row['month'],1));
$values[]=$row['collected'];
}
?>


<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<link rel="icon" href="../assets/icon.png">

<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

.filter-box{
margin:15px 0;
}

.filter-box select,
.filter-box button{
padding:6px;
margin-right:10px;
}

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
<a href="../logout.php" class="logout-btn">Logout</a>
</div>

</div>



<div class="container">


<!-- SIDEBAR -->

<div class="sidebar">

<a href="dashboard.php" class="active">
<i class="ri-dashboard-fill"></i> Dashboard
</a>

<a href="add_flat.php">
<i class="ri-add-circle-fill"></i> Add Flat
</a>

<a href="manage_flats.php">
<i class="ri-settings-fill"></i> Manage Flats
</a>

<a href="maintanance.php">
<i class="ri-hand-coin-fill"></i> Maintenance
</a>

<a href="profile.php">
<i class="ri-user-fill"></i> Profile
</a>

</div>



<!-- CONTENT -->

<div class="content">

<h1><?= $_SESSION['apartment_name'] ?> Dashboard</h1>



<!-- FILTER -->

<div class="filter-box">

<form method="GET">

<select name="type">

<option value="month" <?= $type=='month'?'selected':'' ?>>
This Month
</option>

<option value="year" <?= $type=='year'?'selected':'' ?>>
This Year
</option>

<option value="overall" <?= $type=='overall'?'selected':'' ?>>
Overall
</option>

</select>


<select name="month">

<?php
for($m=1;$m<=12;$m++)
{
$selected=$month==$m?'selected':'';

echo "<option value='$m' $selected>".
date("F", mktime(0,0,0,$m,1)).
"</option>";
}
?>

</select>


<select name="year">

<?php
for($y=$currentYear;$y>=2020;$y--)
{
$selected=$year==$y?'selected':'';

echo "<option value='$y' $selected>$y</option>";
}
?>

</select>


<button>Apply</button>

</form>

</div>



<!-- CARDS -->

<div class="cards">

<div class="card">
<h3>Total Flats</h3>
<p><?= $total_flats ?></p>
</div>

<div class="card">
<h3>Occupied</h3>
<p><?= $occupied_flats ?></p>
</div>

<div class="card">
<h3>Vacant</h3>
<p><?= $vacant_flats ?></p>
</div>

</div>



<div class="cards">

<div class="card">
<h3>Expected</h3>
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
₹<?= number_format($pending_amount) ?>
</p>
</div>

</div>



<!-- CHARTS -->

<div class="dashboard-charts">


<div class="chart-box">
<h3>Occupancy</h3>
<canvas id="occupancyChart"></canvas>
</div>


<div class="chart-box">
<h3>Collection</h3>
<canvas id="collectionChart"></canvas>
</div>


<div class="chart-box">
<h3>Status</h3>
<canvas id="statusChart"></canvas>
</div>


<div class="chart-box">
<h3>Monthly Trend</h3>
<canvas id="monthlyChart"></canvas>
</div>


</div>


</div>
</div>



<script>


new Chart(occupancyChart,{
type:'pie',
data:{
labels:['Occupied','Vacant'],
datasets:[{
data:[<?= $occupied_flats ?>,<?= $vacant_flats ?>],
backgroundColor:['green','gray']
}]
}
});


new Chart(collectionChart,{
type:'pie',
data:{
labels:['Collected','Pending'],
datasets:[{
data:[<?= $total_collected ?>,<?= $pending_amount ?>],
backgroundColor:['#1F4842','#D32F2F']
}]
}
});


new Chart(statusChart,{
type:'pie',
data:{
labels:['Paid','Partial','Unpaid'],
datasets:[{
data:[<?= $paid_flats ?>,<?= $partial_flats ?>,<?= $unpaid_flats ?>],
backgroundColor:['green','orange','red']
}]
}
});


new Chart(monthlyChart,{
type:'bar',
data:{
labels:<?= json_encode($months) ?>,
datasets:[{
label:'Collected',
data:<?= json_encode($values) ?>,
backgroundColor:'#4CAF50'
}]
}
});

</script>


</body>
</html>
