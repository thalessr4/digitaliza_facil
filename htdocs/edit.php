<?php
session_start();
include 'db.php';
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: clients.php");
    exit;
}

// Buscar cliente
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

if (!$cliente) {
    echo "Cliente não encontrado.";
    exit;
}

// Atualizar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    $update = $conn->prepare("UPDATE clientes SET nome = ?, telefone = ?, email = ?, endereco = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("ssssii", $nome, $telefone, $email, $endereco, $id, $user_id);
    $update->execute();

    header("Location: clients.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Fácil - Editar Cliente</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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
    </style>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-edit"></i> Editar Cliente</h1>
        <p>Atualize as informações do cliente</p>
    </div>
    <form method="POST">
        <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required><br>
        <input type="text" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>" required><br>
        <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required><br>
        <input type="text" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>"><br>
        <div class="botoes">
            <button type="submit" class="btn"><i class="fas fa-save"></i> Salvar</button>
            <a href="clients.php" class="btn btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </form>
</body>
</html>
