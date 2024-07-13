<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/register.css">
    <?php include '../templates/head.php'; ?>
    <title>Criar Conta</title>
</head>
<body class="bg-gray-100">

    <?php include '../templates/header.php'; ?>

        <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg rounded-lg bg-white" style="min-height: 450px;">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4 text-2xl font-semibold">Criar Sua Conta</h2>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                echo $_SESSION['error']; 
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="../controllers/auth.php" method="POST" novalidate class="needs-validation">
                            <div class="mb-4">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Por favor, insira seu nome.</div>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Por favor, insira um e-mail válido.</div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Senha</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="generate-password">Gerar</button>
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                        <i class="bi bi-eye-slash" id="password-icon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Por favor, insira sua senha.</div>
                                <small class="form-text text-muted">A senha deve ter pelo menos 6 caracteres, incluindo letras, números e caracteres especiais.</small>
                            </div>
                            <div class="mb-4">
                                <label for="confirm-password" class="form-label">Confirmar Senha</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-confirm-password">
                                        <i class="bi bi-eye-slash" id="confirm-password-icon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Por favor, confirme sua senha.</div>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary w-full">Registrar</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Já tem uma conta? <a href="login.php" class="text-blue-600">Faça login aqui</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include '../templates/footer.php'; ?>

    <!-- Modal para Erro -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Erro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-danger text-center">
                    <?php
                    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Exibir o modal se houver mensagem de erro
        document.addEventListener("DOMContentLoaded", function() {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            var errorMessage = document.querySelector('.modal-body').innerHTML.trim();
            if (errorMessage) {
                errorModal.show();
            }
        });

        // Bootstrap validation example
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

        // Gerador de senhas seguras
        document.getElementById('generate-password').addEventListener('click', function() {
            var length = 12,
                charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+",
                retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            document.getElementById('password').value = retVal;
            document.getElementById('confirm-password').value = retVal;
        });

        // Alternar visibilidade da senha
        document.getElementById('toggle-password').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var passwordIcon = document.getElementById('password-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        });

        document.getElementById('toggle-confirm-password').addEventListener('click', function() {
            var confirmPasswordInput = document.getElementById('confirm-password');
            var confirmPasswordIcon = document.getElementById('confirm-password-icon');
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmPasswordIcon.classList.remove('bi-eye-slash');
                confirmPasswordIcon.classList.add('bi-eye');
            } else {
                confirmPasswordInput.type = 'password';
                confirmPasswordIcon.classList.remove('bi-eye');
                confirmPasswordIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>
</html>
