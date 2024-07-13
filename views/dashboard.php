<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once '../config/database.php'; // Inclua o arquivo apenas uma vez

// Cria uma instância da classe Database
$database = new Database();
$conn = $database->getConnection();

// Consulta para obter tarefas do usuário
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
    <title>Dashboard - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <?php include '../templates/head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/dist/fullcalendar.min.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <main>
        <!-- Seção de Resumo das Tarefas -->
        <section class="dashboard-summary">
            <h2>Resumo das Tarefas</h2>
            <div id="task-stats">
                <canvas id="taskChart"></canvas>
            </div>
        </section>

        <!-- Seção de Gerenciamento de Tarefas -->
        <section class="task-manager">
            <h2>Gerenciamento de Tarefas</h2>
            <!-- Botão para Adicionar Nova Tarefa -->
            <button id="add-task-btn">Adicionar Nova Tarefa</button>

            <!-- Formulário para Adicionar Tarefas -->
            <div id="task-form-container" style="display:none;">
                <form id="task-form">
                    <label for="task-title">Título da Tarefa:</label>
                    <input type="text" id="task-title" name="title" required>
                    <input type="submit" value="Adicionar Tarefa">
                </form>
            </div>

            <!-- Lista de Tarefas -->
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

        <!-- Seção do Calendário -->
        <section class="calendar">
            <h2>Calendário</h2>
            <div id="calendar"></div>
        </section>

        <?php include '../templates/footer.php'; ?>
    </main>

    <!-- Scripts necessários -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
