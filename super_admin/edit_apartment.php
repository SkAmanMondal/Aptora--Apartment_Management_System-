<?php
    include "../includes/auth.php";

    if ($_SESSION['role'] !== 'superadmin') {
        header("Location: ../login.php");
        exit;
    }

    include('../db.php');
    $conn = dbconnect();

    $error = "";
    $message = "";

    $apartment_id = $_GET['id'];

    $sql1 = "SELECT * FROM apartments where id = '$apartment_id'";
    $result1 = mysqli_query($conn,$sql1);
    $data1 = mysqli_fetch_assoc($result1);

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $email_check_sql = "SELECT email FROM apartments 
                        WHERE email = '$email' 
                        AND id != '$apartment_id' ";

        $email_check_result = mysqli_query($conn, $email_check_sql);

        if(mysqli_num_rows($email_check_result) == 0){

            $update_owners_sql = "UPDATE apartments
                SET name = '$name',
                    address = '$address',
                    email = '$email',
                    password = '$password'
                WHERE id = '$apartment_id'";

            $update = mysqli_query($conn, $update_owners_sql);

            if($update > 0){
                $message = "Update Successfull";
                // header("Location: " . $_SERVER['PHP_SELF']);
                // exit;
            }else{
                $error = "Error: ". mysqli_error();
            }

        }else{
                
            $error = "Owner email already exists!";
        }

    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Apartment</title>
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/edit_apartment.css">
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
        <a href="manage_apartments.php" class="active">Manage Apartments</a>
        <a href="profile.php">Profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Edit Apartment</h1>

        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label>Apartment Name</label>
                    <input type="text" name="name" value="<?php echo $data1['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Apartment Address</label>
                    <textarea name="address"><?php echo $data1['address']; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Apartment Email</label>
                    <input type="email" name="email" value="<?php echo $data1['email']; ?>">
                </div>

                <div class="form-group">
                    <label>Apartment Password</label>
                    <input type="text" name="password" value="<?php echo $data1['password']; ?>">
                </div>

                <div class="actions">
                    <button type="submit" name="submit" class="btn">Save Changes</button>
                    <a href="manage_apartments.php" class="btn secondary">Cancel</a>
                </div>
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
