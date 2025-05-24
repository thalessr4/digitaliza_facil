<?php
$host = 'sql102.infinityfree.com';
$user = 'if0_39018459';
$pass = 'Ldfl3535';
$dbname = 'if0_39018459_digitaliza123';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>