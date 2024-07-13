<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/database.php';
    $database = new Database();
    $conn = $database->getConnection();

    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $title, $description);
    if ($stmt->execute()) {
        header('Location: ../views/dashboard.php');
        exit();
    } else {
        echo "Erro ao adicionar tarefa.";
    }
} else {
    header('Location: ../views/dashboard.php');
    exit();
}
?>
