<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <title>Esqueci a Senha</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Esqueci a Senha</h2>
        <div id="alert-container">
            <?php
            if (isset($_SESSION['alert'])) {
                echo '<div class="alert alert-dismissible ' . $_SESSION['alert']['type'] . ' fade show" role="alert">';
                echo $_SESSION['alert']['message'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
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
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
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
