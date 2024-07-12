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
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);

        if (!empty($email) && !empty($password)) {
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: ../views/dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "E-mail ou senha inválidos.";
                    header("Location: ../views/login.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "E-mail ou senha inválidos.";
                header("Location: ../views/login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Por favor, preencha todos os campos.";
            header("Location: ../views/login.php");
            exit();
        }
    }

    if (isset($_POST['register'])) {
        // Lógica de Registro
        $name = cleanInput($_POST['name']);
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);
        $confirm_password = cleanInput($_POST['confirm-password']);
    
        if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error'] = "Esse e-mail já está cadastrado.";
                header("Location: ../views/register.php");
                exit();
            }

            if ($password === $confirm_password) {
                if (strlen($password) >= 6 && 
                    preg_match("/[A-Za-z]/", $password) && 
                    preg_match("/[0-9]/", $password) && 
                    preg_match("/[!@#$%^&*()_+]/", $password)) {
                    
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
                    if ($stmt->execute()) {
                        header("Location: ../views/login.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "Ocorreu um erro. Por favor, tente novamente.";
                    }
                } else {
                    $_SESSION['error'] = "A senha deve ter pelo menos 6 caracteres e incluir pelo menos uma letra, um número e um caractere especial.";
                }
            } else {
                $_SESSION['error'] = "As senhas não correspondem.";
            }
        } else {
            $_SESSION['error'] = "Por favor, preencha todos os campos.";
        }
        header("Location: ../views/register.php");
        exit();
    }
}
?>
