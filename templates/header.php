<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <link rel="stylesheet" href="../assets/css/header.css">
    <h1>Gerenciador de tarefas</h1>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="../views/dashboard.php">Painel</a></li>
                <li><a href="../views/profile.php">Perfil</a></li>
                <li><a href="../controllers/logout.php">Sair</a></li>
            <?php else: ?>
                <li><a href="../views/login.php">Conecte-se</a></li>
                <li><a href="../views/register.php">Registro</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
