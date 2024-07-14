<script src="../assets/js/header.js"></script>
<footer>
<style>
    /* assets/css/footer.css */

    /* Reseta estilos padrões e define a fonte global */
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    }

    /* Estilos gerais do footer */
    footer {
    background: #333;
    color: #fff;
    padding: 40px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
    }

    /* Efeito de fundo do footer com animação */
    footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at top left, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
    z-index: 0;
    pointer-events: none;
    transition: background 0.3s ease;
    }

    /* Efeito de animação de partículas no fundo */
    footer::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.05), rgba(0, 0, 0, 0) 60%);
    opacity: 0.4;
    z-index: 0;
    pointer-events: none;
    animation: particles 10s infinite linear;
    }

    /* Animação de partículas */
    @keyframes particles {
    0% { transform: translate(-50%, -50%) scale(1); }
    100% { transform: translate(50%, 50%) scale(1.5); }
    }

    /* Conteúdo do footer */
    .footer-content {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    position: relative;
    z-index: 1;
    }

    /* Título do footer */
    .footer-content h3 {
    margin: 0;
    font-size: 2.5em;
    color: #00bfff;
    position: relative;
    transition: color 0.3s ease, text-shadow 0.3s ease;
    }

    /* Efeito de hover no título do footer */
    .footer-content h3:hover {
    color: #1e90ff;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    /* Parágrafos do footer */
    .footer-content p {
    margin: 10px 0;
    font-size: 16px;
    color: #ccc;
    line-height: 1.6;
    position: relative;
    transition: color 0.3s ease, text-shadow 0.3s ease;
    }

    /* Efeito de hover nos parágrafos do footer */
    .footer-content p:hover {
    color: #fff;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
    }

    /* Estilos das redes sociais */
    .footer-content .socials {
    list-style: none;
    padding: 0;
    margin: 20px 0;
    display: flex;
    justify-content: center;
    }

    /* Ícones das redes sociais */
    .footer-content .socials li {
    margin: 0 15px;
    }

    /* Links das redes sociais */
    .footer-content .socials a {
    color: #fff;
    text-decoration: none;
    font-size: 24px;
    transition: color 0.3s ease, transform 0.3s ease, filter 0.3s ease;
    }

    /* Efeito de hover nas redes sociais */
    .footer-content .socials a:hover {
    color: #00bfff;
    transform: scale(1.2);
    filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
    }

    /* Estilos do rodapé inferior */
    .footer-bottom {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #444;
    position: relative;
    z-index: 1;
    }

    /* Parágrafo do rodapé inferior */
    .footer-bottom p {
    margin: 0;
    font-size: 14px;
    color: #aaa;
    position: relative;
    transition: color 0.3s ease;
    }

    /* Efeito de hover no texto do rodapé inferior */
    .footer-bottom p:hover {
    color: #fff;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
    }

    /* Destaque para texto especial no rodapé inferior */
    .footer-bottom span {
    color: #00bfff;
    font-weight: bold;
    }

</style>
        <div class="footer-content">
            <h3>Gerenciador de tarefas</h3>
            <p>Gerencie suas tarefas de forma eficiente e produtiva.</p>
            <ul class="socials">
                <li><a href="https://www.github.com/jonhvmp" target="_blank"><i class="bi bi-github" style="padding-right: 5px;"></i>GitHub</a></li>
                <li><a href="https://www.instagram.com/jonhvmp" target="_blank"><i class="bi bi-instagram" style="padding-right: 5px;"></i>Instagram</a></li>
                <li><a href="https://www.linkedin.com/in/jonhvmp" target="_blank"><i class="bi bi-linkedin" style="padding-right: 5px;"></i>Linkedin</a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>&copy; Gerenciador de tarefas 2024. Desenvolvido por <span>Jonh Alex</span></p>
        </div>
    </footer>