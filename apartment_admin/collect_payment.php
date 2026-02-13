<?php
include "../includes/auth.php";
include "../db.php";

$conn = dbconnect();

$bill_id = $_POST['bill_id'];
$amount  = $_POST['amount'];


/* Get current due */

$row = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT due_amount, amount
FROM maintenance_bills
WHERE id='$bill_id'
"));

$new_due = $row['due_amount'] - $amount;


/* Set status */

if($new_due <= 0)
{
$new_due = 0;
$status = "paid";
}
else
{
$status = "partial";
}


/* Update */

mysqli_query($conn,"
UPDATE maintenance_bills
SET due_amount='$new_due',
status='$status'
WHERE id='$bill_id'
");

header("Location: maintanance.php");
