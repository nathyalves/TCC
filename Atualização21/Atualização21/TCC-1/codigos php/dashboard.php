<?php
require_once ('cabecalho.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

echo "Bem-vindo, " . $_SESSION['usuario'];
?>

<a href="cadastro_usuario.php">Cadastrar Novo Usuário</a>

<a href="logout.php">Logout</a>

<?php require_once ('rodape.php'); ?>