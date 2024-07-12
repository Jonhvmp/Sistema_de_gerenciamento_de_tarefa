<?php
session_start();
include '../config/database.php';
include '../models/User.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cria uma instância da classe User
$user_id = $_SESSION['user_id'];
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$user = new User($db);

// Obtém os detalhes do usuário
if (!$user->getUserById($user_id)) {
    die("Erro ao recuperar os detalhes do usuário.");
}

// Mensagem de feedback
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));

        // Atualiza os dados do usuário
        $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssi", $name, $email, $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Perfil atualizado com sucesso.";
            header("Location: profile.php");
            exit();
        } else {
            $message = "Erro ao atualizar perfil.";
        }
    } elseif (isset($_POST['change_password'])) {
        // Alterar a senha
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verifica a senha atual
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        if (password_verify($current_password, $user_data['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $query = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("si", $hashed_password, $user_id);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Senha alterada com sucesso.";
                } else {
                    $message = "Erro ao alterar senha.";
                }
            } else {
                $message = "As senhas não coincidem.";
            }
        } else {
            $message = "Senha atual está incorreta.";
        }
    } elseif (isset($_POST['upload_picture'])) {
        // Upload da foto de perfil
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0755, true); // Cria o diretório se não existir
            }

            $file = $_FILES['profile_picture'];
            $file_name = basename($file['name']);
            $file_path = "../uploads/" . $file_name;

            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("si", $file_path, $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Foto de perfil atualizada com sucesso.";
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
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" readonly>
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
            <?php if (!empty($user->getProfilePicture())): ?>
                <p>Você possui uma foto de perfil.</p>
                <img src="<?php echo htmlspecialchars($user->getProfilePicture()); ?>" alt="Foto de Perfil" style="max-width: 200px;">
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
