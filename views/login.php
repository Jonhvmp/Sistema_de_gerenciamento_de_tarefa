<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include '../templates/css.php'; ?>
</head>
<body>
    <?php include '../templates/header.php'; ?>
    <div class="container">
        <form action="../controllers/auth.php" method="POST">
            <h2>Login</h2>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <a href="../views/register.php">Register</a>
    </div>
    <?php include '../templates/footer.php'; ?>
</body>
</html>
