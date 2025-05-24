<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=clientes.csv");

// Escreve BOM UTF-8 para o Excel abrir corretamente
echo "\xEF\xBB\xBF";

$output = fopen("php://output", "w");

// Cabeçalho da planilha
fputcsv($output, ["Nome", "Telefone", "E-mail", "Endereço"], ';');

$stmt = $conn->prepare("SELECT nome, telefone, email, endereco FROM clientes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Linhas da planilha
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row, ';');
}

fclose($output);
exit;
?>
