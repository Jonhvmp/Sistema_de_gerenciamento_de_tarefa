<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/register.css">
    <?php include '../templates/css.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <title>Register</title>
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h2 class="text-center mb-4">Create Your Account</h2>
                <form action="../controllers/auth.php" method="POST" novalidate class="needs-validation">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">
                            Please enter your name.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Please enter a valid email.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="generate-password">Generate</button>
                            <button type="button" class="btn btn-outline-secondary" id="toggle-password"><i class="bi bi-eye-slash" id="password-icon"></i></button>
                        </div>
                        <div class="invalid-feedback">
                            Please enter your password.
                        </div>
                        <small class="form-text text-muted">Password must be at least 6 characters long, include letters, numbers, and special characters.</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggle-confirm-password"><i class="bi bi-eye-slash" id="confirm-password-icon"></i></button>
                        </div>
                        <div class="invalid-feedback">
                            Please confirm your password.
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script>
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
