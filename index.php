<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Redireciona para a página de dashboard
    header('Location: views/dashboard.php');
    exit();
} else {
    // Redireciona para a página de login
    header('Location: views/login.php');
    exit();
}
?>