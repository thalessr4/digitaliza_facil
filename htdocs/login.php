<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, senha, is_admin FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];

            if ($user['is_admin']) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }

    $erro = "Usuário ou senha incorretos.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Digitaliza Facil - Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-banner {
            background: linear-gradient(to right, #decab6, #c9b4a1);
            padding: 60px 20px;
            text-align: center;
            color: #4e4039;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            border-bottom: 4px solid #b89e88;
            animation: fadeIn 1s ease-out;
        }

        .login-banner h1 {
            font-size: 2.5em;
            margin: 0;
            animation: fadeIn 1.2s ease-out;
        }

        .login-banner p {
            font-size: 1.2em;
            margin-top: 10px;
            animation: fadeIn 1.4s ease-out;
        }

        form {
            animation: fadeIn 1.6s ease-out;
        }

        .register-link {
            margin-top: 15px;
            animation: fadeIn 1.8s ease-out;
        }

        .register-link a {
            color: #4e4039;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-banner">
        <h1><i class="fas fa-lock"></i> Bem-vindo ao Digitaliza Fácil</h1>
        <p>Faça login para acessar o sistema</p>
    </div>
    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuário" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>
    </form>
    <div class="register-link">
        <p>Não tem uma conta? <a href="register_user.php">Cadastre-se aqui</a></p>
    </div>
    <?php if (isset($erro)) echo "<p style='color:red; text-align:center;'>$erro</p>"; ?>
</body>
</html>
