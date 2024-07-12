<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../views/dashboard.php");
    exit();
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
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
</head>
<body class="bg-gray-100">

    <?php include '../templates/header.php'; ?>

    <div class="container mx-auto mt-10">
        <div class="flex justify-center">
            <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm">
                <h2 class="text-center text-2xl font-bold mb-6">Login</h2>
                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 text-red-700 border border-red-300 rounded-lg p-3 mb-4">
                        Senha ou e-mail inválido.
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
            </div>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
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
