<?php
session_start();

include '../config/database.php';
include '../models/User.php';

$database = new Database();
$db = $database -> getConnection();
$user = new User($db);

if (isset($_POST['register'])) {
    $user -> name = $_POST['name'];
    $user -> email = $_POST['email'];
    $user -> password = $_POST['password'];

    if ($user -> register()) {
        $_SESSION['message'] = "Usúario registrado com sucesso!";
        header("Location: ../views/login.php");
    } else {
        $_SESSION['message'] = "Usuário não registrado!";
        header("Location: ../views/login.php");
    }
}

if (isset($_POST['login'])) {
    $user -> email = $_POST['email'];
    $user -> password = $_POST['password'];

    if ($user -> login()) {
        $_SESSION['user_id'] = $user -> id;
        $_SESSION['user_name'] = $user -> name;
        header("Location: ../views/dashboard.php");
    } else {
        $_SESSION['message'] = "Usuário não logado!";
        header("Location: ../views/login.php");
    }
}