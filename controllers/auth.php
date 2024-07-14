<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php'; // Certifique-se de que este caminho está correto
require '../vendor/autoload.php'; // Certifique-se de que o caminho está correto

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Função para limpar dados de entrada
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Função para verificar se o domínio do e-mail é válido
function checkEmailDomain($email) {
    $domain = substr(strrchr($email, "@"), 1);
    return checkdnsrr($domain, "MX") || checkdnsrr($domain, "A");
}

// Conexão com o banco de dados
$database = new Database();
$conn = $database->getConnection();

// Verifica se o método de solicitação é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Processa o login
    if (isset($_POST['login'])) {
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);

        // Verifica se o domínio do email é válido
        if (!checkEmailDomain($email)) {
            $_SESSION['error'] = "O domínio do e-mail não é válido ou não pode ser encontrado.";
            header("Location: ../views/login.php");
            exit();
        }

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
                }
            } else {
                $_SESSION['error'] = "E-mail ou senha inválidos.";
            }
        } else {
            $_SESSION['error'] = "Por favor, preencha todos os campos.";
        }
        header("Location: ../views/login.php");
        exit();
    }

    // Processa o registro
    if (isset($_POST['register'])) {
        $name = cleanInput($_POST['name']);
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);
        $confirm_password = cleanInput($_POST['confirm-password']);

        // Verifica se as senhas coincidem
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "As senhas não coincidem.";
            header("Location: ../views/register.php");
            exit();
        }

        // Verifica se o domínio do email é válido
        if (!checkEmailDomain($email)) {
            $_SESSION['error'] = "O domínio do e-mail não é válido ou não pode ser encontrado.";
            header("Location: ../views/register.php");
            exit();
        }

        // Verifica se a senha atende aos critérios de segurança
        if (strlen($password) >= 6 && 
            preg_match("/[A-Za-z]/", $password) && 
            preg_match("/[0-9]/", $password) && 
            preg_match("/[!@#$%^&*()_+]/", $password)) {

            // Verifica se o e-mail já está registrado
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error'] = "O e-mail já está registrado.";
                header("Location: ../views/register.php");
                exit();
            }

            // Insere o novo usuário no banco de dados
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
        header("Location: ../views/register.php");
        exit();
    }

    // Processa a solicitação de redefinição de senha
    if (isset($_POST['forgot_password'])) {
        $email = cleanInput($_POST['email']);
        if (!empty($email)) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $token = bin2hex(random_bytes(50));
                $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
                $stmt->bind_param("ss", $token, $email);
                $stmt->execute();

                $resetLink = "http://localhost/Sistema_de_gerenciamento_de_tarefa/views/reset_password.php?token=" . $token;

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jonhpaz08@gmail.com';
                    $mail->Password = 'rojp njao ycuj ywnq';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('jonhpaz08@gmail.com', 'Dev J. Alex');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Alterar Senha';
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                            <div style='max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);'>
                                <h2 style='text-align: center; color: #333;'>Redefinição de Senha</h2>
                                <p style='font-size: 16px; color: #666;'>Você solicitou uma redefinição de senha. Clique no link abaixo para redefinir sua senha:</p>
                                <p style='text-align: center;'>
                                    <a href='" . $resetLink . "' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Redefinir Senha</a>
                                </p>
                                <p style='font-size: 16px; color: #666;'>Se você não solicitou isso, ignore este e-mail.</p>
                            </div>
                        </div>
                    ";

                    $mail->send();
                    $_SESSION['alert'] = [
                        'type' => 'alert-success',
                        'message' => 'Um link de redefinição de senha foi enviado para seu e-mail.'
                    ];
                } catch (Exception $e) {
                    $_SESSION['alert'] = [
                        'type' => 'alert-danger',
                        'message' => "Não foi possível enviar o e-mail. Erro: {$mail->ErrorInfo}"
                    ];
                }
            } else {
                $_SESSION['alert'] = [
                    'type' => 'alert-danger',
                    'message' => 'Esse e-mail não está cadastrado.'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'alert-danger',
                'message' => 'Por favor, insira um e-mail.'
            ];
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Processa a redefinição de senha
    if (isset($_POST['reset_password'])) {
        $token = cleanInput($_POST['token']);
        $new_password = cleanInput($_POST['new_password']);
        $confirm_password = cleanInput($_POST['confirm_password']);

        if ($new_password === $confirm_password) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expires > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expires = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();

                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Senha redefinida com sucesso! Você pode fazer login agora.'
                ];
                header("Location: ../views/login.php");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => 'Token inválido ou expirado.'
                ];
                header("Location: ../views/login.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'As senhas não correspondem.'
            ];
            header("Location: ../views/reset_password.php?token=" . $token);
            exit();
        }
    }
}
?>
