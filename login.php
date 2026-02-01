<?php
session_start();

include("db.php");
$conn = dbconnect();

$error = "";

if (isset($_POST['login'])) {

    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    /* ===== SUPER ADMIN LOGIN ===== */
    if ($role === 'superadmin') {

        $sql = "SELECT * FROM superadmin WHERE email='$email'";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);

            if ($row['password'] == $password) {
                $_SESSION['role'] = 'superadmin';
                $_SESSION['user_id'] = $row['id'];

                header("Location:super_admin/dashboard.php");
                exit;
            } else {
                $error = "Wrong password";
            }
        } else {
            $error = "Super Admin not found";
        }
    }

    /* ===== APARTMENT ADMIN LOGIN ===== */
    elseif ($role === 'admin') {
        $sql = "SELECT * FROM apartments WHERE email='$email'";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);

            if ($row['password'] == $password) {
                $_SESSION['role'] = 'apartmentadmin';
                $_SESSION['apartment_id'] = $row['id'];
                $_SESSION['apartment_name'] = $row['name'];
                $_SESSION['apartment_email'] = $row['email'];

                header("Location:apartment_admin/dashboard.php");
                exit;
            } else {
                $error = "Wrong password";
            }
        } else {
            $error = "Apartment Admin not found";
        }
    }

    /* ===== FLAT OWNER LOGIN ===== */
    elseif ($role === 'owner') {
        // will be added later
    } else {
        $error = "Please select a role";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Apartment Management System</title>
    <link rel="icon" href="./assets/icon2.png" type="image/png">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<a href="index.html" class="back-btn">← Back</a>

<div class="auth-wrapper">
    <div class="auth-card">

        <h2>Welcome Back 👋</h2>
        <p class="subtitle">Login to your account</p>

        <?php if ($error != "") { ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="input-group">
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Apartment Admin</option>
                    <option value="owner">Flat Owner</option>
                </select>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email address" required>
            </div>

            <div class="input-group password-box">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span onclick="togglePassword()">👁</span>
            </div>

            <button type="submit" name="login" class="auth-btn">
                Login
            </button>
        </form>

        <div class="bottom-text">
            Don’t have an account?
            <a href="register.php">Register</a>
        </div>

    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
