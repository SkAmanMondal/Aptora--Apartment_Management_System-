<?php
include '../includes/auth.php';

if($_SESSION['role'] !== 'apartmentadmin'){
    header("location: ../login.php");
    exit;
}

include("../db.php");
$conn = dbconnect();

$error = "";

$apartment_id = $_SESSION['apartment_id'];
$flat_id = $_GET['id'];

// FETCH EXISTING DATA
$sql1 = "SELECT
            f.flat_no,
            f.flat_type,
            f.maintanance,
            fo.name,
            fo.phone,
            fo.email,
            fo.password
        FROM flats f
        LEFT JOIN flat_owners fo
            ON f.id = fo.flat_id
        WHERE f.id = $flat_id";

$result1 = mysqli_query($conn, $sql1);
$data1 = mysqli_fetch_assoc($result1);


// FORM SUBMIT
if(isset($_POST['submit'])){

    $flat_no = $_POST['flat_no'];
    $flat_type = $_POST['flat_type'];
    $maintanance = $_POST['maintanance'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // CHECK EMAIL ALREADY EXISTS (EXCEPT CURRENT OWNER)
    $email_check_sql = "SELECT id FROM flat_owners 
                        WHERE email = '$email' 
                        AND flat_id != '$flat_id'";

    $email_check_result = mysqli_query($conn, $email_check_sql);

    if(mysqli_num_rows($email_check_result) > 0){

        $error = "Owner email already exists!";

    } else {

        // UPDATE FLAT
        $update_flats_sql = "UPDATE flats
            SET flat_no = '$flat_no',
                flat_type = '$flat_type',
                maintanance = '$maintanance'
            WHERE id = '$flat_id' AND apartment_id = '$apartment_id'";

        mysqli_query($conn, $update_flats_sql);


        // CHECK OWNER EXIST OR NOT
        $check_owner_sql = "SELECT id FROM flat_owners WHERE flat_id = $flat_id";
        $check_owner_result = mysqli_query($conn, $check_owner_sql);

        if(mysqli_num_rows($check_owner_result) > 0){

            // UPDATE OWNER
            $update_owners_sql = "UPDATE flat_owners
                SET name = '$name',
                    phone = '$phone',
                    email = '$email',
                    password = '$password'
                WHERE flat_id = $flat_id";

            mysqli_query($conn, $update_owners_sql);

        } else {

            // INSERT OWNER
            $insert_owner_sql = "INSERT INTO flat_owners
                (apartment_id, flat_id, name, phone, email, password)
                VALUES
                ('$apartment_id','$flat_id', '$name', '$phone', '$email', '$password')";

            mysqli_query($conn, $insert_owner_sql);
        }

        header("Location: manage_flats.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Flat</title>

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet"/>

<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/edit_flat.css">

</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <div class="logo">
            <img src="../assets/icon4.png" alt="">
        </div>
    </div>
    <div class="nav-right">
        <a href="#" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">

    <div class="sidebar">
        <a href="dashboard.php"><i class="ri-box-3-fill"></i> Dashboard</a>
        <a href="add_flat.php"><i class="ri-file-add-line"></i> Add Flat</a>
        <a href="manage_flats.php" class="active"><i class="ri-home-gear-fill"></i> Manage Flats</a>
        <a href="payments.php"><i class="ri-hand-coin-fill"></i> Payments</a>
        <a href="profile.php"><i class="ri-account-circle-line"></i> Profile</a>
    </div>

    <div class="content">
        <h1>Edit Flat</h1>

        <div class="form-card">

            <!-- ERROR MESSAGE -->
            <?php if(!empty($error)) { ?>
                <div style="color:red; margin-bottom:15px; font-weight:600;">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <form method="POST">

                <label>Flat Number</label>
                <input type="text" name="flat_no" value="<?php echo $data1['flat_no']; ?>">

                <label>Flat Type</label>
                <select name="flat_type">
                    <option value="1 BHK" <?php if($data1['flat_type']=="1 BHK") echo "selected"; ?>>1 BHK</option>
                    <option value="2 BHK" <?php if($data1['flat_type']=="2 BHK") echo "selected"; ?>>2 BHK</option>
                    <option value="3 BHK" <?php if($data1['flat_type']=="3 BHK") echo "selected"; ?>>3 BHK</option>
                </select>

                <label>Maintanance Per Month</label>
                <input type="number" name="maintanance" value="<?php echo $data1['maintanance']; ?>">

                <label>Owner Name</label>
                <input type="text" name="name" value="<?php echo $data1['name']; ?>">

                <label>Owner Phone</label>
                <input type="tel" name="phone" value="<?php echo $data1['phone']; ?>">

                <label>Owner Email</label>
                <input type="email" name="email" value="<?php echo $data1['email']; ?>">

                <label>Owner Password</label>
                <input type="text" name="password" value="<?php echo $data1['password']; ?>">

                <button type="submit" name="submit" class="btn primary">Update Flat</button>
                <a href="manage_flats.php" class="btn secondary">Cancel</a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
