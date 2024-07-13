// assets/js/dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    // Seleciona o botão e o modal
    const addTaskBtn = document.getElementById('add-task-btn');
    const modal = document.getElementById('add-task-modal');
    const closeModal = document.querySelector('.close-modal');

    // Inicializa o calendário se a biblioteca FullCalendar estiver carregada
    if (typeof FullCalendar !== 'undefined') {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                // Eventos exemplo
                { title: 'Tarefa 1', start: '2024-07-20' },
                { title: 'Tarefa 2', start: '2024-07-22' }
            ]
        });
        calendar.render();
    } else {
        console.error('FullCalendar não está definido.');
    }

    // Adiciona o evento de clique para abrir o modal
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', function() {
            modal.style.display = 'flex'; // Exibe o modal
        });
    }

    // Adiciona o evento de clique para fechar o modal
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none'; // Oculta o modal
        });

        // Fecha o modal ao clicar fora da área do modal
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none'; // Oculta o modal
            }
        });
    }
});
