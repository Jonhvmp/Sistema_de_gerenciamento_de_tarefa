<?php
session_start();
include '../config/database.php';
include '../models/User.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cria uma conexão com o banco de dados
$db = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($db->connect_error) {
    die("Conexão falhou: " . $db->connect_error);
}

// Cria uma instância da classe User
$user = new User($db);

// Obtém o ID do usuário da sessão
$user_id = $_SESSION['user_id'];

// Obtém os detalhes do usuário
if ($user->getUserById($user_id)) {
    $user_name = $user->getName();
    $user_email = $user->getEmail();
    $profile_picture = $user->getProfilePicture();
} else {
    echo "Usuário não encontrado.";
}

// Mensagem de feedback
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizar perfil
    if (isset($_POST['update_profile'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        // Atualizar o perfil do usuário
        if ($user->updateProfile($user_id, $name, $email)) {
            $_SESSION['message'] = "Perfil atualizado com sucesso.";
            header("Location: profile.php");
            exit();
        } else {
            $message = "Erro ao atualizar perfil.";
        }
    }
    // Alterar senha
    elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($user->changePassword($user_id, $current_password, $new_password, $confirm_password)) {
            $_SESSION['message'] = "Senha alterada com sucesso.";
        } else {
            $message = "Erro ao alterar senha.";
        }
    }
    // Upload da foto de perfil
    elseif (isset($_POST['upload_picture'])) {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0755, true);
            }
            $file = $_FILES['profile_picture'];
            $file_name = basename($file['name']);
            $file_path = "../uploads/" . $file_name;

            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                if ($user->updateProfilePicture($user_id, $file_path)) {
                    $_SESSION['message'] = "Foto de perfil atualizada com sucesso.";
                } else {
                    $message = "Erro ao atualizar foto de perfil.";
                }
            } else {
                $message = "Erro ao fazer upload da foto.";
            }
        }
    }
}

// Mensagem de feedback
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <?php include '../templates/head.php'; ?>
    <title>Perfil</title>
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h2>Perfil</h2>

        <?php if ($message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
            </div>
            <button type="button" id="edit-button" class="btn btn-secondary">Editar</button>
            <button type="submit" name="update_profile" class="btn btn-primary" style="display:none;" id="save-button">Salvar Alterações</button>
        </form>

        <hr>

        <h3>Alterar Senha</h3>
        <button type="button" id="change-password-button" class="btn btn-secondary">Alterar Senha</button>
        <form method="POST" id="change-password-form" style="display:none;">
            <div class="mb-3">
                <label for="current_password" class="form-label">Senha Atual</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Salvar Alteração de Senha</button>
        </form>

        <hr>

        <h3>Foto de Perfil</h3>
        <button type="button" id="upload-picture-button" class="btn btn-secondary">Adicionar Foto de Perfil</button>
        <form method="POST" enctype="multipart/form-data" id="upload-picture-form" style="display:none;">
            <div class="mb-3">
                <input type="file" class="form-control" name="profile_picture" required>
            </div>
            <button type="submit" name="upload_picture" class="btn btn-primary">Upload Foto</button>
        </form>

        <div class="mt-3">
            <?php if (!empty($profile_picture)): ?>
                <p>Você possui uma foto de perfil.</p>
            <?php else: ?>
                <p>Você não possui uma foto de perfil. Adicione uma!</p>
            <?php endif; ?>
        </div>

        <div class="mt-4 text-center">
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>

    <script>
        document.getElementById('edit-button').addEventListener('click', function() {
            document.getElementById('name').removeAttribute('readonly');
            document.getElementById('email').removeAttribute('readonly');
            this.style.display = 'none';
            document.getElementById('save-button').style.display = 'inline-block';
        });

        document.getElementById('change-password-button').addEventListener('click', function() {
            document.getElementById('change-password-form').style.display = 'block';
            this.style.display = 'none';
        });

        document.getElementById('upload-picture-button').addEventListener('click', function() {
            document.getElementById('upload-picture-form').style.display = 'block';
            this.style.display = 'none';
        });
    </script>

    <?php include '../templates/footer.php'; ?>
</body>
</html>