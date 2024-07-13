document.addEventListener('DOMContentLoaded', function() {
    // Configuração do gráfico
    const ctx = document.getElementById('taskChart').getContext('2d');
    const taskChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Tarefa 1', 'Tarefa 2', 'Tarefa 3'], // Substitua com os dados reais
            datasets: [{
                label: 'Número de Tarefas',
                data: [10, 20, 30], // Substitua com os dados reais
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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

    // Inicialização do calendário
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/path/to/your/events' // Ajuste o caminho para o arquivo de eventos ou API
    });
    calendar.render();

    // Mostrar/ocultar o formulário de adição de tarefa
    document.getElementById('add-task-btn').addEventListener('click', function() {
        const form = document.getElementById('add-task-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

    // Envio do formulário de adição de tarefa
    document.getElementById('task-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita o envio padrão do formulário

        const form = document.getElementById('task-form');
        const formData = new FormData(form);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Aqui você pode processar a resposta do servidor
            alert('Tarefa adicionada com sucesso!');
            // Atualize a lista de tarefas ou recarregue a página
            location.reload(); // Recarrega a página para ver a nova tarefa
        })
        .catch(error => {
            console.error('Erro ao adicionar tarefa:', error);
            alert('Erro ao adicionar tarefa.');
        });
    });
});
