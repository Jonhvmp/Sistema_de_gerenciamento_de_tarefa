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
        <form action="../controllers/auth.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Endereço de E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" name="forgot_password" class="btn btn-primary">Enviar Link de Redefinição</button>
        </form>
    </div>
</body>
</html>
