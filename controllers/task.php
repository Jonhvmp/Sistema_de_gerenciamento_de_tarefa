<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

include_once '../config/database.php'; // Inclua o arquivo apenas uma vez

// Cria uma instância da classe Database
$database = new Database();
$conn = $database->getConnection();

// Função para adicionar uma nova tarefa
function addTask($conn, $title, $description, $due_date, $user_id) {
    $query = "INSERT INTO tasks (title, description, due_date, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $description, $due_date, $user_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Função para editar uma tarefa existente
function editTask($conn, $task_id, $title, $description, $due_date) {
    $query = "UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $description, $due_date, $task_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Função para excluir uma tarefa
function deleteTask($conn, $task_id) {
    $query = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $task_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Processar as ações baseadas no método HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $title = $_POST['title'];
                $description = $_POST['description'];
                $due_date = $_POST['due_date'];
                $user_id = $_SESSION['user_id'];
                
                if (addTask($conn, $title, $description, $due_date, $user_id)) {
                    echo json_encode(['success' => true, 'message' => 'Tarefa adicionada com sucesso!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar tarefa.']);
                }
                break;
                
            case 'edit':
                $task_id = $_POST['task_id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $due_date = $_POST['due_date'];
                
                if (editTask($conn, $task_id, $title, $description, $due_date)) {
                    echo json_encode(['success' => true, 'message' => 'Tarefa atualizada com sucesso!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar tarefa.']);
                }
                break;
                
            case 'delete':
                $task_id = $_POST['task_id'];
                
                if (deleteTask($conn, $task_id)) {
                    echo json_encode(['success' => true, 'message' => 'Tarefa excluída com sucesso!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao excluir tarefa.']);
                }
                break;
        }
    }
}
?>
