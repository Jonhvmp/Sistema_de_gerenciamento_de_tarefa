/*
 * Author: Jonh Alex Paz de Lima
 * All rights reserved
 */

// Função para inicializar o gráfico de tarefas
function initializeTaskChart(taskData) {
    const ctx = document.getElementById('taskChart').getContext('2d');
    const taskChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendente', 'Concluída', 'Atrasada'], // Rótulos baseados nos status das tarefas
            datasets: [{
                label: 'Número de Tarefas',
                data: [
                    taskData.pending,   // Dados dinâmicos para tarefas pendentes
                    taskData.completed, // Dados dinâmicos para tarefas concluídas
                    taskData.overdue    // Dados dinâmicos para tarefas atrasadas
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
                    beginAtZero: true
                }
            }
        }
    });
}

// Função para inicializar o calendário
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/Sistema_de_gerenciamento_de_tarefa/controllers/task.php?action=get_events', // URL para buscar eventos
        editable: true,
        selectable: true,
        select: function(info) {
            const title = prompt('Enter event title:');
            if (title) {
                fetch('/Sistema_de_gerenciamento_de_tarefa/controllers/task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'add_event',
                        title: title,
                        start: info.startStr,
                        end: info.endStr
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        calendar.addEvent({
                            title: title,
                            start: info.startStr,
                            end: info.endStr
                        });
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                });
            }
            calendar.unselect();
        },
        eventClick: function(info) {
            if (confirm('Do you want to delete this event?')) {
                fetch('/Sistema_de_gerenciamento_de_tarefa/controllers/task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'delete_event',
                        event_id: info.event.id
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        info.event.remove();
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
    });

    calendar.render();
}

// Função para adicionar uma nova tarefa
function handleAddTask() {
    const form = document.getElementById('task-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'add');

        fetch('/Sistema_de_gerenciamento_de_tarefa/controllers/task.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Recarrega a página para atualizar a lista de tarefas
            } else {
                alert(data.message);
            }
        });
    });
}

// Função para inicializar todos os componentes do dashboard
function initializeDashboard() {
    if (document.getElementById('taskChart')) {
        // Buscar dados de tarefas do servidor
        fetch('/Sistema_de_gerenciamento_de_tarefa/controllers/task.php?action=get_task_data')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    initializeTaskChart(data.taskData); // Passa dados reais para o gráfico
                } else {
                    console.error('Erro ao obter dados das tarefas:', data.message);
                }
            });
    }
    
    if (document.getElementById('calendar')) {
        initializeCalendar();
    }
    
    if (document.getElementById('task-form')) {
        handleAddTask();
    }
}

// Inicializa o dashboard ao carregar a página
document.addEventListener('DOMContentLoaded', initializeDashboard);
