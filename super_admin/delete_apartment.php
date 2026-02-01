<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'superadmin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Apartment</title>
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/delete_apartment.css">
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
        <h1>Delete Apartment</h1>

        <div class="delete-card">
            <p>
                Are you sure you want to delete
                <strong>Green Heights</strong>?
            </p>

            <p class="warning">
                This action cannot be undone.
            </p>

            <div class="actions">
                <button class="btn danger">Yes, Delete</button>
                <a href="manage_apartments.php" class="btn secondary">Cancel</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
