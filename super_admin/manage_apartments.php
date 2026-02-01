<?php
    include "../includes/auth.php";

    if ($_SESSION['role'] !== 'superadmin') {
        header("Location: ../login.php");
        exit;
    }

    include "../db.php";
    $conn = dbconnect();

    $sql = "select * from apartments";

    $result = mysqli_query($conn,$sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Apartments</title>
    <link rel="icon" href="../assets/icon.png" type="image/png">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/manage_apartments.css">
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
        <h1>Manage Apartments</h1>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Apartment Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Actions</th>
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
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td>
                                <?= $row['email'] 
                                    ? htmlspecialchars($row['email']) 
                                    : "-"; ?>
                            </td>
                            <td>
                                <a href="edit_apartment.php?id=<?= $row['id']; ?>" class="action-btn edit">Edit</a>
                                <a href="delete_apartment.php?id=<?= $row['id']; ?>" 
                                class="action-btn delete"
                                onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No Apartments Found</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

</body>
</html>
