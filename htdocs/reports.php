<?php
session_start();
include 'db.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$count = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE user_id = $user_id")->fetch_assoc();
$clientes = $conn->query("SELECT nome, telefone, email, endereco FROM clientes WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Fácil - Relatório</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 40px 20px;
            text-align: center;
            color: #4e4039;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-bottom: 3px solid #b89e88;
        }
        .banner h1 {
            margin: 0;
            font-size: 2.2em;
        }
        .banner p {
            font-size: 1.1em;
            margin-top: 5px;
        }
        .relatorio {
            text-align: center;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .relatorio h2 {
            margin-bottom: 10px;
        }
        .relatorio p {
            font-size: 1.5em;
            font-weight: bold;
        }
        .tabela-clientes {
            width: 90%;
            margin: 20px auto 40px auto;
            border-collapse: collapse;
            animation: fadeIn 1.2s ease-out;
        }
        .tabela-clientes th, .tabela-clientes td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .tabela-clientes th {
            background-color: #e0d6c9;
        }
        .btn-print, .btn-voltar {
            display: inline-block;
            margin: 20px 10px;
            padding: 10px 20px;
            background-color: #a1866f;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-print:hover, .btn-voltar:hover {
            background-color: #8b6f5a;
        }
        @media print {
            .btn-print, .btn-voltar {
                display: none;
            }
            body {
                background: white;
                color: black;
            }
            .relatorio {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-chart-bar"></i> Relatório de Clientes</h1>
        <p>Resumo completo dos dados cadastrados por você</p>
    </div>

    <div class="relatorio">
        <h2><i class="fas fa-users"></i> Total de Clientes</h2>
        <p><?= $count['total'] ?></p>
    </div>

    <table class="tabela-clientes">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Endereço</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($c = $clientes->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['telefone']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= htmlspecialchars($c['endereco']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="text-align:center;">
        <a href="#" onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Imprimir</a>
        <a href="index.php" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
</body>
</html>
