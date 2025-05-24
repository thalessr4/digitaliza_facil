<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);
    $confirmar = trim($_POST['confirmar_senha']);

    if (!empty($senha) && $senha !== $confirmar) {
        $msg = "As senhas não coincidem. Tente novamente.";
    } else {
        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ?, senha = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nome, $usuario, $senha_hash, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, usuario = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nome, $usuario, $user_id);
        }

        if ($stmt->execute()) {
            $msg = "Dados atualizados com sucesso!";
        } else {
            $msg = "Erro ao atualizar: " . $conn->error;
        }
    }
}

$stmt = $conn->prepare("SELECT nome, usuario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Facil - Editar Conta</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 60px 20px;
            text-align: center;
            color: #4e4039;
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
        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            padding: 10px 20px;
            background-color: #a1866f;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #8b6f5a;
        }
        .btn-delete {
            background-color: #d9534f;
        }
        .btn-delete:hover {
            background-color: #c9302c;
        }
        .mensagem {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
    <script>
        function confirmarExclusao() {
            return confirm("⚠️ ATENÇÃO: Esta ação é irreversível. Todos os seus dados e clientes serão excluídos. Deseja realmente excluir sua conta?");
        }
    </script>
</head>
<body>
    <div class="banner">
        <h1><i class="fas fa-user-cog"></i> Editar Conta</h1>
        <p>Atualize suas informações ou exclua sua conta</p>
    </div>

    <form method="POST">
        <input type="text" name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" readonly>
        <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" placeholder="Nome completo" required>
        <input type="password" name="senha" placeholder="Nova senha (deixe em branco para manter)">
        <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha">
        <button type="submit" class="btn"><i class="fas fa-save"></i> Salvar alterações</button>
    </form>

    <?php if (isset($msg)): ?>
        <p class="mensagem" style="color: <?= strpos($msg, 'sucesso') !== false ? 'green' : 'red' ?>;">
            <?= $msg ?>
        </p>
    <?php endif; ?>

    <div style="text-align:center; margin-top: 30px;">
        <a href="delete_user.php" onclick="return confirmarExclusao();" class="btn btn-delete">
            <i class="fas fa-user-slash"></i> Excluir minha conta
        </a>
    </div>

    <div style="text-align:center; margin-top: 20px;">
        <a href="index.php" class="btn">
            <i class="fas fa-arrow-left"></i> Voltar ao menu
        </a>
    </div>
</body>
</html>
