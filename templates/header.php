<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header style="display: flex; flex-wrap: nowrap; justify-content: space-evenly; flex-direction: row; align-items: center;">
<style>
    /* assets/css/header.css */

    /* Reseta estilos padrões e define a fonte global */
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    }

    /* Estilos do header */
    header {
    background: linear-gradient(135deg, #1f1f1f, #2c2c2c);
    color: #fff;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
    overflow: hidden;
    }

    /* Título do header */
    header h1 {
    margin: 0;
    font-size: 2.5em;
    letter-spacing: 1px;
    background: linear-gradient(45deg, #4CAF50, #2c6e49);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    transition: background 0.3s ease, transform 0.3s ease;
    position: relative;
    z-index: 1;
    }

    /* Efeito de hover no título do header */
    header h1:hover {
    background: linear-gradient(45deg, #45a049, #1d5a34);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    transform: scale(1.05);
    }

    /* Estilos de navegação */
    nav {
    margin-top: 20px;
    }

    /* Lista de navegação */
    nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
    }

    /* Itens da navegação */
    nav ul li {
    position: relative;
    margin: 0 20px;
    }

    /* Links da navegação */
    nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    position: relative;
    padding: 10px 15px;
    transition: color 0.3s ease, transform 0.3s ease;
    }

    /* Efeito de fundo animado nos links da navegação */
    nav ul li a::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    width: 0;
    height: 2px;
    background: #4CAF50;
    transition: width 0.3s ease, transform 0.3s ease;
    transform: translateX(-50%);
    }

    nav ul li a:hover::after {
    width: 100%;
    transform: translateX(-50%) scaleY(1.5);
    }

    /* Efeito de transformação no hover */
    nav ul li a:hover {
    color: #4CAF50;
    transform: translateY(-2px);
    }

    /* Estilos para a imagem de perfil */
    .profile-picture {
    margin-left: auto; /* Move para a direita */
    display: flex;
    justify-content: center;
    margin-top: 20px;
    }

    /* Imagem de perfil */
    .profile-picture img {
    border-radius: 50%; /* Faz a imagem ficar redonda */
    border: 4px solid #007bff; /* Borda opcional */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
    }

    /* Efeito de hover na imagem de perfil */
    .profile-picture img:hover {
    transform: scale(1.1);
    filter: brightness(1.15);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
    }
    
</style>
    <h1>Gerenciador de Tarefas</h1>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="../views/dashboard.php">Painel</a></li>
                <li><a href="../views/profile.php">Perfil</a></li>
                <li><a href="../views/tasks.php">Tarefas</a></li>
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
