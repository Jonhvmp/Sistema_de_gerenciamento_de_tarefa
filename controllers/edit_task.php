<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

include_once '../config/database.php';
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? '';

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $status, $task_id);

    if ($stmt->execute()) {
        header('Location: ../views/dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = 'Erro ao atualizar tarefa.';
    }
} else {
    $task_id = $_GET['id'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $task = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="../assets/css/form.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <main>
        <h2>Editar Tarefa</h2>
        <form action="edit_task.php" method="POST">
            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
            <label for="title">Título:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            <label for="description">Descrição:</label>
            <textarea name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
            <label for="status">Status:</label>
            <select name="status">
                <option value="pending" <?php echo ($task['status'] == 'pending') ? 'selected' : ''; ?>>Pendente</option>
                <option value="completed" <?php echo ($task['status'] == 'completed') ? 'selected' : ''; ?>>Concluída</option>
                <option value="overdue" <?php echo ($task['status'] == 'overdue') ? 'selected' : ''; ?>>Atrasada</option>
            </select>
            <button type="submit">Atualizar Tarefa</button>
        </form>
    </main>
</body>
</html>
