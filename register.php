<?php
session_start();

include("db.php");
$conn = dbconnect();

$error = "";
$registered = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM apartments WHERE email='$email'";
    $response = mysqli_query($conn, $sql);

    if (mysqli_num_rows($response) !== 1) {

        $sql = "INSERT INTO apartments(name,address,email,password)
                VALUES('$name','$address','$email','$password')";
        $response = mysqli_query($conn, $sql);

        if ($response) {
            $registered = "Registered Successfully";

            ?>
            <script>
                setTimeout(() => {
                    if (confirm("Registration successful! Go to Login page?")) {
                        window.location.href = "login.php";
                    }
                }, 2500);
            </script>
            <?php

        } else {
            $error = "Problem to register!!";
        }

    } else {
        $error = "Apartment Already Exists";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Apartment Management System</title>
    <link rel="icon" href="./assets/icon.png" type="image/png">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<a href="index.html" class="back-btn">← Back</a>

<div class="auth-wrapper">
    <div class="auth-card">

        <h2>Create Apartment</h2>
        <p class="subtitle">Register your apartment</p>

        <?php if ($error != "") { ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php } ?>

        <?php if ($registered != "") { ?>
            <div class="success-box"><?php echo $registered; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="input-group">
                <input type="text" name="name" placeholder="Apartment Name" required>
            </div>

            <div class="input-group">
                <textarea name="address" placeholder="Apartment Address" required></textarea>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email address" required>
            </div>

            <div class="input-group password-box">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span onclick="togglePassword()">👁</span>
            </div>

            <button type="submit" name="register" class="auth-btn">
                Register
            </button>
        </form>

        <div class="bottom-text">
            Already have an account?
            <a href="login.php">Login</a>
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
