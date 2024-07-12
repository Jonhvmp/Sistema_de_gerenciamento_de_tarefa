<?php
session_start();
include '../config/database.php'; // Inclua a conexão com o banco de dados

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifique se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Redirecionar para o dashboard se já estiver logado
    header("Location: ../views/dashboard.php");
    exit();
}

// Verificar se o token foi fornecido
if (!isset($_GET['token'])) {
    header("Location: ../views/login.php");
    exit();
}

$token = cleanInput($_GET['token']);

// Verificar se o token é válido e não foi usado
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Token inválido ou já usado
    header("Location: ../views/login.php");
    exit();
}

// Opcional: buscar o ID do usuário se precisar referenciá-lo depois
$user = $result->fetch_assoc();
$user_id = $user['id'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../templates/head.php'; ?>
    <link rel="stylesheet" href="../assets/css/reset_password.css">
    <title>Redefinir Senha</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h2>Redefinir Senha</h2>
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
                <form action="../controllers/auth.php" method="POST" id="reset-password-form">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="reset_password" class="btn btn-primary">Redefinir Senha</button>
                </form>
                <div class="mt-4 text-center">
                    <p>Já lembrou da senha? <a href="../views/login.php" class="link-primary">Voltar ao Login</a></p>
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
