/*
 * Author: Jonh Alex Paz de Lima
 * All rights reserved
 */

document.addEventListener('DOMContentLoaded', function() {
    // Função para exibir formulário com efeito de deslizar
    function slideDown(element) {
        element.style.display = 'block';
        element.style.maxHeight = element.scrollHeight + 'px';
    }

    // Função para esconder formulário com efeito de deslizar
    function slideUp(element) {
        element.style.maxHeight = '0';
        setTimeout(() => {
            element.style.display = 'none';
        }, 300); // Tempo deve corresponder à duração da animação
    }

    // Exibir formulário de perfil ao clicar em "Editar"
    document.getElementById('edit-button').addEventListener('click', function() {
        document.getElementById('name').removeAttribute('readonly');
        document.getElementById('email').removeAttribute('readonly');
        this.style.display = 'none';
        document.getElementById('save-button').style.display = 'inline-block';
    });

    // Mostrar formulário de alteração de senha com efeito de deslizar
    document.getElementById('change-password-button').addEventListener('click', function() {
        const form = document.getElementById('change-password-form');
        slideDown(form);
        this.style.display = 'none';
    });

    // Mostrar formulário de upload de foto com efeito de deslizar
    document.getElementById('upload-picture-button').addEventListener('click', function() {
        const form = document.getElementById('upload-picture-form');
        slideDown(form);
        this.style.display = 'none';
    });

    // Adicionar animação de fade-in ao alert success
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        alert.classList.add('fade-in');
    });

    // Adiciona o efeito de zoom na imagem do perfil
    const profilePic = document.querySelector('.profile-picture img');
    if (profilePic) {
        profilePic.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.2) rotate(0deg)';
            this.style.transition = 'transform 0.5s ease';
        });

        profilePic.addEventListener('mouseout', function() {
            this.style.transform = 'scale(1) rotate(-2deg)';
            this.style.transition = 'transform 0.5s ease';
        });
    }

    // Mostrar toast de sucesso ao exibir mensagem de alerta
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 500); // Tempo para garantir que a animação de desvanecimento tenha terminado
        }, 3000); // Tempo que o toast permanece visível
    }

    alerts.forEach(alert => {
        alert.addEventListener('animationend', function() {
            showToast(this.textContent);
        });
    });

    // Adiciona estilos para animações e toast
    const style = document.createElement('style');
    style.innerHTML = `
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .slide-down {
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .slide-up {
            overflow: hidden;
            transition: max-height 0.3s ease-in;
        }
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            opacity: 1;
            transition: opacity 0.5s ease;
            z-index: 1000;
        }
    `;
    document.head.appendChild(style);
});
