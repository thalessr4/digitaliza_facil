<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Buscar nome do usuário
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nome);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Fácil - Início</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 60px 20px;
            color: #4e4039;
            text-align: center;
            border-bottom: 4px solid #b89e88;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .banner h1 {
            font-size: 2.5em;
            margin: 0;
        }
        .banner .username {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 10px;
            color: white;
            text-transform: uppercase;
        }
        nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 40px 0;
            gap: 15px;
        }
        nav a {
            padding: 12px 24px;
            background-color: #a1866f;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s;
            width: 240px;
            text-align: center;
        }
        nav a:hover {
            background-color: #8b6f5a;
        }
        @media (min-width: 600px) {
            nav {
                flex-direction: row;
                justify-content: center;
                flex-wrap: wrap;
            }
            nav a {
                margin: 8px;
                width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-home"></i> Bem-vindo ao Digitaliza Fácil</h1>
        <div class="username"><?= htmlspecialchars($nome) ?></div>
        <p>Escolha uma das opções abaixo para continuar</p>
    </div>

    <nav>
        <a href="register.php"><i class="fas fa-user-plus"></i> Cadastrar Cliente</a>
        <a href="clients.php"><i class="fas fa-users"></i> Consultar Clientes</a>
        <a href="reports.php"><i class="fas fa-chart-bar"></i> Relatórios</a>
        <a href="edit_user.php"><i class="fas fa-user-cog"></i> Editar Conta</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </nav>
</body>
</html>
