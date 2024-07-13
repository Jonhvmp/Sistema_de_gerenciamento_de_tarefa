<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../views/dashboard.php");
    exit();
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../templates/head.php'; ?>
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php include '../templates/header.php'; ?>

    <div class="container mx-auto mt-10">
        <div class="flex justify-center">
            <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm">
                <h2 class="text-center text-2xl font-bold mb-6">Login</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <form action="../controllers/auth.php" method="POST" novalidate class="needs-validation">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium">Endereço de e-mail</label>
                        <input type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-green-200" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Por favor, insira um e-mail válido.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium">Senha</label>
                        <input type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-green-200" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Por favor, insira sua senha.
                        </div>
                    </div>
                    <div class="mb-4">
                        <input type="checkbox" class="mr-2" id="remember" name="remember">
                        <label class="inline text-gray-600" for="remember">Lembrar-me</label>
                    </div>
                    <button type="submit" name="login" class="w-full bg-green-500 text-white rounded-md p-2 hover:bg-green-600 transition duration-200">Entrar</button>
                </form>
                <div class="mt-4 text-center">
                    <a href="../views/forgot_password.php" class="text-blue-500 hover:underline">Esqueceu a senha?</a>
                    <p style="margin-top: 0; margin-bottom: 0; padding:2.5px">ou</p>
                    <a href="../views/register.php" class="text-blue-500 hover:underline">Novo usuário? Cadastre-se</a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Exemplo de validação do Bootstrap
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
</body>
</html>
