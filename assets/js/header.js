// assets/js/header.js

/*
 * Author: Jonh Alex Paz de Lima
 * All rights reserved
 */


document.addEventListener('DOMContentLoaded', () => {
    // Animação do header ao rolar a página
    const header = document.querySelector('header');
    const title = header.querySelector('h1');
    
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        const opacity = Math.max(1 - scrollY / 300, 0.5);
        const translateY = Math.min(scrollY / 2, 50);
        
        header.style.backgroundColor = `rgba(31, 31, 31, ${opacity})`;
        header.style.transform = `translateY(${translateY}px)`;
        title.style.transform = `translateY(${scrollY / 4}px)`;
    });

    // Menu de navegação dinâmico com animações
    const navLinks = document.querySelectorAll('nav ul li a');

    navLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            link.style.color = '#4CAF50';
            link.style.transition = 'color 0.3s ease';
        });

        link.addEventListener('mouseleave', () => {
            link.style.color = '#fff';
        });

        link.addEventListener('click', (e) => {
            e.preventDefault(); // Previne o comportamento padrão do link
            const href = link.getAttribute('href');
            // Verifica se o href é um seletor válido antes de tentar rolar
            if (href && href.startsWith('#')) {
                const target = document.querySelector(href);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop,
                        behavior: 'smooth'
                    });
                }
            } else {
                // Caso contrário, navegue para a URL
                window.location.href = href;
            }
        });
    });

    // Efeito de destaque no título do header
    const titleAnimation = () => {
        title.style.background = 'linear-gradient(45deg, #4CAF50, #2c6e49)';
        title.style.webkitBackgroundClip = 'text';
        title.style.backgroundClip = 'text';
        title.style.webkitTextFillColor = 'transparent';
        title.style.transition = 'all 0.3s ease';
        
        setInterval(() => {
            title.style.transform = `rotate(${Math.random() * 5 - 2.5}deg)`;
        }, 1000);
    };

    titleAnimation();

    // Efeito de paralaxe para a imagem de perfil
    const profilePicture = document.querySelector('.profile-picture img');

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        profilePicture.style.transform = `translateY(${scrollY * 0.5}px)`;
        profilePicture.style.transition = 'transform 0.3s ease';
    });
});
