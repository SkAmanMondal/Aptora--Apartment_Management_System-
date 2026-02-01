<!DOCTYPE html>
<html>
<head>
    <title>Owner Dashboard</title>
    <link rel="icon" href="./assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<!-- TOP NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <h2>Owner Panel</h2>
    </div>
    <div class="nav-right">
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- MAIN LAYOUT -->
<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="pay_maintenance.php">Pay Maintenance</a>
        <a href="payment_history.php">Payment History</a>
        <a href="profile.php">My Profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Welcome, [Owner Name]</h1>

        <div class="cards">
            <div class="card">
                <h3>Flat Number</h3>
                <p>101</p>
            </div>

            <div class="card">
                <h3>Total Paid</h3>
                <p>₹40,000</p>
            </div>

            <div class="card">
                <h3>Pending Payment</h3>
                <p>₹5,000</p>
            </div>
        </div>

        <div class="info-box">
            <h3>Quick Actions</h3>
            <a href="pay_maintenance.php" class="btn">Pay Now</a>
            <a href="payment_history.php" class="btn">View History</a>
        </div>
    </div>

</div>

</body>
</html>
