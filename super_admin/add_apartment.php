<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}

require_once("../db.php");
$conn = dbconnect();

$message = "";
$error = "";

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $total_maintanance = 0;
        $email = $_POST['email'];

        $sql = "SELECT * FROM apartments WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 0){
            $sql = "insert into apartments(name,address,total_maintanance,email)
            values('$name','$address','$total_maintanance','$email')";

            $response = mysqli_query($conn,$sql);

            if ($response == 1) {
                $message = "Apartment Added Successfully";
            } else {
                $error = "Error: " . mysqli_error($response);
            }
        }else{
            $error = "$email Already Exists";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Apartment</title>
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/add_apartment.css">
</head>
<body>

<!-- TOP NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <!-- <h2>Super Admin Panel</h2> -->
  <div class="logo"><img src="../assets/icon4.png" alt=""></div> 
    </div>
    <div class="nav-right">
        <a href="../logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to Logout....')">Logout</a>
    </div>
</div>

<!-- MAIN LAYOUT -->
<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="add_apartment.php" class="active">Add Apartment</a>
        <a href="manage_apartments.php">Manage Apartments</a>
        <a href="profile.php">Profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Add Apartment</h1>

        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label>Apartment Name</label>
                    <input type="text" name="name" placeholder="Enter apartment name" required>
                </div>

                <div class="form-group">
                    <label>Apartment Address</label>
                    <textarea name="address" placeholder="Enter apartment address"></textarea>
                </div>

                <div class="form-group">
                    <label>Apartment Email</label>
                    <input type="email" name="email" placeholder="Enter apartment Email" required>
                </div>

                <button type="submit" name="submit" class="btn">Add Apartment</button>
            </form>
        </div>
        <?php if ($message != "") { ?>
                <p style="color: green;"><?php echo $message; ?></p>
            <?php }
            else if($error != ""){ ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php }
        ?>
    </div>

</div>

</body>
</html>
