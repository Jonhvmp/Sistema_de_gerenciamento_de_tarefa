<?php
session_start();
include_once '../config/database.php';
include '../models/User.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cria uma instância da classe Database
$database = new Database();
$conn = $database->getConnection();

// Cria uma instância da classe User
$user = new User($conn);

$user_id = $_SESSION['user_id'];

if ($user->getUserById($user_id)) {
    $user_name = $user->getName();
    $user_email = $user->getEmail();
    $profile_picture = $user->getProfilePicture();
} else {
    echo "Usuário não encontrado.";
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        if ($user->updateProfile($user_id, $name, $email)) {
            $_SESSION['message'] = "Perfil atualizado com sucesso.";
            header("Location: profile.php");
            exit();
        } else {
            $message = "Erro ao atualizar perfil.";
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if ($user->changePassword($user_id, $current_password, $new_password, $confirm_password)) {
            $_SESSION['message'] = "Senha alterada com sucesso.";
        } else {
            $message = "Erro ao alterar senha.";
        }
    } elseif (isset($_POST['upload_picture'])) {
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
    <?php include '../templates/head.php'; ?>
    <title>Perfil</title>
    <style>
        /* assets/css/profile.css */

        /* Reseta estilos padrões e define a fonte global */
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        }

        /* Estilos gerais do corpo da página */
        body {
        background: linear-gradient(135deg, #f0f4f8, #d9e1e8);
        color: #333;
        font-size: 16px;
        line-height: 1.6;
        }

        /* Container principal do perfil */
        .container {
        width: 100%;
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        transition: all 0.5s ease;
        }

        /* Efeito de fundo do container com gradiente suave e animação */
        .container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0));
        z-index: 0;
        pointer-events: none;
        transition: opacity 0.5s ease;
        }

        /* Adicionando um efeito de leveza ao container ao passar o mouse */
        .container:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        /* Título do perfil */
        h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
        color: #4CAF50;
        position: relative;
        z-index: 1;
        background: linear-gradient(45deg, #4CAF50, #2c6e49);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: color 0.3s ease;
        }

        /* Mensagem de alerta */
        .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 16px;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        }

        .alert-success:hover {
        transform: scale(1.02);
        opacity: 0.9;
        }

        /* Formulário de perfil */
        form {
        position: relative;
        z-index: 1;
        }

        /* Espaçamento entre elementos */
        .mb-3 {
        margin-bottom: 25px;
        }

        .form-label {
        display: block;
        font-size: 18px;
        margin-bottom: 8px;
        color: #555;
        transition: color 0.3s ease;
        }

        .form-label:hover {
        color: #4CAF50;
        }

        .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f9f9f9;
        }

        .form-control:focus {
        border-color: #4CAF50;
        background: #fff;
        outline: none;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        }

        /* Botões */
        .btn {
        display: inline-block;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        margin-right: 10px;
        position: relative;
        z-index: 1;
        background: linear-gradient(45deg, #4CAF50, #2c6e49);
        color: #fff;
        }

        .btn-primary:hover {
        background: linear-gradient(45deg, #45a049, #1d5a34);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
        }

        .btn-secondary {
        background: linear-gradient(45deg, #6c757d, #5a6268);
        color: #fff;
        }

        .btn-secondary:hover {
        background: linear-gradient(45deg, #5a6268, #4e555b);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
        }

        /* Formulário de alteração de senha e upload de foto */
        #change-password-form, #upload-picture-form {
        display: none;
        }

        #change-password-form input, #upload-picture-form input {
        margin-top: 5px;
        }

        /* Estilo para foto de perfil */
        .profile-picture {
        text-align: center;
        position: relative;
        }

        /* Foto de perfil com efeitos de borda e sombra */
        .profile-picture img {
        max-width: 180px;
        max-height: 180px;
        border-radius: 50%;
        border: 5px solid #4CAF50;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Efeito de hover na foto de perfil */
        .profile-picture img:hover {
        transform: scale(1.1) rotate(2deg);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        /* Estilos adicionais para responsividade */
        @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        .form-control {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
            padding: 10px 20px;
        }

        .profile-picture img {
            max-width: 150px;
            max-height: 150px;
        }
        }

    </style>
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
            <a href="../controllers/logout.php" class="btn btn-secondary">Logout</a>
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