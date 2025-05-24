<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = intval($_GET['id']);

// Buscar dados do usuário
$stmt = $conn->prepare("SELECT nome, usuario, is_admin FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}

// Atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // Impede rebaixar a si mesmo
    $is_admin = ($id == $_SESSION['user_id']) ? 1 : (isset($_POST['is_admin']) ? 1 : 0);

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, senha = ?, is_admin = ? WHERE id = ?");
        $stmt->bind_param("sssii", $nome, $usuario, $senha_hash, $is_admin, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, is_admin = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nome, $usuario, $is_admin, $id);
    }

    if ($stmt->execute()) {
        $msg = "Usuário atualizado com sucesso!";
        $user['nome'] = $nome;
        $user['usuario'] = $usuario;
        $user['is_admin'] = $is_admin;
    } else {
        $msg = "Erro ao atualizar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuário</title>
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
        form {
            max-width: 500px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, label {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        input[type="checkbox"] {
            width: auto;
            display: inline-block;
            margin-right: 6px;
        }
        button {
            padding: 10px 20px;
            background-color: #a1866f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #8b6f5a;
        }
        .msg {
            margin-top: 15px;
            font-weight: bold;
        }
        .voltar {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4e4039;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-user-edit"></i> Editar Usuário</h1>
        <p>Modifique os dados abaixo e salve as alterações</p>
    </div>

    <form method="POST">
        <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" placeholder="Nome completo" required>
        <input type="text" name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" placeholder="Nome de usuário" required>
        <input type="password" name="senha" placeholder="Nova senha (deixe em branco para não alterar)">

        <?php if ($id != $_SESSION['user_id']): ?>
            <label>
                <input type="checkbox" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>> Administrador
            </label>
        <?php else: ?>
            <label style="opacity: 0.6;">
                <input type="checkbox" checked disabled> Administrador (você)
            </label>
            <input type="hidden" name="is_admin" value="1">
        <?php endif; ?>

        <button type="submit"><i class="fas fa-save"></i> Salvar Alterações</button>
    </form>

    <?php if (isset($msg)): ?>
        <p class="msg" style="color: <?= strpos($msg, 'sucesso') !== false ? 'green' : 'red' ?>;">
            <?= $msg ?>
        </p>
    <?php endif; ?>

    <a href="admin.php" class="voltar"><i class="fas fa-arrow-left"></i> Voltar ao painel</a>
</body>
</html>
