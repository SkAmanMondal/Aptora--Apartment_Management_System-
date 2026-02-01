<?php
    include "../includes/auth.php";

    if ($_SESSION['role'] !== 'apartmentadmin') {
        header("Location: ../login.php");
        exit;
    }

    include("../db.php");
    $conn = dbconnect();

    $message = "";

    function getData($conn){
        // global $conn; // used if i don't pass $conn in calling time of getData()
        $sql = "SELECT * from apartments 
                where email='{$_SESSION['apartment_email']}'";

        return mysqli_query($conn,$sql);
    }
    $data = mysqli_fetch_assoc(getData($conn));

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "update apartments 
            set name = '$name',
                address = '$address',
                email = '$email',
                password='$password'
            where email= '{$_SESSION['apartment_email']}'";
        global $conn;
        $response = mysqli_query($conn,$sql);

        if($response == 1){
            $message = "Details Updated";
            // global $data;
            $data = mysqli_fetch_assoc(getData($conn));
            $_SESSION['apartment_name'] = $name;
            $_SESSION['apartment_email'] = $email;
        }else{
            $message = "Update Error: ".mysqli_error($conn);
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Profile</title>
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css"
    rel="stylesheet"
    />
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

<!-- TOP NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <!-- <h2>Apartment Admin Panel</h2> -->
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
        <a href="dashboard.php"><i class="ri-box-3-fill"></i> Dashboard</a>
        <a href="add_flat.php"><i class="ri-file-add-line"></i> Add Flat</a>
        <a href="manage_flats.php"><i class="ri-home-gear-fill"></i> Manage Flats</a>
        <a href="payments.php"><i class="ri-hand-coin-fill"></i> Payments</a>
        <a href="profile.php" class="active"><i class="ri-account-circle-line"></i> profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>My Profile</h1>

        <script>
            function togglePassword() {
                const pass = document.getElementById("password");
                pass.type = pass.type === "password" ? "text" : "password";
            }
        </script>

        <div class="profile-card">
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo $data['name'] ?>" placeholder="New Name" require>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="textarea" name="address" value="<?php echo $data['address'] ?>" placeholder="New Address" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $data['email'] ?>" placeholder="New Email" require>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <div class="password-box">
                        <input type="password" name="password" value="<?php echo $data['password'] ?>" id="password" placeholder="New Password" required>
                        <span onclick="togglePassword()" style="cursor:pointer;">👁</span>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn" onclick="return confirm('Are you sure you want to Update Details of Apartment....')">Update</button>
            </form>
        </div>
        <?php if ($message != "") { ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php } ?>
    </div>

</div>

</body>
</html>
