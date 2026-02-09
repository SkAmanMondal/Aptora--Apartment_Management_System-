<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    die("Not admin");
}

include "../db.php";
$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

echo "Apartment ID = ".$apartment_id."<br>";

$month = date('m');
$year  = date('Y');


$flats = mysqli_query($conn,
"SELECT id, maintanance 
 FROM flats 
 WHERE apartment_id='$apartment_id'");

if(!$flats){
    die("Flat query error: ".mysqli_error($conn));
}

$count = 0;

while($f = mysqli_fetch_assoc($flats)){

    $flat_id = $f['id'];
    $amount  = $f['maintanance'];

    echo "Processing Flat: $flat_id Amount: $amount <br>";

    $check = mysqli_query($conn,
    "SELECT * FROM maintenance_bills
     WHERE flat_id='$flat_id'
     AND month='$month'
     AND year='$year'");

    if(!$check){
        die("Check error: ".mysqli_error($conn));
    }


    if(mysqli_num_rows($check) == 0){

        $ins = mysqli_query($conn,
        "INSERT INTO maintenance_bills
        (apartment_id,flat_id,month,year,amount,due_amount)
        VALUES
        ('$apartment_id',
         '$flat_id',
         '$month',
         '$year',
         '$amount',
         '$amount')");

        if(!$ins){
            die("Insert error: ".mysqli_error($conn));
        }

        $count++;
    }

}

echo "<br>Generated for $count flats";
exit;
?>
