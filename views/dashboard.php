<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once '../config/database.php'; // Inclua o arquivo apenas uma vez

// Cria uma instância da classe Database
$database = new Database();
$conn = $database->getConnection();

// Adiciona uma nova tarefa ao banco de dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    // Prepara e executa a consulta para adicionar a nova tarefa
    $query = "INSERT INTO tasks (title, description, due_date, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $description, $due_date, $user_id);

    if ($stmt->execute()) {
        $message = "Tarefa adicionada com sucesso!";
    } else {
        $message = "Erro ao adicionar tarefa: " . $stmt->error;
    }
}

// Recupera todas as tarefas do banco de dados
$query = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <?php include '../templates/head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <main>
        <section class="task-manager">
            <h2>Gerenciamento de Tarefas</h2>

            <?php if (isset($message)): ?>
                <div class="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <button id="add-task-btn">Adicionar Nova Tarefa</button>
            <div id="add-task-form" style="display:none;">
                <form id="task-form" method="post">
                    <label for="title">Título da Tarefa:</label>
                    <input type="text" id="title" name="title" required>
                    
                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description" required></textarea>
                    
                    <label for="due_date">Data de Vencimento:</label>
                    <input type="date" id="due_date" name="due_date" required>
                    
                    <button type="submit">Adicionar Tarefa</button>
                </form>
            </div>

            <div id="task-list">
                <ul>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($task = $result->fetch_assoc()): ?>
                            <li>
                                <?php echo htmlspecialchars($task['title']); ?>
                                <a href="../controllers/edit_task.php?id=<?php echo $task['id']; ?>">Editar</a>
                                <a href="../controllers/delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>Não há tarefas para exibir.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>

        <section class="calendar">
            <h2>Calendário</h2>
            <div id="calendar"></div>
        </section>

        <?php include '../templates/footer.php'; ?>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
