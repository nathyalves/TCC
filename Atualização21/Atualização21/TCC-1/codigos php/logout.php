<?php
session_start();
session_destroy();
header('Location: login.php');
exit();
?>
?>
<?php include 'cabecalho.php'; ?>
<!-- Conteúdo da página -->
<?php include 'rodape.php'; ?>