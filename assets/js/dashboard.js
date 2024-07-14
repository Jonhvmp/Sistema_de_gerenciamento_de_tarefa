// Função para inicializar o gráfico de tarefas
function initializeTaskChart(taskData) {
    const ctx = document.getElementById('taskChart').getContext('2d');

    // Se um gráfico já existir no canvas, destrua-o
    if (window.taskChart && typeof window.taskChart.destroy === 'function') {
        window.taskChart.destroy(); // Use a instância do gráfico para chamar destroy
    }

    // Verifique se há dados para o gráfico
    if (taskData.pending === 0 && taskData.completed === 0 && taskData.overdue === 0) {
        document.getElementById('taskChartContainer').innerHTML = '<p class="no-data-message">Não há tarefas processadas.</p>';
        return;
    }

    // Crie um novo gráfico
    window.taskChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendente', 'Concluída', 'Atrasada'],
            datasets: [{
                label: 'Número de Tarefas',
                data: [
                    taskData.pending || 0,
                    taskData.completed || 0,
                    taskData.overdue || 0
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
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

    console.log('Gráfico criado:', window.taskChart); // Verifique se a instância é criada corretamente
}

// Função para inicializar o calendário
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/Sistema_de_gerenciamento_de_tarefa/controllers/task.php?action=get_events',
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
                })
                .catch(error => {
                    console.error('Erro ao adicionar evento:', error);
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
                })
                .catch(error => {
                    console.error('Erro ao excluir evento:', error);
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
        })
        .catch(error => {
            console.error('Erro ao adicionar tarefa:', error);
        });
    });
}

// Função para inicializar todos os componentes do dashboard
function initializeDashboard() {
    if (document.getElementById('taskChart')) {
        fetch('/Sistema_de_gerenciamento_de_tarefa/views/dashboard.php?action=get_task_data')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dados da API:', data); // Verifique a estrutura dos dados
                if (data.success) {
                    initializeTaskChart(data.taskData); // Passa dados reais para o gráfico
                } else {
                    console.error('Erro ao obter dados das tarefas:', data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao carregar dados do gráfico:', error);
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
