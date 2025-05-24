
# Digitaliza Fácil – Sistema de Cadastro e Controle de Clientes

Sistema web desenvolvido para auxiliar pequenos negócios no cadastro, gerenciamento e controle de informações dos clientes. Simples, intuitivo e eficiente, o sistema oferece funcionalidades como cadastro, edição, exclusão de clientes e controle de usuários, com níveis de acesso (usuário comum e administrador).

## 🛠️ Tecnologias Utilizadas

- Front-end: HTML5, CSS3, Javascript
- Back-end: PHP (versão 7.4 ou superior)
- Banco de Dados: MySQL
- Servidor local recomendado: XAMPP, WAMP ou similar

## 🚀 Funcionalidades

- ✅ Cadastro de usuários (com senha criptografada)
- ✅ Login seguro com verificação
- ✅ Diferenciação entre usuários comuns e administradores
- ✅ Edição e exclusão de cadastro de clientes
- ✅ Gerenciamento de usuários pelo administrador
- ✅ Interface moderna, responsiva e de fácil utilização

## 📁 Estrutura de Arquivos

```
/digitaliza-facil
│
├── css/
│   └── style.css          # Arquivo de estilos
│
├── db.php                 # Conexão com o banco de dados
├── login.php              # Tela e lógica de login
├── logout.php             # Encerramento de sessão
├── register_user.php      # Registro de novos usuários
├── index.php              # Página inicial após login
├── edit_user.php          # Edição de dados do usuário
├── delete_user.php        # Exclusão de usuários
```

## 🔗 Configuração do Banco de Dados

### 1️⃣ Criação do Banco de Dados

Acesse o **phpMyAdmin** ou outro gerenciador de MySQL e execute o seguinte script SQL:

```sql
-- Criação do banco de dados
CREATE DATABASE digitaliza_facil;

-- Uso do banco
USE digitaliza_facil;

-- Criação da tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Criação da tabela de clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2️⃣ Configuração do Arquivo `db.php`

No arquivo `db.php`, configure as credenciais de acesso ao seu banco de dados:

```php
<?php
$servername = "localhost";
$username = "root";         // Usuário do banco (padrão do XAMPP)
$password = "";             // Senha (geralmente vazia no XAMPP)
$dbname = "digitaliza_facil";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
```

## ▶️ Como Executar o Projeto Localmente

1. Clone o repositório:

```bash
git clone https://github.com/seu-usuario/digitaliza-facil.git
```

2. Copie o projeto para a pasta `htdocs` do XAMPP (ou pasta equivalente no seu servidor local).

3. Inicie o servidor Apache e MySQL no painel do XAMPP.

4. Acesse o **phpMyAdmin**, crie o banco de dados conforme o script acima.

5. Configure corretamente o arquivo `db.php` com suas credenciais.

6. No navegador, acesse:

```
http://localhost/digitaliza-facil/login.php
```

7. Crie seu primeiro usuário administrador manualmente pelo banco (ou adicione no código, se preferir).

## 💡 Observações Importantes

- As senhas são armazenadas de forma segura utilizando `password_hash()` e `password_verify()`.
- A aplicação está preparada para diferenciação de níveis de acesso (usuário comum e administrador).
- Este projeto é acadêmico, podendo ser expandido para produção com melhorias como tratamento de erros, segurança avançada e responsividade total.

## 🤝 Contribuição

Sinta-se à vontade para abrir issues, propor melhorias ou enviar pull requests!

## 📜 Licença

Este projeto é de código aberto, desenvolvido para fins educacionais.
