<?php
session_start();
include 'db.php';
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO clientes (nome, telefone, email, endereco, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $telefone, $email, $endereco, $user_id);

    if ($stmt->execute()) {
        $msg = "Cliente cadastrado com sucesso!";
        $sucesso = true;
    } else {
        $msg = "Erro ao cadastrar: " . $conn->error;
        $sucesso = false;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Fácil - Cadastrar Cliente</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body {
            font-family: Arial, sans-serif;
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
        form {
            max-width: 500px;
            margin: 30px auto;
            animation: fadeIn 1.2s ease-out;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .botoes {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 25px;
            background-color: #a1866f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #8b6f5a;
        }
        .btn i {
            margin-right: 6px;
        }
        .btn-cancelar {
            background-color: #bbb3a5;
        }
        .btn-cancelar:hover {
            background-color: #99907f;
        }
        .mensagem {
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
            animation: fadeIn 2s ease-out;
        }
        .voltar-menu {
            margin-top: 20px;
            text-align: center;
            animation: fadeIn 2.2s ease-out;
        }
    </style>
    <script>
        function confirmarCancelamento(event) {
            if (!confirm("Tem certeza que deseja cancelar o cadastro?")) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-user-plus"></i> Cadastro de Cliente</h1>
        <p>Adicione um novo cliente à sua lista</p>
    </div>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required><br>
        <input type="text" name="telefone" placeholder="Telefone" required><br>
        <input type="email" name="email" placeholder="E-mail" required><br>
        <input type="text" name="endereco" placeholder="Endereço"><br>
        <div class="botoes">
            <button type="submit" class="btn"><i class="fas fa-save"></i> Salvar</button>
            <a href="index.php" class="btn btn-cancelar" onclick="confirmarCancelamento(event)">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>

    <?php if (isset($msg)): ?>
        <p class="mensagem" style="color: <?= $sucesso ? 'green' : 'red' ?>;">
            <?= $msg ?>
        </p>
        <?php if ($sucesso): ?>
            <div class="voltar-menu">
                <a href="index.php" class="btn"><i class="fas fa-arrow-left"></i> Voltar ao menu</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
