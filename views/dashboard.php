<?php
    // Inicie a sessão
    session_start();

    // Verifique se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    // Inclua o arquivo de conexão com o banco de dados
    include_once '../config/database.php';
    $database = new Database();
    $conn = $database->getConnection();

    // Verifique se a ação é obter dados para o gráfico
    if (isset($_GET['action']) && $_GET['action'] === 'get_task_data') {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT status, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY status";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['success' => false, 'message' => 'Erro na preparação da consulta.']);
            exit();
        }

        $stmt->bind_param("i", $user_id);
        
        if (!$stmt->execute()) {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['success' => false, 'message' => 'Erro na execução da consulta.']);
            exit();
        }
        
        $result = $stmt->get_result();

        // Inicialize a estrutura de dados
        $taskData = [
            'pending' => 1,
            'completed' => 0,
            'overdue' => 0
        ];

        while ($row = $result->fetch_assoc()) {
            // Certifique-se de que a chave existe
            if (array_key_exists($row['status'], $taskData)) {
                $taskData[$row['status']] = $row['count'];
            }
        }

        // Retorne os dados como JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'taskData' => $taskData]);
        exit();
    }

    // Consulta para obter as tarefas do usuário
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
    <?php include '../templates/head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <!-- Seção de Resumo das Tarefas -->
    <section class="dashboard-summary">
        <h2>Resumo das Tarefas</h2>
        <div id="taskChartContainer">
            <canvas id="taskChart"></canvas>
        </div>
    </section>

    <!-- Seção de Gerenciamento de Tarefas -->
    <section class="task-manager">
        <h2>Gerenciamento de Tarefas</h2>
        <button id="add-task-btn">Adicionar Nova Tarefa</button>
        <div id="task-form-container" style="display:none;">
            <form id="task-form" method="post" action="../controllers/add_task.php">
                <label for="task-title">Título da Tarefa:</label>
                <input type="text" id="task-title" name="title" required>
                <label for="task-description">Descrição:</label>
                <textarea id="task-description" name="description" required></textarea>
                <input type="submit" value="Adicionar Tarefa">
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

    <!-- Seção do Calendário -->
    <section class="calendar">
        <h2>Calendário</h2>
        <div id="calendar"></div>
    </section>

    <?php include '../templates/footer.php'; ?>

    <!-- Scripts necessários -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>

    <script>
        // Função para obter os dados das tarefas
        function fetchTaskData() {
            fetch('dashboard.php?action=get_task_data')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var taskData = data.taskData;
                        console.log(taskData);

                        // Preparar dados para o gráfico
                        var ctx = document.getElementById('taskChart').getContext('2d');
                        var taskChart = new Chart(ctx, {
                            type: 'bar', // Tipo de gráfico
                            data: {
                                labels: ['Pendente', 'Concluída', 'Atrasada'], // Rótulos para o gráfico
                                datasets: [{
                                    label: 'Número de Tarefas',
                                    data: [
                                        taskData.pending,
                                        taskData.completed,
                                        taskData.overdue
                                    ],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)', // Cor de fundo para tarefas pendentes
                                        'rgba(54, 162, 235, 0.2)', // Cor de fundo para tarefas concluídas
                                        'rgba(255, 206, 86, 0.2)'  // Cor de fundo para tarefas atrasadas
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)', // Cor da borda para tarefas pendentes
                                        'rgba(54, 162, 235, 1)', // Cor da borda para tarefas concluídas
                                        'rgba(255, 206, 86, 1)'  // Cor da borda para tarefas atrasadas
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true // Iniciar o eixo Y do gráfico em zero
                                    }
                                }
                            }
                        });
                    } else {
                        console.error('Erro ao obter dados das tarefas:', data.message);
                    }
                })
                .catch(error => console.error('Erro:', error));
        }

        // Chame a função para obter dados e atualizar o gráfico
        fetchTaskData();
    </script>
</body>
</html>