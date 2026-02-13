<?php
include "../includes/auth.php";
include "../db.php";

$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

$month = date("n");
$year  = date("Y");


/* Already generated */

$generatedQuery = "
SELECT flat_id
FROM maintenance_bills
WHERE apartment_id='$apartment_id'
AND month='$month'
AND year='$year'
";

$result = mysqli_query($conn,$generatedQuery);

$generatedFlats=[];

while($row=mysqli_fetch_assoc($result))
{
$generatedFlats[]=$row['flat_id'];
}


/* Get flats */

$flats = mysqli_query($conn,"
SELECT *
FROM flats
WHERE apartment_id='$apartment_id'
");

?>


<!DOCTYPE html>
<html>

<head>

<title>Generate Maintenance</title>

<link rel="icon" href="../assets/icon.png">

<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet">


<style>

/* Page title */

.page-title
{
margin-bottom:20px;
}


/* Table */

.gen-table
{
width:100%;
background:white;
border-collapse:collapse;
border-radius:8px;
overflow:hidden;
box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.gen-table th
{
background:#1F4842;
color:white;
padding:12px;
}

.gen-table td
{
padding:12px;
border-bottom:1px solid #eee;
text-align:center;
}

.gen-table tr:hover
{
background:#f9f9f9;
}


/* Buttons */

.btn
{
padding:8px 16px;
background:#1F4842;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
text-decoration:none;
}

.btn:hover
{
background:#163732;
}


.btn-delete
{
background:#D32F2F;
}

.btn-delete:hover
{
background:#a51f1f;
}


.btn-group
{
margin-top:15px;
display:flex;
gap:10px;
}


/* Status */

.status-ok
{
color:green;
font-weight:bold;
}

.status-no
{
color:red;
font-weight:bold;
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



<!-- CONTENT -->

<div class="content">


<h1 class="page-title">
Generate Maintenance - <?= date("F Y") ?>
</h1>



<form method="POST" action="generate_action.php">

<table class="gen-table">

<tr>

<th>Select</th>
<th>Flat</th>
<th>Amount</th>
<th>Status</th>
<th>Delete</th>

</tr>


<?php while($flat=mysqli_fetch_assoc($flats)):

$flat_id=$flat['id'];

$isGenerated=in_array($flat_id,$generatedFlats);

?>


<tr>

<td>

<?php if(!$isGenerated): ?>

<input type="checkbox"
name="generate[]"
value="<?= $flat_id ?>">

<?php endif; ?>

</td>


<td>
<?= $flat['flat_no'] ?>
</td>


<td>
₹<?= $flat['maintanance'] ?>
</td>


<td>

<?php if($isGenerated): ?>

<span class="status-ok">
Generated
</span>

<?php else: ?>

<span class="status-no">
Not Generated
</span>

<?php endif; ?>

</td>


<td>

<?php if($isGenerated): ?>

<a class="btn btn-delete"
href="generate_action.php?delete=<?= $flat_id ?>"
onclick="return confirm('Delete maintenance?')">
Delete
</a>

<?php endif; ?>

</td>


</tr>

<?php endwhile; ?>


</table>


<div class="btn-group">

<button class="btn">
Generate Selected
</button>


<a href="maintanance.php"
class="btn">
Back
</a>

</div>


</form>



<br>


<form method="POST" action="generate_action.php">

<input type="hidden" name="generate_all" value="1">

<button class="btn">
Generate All Remaining
</button>

</form>



</div>
</div>


</body>
</html>
