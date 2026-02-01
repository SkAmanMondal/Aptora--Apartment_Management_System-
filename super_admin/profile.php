<?php
    include "../includes/auth.php";

    if ($_SESSION['role'] !== 'superadmin') {
        header("Location: ../login.php");
        exit;
    }

    include("../db.php");
    $conn = dbconnect();

    $message = "";

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "update superadmin set password='$password' where email='$email'";

        $response = mysqli_query($conn,$sql);

        if($response == 1){
            $message = "Password Changed";
        }else{
            $message = "Password Change Error: ".mysqli_error($conn);
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Profile</title>
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/profile.css">
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
        <a href="add_apartment.php">Add Apartment</a>
        <a href="manage_apartments.php">Manage Apartments</a>
        <a href="profile.php" class="active">Profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>My Profile</h1>

        <div class="profile-card">
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="Super Admin" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="admin@gmail.com" readonly>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="New Password" required>
                </div>

                <button type="submit" name="submit" class="btn">Update Profile</button>
            </form>
        </div>
        <?php if ($message != "") { ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php } ?>
    </div>

</div>

</body>
</html>
