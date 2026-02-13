<?php
include "../includes/auth.php";
include "../db.php";

$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

$month = date("n");
$year  = date("Y");


/* DELETE */

if(isset($_GET['delete']))
{
$flat_id = $_GET['delete'];

mysqli_query($conn,"
DELETE FROM maintenance_bills
WHERE apartment_id='$apartment_id'
AND flat_id='$flat_id'
AND month='$month'
AND year='$year'
");

header("Location: generate_maintenance.php");
exit;
}


/* GENERATE SELECTED */

if(isset($_POST['generate']))
{

foreach($_POST['generate'] as $flat_id)
{

$flat = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT maintanance
FROM flats
WHERE id='$flat_id'
"));

$amount = $flat['maintanance'];


/* prevent duplicate */

$check = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id
FROM maintenance_bills
WHERE apartment_id='$apartment_id'
AND flat_id='$flat_id'
AND month='$month'
AND year='$year'
"));

if(!$check)
{

mysqli_query($conn,"
INSERT INTO maintenance_bills
(apartment_id,flat_id,amount,due_amount,status,month,year)
VALUES
('$apartment_id','$flat_id','$amount','$amount','unpaid','$month','$year')
");

}

}

}


/* GENERATE ALL */

if(isset($_POST['generate_all']))
{

$flats = mysqli_query($conn,"
SELECT id,maintanance
FROM flats
WHERE apartment_id='$apartment_id'
");

while($flat=mysqli_fetch_assoc($flats))
{

$flat_id=$flat['id'];
$amount=$flat['maintanance'];

$check=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id FROM maintenance_bills
WHERE apartment_id='$apartment_id'
AND flat_id='$flat_id'
AND month='$month'
AND year='$year'
"));

if(!$check)
{

mysqli_query($conn,"
INSERT INTO maintenance_bills
(apartment_id,flat_id,amount,due_amount,status,month,year)
VALUES
('$apartment_id','$flat_id','$amount','$amount','unpaid','$month','$year')
");

}

}

}


header("Location: generate_maintenance.php");
