<?php
session_start();
include 'db.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM clientes WHERE user_id = ?");
$result->bind_param("i", $user_id);
$result->execute();
$clientes = $result->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Fácil - Consultar Clientes</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 60px 20px;
            text-align: center;
            color: #4e4039;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            border-bottom: 4px solid #b89e88;
            animation: fadeIn 1s ease-out;
        }
        .banner h1 {
            font-size: 2.5em;
            margin: 0;
        }
        .banner p {
            font-size: 1.2em;
            margin-top: 10px;
        }
        nav {
            text-align: center;
            margin: 20px 0;
            animation: fadeIn 1.2s ease-out;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #a1866f;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #8b6f5a;
        }
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            animation: fadeIn 1.4s ease-out;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #e0d6c9;
        }
        .btn-acao {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            font-size: 0.9em;
            margin: 0 4px;
        }
        .editar {
            background-color: #5cb85c;
        }
        .editar:hover {
            background-color: #4cae4c;
        }
        .excluir {
            background-color: #d9534f;
        }
        .excluir:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-address-book"></i> Meus Clientes</h1>
        <p>Veja os clientes cadastrados por você</p>
    </div>

    <nav>
        <a href="index.php"><i class="fas fa-home"></i> Início</a>
        <a href="register.php"><i class="fas fa-user-plus"></i> Cadastrar</a>
        <a href="reports.php"><i class="fas fa-chart-bar"></i> Relatórios</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </nav>

    <table>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Endereço</th>
            <th>Ações</th>
        </tr>
        <?php while ($cliente = $clientes->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($cliente['nome']) ?></td>
            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
            <td><?= htmlspecialchars($cliente['email']) ?></td>
            <td><?= htmlspecialchars($cliente['endereco']) ?></td>
            <td>
                <a class="btn-acao editar" href="edit.php?id=<?= $cliente['id'] ?>"><i class="fas fa-edit"></i> Editar</a>
                <a class="btn-acao excluir" href="delete.php?id=<?= $cliente['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')"><i class="fas fa-trash-alt"></i> Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
