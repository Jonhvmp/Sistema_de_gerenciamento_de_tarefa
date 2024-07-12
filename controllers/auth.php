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

    if (isset($_POST['forgot_password'])) {
        $email = cleanInput($_POST['email']);
        if (!empty($email)) {
            // Verificar se o e-mail está cadastrado
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $token = bin2hex(random_bytes(50)); // Gerar um token único
                $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
                $stmt->bind_param("ss", $token, $email);
                $stmt->execute();
    
                // Enviar o e-mail com o link de redefinição
                $resetLink = "http://localhost/Sistema_de_gerenciamento_de_tarefa/views/reset_password.php?token=" . $token;
                mail($email, "Redefinição de Senha", "Clique no link para redefinir sua senha: " . $resetLink);
    
                echo "Um link de redefinição de senha foi enviado para seu e-mail.";
            } else {
                echo "Esse e-mail não está cadastrado.";
            }
        } else {
            echo "Por favor, insira um e-mail.";
        }
    }

    if (isset($_POST['reset_password'])) {
        $token = cleanInput($_POST['token']);
        $new_password = cleanInput($_POST['new_password']);
        $confirm_password = cleanInput($_POST['confirm_password']);
    
        if ($new_password === $confirm_password) {
            // Verificar se o token é válido
            $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expires > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expires = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();
    
                echo "Senha redefinida com sucesso! Você pode fazer login agora.";
            } else {
                echo "Token inválido ou expirado.";
            }
        } else {
            echo "As senhas não correspondem.";
        }
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php'; // Certifique-se de que o caminho está correto

    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Seu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'jonhpaz08@gmail.com'; // Seu e-mail
        $mail->Password = 'sua_senha'; // Sua senha
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinatários
        $mail->setFrom('jonhpaz08@gmail.com', 'Jonh Alex');
        $mail->addAddress($email); // O e-mail do destinatário

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Redefinição de Senha';
        $mail->Body = 'Clique no link para redefinir sua senha: <a href="' . $resetLink . '">' . $resetLink . '</a>';

        $mail->send();
        echo "Um link de redefinição de senha foi enviado para seu e-mail.";
    } catch (Exception $e) {
        echo "Não foi possível enviar o e-mail. Erro: {$mail->ErrorInfo}";
    }

    
}
?>
