<?php
// register_user.php - Cadastro de novo usuário
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($senha !== $confirmar_senha) {
        $msg = "As senhas não coincidem. Por favor, digite novamente.";
    } else {
        // Verifica se o usuário já existe
        $check = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $check->bind_param("s", $usuario);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "Este nome de usuário já está em uso. Por favor, escolha outro.";
        } else {
            // Prossegue com o cadastro
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, usuario, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $usuario, $senha_hash);

            if ($stmt->execute()) {
                $msg = "Usuário cadastrado com sucesso! Agora você pode fazer login.";
            } else {
                $msg = "Erro ao cadastrar: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Facil - Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 60px 20px;
            text-align: center;
            color: #4e4039;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            border-bottom: 4px solid #b89e88;
            animation: fadeIn 1s ease-out;
        }

        .register-banner h1 {
            font-size: 2.5em;
            margin: 0;
            animation: fadeIn 1.2s ease-out;
        }

        .register-banner p {
            font-size: 1.2em;
            margin-top: 10px;
            animation: fadeIn 1.4s ease-out;
        }

        form {
            animation: fadeIn 1.6s ease-out;
            max-width: 400px;
            margin: 30px auto;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #a1866f;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #8b6f5a;
        }

        .btn-voltar {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #a1866f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            animation: fadeIn 1.8s ease-out;
        }

        .btn-voltar:hover {
            background-color: #8b6f5a;
        }

        .mensagem {
            font-weight: bold;
            margin-top: 20px;
            animation: fadeIn 2s ease-out;
        }
    </style>
</head>
<body>
    <div class="register-banner">
        <h1><i class="fas fa-user-plus"></i> Criar nova conta</h1>
        <p>Cadastre-se para acessar o sistema</p>
    </div>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome completo" required><br>
        <input type="text" name="usuario" placeholder="Nome de usuário" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required><br>
        <button type="submit">Cadastrar</button>
    </form>

    <?php if (isset($msg)): ?>
        <p class="mensagem" style="color: <?= strpos($msg, 'sucesso') !== false ? 'green' : 'red' ?>;">
            <?= $msg ?>
        </p>
    <?php endif; ?>

    <a href="login.php" class="btn-voltar">Voltar para o login</a>
</body>
</html>
