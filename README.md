# Sistema de Gerenciamento de Tarefas

## Descrição
O Sistema de Gerenciamento de Tarefas é uma aplicação web desenvolvida para auxiliar usuários a organizar e gerenciar suas tarefas diárias de forma eficiente. A aplicação permite criar, editar, excluir e visualizar tarefas, além de oferecer funcionalidades adicionais como gerenciamento de perfil e alteração de senha.

## Funcionalidades
- **Autenticação de Usuário**: Registro, login e logout.
- **Gerenciamento de Perfil**: Atualização de informações pessoais e foto de perfil.
- **Gerenciamento de Tarefas**: Adicionar, editar, excluir e visualizar tarefas.
- **Alteração de Senha**: Funcionalidade para alterar a senha do usuário.
- **Validação de Domínio de Email**: Verificação do domínio do email durante o registro.
- **Mensagens de Sucesso e Erro**: Feedback ao usuário sobre as ações realizadas.

## Tecnologias Utilizadas
- **Front-end**: HTML, CSS (Bootstrap, Tailwind CSS, SCSS), JavaScript
- **Back-end**: PHP
- **Banco de Dados**: MySQL
- **Servidor Web**: Apache
- **Gerenciamento de Dependências**: Composer

## Requisitos
- **PHP** >= 7.4
- **MySQL** >= 5.7
- **Apache** >= 2.4
- **Composer** >= 1.10

## Instalação
1. Clone o repositório para o seu ambiente local:
    ```bash
    git clone https://github.com/seu-usuario/sistema-de-gerenciamento-de-tarefas.git
    ```

2. Navegue até o diretório do projeto:
    ```bash
    cd sistema-de-gerenciamento-de-tarefas
    ```

3. Instale as dependências via Composer:
    ```bash
    composer install
    ```

4. Configure o arquivo `.env` com suas credenciais de banco de dados:
    ```bash
    cp .env.example .env
    ```

5. Configure o banco de dados:
    - Crie um banco de dados no MySQL.
    - Importe o arquivo `database.sql` para o seu banco de dados.
    ```bash
    mysql -u seu_usuario -p seu_banco_de_dados < database.sql
    ```

6. Configure o servidor web Apache:
    - Certifique-se de que o módulo `mod_rewrite` está habilitado.
    - Adicione a configuração do VirtualHost ao seu arquivo de configuração do Apache:
    ```apache
    <VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /caminho/para/sistema-de-gerenciamento-de-tarefas/public
        <Directory /caminho/para/sistema-de-gerenciamento-de-tarefas/public>
            AllowOverride All
        </Directory>
    </VirtualHost>
    ```

7. Reinicie o servidor Apache:
    ```bash
    sudo service apache2 restart
    ```

## Estrutura do Projeto
sistema-de-gerenciamento-de-tarefas/
│<br>
├── config/<br>
│ ├── database.php<br>
│<br>
├── controllers/<br>
│ ├── auth.php<br>
│ ├── add_task.php<br>
│<br>
├── models/<br>
│ ├── User.php<br>
│<br>
├── views/<br>
│ ├── login.php<br>
│ ├── dashboard.php<br>
│ ├── profile.php<br>
│<br>
├── assets/<br>
│ ├── css/<br>
│ │ ├── profile.css<br>
│<br>
├── templates/<br>
│ ├── head.php<br>
│ ├── header.php<br>
│ ├── footer.php<br>
│<br>
├── .htaccess<br>
├── composer.json<br>
├── database.sql<br>
├── index.php<br>
├── README.md<br>

## Uso
1. Acesse a aplicação através do navegador:
    ```
    http://localhost/sistema-de-gerenciamento-de-tarefas
    ```

2. Registre-se e faça login para acessar o dashboard.

3. Gerencie suas tarefas e perfil conforme necessário.

## Contribuição
Se você deseja contribuir para o projeto, sinta-se à vontade para abrir um pull request ou enviar sugestões via issues.

## Licença
Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## Contato
Para mais informações, entre em contato:
- **Nome**: Jonh Alex
- **Email**: it.jonhpaz@gmai.com | Jonhpaz08@gmail.com
- **LinkedIn**: [Jonh Akex](https://www.linkedin.com/in/jonhvmp)
