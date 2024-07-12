<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <?php include '../templates/css.php'; ?>
    <title>Perfil</title>
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h2>Perfil</h2>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Membro desde:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>
