<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config/database.php';

// Função para limpar dados de entrada
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Lógica de Login
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);

        if (!empty($email) && !empty($password)) {
            // Busca no banco de dados pelo usuário
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // Verifica a senha
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: ../views/dashboard.php");
                    exit();
                } else {
                    // Senha incorreta
                    echo "Invalid email or password.";
                }
            } else {
                // Usuário não encontrado
                echo "Invalid email or password.";
            }
        } else {
            // Campos vazios
            echo "Please fill in all fields.";
        }
    }

    if (isset($_POST['register'])) {
        // Lógica de Registro
        $name = cleanInput($_POST['name']);
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);
        $confirm_password = cleanInput($_POST['confirm-password']);
    
        if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
            // Verifica se as senhas correspondem
            if ($password === $confirm_password) {
                // Verifica os requisitos de segurança da senha
                if (strlen($password) >= 6 && 
                    preg_match("/[A-Za-z]/", $password) && 
                    preg_match("/[0-9]/", $password) && 
                    preg_match("/[!@#$%^&*()_+]/", $password)) {
    
                    // Hash da senha
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    // Insere o novo usuário no banco de dados
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
                    if ($stmt->execute()) {
                        header("Location: ../views/login.php");
                        exit();
                    } else {
                        echo "An error occurred. Please try again.";
                    }
                } else {
                    // Senha não atende aos requisitos
                    echo "Password must be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
                }
            } else {
                // Senhas não correspondem
                echo "Passwords do not match.";
            }
        } else {
            // Campos vazios
            echo "Please fill in all fields.";
        }
    }
}    
?>
