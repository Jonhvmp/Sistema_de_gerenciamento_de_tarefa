<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php';
require '../vendor/autoload.php'; // Certifique-se de que o caminho está correto

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                        $mail->Body = 'Clique no link para redefinir sua senha: <a href="' . $resetLink . '">' . $resetLink . '</a>';
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
    }

    if (isset($_POST['reset_password'])) {
        $token = cleanInput($_POST['token']);
        $new_password = cleanInput($_POST['new_password']);
        $confirm_password = cleanInput($_POST['confirm_password']);
    
        if ($new_password === $confirm_password) {
            // Verify if the token is valid
            $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expires > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                // Proceed with password reset
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expires = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();
    
                // Success message
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Senha redefinida com sucesso! Você pode fazer login agora.'
                ];
                header("Location: ../views/dashboard.php"); // Redirect to the dashboard
                exit();
            } else {
                // Invalid or expired token
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => 'Token inválido ou expirado.'
                ];
                header("Location: ../views/login.php"); // Redirect to the login page
                exit();
            }
        } else {
            // Passwords do not match
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
