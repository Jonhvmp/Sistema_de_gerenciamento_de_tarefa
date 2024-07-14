<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Redireciona para o dashboard se o usuário estiver logado
    header("Location: views/dashboard.php");
    exit();
} else {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: views/login.php");
    exit();
}
?>
