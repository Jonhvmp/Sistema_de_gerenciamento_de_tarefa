<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header style="display: flex;
    flex-wrap: nowrap;
    justify-content: space-evenly;
    flex-direction: row;
    align-items: center;">
    <link rel="stylesheet" href="../assets/css/header.css">
    <h1>Gerenciador de Tarefas</h1>
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
    <div class="profile-picture" style="margin-left: 0;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php 
            include '../config/database.php';
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            ?>
            <?php if (!empty($user['profile_picture'])): ?>
                <a href="../views/profile.php">
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Foto de Perfil" class="rounded-circle" style="width: 50px; height: 50px;">
                </a>
            <?php else: ?>
                <a href="../views/profile.php">
                    <i class="bi bi-person-circle" style="font-size: 50px;"></i>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>
