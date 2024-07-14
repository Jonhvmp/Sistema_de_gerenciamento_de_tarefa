<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    unset($_SESSION['email']);
}

if (isset($_SESSION['password'])) {
    $password = $_SESSION['password'];
    unset($_SESSION['password']);
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<!-- 
Author: Jonh Alex Paz de Lima
All rights reserved 
-->

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../templates/head.php'; ?>
    <link rel="stylesheet" href="../assets/css/forgot_password.css">
    <title>Esqueci a Senha</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h2>Esqueci a Senha</h2>
            </div>
            <div class="card-body">
                <div id="alert-container">
                    <?php
                    if (isset($_SESSION['alert'])) {
                        echo '<div class="alert alert-' . $_SESSION['alert']['type'] . ' alert-dismissible fade show" role="alert">';
                        echo $_SESSION['alert']['message'];
                        echo '</div>';
                        unset($_SESSION['alert']);
                    }
                    ?>
                </div>
                <form id="forgot-password-form" method="POST" action="../controllers/auth.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Endereço de E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Por favor, insira um e-mail válido.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="forgot_password">Enviar Link de Redefinição</button>
                </form>

                <!-- Links para voltar ao login ou cadastro -->
                <div class="mt-4 text-center">
                    <p>Já lembrou da senha? <a href="../views/login.php" class="link-primary">Voltar ao Login</a></p>
                    <p>Ainda não tem uma conta? <a href="../views/register.php" class="link-primary">Criar uma Conta</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fechar automaticamente os alertas após 5 segundos
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>
</html>
