<?php
include "../includes/auth.php";

if ($_SESSION['role'] !== 'apartmentadmin') {
    header("Location: ../login.php");
    exit;
}

include("../db.php");
$conn = dbconnect();

$apartment_id = $_SESSION['apartment_id'];

$sql = "SELECT 
            flats.id,
            flats.flat_no,
            flats.flat_type,
            flats.maintanance,
            flat_owners.name AS owner_name,
            flat_owners.phone,
            flat_owners.email
        FROM flats
        LEFT JOIN flat_owners
            ON flats.id = flat_owners.flat_id
        WHERE flats.apartment_id = '$apartment_id'";

    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Flats</title>
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css"
    rel="stylesheet"
    />
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/manage_flats.css">
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
           onclick="return confirm('Are you sure you want to Logout?')">Logout</a>
    </div>
</div>

<!-- MAIN LAYOUT -->
<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="dashboard.php"><i class="ri-box-3-fill"></i> Dashboard</a>
        <a href="add_flat.php"><i class="ri-file-add-line"></i> Add Flat</a>
        <a href="manage_flats.php" class="active"><i class="ri-home-gear-fill"></i> Manage Flats</a>
        <a href="payments.php"><i class="ri-hand-coin-fill"></i> Payments</a>
        <a href="profile.php"><i class="ri-account-circle-line"></i> profile</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Manage Flats</h1>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Flat No</th>
                        <th>Flat Type</th>
                        <th>Maintanance/Month</th>
                        <th>Owner</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($row['flat_no']); ?></td>
                            <td><?= htmlspecialchars($row['flat_type']); ?></td>
                            <td><?= htmlspecialchars($row['maintanance']); ?></td>
                            <td>
                                <?= $row['owner_name'] 
                                    ? htmlspecialchars($row['owner_name']) 
                                    : "Not Assigned"; ?>
                            </td>
                            <td>
                                <?= $row['phone'] 
                                    ? htmlspecialchars($row['phone']) 
                                    : "-"; ?>
                            </td>
                            <td>
                                <?= $row['email'] 
                                    ? htmlspecialchars($row['email']) 
                                    : "-"; ?>
                            </td>
                            <td>
                                <a href="edit_flat.php?id=<?= $row['id']; ?>" class="btn edit">Edit</a>
                                <a href="delete_flat.php?id=<?= $row['id']; ?>" 
                                class="btn delete"
                                onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No Flats Found</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

</body>
</html>
