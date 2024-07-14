<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved
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
<!-- 
Author: Jonh Alex Paz de Lima
All rights reserved 
-->

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../templates/head.php'; ?>
    <title>Criar Conta</title>
    <style>
        /* Estilos específicos para a página de registro */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa; /* Cor de fundo suave */
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
            width: 100%;
            max-width: 400px; /* Largura máxima para telas pequenas */
            margin: 20px auto; /* Centraliza horizontalmente */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.6s forwards;
            background: linear-gradient(135deg, #f8f9fa 25%, #ffffff 100%);
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .card {
            border: none; /* Remove bordas do card */
            transition: transform 0.3s ease;
            animation: slideUp 0.6s 0.1s forwards; /* Slight delay for staggered effect */
        }

        .card:hover {
            transform: scale(1.02);
        }

        form {
            display: flex;
            flex-direction: column;
            animation: slideUp 0.6s 0.2s forwards; /* Slight delay for staggered effect */
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
            color: #343a40;
            transition: color 0.3s;
            animation: fadeInLeft 0.8s forwards;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        label:hover {
            color: #5cb85c;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.3s;
            animation: fadeInRight 0.8s forwards;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        input:focus {
            border-color: #5cb85c; /* Cor da borda ao focar */
            outline: none; /* Remove outline padrão */
            box-shadow: 0 0 5px rgba(92, 184, 92, 0.5);
            transform: scale(1.02);
        }

        button {
            padding: 10px 15px;
            background: linear-gradient(45deg, #5cb85c, #4cae4c);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
            animation: fadeInBottom 1s forwards;
        }

        @keyframes fadeInBottom {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        button:hover {
            background: linear-gradient(45deg, #4cae4c, #5cb85c);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(76, 174, 76, 0.5);
        }

        button::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.15);
            transition: all 0.3s;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
        }

        button:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545; /* Cor de erro do Bootstrap */
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        .btn-primary {
            padding: 10px 20px;
            font-size: 1.1rem;
        }

        /* Estilos específicos para a página de registro */

        .col-lg-5 {
            width: 100%; /* Limita a largura em telas grandes */
            flex: 0 0 auto; /* Garante que não seja flexível */
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .container {
                padding: 15px; /* Menos padding em telas menores */
            }

            .btn-primary {
                font-size: 1rem; /* Tamanho menor para botões */
            }

            input {
                margin-bottom: 15px; /* Menor margem em telas pequenas */
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 100%; /* Largura total em telas médias e grandes */
                padding: 30px; /* Padding adicional */
            }

            .btn-primary {
                font-size: 1.2rem; /* Tamanho maior para botões em telas grandes */
            }

            input {
                margin-bottom: 25px; /* Maior margem em telas maiores */
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 600px; /* Largura máxima em telas muito grandes */
            }
        }

        .input-group .btn, .input-group .form-control {
            height: calc(2.25rem + 2px);
            border-radius: 5px;
        }

        .btn-outline-secondary {
            color: white;
            border-color: #6c757d;
        }


        .bg-gray-100 {
            --tw-bg-opacity: 1;
            background: linear-gradient(45deg, black, #333333);
        }

        .mt-5 {
            margin-top: 20px !important;
        }
    </style>
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
