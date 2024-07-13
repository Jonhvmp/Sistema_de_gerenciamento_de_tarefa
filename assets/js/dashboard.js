// dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    // Initialize chart
    const ctx = document.getElementById('taskChart').getContext('2d');
    const taskChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendentes', 'Concluídas', 'Atrasadas'],
            datasets: [{
                label: 'Tarefas',
                data: [10, 5, 3], // Dados de exemplo; substitua com dados reais
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

    // Initialize calendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
            // Eventos de exemplo; substitua com eventos reais
            { title: 'Tarefa 1', date: '2024-07-15' },
            { title: 'Tarefa 2', date: '2024-07-20' }
        ]
    });
    calendar.render();

    // Handle add task button click
    document.getElementById('add-task-btn').addEventListener('click', function() {
        // Open task creation form or modal
        alert('Adicione aqui o formulário para criar uma nova tarefa.');
    });
});
