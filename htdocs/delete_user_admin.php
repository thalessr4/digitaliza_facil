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

// Prevenir exclusão de si mesmo
if ($_SESSION['user_id'] == $id) {
    header("Location: admin.php?msg=Você não pode excluir a si mesmo.");
    exit;
}

// Excluir os clientes vinculados ao usuário
$stmt = $conn->prepare("DELETE FROM clientes WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Excluir o usuário
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php?msg=Usuário excluído com sucesso.");
} else {
    header("Location: admin.php?msg=Erro ao excluir usuário.");
}
exit;
?>
