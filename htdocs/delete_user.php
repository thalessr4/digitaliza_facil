<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Excluir dados do usuário
$stmt = $conn->prepare("DELETE FROM clientes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Encerrar sessão
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Conta Excluída</title>
    <meta http-equiv="refresh" content="5;url=login.php">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ef;
            text-align: center;
            padding: 60px 20px;
        }
        .mensagem {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            color: #4e4039;
        }
        .mensagem h1 {
            font-size: 2em;
            margin-bottom: 15px;
        }
        .mensagem p {
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="mensagem">
        <h1>Conta excluída com sucesso</h1>
        <p>Todos os seus dados foram removidos do sistema.</p>
        <p>Você será redirecionado para a tela de login em instantes...</p>
        <p style="margin-top: 30px;"><a href="login.php">Clique aqui se não for redirecionado automaticamente</a></p>
    </div>
</body>
</html>
