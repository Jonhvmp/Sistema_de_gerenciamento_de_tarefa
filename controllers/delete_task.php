<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved

// delete_task.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $connection = (new Database())->getConnection();
    $stmt = $connection->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        header('Location: ../views/dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = 'Erro ao excluir tarefa.';
    }
} else {
    header('Location: ../views/dashboard.php');
}
