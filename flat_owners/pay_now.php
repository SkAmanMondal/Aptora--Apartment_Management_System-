<?php
include "../includes/auth.php";
include "../db.php";
$conn=dbconnect();

$bill = $_POST['bill_id'];
$amount = $_POST['amount'];

mysqli_query($conn,"
INSERT INTO maintenance_payments
(bill_id,amount,payment_date,mode)
VALUES
('$bill','$amount',NOW(),'online')
");

mysqli_query($conn,"
UPDATE maintenance_bills
SET due_amount = due_amount-$amount
WHERE id='$bill'
");

mysqli_query($conn,"
UPDATE maintenance_bills
SET status=
CASE
WHEN due_amount<=0 THEN 'paid'
ELSE 'partial'
END
WHERE id='$bill'
");

header("location: pay_maintenance.php");
