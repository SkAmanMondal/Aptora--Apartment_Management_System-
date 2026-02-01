<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    header("Location: ../login.php");
    exit;
}

include("../db.php");
$conn = dbconnect();

$message = "";
$error = "";

if (isset($_POST['submit'])) {
    $apartment_id = $_POST['apartment_id'];
    $flat_no = $_POST['flat_no'];
    $flat_type = $_POST['flat_type'];

    $sql = "SELECT * FROM flats WHERE flat_no = '$flat_no' AND apartment_id = '$apartment_id'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 0){
        $sql = "INSERT INTO flats (apartment_id, flat_no, flat_type)
            VALUES ('$apartment_id', '$flat_no', '$flat_type')";

        $response = mysqli_query($conn, $sql);

        if ($response == 1) {
            $message = "Flat Added Successfully";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }else{
        $error = "Falt no - $flat_no Already Exists";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Flat</title>
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css"
    rel="stylesheet"
    />
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/add_flat.css">
</head>
<body>

<!-- TOP NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <!-- <h2>Apartment Admin Panel</h2> -->
  <div class="logo"><img src="../assets/icon4.png" alt=""></div> 

    </div>
    <div class="nav-right">
        <a href="../logout.php" class="logout-btn"
           onclick="return confirm('Are you sure you want to Logout....')">Logout</a>
    </div>
</div>

<!-- MAIN LAYOUT -->
<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="dashboard.php"><i class="ri-box-3-fill"></i> Dashboard</a>
        <a href="add_flat.php" class="active"><i class="ri-file-add-line"></i> Add Flat</a>
        <a href="manage_flats.php"><i class="ri-home-gear-fill"></i> Manage Flats</a>
        <a href="payments.php"><i class="ri-hand-coin-fill"></i> Payments</a>
        <a href="profile.php"><i class="ri-account-circle-line"></i> profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Add Flat</h1>

        <div class="flat-card">
            <form method="POST">
                
                <div class="form-group">
                    <label>Apartment Id</label>
                    <input type="text" name="apartment_id" value='<?php echo $_SESSION['apartment_id'] ?>' placeholder="Apartment ID" readonly>
                </div>

                <div class="form-group">
                    <label>Flat Number</label>
                    <input type="text" name="flat_no" placeholder="Flat Number" required>
                </div>

                <div class="form-group">
                    <label>Flat Type</label>
                    <select name="flat_type" required>
                        <option value="">Select Type</option>
                        <option value="1BHK">1 BHK</option>
                        <option value="2BHK">2 BHK</option>
                        <option value="3BHK">3 BHK</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn"
                        onclick="return confirm('Are you sure you want to add this flat?')">
                    Add Flat
                </button>

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
