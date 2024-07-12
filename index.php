<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Tarefas</title>
</head>
<body>

    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>Dashboard</h1>
        <h2>Bem-vindo, <?php echo $_SESSION['user_name']; ?></h2>
        <a href="../controllers/auth.php?logout=true">Logout</a>
    </div>

    <div>
        <h2>Tarefas</h2>
        <ul>
            <li>Estudar PHP</li>
            <li>Estudar JavaScript</li>
            <li>Estudar MySQL</li>
        </ul>
    </div>

    <?php include '../templates/footer.php'; ?>
    
</body>
</html>