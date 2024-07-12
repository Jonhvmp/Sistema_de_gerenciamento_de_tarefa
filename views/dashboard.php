<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <?php include '../templates/css.php'; ?>
    <title>Dashboard</title>
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Bem-vindo ao Painel</h1>
        <p>Suas tarefas serão exibidas aqui.</p>
        <!-- Aqui você pode incluir mais funcionalidades como listar tarefas -->
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>
