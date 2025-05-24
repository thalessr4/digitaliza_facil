<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Cadastrar novo usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_usuario'])) {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $check = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $check->bind_param("s", $usuario);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $novo_msg = "Nome de usuário já existente.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, usuario, senha, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nome, $usuario, $senha_hash, $is_admin);

        if ($stmt->execute()) {
            $novo_msg = "Usuário criado com sucesso!";
        } else {
            $novo_msg = "Erro ao criar usuário.";
        }
    }
}

// Buscar todos os usuários
$result = $conn->query("SELECT id, nome, usuario, is_admin FROM usuarios");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ef;
            text-align: center;
        }
        .banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 40px 20px;
            color: #4e4039;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-bottom: 4px solid #b89e88;
        }
        table {
            margin: 30px auto;
            border-collapse: collapse;
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #a1866f;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2eae5;
        }
        a.btn {
            padding: 6px 12px;
            background-color: #a1866f;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            margin: 2px;
            display: inline-block;
        }
        a.btn:hover {
            background-color: #8b6f5a;
        }
        .btn-voltar {
            margin-top: 30px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #bbb3a5;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-voltar:hover {
            background-color: #99907f;
        }
        .mensagem {
            font-weight: bold;
            margin-top: 15px;
        }
        form.novo-usuario {
            max-width: 400px;
            margin: 50px auto 0 auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form.novo-usuario input[type="text"],
        form.novo-usuario input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        form.novo-usuario label {
            display: block;
            margin-top: 10px;
        }
        form.novo-usuario button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #a1866f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        form.novo-usuario button:hover {
            background-color: #8b6f5a;
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-user-shield"></i> Painel do Administrador</h1>
        <p>Gerencie os usuários cadastrados no sistema</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <p class="mensagem" style="color: green;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </p>
    <?php endif; ?>

    <?php if (isset($novo_msg)): ?>
        <p class="mensagem" style="color: <?= strpos($novo_msg, 'sucesso') !== false ? 'green' : 'red' ?>;">
            <?= $novo_msg ?>
        </p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Usuário</th>
            <th>Admin?</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['usuario']) ?></td>
            <td><?= $row['is_admin'] ? 'Sim' : 'Não' ?></td>
            <td>
                <a href="edit_user_admin.php?id=<?= $row['id'] ?>" class="btn">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                    <a href="delete_user_admin.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Tem certeza que deseja excluir este usuário? Esta ação é irreversível.')">
                        <i class="fas fa-trash-alt"></i> Excluir
                    </a>
                <?php else: ?>
                    <span style="font-size: 13px; color: #888;">(você)</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <form method="POST" class="novo-usuario">
        <h2><i class="fas fa-user-plus"></i> Cadastrar novo usuário</h2>
        <input type="hidden" name="novo_usuario" value="1">
        <input type="text" name="nome" placeholder="Nome completo" required><br>
        <input type="text" name="usuario" placeholder="Nome de usuário" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <label><input type="checkbox" name="is_admin"> Tornar administrador</label><br>
        <button type="submit">Criar usuário</button>
    </form>

    <a href="logout.php" class="btn-voltar"><i class="fas fa-sign-out-alt"></i> Sair</a>
</body>
</html>
