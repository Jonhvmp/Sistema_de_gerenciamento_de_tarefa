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
    <style>
        /* assets/css/dashboard.css */

        /* Estilos Gerais */
        body {
        font-family: "Arial", sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        }

        main {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Sombra mais pronunciada */
        border-radius: 12px;
        position: relative;
        overflow: hidden; /* Para efeitos de paralaxe */
        }

        /* Efeito de Paralaxe no Background */
        main::before {
        content: '';
        position: absolute;
        top: -50%;
        left: 0;
        width: 200%;
        height: 200%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.1), rgba(255, 255, 255, 0.1));
        z-index: 0;
        transform: rotate(-10deg);
        opacity: 0.1;
        transition: opacity 0.3s ease-in-out;
        }

        main:hover::before {
        opacity: 0.2; /* Aumenta a visibilidade no hover */
        }

        main > * {
        position: relative;
        z-index: 1; /* Garante que o conteúdo esteja acima do efeito de paralaxe */
        }

        /* Seção de Resumo das Tarefas */
        .dashboard-summary {
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ffffff, #f9f9f9); /* Gradiente sutil de fundo */
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); /* Sombra mais pronunciada */
        border: 1px solid #ddd; /* Borda sutil */
        }

        .dashboard-summary h2 {
        color: #4CAF50;
        margin-bottom: 15px;
        font-size: 24px;
        position: relative;
        font-weight: 600;
        }

        .dashboard-summary h2::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 50px;
        height: 3px;
        background: #4CAF50;
        border-radius: 2px;
        transition: width 0.3s ease;
        }

        .dashboard-summary h2:hover::after {
        width: 100%; /* Aumenta a linha ao passar o mouse */
        }

        .dashboard-summary #task-stats {
        text-align: center;
        position: relative;
        }

        .dashboard-summary #task-stats canvas#taskChart {
        width: 100%;
        max-width: 600px;
        height: auto;
        }

        /* Seção de Gerenciamento de Tarefas */
        .task-manager {
        margin-bottom: 20px;
        }

        .task-manager h2 {
        color: #4CAF50;
        margin-bottom: 15px;
        font-size: 24px;
        font-weight: 600;
        position: relative;
        }

        .task-manager h2::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 50px;
        height: 3px;
        background: #4CAF50;
        border-radius: 2px;
        transition: width 0.3s ease;
        }

        .task-manager h2:hover::after {
        width: 100%;
        }

        .task-manager button#add-task-btn {
        background-color: #2196F3;
        color: #fff;
        border: none;
        padding: 12px 24px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        position: relative;
        }

        .task-manager button#add-task-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: rgba(255, 255, 255, 0.3);
        transition: width 0.4s ease;
        border-radius: 8px;
        z-index: 0;
        }

        .task-manager button#add-task-btn:hover::before {
        width: 100%;
        }

        .task-manager button#add-task-btn:hover {
        background-color: #0c7cd5;
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        .task-manager #task-form-container {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 12px;
        background: #fafafa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        transition: transform 0.3s ease;
        }

        .task-manager #task-form-container:hover {
        transform: translateY(-5px); /* Efeito de levitação ao passar o mouse */
        }

        .task-manager #task-form-container form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        }

        .task-manager #task-form-container input[type="text"] {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .task-manager #task-form-container input[type="text"]:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 8px rgba(76, 175, 80, 0.2); /* Sombra ao focar */
        }

        .task-manager #task-form-container input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .task-manager #task-form-container input[type="submit"]:hover {
        background-color: #3d8b40;
        transform: translateY(-2px); /* Efeito de movimento para cima ao passar o mouse */
        }

        /* Seção do Calendário */
        .calendar {
        margin-top: 20px;
        position: relative;
        }

        .calendar h2 {
        color: #4CAF50;
        margin-bottom: 15px;
        font-size: 24px;
        font-weight: 600;
        position: relative;
        }

        .calendar h2::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 50px;
        height: 3px;
        background: #4CAF50;
        border-radius: 2px;
        transition: width 0.3s ease;
        }

        .calendar h2:hover::after {
        width: 100%;
        }

        .calendar #calendar {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        position: relative;
        transition: transform 0.3s ease;
        }

        .calendar #calendar:hover {
        transform: scale(1.02); /* Efeito de leve aumento ao passar o mouse */
        }

        .calendar #calendar .fc {
        border-radius: 12px;
        }

        .calendar #calendar .fc-toolbar {
        background-color: #4CAF50;
        color: #fff;
        padding: 12px;
        border-bottom: 1px solid #ddd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .calendar #calendar .fc-daygrid-day {
        border: 1px solid #ddd;
        transition: background-color 0.3s ease;
        }

        .calendar #calendar .fc-daygrid-day:hover {
        background-color: #f0f0f0; /* Cor de fundo ao passar o mouse */
        }

        .calendar #calendar .fc-button-primary {
        background-color: #2196F3;
        border-color: #2196F3;
        transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .calendar #calendar .fc-button-primary:hover {
        background-color: #0c7cd5;
        border-color: #0c7cd5;
        transform: scale(1.05);
        }

        /* Estilos para o Modal */
        #add-task-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
        }

        #add-task-modal.show {
        display: flex;
        opacity: 1;
        }

        #add-task-modal .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        position: relative;
        transform: translateY(-50px);
        transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #add-task-modal.show .modal-content {
        transform: translateY(0);
        opacity: 1;
        }

        #add-task-modal .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 24px;
        color: #333;
        }

        #add-task-modal h2 {
        margin-top: 0;
        }

        #add-task-modal form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        }

        #add-task-modal input, #add-task-modal textarea {
        margin-top: 5px;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        #add-task-modal input:focus, #add-task-modal textarea:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 8px rgba(76, 175, 80, 0.2);
        }

        #add-task-modal button {
        margin-top: 10px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #add-task-modal button:hover {
        background-color: #3d8b40;
        transform: scale(1.05);
        }

        /* Media Queries para Responsividade */
        @media (max-width: 768px) {
        main {
            padding: 10px;
        }

        .task-manager button#add-task-btn {
            font-size: 14px;
            padding: 8px 16px;
        }

        .modal-content {
            width: 90%;
            padding: 15px;
        }
        }

    </style>
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
