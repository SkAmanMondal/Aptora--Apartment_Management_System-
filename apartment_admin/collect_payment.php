<?php
    $bill_id = $_POST['bill_id'];
    $flat_id = $_POST['flat_id'];
    $amount  = $_POST['amount'];

    mysqli_query($conn,
    "INSERT INTO maintenance_payments
    (bill_id,flat_id,amount,payment_date,mode)
    VALUES
    ('$bill_id','$flat_id','$amount',NOW(),'cash')");

    // reduce due
    mysqli_query($conn,
    "UPDATE maintenance_bills
    SET due_amount = due_amount - $amount
    WHERE id='$bill_id'");

    // update status
    mysqli_query($conn,
    "UPDATE maintenance_bills
    SET status =
    CASE
    WHEN due_amount<=0 THEN 'paid'
    ELSE 'partial'
    END
    WHERE id='$bill_id'");

?>