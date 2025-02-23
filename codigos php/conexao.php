<?php
$servername = "localhost"; // Nome do servidor, geralmente "localhost"
$username = "root"; // Nome de usuário do MySQL
$password = ""; // Senha do MySQL (geralmente vazio no XAMPP)
$dbname = "clinica"; // Nome do banco de dados

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
